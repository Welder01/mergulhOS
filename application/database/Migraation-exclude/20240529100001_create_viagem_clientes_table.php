<?php

class Migration_create_viagem_clientes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'viagem_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'cliente_id' => [
                'type' => 'INT',
                'unsigned' => false,
            ],
            'status_pagamento' => [
                'type' => 'ENUM("Pendente","Pago","Parcial")',
                'default' => 'Pendente',
            ],
            'precisa_embarque' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'precisa_hospedagem' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'detalhes_hospedagem' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'numero_bolsa' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'data_inscricao' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_viagem_cliente_viagem FOREIGN KEY (viagem_id) REFERENCES viagens(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_viagem_cliente_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(idClientes) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('viagem_clientes');
    }

    public function down()
    {
        $this->dbforge->drop_table('viagem_clientes');
    }
}