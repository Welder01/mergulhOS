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
        $this->db->select('viagem_clientes.*, clientes.nomeCliente, clientes.documento as cpf, clientes.telefone, clientes.celular');
        $this->db->from('viagem_clientes');
        $this->db->join('clientes', 'clientes.idClientes = viagem_clientes.cliente_id');
        $this->db->where('viagem_clientes.viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function getClientesComEquipamentos($viagem_id)
    {
        $this->db->select('viagem_clientes.*, clientes.nomeCliente, clientes.atestado_medico_validade, clientes.tamanho_colete, clientes.peso_lastro, clientes.tamanho_neoprene, clientes.tamanho_nadadeira');
        $this->db->from('viagem_clientes');
        $this->db->join('clientes', 'clientes.idClientes = viagem_clientes.cliente_id');
        $this->db->where('viagem_clientes.viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function getByCliente($cliente_id, $pesquisa = null)
    {
        $this->db->select('viagem_clientes.*, viagens.*, viagens.status as status_viagem');
        $this->db->from('viagem_clientes');
        $this->db->join('viagens', 'viagens.id = viagem_clientes.viagem_id');
        $this->db->where('viagem_clientes.cliente_id', $cliente_id);
        if ($pesquisa) {
            $this->db->like('viagens.nome_viagem', $pesquisa);
        }
        $this->db->order_by('viagens.data_partida', 'desc');
        return $this->db->get()->result();
    }

    public function isClienteInViagem($viagem_id, $cliente_id)
    {
        $this->db->where('viagem_id', $viagem_id);
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function add($table, $data, $returnId = false)
    {
        $this->db->insert($this->table, $data);
        if ($this->db->affected_rows() == '1') {
            if ($returnId == true) {
                return $this->db->insert_id($this->table);
            }
            return true;
        }
        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows() >= 0;
    }
}