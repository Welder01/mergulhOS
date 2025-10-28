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
        redirect('cursos/visualizar/' . $this->input->post('curso_id') . '#instrutores');
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
        redirect('cursos/visualizar/' . $instrutor->curso_id . '#instrutores');
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
        } else {
            $curso_id = $this->input->post('curso_id');
            $cliente_id = $this->input->post('cliente_id');

            if ($this->curso_alunos_model->isAlunoInCurso($curso_id, $cliente_id)) {
                $this->session->set_flashdata('error', 'Este aluno já está inscrito neste curso.');
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
                    $this->session->set_flashdata('error', 'Erro ao adicionar aluno.');
                }
            }
        }
        redirect('cursos/visualizar/' . $this->input->post('curso_id') . '#alunos');
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
        redirect('cursos/visualizar/' . $aluno->curso_id . '#alunos');
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
}