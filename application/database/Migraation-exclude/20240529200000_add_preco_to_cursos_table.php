<?php

class Migration_add_preco_to_cursos_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'preco' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => '0.00',
                'after' => 'status',
            ],
        ];
        $this->dbforge->add_column('cursos', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('cursos', 'preco');
    }
}