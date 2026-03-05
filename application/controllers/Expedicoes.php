<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Expedicoes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('expedicoes_model');
        $this->data['menuBilhetagem'] = 'Expedicoes';
    }

    public function index() {
        $this->gerenciar();
    }

    public function gerenciar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar expedições.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('expedicoes/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->expedicoes_model->count('expedicoes');
        $this->data['configuration']['per_page'] = 10;

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->expedicoes_model->get('expedicoes', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'expedicoes/gerenciar';
        return $this->layout();
    }

    public function adicionar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar expedições.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('titulo', 'Título', 'trim|required');
        $this->form_validation->set_rules('data_ida', 'Data Ida', 'trim|required');
        $this->form_validation->set_rules('data_volta', 'Data Volta', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'titulo' => $this->input->post('titulo'),
                'moeda_base' => $this->input->post('moeda_base'),
                'data_ida' => $this->input->post('data_ida'),
                'data_volta' => $this->input->post('data_volta'),
                'taxa_parque_unitaria' => $this->input->post('taxa_parque_unitaria'),
                'preco_saida_barco_dia' => $this->input->post('preco_saida_barco_dia'),
                'cancelamento_tipo' => $this->input->post('cancelamento_tipo'),
                'cancelamento_limite' => $this->input->post('cancelamento_limite'),
                'transporte_id' => $this->input->post('transporte_id'),
            ];

            if ($this->expedicoes_model->add('expedicoes', $data) == true) {
                $this->session->set_flashdata('success', 'Expedição adicionada com sucesso!');
                redirect(site_url('expedicoes/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'expedicoes/adicionarExpedicao';
        return $this->layout();
    }

    public function editar() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar expedições.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('titulo', 'Título', 'trim|required');
        $this->form_validation->set_rules('data_ida', 'Data Ida', 'trim|required');
        $this->form_validation->set_rules('data_volta', 'Data Volta', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'titulo' => $this->input->post('titulo'),
                'moeda_base' => $this->input->post('moeda_base'),
                'data_ida' => $this->input->post('data_ida'),
                'data_volta' => $this->input->post('data_volta'),
                'taxa_parque_unitaria' => $this->input->post('taxa_parque_unitaria'),
                'preco_saida_barco_dia' => $this->input->post('preco_saida_barco_dia'),
                'cancelamento_tipo' => $this->input->post('cancelamento_tipo'),
                'cancelamento_limite' => $this->input->post('cancelamento_limite'),
                'transporte_id' => $this->input->post('transporte_id'),
            ];

            if ($this->expedicoes_model->edit('expedicoes', $data, 'idExpedicao', $this->input->post('idExpedicao')) == true) {
                $this->session->set_flashdata('success', 'Expedição editada com sucesso!');
                redirect(site_url('expedicoes/editar/') . $this->input->post('idExpedicao'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['result'] = $this->expedicoes_model->getById($this->uri->segment(3));
        $this->data['view'] = 'expedicoes/editarExpedicao';
        return $this->layout();
    }

    public function excluir() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir expedições.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir expedição.');
            redirect(site_url('expedicoes/gerenciar/'));
        }

        if ($this->expedicoes_model->delete('expedicoes', 'idExpedicao', $id)) {
            $this->session->set_flashdata('success', 'Expedição excluída com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir expedição. Verifique se há bilhetes vinculados.');
        }

        redirect(site_url('expedicoes/gerenciar/'));
    }
}