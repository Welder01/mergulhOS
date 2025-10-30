<?php

class Migration_add_all_fields_to_clientes extends CI_Migration
{
    public function up()
    {
        // Adiciona altura e peso
        if (!$this->db->field_exists('altura', 'clientes')) {
            $this->dbforge->add_column('clientes', ['altura' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'after' => 'sexo']]);
        }
        if (!$this->db->field_exists('peso', 'clientes')) {
            $this->dbforge->add_column('clientes', ['peso' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'after' => 'altura']]);
        }

        // Adiciona campos de equipamento
        if (!$this->db->field_exists('tamanho_colete', 'clientes')) {
            $this->dbforge->add_column('clientes', ['tamanho_colete' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'fornecedor']]);
        }
        if (!$this->db->field_exists('peso_lastro', 'clientes')) {
            $this->dbforge->add_column('clientes', ['peso_lastro' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'tamanho_colete']]);
        }
        if (!$this->db->field_exists('tamanho_neoprene', 'clientes')) {
            $this->dbforge->add_column('clientes', ['tamanho_neoprene' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'peso_lastro']]);
        }
        if (!$this->db->field_exists('tamanho_nadadeira', 'clientes')) {
            $this->dbforge->add_column('clientes', ['tamanho_nadadeira' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'tamanho_neoprene']]);
        }

        // Adiciona campos de contato de emergência e atestado
        if (!$this->db->field_exists('contato_emergencia_nome', 'clientes')) {
            $this->dbforge->add_column('clientes', ['contato_emergencia_nome' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'tamanho_nadadeira']]);
        }
        if (!$this->db->field_exists('contato_emergencia_telefone', 'clientes')) {
            $this->dbforge->add_column('clientes', ['contato_emergencia_telefone' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'after' => 'contato_emergencia_nome']]);
        }
        if (!$this->db->field_exists('contato_emergencia_parentesco', 'clientes')) {
            $this->dbforge->add_column('clientes', ['contato_emergencia_parentesco' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true, 'after' => 'contato_emergencia_telefone']]);
        }
        if (!$this->db->field_exists('atestado_medico_validade', 'clientes')) {
            $this->dbforge->add_column('clientes', ['atestado_medico_validade' => ['type' => 'DATE', 'null' => true, 'after' => 'contato_emergencia_parentesco']]);
        }
        if (!$this->db->field_exists('atestado_medico_arquivo', 'clientes')) {
            $this->dbforge->add_column('clientes', ['atestado_medico_arquivo' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'atestado_medico_validade']]);
        }

        // Adiciona/Modifica campos de quantidade para equipamentos
        if ($this->db->field_exists('possui_regulador', 'clientes')) {
            $this->dbforge->modify_column('clientes', ['possui_regulador' => ['name' => 'qtd_reguladores', 'type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        } elseif (!$this->db->field_exists('qtd_reguladores', 'clientes')) {
            $this->dbforge->add_column('clientes', ['qtd_reguladores' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'after' => 'fornecedor']]);
        }

        if ($this->db->field_exists('possui_lanterna', 'clientes')) {
            $this->dbforge->modify_column('clientes', ['possui_lanterna' => ['name' => 'qtd_lanterna', 'type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        } elseif (!$this->db->field_exists('qtd_lanterna', 'clientes')) {
            $this->dbforge->add_column('clientes', ['qtd_lanterna' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'after' => 'qtd_reguladores']]);
        }

        if ($this->db->field_exists('possui_computador', 'clientes')) {
            $this->dbforge->modify_column('clientes', ['possui_computador' => ['name' => 'qtd_computador', 'type' => 'INT', 'constraint' => 11, 'default' => 0]]);
        } elseif (!$this->db->field_exists('qtd_computador', 'clientes')) {
            $this->dbforge->add_column('clientes', ['qtd_computador' => ['type' => 'INT', 'constraint' => 11, 'default' => 0, 'after' => 'qtd_lanterna']]);
        }
    }

    public function down()
    {
        // O método down pode ser implementado para reverter as alterações se necessário
    }
}

```

### 2. Limpeza dos Arquivos Antigos

Para evitar qualquer conflito, **remova** todos os outros arquivos de *migration* que você tem relacionados a essas alterações. Deixe apenas o novo arquivo que criamos (`20240601150000_add_all_fields_to_clientes.php`) na pasta `application/migrations/`.

**Arquivos a serem removidos:**
*   `f:\APLICATIVOS LARAGON\mergulhOS\application\database\migrations\20240520100000_add_equipamentos_to_clientes_table.php`
*   `f:\APLICATIVOS LARAGON\mergulhOS\application\migrations\20250202000000_add_cliente_details.php`
*   `f:\APLICATIVOS LARAGON\mergulhOS\application\database\migrations\20250203000000_update_cliente_equipamentos.php`
*   `f:\APLICATIVOS LARAGON\mergulhOS\application\database\migrations\20250204000000_update_cliente_equipamentos_qty.php`

### Próximos Passos

1.  **Limpe** os arquivos de *migration* antigos.
2.  **Crie** o novo arquivo `20240601150000_add_all_fields_to_clientes.php` na pasta `application/migrations/`.
3.  Acesse a área de **Configurações > Sistema** e clique em **"Atualizar Banco de Dados"**.

Agora, o sistema de *migration* deve funcionar corretamente, pois ele terá um único arquivo, bem estruturado e no local correto, para executar as atualizações.

<!--
[PROMPT_SUGGESTION]Revise o controller `Clientes.php` e sugira melhorias de código.[/PROMPT_SUGGESTION]
[PROMPT_SUGGESTION]Como posso criar um relatório em PDF com os dados dos clientes, incluindo os equipamentos que eles possuem?[/PROMPT_SUGGESTION]
