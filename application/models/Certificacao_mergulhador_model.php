<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificacao_mergulhador_model extends CI_Model {
    
    public function getByCliente($cliente_id) {
        $this->db->where('cliente_id', $cliente_id);
        $this->db->order_by('data_emissao', 'DESC');
        return $this->db->get('certificacoes_mergulhador')->result();
    }
    
    public function get($id) {
        $this->db->where('id', $id);
        return $this->db->get('certificacoes_mergulhador')->row();
    }
    
    public function add($data) {
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        return $this->db->insert('certificacoes_mergulhador', $data);
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('certificacoes_mergulhador', $data);
    }
    
    public function delete($id) {
        // Primeiro obtém os dados do arquivo para remover o arquivo físico
        $certificacao = $this->get($id);
        if ($certificacao && $certificacao->arquivo) {
            $file_path = FCPATH . 'uploads/certificados/' . $certificacao->arquivo;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $this->db->where('id', $id);
        return $this->db->delete('certificacoes_mergulhador');
    }
}