<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_course_trip_events extends CI_Migration
{
    public function up()
    {
        // Ensure table exists (safeguard)
        if ($this->db->table_exists('evolution_eventos')) {
            $eventsToAdd = [
                ['evento' => 'curso_cliente_adicionado', 'status' => 0],
                ['evento' => 'curso_usuario_adicionado', 'status' => 0],
                ['evento' => 'viagem_cliente_adicionado', 'status' => 0],
                ['evento' => 'viagem_usuario_adicionado', 'status' => 0],
                ['evento' => 'treino_cliente_adicionado', 'status' => 0],
                ['evento' => 'treino_usuario_adicionado', 'status' => 0],
            ];

            foreach ($eventsToAdd as $event) {
                // Check if already exists to prevent duplicate key errors
                $exists = $this->db->get_where('evolution_eventos', ['evento' => $event['evento']])->row();
                if (!$exists) {
                    $this->db->insert('evolution_eventos', $event);
                }
            }
        }
    }

    public function down()
    {
        // Optional: Delete these specific events
        // $slugs = ['curso_cliente_adicionado', ...];
        // $this->db->where_in('evento', $slugs)->delete('evolution_eventos');
    }
}
