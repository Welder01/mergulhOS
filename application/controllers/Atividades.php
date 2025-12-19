<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Atividades extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logado')) {
            redirect(base_url('index.php/login'));
        }

        $this->load->model('atividades_model');
        $this->data['menuAtividades'] = 'atividades';
    }

    public function atualizar_aceite()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $id = $this->input->post('id');
        $type = $this->input->post('type'); // course, trip, training
        $status = $this->input->post('status'); // 1 = Accept, 0 = Reject

        if (!$id || !$type || !isset($status)) {
            echo json_encode(['result' => false, 'message' => 'Dados inválidos.']);
            return;
        }

        $table = '';
        $id_column = 'id';

        switch ($type) {
            case 'course':
                $table = 'curso_instrutores';
                // Note: curso_instrutores uses 'id' as primary key usually? Checking schema...
                // Previous edits suggested ID field differences. 
                // Let's assume 'id' based on typical CI patterns, but double check.
                // In Fix_schema2, I didn't see the ID column name for courses.
                // Standard Model `add` usually uses array.
                // `curso_instrutores` usually has `id` or composite primary key.
                // Let's check `Curso_instrutores_model`. it uses `id`.
                break;
            case 'trip':
                $table = 'viagem_instrutores';
                break;
            case 'training':
                $table = 'treinos_agendados';
                break;
            default:
                echo json_encode(['result' => false, 'message' => 'Tipo inválido.']);
                return;
        }

        $data = ['aceite' => $status];

        $this->db->where('id', $id);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            echo json_encode(['result' => true, 'message' => 'Status atualizado com sucesso.']);
        } else {
            echo json_encode(['result' => false, 'message' => 'Erro ao atualizar status.']);
        }
    }

    public function faturar_atividade()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aLancamento')) {
            echo json_encode(['result' => false, 'message' => 'Você não tem permissão para gerar lançamentos.']);
            return;
        }

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $valor = $this->input->post('valor');
        $descricao = $this->input->post('descricao');
        $data_vencimento = date('Y-m-d');

        // Identify table and user column
        $table = '';
        $user_col = 'usuario_id';
        switch ($type) {
            case 'course':
                $table = 'curso_instrutores';
                break;
            case 'trip':
                $table = 'viagem_instrutores';
                break;
            case 'training':
                $table = 'treinos_agendados';
                $user_col = 'instrutor_id';
                break;
            default:
                echo json_encode(['result' => false, 'message' => 'Tipo inválido.']);
                return;
        }

        // Fetch activity
        $activity = $this->db->where('id', $id)->get($table)->row();
        if (!$activity) {
            echo json_encode(['result' => false, 'message' => 'Atividade não encontrada.']);
            return;
        }

        // DOUBLE CHECK: Prevent duplicates
        if ($activity->status_pagamento == 'pago' || !empty($activity->lancamento_id)) {
            echo json_encode(['result' => false, 'message' => 'Esta atividade JÁ FOI PAGA. Atualize a página.']);
            return;
        }

        // Get Instructor/User Details
        $instrutor_id = isset($activity->$user_col) ? $activity->$user_col : null;
        if ($type == 'training' && !isset($activity->$user_col) && isset($activity->instrutor_id)) {
            $instrutor_id = $activity->instrutor_id;
        }

        $instrutor_nome = 'Instrutor';
        if ($instrutor_id) {
            $user = $this->db->where('idUsuarios', $instrutor_id)->get('usuarios')->row();
            if ($user) {
                $instrutor_nome = $user->nome;
            }
        }

        // Fix Value format (150,00 -> 150.00)
        $valor = str_replace(',', '.', $valor);
        if (!is_numeric($valor)) {
            // Fallback cleanup just in case
            $valor = preg_replace('/[^0-9.]/', '', $valor);
        }

        // Create Expense (Lancamento)
        $data = [
            'descricao' => $descricao,
            'valor' => $valor,
            'data_vencimento' => $data_vencimento,
            'data_pagamento' => $data_vencimento,
            'baixado' => 1,
            'cliente_fornecedor' => 'Instrutor: ' . $instrutor_nome,
            'forma_pgto' => $this->input->post('forma_pgto') ?: 'Dinheiro',
            'tipo' => 'despesa',
            'anexo' => null,
            'clientes_id' => null,
            'pagar_usuario_id' => $instrutor_id,
            'usuarios_id' => $this->session->userdata('id_admin'),
        ];

        $this->load->model('mapos_model');

        // HEALING LOGIC: Check for ORPHAN payments (identical duplicates) before creating new
        $this->db->where('descricao', $descricao);
        $this->db->where('valor', $valor);
        $this->db->where('data_vencimento', $data_vencimento);
        $this->db->where('tipo', 'despesa');
        if ($instrutor_id) {
            $this->db->where('pagar_usuario_id', $instrutor_id);
        }
        // Check only recent ones or all? All is safer to find the lost one.
        $orphan = $this->db->order_by('idLancamentos', 'DESC')->get('lancamentos')->row();

        if ($orphan) {
            $idLancamento = $orphan->idLancamentos;
            log_info('HEALING: Encontrado pagamento órfão (ID: ' . $idLancamento . '). Reconectando em vez de duplicar.');
        } else {
            $idLancamento = $this->mapos_model->add('lancamentos', $data);
        }

        if ($idLancamento) {
            // Update Activity
            $this->db->where('id', $id);
            $updated = $this->db->update($table, [
                'status_pagamento' => 'pago',
                'lancamento_id' => $idLancamento
            ]);

            if (!$updated) {
                log_info('ERRO AO ATUALIZAR ATIVIDADE APÓS PAGAMENTO: ID ' . $id);
                echo json_encode(['result' => false, 'message' => 'Erro ao vincular pagamento à atividade.']);
                return;
            }

            // VERIFICATION
            $check = $this->db->where('id', $id)->get($table)->row();
            if (empty($check->lancamento_id)) {
                log_info('CRITICAL: Pagamento gerado (' . $idLancamento . ') mas COLUMN lancamento_id NÃO SALVOU na tabela ' . $table);
                echo json_encode(['result' => false, 'message' => 'Erro crítico: O sistema não conseguiu vincular o pagamento. Execute a correção de banco de dados.']);
                return;
            }

            log_info('Gerou pagamento p/ ativ. ID: ' . $id . ' Lanc: ' . $idLancamento . ' Valor: ' . $valor);
            echo json_encode(['result' => true, 'message' => 'Pagamento gerado com sucesso.']);
        } else {
            echo json_encode(['result' => false, 'message' => 'Erro ao inserir lançamento financeiro.']);
        }
    }

    public function estornar_pagamento()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eEstorno')) {
            echo json_encode(['result' => false, 'message' => 'Você não tem permissão para estornar pagamentos.']);
            return;
        }

        $id = $this->input->post('id');
        $type = $this->input->post('type');

        if (!$id || !$type) {
            echo json_encode(['result' => false, 'message' => 'Dados incompletos.']);
            return;
        }

        $table = '';
        switch ($type) {
            case 'course':
                $table = 'curso_instrutores';
                break;
            case 'trip':
                $table = 'viagem_instrutores';
                break;
            case 'training':
                $table = 'treinos_agendados';
                break;
            default:
                echo json_encode(['result' => false, 'message' => 'Tipo inválido.']);
                return;
        }

        $activity = $this->db->where('id', $id)->get($table)->row();

        if (!$activity) {
            echo json_encode(['result' => false, 'message' => 'Atividade não encontrada.']);
            return;
        }

        $deleted = false;

        // 1. MASSIVE CLEANUP: Reconstruct details to delete orphans (Run this unconditionally first)
        $orphans_deleted = 0;
        try {
            // We need to match the logic from faturar_atividades in JS/PHP
            $user_col = 'usuario_id';
            if ($type == 'training') {
                $user_col = 'instrutor_id';
            }

            $instrutor_id = isset($activity->$user_col) ? $activity->$user_col : null;
            if ($type == 'training' && !$instrutor_id && isset($activity->instrutor_id)) {
                $instrutor_id = $activity->instrutor_id;
            }

            $user_name = 'Instrutor';
            if ($instrutor_id) {
                $u_row = $this->db->where('idUsuarios', $instrutor_id)->get('usuarios')->row();
                if ($u_row) {
                    $user_name = $u_row->nome;
                }
            }

            $activity_name = '';
            if ($type == 'course') {
                // Fetch from cursos
                if (isset($activity->curso_id)) {
                    $row = $this->db->where('id', $activity->curso_id)->get('cursos')->row();
                    if ($row)
                        $activity_name = $row->nome_curso;
                }
            } elseif ($type == 'trip') {
                // Fetch from viagens
                if (isset($activity->viagem_id)) {
                    $row = $this->db->where('id', $activity->viagem_id)->get('viagens')->row();
                    if ($row)
                        $activity_name = $row->nome_viagem;
                }
            } elseif ($type == 'training') {
                // For training, it might just be the date or modality name if available
            }

            $search_desc = '';
            $search_val = 0; // Use 0 to ignore value check if unsafe

            $search_desc = '';
            $search_val = 0; // Use 0 to ignore value check if unsafe

            if ($type == 'trip') {
                if ($activity_name) {
                    $search_desc = 'Pagamento Trip - ' . $activity_name;
                    $search_val = isset($activity->preco) ? $activity->preco : 0;
                }
            } elseif ($type == 'course') {
                if ($activity_name) {
                    $search_desc = 'Pagamento Course - ' . $activity_name;
                    $search_val = isset($activity->preco) ? $activity->preco : 0;
                }
            } elseif ($type == 'training') {
                if (isset($activity->data_aula)) {
                    $search_desc = 'Pagamento Training - ' . $activity->data_aula;
                    if (isset($activity->valor_pagamento))
                        $search_val = $activity->valor_pagamento;
                    elseif (isset($activity->preco))
                        $search_val = $activity->preco;
                }
            }

            if ($search_desc && $instrutor_id) {
                // Aggressively delete matching orphans
                $this->db->where('descricao', $search_desc);
                $this->db->where('pagar_usuario_id', $instrutor_id);
                $this->db->where('tipo', 'despesa');

                if ($search_val > 0) {
                    // strict check on value? maybe risky with floats, but let's try
                    // $this->db->where('valor', $search_val);
                }

                $this->db->delete('lancamentos');
                $orphans_deleted = $this->db->affected_rows();
                if ($orphans_deleted > 0) {
                    log_info("Estorno Massive Cleanup: Deleted $orphans_deleted orphans for $search_desc");
                }
            }
        } catch (Exception $e) {
            log_info('MASSIVE CLEANUP ERROR: ' . $e->getMessage());
        }

        // 2. Direct delete of linked ID (if exists and wasn't caught by cleanup)
        if (!empty($activity->lancamento_id)) {
            $this->db->where('idLancamentos', $activity->lancamento_id);
            if ($this->db->delete('lancamentos')) {
                $deleted = true;
            }
        } else {
            // If no linked ID, we consider it "deleted" if we found orphans or if we just need to reset status
            $deleted = true;
        }

        if ($deleted || $orphans_deleted > 0) {
            $msg = 'Pagamento estornado com sucesso.';

            // Reset activity
            $this->db->where('id', $id);
            $this->db->update($table, [
                'status_pagamento' => 'pendente',
                'lancamento_id' => null
            ]);

            if ($orphans_deleted > 0) {
                $msg .= " (Sistema removeu $orphans_deleted duplicatas encontradas).";
            }

            echo json_encode(['result' => true, 'message' => $msg]);
        } else {
            // Even if nothing was deleted (maybe already gone), we ensure status is reset
            $this->db->where('id', $id);
            $this->db->update($table, [
                'status_pagamento' => 'pendente',
                'lancamento_id' => null
            ]);
            echo json_encode(['result' => true, 'message' => 'Atividade marcada como pendente.']);
        }
    }

    public function cancelar_atribuicao()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'faturarAtribuicao')) {
            echo json_encode(['result' => false, 'message' => 'Sem permissão.']);
            return;
        }

        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $table = '';
        switch ($type) {
            case 'course':
                $table = 'curso_instrutores';
                break;
            case 'trip':
                $table = 'viagem_instrutores';
                break;
            case 'training':
                $table = 'treinos_agendados';
                break;
            default:
                echo json_encode(['result' => false, 'message' => 'Tipo inválido.']);
                return;
        }

        // Get details for audit before deleting
        $activity = $this->db->where('id', $id)->get($table)->row();

        if ($this->db->delete($table, ['id' => $id])) {
            log_info('Cancelou atribuição da atividade ID: ' . $id . ' (' . $type . ')');
            echo json_encode(['result' => true, 'message' => 'Atribuição cancelada com sucesso.']);
        } else {
            echo json_encode(['result' => false, 'message' => 'Erro ao cancelar atribuição.']);
        }
    }

    public function index()
    {
        $usuario_id = $this->session->userdata('id_admin');

        $this->data['cursos'] = $this->atividades_model->getMeusCursos($usuario_id);
        $this->data['viagens'] = $this->atividades_model->getMinhasViagens($usuario_id);
        $this->data['treinos'] = $this->atividades_model->getMeusTreinos($usuario_id);
        $this->data['lancamentos'] = $this->atividades_model->getMeusLancamentos($usuario_id);

        $this->data['view'] = 'atividades/minhas_atividades';
        return $this->layout();
    }
}
