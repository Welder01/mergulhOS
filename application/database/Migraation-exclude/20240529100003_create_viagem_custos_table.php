<?php

class Migration_create_viagem_custos_table extends CI_Migration
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
            'descricao' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'valor' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_viagem_custo_viagem FOREIGN KEY (viagem_id) REFERENCES viagens(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('viagem_custos');
    }

    public function down()
    {
        $this->dbforge->drop_table('viagem_custos');
    }
}