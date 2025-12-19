<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Fix_evolution_events extends CI_Migration
{
    public function up()
    {
        // Create evolution_eventos table
        if (!$this->db->table_exists('evolution_eventos')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'evento' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => FALSE,
                    'unique' => TRUE
                ],
                'mensagem_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE,
                    'default' => NULL
                ],
                'status' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'default' => NULL
                ]
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('evolution_eventos');

            // Seed Default Events
            $defaultEvents = [
                ['evento' => 'aniversario_cliente', 'status' => 0],
                ['evento' => 'cobranca_vencimento', 'status' => 0],
                ['evento' => 'pagamento_confirmado', 'status' => 0],
                ['evento' => 'lembrete_agendamento', 'status' => 0]
            ];
            $this->db->insert_batch('evolution_eventos', $defaultEvents);
        }
    }

    public function down()
    {
        // $this->dbforge->drop_table('evolution_eventos');
    }
}
