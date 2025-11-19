<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Treinos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar treinos.');
            redirect(base_url());
        }

        $this->load->model('treinos_model');
    }

    public function index()
    {
        $this->data['results'] = $this->treinos_model->get('treinos_config', '*', '', 100, 0, false);
        $this->data['menuTreinos'] = 'active';
        $this->data['view'] = 'treinos/treinos';

        return $this->layout();
    }

    public function adicionarConfiguracao()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar configurações de treino.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('duracao_minutos', 'Duração', 'required|integer');
        $this->form_validation->set_rules('preco_sem_instrutor', 'Preço Sem Instrutor', 'required');
        $this->form_validation->set_rules('preco_com_instrutor', 'Preço Com Instrutor', 'required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $preco_sem_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_sem_instrutor'));
            $preco_com_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_com_instrutor'));

            $data = [
                'nome' => $this->input->post('nome'),
                'descricao' => $this->input->post('descricao'),
                'duracao_minutos' => $this->input->post('duracao_minutos'),
                'preco_sem_instrutor' => $preco_sem_instrutor,
                'preco_com_instrutor' => $preco_com_instrutor,
                'dias_semana_disponiveis' => implode(',', $this->input->post('dias_semana_disponiveis') ?: []),
                'horario_inicio' => $this->input->post('horario_inicio'),
                'horario_fim' => $this->input->post('horario_fim'),
                'status' => $this->input->post('status'),
            ];

            if ($this->treinos_model->add('treinos_config', $data) == true) {
                $this->session->set_flashdata('success', 'Configuração de treino adicionada com sucesso!');
                log_info('Adicionou uma configuração de treino');
                redirect(site_url('treinos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['menuTreinos'] = 'active';
        $this->data['view'] = 'treinos/adicionarTreino';
        return $this->layout();
    }

    public function editarConfiguracao()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('treinos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar configurações de treino.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('duracao_minutos', 'Duração', 'required|integer');
        $this->form_validation->set_rules('preco_sem_instrutor', 'Preço Sem Instrutor', 'required');
        $this->form_validation->set_rules('preco_com_instrutor', 'Preço Com Instrutor', 'required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $preco_sem_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_sem_instrutor'));
            $preco_com_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_com_instrutor'));

            $data = [
                'nome' => $this->input->post('nome'),
                'descricao' => $this->input->post('descricao'),
                'duracao_minutos' => $this->input->post('duracao_minutos'),
                'preco_sem_instrutor' => $preco_sem_instrutor,
                'preco_com_instrutor' => $preco_com_instrutor,
                'dias_semana_disponiveis' => implode(',', $this->input->post('dias_semana_disponiveis') ?: []),
                'horario_inicio' => $this->input->post('horario_inicio'),
                'horario_fim' => $this->input->post('horario_fim'),
                'status' => $this->input->post('status'),
            ];

            if ($this->treinos_model->edit('treinos_config', $data, 'id', $this->input->post('id')) == true) {
                $this->session->set_flashdata('success', 'Configuração de treino editada com sucesso!');
                log_info('Alterou uma configuração de treino. ID: ' . $this->input->post('id'));
                redirect(site_url('treinos/editarConfiguracao/') . $this->input->post('id'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->treinos_model->getById($this->uri->segment(3));
        $this->data['view'] = 'treinos/editarTreino';
        return $this->layout();
    }

    public function excluirConfiguracao()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir configurações de treino.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir configuração de treino.');
            redirect(site_url('treinos'));
        }

        $this->treinos_model->delete('treinos_config', 'id', $id);
        log_info('Removeu uma configuração de treino. ID: ' . $id);
        $this->session->set_flashdata('success', 'Configuração de treino excluída com sucesso!');
        redirect(site_url('treinos'));
    }
}