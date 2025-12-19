<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Servicos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('servicos_model');
        $this->data['menuServicos'] = 'Serviços';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vServico')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar serviços.');
            redirect(base_url());
        }

        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('servicos/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->servicos_model->count('servicos');
        if ($pesquisa) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}";
            $this->data['configuration']['first_url'] = base_url("index.php/servicos") . "\?pesquisa={$pesquisa}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->servicos_model->get('servicos', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'servicos/servicos';

        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aServico')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar serviços.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('servicos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $preco = $this->input->post('preco');
            $preco = str_replace(',', '', $preco);

            $data = [
                'nome' => set_value('nome'),
                'descricao' => set_value('descricao'),
                'preco' => $preco,
            ];

            if ($this->servicos_model->add('servicos', $data) == true) {
                // --- Evolution API Trigger (servico_criado) ---
                $this->load->model('evolution_model');
                $trigger = $this->evolution_model->getEventTrigger('servico_criado');
                if ($trigger && $trigger->status == 1 && $trigger->mensagem_id) {
                    $this->load->model('mapos_model');
                    $emitente = $this->mapos_model->getEmitente();
                    if ($emitente && !empty($emitente->telefone)) {
                        $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, $data);
                        $this->load->library('evolution_queue');
                        $this->evolution_queue->add($emitente->telefone, $msg_parsed);
                    }
                }

                $this->session->set_flashdata('success', 'Serviço adicionado com sucesso!');
                log_info('Adicionou um serviço');
                redirect(site_url('servicos/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }
        $this->data['view'] = 'servicos/adicionarServico';

        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3)) || !$this->servicos_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Serviço não encontrado ou parâmetro inválido.');
            redirect('servicos/gerenciar');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eServico')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar serviços.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('servicos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $preco = $this->input->post('preco');
            $preco = str_replace(',', '', $preco);
            $data = [
                'nome' => $this->input->post('nome'),
                'descricao' => $this->input->post('descricao'),
                'preco' => $preco,
            ];

            if ($this->servicos_model->edit('servicos', $data, 'idServicos', $this->input->post('idServicos')) == true) {
                // --- Evolution API Trigger (servico_editado) ---
                $this->load->model('evolution_model');
                $trigger = $this->evolution_model->getEventTrigger('servico_editado');
                if ($trigger && $trigger->status == 1 && $trigger->mensagem_id) {
                    $this->load->model('mapos_model');
                    $emitente = $this->mapos_model->getEmitente();
                    if ($emitente && !empty($emitente->telefone)) {
                        $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, $data);
                        $this->load->library('evolution_queue');
                        $this->evolution_queue->add($emitente->telefone, $msg_parsed);
                    }
                }

                $this->session->set_flashdata('success', 'Serviço editado com sucesso!');
                log_info('Alterou um serviço. ID: ' . $this->input->post('idServicos'));
                redirect(site_url('servicos/editar/') . $this->input->post('idServicos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um errro.</p></div>';
            }
        }

        $this->data['result'] = $this->servicos_model->getById($this->uri->segment(3));

        $this->data['view'] = 'servicos/editarServico';

        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dServico')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir serviços.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir serviço.');
            redirect(site_url('servicos/gerenciar/'));
        }

        $this->servicos_model->delete('servicos_os', 'servicos_id', $id);
        $this->servicos_model->delete('servicos', 'idServicos', $id);

        // --- Evolution API Trigger (servico_excluido) ---
        $this->load->model('evolution_model');
        $trigger = $this->evolution_model->getEventTrigger('servico_excluido');
        if ($trigger && $trigger->status == 1 && $trigger->mensagem_id) {
            $this->load->model('mapos_model');
            $emitente = $this->mapos_model->getEmitente();
            if ($emitente && !empty($emitente->telefone)) {
                $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, ['id' => $id]);
                $this->load->library('evolution_queue');
                $this->evolution_queue->add($emitente->telefone, $msg_parsed);
            }
        }

        log_info('Removeu um serviço. ID: ' . $id);

        $this->session->set_flashdata('success', 'Serviço excluido com sucesso!');
        redirect(site_url('servicos/gerenciar/'));
    }
}
