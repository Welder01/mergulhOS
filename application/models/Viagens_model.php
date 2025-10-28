<?php
class Viagens_model extends CI_Model
{
    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields . ', cursos.nome_curso');
        $this->db->from($table);
        $this->db->join('cursos', 'cursos.id = viagens.curso_id', 'left');
        $this->db->order_by('id', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->like('nome_viagem', $where);
        }
        $query = $this->db->get();
        return !$one ? $query->result() : $query->row();
    }

    public function getById($id)
    {
        $this->db->select('viagens.*, cursos.nome_curso');
        $this->db->from('viagens');
        $this->db->join('cursos', 'cursos.id = viagens.curso_id', 'left');
        $this->db->where('viagens.id', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
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
        return $this->db->delete($table);
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }
}