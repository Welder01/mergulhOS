<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Viagens_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'viagens';
        $this->primary_key = 'id';
    }
    public function getViagensByCliente($cliente_id)
    {
        $this->db->select('viagens.*, viagem_clientes.id as viagem_cliente_id, viagem_clientes.viagem_id, viagem_clientes.status_pagamento');
        $this->db->from('viagens');
        $this->db->join('viagem_clientes', 'viagem_clientes.viagem_id = viagens.id');
        $this->db->where('viagem_clientes.cliente_id', $cliente_id);
        return $this->db->get()->result();
    }

    public function getViagensDisponiveis($cliente_id)
    {
        $subquery = $this->db->select('viagem_id')->from('viagem_clientes')->where('cliente_id', $cliente_id)->get_compiled_select();
        
        $this->db->select('*');
        $this->db->from('viagens');
        $this->db->where("id NOT IN ($subquery)", null, false);
        return $this->db->get()->result();
    }

    public function getById($id)
    {
        $this->db->select('viagens.*');
        $this->db->from('viagens');
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->like('nome_viagem', $where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();

        return $result;
    }

    public function count($table, $where = '')
    {
        $this->db->from($table);
        if ($where) {
            $this->db->like('nome_viagem', $where);
        }
        return $this->db->count_all_results();
    }
    public function autoCompleteViagem($q)
    {
        $this->db->select('id, nome_viagem, data_partida, preco_pessoa');
        $this->db->like('nome_viagem', $q);
        $this->db->limit(5);
        $query = $this->db->get('viagens');
        if ($query->num_rows() > 0) {
            $result = array_map(function ($viagem) {
                return [
                    'id' => $viagem->id,
                    'label' => 'ID: ' . $viagem->id . ' | Viagem: ' . $viagem->nome_viagem . ' | Partida: ' . date('d/m/Y', strtotime($viagem->data_partida)),
                    'preco' => $viagem->preco_pessoa,
                ];
            }, $query->result());
            return $result;
        }
        return [];
    }

    public function adicionar_cliente($viagem_id, $cliente_id, $data = [])
    {
        $this->load->model('viagem_clientes_model');
        $viagem = $this->getById($viagem_id);

        if ($this->viagem_clientes_model->isClienteInViagem($viagem_id, $cliente_id)) {
            // Atualiza o propósito se fornecido, mesmo se já inscrito
            if (isset($data['proposito'])) {
                $inscricao = $this->viagem_clientes_model->getInscricao($viagem_id, $cliente_id);
                if ($inscricao) {
                    $this->viagem_clientes_model->edit('viagem_clientes', ['proposito' => $data['proposito']], 'id', $inscricao->id);
                }
            }
            log_info("Tentativa de adicionar cliente duplicado à viagem. Cliente ID: {$cliente_id}, Viagem ID: {$viagem_id}");
            return ['success' => true, 'message' => 'Este cliente já está inscrito nesta viagem.'];
        }

        if ($viagem->vagas > 0) {
            $default_data = [
                'viagem_id' => $viagem_id,
                'cliente_id' => $cliente_id,
            ];
            // Garante que o propósito e outros dados extras sejam incluídos
            $insert_data = array_merge($default_data, $data);

            if ($this->viagem_clientes_model->add('viagem_clientes', $insert_data)) {
                // Decrementa o número de vagas
                $this->db->set('vagas', 'vagas - 1', false);
                $this->db->where('id', $viagem_id);
                $this->db->update('viagens');

                log_info("Adicionou cliente ID: {$cliente_id} à viagem ID: {$viagem_id}");
                return ['success' => true, 'message' => 'Cliente adicionado à viagem!'];
            } else {
                $db_error = $this->db->error();
                log_info("Falha ao adicionar cliente à viagem. Cliente ID: {$cliente_id}, Viagem ID: {$viagem_id}. Erro do DB: " . ($db_error['message'] ?? ''));
                return ['success' => false, 'message' => 'Ocorreu um erro ao adicionar o cliente.'];
            }
        } else {
            return ['success' => false, 'message' => 'Não há mais vagas disponíveis para esta viagem.'];
        }
    }

    public function remover_cliente($viagem_cliente_id)
    {
        $this->load->model('viagem_clientes_model');
        $cliente_viagem = $this->viagem_clientes_model->getById($viagem_cliente_id);

        if (!$cliente_viagem) {
            return ['success' => false, 'message' => 'Inscrição do cliente na viagem não encontrada.'];
        }

        if ($this->viagem_clientes_model->delete('viagem_clientes', 'id', $viagem_cliente_id)) {
            // Incrementa o número de vagas
            $this->db->set('vagas', 'vagas + 1', false);
            $this->db->where('id', $cliente_viagem->viagem_id);
            $this->db->update('viagens');

            log_info("Removeu cliente da viagem. Inscrição ID: {$viagem_cliente_id}");
            return ['success' => true, 'message' => 'Cliente removido da viagem!'];
        } else {
            return ['success' => false, 'message' => 'Ocorreu um erro ao remover o cliente.'];
        }
    }
}

/* End of file viagens_model.php */
/* Location: ./application/models/viagens_model.php */