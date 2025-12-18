<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Force_micropayment_user extends CI_Controller
{
    public function index()
    {
        $this->load->dbforge();

        $fields = [
            'usuario_cadastrou_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'modulo_id'
            ]
        ];

        if ($this->dbforge->add_column('curso_instrutores', $fields)) {
            echo "Coluna usuario_cadastrou_id adicionada com sucesso ou já existe.";
        } else {
            echo "Erro ao adicionar coluna.";
        }
    }
}
