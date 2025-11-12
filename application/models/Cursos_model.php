<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cursos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->like('nome_curso', $where);
        }

        $query = $this->db->get();

        $result = ! $one ? $query->result() : $query->row();

        return $result;
    }

    public function getById($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get('cursos')->row();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id($table);
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        return $this->db->affected_rows() >= 0;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return $this->db->affected_rows() == '1';
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function adicionar_aluno($curso_id, $cliente_id)
    {
        $this->load->model('curso_alunos_model');
        $curso = $this->getById($curso_id);

        if ($this->curso_alunos_model->isAlunoInCurso($curso_id, $cliente_id)) {
            log_info("Tentativa de adicionar aluno duplicado. Cliente ID: {$cliente_id}, Curso ID: {$curso_id}");
            return ['success' => true, 'message' => 'Este aluno já está inscrito neste curso.'];
        }

        if ($curso->vagas > 0) {
            $data = [
                'curso_id' => $curso_id,
                'cliente_id' => $cliente_id,
                'data_inscricao' => date('Y-m-d H:i:s'),
                'status_aluno' => 'inscrito',
            ];

            if ($this->curso_alunos_model->add($data)) {
                // Decrementa o número de vagas
                $this->db->set('vagas', 'vagas - 1', false);
                $this->db->where('id', $curso_id);
                $this->db->update('cursos');

                log_info('Adicionou aluno ID: ' . $cliente_id . ' ao curso ID: ' . $curso_id);
                return ['success' => true, 'message' => 'Aluno adicionado com sucesso!'];
            } else {
                $db_error = $this->db->error();
                log_info("Falha ao adicionar aluno ao banco de dados. Cliente ID: {$cliente_id}, Curso ID: {$curso_id}. Erro do DB: " . ($db_error['message'] ?? ''));
                return ['success' => false, 'message' => 'Erro ao adicionar aluno.'];
            }
        } else {
            return ['success' => false, 'message' => 'Não há mais vagas disponíveis para este curso.'];
        }
    }

    public function remover_aluno($inscricao_id)
    {
        $this->load->model('curso_alunos_model');
        $aluno = $this->curso_alunos_model->getById($inscricao_id);

        if (!$aluno) {
            return ['success' => false, 'message' => 'Inscrição do aluno não encontrada.'];
        }

        if ($this->curso_alunos_model->delete($inscricao_id)) {
            // Incrementa o número de vagas
            $this->db->set('vagas', 'vagas + 1', false);
            $this->db->where('id', $aluno->curso_id);
            $this->db->update('cursos');

            log_info('Removeu aluno ID: ' . $inscricao_id . ' do curso ID: ' . $aluno->curso_id);
            return ['success' => true, 'message' => 'Aluno removido com sucesso!'];
        } else {
            $db_error = $this->db->error();
            log_info("Falha ao remover aluno do banco de dados. Inscrição ID: {$inscricao_id}. Erro do DB: " . ($db_error['message'] ?? ''));
            return ['success' => false, 'message' => 'Erro ao remover aluno.'];
        }
    }

    public function getCursosByCliente($cliente_id)
    {
        $this->db->select('cursos.*, curso_alunos.data_inscricao, curso_alunos.status_aluno');
        $this->db->from('cursos');
        $this->db->join('curso_alunos', 'curso_alunos.curso_id = cursos.id');
        $this->db->where('curso_alunos.cliente_id', $cliente_id);
        $this->db->order_by('cursos.data_inicio', 'DESC');
        return $this->db->get()->result();
    }
}