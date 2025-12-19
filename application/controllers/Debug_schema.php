<?php
class Debug_schema extends CI_Controller
{
    public function index()
    {
        $this->load->database();

        $tables = ['cursos', 'curso_instrutores'];
        foreach ($tables as $t) {
            echo "<h3>Table: $t</h3>";
            $query = $this->db->get($t, 1);
            if ($query) {
                $row = $query->row();
                if ($row) {
                    echo "<pre>";
                    print_r($row);
                    echo "</pre>";
                } else {
                    echo "Table empty or no row returned.<br>";
                    // if empty, we can't see columns via row(), but fields() should have worked.
                    // let's try field_data again but var_dump it
                    $fields = $this->db->field_data($t);
                    var_dump($fields);
                }
            } else {
                echo "Query failed: " . $this->db->error()['message'];
            }
        }
    }
}
