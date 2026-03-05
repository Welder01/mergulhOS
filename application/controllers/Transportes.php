<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Transportes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transportes_model');
        $this->data['menuBilhetagem'] = 'Transportes';
    }

    public function index() {
        $this->gerenciar();
    }

    public function gerenciar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vTransporte')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar transportes.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('transportes/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->transportes_model->count('transportes');
        $this->data['configuration']['per_page'] = 10;

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->transportes_model->get('transportes', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'transportes/gerenciar';
        return $this->layout();
    }

    public function adicionar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aTransporte')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar transportes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        $this->form_validation->set_rules('tipo', 'Tipo', 'trim|required');
        $this->form_validation->set_rules('qtd_assentos', 'Qtd. Assentos', 'trim|required|integer');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
                'tipo' => $this->input->post('tipo'),
                'categoria' => $this->input->post('categoria'),
                'qtd_assentos' => $this->input->post('qtd_assentos'),
                'placa' => $this->input->post('placa'),
                'status' => 1
            ];

            if ($this->transportes_model->add('transportes', $data) == true) {
                $this->session->set_flashdata('success', 'Transporte adicionado com sucesso!');
                redirect(site_url('transportes/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'transportes/adicionarTransporte';
        return $this->layout();
    }

    public function editar() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eTransporte')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar transportes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        $this->form_validation->set_rules('qtd_assentos', 'Qtd. Assentos', 'trim|required|integer');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
                'tipo' => $this->input->post('tipo'),
                'categoria' => $this->input->post('categoria'),
                'qtd_assentos' => $this->input->post('qtd_assentos'),
                'placa' => $this->input->post('placa'),
                'status' => $this->input->post('status')
            ];

            if ($this->transportes_model->edit('transportes', $data, 'idTransporte', $this->input->post('idTransporte')) == true) {
                $this->session->set_flashdata('success', 'Transporte editado com sucesso!');
                redirect(site_url('transportes/editar/') . $this->input->post('idTransporte'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['result'] = $this->transportes_model->getById($this->uri->segment(3));
        $this->data['view'] = 'transportes/editarTransporte';
        return $this->layout();
    }

    public function excluir() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dTransporte')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir transportes.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir transporte.');
            redirect(site_url('transportes/gerenciar/'));
        }

        if ($this->transportes_model->delete('transportes', 'idTransporte', $id)) {
            $this->session->set_flashdata('success', 'Transporte excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir transporte.');
        }

        redirect(site_url('transportes/gerenciar/'));
    }
}
