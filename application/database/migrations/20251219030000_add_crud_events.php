<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_crud_events extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('evolution_eventos')) {
            $eventsToAdd = [
                // Clientes
                ['evento' => 'cliente_criado', 'status' => 0],
                ['evento' => 'cliente_editado', 'status' => 0],
                ['evento' => 'cliente_excluido', 'status' => 0],

                // Produtos
                ['evento' => 'produto_criado', 'status' => 0],
                ['evento' => 'produto_editado', 'status' => 0],
                ['evento' => 'produto_excluido', 'status' => 0],

                // Serviços
                ['evento' => 'servico_criado', 'status' => 0],
                ['evento' => 'servico_editado', 'status' => 0],
                ['evento' => 'servico_excluido', 'status' => 0],

                // Cobranças
                ['evento' => 'cobranca_criada', 'status' => 0],
                ['evento' => 'cobranca_excluida', 'status' => 0],
                ['evento' => 'cobranca_pagamento_confirmado', 'status' => 0],
                ['evento' => 'cobranca_cancelada', 'status' => 0],
            ];

            foreach ($eventsToAdd as $event) {
                $exists = $this->db->get_where('evolution_eventos', ['evento' => $event['evento']])->row();
                if (!$exists) {
                    $this->db->insert('evolution_eventos', $event);
                }
            }
        }
    }

    public function down()
    {
        // No down needed for additive updates usually, to preserve data if re-run
    }
}
