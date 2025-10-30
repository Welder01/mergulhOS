<?php

class Migration_add_equipment_to_usuarios_table extends CI_Migration
{
    public function up()
    {
        $fields = [
            'tamanho_colete' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'permissoes_id',
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
        $this->dbforge->add_column('usuarios', $fields);
    }

    public function down()
    {
        // A remoção pode ser feita manualmente se necessário
    }
}