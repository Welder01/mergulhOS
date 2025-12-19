<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Atualizacoes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['menuConfiguracoes'] = 'Atualizacoes';
    }

    public function index()
    {
        $this->data['view'] = 'mapos/atualizacoes';
        $this->layout();
    }
}
