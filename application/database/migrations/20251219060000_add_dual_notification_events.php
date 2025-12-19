<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_dual_notification_events extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('evolution_eventos')) {

            // 1. Rename existing 'mixed' events to '_cliente' (assuming they were primarily for clients)
            // We use 'update' to preserve the configured message/status if any.

            $renames = [
                'os_status_alterado' => 'os_status_alterado_cliente',
                'viagem_cliente_adicionado' => 'viagem_cliente_adicionado_cliente',
                'viagem_cliente_removido' => 'viagem_cliente_removido_cliente',
                'curso_cliente_adicionado' => 'curso_cliente_adicionado_cliente',
                'curso_cliente_removido' => 'curso_cliente_removido_cliente',
                'treino_cliente_adicionado' => 'treino_cliente_adicionado_cliente',
            ];

            foreach ($renames as $old => $new) {
                // Check if old exists
                $q = $this->db->get_where('evolution_eventos', ['evento' => $old]);
                if ($q->num_rows() > 0) {
                    // Check if new already exists (safety)
                    $qNew = $this->db->get_where('evolution_eventos', ['evento' => $new]);
                    if ($qNew->num_rows() == 0) {
                        $this->db->where('evento', $old);
                        $this->db->update('evolution_eventos', ['evento' => $new]);
                    }
                }
            }

            // 2. Add new 'usuario' (Instructor/Tech) events
            $newEvents = [
                ['evento' => 'os_status_alterado_usuario', 'status' => 0],
                ['evento' => 'viagem_cliente_adicionado_usuario', 'status' => 0], // Notify Instructor when client is added
                ['evento' => 'viagem_cliente_removido_usuario', 'status' => 0],
                ['evento' => 'curso_cliente_adicionado_usuario', 'status' => 0],
                ['evento' => 'curso_cliente_removido_usuario', 'status' => 0],
                ['evento' => 'treino_cliente_adicionado_usuario', 'status' => 0],
            ];

            foreach ($newEvents as $event) {
                $exists = $this->db->get_where('evolution_eventos', ['evento' => $event['evento']])->row();
                if (!$exists) {
                    $this->db->insert('evolution_eventos', $event);
                }
            }
        }
    }

    public function down()
    {
        // Revert is complex due to data loss on rename reverting, so we skip for now
    }
}
