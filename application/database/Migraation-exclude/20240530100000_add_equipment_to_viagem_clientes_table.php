<?php

class Migration_add_equipment_to_viagem_clientes_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'locar_nadadeira' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'numero_bolsa',
            ],
            'locar_cilindro' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'locar_nadadeira',
            ],
            'locar_colete' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'locar_cilindro',
            ],
            'locar_neoprene' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'locar_colete',
            ],
            'locar_regulador' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'locar_neoprene',
            ],
        ];
        $this->dbforge->add_column('viagem_clientes', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('viagem_clientes', 'locar_nadadeira');
        $this->dbforge->drop_column('viagem_clientes', 'locar_cilindro');
        $this->dbforge->drop_column('viagem_clientes', 'locar_colete');
        $this->dbforge->drop_column('viagem_clientes', 'locar_neoprene');
        $this->dbforge->drop_column('viagem_clientes', 'locar_regulador');
    }
}