<?php
if (!defined('BASEPATH')) {
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
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) { // Usando uma permissão genérica de configuração
            $this->session->set_flashdata('error', 'Você não tem permissão para configurar integrações.');
            redirect(base_url());
        }

        $this->data['mensagens'] = $this->evolution_model->get('evolution_mensagens', '*', '', 100);
        $this->data['clientes'] = $this->db->get('clientes')->result();
        $this->data['logs'] = $this->evolution_model->get('evolution_logs', '*', '', 100, 0, false, 'desc');
        $this->data['usuarios'] = $this->db->get('usuarios')->result();

        $this->data['view'] = 'evolution/gerenciar';
        return $this->layout();
    }

    public function fetch_instance()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
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
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
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

        $activeTab = ltrim($this->input->post('active_tab'), '#');
        redirect('evolution/gerenciar?tab=' . $activeTab);
    }

    public function editar_mensagem()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
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
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
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

    public function excluir_logs()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) { // Usando uma permissão genérica
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir logs.');
            redirect('evolution/gerenciar#tabLogs');
        }

        $ids = $this->input->post('ids');
        if (empty($ids)) {
            $this->session->set_flashdata('error', 'Nenhum log selecionado para exclusão.');
            redirect('evolution/gerenciar#tabLogs');
        }

        $this->db->where_in('id', $ids);
        $this->db->delete('evolution_logs');

        $this->session->set_flashdata('success', 'Logs excluídos com sucesso!');
        redirect('evolution/gerenciar#tabLogs');
    }

    public function excluir_log($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir logs.');
            redirect('evolution/gerenciar#tabLogs');
        }

        if ($this->evolution_model->delete('evolution_logs', 'id', $id)) {
            $this->session->set_flashdata('success', 'Log excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir log.');
        }
        redirect('evolution/gerenciar#tabLogs');
    }

    public function limpar_logs()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para limpar logs.');
            redirect('evolution/gerenciar#tabLogs');
        }

        if ($this->db->empty_table('evolution_logs')) {
            $this->session->set_flashdata('success', 'Todos os logs foram apagados com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao limpar logs.');
        }
        redirect('evolution/gerenciar#tabLogs');
    }

    public function enviar_mensagem()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
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
        $cursoId = $this->input->post('curso_id');
        $viagemId = $this->input->post('viagem_id');

        $mensagem = $this->evolution_model->getById($mensagemId);
        if (!$mensagem) {
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
                $mensagem->mensagem = str_replace('{LINK_CLIENTE}', base_url('index.php/mine/loginCliente/') . $contato->idClientes, $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DATA_CADASTRO}', isset($contato->dataCadastro) ? date('d/m/Y', strtotime($contato->dataCadastro)) : '', $mensagem->mensagem);
            }

            // Substituição de variáveis de Curso
            if ($cursoId) {
                $this->load->model('cursos_model');
                $curso = $this->cursos_model->getById($cursoId);
                $mensagem->mensagem = str_replace('{NOME_CURSO}', $curso->nome_curso ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DATA_INICIO_CURSO}', isset($curso->data_inicio) ? date('d/m/Y', strtotime($curso->data_inicio)) : '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DATA_FIM_CURSO}', isset($curso->data_fim) ? date('d/m/Y', strtotime($curso->data_fim)) : '', $mensagem->mensagem);
            }
            // Substituição de variáveis de Viagem
            if ($viagemId) {
                $this->load->model('viagens_model');
                $viagem = $this->viagens_model->getById($viagemId);
                $mensagem->mensagem = str_replace('{NOME_VIAGEM}', $viagem->nome_viagem ?? '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DATA_PARTIDA_VIAGEM}', isset($viagem->data_partida) ? date('d/m/Y', strtotime($viagem->data_partida)) : '', $mensagem->mensagem);
                $mensagem->mensagem = str_replace('{DATA_RETORNO_VIAGEM}', isset($viagem->data_retorno) ? date('d/m/Y', strtotime($viagem->data_retorno)) : '', $mensagem->mensagem);
            }
        } else {
            $numerosParaEnvio = array_map(function ($num) {
                return preg_replace('/[^0-9]/', '', $num);
            }, explode(',', $numeros[0]));
        }

        // Define o endpoint fixo para envio de texto
        $url = rtrim($apiUrl, '/') . "/message/sendText/{$instanceName}";

        $sucessos = 0;
        $falhas = 0;

        $presence = $this->mapos_model->get_ci_config('evolution_presence') ?: 'composing';
        $delayFixo = (int) ($this->mapos_model->get_ci_config('evolution_delay_fixo') ?: 1200);
        $delayMin = (int) ($this->mapos_model->get_ci_config('evolution_delay_min') ?: 1000);
        $delayMax = (int) ($this->mapos_model->get_ci_config('evolution_delay_max') ?: 5000);

        $useRandomDelay = $delayFixo <= 0;

        foreach ($numerosParaEnvio as $numero) {
            $delay = $useRandomDelay ? rand($delayMin, $delayMax) : $delayFixo;

            // Prepara a mensagem para o formato de texto puro do WhatsApp
            $text = $mensagem->mensagem;

            // 1. Converte tags de formatação HTML para o formato do WhatsApp, removendo espaços adjacentes
            // Negrito: <b>, <strong>
            $text = preg_replace(['/<b>\s*/i', '/\s*<\/b>/i', '/<strong>\s*/i', '/\s*<\/strong>/i'], '*', $text);

            // Itálico: <i>, <em>
            $text = preg_replace(['/<i>\s*/i', '/\s*<\/i>/i', '/<em>\s*/i', '/\s*<\/em>/i'], '_', $text);

            // Riscado: <s>, <strike>, <del>
            $text = preg_replace(['/<s>\s*/i', '/\s*<\/s>/i', '/<strike>\s*/i', '/\s*<\/strike>/i', '/<del>\s*/i', '/\s*<\/del>/i'], '~', $text);

            // O WhatsApp não suporta sublinhado com caracteres especiais, então a tag <u> será removida por strip_tags.

            // 2. Converte parágrafos e quebras de linha
            $textWithLineBreaks = str_replace('</p>', "\n\n", $text);
            $textWithLineBreaks = preg_replace('/<br\s?\/?>/i', "\n", $textWithLineBreaks);

            // 3. Remove todas as outras tags HTML restantes e espaços extras
            $plainTextMessage = trim(strip_tags($textWithLineBreaks));


            $payload = [
                'number' => $numero,
                'options' => [
                    'delay' => (int) $delay,
                    'presence' => $presence,
                    'linkPreview' => false,
                ]
            ];

            // Monta o payload para mensagem de texto, com a propriedade 'text' no root
            $payload['text'] = $plainTextMessage;

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

            // Salva o log no banco de dados
            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'endpoint' => $url,
                'phone_number' => $numero,
                'request_payload' => json_encode($payload),
                'response_code' => $httpcode,
                'response_body' => $response,
                'curl_error' => $err,
            ];
            $this->evolution_model->add('evolution_logs', $logData);

            ($httpcode >= 200 && $httpcode < 300) ? $sucessos++ : $falhas++;
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => "Envio concluído: {$sucessos} com sucesso, {$falhas} com falha."]));
    }

    public function enviar_mensagem_novo()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Acesso não autorizado.']));
        }

        $apiUrl = $this->mapos_model->get_ci_config('evolution_api_url');
        $apiKey = $this->mapos_model->get_ci_config('evolution_api_key');
        $instanceName = $this->mapos_model->get_ci_config('evolution_api_instance');

        if (empty($apiUrl) || empty($apiKey) || empty($instanceName)) {
            return $this->output->set_status_header(400)->set_output(json_encode(['message' => 'Configurações da API Evolution incompletas.']));
        }

        $mensagemId = $this->input->post('mensagem_id');
        $mensagemOriginal = $this->evolution_model->getById($mensagemId);
        if (!$mensagemOriginal) {
            return $this->output->set_status_header(404)->set_output(json_encode(['message' => 'Modelo de mensagem não encontrado.']));
        }

        $alvos = $this->input->post('alvo') ?: [];
        $contatosParaEnvio = [];

        // Processa Clientes
        if (in_array('clientes', $alvos)) {
            $tipoCliente = $this->input->post('tipo_cliente');
            if ($tipoCliente === 'selecionar') {
                $clientesIds = $this->input->post('clientes_ids') ? explode(',', $this->input->post('clientes_ids')) : [];
                $contatosParaEnvio = array_merge($contatosParaEnvio, $this->evolution_model->getContatos($clientesIds, 'clientes', 'idClientes'));
            } else { // "todos"
                $cursosIds = $this->input->post('cursos_ids') ? explode(',', $this->input->post('cursos_ids')) : [];
                $viagensIds = $this->input->post('viagens_ids') ? explode(',', $this->input->post('viagens_ids')) : [];
                $aniversariantesSemana = $this->input->post('aniversariantes_semana');

                if ($aniversariantesSemana) {
                    $contatosParaEnvio = array_merge($contatosParaEnvio, $this->evolution_model->getAniversariantesDaSemana());
                } else {
                    $clientesCursos = !empty($cursosIds) ? $this->evolution_model->getClientesByCurso($cursosIds) : null;
                    $clientesViagens = !empty($viagensIds) ? $this->evolution_model->getClientesByViagem($viagensIds) : [];

                    if ($clientesCursos !== null && !empty($clientesViagens)) {
                        $idsCursos = array_map(function ($c) {
                            return $c->idClientes;
                        }, $clientesCursos);
                        $idsViagens = array_map(function ($v) {
                            return $v->idClientes;
                        }, $clientesViagens);
                        $clientesIds = array_intersect($idsCursos, $idsViagens);
                        if (!empty($clientesIds)) {
                            $contatosParaEnvio = array_merge($contatosParaEnvio, $this->evolution_model->getContatos($clientesIds, 'clientes', 'idClientes'));
                        }
                    } elseif ($clientesCursos !== null) {
                        $contatosParaEnvio = array_merge($contatosParaEnvio, $clientesCursos);
                    } elseif (!empty($clientesViagens)) {
                        $contatosParaEnvio = array_merge($contatosParaEnvio, $clientesViagens);
                    } else {
                        $contatosParaEnvio = array_merge($contatosParaEnvio, $this->evolution_model->getAllContatos('clientes'));
                    }
                }
            }
        }

        // Processa Usuários
        if (in_array('usuarios', $alvos)) {
            $tipoUsuario = $this->input->post('tipo_usuario');
            if ($tipoUsuario === 'selecionar') {
                $usuariosIds = $this->input->post('usuarios_ids') ? explode(',', $this->input->post('usuarios_ids')) : [];
                $contatosParaEnvio = array_merge($contatosParaEnvio, $this->evolution_model->getContatos($usuariosIds, 'usuarios', 'idUsuarios'));
            } else { // "todos"
                $permissoesIds = $this->input->post('permissoes_ids') ? explode(',', $this->input->post('permissoes_ids')) : [];
                $cursosIdsUsuarios = $this->input->post('cursos_ids_usuarios') ? explode(',', $this->input->post('cursos_ids_usuarios')) : [];
                $viagensIdsUsuarios = $this->input->post('viagens_ids_usuarios') ? explode(',', $this->input->post('viagens_ids_usuarios')) : [];

                $usersByPermissao = !empty($permissoesIds) ? $this->evolution_model->getUsuariosByPermissao($permissoesIds) : null;
                $usersByCurso = !empty($cursosIdsUsuarios) ? $this->evolution_model->getUsuariosByCurso($cursosIdsUsuarios) : null;
                $usersByViagem = !empty($viagensIdsUsuarios) ? $this->evolution_model->getUsuariosByViagem($viagensIdsUsuarios) : null;

                // Se nenhum filtro foi selecionado, busca todos
                if ($usersByPermissao === null && $usersByCurso === null && $usersByViagem === null) {
                    $contatosParaEnvio = array_merge($contatosParaEnvio, $this->evolution_model->getAllContatos('usuarios'));
                } else {
                    $filteredUsers = [];
                    // Inicializa com o primeiro resultado não nulo encontrado, ou array vazio
                    if ($usersByPermissao !== null)
                        $filteredUsers = $usersByPermissao;
                    elseif ($usersByCurso !== null)
                        $filteredUsers = $usersByCurso;
                    elseif ($usersByViagem !== null)
                        $filteredUsers = $usersByViagem;

                    // Intersecta com os outros resultados não nulos para fazer um AND (E)
                    // Ou devemos fazer OR (OU)? Geralmente filtros acumulativos são AND.
                    // O cliente pediu "usuarios em viagem E em curso" (filtro), então AND faz sentido se ambos selecionados.
                    // Mas se eu selecionar permissao X e curso Y, eu quero usuarios que tenham permissao X E estejam no curso Y.

                    if ($usersByPermissao !== null) {
                        $ids = array_map(function ($u) {
                            return $u->idUsuarios;
                        }, $usersByPermissao);
                        $filteredUsers = array_filter($filteredUsers, function ($u) use ($ids) {
                            return in_array($u->idUsuarios, $ids);
                        });
                    }
                    if ($usersByCurso !== null) {
                        $ids = array_map(function ($u) {
                            return $u->idUsuarios;
                        }, $usersByCurso);
                        $filteredUsers = array_filter($filteredUsers, function ($u) use ($ids) {
                            return in_array($u->idUsuarios, $ids);
                        });
                    }
                    if ($usersByViagem !== null) {
                        $ids = array_map(function ($u) {
                            return $u->idUsuarios;
                        }, $usersByViagem);
                        $filteredUsers = array_filter($filteredUsers, function ($u) use ($ids) {
                            return in_array($u->idUsuarios, $ids);
                        });
                    }

                    // Reindex array keys after filtering
                    $contatosParaEnvio = array_merge($contatosParaEnvio, array_values($filteredUsers));
                }
            }
        }

        // Mapeia os contatos para um formato unificado {numero, data}
        $destinatarios = [];
        foreach ($contatosParaEnvio as $contato) {
            $numero = null;
            if (isset($contato->idClientes)) { // É um cliente
                $numero = $contato->celular;
            } elseif (isset($contato->idUsuarios)) { // É um usuário
                $numero = $contato->celular;
            }

            if ($numero) {
                $numeroLimpo = preg_replace('/[^0-9]/', '', $numero);
                if (strlen($numeroLimpo) >= 10) {
                    if (strlen($numeroLimpo) <= 11) {
                        $destinatarios[$numeroLimpo] = ['numero' => '55' . $numeroLimpo, 'dados' => $contato];
                    } else {
                        $destinatarios[$numeroLimpo] = ['numero' => $numeroLimpo, 'dados' => $contato];
                    }
                }
            }
        }

        // Processa Números Específicos
        if (in_array('especifico', $alvos)) {
            $numerosEspecificos = $this->input->post('numeros_especificos');
            $numerosArray = preg_split('/[,\s\n]+/ ', $numerosEspecificos, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($numerosArray as $numero) {
                $numeroLimpo = preg_replace('/[^0-9]/', '', $numero);
                if (strlen($numeroLimpo) >= 10) {
                    $destinatarios[$numeroLimpo] = ['numero' => $numeroLimpo, 'dados' => null]; // Sem dados para substituição
                }
            }
        }

        if (empty($destinatarios)) {
            return $this->output->set_status_header(400)->set_output(json_encode(['message' => 'Nenhum destinatário válido encontrado.']));
        }

        // Lógica de envio (adaptada do método antigo)
        $url = rtrim($apiUrl, '/') . "/message/sendText/{$instanceName}";
        $sucessos = 0;
        $falhas = 0;
        $presence = $this->mapos_model->get_ci_config('evolution_presence') ?: 'composing';
        $delayFixo = (int) ($this->mapos_model->get_ci_config('evolution_delay_fixo') ?: 1200);
        $delayMin = (int) ($this->mapos_model->get_ci_config('evolution_delay_min') ?: 1000);
        $delayMax = (int) ($this->mapos_model->get_ci_config('evolution_delay_max') ?: 5000);

        $totalDestinatarios = count($destinatarios);

        foreach ($destinatarios as $destinatario) {
            $mensagemFinal = $mensagemOriginal->mensagem;
            $dados = $destinatario['dados'];

            // Define o delay
            // Se delay fixo estiver configurado (> 0), usa ele.
            // Se não, usa randômico entre Min e Max.
            if ($delayFixo > 0) {
                $delay = $delayFixo;
            } else {
                $delay = rand($delayMin, $delayMax);
            }

            // Substituição de variáveis
            if ($dados) {
                if (isset($dados->idClientes)) { // Cliente
                    $mensagemFinal = str_replace('{NOME_CLIENTE}', $dados->nomeCliente ?? '', $mensagemFinal);
                    $mensagemFinal = str_replace('{EMAIL_CLIENTE}', $dados->email ?? '', $mensagemFinal);
                    $mensagemFinal = str_replace('{TELEFONE_CLIENTE}', $dados->telefone ?? '', $mensagemFinal);
                    $mensagemFinal = str_replace('{CELULAR_CLIENTE}', $dados->celular ?? '', $mensagemFinal);
                } elseif (isset($dados->idUsuarios)) { // Usuário
                    $mensagemFinal = str_replace('{NOME_USUARIO}', $dados->nome ?? '', $mensagemFinal);
                }

                // Carrega e substitui variáveis de curso/viagem se houver IDs
                $cursosIds = $this->input->post('cursos_ids') ? explode(',', $this->input->post('cursos_ids')) : [];
                if (!empty($cursosIds)) {
                    $this->load->model('cursos_model');
                    $curso = $this->cursos_model->getById($cursosIds[0]); // Pega o primeiro para substituição
                    $mensagemFinal = str_replace('{NOME_CURSO}', $curso->nome_curso ?? '', $mensagemFinal);
                    $mensagemFinal = str_replace('{DATA_INICIO_CURSO}', isset($curso->data_inicio) ? date('d/m/Y', strtotime($curso->data_inicio)) : '', $mensagemFinal);
                    $mensagemFinal = str_replace('{DATA_FIM_CURSO}', isset($curso->data_fim) ? date('d/m/Y', strtotime($curso->data_fim)) : '', $mensagemFinal);
                }
                $viagensIds = $this->input->post('viagens_ids') ? explode(',', $this->input->post('viagens_ids')) : [];
                if (!empty($viagensIds)) {
                    $this->load->model('viagens_model');
                    $viagem = $this->viagens_model->getById($viagensIds[0]); // Pega a primeira para substituição
                    $mensagemFinal = str_replace('{NOME_VIAGEM}', $viagem->nome_viagem ?? '', $mensagemFinal);
                    $mensagemFinal = str_replace('{DATA_PARTIDA_VIAGEM}', isset($viagem->data_partida) ? date('d/m/Y', strtotime($viagem->data_partida)) : '', $mensagemFinal);
                    $mensagemFinal = str_replace('{DATA_RETORNO_VIAGEM}', isset($viagem->data_retorno) ? date('d/m/Y', strtotime($viagem->data_retorno)) : '', $mensagemFinal);
                }
            }

            // Prepara a mensagem para o formato de texto puro do WhatsApp
            $text = $mensagemFinal;

            // 1. Converte tags de formatação HTML para o formato do WhatsApp

            // Decodifica entidades HTML e normaliza espaços
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text = str_replace(["\xc2\xa0", "&nbsp;"], ' ', $text);

            // 2. Converte parágrafos e quebras de linha ANTES da formatação
            // Para evitar conflitos como <b><br></b> virando * \n *
            $text = str_replace('</p>', "\n\n", $text);
            $text = preg_replace('/<br\s?\/?>/i', "\n", $text);
            // Remove tags <p> restantes
            $text = str_replace('<p>', '', $text);

            // 3. Limpeza de tags vazias ou com apenas whitespace (agora incluindo \n gerados)
            $text = preg_replace('/<(b|strong|i|em|s|strike|del)[^>]*>\s*<\/\1>/iu', '', $text);

            // 4. Converte tags de formatação restantes
            // Negrito: <b>, <strong> -> *texto*
            $text = preg_replace(['/<b>\s*/iu', '/\s*<\/b>/iu', '/<strong>\s*/iu', '/\s*<\/strong>/iu'], '*', $text);

            // Itálico: <i>, <em> -> _texto_
            $text = preg_replace(['/<i>\s*/iu', '/\s*<\/i>/iu', '/<em>\s*/iu', '/\s*<\/em>/iu'], '_', $text);

            // Riscado: <s>, <strike>, <del> -> ~texto~
            $text = preg_replace(['/<s>\s*/iu', '/\s*<\/s>/iu', '/<strike>\s*/iu', '/\s*<\/strike>/iu', '/<del>\s*/iu', '/\s*<\/del>/iu'], '~', $text);

            // 5. Remove todas as outras tags HTML restantes
            $plainTextMessage = trim(strip_tags($text));

            // Remove múltiplos espaços/quebras de linha excessivos
            $plainTextMessage = preg_replace("/\n{3,}/", "\n\n", $plainTextMessage);
            // Remove espaços duplos
            $plainTextMessage = preg_replace('/[ \t]+/', ' ', $plainTextMessage);


            $payload = [
                'number' => $destinatario['numero'],
                'options' => ['delay' => $delay, 'presence' => $presence],
                'text' => $plainTextMessage,
            ];

            // Envio via cURL (simplificado para brevidade)
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

            // Log
            $this->evolution_model->add('evolution_logs', [
                'timestamp' => date('Y-m-d H:i:s'),
                'phone_number' => $destinatario['numero'],
                'request_payload' => json_encode($payload),
                'response_code' => $httpcode,
                'response_body' => $response,
                'curl_error' => $err,
            ]);

            ($httpcode >= 200 && $httpcode < 300) ? $sucessos++ : $falhas++;
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true, 'message' => "Envio concluído: {$sucessos} com sucesso, {$falhas} com falha."]));
    }

    public function autoComplete($alvo = null)
    {
        if ($this->input->get('term')) {
            $q = strtolower($this->input->get('term'));
        } elseif ($this->input->get('ids')) {
            $q = $this->input->get('ids');
        } else {
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }

        if ($alvo === 'clientes') {
            $this->db->select("idClientes as id, CONCAT('ID: ', idClientes, ' | ', nomeCliente, ' | Cel: ', celular) as text", false);
            $this->input->get('ids') ? $this->db->where_in('idClientes', explode(',', $q)) : $this->db->like('LOWER(nomeCliente)', $q);
            $this->db->limit(10);
            $query = $this->db->get('clientes');
        } elseif ($alvo === 'usuarios') {
            $this->db->select("idUsuarios as id, CONCAT('ID: ', idUsuarios, ' | ', nome, ' | Cel: ', celular) as text", false);
            $this->input->get('ids') ? $this->db->where_in('idUsuarios', explode(',', $q)) : $this->db->like('LOWER(nome)', $q);
            $this->db->limit(10);
            $query = $this->db->get('usuarios');
        } else {
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }

        $result = $query->result();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function autoCompletePermissao()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select("idPermissao as id, nome as text", false);
            $this->db->like('LOWER(nome)', $q);
            $this->db->where('situacao', 1);
        } elseif (isset($_GET['ids'])) {
            $ids = explode(',', $_GET['ids']);
            $this->db->select("idPermissao as id, nome as text", false);
            $this->db->where_in('idPermissao', $ids);
        } else {
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }
        $this->db->limit(10);
        $query = $this->db->get('permissoes');
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($query->result()));
    }

    public function log_ajax_error()
    {
        // This method is intended for client-side AJAX error logging.
        // While it's generally good practice to have permission checks,
        // for debugging purposes, we might allow this without strict permissions
        // to capture all potential client-side issues.
        // If you need to restrict this, uncomment the permission check below.
        /*
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['message' => 'Acesso não autorizado para logar erros.']));
        }
        */

        $errorMessage = $this->input->post('error_message');
        $responseText = $this->input->post('response_text');
        $statusCode = $this->input->post('status_code');

        $logData = "Client-side AJAX Error:" . PHP_EOL
            . "Message: " . ($errorMessage ?: 'N/A') . PHP_EOL
            . "Status Code: " . ($statusCode ?: 'N/A') . PHP_EOL
            . "Response Text: " . ($responseText ?: 'N/A') . PHP_EOL
            . "User Agent: " . $this->input->user_agent() . PHP_EOL
            . "IP Address: " . $this->input->ip_address();
        log_message('error', $logData);

        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => 'success', 'message' => 'Error logged successfully.']));
    }
}