<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Ensure_all_triggers extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('evolution_eventos')) {
            $events = [
                // --- OS ---
                ['evento' => 'os_status_alterado_cliente', 'status' => 0],
                ['evento' => 'os_status_alterado_usuario', 'status' => 0],
                ['evento' => 'os_usuario_adicionado', 'status' => 0], // Already strict
                ['evento' => 'os_usuario_removido', 'status' => 0],

                // --- Viagens ---
                ['evento' => 'viagem_cliente_adicionado_cliente', 'status' => 0],
                ['evento' => 'viagem_cliente_adicionado_usuario', 'status' => 0],
                ['evento' => 'viagem_cliente_removido_cliente', 'status' => 0],
                ['evento' => 'viagem_cliente_removido_usuario', 'status' => 0],

                // --- Cursos ---
                ['evento' => 'curso_cliente_adicionado_cliente', 'status' => 0],
                ['evento' => 'curso_cliente_adicionado_usuario', 'status' => 0],
                ['evento' => 'curso_cliente_removido_cliente', 'status' => 0],
                ['evento' => 'curso_cliente_removido_usuario', 'status' => 0],

                // --- Treinos ---
                ['evento' => 'treino_agendado_admin', 'status' => 0], // Assuming admin schedules for client
                ['evento' => 'treino_cliente_adicionado_cliente', 'status' => 0],
                ['evento' => 'treino_cliente_adicionado_usuario', 'status' => 0],

                // --- Administrative ---
                ['evento' => 'cliente_criado', 'status' => 0], // Usually for admin/user
                ['evento' => 'produto_criado', 'status' => 0],
                ['evento' => 'servico_criado', 'status' => 0],
                ['evento' => 'cobranca_criada', 'status' => 0],
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
