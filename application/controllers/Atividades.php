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
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'faturarAtribuicao')) {
            echo json_encode(['result' => false, 'message' => 'Sem permissão.']);
            return;
        }

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $valor = $this->input->post('valor');
        $descricao = $this->input->post('descricao');
        $data_vencimento = date('Y-m-d');

        // Identify table and user column
        $table = '';
        $user_col = 'instrutor_id'; // default
        switch ($type) {
            case 'course':
                $table = 'curso_instrutores';
                break;
            case 'trip':
                $table = 'viagem_instrutores';
                break;
            case 'training':
                $table = 'treinos_agendados';
                $user_col = 'staff_id'; // Verify if this is correct provided previous knowledge, if not default to instructor_id or similar
                break;
        }

        // Fetch the activity linkage to get the instructor ID
        $activity = $this->db->where('id', $id)->get($table)->row();
        if (!$activity) {
            echo json_encode(['result' => false, 'message' => 'Atividade não encontrada.']);
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

        // Create Expense (Lancamento)
        $data = [
            'descricao' => $descricao,
            'valor' => $valor,
            'data_vencimento' => $data_vencimento,
            'data_pagamento' => $data_vencimento,
            'baixado' => 1,
            'cliente_fornecedor' => 'Instrutor: ' . $instrutor_nome,
            'forma_pgto' => 'Dinheiro',
            'tipo' => 'despesa',
            'anexo' => null,
            'clientes_id' => null, // Correct field name
            'pagar_usuario_id' => $instrutor_id, // Link to the instructor user
            'usuarios_id' => $this->session->userdata('id_admin'), // The admin creating the payment
        ];

        $this->load->model('mapos_model');
        if ($this->mapos_model->add('lancamentos', $data)) {
            // Update Activity to Paid
            $this->db->where('id', $id);
            $this->db->update($table, ['status_pagamento' => 'pago']);

            log_info('Gerou pagamento para atividade ID: ' . $id . ' (' . $type . ')');

            echo json_encode(['result' => true, 'message' => 'Pagamento gerado com sucesso.']);
        } else {
            echo json_encode(['result' => false, 'message' => 'Erro ao gerar lançamento.']);
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
