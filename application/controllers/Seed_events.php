<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Seed_events extends CI_Controller
{

    public function index()
    {
        echo "Seeding events...\n";
        $this->load->database();

        $events = [
            'viagem_cliente_adicionado',
            'viagem_usuario_adicionado',
            'curso_cliente_adicionado',
            'curso_usuario_adicionado',
            'treino_cliente_adicionado',
            'treino_usuario_adicionado',
            'viagem_criada', // Ensure defaults exist
            'curso_criado',
        ];

        foreach ($events as $event) {
            $exists = $this->db->where('evento', $event)->get('evolution_eventos')->row();
            if (!$exists) {
                $this->db->insert('evolution_eventos', [
                    'evento' => $event,
                    'mensagem_id' => NULL,
                    'status' => 0
                ]);
                echo "Inserted: $event\n";
            } else {
                echo "Exists: $event\n";
            }
        }
        echo "Seeding complete.\n";
    }
}
