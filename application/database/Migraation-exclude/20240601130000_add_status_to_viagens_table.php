<?php

class Migration_add_status_to_viagens_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'name' => 'status',
                'type' => 'ENUM("Aberta","Concluida","Disponível","Indisponível","Adiada","Cancelada","Prorrogada","Prevista")',
                'default' => 'Prevista',
                'null' => false,
            ],
        ];
        $this->dbforge->modify_column('viagens', $fields);
    }

    public function down()
    {
        // Reverter para o ENUM anterior se necessário
    }
}