<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Treinos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('id', 'desc');
        if ($where) {
            $this->db->where($where);
        }
        if ($perpage) {
            $this->db->limit($perpage, $start);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get('treinos_config')->row();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }

        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return $this->db->affected_rows() == '1';
    }

    public function getAgendamentoById($id)
    {
        $this->db->select('ta.*, tc.nome as nome_treino, c.nomeCliente as nome_cliente, u.nome as nome_instrutor');
        $this->db->from('treinos_agendados as ta');
        $this->db->join('treinos_config as tc', 'tc.id = ta.config_id');
        $this->db->join('clientes as c', 'c.idClientes = ta.cliente_id');
        $this->db->join('usuarios as u', 'u.idUsuarios = ta.instrutor_id', 'left');
        $this->db->where('ta.id', $id);
        return $this->db->get()->row();
    }
}