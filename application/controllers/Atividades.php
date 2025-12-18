<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Atividades extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logado')) {
            redirect(base_url('index.php/login'));
        }

        $this->load->model('atividades_model');
        $this->data['menuAtividades'] = 'atividades';
    }

    public function index()
    {
        $usuario_id = $this->session->userdata('idUsuarios');

        $this->data['cursos'] = $this->atividades_model->getMeusCursos($usuario_id);
        $this->data['viagens'] = $this->atividades_model->getMinhasViagens($usuario_id);
        $this->data['lancamentos'] = $this->atividades_model->getMeusLancamentos($usuario_id);

        $this->data['view'] = 'atividades/minhas_atividades';
        return $this->layout();
    }
}
