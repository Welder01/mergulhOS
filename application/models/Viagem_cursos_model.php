<?php
class Viagem_cursos_model extends CI_Model
{
    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_cursos.*, cursos.nome_curso');
        $this->db->from('viagem_cursos');
        $this->db->join('cursos', 'cursos.id = viagem_cursos.curso_id');
        $this->db->where('viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        return $this->db->insert('viagem_cursos', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('viagem_cursos');
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('viagem_cursos')->row();
    }

    public function clearViagemCursos($viagem_id)
    {
        $this->db->where('viagem_id', $viagem_id);
        return $this->db->delete('viagem_cursos');
    }
}