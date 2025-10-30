<?php

class Migration_create_viagem_instrutores_table extends CI_Migration
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
            'usuario_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_field('CONSTRAINT fk_viagem_instrutor_viagem FOREIGN KEY (viagem_id) REFERENCES viagens(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_viagem_instrutor_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(idUsuarios) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->dbforge->create_table('viagem_instrutores');
    }

    public function down()
    {
        $this->dbforge->drop_table('viagem_instrutores');
    }
}