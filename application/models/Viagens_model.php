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
}

/* End of file viagens_model.php */
/* Location: ./application/models/viagens_model.php */