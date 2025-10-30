<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Consolidate_database_schema extends CI_Migration
{
    public function up()
    {
        // --- ALTERAÇÕES NA TABELA 'clientes' ---

        $cliente_fields = [
            'altura' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'after' => 'sexo'],
            'peso' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'after' => 'altura'],
            'tamanho_colete' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'fornecedor'],
            'peso_lastro' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'tamanho_colete'],
            'tamanho_neoprene' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'peso_lastro'],
            'tamanho_nadadeira' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'tamanho_neoprene'],
            'contato_emergencia_nome' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'tamanho_nadadeira'],
            'contato_emergencia_telefone' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'contato_emergencia_nome'],
            'contato_emergencia_parentesco' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true, 'after' => 'contato_emergencia_telefone'],
            'atestado_medico_validade' => ['type' => 'DATE', 'null' => true, 'after' => 'contato_emergencia_parentesco'],
            'atestado_medico_arquivo' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'atestado_medico_validade'],
            'nome_medico' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'atestado_medico_arquivo'],
            'crm_medico' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'nome_medico'],
            'codigo_validacao_atestado' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'crm_medico'],
            'atestado_medico_emissao' => ['type' => 'DATE', 'null' => true, 'after' => 'codigo_validacao_atestado'],
        ];

        foreach ($cliente_fields as $field => $attributes) {
            if (!$this->db->field_exists($field, 'clientes')) {
                $this->dbforge->add_column('clientes', [$field => $attributes]);
            }
        }

        if ($this->db->field_exists('possui_regulador', 'clientes')) {
            $this->dbforge->modify_column('clientes', ['possui_regulador' => ['name' => 'qtd_reguladores', 'type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        } elseif (!$this->db->field_exists('qtd_reguladores', 'clientes')) {
            $this->dbforge->add_column('clientes', ['qtd_reguladores' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'after' => 'fornecedor']]);
        }

        // --- ALTERAÇÕES NA TABELA 'usuarios' ---

        $usuario_fields = [
            'sexo' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'nome'],
            'altura' => ['type' => 'DECIMAL(5,2)', 'null' => true, 'after' => 'sexo'],
            'peso' => ['type' => 'DECIMAL(5,2)', 'null' => true, 'after' => 'altura'],
            'contato' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true, 'after' => 'celular'],
            'complemento' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true, 'after' => 'numero'],
            'tamanho_colete' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'dataExpiracao'],
            'peso_lastro' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'tamanho_colete'],
            'tamanho_neoprene' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'peso_lastro'],
            'tamanho_nadadeira' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'tamanho_neoprene'],
            'qtd_reguladores' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'null' => false, 'after' => 'tamanho_nadadeira'],
            'qtd_lanterna' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'null' => false, 'after' => 'qtd_reguladores'],
            'qtd_computador' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'null' => false, 'after' => 'qtd_lanterna'],
            'contato_emergencia_nome' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'qtd_computador'],
            'contato_emergencia_telefone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true, 'after' => 'contato_emergencia_nome'],
            'contato_emergencia_parentesco' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'contato_emergencia_telefone'],
            'atestado_medico_validade' => ['type' => 'DATE', 'null' => true, 'after' => 'contato_emergencia_parentesco'],
            'atestado_medico_arquivo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'atestado_medico_validade'],
            'nome_medico' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'atestado_medico_arquivo'],
            'crm_medico' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'nome_medico'],
            'codigo_validacao_atestado' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'crm_medico'],
            'atestado_medico_emissao' => ['type' => 'DATE', 'null' => true, 'after' => 'codigo_validacao_atestado'],
        ];

        foreach ($usuario_fields as $field => $attributes) {
            if (!$this->db->field_exists($field, 'usuarios')) {
                $this->dbforge->add_column('usuarios', [$field => $attributes]);
            }
        }

        // --- CRIAÇÃO DE NOVAS TABELAS ---

        // Tabela de certificações para usuários
        if (!$this->db->table_exists('certificacoes_usuario')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'usuario_id' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
                'nome_certificacao' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
                'orgao_emissor' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'numero_certificacao' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'data_emissao' => ['type' => 'DATE', 'null' => true],
                'arquivo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table('certificacoes_usuario');
        }

        // Tabela de restrições alimentares para usuários
        if (!$this->db->table_exists('restricoes_alimentares_usuario')) {
            $this->dbforge->add_field([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'usuario_id' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
                'restricao' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
                'observacoes' => ['type' => 'TEXT', 'null' => true],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->create_table('restricoes_alimentares_usuario');
        }
    }

    public function down()
    {
        // O método down pode ser implementado para reverter as alterações se necessário
    }
}