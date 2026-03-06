<?php

class Migration_Add_bilhetes_detalhes extends CI_Migration {

    public function up() {
        $table = 'bilhetes';
        
        // Verifica e adiciona tipo_veiculo
        if (!$this->db->field_exists('tipo_veiculo', $table)) {
            $this->dbforge->add_column($table, [
                'tipo_veiculo' => [
                    'type' => 'ENUM("onibus", "van", "aviao")',
                    'default' => NULL,
                    'null' => TRUE,
                    'after' => 'tipo_transporte'
                ]
            ]);
        }

        // Verifica e adiciona terminal
        if (!$this->db->field_exists('terminal', $table)) {
            $this->dbforge->add_column($table, [
                'terminal' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'default' => NULL,
                    'null' => TRUE,
                    'after' => 'assento'
                ]
            ]);
        }

        // Verifica e adiciona plataforma
        if (!$this->db->field_exists('plataforma', $table)) {
            $this->dbforge->add_column($table, [
                'plataforma' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'default' => NULL,
                    'null' => TRUE,
                    'after' => 'terminal'
                ]
            ]);
        }

        // Verifica e adiciona portao
        if (!$this->db->field_exists('portao', $table)) {
            $this->dbforge->add_column($table, [
                'portao' => [
                    'type' => 'VARCHAR',
                    'constraint' => '50',
                    'default' => NULL,
                    'null' => TRUE,
                    'after' => 'plataforma'
                ]
            ]);
        }

        // Verifica e adiciona ponto_encontro
        if (!$this->db->field_exists('ponto_encontro', $table)) {
            $this->dbforge->add_column($table, [
                'ponto_encontro' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'default' => NULL,
                    'null' => TRUE,
                    'after' => 'portao'
                ]
            ]);
        }

        // Verifica e adiciona numero_antt_anac
        if (!$this->db->field_exists('numero_antt_anac', $table)) {
            $this->dbforge->add_column($table, [
                'numero_antt_anac' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'default' => NULL,
                    'null' => TRUE,
                    'after' => 'ponto_encontro'
                ]
            ]);
        }
    }

    public function down() {
        $table = 'bilhetes';
        $columns = ['tipo_veiculo', 'terminal', 'plataforma', 'portao', 'ponto_encontro', 'numero_antt_anac'];
        foreach ($columns as $col) {
            if ($this->db->field_exists($col, $table)) {
                $this->dbforge->drop_column($table, $col);
            }
        }
    }
}