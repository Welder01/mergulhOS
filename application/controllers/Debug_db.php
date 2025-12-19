<?php
class Debug_db extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        echo "<h2>Table: cursos</h2>";
        $print_fields = $this->db->field_data('cursos');
        foreach ($print_fields as $field) {
            echo $field->name . " (" . $field->type . ")<br>";
        }

        echo "<h2>Table: curso_instrutores</h2>";
        $print_fields2 = $this->db->field_data('curso_instrutores');
        foreach ($print_fields2 as $field) {
            echo $field->name . " (" . $field->type . ")<br>";
        }

        echo "<h2>Query Test</h2>";
        $usuario_id = 1; // Assuming admin ID 1 exists
        $this->db->select('cursos.*, curso_instrutores.data_atribuicao, curso_instrutores.valor_pagamento, curso_instrutores.tipo_pagamento, curso_instrutores.hora_inicio, curso_instrutores.hora_fim, curso_instrutores.status_pagamento, curso_instrutores.aceite, curso_instrutores.id as id');
        $this->db->from('cursos');
        $this->db->join('curso_instrutores', 'curso_instrutores.curso_id = cursos.id');
        $this->db->where('curso_instrutores.usuario_id', $usuario_id);
        $this->db->order_by('cursos.data_inicio', 'DESC');
        $query = $this->db->get();

        if (!$query) {
            $error = $this->db->error();
            echo "Query Failed: " . $error['message'];
        } else {
            echo "Query Success. Rows: " . $query->num_rows();
        }
    }
}
