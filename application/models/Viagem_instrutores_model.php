<?php
class Viagem_instrutores_model extends CI_Model
{
    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_instrutores.*, usuarios.nome as nome_instrutor, usuarios.tamanho_colete, usuarios.peso_lastro, usuarios.tamanho_neoprene, usuarios.tamanho_nadadeira');
        $this->db->from('viagem_instrutores');
        $this->db->join('usuarios', 'usuarios.idUsuarios = viagem_instrutores.usuario_id');
        $this->db->where('viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        return $this->db->insert('viagem_instrutores', $data);
    }

    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('viagem_instrutores', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('viagem_instrutores');
    }

    public function isInstrutorInViagem($viagem_id, $usuario_id)
    {
        $this->db->where('viagem_id', $viagem_id);
        $this->db->where('usuario_id', $usuario_id);
        return $this->db->get('viagem_instrutores')->num_rows() > 0;
    }
}