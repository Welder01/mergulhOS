<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Restricao_alimentar_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByCliente($cliente_id)
    {
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->get('restricoes_alimentares')->result();
    }

    public function add($data)
    {
        return $this->db->insert('restricoes_alimentares', $data);
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('restricoes_alimentares')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('restricoes_alimentares');

        return $this->db->affected_rows() > 0;
    }
}