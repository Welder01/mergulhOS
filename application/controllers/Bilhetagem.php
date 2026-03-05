<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Bilhetagem extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('bilhetagem_model');
        $this->load->model('mapos_model');
        $this->data['menuBilhetagem'] = 'Bilhetagem';
    }

    public function index() {
        $this->gerenciar();
    }

    public function gerenciar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar bilhetes.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('bilhetagem/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->bilhetagem_model->count('bilhetes');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->bilhetagem_model->getBilhetes($this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'bilhetagem/gerenciar';
        return $this->layout();
    }

    public function adicionar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para emitir bilhetes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('bilhetagem') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            // Dados do formulário
            $expedicao_id = $this->input->post('expedicao_id');
            $moeda = $this->input->post('moeda_venda');
            $cotacao = $this->input->post('cotacao_venda');
            $valor_original = $this->input->post('valor_original'); // Valor na moeda selecionada
            
            // Conversão para BRL
            $valor_bilhete_brl = ($moeda == 'BRL') ? $valor_original : ($valor_original * $cotacao);

            // Busca dados da expedição para calcular adicionais
            $expedicao = $this->bilhetagem_model->getExpedicaoById($expedicao_id);
            
            $qtd_dias_navegacao = $this->input->post('qtd_dias_navegacao') ?: 0;
            $pagou_taxa_parque = $this->input->post('pagou_taxa_parque') ? 1 : 0;
            $estadia_estendida = $this->input->post('estadia_estendida') ? 1 : 0;
            
            // Cálculos Adicionais
            $total_navegacao = $qtd_dias_navegacao * $expedicao->preco_saida_barco_dia;
            $total_parque = $pagou_taxa_parque ? $expedicao->taxa_parque_unitaria : 0;
            $taxa_servico = $this->input->post('taxa_servico_emissao');
            $bagagem_extra = $this->input->post('valor_bagagem_extra');

            $valor_total_lancamento = $valor_bilhete_brl + $total_navegacao + $total_parque + $taxa_servico + $bagagem_extra;

            $data = [
                'expedicao_id' => $expedicao_id,
                'cliente_id' => $this->input->post('cliente_id'),
                'viagem_id' => $this->input->post('viagem_id') ?: null,
                'tipo_transporte' => $this->input->post('tipo_transporte'),
                'empresa_emissora' => $this->input->post('empresa_emissora'),
                'codigo_bilhete' => $this->input->post('codigo_bilhete'),
                'numero_seguranca' => $this->input->post('numero_seguranca'),
                'assento' => $this->input->post('assento'),
                'moeda_venda' => $moeda,
                'cotacao_venda' => $cotacao,
                'valor_bilhete_brl' => $valor_bilhete_brl,
                'taxa_servico_emissao' => $taxa_servico,
                'valor_bagagem_extra' => $bagagem_extra,
                'qtd_dias_navegacao' => $qtd_dias_navegacao,
                'pagou_taxa_parque' => $pagou_taxa_parque,
                'estadia_estendida' => $estadia_estendida,
                'status' => 'ativo'
            ];

            if ($id = $this->bilhetagem_model->add('bilhetes', $data)) {
                
                // Lançamento Financeiro Automático
                if ($valor_total_lancamento > 0) {
                    $this->load->model('financeiro_model');
                    $lancamento = [
                        'descricao' => 'Emissão Bilhete #' . $id . ' - Exp: ' . $expedicao->titulo,
                        'valor' => $valor_total_lancamento,
                        'data_vencimento' => date('Y-m-d'),
                        'data_pagamento' => date('Y-m-d'),
                        'baixado' => 1,
                        'cliente_fornecedor' => $this->input->post('nomeCliente'),
                        'forma_pgto' => 'Dinheiro', // Poderia vir do form
                        'tipo' => 'receita',
                        'usuarios_id' => $this->session->userdata('id_admin')
                    ];
                    $this->financeiro_model->add('lancamentos', $lancamento);
                }

                $this->session->set_flashdata('success', 'Bilhete emitido com sucesso!');
                redirect(site_url('bilhetagem/visualizar/') . $id);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['expedicoes'] = $this->bilhetagem_model->getExpedicoesAtivas();
        $this->data['view'] = 'bilhetagem/adicionarBilhete';
        return $this->layout();
    }

    public function visualizar() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vBilhete')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar bilhetes.');
            redirect(base_url());
        }

        $this->data['result'] = $this->bilhetagem_model->getById($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['view'] = 'bilhetagem/visualizarBilhete';
        return $this->layout();
    }

    public function imprimirVoucher() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado.');
            redirect('mapos');
        }

        $this->data['result'] = $this->bilhetagem_model->getById($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->load->view('bilhetagem/imprimirVoucher', $this->data);
    }

    // AJAX para buscar cotação
    public function get_cotacao() {
        $moeda = $this->input->post('moeda');
        echo json_encode(['valor' => $this->bilhetagem_model->getCotacao($moeda)]);
    }
    
    // AJAX para buscar detalhes da expedição
    public function get_expedicao_detalhes() {
        $id = $this->input->post('id');
        $expedicao = $this->bilhetagem_model->getExpedicaoById($id);
        echo json_encode($expedicao);
    }
}
