<?php

class Migration_create_curso_instrutores_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'curso_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false,
            ],
            'data_atribuicao' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_curso_instrutor FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_instrutor_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(idUsuarios) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('curso_instrutores');
    }

    public function down()
    {
        $this->dbforge->drop_table('curso_instrutores');
    }
}