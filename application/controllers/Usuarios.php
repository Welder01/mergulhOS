<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Usuarios extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('upload');
        $this->load->helper('form');
        $this->load->model('usuarios_model');
        $this->load->model('mapos_model');
        $this->load->model('restricao_alimentar_usuario_model');
        $this->load->model('certificacao_usuario_model');
        $this->data['menuUsuarios'] = 'Usuários';
        $this->data['menuConfiguracoes'] = 'Configurações';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = base_url() . 'index.php/usuarios/gerenciar/';
        $this->data['configuration']['total_rows'] = $this->usuarios_model->count('usuarios');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->usuarios_model->get($this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'usuarios/usuarios';

        return $this->layout();
    }

    public function adicionar()
    {
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('usuarios') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => set_value('nome'),
                'rg' => set_value('rg'),
                'cpf' => set_value('cpf'),
                'cep' => set_value('cep'),
                'rua' => set_value('rua'),
                'numero' => set_value('numero'),
                'bairro' => set_value('bairro'),
                'cidade' => set_value('cidade'),
                'estado' => set_value('estado'),
                'email' => set_value('email'),
                'senha' => password_hash($this->input->post('senha'), PASSWORD_DEFAULT),
                'telefone' => set_value('telefone'),
                'celular' => set_value('celular'),
                'dataExpiracao' => set_value('dataExpiracao'),
                'situacao' => set_value('situacao'),
                'permissoes_id' => $this->input->post('permissoes_id'),
                'dataCadastro' => date('Y-m-d'),
            ];

            if ($this->usuarios_model->add('usuarios', $data) == true) {
                $this->session->set_flashdata('success', 'Usuário cadastrado com sucesso!');
                log_info('Adicionou um usuário.');
                redirect(site_url('usuarios/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->load->model('permissoes_model');
        $this->data['permissoes'] = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao,permissoes.nome');
        $this->data['view'] = 'usuarios/adicionarUsuario';

        return $this->layout();
    }

    public function editar()
    {
          if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->usuarios_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Usuário não encontrado ou parâmetro inválido.');
            redirect('usuarios/gerenciar');
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');
        $this->form_validation->set_rules('permissoes_id', 'Permissão', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            if ($this->input->post('idUsuarios') == 1 && $this->input->post('situacao') == 0) {
                $this->session->set_flashdata('error', 'O usuário super admin não pode ser desativado!');
                redirect(base_url() . 'index.php/usuarios/editar/' . $this->input->post('idUsuarios'));
            }

            $senha = $this->input->post('senha');
            if ($senha != null) {
                $atestado_emissao = $this->input->post('atestado_medico_emissao');
                $atestado_validade = null;
                if ($atestado_emissao) {
                    $date = new DateTime($atestado_emissao);
                    $date->add(new DateInterval('P1Y')); // Adiciona 1 ano
                    $atestado_validade = $date->format('Y-m-d');
                }

                $senha = password_hash($senha, PASSWORD_DEFAULT);

                $data = [
                    'nome' => $this->input->post('nome'),
                    'rg' => $this->input->post('rg'),
                    'sexo' => $this->input->post('sexo'),
                    'altura' => str_replace(',', '.', $this->input->post('altura')),
                    'cpf' => $this->input->post('cpf'),
                    'cep' => $this->input->post('cep'),
                    'rua' => $this->input->post('rua'),
                    'numero' => $this->input->post('numero'),
                    'bairro' => $this->input->post('bairro'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'email' => $this->input->post('email'),
                    'senha' => $senha,
                    'telefone' => $this->input->post('telefone'),
                    'peso' => str_replace(',', '.', $this->input->post('peso')),
                    'celular' => $this->input->post('celular'),
                    'dataExpiracao' => set_value('dataExpiracao'),
                    'situacao' => $this->input->post('situacao'),
                    'permissoes_id' => $this->input->post('permissoes_id'),
                    'tamanho_colete' => $this->input->post('tamanho_colete'),
                    'peso_lastro' => $this->input->post('peso_lastro'),
                    'tamanho_neoprene' => $this->input->post('tamanho_neoprene'),
                    'qtd_reguladores' => $this->input->post('qtd_reguladores') ?: 0,
                    'qtd_lanterna' => $this->input->post('qtd_lanterna') ?: 0,
                    'qtd_computador' => $this->input->post('qtd_computador') ?: 0,
                    'tamanho_nadadeira' => $this->input->post('tamanho_nadadeira'),
                    'atestado_medico_emissao' => $atestado_emissao ?: null,
                    'atestado_medico_validade' => $atestado_validade,
                    'nome_medico' => $this->input->post('nome_medico'),
                    'crm_medico' => $this->input->post('crm_medico'),
                    'codigo_validacao_atestado' => $this->input->post('codigo_validacao_atestado'),
                    'contato_emergencia_nome' => $this->input->post('contato_emergencia_nome'),
                    'contato_emergencia_parentesco' => $this->input->post('contato_emergencia_parentesco'),
                    'contato_emergencia_telefone' => $this->input->post('contato_emergencia_telefone'),
                ];
            } else {
                $atestado_emissao = $this->input->post('atestado_medico_emissao');
                $atestado_validade = null;
                if ($atestado_emissao) {
                    $date = new DateTime($atestado_emissao);
                    $date->add(new DateInterval('P1Y')); // Adiciona 1 ano
                    $atestado_validade = $date->format('Y-m-d');
                }

                $data = [
                    'nome' => $this->input->post('nome'),
                    'rg' => $this->input->post('rg'),
                    'sexo' => $this->input->post('sexo'),
                    'altura' => str_replace(',', '.', $this->input->post('altura')),
                    'cpf' => $this->input->post('cpf'),
                    'cep' => $this->input->post('cep'),
                    'rua' => $this->input->post('rua'),
                    'numero' => $this->input->post('numero'),
                    'bairro' => $this->input->post('bairro'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'peso' => str_replace(',', '.', $this->input->post('peso')),
                    'celular' => $this->input->post('celular'),
                    'dataExpiracao' => set_value('dataExpiracao'),
                    'situacao' => $this->input->post('situacao'),
                    'permissoes_id' => $this->input->post('permissoes_id'),
                    'tamanho_colete' => $this->input->post('tamanho_colete'),
                    'peso_lastro' => $this->input->post('peso_lastro'),
                    'tamanho_neoprene' => $this->input->post('tamanho_neoprene'),
                    'qtd_reguladores' => $this->input->post('qtd_reguladores') ?: 0,
                    'qtd_lanterna' => $this->input->post('qtd_lanterna') ?: 0,
                    'qtd_computador' => $this->input->post('qtd_computador') ?: 0,
                    'tamanho_nadadeira' => $this->input->post('tamanho_nadadeira'),
                    'atestado_medico_emissao' => $atestado_emissao ?: null,
                    'atestado_medico_validade' => $atestado_validade,
                    'nome_medico' => $this->input->post('nome_medico'),
                    'crm_medico' => $this->input->post('crm_medico'),
                    'codigo_validacao_atestado' => $this->input->post('codigo_validacao_atestado'),
                    'contato_emergencia_nome' => $this->input->post('contato_emergencia_nome'),
                    'contato_emergencia_parentesco' => $this->input->post('contato_emergencia_parentesco'),
                    'contato_emergencia_telefone' => $this->input->post('contato_emergencia_telefone'),
                ];
            }

            // Upload do atestado médico
            if (!empty($_FILES['atestado_medico_arquivo']['name'])) {
                $config['upload_path'] = './assets/uploads/atestados_usuarios/';
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

                    if ($this->input->post('atestado_medico_arquivo_atual')) {
                        $old_file = './assets/uploads/atestados_usuarios/' . $this->input->post('atestado_medico_arquivo_atual');
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                } else {
                    $this->data['custom_error'] = '<div class="form_error"><p>Erro no upload do atestado: ' . $this->upload->display_errors() . '</p></div>';
                }
            }

            // Adicionar nova certificação, se houver
            if ($this->input->post('nome_certificacao')) {
                $dataCertificacao = [
                    'numero_certificacao' => $this->input->post('numero_certificacao'),
                    'usuario_id' => $this->input->post('idUsuarios'),
                    'nome_certificacao' => $this->input->post('nome_certificacao'),
                    'orgao_emissor' => $this->input->post('orgao_emissor'),
                    'data_emissao' => $this->input->post('data_emissao') ?: null,
                ];

                if (!empty($_FILES['arquivo_certificacao']['name'])) {
                    $configCert['upload_path'] = './uploads/certificados_usuarios/';
                    $configCert['allowed_types'] = 'pdf|jpg|jpeg|png';
                    $configCert['max_size'] = 5120;
                    $configCert['encrypt_name'] = true;

                    if (!is_dir($configCert['upload_path'])) {
                        mkdir($configCert['upload_path'], 0777, true);
                    }
                    $this->upload->initialize($configCert);
                    if ($this->upload->do_upload('arquivo_certificacao')) {
                        $dataCertificacao['arquivo'] = $this->upload->data('file_name');
                    } else {
                        $this->data['custom_error'] = '<div class="alert alert-danger">Erro no upload do arquivo de certificação: ' . $this->upload->display_errors() . '</div>';
                        $this->editar(); // Recarrega a view com o erro
                        return; // Interrompe a execução
                    }
                }
                $this->certificacao_usuario_model->add($dataCertificacao);
            }

            // Adicionar nova restrição, se houver
            if ($this->input->post('restricao')) {
                $dataRestricao = [
                    'usuario_id' => $this->input->post('idUsuarios'),
                    'restricao' => $this->input->post('restricao'),
                    'observacoes' => $this->input->post('observacoes_restricao')
                ];
                $this->restricao_alimentar_usuario_model->add($dataRestricao);
            }

            if ($this->usuarios_model->edit('usuarios', $data, 'idUsuarios', $this->input->post('idUsuarios')) == true) {
                $this->session->set_flashdata('success', 'Usuário editado com sucesso!');
                $activeTab = ltrim($this->input->post('active_tab'), '#');
                log_info('Alterou um usuário. ID: ' . $this->input->post('idUsuarios'));
                redirect(site_url('usuarios/editar/') . $this->input->post('idUsuarios') . '?tab=' . $activeTab);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->usuarios_model->getById($this->uri->segment(3));
        $this->load->model('permissoes_model');
        $this->data['permissoes'] = $this->permissoes_model->getActive('permissoes', 'permissoes.idPermissao,permissoes.nome');
        $this->data['restricoes'] = $this->restricao_alimentar_usuario_model->getByUsuario($this->uri->segment(3));
        $this->data['certificacoes'] = $this->certificacao_usuario_model->getByUsuario($this->uri->segment(3));
        $this->data['active_tab'] = $this->input->get('tab');

        $tipos_certificacao_str = $this->mapos_model->get_ci_config('certificacao_tipos');
        $this->data['tipos_certificacao'] = !empty($tipos_certificacao_str) ? explode(',', $tipos_certificacao_str) : [];

        $tipos_certificadora_str = $this->mapos_model->get_ci_config('certificadora_tipos');
        $this->data['tipos_certificadora'] = !empty($tipos_certificadora_str) ? explode(',', $tipos_certificadora_str) : [];

        $this->data['view'] = 'usuarios/editarUsuario';

        return $this->layout();
    }

    public function remover_atestado_usuario($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eUsuario')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar usuários.');
            redirect(base_url());
        }

        $usuario = $this->usuarios_model->getById($id);
        if ($usuario && $usuario->atestado_medico_arquivo) {
            $arquivo = './assets/uploads/atestados_usuarios/' . $usuario->atestado_medico_arquivo;
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
            $this->usuarios_model->edit('usuarios', ['atestado_medico_arquivo' => null, 'atestado_medico_validade' => null], 'idUsuarios', $id);
            $this->session->set_flashdata('success', 'Atestado médico removido com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover atestado médico.');
        }

        redirect('usuarios/editar/' . $id . '#saude');
    }

    public function remover_certificacao_usuario($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cUsuario')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar usuários.');
            redirect(base_url());
        }

        $certificacao = $this->certificacao_usuario_model->getById($id);
        if ($certificacao && $this->certificacao_usuario_model->delete($id)) {
            $this->session->set_flashdata('success', 'Certificação removida com sucesso!');
            if ($certificacao->arquivo && file_exists('./uploads/certificados_usuarios/' . $certificacao->arquivo)) {
                unlink('./uploads/certificados_usuarios/' . $certificacao->arquivo);
            }
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover certificação.');
        }

        redirect('usuarios/editar/' . $certificacao->usuario_id . '?tab=certificacoes');
    }

    public function remover_restricao_usuario($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'cUsuario')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar usuários.');
            redirect(base_url());
        }

        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! A restrição não existe.');
            redirect(base_url());
        }

        $restricao = $this->restricao_alimentar_usuario_model->getById($id);
        if ($restricao && $this->restricao_alimentar_usuario_model->delete($id)) {
            $this->session->set_flashdata('success', 'Restrição alimentar removida com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover restrição alimentar.');
        }
        redirect('usuarios/editar/' . $restricao->usuario_id . '?tab=restricoes');
    }

    public function excluir()
    {
        $id = $this->uri->segment(3);
        $this->usuarios_model->delete('usuarios', 'idUsuarios', $id);

        log_info('Removeu um usuário. ID: ' . $id);

        redirect(site_url('usuarios/gerenciar/'));
    }
}
