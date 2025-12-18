<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Curso_instrutores_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByCurso($curso_id)
    {
        $this->db->select('curso_instrutores.*, usuarios.nome as nome_instrutor, u_cad.nome as nome_cadastrou');
        $this->db->from('curso_instrutores');
        $this->db->join('usuarios', 'usuarios.idUsuarios = curso_instrutores.usuario_id');
        $this->db->join('usuarios as u_cad', 'u_cad.idUsuarios = curso_instrutores.usuario_cadastrou_id', 'left');
        $this->db->where('curso_id', $curso_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        return $this->db->insert('curso_instrutores', $data);
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        return $this->db->update($table, $data);
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('curso_instrutores')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('curso_instrutores');
        return $this->db->affected_rows() > 0;
    }

    public function isInstrutorInCurso($curso_id, $usuario_id)
    {
        $this->db->where('curso_id', $curso_id);
        $this->db->where('usuario_id', $usuario_id);
        return $this->db->get('curso_instrutores')->num_rows() > 0;
    }
}