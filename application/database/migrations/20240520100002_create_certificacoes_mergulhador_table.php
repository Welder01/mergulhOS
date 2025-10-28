<?php

class Migration_create_certificacoes_mergulhador_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cliente_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'nome_certificacao' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'orgao_emissor' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'data_emissao' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'data_validade' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'arquivo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
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
        $this->dbforge->add_field('CONSTRAINT fk_certificacao_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(idClientes) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('certificacoes_mergulhador');
    }

    public function down()
    {
        $this->dbforge->drop_table('certificacoes_mergulhador');
    }
}