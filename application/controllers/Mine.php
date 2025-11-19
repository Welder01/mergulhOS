<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mine extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('restricao_alimentar_model');
        $this->load->model('viagens_model');
        $this->load->model('curso_alunos_model');
        $this->load->model('cursos_model');
        $this->load->model('mapos_model');
        $this->load->library('upload');
        $this->load->model('Conecte_model');
        $this->load->helper('Security_helper');

        $configs = $this->mapos_model->get('configuracoes', '*');
        $this->data['configuration'] = [];
        foreach ($configs as $c) {
            $this->data['configuration'][$c->config] = $c->valor;
        }
    }

    public function index()
    {
        if ($this->session->userdata('cliente_id') && $this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/painel');
        }

        $this->data['view'] = 'conecte/login';
        $this->load->view('conecte/login', $this->data);
    }

    public function sair()
    {
        $this->session->sess_destroy();
        log_info('O cliente saiu da área do cliente.');
        redirect(base_url() . 'index.php/mine/login');
    }

    public function resetarSenha()
    {
        $this->load->view('conecte/resetar_senha', $this->data);
    }

    /**
     * @deprecated
     * @see self::senhaSalvarComAjax()
     */
    public function senhaSalvar()
    {
        $this->load->library('form_validation');
        $data['custom_error'] = '';
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->input->post('token') == null || $this->input->post('token') == '') {
            return redirect('mine');
        }
        if ($this->form_validation->run() == false) {
            echo json_encode(['result' => false, 'message' => 'Por favor digite uma senha']);
        } else {
            $token = $this->check_token($this->input->post('token'));
            $cliente = $this->check_credentials($token->email);

            if ($token == null && $cliente == null) {
                $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                $this->session->set_userdata($session_mine_data);
                log_info('Alteração de senha. Porém, os dados de acesso estão incorretos.');
                echo json_encode(['result' => false, 'message' => 'Os dados de acesso estão incorretos.']);
            } else {
                if ($token->email == $cliente->email) {
                    $data = [
                        'senha' => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                    ];

                    $dataToken = [
                        'token_utilizado' => true,
                    ];
                    $this->load->model('resetSenhas_model', '', true);
                    if ($this->Conecte_model->edit('clientes', $data, 'idClientes', $cliente->idClientes) == true) {
                        if ($this->resetSenhas_model->edit('resets_de_senha', $dataToken, 'id', $token->id) == true) {
                            $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                            $this->session->set_userdata($session_mine_data);
                            log_info('Alteração da senha realizada com sucesso.');
                            echo json_encode(['result' => true]);
                        }
                    }
                } else {
                    $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                    $this->session->set_userdata($session_mine_data);
                    log_info('Alteração de senha. Porém, dados divergentes.');
                    echo json_encode(['result' => false, 'message' => 'Dados divergentes.']);
                }
            }
        }
    }

    public function tokenManual()
    {
        $this->load->library('form_validation');
        $data['custom_error'] = '';
        $this->form_validation->set_rules('token', 'Token', 'required');

        if ($this->form_validation->run('token') == false) {
            $this->session->set_flashdata(['error' => (validation_errors() ? 'Por favor digite o token' : false)]);

            return $this->load->view('conecte/token_digita', $this->data);
        }
            $token = $this->check_token($this->input->post('token'));

            if ($this->validateDate($token->data_expiracao)) {
                $this->session->set_flashdata(['error' => 'Token expirado']);
                $session_mine_data = $token->email ? ['nome' => $token->email] : ['nome' => 'Inexistente'];
                $this->session->set_userdata($session_mine_data);
                log_info('Digitou Token. Porém, Token expirado');

                return redirect(base_url() . 'index.php/mine/login', $this->data);
            } else {
                if ($token) {
                    if (($cliente = $this->check_credentials($token->email)) == null) {
                        $this->session->set_flashdata(['error' => 'Os dados de acesso estão incorretos.']);
                        $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                        $this->session->set_userdata($session_mine_data);
                        log_info('Digitou Token. Porém, os dados de acesso estão incorretos.');

                        return $this->load->view('conecte/token_digita', $this->data);
                    } else {
                        if ($token->email == $cliente->email && $token->token_utilizado == false) {
                            return $this->load->view('conecte/nova_senha', $token);
                        } else {
                            $this->session->set_flashdata('error', 'Dados divergentes ou Token invalido.');
                            $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                            $this->session->set_userdata($session_mine_data);
                            log_info('Digitou Token. Porém, dados divergentes ou Token invalido.');

                            return redirect(base_url() . 'index.php/mine/login', $this->data);
                        }
                    }
                } else {
                    $this->session->set_flashdata(['error' => 'Token Invalido']);
                    $session_mine_data = $token->email ? ['nome' => $token->email] : ['nome' => 'Inexistente'];
                    $this->session->set_userdata($session_mine_data);
                    log_info('Digitou Token. Porém, Token invalido.');

                    return $this->load->view('conecte/token_digita', $this->data);
                }
            }
        $this->load->view('conecte/token_digita', $this->data);
    }

    public function verifyTokenSenha()
    {
        $token = $this->uri->uri_to_assoc(3);
        $token = $this->check_token($token['token']);

        if ($token == null || $token == '') {
            $this->session->set_flashdata(['error' => 'Token invalido']);
            $session_mine_data = $token->email ? ['nome' => $token->email] : ['nome' => 'Inexistente'];
            $this->session->set_userdata($session_mine_data);
            log_info('Acesso via link do email (Token). Porém, Token invalido.');

            return $this->load->view('conecte/token_digita', $this->data);
        } else {
            if ($this->validateDate($token->data_expiracao)) {
                $this->session->set_flashdata(['error' => 'Token expirado']);
                $session_mine_data = $token->email ? ['nome' => $token->email] : ['nome' => 'Inexistente'];
                $this->session->set_userdata($session_mine_data);
                log_info('Acesso via link do email (Token). Porém, Token expirado');

                return redirect(base_url() . 'index.php/mine/login', $this->data);
            } else {
                if ($token) {
                    if (($cliente = $this->check_credentials($token->email)) == null) {
                        $this->session->set_flashdata(['error' => 'Os dados de acesso estão incorretos.']);
                        $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                        $this->session->set_userdata($session_mine_data);
                        log_info('Acesso via link do email (Token). Porém, dados de acesso estão incorretos.');

                        return $this->load->view('conecte/token_digita', $this->data);
                    } else {
                        if ($token->email == $cliente->email && $token->token_utilizado == false) {
                            return $this->load->view('conecte/nova_senha', $token);
                        } else {
                            $this->session->set_flashdata('error', 'Dados divergentes ou Token invalido.');
                            $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                            $this->session->set_userdata($session_mine_data);
                            log_info('Acesso via link do email (Token). Porém, dados divergentes ou Token invalido.');

                            return redirect(base_url() . 'index.php/mine/login', $this->data);
                        }
                    }
                } else {
                    $this->session->set_flashdata(['error' => 'Token Invalido']);
                    $session_mine_data = $token->email ? ['nome' => $token->email] : ['nome' => 'Inexistente'];
                    $this->session->set_userdata($session_mine_data);
                    log_info('Acesso via link do email (Token). Porém, Token invalido.');

                    return $this->load->view('conecte/token_digita', $this->data);
                }

                return $this->load->view('conecte/nova_senha', $token);
            }
        }
    }

    public function gerarTokenResetarSenha()
    {
        if (! $cliente = $this->check_credentials($this->input->post('email'))) {
            $this->session->set_flashdata(['error' => 'Os dados de acesso estão incorretos.']);
            $session_mine_data = $cliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
            $this->session->set_userdata($session_mine_data);
            log_info('Cliente solicitou alteração de senha. Porém falhou ao realizar solicitação!');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->load->helper('string');
            $this->load->model('resetSenhas_model', '', true);
            $data = [
                'email' => $cliente->email,
                'token' => random_string('alnum', 32),
                'data_expiracao' => date('Y-m-d H:i:s'),
            ];
            if ($this->resetSenhas_model->add('resets_de_senha', $data) == true) {
                $this->enviarRecuperarSenha($cliente->idClientes, $cliente->email, 'Recuperar Senha', json_encode($data));
                $session_mine_data = ['nome' => $cliente->nomeCliente];
                $this->session->set_userdata($session_mine_data);
                log_info('Cliente solicitou alteração de senha.');
                $this->session->set_flashdata('success', 'Solicitação realizada com sucesso! <br> Um e-mail com as instruções será enviado para ' . $cliente->email);
                redirect(base_url() . 'index.php/mine/login');
            } else {
                $this->session->set_flashdata('error', 'Falha ao realizar solicitação!');
                $session_mine_data = $cliente->nomeCliente ? ['nome' => $cliente->nomeCliente] : ['nome' => 'Inexistente'];
                $this->session->set_userdata($session_mine_data);
                log_info('Cliente solicitou alteração de senha. Porém falhou ao realizar solicitação!');
                redirect(current_url());
            }
        }
    }

    public function login()
    {
        header('Access-Control-Allow-Origin: ' . base_url());
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type');

        $this->lang->load('form_validation');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'E-mail', 'valid_email|required|trim');
        $this->form_validation->set_rules('senha', 'Senha', 'required|trim');
        if ($this->form_validation->run() == false) {
            echo json_encode(['result' => false, 'message' => validation_errors()]);
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('senha');
            $cliente = $this->check_credentials($email);

            if ($cliente) {
                // Verificar credenciais do usuário
                if (password_verify($password, $cliente->senha)) {
                    $session_mine_data = [
                        'nome' => $cliente->nomeCliente, 
                        'cliente_id' => $cliente->idClientes, 
                        'email' => $cliente->email, 
                        'conectado' => true, 
                        'isCliente' => true
                    ];
                    $this->session->set_userdata($session_mine_data);
                    log_info($_SERVER['REMOTE_ADDR'] . ' Efetuou login no sistema');

                    // Registrar login na auditoria
                    $this->load->model('Audit_model');
                    $log_data = [
                        'usuario' => $cliente->nomeCliente,
                        'tarefa' => 'Cliente ' . $cliente->nomeCliente . ' efetuou login',
                        'data' => date('Y-m-d'),
                        'hora' => date('H:i:s'),
                        'ip' => $_SERVER['REMOTE_ADDR']
                    ];

                    $this->Audit_model->add($log_data);

                    echo json_encode(['result' => true]);
                } else {
                    echo json_encode(['result' => false, 'message' => 'Os dados de acesso estão incorretos.', 'MAPOS_TOKEN' => $this->security->get_csrf_hash()]);
                }
            } else {
                echo json_encode(['result' => false, 'message' => 'Usuário não encontrado, verifique se suas credenciais estão corretas.', 'MAPOS_TOKEN' => $this->security->get_csrf_hash()]);
            }
        }
    }

    public function painel()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuPainel'] = 'painel';
        $this->data['compras'] = $this->Conecte_model->getLastCompras($this->session->userdata('cliente_id'));
        $this->data['os'] = $this->Conecte_model->getLastOs($this->session->userdata('cliente_id'));
        $this->data['output'] = 'conecte/painel';

        // Verifica se o cliente está em alguma viagem futura e se o perfil está incompleto
        $this->data['alerta_perfil_incompleto'] = false;
        $viagensCliente = $this->viagens_model->getViagensByCliente($this->session->userdata('cliente_id'));
        $temViagemFutura = false;
        foreach ($viagensCliente as $viagem) {
            if (strtotime($viagem->data_partida) >= strtotime(date('Y-m-d'))) {
                $temViagemFutura = true;
                break;
            }
        }

        if ($temViagemFutura) {
            $cliente = $this->Conecte_model->getDados();
            $camposObrigatorios = [
                'altura', 'peso', 'contato_emergencia_nome', 'contato_emergencia_telefone', 'atestado_medico_validade'
            ];
            foreach ($camposObrigatorios as $campo) {
                if (empty($cliente->$campo)) {
                    $this->data['alerta_perfil_incompleto'] = true;
                    break;
                }
            }
        }

        $this->load->view('conecte/template', $this->data);
    }

    public function conta()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuConta'] = 'conta';
        $this->data['result'] = $this->Conecte_model->getDados();
        $this->data['restricoes'] = $this->restricao_alimentar_model->getByCliente($this->session->userdata('cliente_id'));
        $this->data['output'] = 'conecte/conta';
        $this->load->view('conecte/template', $this->data);
    }

    public function editarDados()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuConta'] = 'conta';
        $this->data['result'] = $this->Conecte_model->getDados();

        $this->data['output'] = 'conecte/conta';

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nomeCliente', 'Nome', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
            $this->load->view('conecte/template', $this->data);
            return;
        }

        $atestado_emissao = $this->input->post('atestado_medico_emissao');
        $atestado_validade = null;
        if ($atestado_emissao) { // Check if $atestado_emissao is not empty
            $date = DateTime::createFromFormat('d/m/Y', $atestado_emissao);
            if ($date !== false) { // Check if parsing was successful
                $date->add(new DateInterval('P1Y')); // Adiciona 1 ano
                $atestado_validade = $date->format('Y-m-d');
            } else {
                log_message('error', 'Failed to parse atestado_medico_emissao in Mine/editarDados: ' . $atestado_emissao);
            }
        }

        $data = [
            'nomeCliente' => $this->input->post('nomeCliente'),
            'sexo' => $this->input->post('sexo'),
            'documento' => $this->input->post('documento'),
            'telefone' => $this->input->post('telefone'),
            'celular' => $this->input->post('celular'),
            'email' => $this->input->post('email'),
            'rua' => $this->input->post('rua'),
            'numero' => $this->input->post('numero'),
            'complemento' => $this->input->post('complemento'),
            'bairro' => $this->input->post('bairro'),
            'cidade' => $this->input->post('cidade'),
            'estado' => $this->input->post('estado'),
            'cep' => $this->input->post('cep'),
            'contato' => $this->input->post('contato'),
            'altura' => str_replace(',', '.', $this->input->post('altura')),
            'peso' => str_replace(',', '.', $this->input->post('peso')),
            'tamanho_colete' => $this->input->post('tamanho_colete') ?: null,
            'peso_lastro' => $this->input->post('peso_lastro') ?: null,
            'possui_colete' => $this->input->post('possui_colete') ? 1 : 0,
            'possui_lastro' => $this->input->post('possui_lastro') ? 1 : 0,
            'possui_neoprene' => $this->input->post('possui_neoprene') ? 1 : 0,
            'possui_nadadeira' => $this->input->post('possui_nadadeira') ? 1 : 0,
            'possui_regulador' => $this->input->post('possui_regulador') ? 1 : 0,
            'possui_lanterna' => $this->input->post('possui_lanterna') ? 1 : 0,
            'possui_computador' => $this->input->post('possui_computador') ? 1 : 0,
            'tamanho_neoprene' => $this->input->post('tamanho_neoprene') ?: null,
            'tamanho_nadadeira' => $this->input->post('tamanho_nadadeira') ?: null,
            'qtd_reguladores' => $this->input->post('qtd_reguladores') ?: 0,
            'qtd_lanterna' => $this->input->post('qtd_lanterna') ?: 0,
            'qtd_computador' => $this->input->post('qtd_computador') ?: 0,
            'contato_emergencia_nome' => $this->input->post('contato_emergencia_nome'),
            'contato_emergencia_telefone' => $this->input->post('contato_emergencia_telefone'),
            'contato_emergencia_parentesco' => $this->input->post('contato_emergencia_parentesco'),
            'atestado_medico_emissao' => $atestado_emissao ?: null,
            'atestado_medico_validade' => $atestado_validade,
            'nome_medico' => $this->input->post('nome_medico'),
            'crm_medico' => $this->input->post('crm_medico'),
            'codigo_validacao_atestado' => $this->input->post('codigo_validacao_atestado'),
        ];

        $senha = $this->input->post('senha');
        if ($senha) {
            $data['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }

        // Upload do atestado médico
        if (!empty($_FILES['atestado_medico_arquivo']['name'])) {
            $config['upload_path'] = './assets/uploads/atestados/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size'] = 5120; // 5MB
            $config['encrypt_name'] = true;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->upload->initialize($config);

            if ($this->upload->do_upload('atestado_medico_arquivo')) {
                $upload_data = $this->upload->data();
                $data['atestado_medico_arquivo'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Erro no upload do atestado: ' . $this->upload->display_errors());
                redirect(site_url('mine/conta'));
            }
        }

        if ($this->Conecte_model->edit('clientes', $data, 'idClientes', $this->session->userdata('cliente_id')) == true) {
            $this->session->set_flashdata('success', 'Dados editados com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao editar os dados.');
        }

        $activeTab = ltrim($this->input->post('active_tab'), '#');

        // Adicionar nova restrição, se houver
        if ($this->input->post('restricao')) {
            $dataRestricao = [
                'cliente_id' => $this->session->userdata('cliente_id'),
                'restricao' => $this->input->post('restricao'),
                'observacoes' => $this->input->post('observacoes_restricao')
            ];
            $this->restricao_alimentar_model->add($dataRestricao);
            $this->session->set_flashdata('success', 'Restrição alimentar adicionada com sucesso!');
            $activeTab = 'restricoes'; // Força a aba de restrições a ficar ativa
        }

        redirect(site_url('mine/conta?tab=') . $activeTab);
    }

    public function compras()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuVendas'] = 'vendas';
        $this->load->library('pagination');

        $config['base_url'] = base_url() . 'index.php/mine/compras/';
        $config['total_rows'] = $this->Conecte_model->count('vendas', $this->session->userdata('cliente_id'));
        $config['per_page'] = 10;
        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $this->data['results'] = $this->Conecte_model->getCompras('vendas', '*', '', $config['per_page'], $this->uri->segment(3), '', '', $this->session->userdata('cliente_id'));

        $this->data['output'] = 'conecte/compras';
        $this->load->view('conecte/template', $this->data);
    }

    public function cobrancas()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->load->library('pagination');
        $this->load->config('payment_gateways');

        $this->data['menuCobrancas'] = 'cobrancas';

        $config['base_url'] = base_url() . 'index.php/mine/cobrancas/';
        $config['total_rows'] = $this->Conecte_model->count('cobrancas', $this->session->userdata('cliente_id'));
        $config['per_page'] = 10;
        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $this->data['results'] = $this->Conecte_model->getCobrancas('cobrancas', '*', '', $config['per_page'], $this->uri->segment(3), '', '', $this->session->userdata('cliente_id'));
        $this->data['output'] = 'conecte/cobrancas';

        $this->load->view('conecte/template', $this->data);
    }

    public function atualizarcobranca($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar cobrança.');
            redirect(base_url());
        }

        $this->load->model('cobrancas_model');
        $this->cobrancas_model->atualizarStatus($this->uri->segment(3));

        redirect(site_url('mine/cobrancas/'));
    }

    public function enviarcobranca()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCobranca')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar cobrança.');
            redirect(base_url());
        }

        $this->load->model('cobrancas_model');
        $this->cobrancas_model->enviarEmail($this->uri->segment(3));
        $this->session->set_flashdata('success', 'Email adicionado na fila.');

        redirect(site_url('mine/cobrancas/'));
    }

    public function os()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->load->library('pagination');
        $this->data['menuOs'] = 'os';

        $config['base_url'] = base_url() . 'index.php/mine/os/';
        $config['total_rows'] = $this->Conecte_model->count('os', $this->session->userdata('cliente_id'));
        $config['per_page'] = 10;
        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $this->data['results'] = $this->Conecte_model->getOs('os', '*', '', $config['per_page'], $this->uri->segment(3), '', '', $this->session->userdata('cliente_id'));

        $this->data['output'] = 'conecte/os';
        $this->load->view('conecte/template', $this->data);
    }

    public function visualizarOs($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuOs'] = 'os';
        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->load->model('os_model');
        $this->CI = &get_instance();
        $this->CI->load->database();

        $this->data['pix_key'] = $this->CI->db->get_where('configuracoes', ['config' => 'pix_key'])->row_object()->valor;
        $this->data['result'] = $this->os_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->os_model->getProdutos($this->uri->segment(3));
        $this->data['servicos'] = $this->os_model->getServicos($this->uri->segment(3));
        $this->data['cursos'] = $this->os_model->getCursos($this->uri->segment(3));
        $this->data['viagens'] = $this->os_model->getViagens($this->uri->segment(3));
        $this->data['anexos'] = $this->os_model->getAnexos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['qrCode'] = $this->os_model->getQrCode(
            $id,
            $this->data['pix_key'],
            $this->data['emitente']
        );
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['pix_key']);

        if ($this->data['result']->idClientes != $this->session->userdata('cliente_id')) {
            $this->session->set_flashdata('error', 'Esta OS não pertence ao cliente logado.');
            redirect('mine/painel');
        }

        $this->data['output'] = 'conecte/visualizar_os';
        $this->load->view('conecte/template', $this->data);
    }

    public function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }
        $soma1 = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma1 += $cpf[$i] * (10 - $i);
        }
        $resto1 = $soma1 % 11;
        $dv1 = ($resto1 < 2) ? 0 : 11 - $resto1;
        if ($dv1 != $cpf[9]) {
            return false;
        }
        $soma2 = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma2 += $cpf[$i] * (11 - $i);
        }
        $resto2 = $soma2 % 11;
        $dv2 = ($resto2 < 2) ? 0 : 11 - $resto2;

        return $dv2 == $cpf[10];
    }

    public function validarCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }
        $soma1 = 0;
        for ($i = 0, $pos = 5; $i < 12; $i++, $pos--) {
            $pos = ($pos < 2) ? 9 : $pos;
            $soma1 += $cnpj[$i] * $pos;
        }
        $dv1 = ($soma1 % 11 < 2) ? 0 : 11 - ($soma1 % 11);
        if ($dv1 != $cnpj[12]) {
            return false;
        }
        $soma2 = 0;
        for ($i = 0, $pos = 6; $i < 13; $i++, $pos--) {
            $pos = ($pos < 2) ? 9 : $pos;
            $soma2 += $cnpj[$i] * $pos;
        }
        $dv2 = ($soma2 % 11 < 2) ? 0 : 11 - ($soma2 % 11);

        return $dv2 == $cnpj[13];
    }

    public function formatarChave($chave)
    {
        if ($this->validarCPF($chave)) {
            return substr($chave, 0, 3) . '.' . substr($chave, 3, 3) . '.' . substr($chave, 6, 3) . '-' . substr($chave, 9);
        } elseif ($this->validarCNPJ($chave)) {
            return substr($chave, 0, 2) . '.' . substr($chave, 2, 3) . '.' . substr($chave, 5, 3) . '/' . substr($chave, 8, 4) . '-' . substr($chave, 12);
        } elseif (strlen($chave) === 11) {
            return '(' . substr($chave, 0, 2) . ') ' . substr($chave, 2, 5) . '-' . substr($chave, 7);
        }

        return $chave;
    }

    public function gerarPagamentoGerencianetBoleto()
    {
        print_r(json_encode(['code' => 4001, 'error' => 'Erro interno', 'errorDescription' => 'Cobrança não pode ser gerada pelo lado do cliente']));

    }

    public function gerarPagamentoGerencianetLink()
    {
        print_r(json_encode(['code' => 4001, 'error' => 'Erro interno', 'errorDescription' => 'Cobrança não pode ser gerada pelo lado do cliente']));

    }

    public function imprimirOs($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuOs'] = 'os';
        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->load->model('os_model');
        $this->data['result'] = $this->os_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->os_model->getProdutos($this->uri->segment(3));
        $this->data['servicos'] = $this->os_model->getServicos($this->uri->segment(3));
        $this->data['cursos'] = $this->os_model->getCursos($this->uri->segment(3));
        $this->data['viagens'] = $this->os_model->getViagens($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['pix_key'] = $this->db->get_where('configuracoes', ['config' => 'pix_key'])->row_object()->valor;
        $this->data['qrCode'] = $this->os_model->getQrCode(
            $id,
            $this->data['pix_key'],
            $this->data['emitente']
        );
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['pix_key']);

        if ($this->data['result']->idClientes != $this->session->userdata('cliente_id')) {
            $this->session->set_flashdata('error', 'Esta OS não pertence ao cliente logado.');
            redirect('mine/painel');
        }

        $this->load->view('conecte/imprimirOs', $this->data);
    }

    public function visualizarCompra($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuVendas'] = 'vendas';
        $this->data['custom_error'] = '';
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->load->model('mapos_model');
        $this->load->model('os_model');
        $this->load->model('vendas_model');        

        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();
        $this->data['pix_key'] = $this->CI->db->get_where('configuracoes', ['config' => 'pix_key'])->row_object()->valor;
        $this->data['qrCode'] = $this->vendas_model->getQrCode(
            $id,
            $data['pix_key'],
            $data['emitente']
        );
        $data['chaveFormatada'] = $this->formatarChave($data['pix_key']);
        
        if ($data['result']->clientes_id != $this->session->userdata('cliente_id')) {
            $this->session->set_flashdata('error', 'Esta OS não pertence ao cliente logado.');
            redirect('mine/painel');
        }

        $this->data['output'] = 'conecte/visualizar_compra';

        $this->load->view('conecte/template', $this->data);
    }

    public function imprimirCompra($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['menuVendas'] = 'vendas';
        $this->data['custom_error'] = '';

        $this->load->model('mapos_model');
        $this->load->model('vendas_model');
        $this->load->model('os_model');

        $this->data['result'] = $this->vendas_model->getById($id);
        $this->data['produtos'] = $this->vendas_model->getProdutos($id);
        $this->data['emitente'] = $this->mapos_model->getEmitente();

        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->data['pix_key'] = $this->CI->db->get_where('configuracoes', ['config' => 'pix_key'])->row_object()->valor;
        $this->data['qrCode'] = $this->vendas_model->getQrCode($id, $this->data['pix_key'], $this->data['emitente']);
        $this->data['chaveFormatada'] = $this->formatarChave($this->data['pix_key']);

        if ($this->data['result']->clientes_id != $this->session->userdata('cliente_id')) {
            $this->session->set_flashdata('error', 'Esta venda não pertence ao cliente logado.');
            redirect('mine/painel');
        }

        $this->load->view('conecte/imprimirVenda', $this->data);
    }

    public function minha_ordem_de_servico($y = null, $when = null)
    {
        if (($y != null) && (is_numeric($y))) {
            // Do not forget this number -> 44023
            // function sending => y = (7653 * ID) + 44023
            // function recieving => x = (y - 44023) / 7653

            // Example ID = 2 | y = 59329
            $y = intval($y);
            $id = ($y - 44023) / 7653;

            $this->data['menuOs'] = 'os';
            $this->data['custom_error'] = '';
            $this->load->model('mapos_model');
            $this->load->model('os_model');
            $this->data['result'] = $this->os_model->getById($id);

            if ($this->data['result'] == null) {
                // Resposta em caso de não encontrar a ordem de serviço
                //$this->load->view('conecte/login');
            } else {
                $data['produtos'] = $this->os_model->getProdutos($id);
                $data['servicos'] = $this->os_model->getServicos($id);
                $data['emitente'] = $this->mapos_model->getEmitente();

                $this->load->view('conecte/minha_os', array_merge($this->data, $data));
            }
        } else {
            // Resposta em caso de não encontrar a ordem de serviço
            //$this->load->view('conecte/');
        }
    }

    public function adicionarOs()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }
        $this->load->library('form_validation');

        $this->form_validation->set_rules('descricaoProduto', 'Descrição', 'required');
        $this->form_validation->set_rules('defeito', 'Defeito');
        $this->form_validation->set_rules('observacoes', 'Observações');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? true : false);
        } else {
            $id = null;
            $usuario = $this->db->query('SELECT usuarios_id, count(*) as down FROM os GROUP BY usuarios_id ORDER BY down LIMIT 1')->row();
            if ($usuario == null) {
                $this->db->where('situacao', 1);
                $this->db->limit(1);
                $usuario = $this->db->get('usuarios')->row();

                if ($usuario->idUsuarios == null) {
                    $this->session->set_flashdata('error', 'Ocorreu um erro ao cadastrar a ordem de serviço, por favor contate o administrador do sistema.');
                    redirect('mine/os');
                } else {
                    $id = $usuario->idUsuarios;
                }
            } else {
                $id = $usuario->usuarios_id;
            }

            $data = [
                'dataInicial' => date('Y-m-d'),
                'clientes_id' => $this->session->userdata('cliente_id'),
                'usuarios_id' => $id,
                'dataFinal' => date('Y-m-d'),
                'descricaoProduto' => $this->security->xss_clean($this->input->post('descricaoProduto')),
                'defeito' => $this->security->xss_clean($this->input->post('defeito')),
                'status' => 'Aberto',
                'observacoes' => $this->security->xss_clean(set_value('observacoes')),
                'faturado' => 0,
            ];

            if (is_numeric($id = $this->Conecte_model->add('os', $data, true))) {
                $this->load->model('mapos_model');
                $this->load->model('usuarios_model');

                $idOs = $id;
                $os = $this->Conecte_model->getById($id);

                $remetentes = [];
                $usuarios = $this->usuarios_model->getAll();

                foreach ($usuarios as $usuario) {
                    array_push($remetentes, $usuario->email);
                }
                array_push($remetentes, $os->email);

                $this->enviarOsPorEmail($idOs, $remetentes, 'Nova Ordem de Serviço #' . $idOs . ' - Criada pelo Cliente');
                $this->session->set_flashdata('success', 'OS adicionada com sucesso!');
                redirect('mine/detalhesOs/' . $id);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['output'] = 'conecte/adicionarOs';
        $this->load->view('conecte/template', $this->data);
    }

    public function detalhesOs($id = null)
    {
        if (is_numeric($id) && $id != null) {
            $this->load->model('mapos_model');
            $this->load->model('os_model');

            $this->data['result'] = $this->os_model->getById($id);
            $this->data['produtos'] = $this->os_model->getProdutos($id);
            $this->data['servicos'] = $this->os_model->getServicos($id);
            $this->data['anexos'] = $this->os_model->getAnexos($id);

            if ($this->data['result']->idClientes != $this->session->userdata('cliente_id')) {
                $this->session->set_flashdata('error', 'Esta OS não pertence ao cliente logado.');
                redirect('mine/painel');
            }

            $this->data['output'] = 'conecte/detalhes_os';
            $this->load->view('conecte/template', $this->data);
        } else {
            echo 'teste';
        }
    }

    public function cadastrar()
    {
        $this->load->model('clientes_model', '', true);
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $id = 0;

        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } elseif (strtolower($this->input->post('captcha')) != strtolower($this->session->userdata('captchaWord'))) {
            $this->session->set_flashdata('error', 'Os caracteres da imagem não foram preenchidos corretamente!');
        } else {
            $data = [
                'nomeCliente' => $this->input->post('nomeCliente'),
                'documento' => set_value('documento'),
                'telefone' => set_value('telefone'),
                'celular' => $this->input->post('celular'),
                'email' => set_value('email'),
                'senha' => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                'rua' => set_value('rua'),
                'complemento' => set_value('complemento'),
                'numero' => set_value('numero'),
                'bairro' => set_value('bairro'),
                'cidade' => set_value('cidade'),
                'estado' => set_value('estado'),
                'cep' => set_value('cep'),
                'dataCadastro' => date('Y-m-d'),
                'contato' => $this->input->post('contato'),
            ];

            $id = $this->clientes_model->add('clientes', $data);

            if ($id > 0) {
                $this->enviarEmailBoasVindas($id);
                $this->enviarEmailTecnicoNotificaClienteNovo($id);
                $this->session->set_flashdata('success', 'Cadastro realizado com sucesso! <br> Um e-mail de boas vindas será enviado para ' . $data['email']);
                redirect(base_url() . 'index.php/mine/login');
            } else {
                $this->session->set_flashdata('error', 'Falha ao realizar cadastro!');
            }
        }

        $this->data['view'] = 'conecte/cadastrar';
        $this->load->view('conecte/cadastrar', $this->data);
    }

    public function downloadanexo($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }
        if ($id != null && is_numeric($id)) {
            $this->db->where('idAnexos', $id);
            $file = $this->db->get('anexos', 1)->row();

            $this->load->library('zip');
            $path = $file->path;
            $this->zip->read_file($path . '/' . $file->anexo);
            $this->zip->download('file' . date('d-m-Y-H.i.s') . '.zip');
        }
    }

    public function remover_atestado()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $cliente = $this->Conecte_model->getDados();
        if ($cliente && $cliente->atestado_medico_arquivo) {
            $arquivo = './assets/uploads/atestados/' . $cliente->atestado_medico_arquivo;
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
            $this->Conecte_model->edit('clientes', ['atestado_medico_arquivo' => null, 'atestado_medico_validade' => null], 'idClientes', $cliente->idClientes);
            $this->session->set_flashdata('success', 'Atestado médico removido com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover atestado médico.');
        }

        redirect('mine/conta?tab=saude');
    }

    public function remover_restricao_cliente($id = null)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro ao tentar remover restrição.');
            redirect('mine/conta?tab=restricoes');
        }

        $restricao = $this->restricao_alimentar_model->getById($id);

        // Garante que o cliente só pode apagar a própria restrição
        if ($restricao && $restricao->cliente_id == $this->session->userdata('cliente_id')) {
            if ($this->restricao_alimentar_model->delete($id)) {
                $this->session->set_flashdata('success', 'Restrição alimentar removida com sucesso!');
            } else {
                $this->session->set_flashdata('error', 'Erro ao remover restrição alimentar.');
            }
        } else {
            $this->session->set_flashdata('error', 'Você não tem permissão para remover esta restrição.');
        }
        redirect('mine/conta?tab=restricoes');
    }

    public function minhasViagens()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $cliente_id = $this->session->userdata('cliente_id');
        $this->data['minhas_viagens'] = $this->viagens_model->getViagensByCliente($cliente_id);
        $this->data['viagens_disponiveis'] = $this->viagens_model->getViagensDisponiveis($cliente_id);
        $this->data['menuViagens'] = 'viagens';
        $this->data['output'] = 'conecte/minhasViagens'; // A view será atualizada
        $this->load->view('conecte/template', $this->data);
    }

    public function visualizarViagem($id)
    {
        $this->data['result'] = $this->viagens_model->getById($id);
        $this->data['output'] = 'conecte/visualizarViagem';
        $this->load->view('conecte/template', $this->data);
    }

    public function calendarioViagens()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            return $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $cliente_id = $this->session->userdata('cliente_id');
        $minhas_viagens = $this->viagens_model->getViagensByCliente($cliente_id);
        $viagens_disponiveis = $this->viagens_model->getViagensDisponiveis($cliente_id);

        $events = [];

        foreach ($minhas_viagens as $viagem) {
            $events[] = [
                'title' => 'Sua Viagem: ' . $viagem->nome_viagem,
                'start' => $viagem->data_partida,
                'end' => $viagem->data_retorno ?: $viagem->data_partida,
                'color' => '#28a745', // Verde para viagens do cliente
                'url' => base_url() . 'index.php/mine/visualizarViagem/' . $viagem->viagem_id,
            ];
        }

        foreach ($viagens_disponiveis as $viagem) {
            $events[] = [
                'title' => 'Viagem: ' . $viagem->nome_viagem,
                'start' => $viagem->data_partida,
                'end' => $viagem->data_retorno ?: $viagem->data_partida,
                'color' => '#17a2b8', // Azul para viagens disponíveis
                'url' => base_url() . 'index.php/mine/visualizarViagem/' . $viagem->id,
            ];
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode($events));
    }

    public function pagarViagem($viagem_cliente_id)
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->load->model('viagem_clientes_model');
        $viagemInscricao = $this->viagem_clientes_model->getByIdWithViagem($viagem_cliente_id);

        if (!$viagemInscricao || $viagemInscricao->cliente_id != $this->session->userdata('cliente_id')) {
            $this->session->set_flashdata('error', 'Inscrição em viagem não encontrada ou não pertence a você.');
            redirect(base_url() . 'index.php/mine/minhasViagens');
        }

        $this->data['viagem'] = $viagemInscricao;
        $this->data['output'] = 'conecte/pagarViagem';
        $this->load->view('conecte/template', $this->data);
    }

    public function gerarCobrancaViagem()
    {
        if (! $this->session->userdata('cliente_id') || ! $this->session->userdata('conectado')) {
            return $this->output->set_status_header(401)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }
    
        $this->load->model('viagem_clientes_model');
        $this->load->model('cobrancas_model');
    
        $viagemClienteId = $this->input->post('viagem_cliente_id');
        $paymentMethod = $this->input->post('payment_method');
        $gateway = $this->input->post('gateway');
    
        $viagemInscricao = $this->viagem_clientes_model->getByIdWithViagem($viagemClienteId);
    
        if (! $viagemInscricao || $viagemInscricao->cliente_id != $this->session->userdata('cliente_id')) {
            return $this->output->set_status_header(404)->set_output(json_encode(['error' => 'Inscrição em viagem não encontrada.']));
        }
    
        if ($viagemInscricao->status_pagamento == 'Pago') {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Esta viagem já está paga.']));
        }
    
        // Verifica se já existe uma cobrança pendente
        $cobrancaExistente = $this->cobrancas_model->get('cobrancas', '*', "viagem_clientes_id = {$viagemClienteId} AND status = 'pending' AND gateway = '{$gateway}'", 1, 0, true);
    
        if ($cobrancaExistente) {
            $chargeId = $cobrancaExistente->charge_id;
            $cobrancaId = $cobrancaExistente->id;
        } else {
            // Cria uma nova cobrança se não existir
            $dataCobranca = [
                'clientes_id' => $viagemInscricao->cliente_id,
                'viagem_clientes_id' => $viagemClienteId,
                'status' => 'pending',
                'gateway' => $gateway,
                'valor' => $viagemInscricao->preco_pessoa,
                'vencimento' => date('Y-m-d'),
                'registrado_em' => date('Y-m-d H:i:s'),
                'referencia' => "viagem_{$viagemClienteId}",
            ];
            $cobrancaId = $this->cobrancas_model->add('cobrancas', $dataCobranca, true);
        }
    
        $paymentResponse = null;
        $chargeId = null;
    
        switch ($gateway) {
            case 'efi':
                $this->load->library('gateways/gerencianetsdk');
                if (! $cobrancaExistente) {
                    $chargeId = $this->gerencianetsdk->createCharge(
                        [$cobrancaId],
                        [['name' => "Pagamento da Viagem: {$viagemInscricao->nome_viagem}", 'value' => $viagemInscricao->preco_pessoa * 100, 'amount' => 1]]
                    );
                    if (! $chargeId) {
                        return $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Falha ao criar a cobrança no gateway EFI.']));
                    }
                }
                $paymentResponse = $this->gerencianetsdk->generatePayment($chargeId, $paymentMethod);
                break;
    
            case 'mercadopago':
                $this->load->library('gateways/mercadopagosdk');
                if (! $cobrancaExistente) {
                    $chargeId = $this->mercadopagosdk->createCharge(
                        $cobrancaId,
                        "Pagamento da Viagem: {$viagemInscricao->nome_viagem}",
                        $viagemInscricao->preco_pessoa
                    );
                    if (! $chargeId) {
                        return $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Falha ao criar a cobrança no gateway Mercado Pago.']));
                    }
                }
                $paymentResponse = $this->mercadopagosdk->generatePayment($chargeId, $paymentMethod);
                break;
    
            case 'asaas':
                $this->load->library('gateways/asaassdk');
                if (! $cobrancaExistente) {
                    $chargeId = $this->asaassdk->createCharge(
                        $cobrancaId,
                        "Pagamento da Viagem: {$viagemInscricao->nome_viagem}",
                        $viagemInscricao->preco_pessoa
                    );
                    if (! $chargeId) {
                        return $this->output->set_status_header(500)->set_output(json_encode(['error' => 'Falha ao criar a cobrança no gateway Asaas.']));
                    }
                }
                $paymentResponse = $this->asaassdk->generatePayment($chargeId, $paymentMethod);
                break;
    
            default:
                return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Gateway de pagamento inválido.']));
        }
    
        return $this->output->set_content_type('application/json')->set_output(json_encode($paymentResponse));
    }

    public function meusCursos()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->data['results'] = $this->cursos_model->getCursosByCliente($this->session->userdata('cliente_id'));
        $this->data['menuCursos'] = 'cursos';
        $this->data['output'] = 'conecte/meusCursos';
        $this->load->view('conecte/template', $this->data);
    }

    public function treinos()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->load->model('treinos_model');
        $this->data['menuTreinos'] = 'treinos';
        $this->data['treinos_config'] = $this->treinos_model->get('treinos_config', '*', ['status' => 1]);
        $this->data['meus_treinos'] = $this->treinos_model->get('treinos_agendados', '*', ['cliente_id' => $this->session->userdata('cliente_id')]);
        $this->data['output'] = 'conecte/treinos';
        $this->load->view('conecte/template', $this->data);
    }

    public function agendarTreino()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            redirect(base_url() . 'index.php/mine/login');
        }

        $this->load->library('form_validation');
        $this->load->model('treinos_model');

        $this->form_validation->set_rules('treino_config_id', 'Tipo de Treino', 'required');
        $this->form_validation->set_rules('data_hora_treino', 'Data e Hora', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Por favor, preencha todos os campos obrigatórios.');
            redirect('mine/treinos');
            return;
        }

        $configId = $this->input->post('treino_config_id');
        $dataHoraStr = $this->input->post('data_hora_treino');
        $comInstrutor = $this->input->post('com_instrutor') ? 1 : 0;
        $instrutorId = $comInstrutor ? $this->input->post('instrutor_id') : null;
        $clienteId = $this->session->userdata('cliente_id');

        $config = $this->treinos_model->getById($configId);
        if (!$config) {
            $this->session->set_flashdata('error', 'Configuração de treino inválida.');
            redirect('mine/treinos');
            return;
        }

        try {
            $inicioTreino = DateTime::createFromFormat('d/m/Y H:i', $dataHoraStr);
            $fimTreino = clone $inicioTreino;
            $fimTreino->add(new DateInterval('PT' . $config->duracao_minutos . 'M'));
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Formato de data e hora inválido.');
            redirect('mine/treinos');
            return;
        }

        $data = [
            'config_id' => $configId,
            'cliente_id' => $clienteId,
            'com_instrutor' => $comInstrutor,
            'instrutor_id' => $instrutorId,
            'data_hora_inicio' => $inicioTreino->format('Y-m-d H:i:s'),
            'data_hora_fim' => $fimTreino->format('Y-m-d H:i:s'),
            'valor_cobrado' => $comInstrutor ? $config->preco_com_instrutor : $config->preco_sem_instrutor,
            'status' => 'Agendado'
        ];

        $this->treinos_model->add('treinos_agendados', $data);
        $this->session->set_flashdata('success', 'Treino agendado com sucesso!');
        redirect('mine/treinos');
    }

    public function getHorariosDisponiveis()
    {
        // Lógica para retornar um JSON com os horários disponíveis para um determinado dia e tipo de treino
        $this->load->model('treinos_model');
        $config_id = $this->input->get('config_id');

        if (!$config_id) {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'ID do treino não fornecido.']));
        }

        $config = $this->treinos_model->getById($config_id);
        if (!$config) {
            return $this->output->set_status_header(404)->set_output(json_encode(['error' => 'Configuração do treino não encontrada.']));
        }

        // Busca agendamentos futuros para este tipo de treino para evitar sobreposição
        $agendamentosFuturos = $this->db
            ->where('config_id', $config_id)
            ->where('data_hora_inicio >=', date('Y-m-d H:i:s'))
            ->get('treinos_agendados')
            ->result();

        $agendamentosFormatados = [];
        foreach ($agendamentosFuturos as $ag) {
            // Formata para 'd/m/Y H:i' para bater com o formato do frontend
            $agendamentosFormatados[] = date('d/m/Y H:i', strtotime($ag->data_hora_inicio));
        }


        $horariosOcupados = [];
        foreach ($agendamentosFuturos as $ag) {
            $horariosOcupados[] = date('Y/m/d H:i', strtotime($ag->data_hora_inicio));
        }

        $diasPermitidos = explode(',', $config->dias_semana_disponiveis); // Ex: [1,2,3,4,5]
        $allowedDates = [];
        $allowedTimes = [];

        $inicio = new DateTime($config->horario_inicio);
        $fim = new DateTime($config->horario_fim);
        $intervalo = new DateInterval('PT' . $config->duracao_minutos . 'M');
        $periodo = new DatePeriod($inicio, $intervalo, $fim);

        foreach ($periodo as $dt) {
            $allowedTimes[] = $dt->format('H:i');
        }

        $response = [
            'allowedDates' => $diasPermitidos, // A biblioteca usa os dias da semana (0=Dom, 1=Seg, ...)
            'allowedTimes' => $allowedTimes,
            'agendamentos' => $agendamentosFormatados, // Envia os horários já ocupados
            'disabledDates' => [],
            'disabledWeekDays' => array_values(array_diff([0, 1, 2, 3, 4, 5, 6], $diasPermitidos)),
            'duration' => (int)$config->duracao_minutos,
        ];

        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function getInstrutoresDisponiveis()
    {
        if (!$this->session->userdata('cliente_id') || !$this->session->userdata('conectado')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $this->load->model('treinos_model');
        $config_id = $this->input->get('config_id');
        $data_hora_str = $this->input->get('data_hora');

        if (!$config_id || !$data_hora_str) {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Parâmetros insuficientes.']));
        }

        $config = $this->treinos_model->getById($config_id);
        if (!$config) {
            return $this->output->set_status_header(404)->set_output(json_encode(['error' => 'Configuração de treino não encontrada.']));
        }

        try {
            $inicio_treino = DateTime::createFromFormat('d/m/Y H:i', $data_hora_str);
            $fim_treino = clone $inicio_treino;
            $fim_treino->add(new DateInterval('PT' . $config->duracao_minutos . 'M'));
        } catch (Exception $e) {
            return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Formato de data e hora inválido.']));
        }

        // Busca os IDs dos instrutores configurados para este treino
        $instrutores_configurados_ids = !empty($config->instrutores_ids) ? explode(',', $config->instrutores_ids) : [];

        if (empty($instrutores_configurados_ids)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([])); // Retorna vazio se nenhum instrutor configurado
        }

        $this->db->select('u.idUsuarios as id, u.nome');
        $this->db->from('usuarios u');
        $this->db->where('u.situacao', 1);
        $this->db->where_in('u.idUsuarios', $instrutores_configurados_ids); // Filtra apenas pelos instrutores configurados
        $instrutores = $this->db->get()->result();

        // Aqui você adicionaria a lógica para verificar a agenda dos instrutores
        // e remover os que estiverem ocupados no horário de $inicio_treino a $fim_treino.
        // Por enquanto, retornaremos todos.

        return $this->output->set_content_type('application/json')->set_output(json_encode($instrutores));
    }

    public function senhaSalvarComAjax()
    {
    }

    private function check_credentials($email)
    {
        $this->db->where('email', $email);
        $this->db->limit(1);

        return $this->db->get('clientes')->row();
    }

    private function check_token($token)
    {
        $this->db->where('token', $token);
        $this->db->limit(1);

        return $this->db->get('resets_de_senha')->row();
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $dateStart = new \DateTime($date);
        $dateNow = new \DateTime(date($format));

        $dateDiff = $dateStart->diff($dateNow);

        if ($dateDiff->days >= 1) {
            return true;
        } else {
            return false;
        }
    }

    private function enviarRecuperarSenha($idClientes, $clienteEmail, $assunto, $token)
    {
        $dados = [];
        $this->load->model('mapos_model');
        $this->load->model('clientes_model', '', true);

        $dados['emitente'] = $this->mapos_model->getEmitente();
        $dados['cliente'] = $this->clientes_model->getById($idClientes);
        $dados['resets_de_senha'] = json_decode($token);

        $emitente = $dados['emitente'];
        $remetente = $clienteEmail;

        $html = $this->load->view('conecte/emails/clientenovasenha', $dados, true);

        $this->load->model('email_model');

        if ($emitente == null) {
            $this->session->set_flashdata(['error' => 'Cadastrar Emitente.\n\n Por favor contate o administrador do sistema.']);

            return redirect(base_url() . 'index.php/mine/resetarSenha');
        }

        $headers = [
            'From' => "\"$emitente->nome\" <$emitente->email>",
            'Subject' => $assunto,
            'Return-Path' => '',
        ];
        $email = [
            'to' => $remetente,
            'message' => $html,
            'status' => 'pending',
            'date' => date('Y-m-d H:i:s'),
            'headers' => serialize($headers),
        ];

        return $this->email_model->add('email_queue', $email);
    }

    private function enviarOsPorEmail($idOs, $remetentes, $assunto)
    {
        $dados = [];

        $this->load->model('mapos_model');
        $this->load->model('os_model');
        $dados['result'] = $this->os_model->getById($idOs);
        if (! isset($dados['result']->email)) {
            return false;
        }

        $dados['produtos'] = $this->os_model->getProdutos($idOs);
        $dados['servicos'] = $this->os_model->getServicos($idOs);
        $dados['emitente'] = $this->mapos_model->getEmitente();

        $emitente = $dados['emitente'];
        if (! isset($emitente)) {
            return false;
        }

        $html = $this->load->view('os/emails/os', $dados, true);

        $this->load->model('email_model');

        $remetentes = array_unique($remetentes);
        foreach ($remetentes as $remetente) {
            $headers = [
                'From' => $emitente->email,
                'Subject' => $assunto,
                'Return-Path' => '',
            ];
            $email = [
                'to' => $remetente,
                'message' => $html,
                'status' => 'pending',
                'date' => date('Y-m-d H:i:s'),
                'headers' => serialize($headers),
            ];
            $this->email_model->add('email_queue', $email);
        }

        return true;
    }

    private function enviarEmailBoasVindas($id)
    {
        $dados = [];
        $this->load->model('mapos_model');
        $this->load->model('clientes_model', '', true);

        $dados['emitente'] = $this->mapos_model->getEmitente();
        $dados['cliente'] = $this->clientes_model->getById($id);

        $emitente = $dados['emitente'];
        $remetente = $dados['cliente'];
        $assunto = 'Bem-vindo!';

        $html = $this->load->view('os/emails/clientenovo', $dados, true);

        $this->load->model('email_model');

        $headers = [
            'From' => "\"$emitente->nome\" <$emitente->email>",
            'Subject' => $assunto,
            'Return-Path' => '',
        ];
        $email = [
            'to' => $remetente->email,
            'message' => $html,
            'status' => 'pending',
            'date' => date('Y-m-d H:i:s'),
            'headers' => serialize($headers),
        ];

        return $this->email_model->add('email_queue', $email);
    }

    private function enviarEmailTecnicoNotificaClienteNovo($id)
    {
        $dados = [];
        $this->load->model('mapos_model');
        $this->load->model('clientes_model', '', true);
        $this->load->model('usuarios_model');

        $dados['emitente'] = $this->mapos_model->getEmitente();
        $dados['cliente'] = $this->clientes_model->getById($id);

        $emitente = $dados['emitente'];
        $assunto = 'Novo Cliente Cadastrado no Sistema';

        $usuarios = [];
        $usuarios = $this->usuarios_model->getAll();

        foreach ($usuarios as $usuario) {
            $dados['usuario'] = $usuario;
            $html = $this->load->view('os/emails/clientenovonotifica', $dados, true);
            $headers = [
                'From' => "\"$emitente->nome\" <$emitente->email>",
                'Subject' => $assunto,
                'Return-Path' => '',
            ];
            $email = [
                'to' => $usuario->email,
                'message' => $html,
                'status' => 'pending',
                'date' => date('Y-m-d H:i:s'),
                'headers' => serialize($headers),
            ];
            $this->email_model->add('email_queue', $email);
        }
    }

    public function captcha()
    {
        header('Content-type: image/jpeg');

        $arrFont = ['font-ZXX_Noise.otf', 'font-karabine.ttf', 'font-capture.ttf', 'font-captcha.ttf'];
        shuffle($arrFont);

        $codigoCaptcha = substr(md5(time()), 0, 7);
        $img = imagecreatefromjpeg('./assets/img/captcha_bg.jpg');
        $corCaptcha = imagecolorallocate($img, 255, 0, 0);
        $font = './assets/font-awesome/' . $arrFont[0];

        imagettftext($img, 23, 0, 5, rand(30, 35), $corCaptcha, $font, $codigoCaptcha);
        imagepng($img);
        imagedestroy($img);

        $this->session->set_userdata('captchaWord', $codigoCaptcha);
    }
    
}

/* End of file conecte.php */
/* Location: ./application/controllers/conecte.php */
