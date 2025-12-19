<?php
class Fix_schema extends CI_Controller
{
    public function index()
    {
        $this->load->dbforge();

        echo "Running Schema Fix...<br>";

        // Add valor_pagamento to curso_instrutores
        if (!$this->db->field_exists('valor_pagamento', 'curso_instrutores')) {
            echo "Adding valor_pagamento...<br>";
            $fields = [
                'valor_pagamento' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                    'after' => 'data_atribuicao'
                ]
            ];
            $this->dbforge->add_column('curso_instrutores', $fields);
        } else {
            echo "valor_pagamento already exists.<br>";
        }

        // Add tipo_pagamento to curso_instrutores
        if (!$this->db->field_exists('tipo_pagamento', 'curso_instrutores')) {
            echo "Adding tipo_pagamento...<br>";
            $fields = [
                'tipo_pagamento' => [
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'default' => 'aula',
                    'after' => 'valor_pagamento'
                ]
            ];
            $this->dbforge->add_column('curso_instrutores', $fields);
        } else {
            echo "tipo_pagamento already exists.<br>";
        }

        echo "Done.";
    }
}
