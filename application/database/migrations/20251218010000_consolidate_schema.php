<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Consolidate_schema extends CI_Migration
{
    public function up()
    {
        // 1. Add pagar_usuario_id to lancamentos
        if (!$this->db->field_exists('pagar_usuario_id', 'lancamentos')) {
            $fields = [
                'pagar_usuario_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    'default' => NULL,
                    'after' => 'clientes_id'
                ]
            ];
            $this->dbforge->add_column('lancamentos', $fields);
        }

        // 2. Add importacao_inconsistente to clientes
        if (!$this->db->field_exists('importacao_inconsistente', 'clientes')) {
            $fields = [
                'importacao_inconsistente' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'null' => FALSE
                ]
            ];
            $this->dbforge->add_column('clientes', $fields);
        }

        // 3. Create evolution_logs table
        if (!$this->db->table_exists('evolution_logs')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'timestamp' => [
                    'type' => 'DATETIME',
                    'null' => FALSE
                ],
                'endpoint' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE,
                    'default' => NULL
                ],
                'phone_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => TRUE,
                    'default' => NULL
                ],
                'request_payload' => [
                    'type' => 'TEXT',
                    'null' => TRUE,
                    'default' => NULL
                ],
                'response_code' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                    'default' => NULL
                ],
                'response_body' => [
                    'type' => 'TEXT',
                    'null' => TRUE,
                    'default' => NULL
                ],
                'curl_error' => [
                    'type' => 'TEXT',
                    'null' => TRUE,
                    'default' => NULL
                ],
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('evolution_logs');
        }

        // 4. Create evolution_mensagens table
        if (!$this->db->table_exists('evolution_mensagens')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'titulo' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => FALSE
                ],
                'mensagem' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'imagem_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 1024,
                    'null' => TRUE,
                    'default' => NULL
                ],
                'criado_em' => [
                    'type' => 'TIMESTAMP',
                    'null' => FALSE,
                    'default' => 'CURRENT_TIMESTAMP' // Note: dbforge sometimes struggles with literal defaults, but this usually works or defaults to 0
                ],
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('evolution_mensagens');

            // Fix for CURRENT_TIMESTAMP if dbforge creates it as string 'CURRENT_TIMESTAMP' or errors
            // We can run a raw query to ensure default is correct if needed, but CI3 often handles it.
        }

        // 5. Add Evolution API configurations
        $configs = [
            ['config' => 'evolution_api_url', 'valor' => 'https://chatapi.paj.org.br'],
            ['config' => 'evolution_api_key', 'valor' => '111D99F76C00-4D42-8BB9-09D1056784C5'],
            ['config' => 'evolution_api_instance', 'valor' => 'isacbrasil'],
            ['config' => 'evolution_presence', 'valor' => 'composing'],
            ['config' => 'evolution_delay_fixo', 'valor' => '1200'],
            ['config' => 'evolution_delay_min', 'valor' => '1000'],
            ['config' => 'evolution_delay_max', 'valor' => '5000']
        ];

        foreach ($configs as $config) {
            $exists = $this->db->where('config', $config['config'])->get('configuracoes')->row();
            if (!$exists) {
                $this->db->insert('configuracoes', $config);
            }
        }
    }

    public function down()
    {
        // Don't drop tables/columns to avoid data loss on rollback unless explicit
    }
}
