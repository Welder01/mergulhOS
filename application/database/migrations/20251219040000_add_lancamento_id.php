<?php

class Migration_Add_lancamento_id extends CI_Migration
{
    public function up()
    {
        $tables = ['curso_instrutores', 'viagem_instrutores', 'treinos_agendados'];
        $column = [
            'lancamento_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'default' => NULL,
                'null' => TRUE,
                'after' => 'status_pagamento'
            ]
        ];

        foreach ($tables as $table) {
            if ($this->db->table_exists($table)) {
                if (!$this->db->field_exists('lancamento_id', $table)) {
                    $this->dbforge->add_column($table, $column);
                }
            }
        }
    }

    public function down()
    {
        $tables = ['curso_instrutores', 'viagem_instrutores', 'treinos_agendados'];
        foreach ($tables as $table) {
            if ($this->db->table_exists($table) && $this->db->field_exists('lancamento_id', $table)) {
                $this->dbforge->drop_column($table, 'lancamento_id');
            }
        }
    }
}
