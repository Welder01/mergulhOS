<?php

class Migration_Add_ultimate_events extends CI_Migration
{
    public function up()
    {
        $events = [
            // 1. Cliente CRUD
            'cliente_criado' => 'Olá {CLIENTE.NOME}, seu cadastro foi realizado com sucesso! Bem-vindo!',
            'cliente_editado' => 'Olá {CLIENTE.NOME}, seus dados cadastrais foram atualizados.',
            'cliente_excluido' => 'O cliente {CLIENTE.NOME} foi removido do sistema.',
            'aniversario' => 'Parabéns {CLIENTE.NOME}! A Equipe MergulhOS te deseja um feliz aniversário!',

            // 2. Financeiro (Cobranças)
            'cobranca_criada' => 'Olá {CLIENTE.NOME}, uma nova cobrança foi gerada. Valor: {COBRANCA.VALOR}. Vencimento: {COBRANCA.VENCIMENTO}. Link: {COBRANCA.LINK}',
            'cobranca_cancelada' => 'Olá {CLIENTE.NOME}, a cobrança #{COBRANCA.ID} foi cancelada.',
            'cobranca_excluida' => 'A cobrança #{COBRANCA.ID} do cliente {CLIENTE.NOME} foi excluída.',
            'cobranca_pagamento_confirmado' => 'Olá {CLIENTE.NOME}, recebemos o pagamento da cobrança #{COBRANCA.ID}. Obrigado!',
            'cobranca_vencimento' => 'Olá {CLIENTE.NOME}, lembrete: sua cobrança vence hoje/amanhã. Link: {COBRANCA.LINK}',

            // 3. Produtos & Serviços
            'produto_criado' => 'Novo produto cadastrado: {PRODUTO.NOME}. Preço: {PRODUTO.PRECO}.',
            'produto_editado' => 'O produto {PRODUTO.NOME} foi atualizado.',
            'produto_excluido' => 'O produto {PRODUTO.NOME} foi removido do estoque.',
            'servico_criado' => 'Novo serviço disponível: {SERVICO.NOME}. Preço: {SERVICO.PRECO}.',
            'servico_editado' => 'O serviço {SERVICO.NOME} foi atualizado.',
            'servico_excluido' => 'O serviço {SERVICO.NOME} foi removido.',

            // 4. Viagens (Dual)
            'viagem_criada_cliente' => 'Nova viagem disponível: {VIAGEM.NOME}! Confira as datas: {VIAGEM.DATA_INICIO}.',
            'viagem_criada_usuario' => 'Nova viagem cadastrada: {VIAGEM.NOME}. Prepare-se para as vendas!',
            'viagem_editada_cliente' => 'Atualização na viagem {VIAGEM.NOME}. Verifique os detalhes.',
            'viagem_editada_usuario' => 'A viagem {VIAGEM.NOME} sofreu alterações.',
            'viagem_cancelada_cliente' => 'Atenção: A viagem {VIAGEM.NOME} foi cancelada. Entre em contato para mais detalhes.',
            'viagem_cancelada_usuario' => 'A viagem {VIAGEM.NOME} foi cancelada.',
            'viagem_excluida_cliente' => 'A viagem {VIAGEM.NOME} foi removida da nossa programação.',
            'viagem_excluida_usuario' => 'A viagem {VIAGEM.NOME} foi excluída do sistema.',

            // 5. Staff Assignment
            'viagem_instrutor_adicionado' => 'Você foi adicionado como instrutor na viagem {VIAGEM.NOME}.',
            'viagem_instrutor_removido' => 'Você foi removido da viagem {VIAGEM.NOME}.',
            'curso_instrutor_adicionado' => 'Você foi adicionado como instrutor no curso {CURSO.NOME}.',
            'curso_instrutor_removido' => 'Você foi removido do curso {CURSO.NOME}.',

            // 6. Treinos (Status)
            'treino_alterado_cliente' => 'Seu treino de {TREINO.NOME} foi alterado para {TREINO.DATA}.',
            'treino_alterado_usuario' => 'O agendamento de {CLIENTE.NOME} para {TREINO.NOME} foi alterado.',
            'treino_confirmado_cliente' => 'Seu treino de {TREINO.NOME} em {TREINO.DATA} está confirmado!',
            'treino_confirmado_usuario' => 'Treino confirmado: {CLIENTE.NOME} - {TREINO.NOME} em {TREINO.DATA}.',
            'treino_excluido_cliente' => 'Seu agendamento de treino {TREINO.NOME} foi cancelado/excluído.',
            'treino_excluido_usuario' => 'Agendamento removido: {CLIENTE.NOME} - {TREINO.NOME}.',

            // 7. Geral
            'lembrete_agendamento' => 'Lembrete: Você tem um compromisso agendado para {AGENDAMENTO.DATA}. Detalhes: {AGENDAMENTO.DETALHES}.'
        ];

        foreach ($events as $event => $msg) {
            $exists = $this->db->where('evento', $event)->get('evolution_eventos')->num_rows();
            if ($exists == 0) {
                $this->db->insert('evolution_eventos', [
                    'evento' => $event,
                    'mensagem' => $msg,
                    'status' => 0 // Default inactive
                ]);
            }
        }
    }

    public function down()
    {
        // Optional: remove added events
    }
}
