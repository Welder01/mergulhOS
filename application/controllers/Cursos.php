<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cursos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cursos_model');
        $this->load->model('curso_instrutores_model');
        $this->load->model('curso_alunos_model');
        $this->load->model('curso_modulos_model');
        $this->load->model('curso_modulo_instrutores_model');
        $this->load->model('curso_requisitos_model');
        $this->load->model('certificacao_mergulhador_model');
        $this->data['menuCursos'] = 'cursos'; // Para ativar o menu "Cursos"
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cursos.');
            redirect(base_url());
        }

        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('cursos/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->cursos_model->count('cursos');
        if ($pesquisa) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}";
            $this->data['configuration']['first_url'] = base_url('index.php/cursos') . "?pesquisa={$pesquisa}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->cursos_model->get('cursos', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'cursos/cursos';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome_curso', 'Nome do Curso', 'trim|required');
        $this->form_validation->set_rules('data_inicio', 'Data de Início', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        $this->form_validation->set_rules('vagas', 'Vagas', 'trim|required|is_natural');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data_inicio = $this->input->post('data_inicio');
            $data_fim = $this->input->post('data_fim');

            $data_inicio_formatted = null;
            $data_fim_formatted = null;

            $preco = $this->input->post('preco');
            $preco = str_replace('.', '', $preco); // Remove separador de milhares
            $preco = str_replace(',', '.', $preco); // Troca vírgula por ponto decimal

            if ($data_inicio) {
                $date_obj = DateTime::createFromFormat('d/m/Y', $data_inicio);
                if ($date_obj !== false) {
                    $data_inicio_formatted = $date_obj->format('Y-m-d');
                } else {
                    log_message('error', 'Failed to parse data_inicio in Cursos/adicionar: ' . $data_inicio);
                }
            }
            if ($data_fim) {
                $date_obj = DateTime::createFromFormat('d/m/Y', $data_fim);
                if ($date_obj !== false) {
                    $data_fim_formatted = $date_obj->format('Y-m-d');
                } else {
                    log_message('error', 'Failed to parse data_fim in Cursos/adicionar: ' . $data_fim);
                }
            }

            $data = [
                'nome_curso' => set_value('nome_curso'),
                'vagas' => set_value('vagas'),
                'vagas_total' => set_value('vagas'),
                'descricao' => set_value('descricao'),
                'data_inicio' => $data_inicio_formatted,
                'data_fim' => $data_fim_formatted,
                'status' => set_value('status'),
                'preco' => $preco,

                'data_cadastro' => date('Y-m-d H:i:s'),
            ];

            if ($id = $this->cursos_model->add('cursos', $data)) {
                $this->session->set_flashdata('success', 'Curso adicionado com sucesso!');
                log_info('Adicionou um curso. ID: ' . $id);
                redirect(site_url('cursos/editar/' . $id));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'cursos/adicionarCurso';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3)) || !$this->cursos_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Curso não encontrado ou parâmetro inválido.');
            redirect('cursos/gerenciar');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome_curso', 'Nome do Curso', 'trim|required');
        $this->form_validation->set_rules('data_inicio', 'Data de Início', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        $this->form_validation->set_rules('vagas', 'Vagas', 'trim|required|is_natural');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data_inicio = $this->input->post('data_inicio');
            $data_fim = $this->input->post('data_fim');

            $data_inicio_formatted = null;
            $data_fim_formatted = null;

            $preco = $this->input->post('preco');
            $preco = str_replace('.', '', $preco); // Remove separador de milhares
            $preco = str_replace(',', '.', $preco); // Troca vírgula por ponto decimal

            if ($data_inicio) {
                $date_obj = DateTime::createFromFormat('d/m/Y', $data_inicio);
                if ($date_obj !== false) {
                    $data_inicio_formatted = $date_obj->format('Y-m-d');
                } else {
                    log_message('error', 'Failed to parse data_inicio in Cursos/editar: ' . $data_inicio);
                }
            }
            if ($data_fim) {
                $date_obj = DateTime::createFromFormat('d/m/Y', $data_fim);
                if ($date_obj !== false) {
                    $data_fim_formatted = $date_obj->format('Y-m-d');
                } else {
                    log_message('error', 'Failed to parse data_fim in Cursos/editar: ' . $data_fim);
                }
            }

            $data = [
                'nome_curso' => $this->input->post('nome_curso'),
                'vagas' => $this->input->post('vagas'),
                'vagas_total' => $this->input->post('vagas'),
                'descricao' => $this->input->post('descricao'),
                'data_inicio' => $data_inicio_formatted,
                'data_fim' => $data_fim_formatted,
                'status' => $this->input->post('status'),
                'preco' => $preco
            ];

            if ($this->cursos_model->edit('cursos', $data, 'id', $this->input->post('id_curso'))) {
                $this->session->set_flashdata('success', 'Curso editado com sucesso!');
                log_info('Alterou um curso. ID: ' . $this->input->post('id_curso'));
                redirect(site_url('cursos/editar/' . $this->input->post('id_curso')));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->cursos_model->getById($this->uri->segment(3));
        $this->data['view'] = 'cursos/editarCurso';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('cursos/gerenciar');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->uri->segment(3);
        $this->data['custom_error'] = '';
        $this->data['result'] = $this->cursos_model->getById($curso_id);
        $this->data['instrutores'] = $this->curso_instrutores_model->getByCurso($curso_id);
        $this->data['alunos'] = $this->curso_alunos_model->getByCurso($curso_id);
        $modulos = $this->curso_modulos_model->getModulosByCurso($curso_id);
        foreach ($modulos as $modulo) {
            $modulo->instrutores = $this->curso_modulo_instrutores_model->getInstrutoresByModulo($modulo->id);
        }
        $this->data['modulos'] = $modulos;
        $this->data['requisitos'] = $this->curso_requisitos_model->getByCurso($curso_id);

        // Carregar tipos de certificação da configuração
        $this->load->model('mapos_model');
        $tipos_certificacao_str = $this->mapos_model->get_ci_config('certificacao_tipos');
        $this->data['tipos_certificacao'] = !empty($tipos_certificacao_str) ? explode(',', $tipos_certificacao_str) : [];

        $this->data['view'] = 'cursos/visualizarCurso';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir cursos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir curso.');
            redirect(site_url('cursos/gerenciar/'));
        }

        // Excluir instrutores e alunos vinculados (ON DELETE CASCADE nas migrations já cuida disso)
        if ($this->cursos_model->delete('cursos', 'id', $id) == true) {
            log_info('Removeu um curso. ID: ' . $id);
            $this->session->set_flashdata('success', 'Curso excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar excluir o curso.');
        }
        redirect(site_url('cursos/gerenciar/'));
    }

    public function adicionar_instrutor()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('usuario_id', 'Instrutor', 'trim|required');
        $this->form_validation->set_rules('curso_id', 'Curso', 'trim|required');

        if ($this->form_validation->run() == false) {
            $val_errors = validation_errors();
            $safe_val_errors = str_replace(["\r", "\n"], ' ', $val_errors);
            $safe_val_errors = addslashes($safe_val_errors);
            $this->session->set_flashdata('error', 'Erro de validação: ' . $safe_val_errors);
        } else {
            $curso_id = $this->input->post('curso_id');
            $usuario_id = $this->input->post('usuario_id');
            $id_curso_instrutor = $this->input->post('id_curso_instrutor');

            // Duplicate Check
            $isDuplicate = $this->curso_instrutores_model->isInstrutorInCurso($curso_id, $usuario_id);

            // If Editing...
            if ($id_curso_instrutor) {
                // Get current record to check if user changed
                $current = $this->curso_instrutores_model->getById($id_curso_instrutor);
                // If user changed AND new user exists in course -> Error
                if ($current && $current->usuario_id != $usuario_id && $isDuplicate) {
                    $this->session->set_flashdata('error', 'Este instrutor já está atribuído a este curso.');
                    redirect('cursos/visualizar/' . $curso_id . '?tab=tab2');
                }

                $data = [
                    'usuario_id' => $usuario_id,
                    'usuario_cadastrou_id' => $this->session->userdata('id_admin'),
                    'valor_pagamento' => $this->input->post('valor_pagamento') ? str_replace(',', '.', $this->input->post('valor_pagamento')) : 0.00,
                    'tipo_pagamento' => $this->input->post('tipo_pagamento'),
                    'hora_inicio' => $this->input->post('hora_inicio'),
                    'hora_fim' => $this->input->post('hora_fim'),
                    'modulo_id' => $this->input->post('modulo_id'),
                    'data_aula' => $this->input->post('data_aula'),
                ];

                if ($this->curso_instrutores_model->edit('curso_instrutores', $data, 'id', $id_curso_instrutor)) {
                    $this->session->set_flashdata('success', 'Instrutor atualizado com sucesso!');
                    log_info('Alterou atribuição de instrutor. ID: ' . $id_curso_instrutor);
                } else {
                    $this->session->set_flashdata('error', 'Erro ao atualizar instrutor.');
                }

            } else { // Adding...
                if ($isDuplicate) {
                    $this->session->set_flashdata('error', 'Este instrutor já está atribuído a este curso.');
                } else {
                    $data = [
                        'curso_id' => $curso_id,
                        'usuario_id' => $usuario_id,
                        'usuario_cadastrou_id' => $this->session->userdata('id_admin'),
                        'data_atribuicao' => date('Y-m-d H:i:s'),
                        'valor_pagamento' => $this->input->post('valor_pagamento') ? str_replace(',', '.', $this->input->post('valor_pagamento')) : 0.00,
                        'tipo_pagamento' => $this->input->post('tipo_pagamento'),
                        'hora_inicio' => $this->input->post('hora_inicio'),
                        'hora_fim' => $this->input->post('hora_fim'),
                        'modulo_id' => $this->input->post('modulo_id'),
                        'data_aula' => $this->input->post('data_aula'),
                    ];

                    if ($this->curso_instrutores_model->add($data)) {
                        $this->session->set_flashdata('success', 'Instrutor adicionado com sucesso!');
                        log_info('Adicionou instrutor ao curso. ID Curso: ' . $curso_id);

                        // --- Evolution API Trigger (curso_instrutor_adicionado) ---
                        $this->load->model('evolution_model');
                        $trigger = $this->evolution_model->getEventTrigger('curso_instrutor_adicionado');
                        if ($trigger && $trigger->status == 1) {
                            $curso = $this->cursos_model->getById($curso_id);
                            $usuario = $this->db->where('idUsuarios', $usuario_id)->get('usuarios')->row();
                            if ($usuario) {
                                $mensagem = $this->evolution_model->getById($trigger->mensagem_id);
                                $mediaUrl = $mensagem->imagem_url ?? null;
                                $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, [
                                    'usuario' => $usuario,
                                    'curso' => $curso
                                ]);
                                $this->load->library('evolution_queue');
                                $celular = preg_replace('/[^0-9]/', '', $usuario->celular);
                                $telefone = preg_replace('/[^0-9]/', '', $usuario->telefone);
                                $phone = !empty($celular) ? $celular : $telefone;
                                if (strlen($phone) >= 10 && strlen($phone) <= 11) $phone = '55' . $phone;
                                if ($phone && strlen($phone) >= 10)
                                    $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                            }
                        }
                        // --------------------------------------------------------
                    } else {
                        $db_err = $this->db->error();
                        // Replace newlines with spaces and escape single quotes to prevent JS errors
                        $safe_err_msg = str_replace(["\r", "\n"], ' ', $db_err['message']);
                        $safe_err_msg = addslashes($safe_err_msg);
                        $this->session->set_flashdata('error', 'Erro ao adicionar instrutor: ' . $safe_err_msg);
                    }
                }
            }
        }
        redirect('cursos/visualizar/' . $this->input->post('curso_id') . '?tab=tab2');
    }

    public function remover_instrutor($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! O instrutor não existe.');
            redirect(base_url());
        }

        $instrutor = $this->curso_instrutores_model->getById($id);
        if ($instrutor) {
            // --- Evolution API Trigger (curso_instrutor_removido) ---
            $this->load->model('evolution_model');
            $trigger = $this->evolution_model->getEventTrigger('curso_instrutor_removido');
            if ($trigger && $trigger->status == 1) {
                $curso = $this->cursos_model->getById($instrutor->curso_id);
                $usuario = $this->db->where('idUsuarios', $instrutor->usuario_id)->get('usuarios')->row();
                if ($curso && $usuario) {
                    $mensagem = $this->evolution_model->getById($trigger->mensagem_id);
                    $mediaUrl = $mensagem->imagem_url ?? null;
                    $msg_parsed = $this->evolution_model->parseMessage($trigger->mensagem, [
                        'usuario' => $usuario,
                        'curso' => $curso
                    ]);
                    $this->load->library('evolution_queue');
                    $celular = preg_replace('/[^0-9]/', '', $usuario->celular);
                    $telefone = preg_replace('/[^0-9]/', '', $usuario->telefone);
                    $phone = !empty($celular) ? $celular : $telefone;
                    if (strlen($phone) >= 10 && strlen($phone) <= 11) $phone = '55' . $phone;
                    if ($phone && strlen($phone) >= 10) {
                        $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                    }
                }
            }
            // ---------------------------------------------------------

            if ($this->curso_instrutores_model->delete($id)) {
                $this->session->set_flashdata('success', 'Instrutor removido com sucesso!');
                log_info('Removeu instrutor do curso. ID da atribuição: ' . $id);
            } else {
                $db_error = $this->db->error();
                $msg = 'Erro ao remover instrutor.';
                if (!empty($db_error['message'])) {
                    $msg .= ' ' . $db_error['message'];
                }
                $this->session->set_flashdata('error', $msg);
            }
        } else {
            $this->session->set_flashdata('error', 'Registro não encontrado.');
        }
        redirect('cursos/visualizar/' . $instrutor->curso_id . '?tab=tab2');
    }

    public function adicionar_aluno($curso_id_param = null, $cliente_id_param = null, $is_internal_call = false)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('cliente_id', 'Aluno', 'trim|required');
        $this->form_validation->set_rules('curso_id', 'Curso', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
            log_info('Falha na validação ao adicionar aluno: ' . validation_errors());
            redirect('cursos/visualizar/' . $this->input->post('curso_id') . '#alunos');
            return;
        }

        $curso_id = $this->input->post('curso_id');
        $cliente_id = $this->input->post('cliente_id');

        $resultado = $this->cursos_model->adicionar_aluno($curso_id, $cliente_id);

        if ($resultado['success']) {
            $this->session->set_flashdata('success', $resultado['message']);

            // --- Evolution API Trigger (curso_cliente_adicionado) ---
            $this->load->model('evolution_model');

            // 1. Client Notification
            $triggerClient = $this->evolution_model->getEventTrigger('curso_cliente_adicionado_cliente');
            if ($triggerClient && $triggerClient->status == 1) {
                $curso = $this->cursos_model->getById($curso_id);
                $this->load->model('clientes_model');
                $cliente = $this->clientes_model->getById($cliente_id);
                if ($cliente) {
                    $mensagem = $this->evolution_model->getById($triggerClient->mensagem_id);
                    $mediaUrl = $mensagem->imagem_url ?? null;
                    $msg_parsed = $this->evolution_model->parseMessage($triggerClient->mensagem, [
                        'cliente' => $cliente,
                        'curso' => $curso
                    ]);
                    $this->load->library('evolution_queue');
                    $celular = preg_replace('/[^0-9]/', '', $cliente->celular);
                    $telefone = preg_replace('/[^0-9]/', '', $cliente->telefone);
                    $phone = !empty($celular) ? $celular : $telefone;
                    if (strlen($phone) >= 10 && strlen($phone) <= 11) $phone = '55' . $phone;
                    if ($phone && strlen($phone) >= 10)
                        $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                }
            }

            // 2. Instructors Notification
            $triggerUser = $this->evolution_model->getEventTrigger('curso_cliente_adicionado_usuario');
            if ($triggerUser && $triggerUser->status == 1) {
                $this->load->model('clientes_model');
                $this->load->model('usuarios_model');
                $curso = $this->cursos_model->getById($curso_id); // Ensure we have course
                $cliente = $this->clientes_model->getById($cliente_id); // Ensure we have client

                $this->load->model('curso_instrutores_model');
                $instrutores = $this->curso_instrutores_model->getByCurso($curso_id);
                if ($instrutores) {
                    $this->load->library('evolution_queue');
                    foreach ($instrutores as $inst) {
                        $u = $this->usuarios_model->getById($inst->usuario_id);
                        if ($u) {
                            $mensagem = $this->evolution_model->getById($triggerUser->mensagem_id);
                            $mediaUrl = $mensagem->imagem_url ?? null;
                            $msg_parsed = $this->evolution_model->parseMessage($triggerUser->mensagem, [
                                'cliente' => $cliente,
                                'curso' => $curso,
                                'usuario' => $u
                            ]);
                            $celular = preg_replace('/[^0-9]/', '', $u->celular);
                            $telefone = preg_replace('/[^0-9]/', '', $u->telefone);
                            $phone = !empty($celular) ? $celular : $telefone;
                            if (strlen($phone) >= 10 && strlen($phone) <= 11) $phone = '55' . $phone;
                            if ($phone && strlen($phone) >= 10)
                                $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                        }
                    }
                }
            }
            // --------------------------------------------------------
        } else {
            $this->session->set_flashdata('error', $resultado['message']);
        }

        redirect('cursos/visualizar/' . $curso_id . '?tab=tab3');
    }

    private function verificarRequisitosAluno($curso_id, $cliente_id)
    {
        $requisitos_curso = $this->curso_requisitos_model->getByCurso($curso_id);
        if (empty($requisitos_curso)) {
            return true; // Curso não tem requisitos
        }

        $certificacoes_aluno = $this->certificacao_mergulhador_model->getByCliente($cliente_id);
        $certificacoes_aluno_nomes = array_map(function ($cert) {
            return $cert->nome_certificacao;
        }, $certificacoes_aluno);

        foreach ($requisitos_curso as $requisito) {
            if (!in_array($requisito->requisito, $certificacoes_aluno_nomes)) {
                $this->session->set_flashdata('error', 'O aluno não possui o requisito necessário: <strong>' . html_escape($requisito->requisito) . '</strong>.');
                return false;
            }
        }

        return true;
    }

    public function remover_aluno($id = null, $is_internal_call = false)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! O aluno não existe.');
            redirect(base_url());
        }

        $aluno = $this->curso_alunos_model->getById($id); // Precisa para o redirect

        // Fetch Data for Trigger
        if ($aluno) {
            $this->load->model('clientes_model');
            $cliente = $this->clientes_model->getById($aluno->cliente_id);
            $curso = $this->cursos_model->getById($aluno->curso_id);
        }

        $resultado = $this->cursos_model->remover_aluno($id);

        if ($resultado['success'] && isset($cliente) && isset($curso)) {
            // --- Evolution API Trigger (curso_cliente_removido) ---
            $this->load->model('evolution_model');

            // 1. Client Notification
            $triggerClient = $this->evolution_model->getEventTrigger('curso_cliente_removido_cliente');
            if ($triggerClient && $triggerClient->status == 1) {
                $mensagem = $this->evolution_model->getById($triggerClient->mensagem_id);
                $mediaUrl = $mensagem->imagem_url ?? null;
                $msg_parsed = $this->evolution_model->parseMessage($triggerClient->mensagem, [
                    'cliente' => $cliente,
                    'curso' => $curso
                ]);
                $this->load->library('evolution_queue');
                $celular = preg_replace('/[^0-9]/', '', $cliente->celular);
                $telefone = preg_replace('/[^0-9]/', '', $cliente->telefone);
                $phone = !empty($celular) ? $celular : $telefone;
                if (strlen($phone) >= 10 && strlen($phone) <= 11) $phone = '55' . $phone;
                if ($phone && strlen($phone) >= 10) {
                    $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                }
            }

            // 2. Instructors Notification
            $triggerUser = $this->evolution_model->getEventTrigger('curso_cliente_removido_usuario');
            if ($triggerUser && $triggerUser->status == 1) {
                $this->load->model('usuarios_model');
                $this->load->model('curso_instrutores_model');
                $instrutores = $this->curso_instrutores_model->getByCurso($curso->id);
                if ($instrutores) {
                    $this->load->library('evolution_queue');
                    foreach ($instrutores as $inst) {
                        $u = $this->usuarios_model->getById($inst->usuario_id);
                        if ($u) {
                            $mensagem = $this->evolution_model->getById($triggerUser->mensagem_id);
                            $mediaUrl = $mensagem->imagem_url ?? null;
                            $msg_parsed = $this->evolution_model->parseMessage($triggerUser->mensagem, [
                                'cliente' => $cliente,
                                'curso' => $curso,
                                'usuario' => $u
                            ]);
                            $celular = preg_replace('/[^0-9]/', '', $u->celular);
                            $telefone = preg_replace('/[^0-9]/', '', $u->telefone);
                            $phone = !empty($celular) ? $celular : $telefone;
                            if (strlen($phone) >= 10 && strlen($phone) <= 11) $phone = '55' . $phone;
                            if ($phone && strlen($phone) >= 10)
                                $this->evolution_queue->add($phone, $msg_parsed, ['media_url' => $mediaUrl]);
                        }
                    }
                }
            }
            // ------------------------------------------------------
        }

        $this->session->set_flashdata(
            $resultado['success'] ? 'success' : 'error',
            $resultado['message']
        );

        redirect('cursos/visualizar/' . $aluno->curso_id . '?tab=tab3');
    }

    // Métodos para Módulos do Curso
    public function adicionar_modulo()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->input->post('curso_id');
        $data = [
            'curso_id' => $curso_id,
            'nome' => $this->input->post('nome_modulo'),
            'descricao' => $this->input->post('descricao_modulo'),
        ];

        if ($this->curso_modulos_model->add('curso_modulos', $data)) {
            $this->session->set_flashdata('success', 'Módulo adicionado com sucesso!');
            log_info('Adicionou um módulo ao curso. ID Curso: ' . $curso_id);
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar módulo.');
        }
        redirect('cursos/visualizar/' . $curso_id . '?tab=modulos');
    }

    public function remover_modulo($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir módulos.');
            redirect(base_url());
        }

        $modulo = $this->curso_modulos_model->getById($id);
        if ($modulo && $this->curso_modulos_model->delete('curso_modulos', 'id', $id)) {
            $this->session->set_flashdata('success', 'Módulo removido com sucesso!');
            log_info('Removeu um módulo do curso. ID Módulo: ' . $id);
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover módulo.');
        }
        redirect('cursos/visualizar/' . $modulo->curso_id . '?tab=modulos');
    }

    public function toggle_conclusao_modulo()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $modulo_id = $this->input->post('id');
        $concluido = $this->input->post('concluido') === 'true' ? 1 : 0;

        $data = [
            'concluido' => $concluido,
            'data_conclusao' => $concluido ? date('Y-m-d H:i:s') : null,
        ];

        if ($this->curso_modulos_model->edit('curso_modulos', $data, 'id', $modulo_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode(['success' => true]));
        }
        return $this->output->set_status_header(500)->set_output(json_encode(['success' => false]));
    }

    public function adicionar_instrutor_modulo()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->input->post('curso_id');
        $data = [
            'curso_modulo_id' => $this->input->post('curso_modulo_id'),
            'usuario_id' => $this->input->post('usuario_id'),
        ];

        if ($this->curso_modulo_instrutores_model->add('curso_modulo_instrutores', $data)) {
            $this->session->set_flashdata('success', 'Instrutor adicionado ao módulo com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar instrutor ao módulo.');
        }
        redirect('cursos/visualizar/' . $curso_id . '?tab=modulos');
    }

    public function remover_instrutor_modulo($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $relacao = $this->curso_modulo_instrutores_model->getById($id);
        $modulo = $this->curso_modulos_model->getById($relacao->curso_modulo_id);

        $this->curso_modulo_instrutores_model->delete('curso_modulo_instrutores', 'id', $id);
        redirect('cursos/visualizar/' . $modulo->curso_id . '?tab=modulos');
    }

    // Métodos para Requisitos do Curso
    public function adicionar_requisito()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar cursos.');
            redirect(base_url());
        }

        $curso_id = $this->input->post('curso_id');
        $data = [
            'curso_id' => $curso_id,
            'requisito' => $this->input->post('requisito'),
        ];

        if ($this->curso_requisitos_model->add($data)) {
            $this->session->set_flashdata('success', 'Requisito adicionado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar requisito.');
        }
        redirect('cursos/visualizar/' . $curso_id . '?tab=requisitos');
    }

    public function remover_requisito($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir requisitos.');
            redirect(base_url());
        }

        $requisito = $this->curso_requisitos_model->getById($id);
        if ($requisito && $this->curso_requisitos_model->delete('curso_requisitos', 'id', $id)) {
            $this->session->set_flashdata('success', 'Requisito removido com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover requisito.');
        }
        redirect('cursos/visualizar/' . $requisito->curso_id . '?tab=requisitos');
    }

    public function ajax_check_requisitos()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $curso_id = $this->input->post('curso_id');
        $cliente_id = $this->input->post('cliente_id');

        $this->load->model('certificacao_mergulhador_model');

        $requisitos_curso = $this->curso_requisitos_model->getByCurso($curso_id);

        $missing_requisitos = [];
        if (!empty($requisitos_curso)) {
            $certificacoes_aluno = $this->certificacao_mergulhador_model->getByCliente($cliente_id);
            $certificacoes_aluno_nomes = array_map(function ($cert) {
                return $cert->nome_certificacao;
            }, $certificacoes_aluno);

            foreach ($requisitos_curso as $requisito) {
                if (!in_array($requisito->requisito, $certificacoes_aluno_nomes)) {
                    $missing_requisitos[] = html_escape($requisito->requisito);
                }
            }
        }

        $status = empty($missing_requisitos) ? 'success' : 'failure';

        if ($status === 'failure') {
            $logMessage = "Falha na verificação de requisitos para o cliente ID: {$cliente_id} no curso ID: {$curso_id}. Requisitos faltantes: " . implode(', ', $missing_requisitos);
            log_info($logMessage);
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => $status,
            'missing' => $missing_requisitos,
            'csrf_token' => $this->security->get_csrf_hash()
        ]));
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->db->select('idUsuarios, nome, telefone, cpf');
            $this->db->limit(5);
            $this->db->group_start();
            $this->db->like('nome', $q);
            $this->db->or_like('telefone', $q);
            $this->db->or_like('cpf', $q);
            $this->db->group_end();
            $this->db->where('situacao', 1);
            $query = $this->db->get('usuarios');
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $label = $row['nome'] . ' | Tel: ' . $row['telefone'] . ' | CPF: ' . $row['cpf'];
                    $row_set[] = ['label' => $label, 'id' => $row['idUsuarios']];
                }
                echo json_encode($row_set);
            }
        }
    }

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->db->select('idClientes, nomeCliente, documento, telefone');
            $this->db->limit(5);
            $this->db->group_start();
            $this->db->like('nomeCliente', $q);
            $this->db->or_like('documento', $q);
            $this->db->group_end();
            $query = $this->db->get('clientes');
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    $label = 'ID: ' . $row['idClientes'] . ' | ' . $row['nomeCliente'] . ' | CPF: ' . $row['documento'] . ' | Tel: ' . $row['telefone'];
                    $row_set[] = ['label' => $label, 'id' => $row['idClientes']];
                }
                echo json_encode($row_set);
            }
        }
    }

    public function autoCompleteCurso()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select("id, preco, CONCAT('ID: ', id, ' | ', nome_curso, ' | Início: ', DATE_FORMAT(data_inicio, '%d/%m/%Y')) as label, CONCAT('ID: ', id, ' | ', nome_curso, ' | Início: ', DATE_FORMAT(data_inicio, '%d/%m/%Y')) as text", false);
            $this->db->like('LOWER(nome_curso)', $q);
        } elseif (isset($_GET['ids'])) {
            $ids = explode(',', $_GET['ids']);
            $this->db->select("id, preco, CONCAT('ID: ', id, ' | ', nome_curso, ' | Início: ', DATE_FORMAT(data_inicio, '%d/%m/%Y')) as label, CONCAT('ID: ', id, ' | ', nome_curso, ' | Início: ', DATE_FORMAT(data_inicio, '%d/%m/%Y')) as text", false);
            $this->db->where_in('id', $ids);
        } else {
            return $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }

        $this->db->limit(10);
        $query = $this->db->get('cursos');
        $result = $query->result();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}