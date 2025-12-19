<?php
class Debug_verify extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        echo "Running Debug_verify " . date('H:i:s') . "<br>";

        $cols = ['valor_pagamento', 'tipo_pagamento'];
        foreach ($cols as $col) {
            $q = $this->db->query("SELECT $col FROM curso_instrutores LIMIT 1");
            if ($q)
                echo "$col EXISTS<br>";
            else
                echo "$col MISSING<br>";
        }
    }
}
