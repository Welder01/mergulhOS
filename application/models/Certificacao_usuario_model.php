<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificacao_usuario_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByUsuario($usuario_id)
    {
        $this->db->where('usuario_id', $usuario_id);
        return $this->db->get('certificacoes_usuario')->result();
    }

    public function add($data)
    {
        return $this->db->insert('certificacoes_usuario', $data);
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('certificacoes_usuario')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('certificacoes_usuario');
    }
}