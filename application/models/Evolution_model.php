<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Evolution_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        if ($array == 'desc') {
            $this->db->order_by('id', 'desc');
        } else {
            $this->db->order_by('id', 'asc');
        }
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();

        return $result;
    }

    public function getById($id)
    {
        return $this->db->get_where('evolution_mensagens', ['id' => $id])->row();
    }

    public function add($table, $data, $returnId = false)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            if ($returnId == true) {
                return $this->db->insert_id($table);
            }
            return true;
        }
        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return $this->db->affected_rows() == '1';
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

    public function getContatos($ids, $table, $idField, $fields = '*')
    {
        if (empty($ids)) {
            return [];
        }
        $this->db->select($fields);
        $this->db->where_in($idField, $ids);
        return $this->db->get($table)->result();
    }

    public function getAllContatos($table, $fields = '*')
    {
        $this->db->select($fields);
        return $this->db->get($table)->result();
    }

    public function getClientesByCurso($cursoIds)
    {
        if (empty($cursoIds)) {
            return [];
        }
        $this->db->select('c.*');
        $this->db->from('clientes c');
        $this->db->join('curso_alunos ca', 'c.idClientes = ca.cliente_id');
        $this->db->where_in('ca.curso_id', $cursoIds);
        $this->db->group_by('c.idClientes');
        return $this->db->get()->result();
    }

    public function getClientesByViagem($viagemIds)
    {
        if (empty($viagemIds)) {
            return [];
        }
        $this->db->select('c.*');
        $this->db->from('clientes c');
        $this->db->join('viagem_clientes vc', 'c.idClientes = vc.cliente_id');
        $this->db->where_in('vc.viagem_id', $viagemIds);
        $this->db->group_by('c.idClientes');
        return $this->db->get()->result();
    }

    public function getUsuariosByPermissao($permissoesIds)
    {
        if (empty($permissoesIds)) {
            return [];
        }
        $this->db->select('*');
        $this->db->from('usuarios');
        $this->db->where_in('permissoes_id', $permissoesIds);
        return $this->db->get()->result();
    }

    public function getUsuariosByCurso($cursoIds)
    {
        if (empty($cursoIds)) {
            return [];
        }
        $this->db->select('u.*');
        $this->db->from('usuarios u');
        $this->db->join('curso_instrutores ci', 'u.idUsuarios = ci.usuario_id');
        $this->db->where_in('ci.curso_id', $cursoIds);
        $this->db->group_by('u.idUsuarios');
        return $this->db->get()->result();
    }

    public function getUsuariosByViagem($viagemIds)
    {
        if (empty($viagemIds)) {
            return [];
        }
        $this->db->select('u.*');
        $this->db->from('usuarios u');
        $this->db->join('viagem_instrutores vi', 'u.idUsuarios = vi.usuario_id');
        $this->db->where_in('vi.viagem_id', $viagemIds);
        $this->db->group_by('u.idUsuarios');
        return $this->db->get()->result();
    }

    // --- Eventos / Triggers ---

    public function getEvents()
    {
        $this->db->select('e.*, m.titulo as mensagem_titulo');
        $this->db->from('evolution_eventos e');
        $this->db->join('evolution_mensagens m', 'e.mensagem_id = m.id', 'left');
        $this->db->order_by('e.evento', 'asc');
        return $this->db->get()->result();
    }

    public function updateEvent($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('evolution_eventos', $data);
    }

    public function getEventTrigger($eventName)
    {
        $this->db->select('e.*, m.mensagem');
        $this->db->from('evolution_eventos e');
        $this->db->join('evolution_mensagens m', 'e.mensagem_id = m.id');
        $this->db->where('e.evento', $eventName);
        $this->db->where('e.status', 1); // Only active
        return $this->db->get()->row();
    }
}