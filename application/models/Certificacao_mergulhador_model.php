<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificacao_mergulhador_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByCliente($cliente_id)
    {
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->get('certificacoes_mergulhador')->result();
    }

    public function add($data)
    {
        return $this->db->insert('certificacoes_mergulhador', $data);
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('certificacoes_mergulhador')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('certificacoes_mergulhador');

        return $this->db->affected_rows() > 0;
    }
}