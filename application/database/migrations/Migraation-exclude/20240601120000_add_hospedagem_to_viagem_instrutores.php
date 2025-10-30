<?php

class Migration_add_hospedagem_to_viagem_instrutores extends CI_Migration
{
    public function up()
    {
        $fields = [
            'precisa_hospedagem' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'locar_lastro',
            ],
            'detalhes_hospedagem' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'precisa_hospedagem',
            ],
            'hospedagem_quarto_numero' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'detalhes_hospedagem',
            ],
            'hospedagem_tipo_quarto' => [
                'type' => "ENUM('Solteiro','Casal','Família','Compartilhado')",
                'null' => true,
                'after' => 'hospedagem_quarto_numero',
            ],
            'hospedagem_numero_camas' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
                'after' => 'hospedagem_tipo_quarto',
            ],
        ];
        $this->dbforge->add_column('viagem_instrutores', $fields);
    }

    public function down()
    {
        // A remoção pode ser feita manualmente se necessário
    }
}