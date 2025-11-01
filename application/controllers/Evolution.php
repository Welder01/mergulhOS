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
        $this->load->model('evolution_model');
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

        $this->data['mensagens'] = $this->evolution_model->get('evolution_mensagens', '*', '', 100);
        $this->data['clientes'] = $this->db->get('clientes')->result();
        $this->data['usuarios'] = $this->db->get('usuarios')->result();

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

    public function adicionar_mensagem()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar mensagens.');
            redirect('evolution/gerenciar?tab=mensagens');
        }

        $data = [
            'titulo' => $this->input->post('titulo'),
            'mensagem' => $this->input->post('mensagem'),
            'imagem_url' => $this->input->post('imagem_url'),
        ];

        if ($this->evolution_model->add('evolution_mensagens', $data)) {
            $this->session->set_flashdata('success', 'Mensagem adicionada com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar mensagem.');
        }
        redirect('evolution/gerenciar#tabMensagens');
    }

    public function editar_mensagem()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar mensagens.');
            redirect('evolution/gerenciar#tabMensagens');
        }

        $id = $this->input->post('id');
        $data = [
            'titulo' => $this->input->post('titulo'),
            'mensagem' => $this->input->post('mensagem'),
            'imagem_url' => $this->input->post('imagem_url'),
        ];

        if ($this->evolution_model->edit('evolution_mensagens', $data, 'id', $id)) {
            $this->session->set_flashdata('success', 'Mensagem editada com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao editar mensagem.');
        }
        redirect('evolution/gerenciar#tabMensagens');
    }

    public function excluir_mensagem($id)
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir mensagens.');
            redirect('evolution/gerenciar#tabMensagens');
        }

        if ($this->evolution_model->delete('evolution_mensagens', 'id', $id)) {
            $this->session->set_flashdata('success', 'Mensagem excluída com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir mensagem.');
        }
        redirect('evolution/gerenciar#tabMensagens');
    }

    public function enviar_mensagem()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $apiUrl = $this->mapos_model->get_ci_config('evolution_api_url');
        $apiKey = $this->mapos_model->get_ci_config('evolution_api_key');
        $instanceName = $this->mapos_model->get_ci_config('evolution_api_instance');

        if (empty($apiUrl) || empty($apiKey) || empty($instanceName)) {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Configurações da API Evolution incompletas.']));
        }

        $mensagemId = $this->input->post('mensagem_id');
        $alvo = $this->input->post('alvo');
        $numeros = $this->input->post('numeros');

        $mensagem = $this->evolution_model->getById($mensagemId);
        if (! $mensagem) {
            return $this->output->set_status_header(404)->set_output(json_encode(['error' => 'Modelo de mensagem não encontrado.']));
        }

        $numerosParaEnvio = [];
        if ($alvo === 'clientes' || $alvo === 'usuarios') {
            $this->load->model('clientes_model');
            $tabela = ($alvo === 'clientes') ? 'clientes' : 'usuarios';
            $campoTelefone = ($alvo === 'clientes') ? 'celular' : 'celular';
            $this->db->select('*');
            $idField = ($alvo === 'clientes') ? 'idClientes' : 'idUsuarios';
            $this->db->where($idField, $numeros[0]);
            $results = $this->db->get($tabela)->result();
            foreach ($results as $contato) {
                if (!empty($contato->$campoTelefone)) {
                    $numeroLimpo = preg_replace('/[^0-9]/', '', $contato->$campoTelefone);
                    // Garante que o número tenha o DDI 55 (Brasil) se não tiver
                    if (strlen($numeroLimpo) <= 11) {
                        $numerosParaEnvio[] = '55' . $numeroLimpo;
                    } else {
                        $numerosParaEnvio[] = $numeroLimpo;
                    }
                }

                // Substituição de variáveis na mensagem
                $mensagem->mensagem = str_replace('{NOME_CLIENTE}', $contato->nomeCliente ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{NOME_USUARIO}', $contato->nome ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{EMAIL_CLIENTE}', $contato->email ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DOCUMENTO_CLIENTE}', $contato->documento ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{TELEFONE_CLIENTE}', $contato->telefone ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{CELULAR_CLIENTE}', $contato->celular ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DATA_CADASTRO}', isset($contato->dataCadastro) ? date('d/m/Y', strtotime($contato->dataCadastro)) : '', $mensagem->mensagem);
            }
        } else {
            $numerosParaEnvio = array_map(function ($num) {
                return preg_replace('/[^0-9]/', '', $num);
            }, explode(',', $numeros[0]));
        }

        $endpoint = !empty($mensagem->imagem_url) ? 'message/sendImage' : 'message/sendText';
        $url = rtrim($apiUrl, '/') . "/{$endpoint}/{$instanceName}";

        $sucessos = 0;
        $falhas = 0;

        $presence = $this->mapos_model->get_ci_config('evolution_presence') ?: 'composing';
        $delayFixo = (int)($this->mapos_model->get_ci_config('evolution_delay_fixo') ?: 1200);
        $delayMin = (int)($this->mapos_model->get_ci_config('evolution_delay_min') ?: 1000);
        $delayMax = (int)($this->mapos_model->get_ci_config('evolution_delay_max') ?: 5000);

        $useRandomDelay = $delayFixo <= 0;

        foreach ($numerosParaEnvio as $numero) {
            $delay = $useRandomDelay ? rand($delayMin, $delayMax) : $delayFixo;

            $payload = [
                'number' => $numero,
                'options' => ['delay' => $delay, 'presence' => $presence],
                'textMessage' => ['text' => $mensagem->mensagem],
            ];

            if (!empty($mensagem->imagem_url)) {
                $payload['mediaMessage'] = ['mediatype' => 'image', 'media' => $mensagem->imagem_url];
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => ["Content-Type: application/json", "apikey: {$apiKey}"],
            ]);

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            curl_close($curl);

            // Log detalhado para depuração
            $logMessage = "Envio para: {$numero} | Status: {$httpcode} | Payload: " . json_encode($payload) . " | Resposta: {$response} | Erro cURL: {$err}";
            log_info($logMessage);

            ($httpcode >= 200 && $httpcode < 300) ? $sucessos++ : $falhas++;
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => "Envio concluído: {$sucessos} com sucesso, {$falhas} com falha."]));
    }

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select('idClientes, nomeCliente, celular');
            $this->db->like('LOWER(nomeCliente)', $q);
            $this->db->limit(5);
            $query = $this->db->get('clientes');
            $result = array_map(function ($cliente) {
                return [
                    'id' => $cliente->idClientes,
                    'label' => $cliente->nomeCliente . ' - ' . $cliente->celular,
                ];
            }, $query->result());
            echo json_encode($result);
        }
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select('idUsuarios, nome, celular');
            $this->db->like('LOWER(nome)', $q);
            $this->db->limit(5);
            $query = $this->db->get('usuarios');
            $result = array_map(function ($usuario) {
                return [
                    'id' => $usuario->idUsuarios,
                    'label' => $usuario->nome . ' - ' . $usuario->celular,
                ];
            }, $query->result());
            echo json_encode($result);
        }
    }
}