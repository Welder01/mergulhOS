<?php

class Migration_add_equipamentos_to_clientes_table extends CI_Migration // Alterado para refletir todas as adições
{
    public function up()
    {
        if (!$this->db->field_exists('altura', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'altura' => ['type' => 'DECIMAL(5,2)', 'null' => true, 'after' => 'sexo']
            ]);
        }
        if (!$this->db->field_exists('peso', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'peso' => ['type' => 'DECIMAL(5,2)', 'null' => true, 'after' => 'altura']
            ]);
        }
        if (!$this->db->field_exists('qtd_reguladores', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'qtd_reguladores' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'null' => false, 'after' => 'fornecedor']
            ]);
        }
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
        if (!$this->db->field_exists('possui_lanterna', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'possui_lanterna' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false, 'after' => 'qtd_reguladores']
            ]);
        }
        if (!$this->db->field_exists('possui_computador', 'clientes')) {
            $this->dbforge->add_column('clientes', [
                'possui_computador' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false, 'after' => 'possui_lanterna']
            ]);
        }
    }

    public function down()
    {
        // Revertendo as novas colunas
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
        if ($this->db->field_exists('altura', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'altura');
        }
        if ($this->db->field_exists('peso', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'peso');
        }
        if ($this->db->field_exists('qtd_reguladores', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'qtd_reguladores');
        }
        if ($this->db->field_exists('possui_lanterna', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'possui_lanterna');
        }
        if ($this->db->field_exists('possui_computador', 'clientes')) {
            $this->dbforge->drop_column('clientes', 'possui_computador');
        }
    }
}