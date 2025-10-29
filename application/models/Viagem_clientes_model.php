<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Viagem_clientes_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'viagem_clientes';
        $this->primary_key = 'id';
    }

    public function getByIdWithViagem($id)
    {
        $this->db->select('viagem_clientes.*, viagens.nome_viagem, viagens.preco_pessoa');
        $this->db->from('viagem_clientes');
        $this->db->join('viagens', 'viagens.id = viagem_clientes.viagem_id');
        $this->db->where('viagem_clientes.id', $id);
        return $this->db->get()->row();
    }

    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_clientes.*, clientes.nomeCliente, clientes.celular');
        $this->db->from('viagem_clientes');
        $this->db->join('clientes', 'clientes.idClientes = viagem_clientes.cliente_id');
        $this->db->where('viagem_clientes.viagem_id', $viagem_id);
        return $this->db->get()->result();
    }
}