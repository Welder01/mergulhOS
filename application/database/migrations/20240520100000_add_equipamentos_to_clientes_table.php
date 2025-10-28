<?php

class Migration_add_equipamentos_to_clientes_table extends CI_Migration // Alterado para refletir todas as adições
{
    public function up()
    {
        if (!$this->db->field_exists('tamanho_colete', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'tamanho_colete' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'fornecedor']
            ]);
        }
        if (!$this->db->field_exists('peso_lastro', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'peso_lastro' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'tamanho_colete']
            ]);
        }
        if (!$this->db->field_exists('tamanho_neoprene', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'tamanho_neoprene' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'peso_lastro']
            ]);
        }
        if (!$this->db->field_exists('tamanho_nadadeira', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'tamanho_nadadeira' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'tamanho_neoprene']
            ]);
        }
        if (!$this->db->field_exists('contato_emergencia_nome', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'contato_emergencia_nome' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'tamanho_nadadeira']
            ]);
        }
        if (!$this->db->field_exists('contato_emergencia_telefone', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'contato_emergencia_telefone' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'contato_emergencia_nome']
            ]);
        }
        if (!$this->db->field_exists('contato_emergencia_parentesco', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'contato_emergencia_parentesco' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true, 'after' => 'contato_emergencia_telefone']
            ]);
        }
        if (!$this->db->field_exists('atestado_medico_validade', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'atestado_medico_validade' => ['type' => 'DATE', 'null' => true, 'after' => 'contato_emergencia_parentesco']
            ]);
        }
        if (!$this->db->field_exists('atestado_medico_arquivo', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'atestado_medico_arquivo' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'atestado_medico_validade']
            ]);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('tamanho_colete', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'tamanho_colete');
        }
        if ($this->db->field_exists('peso_lastro', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'peso_lastro');
        }
        if ($this->db->field_exists('tamanho_neoprene', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'tamanho_neoprene');
        }
        if ($this->db->field_exists('tamanho_nadadeira', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'tamanho_nadadeira');
        }
        if ($this->db->field_exists('contato_emergencia_nome', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'contato_emergencia_nome');
        }
        if ($this->db->field_exists('contato_emergencia_telefone', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'contato_emergencia_telefone');
        }
        if ($this->db->field_exists('contato_emergencia_parentesco', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'contato_emergencia_parentesco');
        }
        if ($this->db->field_exists('atestado_medico_validade', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'atestado_medico_validade');
        }
        if ($this->db->field_exists('atestado_medico_arquivo', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'atestado_medico_arquivo');
        }
    }
}