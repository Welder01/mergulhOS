<?php

class Migration_add_proposito_to_viagem_clientes extends CI_Migration
{
    public function up()
    {
        $fields = [
            'proposito' => [
                'type' => "ENUM('Checkout','Acompanhante','Turismo','Batismo')",
                'null' => true,
                'after' => 'numero_bolsa',
            ],
        ];
        $this->dbforge->add_column('viagem_clientes', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('viagem_clientes', 'proposito');
    }
}