<?php
class Debug_fk extends CI_Controller
{
    public function index()
    {
        $this->load->database();
        echo "start_debug<br>";

        // Check 1: FK name
        $q1 = $this->db->query("SELECT curso_id FROM curso_instrutores LIMIT 1");
        if ($q1)
            echo "curso_id EXISTS<br>";
        else
            echo "curso_id MISSING: " . $this->db->error()['message'] . "<br>";

        $q2 = $this->db->query("SELECT cursos_id FROM curso_instrutores LIMIT 1");
        if ($q2)
            echo "cursos_id EXISTS<br>";
        else
            echo "cursos_id MISSING: " . $this->db->error()['message'] . "<br>";

        // Check 2: PK name
        $q3 = $this->db->query("SELECT id FROM cursos LIMIT 1");
        if ($q3)
            echo "cursos.id EXISTS<br>";
        else
            echo "cursos.id MISSING: " . $this->db->error()['message'] . "<br>";

        $q4 = $this->db->query("SELECT idCursos FROM cursos LIMIT 1");
        if ($q4)
            echo "cursos.idCursos EXISTS<br>";
        else
            echo "cursos.idCursos MISSING: " . $this->db->error()['message'] . "<br>";

        echo "end_debug";
    }
}
