<?php
class Debug_columns2 extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        echo "start_debug<br>";

        $cols = ['valor_pagamento', 'tipo_pagamento', 'hora_inicio', 'hora_fim', 'status_pagamento', 'aceite'];
        foreach ($cols as $col) {
            $q = $this->db->query("SELECT $col FROM curso_instrutores LIMIT 1");
            if ($q)
                echo "$col EXISTS<br>";
            else
                echo "$col MISSING: " . $this->db->error()['message'] . "<br>";
        }

        echo "end_debug";
    }
}
