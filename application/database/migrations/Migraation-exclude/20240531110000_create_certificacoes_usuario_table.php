<?php

class Migration_create_certificacoes_usuario_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
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
            'arquivo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_certificacao_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(idUsuarios) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('certificacoes_usuario');
    }

    public function down()
    {
        $this->dbforge->drop_table('certificacoes_usuario');
    }
}