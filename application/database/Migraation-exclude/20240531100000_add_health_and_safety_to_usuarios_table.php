<?php

class Migration_add_health_and_safety_to_usuarios_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'contato_emergencia_nome' => [
                'type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'tamanho_nadadeira'
            ],
            'contato_emergencia_telefone' => [
                'type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'contato_emergencia_nome'
            ],
            'contato_emergencia_parentesco' => [
                'type' => 'VARCHAR', 'constraint' => '50', 'null' => true, 'after' => 'contato_emergencia_telefone'
            ],
            'atestado_medico_validade' => [
                'type' => 'DATE', 'null' => true, 'after' => 'contato_emergencia_parentesco'
            ],
            'atestado_medico_arquivo' => [
                'type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'atestado_medico_validade'
            ],
        ];
        $this->dbforge->add_column('usuarios', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('usuarios', 'contato_emergencia_nome');
        $this->dbforge->drop_column('usuarios', 'contato_emergencia_telefone');
        $this->dbforge->drop_column('usuarios', 'contato_emergencia_parentesco');
        $this->dbforge->drop_column('usuarios', 'atestado_medico_validade');
        $this->dbforge->drop_column('usuarios', 'atestado_medico_arquivo');
    }
}