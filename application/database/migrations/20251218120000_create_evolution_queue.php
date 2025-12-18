<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_evolution_queue extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('evolution_queue')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'phone_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => FALSE
                ],
                'message' => [
                    'type' => 'TEXT',
                    'null' => FALSE
                ],
                'options' => [
                    'type' => 'TEXT', // JSON string for delay, presence, etc.
                    'null' => TRUE
                ],
                'status' => [
                    'type' => 'ENUM("pending","sending","sent","failed")',
                    'default' => 'pending',
                    'null' => FALSE
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => FALSE
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE
                ],
                'attempts' => [
                    'type' => 'INT',
                    'default' => 0
                ],
                'last_error' => [
                    'type' => 'TEXT',
                    'null' => TRUE
                ]
            ]);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('evolution_queue');
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('evolution_queue');
    }
}
