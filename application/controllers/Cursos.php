<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cursos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cursos_model');
        $this->load->model('curso_instrutores_model');
        $this->load->model('curso_alunos_model');
        $this->load->model('curso_modulos_model');
        $this->load->model('curso_modulo_instrutores_model');
        $this->load->model('curso_requisitos_model');
        $this->load->model('certificacao_mergulhador_model');
        $this->data['menuCursos'] = 'cursos'; // Para ativar o menu "Cursos"
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cursos.');
            redirect(base_url());
        }

        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('cursos/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->cursos_model->count('cursos');
        if ($pesquisa) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}";
            $this->data['configuration']['first_url'] = base_url('index.php/cursos') . "?pesquisa={$pesquisa}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->cursos_model->get('cursos', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'cursos/cursos';
        return $this->layout();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome_curso', 'Nome do Curso', 'trim|required');
        $this->form_validation->set_rules('data_inicio', 'Data de Início', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data_inicio = $this->input->post('data_inicio');
            $data_fim = $this->input->post('data_fim');

            $preco = $this->input->post('preco');
            $preco = str_replace('.', '', $preco); // Remove separador de milhares
            $preco = str_replace(',', '.', $preco); // Troca vírgula por ponto decimal

            try {
                $data_inicio = DateTime::createFromFormat('d/m/Y', $data_inicio)->format('Y-m-d');
                $data_fim = $data_fim ? DateTime::createFromFormat('d/m/Y', $data_fim)->format('Y-m-d') : null;
            } catch (Exception $e) {
                $data_inicio = date('Y-m-d');
                $data_fim = null;
            }

            $data = [
                'nome_curso' => set_value('nome_curso'),
                'descricao' => set_value('descricao'),
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'status' => set_value('status'),
                'preco' => $preco,
                'data_cadastro' => date('Y-m-d H:i:s'),
            ];

            if ($this->cursos_model->add('cursos', $data) == true) {
                $this->session->set_flashdata('success', 'Curso adicionado com sucesso!');
                log_info('Adicionou um curso. ID: ' . $this->db->insert_id());
                redirect(site_url('cursos/gerenciar'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'cursos/adicionarCurso';
        return $this->layout();
    }

    public function editar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->cursos_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Curso não encontrado ou parâmetro inválido.');
            redirect('cursos/gerenciar');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome_curso', 'Nome do Curso', 'trim|required');
        $this->form_validation->set_rules('data_inicio', 'Data de Início', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data_inicio = $this->input->post('data_inicio');
            $data_fim = $this->input->post('data_fim');

            $preco = $this->input->post('preco');
            $preco = str_replace('.', '', $preco); // Remove separador de milhares
            $preco = str_replace(',', '.', $preco); // Troca vírgula por ponto decimal

            try {
                $data_inicio = DateTime::createFromFormat('d/m/Y', $data_inicio)->format('Y-m-d');
                $data_fim = $data_fim ? DateTime::createFromFormat('d/m/Y', $data_fim)->format('Y-m-d') : null;
            } catch (Exception $e) {
                $data_inicio = date('Y-m-d');
                $data_fim = null;
            }

            $data = [
                'nome_curso' => $this->input->post('nome_curso'),
                'descricao' => $this->input->post('descricao'),
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'status' => $this->input->post('status'),
                'preco' => $preco,
            ];

            if ($this->cursos_model->edit('cursos', $data, 'id', $this->input->post('id')) == true) {
                $this->session->set_flashdata('success', 'Curso editado com sucesso!');
                log_info('Editou um curso. ID: ' . $this->input->post('id'));
                redirect(site_url('cursos/editar/') . $this->input->post('id'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->cursos_model->getById($this->uri->segment(3));
        $this->data['view'] = 'cursos/editarCurso';
        return $this->layout();
    }

    public function visualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('cursos/gerenciar');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->uri->segment(3);
        $this->data['custom_error'] = '';
        $this->data['result'] = $this->cursos_model->getById($curso_id);
        $this->data['instrutores'] = $this->curso_instrutores_model->getByCurso($curso_id);
        $this->data['alunos'] = $this->curso_alunos_model->getByCurso($curso_id);
        $modulos = $this->curso_modulos_model->getModulosByCurso($curso_id);
        foreach ($modulos as $modulo) {
            $modulo->instrutores = $this->curso_modulo_instrutores_model->getInstrutoresByModulo($modulo->id);
        }
        $this->data['modulos'] = $modulos;
        $this->data['requisitos'] = $this->curso_requisitos_model->getByCurso($curso_id);

        // Carregar tipos de certificação da configuração
        $this->load->model('mapos_model');
        $tipos_certificacao_str = $this->mapos_model->get_ci_config('certificacao_tipos');
        $this->data['tipos_certificacao'] = !empty($tipos_certificacao_str) ? explode(',', $tipos_certificacao_str) : [];

        $this->data['view'] = 'cursos/visualizarCurso';
        return $this->layout();
    }

    public function excluir()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir cursos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir curso.');
            redirect(site_url('cursos/gerenciar/'));
        }

        // Excluir instrutores e alunos vinculados (ON DELETE CASCADE nas migrations já cuida disso)
        if ($this->cursos_model->delete('cursos', 'id', $id) == true) {
            log_info('Removeu um curso. ID: ' . $id);
            $this->session->set_flashdata('success', 'Curso excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar excluir o curso.');
        }
        redirect(site_url('cursos/gerenciar/'));
    }

    public function adicionar_instrutor()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('usuario_id', 'Instrutor', 'trim|required');
        $this->form_validation->set_rules('curso_id', 'Curso', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
        } else {
            $curso_id = $this->input->post('curso_id');
            $usuario_id = $this->input->post('usuario_id');

            if ($this->curso_instrutores_model->isInstrutorInCurso($curso_id, $usuario_id)) {
                $this->session->set_flashdata('error', 'Este instrutor já está atribuído a este curso.');
            } else {
                $data = [
                    'curso_id' => $curso_id,
                    'usuario_id' => $usuario_id,
                    'data_atribuicao' => date('Y-m-d H:i:s'),
                ];

                if ($this->curso_instrutores_model->add($data)) {
                    $this->session->set_flashdata('success', 'Instrutor adicionado com sucesso!');
                    log_info('Adicionou instrutor ID: ' . $usuario_id . ' ao curso ID: ' . $curso_id);
                } else {
                    $this->session->set_flashdata('error', 'Erro ao adicionar instrutor.');
                }
            }
        }
        redirect('cursos/visualizar/' . $this->input->post('curso_id') . '?tab=tab2');
    }

    public function remover_instrutor($id = null)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        if ($id == null || ! is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! O instrutor não existe.');
            redirect(base_url());
        }

        $instrutor = $this->curso_instrutores_model->getById($id);
        if ($instrutor && $this->curso_instrutores_model->delete($id)) {
            $this->session->set_flashdata('success', 'Instrutor removido com sucesso!');
            log_info('Removeu instrutor ID: ' . $id . ' do curso ID: ' . $instrutor->curso_id);
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover instrutor.');
        }
        redirect('cursos/visualizar/' . $instrutor->curso_id . '?tab=tab2');
    }

    public function adicionar_aluno()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cliente_id', 'Aluno', 'trim|required');
        $this->form_validation->set_rules('curso_id', 'Curso', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
            log_info('Falha na validação ao adicionar aluno: ' . validation_errors());
            redirect('cursos/visualizar/' . $this->input->post('curso_id') . '#alunos');
            return;
        }

        $curso_id = $this->input->post('curso_id');
        $cliente_id = $this->input->post('cliente_id');

        if ($this->curso_alunos_model->isAlunoInCurso($curso_id, $cliente_id)) {
            $this->session->set_flashdata('error', 'Este aluno já está inscrito neste curso.');
            log_info("Tentativa de adicionar aluno duplicado. Cliente ID: {$cliente_id}, Curso ID: {$curso_id}");
        } else {
            $data = [
                'curso_id' => $curso_id,
                'cliente_id' => $cliente_id,
                'data_inscricao' => date('Y-m-d H:i:s'),
                'status_aluno' => 'inscrito',
            ];

            if ($this->curso_alunos_model->add($data)) {
                $this->session->set_flashdata('success', 'Aluno adicionado com sucesso!');
                log_info('Adicionou aluno ID: ' . $cliente_id . ' ao curso ID: ' . $curso_id);
            } else {
                $this->session->set_flashdata('error', 'Erro ao adicionar aluno. Verifique os logs.');
                log_info("Falha ao adicionar aluno ao banco de dados. Cliente ID: {$cliente_id}, Curso ID: {$curso_id}. Erro do DB: " . $this->db->error()['message']);
            }
        }
        redirect('cursos/visualizar/' . $this->input->post('curso_id') . '?tab=tab3');
    }

    private function verificarRequisitosAluno($curso_id, $cliente_id)
    {
        $requisitos_curso = $this->curso_requisitos_model->getByCurso($curso_id);
        if (empty($requisitos_curso)) {
            return true; // Curso não tem requisitos
        }

        $certificacoes_aluno = $this->certificacao_mergulhador_model->getByCliente($cliente_id);
        $certificacoes_aluno_nomes = array_map(function ($cert) {
            return $cert->nome_certificacao;
        }, $certificacoes_aluno);

        foreach ($requisitos_curso as $requisito) {
            if (!in_array($requisito->requisito, $certificacoes_aluno_nomes)) {
                $this->session->set_flashdata('error', 'O aluno não possui o requisito necessário: <strong>' . html_escape($requisito->requisito) . '</strong>.');
                return false;
            }
        }

        return true;
    }

    public function remover_aluno($id = null)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        if ($id == null || ! is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! O aluno não existe.');
            redirect(base_url());
        }

        $aluno = $this->curso_alunos_model->getById($id);
        if ($aluno && $this->curso_alunos_model->delete($id)) {
            $this->session->set_flashdata('success', 'Aluno removido com sucesso!');
            log_info('Removeu aluno ID: ' . $id . ' do curso ID: ' . $aluno->curso_id);
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover aluno.');
        }
        redirect('cursos/visualizar/' . $aluno->curso_id . '?tab=tab3');
    }

    // Métodos para Módulos do Curso
    public function adicionar_modulo()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->input->post('curso_id');
        $data = [
            'curso_id' => $curso_id,
            'nome' => $this->input->post('nome_modulo'),
            'descricao' => $this->input->post('descricao_modulo'),
        ];

        if ($this->curso_modulos_model->add('curso_modulos', $data)) {
            $this->session->set_flashdata('success', 'Módulo adicionado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar módulo.');
        }
        redirect('cursos/visualizar/' . $curso_id . '?tab=modulos');
    }

    public function remover_modulo($id)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir módulos.');
            redirect(base_url());
        }

        $modulo = $this->curso_modulos_model->getById($id);
        if ($modulo && $this->curso_modulos_model->delete('curso_modulos', 'id', $id)) {
            $this->session->set_flashdata('success', 'Módulo removido com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover módulo.');
        }
        redirect('cursos/visualizar/' . $modulo->curso_id . '?tab=modulos');
    }

    public function toggle_conclusao_modulo()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $modulo_id = $this->input->post('id');
        $concluido = $this->input->post('concluido') === 'true' ? 1 : 0;

        $data = [
            'concluido' => $concluido,
            'data_conclusao' => $concluido ? date('Y-m-d H:i:s') : null,
        ];

        if ($this->curso_modulos_model->edit('curso_modulos', $data, 'id', $modulo_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true]));
        }
        return $this->output->set_status_header(500)->set_output(json_encode(['success' => false]));
    }

    public function adicionar_instrutor_modulo()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->input->post('curso_id');
        $data = [
            'curso_modulo_id' => $this->input->post('curso_modulo_id'),
            'usuario_id' => $this->input->post('usuario_id'),
        ];

        if ($this->curso_modulo_instrutores_model->add('curso_modulo_instrutores', $data)) {
            $this->session->set_flashdata('success', 'Instrutor adicionado ao módulo com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar instrutor ao módulo.');
        }
        redirect('cursos/visualizar/' . $curso_id . '?tab=modulos');
    }

    public function remover_instrutor_modulo($id)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $relacao = $this->curso_modulo_instrutores_model->getById($id);
        $modulo = $this->curso_modulos_model->getById($relacao->curso_modulo_id);

        $this->curso_modulo_instrutores_model->delete('curso_modulo_instrutores', 'id', $id);
        redirect('cursos/visualizar/' . $modulo->curso_id . '?tab=modulos');
    }

    // Métodos para Requisitos do Curso
    public function adicionar_requisito()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->input->post('curso_id');
        $data = [
            'curso_id' => $curso_id,
            'requisito' => $this->input->post('requisito'),
        ];

        if ($this->curso_requisitos_model->add($data)) {
            $this->session->set_flashdata('success', 'Requisito adicionado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar requisito.');
        }
        redirect('cursos/visualizar/' . $curso_id . '?tab=requisitos');
    }

    public function remover_requisito($id)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir requisitos.');
            redirect(base_url());
        }

        $requisito = $this->curso_requisitos_model->getById($id);
        if ($requisito && $this->curso_requisitos_model->delete('curso_requisitos', 'id', $id)) {
            $this->session->set_flashdata('success', 'Requisito removido com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover requisito.');
        }
        redirect('cursos/visualizar/' . $requisito->curso_id . '?tab=requisitos');
    }

    public function ajax_check_requisitos()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $curso_id = $this->input->post('curso_id');
        $cliente_id = $this->input->post('cliente_id');

        $this->load->model('certificacao_mergulhador_model');

        $requisitos_curso = $this->curso_requisitos_model->getByCurso($curso_id);

        $missing_requisitos = [];
        if (!empty($requisitos_curso)) {
            $certificacoes_aluno = $this->certificacao_mergulhador_model->getByCliente($cliente_id);
            $certificacoes_aluno_nomes = array_map(function ($cert) {
                return $cert->nome_certificacao;
            }, $certificacoes_aluno);

            foreach ($requisitos_curso as $requisito) {
                if (!in_array($requisito->requisito, $certificacoes_aluno_nomes)) {
                    $missing_requisitos[] = html_escape($requisito->requisito);
                }
            }
        }

        $status = empty($missing_requisitos) ? 'success' : 'failure';

        if ($status === 'failure') {
            $logMessage = "Falha na verificação de requisitos para o cliente ID: {$cliente_id} no curso ID: {$curso_id}. Requisitos faltantes: " . implode(', ', $missing_requisitos);
            log_info($logMessage);
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => $status,
            'missing' => $missing_requisitos,
            'csrf_token' => $this->security->get_csrf_hash()
        ]));
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->db->select('idUsuarios, nome');
            $this->db->limit(5);
            $this->db->like('nome', $q);
            $this->db->where('situacao', 1);
            $query = $this->db->get('usuarios');
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $row_set[] = ['label' => $row['nome'], 'id' => $row['idUsuarios']];
                }
                echo json_encode($row_set);
            }
        }
    }

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->db->select('idClientes, nomeCliente');
            $this->db->limit(5);
            $this->db->like('nomeCliente', $q);
            $query = $this->db->get('clientes');
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $row_set[] = ['label' => $row['nomeCliente'], 'id' => $row['idClientes']];
                }
                echo json_encode($row_set);
            }
        }
    }

    public function autoCompleteCurso()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select('id, nome_curso, data_inicio, preco');
            $this->db->like('nome_curso', $q);
            $this->db->limit(5);
            $query = $this->db->get('cursos');
            $result = array_map(function ($curso) {
                return [
                    'id' => $curso->id,
                    'text' => 'ID: ' . $curso->id . ' | Curso: ' . $curso->nome_curso . ' | Início: ' . date('d/m/Y', strtotime($curso->data_inicio)),
                    'preco' => $curso->preco,
                ];
            }, $query->result());
            echo json_encode($result);
        }
    }
}