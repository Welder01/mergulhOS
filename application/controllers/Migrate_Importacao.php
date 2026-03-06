<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate_Importacao extends CI_Controller
{

    public function index()
    {
        $this->load->dbforge();

        // Check if column exists
        if (!$this->db->field_exists('importacao_inconsistente', 'clientes')) {
            $fields = array(
                'importacao_inconsistente' => array(
                    'type' => 'TINYINT',
                    'default' => 0,
                    'null' => FALSE,
                    'after' => 'documento' // Position it after document
                )
            );

            if ($this->dbforge->add_column('clientes', $fields)) {
                echo "Coluna 'importacao_inconsistente' adicionada com sucesso!<br>";
            } else {
                echo "Erro ao adicionar coluna 'importacao_inconsistente'.<br>";
            }
        } else {
            echo "Coluna 'importacao_inconsistente' já existe.<br>";
        }

        echo "Migração concluída.";
    }
}
