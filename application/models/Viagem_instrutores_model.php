<?php
class Viagem_instrutores_model extends CI_Model
{
    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_instrutores.*, usuarios.nome as nome_instrutor');
        $this->db->from('viagem_instrutores');
        $this->db->join('usuarios', 'usuarios.idUsuarios = viagem_instrutores.usuario_id');
        $this->db->where('viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        return $this->db->insert('viagem_instrutores', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('viagem_instrutores');
    }
}