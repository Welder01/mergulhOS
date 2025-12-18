<?php

class Migration_Add_payment_to_curso_instrutores extends CI_Migration
{
    public function up()
    {
        $fields = [
            'valor_pagamento' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'data_atribuicao'
            ],
            'tipo_pagamento' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'aula',
                'after' => 'valor_pagamento'
            ]
        ];

        // Check if columns exist before adding
        if (!$this->db->field_exists('valor_pagamento', 'curso_instrutores')) {
            $this->dbforge->add_column('curso_instrutores', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('valor_pagamento', 'curso_instrutores')) {
            $this->dbforge->drop_column('curso_instrutores', 'valor_pagamento');
        }
        if ($this->db->field_exists('tipo_pagamento', 'curso_instrutores')) {
            $this->dbforge->drop_column('curso_instrutores', 'tipo_pagamento');
        }
    }
}
