<?php
class Force_micropayment extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        $this->load->dbforge();

        // Add valor_pagamento
        if (!$this->db->field_exists('valor_pagamento', 'curso_instrutores')) {
            $this->db->query("ALTER TABLE `curso_instrutores` ADD `valor_pagamento` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `data_atribuicao`");
            echo "Added valor_pagamento via SQL.<br>";
        } else {
            echo "valor_pagamento already exists.<br>";
        }

        // Add tipo_pagamento
        if (!$this->db->field_exists('tipo_pagamento', 'curso_instrutores')) {
            $this->db->query("ALTER TABLE `curso_instrutores` ADD `tipo_pagamento` VARCHAR(20) NOT NULL DEFAULT 'aula' AFTER `valor_pagamento`");
            echo "Added tipo_pagamento via SQL.<br>";
        } else {
            echo "tipo_pagamento already exists.<br>";
        }
    }
}
