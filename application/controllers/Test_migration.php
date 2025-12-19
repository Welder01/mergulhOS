<?php
class Test_migration extends CI_Controller
{
    public function index()
    {
        echo "Running Verification " . date('H:i:s') . "<br>";
        $this->load->library('migration');
        $this->migration->latest();

        $this->load->database();
        $SlugsToCheck = ['cliente_criado', 'produto_criado', 'servico_criado', 'cobranca_criada'];
        foreach ($SlugsToCheck as $slug) {
            $exists = $this->db->get_where('evolution_eventos', ['evento' => $slug])->row();
            echo "$slug: " . ($exists ? "EXISTS" : "MISSING") . "<br>";
        }
    }
}
