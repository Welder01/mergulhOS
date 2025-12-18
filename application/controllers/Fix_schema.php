<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fix_schema extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->dbforge();
    }

    public function index()
    {
        echo "<h1>Fixing Schema: Viagem Instrutores</h1>";

        // Bypass cache check and force add
        $sql1 = "ALTER TABLE `viagem_instrutores` ADD COLUMN `valor_pagamento` DECIMAL(10,2) DEFAULT '0.00' AFTER `usuario_id`";
        if (!$this->db->query($sql1)) {
            echo "Msg 1: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Added valor_pagamento force.<br>";
        }

        $sql2 = "ALTER TABLE `viagem_instrutores` ADD COLUMN `tipo_pagamento` VARCHAR(20) DEFAULT 'fixo' AFTER `valor_pagamento`";
        if (!$this->db->query($sql2)) {
            echo "Msg 2: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Added tipo_pagamento force.<br>";
        }

        $sql3 = "ALTER TABLE `viagem_instrutores` ADD COLUMN `status_pagamento` VARCHAR(50) DEFAULT 'pendente' AFTER `tipo_pagamento`";
        if (!$this->db->query($sql3)) {
            echo "Msg 3: " . $this->db->error()['message'] . "<br>";
        } else {
            echo "Added status_pagamento force.<br>";
        }


        // Verify
        $fields = $this->db->list_fields('viagem_instrutores');
        echo "<h3>Current Columns in viagem_instrutores:</h3>";
        echo "<pre>";
        print_r($fields);
        echo "</pre>";
    }
}
