<?php
class Viagem_instrutores_model extends CI_Model
{
    public function getById($id)
    {
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get('viagem_instrutores')->row();
    }

    public function getByViagem($viagem_id)
    {
        $this->db->select('viagem_instrutores.*, usuarios.nome as nome_instrutor, usuarios.cpf as cpf_instrutor, usuarios.telefone as telefone_instrutor, usuarios.tamanho_colete, usuarios.tamanho_neoprene, usuarios.tamanho_nadadeira, usuarios.peso_lastro, usuarios.atestado_medico_validade, usuarios.possui_regulador, usuarios.possui_lanterna, usuarios.possui_computador, usuarios.qtd_reguladores, usuarios.qtd_lanterna, usuarios.qtd_computador, viagem_instrutores.hospedagem_quarto_numero, viagem_instrutores.hospedagem_tipo_quarto, viagem_instrutores.hospedagem_numero_camas, viagem_instrutores.detalhes_hospedagem, ativos_bolsas.nome as nome_bolsa, ativos_bolsas.codigo_identificador as codigo_bolsa');
        $this->db->from('viagem_instrutores');
        $this->db->join('usuarios', 'usuarios.idUsuarios = viagem_instrutores.usuario_id', 'left'); // Changed to LEFT JOIN
        $this->db->join('ativos_bolsas', 'ativos_bolsas.idBolsa = viagem_instrutores.numero_bolsa', 'left');
        $this->db->where('viagem_instrutores.viagem_id', $viagem_id);
        $query = $this->db->get();
        if ($query === false) {
            // Loga o erro do banco de dados para depuração
            log_message('error', 'Database error in getByViagem method of Viagem_instrutores_model: ' . $this->db->error()['message']);
            return []; // Retorna um array vazio para evitar erros subsequentes
        }
        return $query->result();
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