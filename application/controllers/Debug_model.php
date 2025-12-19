<?php
class Debug_model extends CI_Controller
{
    public function index()
    {
        $this->load->model('Atividades_model');
        try {
            $res = $this->Atividades_model->getMeusCursos(1);
            echo "Query Success. Count: " . count($res);
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }

        // Also try simple way if try-catch doesn't catch DB errors in CI
        $this->load->database();
        if ($this->db->error()['message']) {
            echo "DB Error: " . $this->db->error()['message'];
        }
    }
}
