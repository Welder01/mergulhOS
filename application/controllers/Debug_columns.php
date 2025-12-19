<?php
class Debug_columns extends CI_Controller
{
    public function index()
    {
        $this->load->database();

        echo "<h2>Columns: cursos</h2>";
        $query = $this->db->query("SHOW COLUMNS FROM cursos");
        foreach ($query->result() as $row) {
            echo $row->Field . "<br>";
        }

        echo "<h2>Columns: curso_instrutores</h2>";
        $query2 = $this->db->query("SHOW COLUMNS FROM curso_instrutores");
        foreach ($query2->result() as $row) {
            echo $row->Field . "<br>";
        }
    }
}
