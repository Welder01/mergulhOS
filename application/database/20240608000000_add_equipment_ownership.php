<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_equipment_ownership extends CI_Migration
{
    public function up()
    {
        $equip_fields = [
            'possui_colete' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
            'possui_lastro' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
            'possui_neoprene' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
            'possui_nadadeira' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
            'possui_regulador' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
            'possui_lanterna' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
            'possui_computador' => ['type' => 'BOOLEAN', 'default' => 0, 'null' => false],
        ];

        // Adiciona colunas na tabela 'clientes'
        foreach ($equip_fields as $field => $attributes) {
            if (!$this->db->field_exists($field, 'clientes')) {
                $this->dbforge->add_column('clientes', [$field => $attributes]);
            }
        }

        // Adiciona colunas na tabela 'usuarios'
        foreach ($equip_fields as $field => $attributes) {
            if (!$this->db->field_exists($field, 'usuarios')) {
                $this->dbforge->add_column('usuarios', [$field => $attributes]);
            }
        }
    }

    public function down()
    {
        // Lógica para reverter, se necessário
    }
}