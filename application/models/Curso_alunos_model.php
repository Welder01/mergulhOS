<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Curso_alunos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByCurso($curso_id)
    {
        $this->db->select('curso_alunos.*, clientes.nomeCliente as nome_aluno');
        $this->db->from('curso_alunos');
        $this->db->join('clientes', 'clientes.idClientes = curso_alunos.cliente_id');
        $this->db->where('curso_id', $curso_id);
        return $this->db->get()->result();
    }

    public function getByCliente($cliente_id)
    {
        $this->db->select('curso_alunos.*, cursos.nome_curso, cursos.data_inicio, cursos.preco');
        $this->db->from('curso_alunos');
        $this->db->join('cursos', 'cursos.id = curso_alunos.curso_id');
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        $this->db->insert('curso_alunos', $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        return false;
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('curso_alunos')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('curso_alunos');
        return $this->db->affected_rows() > 0;
    }

    public function isAlunoInCurso($curso_id, $cliente_id)
    {
        $this->db->where('curso_id', $curso_id);
        $this->db->where('cliente_id', $cliente_id);
        return $this->db->get('curso_alunos')->num_rows() > 0;
    }
}