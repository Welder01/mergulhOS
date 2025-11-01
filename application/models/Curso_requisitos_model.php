<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Curso_requisitos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByCurso($curso_id)
    {
        $this->db->where('curso_id', $curso_id);
        $query = $this->db->get('curso_requisitos');
        if ($query) {
            return $query->result();
        }
        // Retorna um array vazio em caso de falha na consulta para evitar erros.
        return [];
    }

    public function add($data)
    {
        $this->db->insert('curso_requisitos', $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id('curso_requisitos');
        }
        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return $this->db->affected_rows() == '1';
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('curso_requisitos')->row();
    }
}