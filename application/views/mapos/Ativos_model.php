<?php
class Ativos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idAtivo', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id) {
        $this->db->where('idAtivo', $id);
        $this->db->limit(1);
        return $this->db->get('ativos')->row();
    }

    public function add($table, $data) {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id();
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() >= 0) {
            return true;
        }
        return false;
    }

    public function delete($table, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        return false;
    }

    public function count($table) {
        return $this->db->count_all($table);
    }
    
    public function log_acao($ativo_id, $acao, $detalhes) {
        $data = [
            'ativo_id' => $ativo_id,
            'usuario_id_acao' => $this->session->userdata('id_admin'),
            'acao' => $acao,
            'detalhes' => $detalhes,
            'data_acao' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('ativos_logs', $data);
    }
}