<?php
class Viagem_instrutores_model extends CI_Model
{
    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('id', 'desc');
        if ($where) {
            $this->db->where($where);
        }
        if ($perpage) {
            $this->db->limit($perpage, $start);
        }
        $query = $this->db->get();
        $result = !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get('viagem_instrutores')->row();
    }

    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_instrutores.*, usuarios.nome as nome_instrutor, usuarios.cpf as cpf_instrutor, usuarios.telefone as telefone_instrutor, usuarios.tamanho_colete, usuarios.tamanho_neoprene, usuarios.tamanho_nadadeira, usuarios.peso_lastro');
        $this->db->from('viagem_instrutores');
        $this->db->join('usuarios', 'usuarios.idUsuarios = viagem_instrutores.usuario_id');
        $this->db->where('viagem_instrutores.viagem_id', $viagem_id);
        return $this->db->get()->result();
    }

    public function add($data)
    {
        $this->db->insert('viagem_instrutores', $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('viagem_instrutores');
    }

    public function isInstrutorInViagem($viagem_id, $usuario_id)
    {
        $this->db->where('viagem_id', $viagem_id);
        $this->db->where('usuario_id', $usuario_id);
        $query = $this->db->get('viagem_instrutores');
        return $query->num_rows() > 0;
    }
}