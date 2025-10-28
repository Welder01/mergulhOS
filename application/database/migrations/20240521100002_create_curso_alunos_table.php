<?php

class Migration_create_curso_alunos_table extends CI_Migration
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
            'curso_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'cliente_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'data_inscricao' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'status_aluno' => [
                'type' => 'ENUM("inscrito","concluido","desistente")',
                'default' => 'inscrito',
                'null' => false,
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_curso_aluno FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_aluno_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(idClientes) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('curso_alunos');
    }

    public function down()
    {
        $this->dbforge->drop_table('curso_alunos');
    }
}