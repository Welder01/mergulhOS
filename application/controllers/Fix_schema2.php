<?php
class Fix_schema2 extends CI_Controller
{
    public function index()
    {
        $this->load->dbforge();
        echo "Running Fix_schema2 " . date('H:i:s') . "<br>";

        // Add valor_pagamento
        if (!$this->db->field_exists('valor_pagamento', 'curso_instrutores')) {
            $fields = [
                'valor_pagamento' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                    'after' => 'data_atribuicao'
                ]
            ];
            $res = $this->dbforge->add_column('curso_instrutores', $fields);
            echo "Added valor_pagamento: " . ($res ? 'SUCCESS' : 'FAIL') . "<br>";
        } else {
            echo "valor_pagamento exists.<br>";
        }

        // Add tipo_pagamento
        if (!$this->db->field_exists('tipo_pagamento', 'curso_instrutores')) {
            $fields = [
                'tipo_pagamento' => [
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'default' => 'aula',
                    'after' => 'valor_pagamento'
                ]
            ];
            $res = $this->dbforge->add_column('curso_instrutores', $fields);
            echo "Added tipo_pagamento: " . ($res ? 'SUCCESS' : 'FAIL') . "<br>";
        } else {
            echo "tipo_pagamento exists.<br>";
        }
    }
}
