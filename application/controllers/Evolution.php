<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Evolution extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mapos_model');
        $this->data['menuIntegracoes'] = 'evolution';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) { // Usando uma permissão genérica de configuração
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar integrações.');
            redirect(base_url());
        }

        $this->data['view'] = 'evolution/gerenciar';
        return $this->layout();
    }

    public function fetch_instance()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $apiUrl = $this->mapos_model->get_ci_config('evolution_api_url');
        $apiKey = $this->mapos_model->get_ci_config('evolution_api_key');
        $instanceName = $this->mapos_model->get_ci_config('evolution_api_instance');

        if (empty($apiUrl) || empty($apiKey) || empty($instanceName)) {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'URL da API, Chave ou Nome da Instância não configurados.']));
        }

        $url = rtrim($apiUrl, '/') . "/instance/connectionState/{$instanceName}";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "apikey: {$apiKey}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            return $this->output->set_status_header(500)->set_output(json_encode(['error' => "cURL Error: " . $err]));
        }

        if ($httpcode >= 400) {
             $responseData = json_decode($response, true);
             $errorMessage = $responseData['message'] ?? 'Erro desconhecido na API.';
             return $this->output->set_status_header($httpcode)->set_output(json_encode(['error' => "API Error: " . $errorMessage]));
        }

        return $this->output->set_content_type('application/json')->set_output($response);
    }
}