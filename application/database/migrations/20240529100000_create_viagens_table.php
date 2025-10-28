<?php

class Migration_create_viagens_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nome_viagem' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_partida' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'data_retorno' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'vagas' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0,
            ],
            'preco_pessoa' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
            ],
            'status' => [
                'type' => 'ENUM("Disponível","Indisponível","Adiada","Cancelada","Prorrogada","Prevista")',
                'default' => 'Prevista',
                'null' => false,
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('viagens');
    }

    public function down()
    {
        $this->dbforge->drop_table('viagens');
    }
}