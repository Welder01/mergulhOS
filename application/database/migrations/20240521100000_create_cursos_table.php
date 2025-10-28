<?php

class Migration_create_cursos_table extends CI_Migration
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
            'nome_curso' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'descricao' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'data_inicio' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'data_fim' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM("ativo","inativo","em andamento","finalizado")',
                'default' => 'ativo',
                'null' => false,
            ],
            'data_cadastro' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('cursos');
    }

    public function down()
    {
        $this->dbforge->drop_table('cursos');
    }
}