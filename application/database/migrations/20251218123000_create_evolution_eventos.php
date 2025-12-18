<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_evolution_eventos extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('evolution_eventos')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'evento' => [
                    'type' => 'VARCHAR', // e.g., 'viagem_criada', 'curso_criado'
                    'constraint' => 100,
                    'null' => FALSE
                ],
                'mensagem_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE // Can be null if we want to just track the event but no message active? Or strictly FK. 
                    // Better strictly nullable, if null => no message sent.
                ],
                'status' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE
                ]
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('evento'); // Index for lookups
            $this->dbforge->create_table('evolution_eventos');

            // Seed default events
            $defaultEvents = [
                ['evento' => 'viagem_criada', 'status' => 0],
                ['evento' => 'viagem_editada', 'status' => 0],
                ['evento' => 'viagem_excluida', 'status' => 0],
                ['evento' => 'curso_criado', 'status' => 0],
                ['evento' => 'curso_editado', 'status' => 0],
                ['evento' => 'curso_excluido', 'status' => 0],
                ['evento' => 'treino_criado', 'status' => 0],
                ['evento' => 'treino_editado', 'status' => 0],
                ['evento' => 'treino_excluido', 'status' => 0],
            ];
            $this->db->insert_batch('evolution_eventos', $defaultEvents);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('evolution_eventos');
    }
}
