<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Curso_modulos_model extends CI_Model
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
        return $this->db->get_where('curso_modulos', ['id' => $id])->row();
    }

    public function getModulosByCurso($curso_id)
    {
        return $this->db->get_where('curso_modulos', ['curso_id' => $curso_id])->result();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id($table);
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        return $this->db->affected_rows() >= 0;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return $this->db->affected_rows() == '1';
    }
}