<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_medico_details_to_clientes extends CI_Migration
{
    public function up()
    {
        $fields = [
            'nome_medico' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'atestado_medico_validade',
            ],
            'crm_medico' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'nome_medico',
            ],
            'codigo_validacao_atestado' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'crm_medico',
            ],
            'atestado_medico_emissao' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'codigo_validacao_atestado',
            ],
        ];
        $this->dbforge->add_column('clientes', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('clientes', 'nome_medico');
        $this->dbforge->drop_column('clientes', 'crm_medico');
        $this->dbforge->drop_column('clientes', 'codigo_validacao_atestado');
        $this->dbforge->drop_column('clientes', 'atestado_medico_emissao');
    }
}