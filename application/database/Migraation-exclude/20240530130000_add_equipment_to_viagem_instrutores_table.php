<?php

class Migration_add_equipment_to_viagem_instrutores_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'numero_bolsa' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'usuario_id',
            ],
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
            'locar_lastro' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'locar_regulador',
            ],
        ];
        $this->dbforge->add_column('viagem_instrutores', $fields);
    }

    public function down()
    {
        // Para simplificar, a remoção pode ser feita manualmente se necessário
    }
}