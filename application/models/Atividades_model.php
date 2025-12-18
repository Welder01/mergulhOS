<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Atividades_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMeusCursos($usuario_id)
    {
        $this->db->select('cursos.*, curso_instrutores.data_atribuicao');
        $this->db->from('cursos');
        $this->db->join('curso_instrutores', 'curso_instrutores.curso_id = cursos.id');
        $this->db->where('curso_instrutores.usuario_id', $usuario_id);
        $this->db->order_by('cursos.data_inicio', 'DESC');
        return $this->db->get()->result();
    }

    public function getMinhasViagens($usuario_id)
    {
        // Adjust fields based on viaggio_instrutores table (assuming standard fields based on controller analysis)
        $this->db->select('viagens.*, viagem_instrutores.status_pagamento, viagem_instrutores.proposito');
        $this->db->from('viagens');
        $this->db->join('viagem_instrutores', 'viagem_instrutores.viagem_id = viagens.id');
        $this->db->where('viagem_instrutores.usuario_id', $usuario_id);
        $this->db->order_by('viagens.data_partida', 'DESC');
        return $this->db->get()->result();
    }

    public function getMeusLancamentos($usuario_id)
    {
        $this->db->select('*');
        $this->db->from('lancamentos');
        $this->db->where('pagar_usuario_id', $usuario_id);
        $this->db->order_by('data_vencimento', 'ASC');
        return $this->db->get()->result();
    }
}
