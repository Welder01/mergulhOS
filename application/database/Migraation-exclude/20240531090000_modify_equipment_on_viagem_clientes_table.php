<?php

class Migration_modify_equipment_on_viagem_clientes_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'locar_cilindro' => [
                'name' => 'locar_cilindro',
                'type' => 'TINYINT',
                'constraint' => 3,
                'unsigned' => true,
                'default' => 0,
            ],
            'locar_regulador' => [
                'name' => 'locar_regulador',
                'type' => 'TINYINT',
                'constraint' => 3,
                'unsigned' => true,
                'default' => 0,
            ],
        ];
        $this->dbforge->modify_column('viagem_clientes', $fields);
    }

    public function down()
    {
        // Reverter para o tipo anterior se necessário
        $fields = [
            'locar_cilindro' => ['name' => 'locar_cilindro', 'type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'locar_regulador' => ['name' => 'locar_regulador', 'type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ];
        $this->dbforge->modify_column('viagem_clientes', $fields);
    }
}