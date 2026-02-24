<?php

class Migration_Add_proposito_to_viagem_clientes extends CI_Migration
{
    public function up()
    {
        $col = [
            'proposito' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE,
            ],
        ];
        if (!$this->db->field_exists('proposito', 'viagem_clientes')) {
            $this->dbforge->add_column('viagem_clientes', $col);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('proposito', 'viagem_clientes')) {
            $this->dbforge->drop_column('viagem_clientes', 'proposito');
        }
    }
}