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
        $this->db->select('cursos.*, curso_instrutores.data_atribuicao, curso_instrutores.valor_pagamento, curso_instrutores.tipo_pagamento, curso_instrutores.hora_inicio, curso_instrutores.hora_fim, curso_instrutores.status_pagamento, curso_instrutores.aceite, curso_instrutores.id as id');
        $this->db->from('cursos');
        $this->db->join('curso_instrutores', 'curso_instrutores.curso_id = cursos.id');
        $this->db->where('curso_instrutores.usuario_id', $usuario_id);
        $this->db->order_by('cursos.data_inicio', 'DESC');
        return $this->db->get()->result();
    }

    public function getMinhasViagens($usuario_id)
    {
        // Adjust fields based on viaggio_instrutores table (assuming standard fields based on controller analysis)
        $this->db->select('viagens.*, viagem_instrutores.status_pagamento, viagem_instrutores.proposito, viagem_instrutores.valor_pagamento, viagem_instrutores.tipo_pagamento, viagem_instrutores.aceite, viagem_instrutores.id as id');
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
        $this->db->group_start();
        $this->db->where('pagar_usuario_id', $usuario_id);
        $this->db->or_where('clientes_id', $usuario_id); // Fallback if linked via client_id
        $this->db->group_end();
        $this->db->order_by('data_vencimento', 'DESC');
        return $this->db->get()->result();
    }

    public function getMeusTreinos($usuario_id)
    {
        $this->db->select('treinos_agendados.*, treinos_config.nome as nome_treino');
        $this->db->from('treinos_agendados');
        $this->db->join('treinos_config', 'treinos_config.id = treinos_agendados.config_id');
        $this->db->where('treinos_agendados.instrutor_id', $usuario_id);
        $this->db->order_by('treinos_agendados.data_hora_inicio', 'DESC');
        return $this->db->get()->result();
    }
}
