<?php

class Migration_create_restricoes_alimentares_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cliente_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
            ],
            'restricao' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'observacoes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_cadastro' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_restricao_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(idClientes) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('restricoes_alimentares');
    }

    public function down()
    {
        $this->dbforge->drop_table('restricoes_alimentares');
    }
}