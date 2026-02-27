<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_cobrancas_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->modify_column('cobrancas', [
            'expire_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'total' => [
                'type' => 'DECIMAL(10,2)',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->modify_column('cobrancas', [
            'expire_at' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'total' => [
                'type' => 'VARCHAR(15)',
                'null' => true,
            ],
        ]);
    }
}
