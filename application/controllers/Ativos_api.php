<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ativos_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Verifica se o usuário está logado para permitir sincronização
        if ((!session_id()) || (!$this->session->userdata('logado'))) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Não autorizado']));
            exit;
        }
        $this->load->model('ativos_model');
    }

    /**
     * Retorna todos os ativos para popular o IndexedDB local
     */
    public function sync_down() {
        $ativos = $this->ativos_model->get('ativos', '*', '', 0, 0);
        
        $response = [
            'timestamp' => time(),
            'ativos' => $ativos
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * Recebe logs e alterações feitas offline
     */
    public function sync_up() {
        $input = json_decode($this->input->raw_input_stream, true);

        if (!$input || !isset($input['logs'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Dados inválidos']));
            return;
        }

        $logs_processados = 0;
        $erros = [];

        foreach ($input['logs'] as $log) {
            // Atualiza o status do ativo se houver alteração
            if (isset($log['novo_status']) && $log['ativo_id']) {
                $this->ativos_model->edit('ativos', ['status' => $log['novo_status']], 'idAtivo', $log['ativo_id']);
            }

            // Registra o log
            $data_log = [
                'ativo_id' => $log['ativo_id'],
                'usuario_id_acao' => $this->session->userdata('id_admin'),
                'acao' => $log['acao'], // ex: 'checkin_offline', 'checkout_offline'
                'detalhes' => $log['detalhes'] . ' (Sincronizado em ' . date('d/m/Y H:i') . ')',
                'data_acao' => $log['data_acao'], // Data original da ação offline
                'data_sincronizacao' => date('Y-m-d H:i:s')
            ];

            if ($this->db->insert('ativos_logs', $data_log)) {
                $logs_processados++;
            } else {
                $erros[] = "Falha ao inserir log para ativo ID: " . $log['ativo_id'];
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success', 'processed' => $logs_processados, 'errors' => $erros]));
    }
}