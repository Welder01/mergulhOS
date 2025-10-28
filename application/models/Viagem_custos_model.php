<?php
class Viagem_custos_model extends CI_Model
{
    public function getByViagem($viagem_id)
    {
        $this->db->where('viagem_id', $viagem_id);
        return $this->db->get('viagem_custos')->result();
    }

    public function add($data)
    {
        return $this->db->insert('viagem_custos', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('viagem_custos');
    }
}