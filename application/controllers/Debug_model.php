<?php
class Debug_model extends CI_Controller
{
    public function index()
    {
        $this->load->model('Atividades_model');
        try {
            $res = $this->Atividades_model->getMeusCursos(1);
            echo "Cursos Count: " . count($res) . "<br>";
            if (count($res) > 0) {
                foreach ($res as $r) {
                    echo "Course ID: " . $r->id . " Val: " . $r->valor_pagamento . " Status: " . $r->status_pagamento . " Aceite: " . $r->aceite . "<br>";
                }
            }

            $res2 = $this->Atividades_model->getMeusLancamentos(1);
            echo "Lancamentos Count: " . count($res2) . "<br>";
            if (count($res2) > 0) {
                foreach ($res2 as $l) {
                    echo "Lanc ID: " . $l->idLancamentos . " Val: " . $l->valor . " Baixado: " . $l->baixado . "<br>";
                }
            }
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
