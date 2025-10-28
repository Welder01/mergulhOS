<?php
class Viagem_clientes_model extends CI_Model
{
    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_clientes.*, clientes.nomeCliente');
        $this->db->from('viagem_clientes');
        $this->db->join('clientes', 'clientes.idClientes = viagem_clientes.cliente_id');
        $this->db->where('viagem_clientes.viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function getByCliente($cliente_id)
    {
        $this->db->select('viagem_clientes.*, viagens.nome_viagem, viagens.data_partida, viagens.preco_pessoa');
        $this->db->from('viagem_clientes');
        $this->db->join('viagens', 'viagens.id = viagem_clientes.viagem_id');
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        return $this->db->insert('viagem_clientes', $data);
    }

    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('viagem_clientes', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('viagem_clientes');
    }
}