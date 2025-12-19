<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fix_schema extends CI_Controller
{

    public function index()
    {
        echo "<h1>Diagnóstico e Correção de Banco de Dados</h1>";
        $this->load->dbforge();

        // 1. Verificando estrutura da tabela lancamentos
        echo "<h2>1. Verificando tabela 'lancamentos'</h2>";
        $fields = $this->db->list_fields('lancamentos');
        if (in_array('idLancamentos', $fields)) {
            echo "<p style='color:green'>[OK] PK idLancamentos encontrada.</p>";
        } else {
            // Try to find what is the PK
            echo "<p style='color:red'>[ERRO] idLancamentos NÃO encontrada. Campos disponíveis: " . implode(', ', $fields) . "</p>";
        }

        // 2. Verificando colunas nas tabelas de atividades
        $tables = ['curso_instrutores', 'viagem_instrutores', 'treinos_agendados'];
        foreach ($tables as $table) {
            echo "<h2>2. Verificando tabela '$table'</h2>";

            if (!$this->db->table_exists($table)) {
                echo "<p style='color:red'>Tabela não existe.</p>";
                continue;
            }

            // Check column lancamento_id
            if (!$this->db->field_exists('lancamento_id', $table)) {
                echo "<p style='color:red'>[ERRO] Coluna 'lancamento_id' NÃO existe. Criando agora...</p>";
                $column = [
                    'lancamento_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'default' => NULL,
                        'null' => TRUE,
                        'after' => 'status_pagamento' // Try to place it reasonably
                    ]
                ];
                if ($this->dbforge->add_column($table, $column)) {
                    echo "<p style='color:green'>[SUCESSO] Coluna criada.</p>";
                } else {
                    echo "<p style='color:red'>[FALHA] Não foi possível criar a coluna.</p>";
                }
            } else {
                echo "<p style='color:green'>[OK] Coluna 'lancamento_id' já existe.</p>";

                // Fix possible 0s
                $this->db->where('lancamento_id', 0);
                $this->db->or_where('lancamento_id', '');
                $count = $this->db->count_all_results($table);

                if ($count > 0) {
                    echo "<p style='color:orange'>Encontrados $count registros com lancamento_id = 0 ou vazio. Corrigindo para NULL...</p>";
                    $this->db->set('lancamento_id', NULL);
                    $this->db->where('lancamento_id', 0);
                    $this->db->or_where('lancamento_id', '');
                    $this->db->update($table);
                    echo "<p style='color:green'>Correção aplicada.</p>";
                } else {
                    echo "<p style='color:green'>[OK] Nenhum valor inválido (0) encontrado.</p>";
                }
            }
        }

        echo "<h3>Concluído. Tente novamente faturar/estornar.</h3>";
    }
}
