<?php

class Migration_add_equipamentos_to_clientes_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'tamanho_colete' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'fornecedor',
            ],
            'peso_lastro' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'tamanho_colete',
            ],
            'tamanho_neoprene' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'peso_lastro',
            ],
            'tamanho_nadadeira' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'tamanho_neoprene',
            ],
        ];
        $this->dbforge->add_column('clientes', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('clientes', 'tamanho_colete');
        $this->dbforge->drop_column('clientes', 'peso_lastro');
        $this->dbforge->drop_column('clientes', 'tamanho_neoprene');
        $this->dbforge->drop_column('clientes', 'tamanho_nadadeira');
    }
}