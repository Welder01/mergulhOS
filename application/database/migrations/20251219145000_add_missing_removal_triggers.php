<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_missing_removal_triggers extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('evolution_eventos')) {
            $events = [
                // Treino REMOVED events were missing
                ['evento' => 'treino_cliente_removido_cliente', 'status' => 0],
                ['evento' => 'treino_cliente_removido_usuario', 'status' => 0],
            ];

            foreach ($events as $event) {
                $exists = $this->db->get_where('evolution_eventos', ['evento' => $event['evento']])->row();
                if (!$exists) {
                    $this->db->insert('evolution_eventos', $event);
                }
            }
        }
    }

    public function down()
    {
    }
}
