<?php
class Bilhetagem_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idBilhete', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        $result =  !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getBilhetes($perpage = 0, $start = 0) {
        $this->db->select('bilhetes.*, clientes.nomeCliente, expedicoes.titulo as expedicao, viagens.nome_viagem');
        $this->db->from('bilhetes');
        $this->db->join('clientes', 'clientes.idClientes = bilhetes.cliente_id');
        $this->db->join('expedicoes', 'expedicoes.idExpedicao = bilhetes.expedicao_id');
        $this->db->join('viagens', 'viagens.id = bilhetes.viagem_id', 'left');
        $this->db->order_by('bilhetes.idBilhete', 'desc');
        $this->db->limit($perpage, $start);
        return $this->db->get()->result();
    }

    public function getById($id) {
        $this->db->select('bilhetes.*, clientes.nomeCliente, clientes.documento, clientes.email, clientes.telefone, expedicoes.titulo as expedicao, expedicoes.data_ida, expedicoes.data_volta, viagens.nome_viagem');
        $this->db->from('bilhetes');
        $this->db->join('clientes', 'clientes.idClientes = bilhetes.cliente_id');
        $this->db->join('expedicoes', 'expedicoes.idExpedicao = bilhetes.expedicao_id');
        $this->db->join('viagens', 'viagens.id = bilhetes.viagem_id', 'left');
        $this->db->where('bilhetes.idBilhete', $id);
        return $this->db->get()->row();
    }

    public function getExpedicoesAtivas() {
        $this->db->where('data_ida >=', date('Y-m-d'));
        return $this->db->get('expedicoes')->result();
    }
    
    public function getExpedicaoById($id) {
        $this->db->where('idExpedicao', $id);
        return $this->db->get('expedicoes')->row();
    }

    public function getCotacao($moeda) {
        $this->db->where('moeda', $moeda);
        $row = $this->db->get('config_cambio')->row();
        return $row ? $row->valor_brl : 1.00;
    }

    public function add($table, $data) {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id();
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        return ($this->db->affected_rows() >= 0);
    }

    public function delete($table, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return ($this->db->affected_rows() == '1');
    }

    public function count($table) {
        return $this->db->count_all($table);
    }
}
