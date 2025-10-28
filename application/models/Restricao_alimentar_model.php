<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Restricao_alimentar_model extends CI_Model {
    
    public function getByCliente($cliente_id) {
        $this->db->where('cliente_id', $cliente_id);
        $this->db->order_by('data_cadastro', 'DESC');
        return $this->db->get('restricoes_alimentares')->result();
    }
    
    public function get($id) {
        $this->db->where('id', $id);
        return $this->db->get('restricoes_alimentares')->row();
    }
    
    public function add($data) {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        return $this->db->insert('restricoes_alimentares', $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('restricoes_alimentares');
    }
}