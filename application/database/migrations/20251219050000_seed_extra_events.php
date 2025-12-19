<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Seed_extra_events extends CI_Migration
{
    public function up()
    {
        $events = [
            // Viagens
            [
                'evento' => 'viagem_cliente_adicionado', // Already exists? Maybe check. 
                'titulo' => 'Cliente Adicionado à Viagem',
                'mensagem' => 'Olá {NOME_CLIENTE}, você foi adicionado à viagem {NOME_VIAGEM}. Partida: {DATA_PARTIDA_VIAGEM}.'
            ],
            [
                'evento' => 'viagem_cliente_removido',
                'titulo' => 'Cliente Removido da Viagem',
                'mensagem' => 'Olá {NOME_CLIENTE}, você foi removido da viagem {NOME_VIAGEM}.'
            ],
            // Cursos (Add already likely exists, adding Remove)
            [
                'evento' => 'curso_cliente_removido',
                'titulo' => 'Aluno Removido do Curso',
                'mensagem' => 'Olá {NOME_CLIENTE}, você foi removido do curso {NOME_CURSO}.'
            ],
            // OS / Tarefas
            [
                'evento' => 'os_status_alterado',
                'titulo' => 'Status da Tarefa Alterado',
                'mensagem' => 'A tarefa #{OS.IDOS} mudou de status para: {OS.STATUS}. Link: {OS.LINK_VISUALIZAR}'
            ],
            [
                'evento' => 'os_usuario_adicionado',
                'titulo' => 'Usuário Atribuído à Tarefa',
                'mensagem' => 'Olá {NOME_USUARIO}, você foi atribuído à tarefa #{OS.IDOS}.'
            ],
            [
                'evento' => 'os_usuario_removido',
                'titulo' => 'Usuário Removido da Tarefa',
                'mensagem' => 'Olá {NOME_USUARIO}, você foi desvinculado da tarefa #{OS.IDOS}.'
            ],
            // Financeiro
            [
                'evento' => 'tarefa_pagamento_realizado',
                'titulo' => 'Pagamento de Tarefa Realizado',
                'mensagem' => 'Pagamento realizado para a tarefa: {LANCAMENTO.DESCRICAO}. Valor: {LANCAMENTO.VALOR_FORMATADO}.'
            ],
            [
                'evento' => 'tarefa_pagamento_estornado',
                'titulo' => 'Pagamento de Tarefa Estornado',
                'mensagem' => 'O pagamento da tarefa {LANCAMENTO.DESCRICAO} foi estornado.'
            ]
        ];

        foreach ($events as $evt) {
            // Check if exists first to avoid duplicates/errors
            $exists = $this->db->where('evento', $evt['evento'])->count_all_results('evolution_eventos');

            if (!$exists) {
                // Create Message first
                $msgData = [
                    'titulo' => $evt['titulo'],
                    'mensagem' => $evt['mensagem'],
                    'imagem_url' => ''
                ];
                $this->db->insert('evolution_mensagens', $msgData);
                $msgId = $this->db->insert_id();

                // Create Event linked to Message
                $this->db->insert('evolution_eventos', [
                    'evento' => $evt['evento'],
                    'mensagem_id' => $msgId,
                    'status' => 1 // Active by default
                ]);
            }
        }
    }

    public function down()
    {
        // Optional: Remove them, but usually risky to delete user data on down
    }
}
