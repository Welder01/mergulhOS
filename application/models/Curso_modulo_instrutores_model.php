<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Curso_modulo_instrutores_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        return $this->db->get($table, $perpage, $start)->result();
    }

    public function getById($id)
    {
        return $this->db->get_where('curso_modulo_instrutores', ['id' => $id])->row();
    }

    public function getInstrutoresByModulo($modulo_id)
    {
        $this->db->select('cmi.*, u.nome as nome_instrutor');
        $this->db->from('curso_modulo_instrutores as cmi');
        $this->db->join('usuarios as u', 'u.idUsuarios = cmi.usuario_id');
        $this->db->where('cmi.curso_modulo_id', $modulo_id);
        return $this->db->get()->result();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id($table);
        }
        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return $this->db->affected_rows() == '1';
    }
}