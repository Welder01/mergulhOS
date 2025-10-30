<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clientes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('curso_alunos_model');
        $this->load->model('viagem_clientes_model');
        $this->load->model('restricao_alimentar_model');
        $this->load->model('certificacao_mergulhador_model');
        $this->load->model('clientes_model');
        $this->load->model('mapos_model');
        $this->data['menuClientes'] = 'clientes';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar clientes.');
            redirect(base_url());
        }

        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('clientes/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->clientes_model->count('clientes');
        if($pesquisa) {
            $this->data['configuration']['suffix'] = "?pesquisa={$pesquisa}";
            $this->data['configuration']['first_url'] = base_url("index.php/clientes")."\?pesquisa={$pesquisa}";
        }

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->clientes_model->get('clientes', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));

        $this->data['view'] = 'clientes/clientes';

        return $this->layout();
    }

    public function adicionar()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar clientes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $senhaCliente = $this->input->post('senha') ? $this->input->post('senha') : preg_replace('/[^\p{L}\p{N}\s]/', '', set_value('documento'));

        $cpf_cnpj = preg_replace('/[^\p{L}\p{N}\s]/', '', set_value('documento'));

        if (strlen($cpf_cnpj) == 11) {
            $pessoa_fisica = true;
        } else {
            $pessoa_fisica = false;
        }

        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $email = set_value('email');
            if ($email && $this->clientes_model->emailExists($email)) {
                $this->data['custom_error'] = '<div class="form_error"><p>Este e-mail já está sendo utilizado por outro cliente.</p></div>';
            } else {
                $data = [
                'nomeCliente' => set_value('nomeCliente'),
                'contato' => set_value('contato'),
                'pessoa_fisica' => $pessoa_fisica,
                'altura' => str_replace(',', '.', set_value('altura')),
                'peso' => str_replace(',', '.', set_value('peso')),
                'documento' => set_value('documento'),
                'telefone' => set_value('telefone'),
                'celular' => set_value('celular'),
                'email' => set_value('email'),
                'senha' => password_hash($senhaCliente, PASSWORD_DEFAULT),
                'rua' => set_value('rua'),
                'numero' => set_value('numero'),
                'complemento' => set_value('complemento'),
                'bairro' => set_value('bairro'),
                'cidade' => set_value('cidade'),
                'estado' => set_value('estado'),
                'cep' => set_value('cep'),
                'dataCadastro' => date('Y-m-d'),
                'fornecedor' => $this->input->post('fornecedor') ? 1 : 0,
            ];

            if ($this->clientes_model->add('clientes', $data) == true) {
                $this->session->set_flashdata('success', 'Cliente adicionado com sucesso!');
                log_info('Adicionou um cliente.');
                redirect(site_url('clientes/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
            }
        }

        $this->data['view'] = 'clientes/adicionarCliente';

        return $this->layout();
    }

    public function editar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3)) || ! $this->clientes_model->getById($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Cliente não encontrado ou parâmetro inválido.');
            redirect('clientes/gerenciar');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            
            $email = $this->input->post('email');
            $idCliente = $this->input->post('idClientes');
            if ($email && $this->clientes_model->emailExists($email, $idCliente)) {
                $this->data['custom_error'] = '<div class="form_error"><p>Este e-mail já está sendo utilizado por outro cliente.</p></div>';
            } else {
                $this->load->library('upload');

                $atestado_emissao = $this->input->post('atestado_medico_emissao');
                $atestado_validade = null;
                if ($atestado_emissao) {
                    $date = new DateTime($atestado_emissao);
                    $date->add(new DateInterval('P1Y')); // Adiciona 1 ano
                    $atestado_validade = $date->format('Y-m-d');
                }

                $data = [
                    'nomeCliente' => $this->input->post('nomeCliente'),
                    'contato' => $this->input->post('contato'),
                    'altura' => str_replace(',', '.', $this->input->post('altura')),
                    'peso' => str_replace(',', '.', $this->input->post('peso')),
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
                    'tamanho_colete' => $this->input->post('tamanho_colete') ?: null,
                    'peso_lastro' => $this->input->post('peso_lastro') ?: null,
                    'tamanho_neoprene' => $this->input->post('tamanho_neoprene') ?: null,
                    'tamanho_nadadeira' => $this->input->post('tamanho_nadadeira') ?: null,
                    'fornecedor' => $this->input->post('fornecedor') ? 1 : 0,
                    'contato_emergencia_nome' => $this->input->post('contato_emergencia_nome'),
                    'contato_emergencia_telefone' => $this->input->post('contato_emergencia_telefone'),
                    'contato_emergencia_parentesco' => $this->input->post('contato_emergencia_parentesco'),
                    'qtd_reguladores' => $this->input->post('qtd_reguladores') ?: 0,
                    'qtd_lanterna' => $this->input->post('qtd_lanterna') ?: 0,
                    'qtd_computador' => $this->input->post('qtd_computador') ?: 0,
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

                $this->upload->initialize($config);

                if ($this->upload->do_upload('atestado_medico_arquivo')) {
                    // Remove o arquivo antigo, se existir
                    $clienteAtual = $this->clientes_model->getById($this->input->post('idClientes'));
                    if ($clienteAtual && $clienteAtual->atestado_medico_arquivo) {
                        $old_file = './assets/uploads/atestados/' . $clienteAtual->atestado_medico_arquivo;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }

                    // Adiciona o novo arquivo aos dados a serem salvos
                    $upload_data = $this->upload->data();
                    $data['atestado_medico_arquivo'] = $upload_data['file_name'];
                } else {
                    $this->data['custom_error'] = '<div class="form_error"><p>Erro no upload do atestado: ' . $this->upload->display_errors() . '</p></div>';
                }
            }

            // Adicionar nova restrição, se houver
            if ($this->input->post('restricao')) {
                $dataRestricao = [
                    'cliente_id' => $this->input->post('idClientes'),
                    'restricao' => $this->input->post('restricao'),
                    'observacoes' => $this->input->post('observacoes_restricao')
                ];
                $this->restricao_alimentar_model->add($dataRestricao);
            }

            // Adicionar nova certificação, se houver
            if ($this->input->post('nome_certificacao')) {
                $dataCertificacao = [
                    'cliente_id' => $this->input->post('idClientes'),
                    'nome_certificacao' => $this->input->post('nome_certificacao'),
                    'orgao_emissor' => $this->input->post('orgao_emissor'),
                    'numero_certificacao' => $this->input->post('numero_certificacao'),
                    'data_emissao' => $this->input->post('data_emissao') ?: null,
                    'data_validade' => $this->input->post('data_validade') ?: null,
                    'observacoes' => $this->input->post('observacoes_cert')
                ];

                if (!empty($_FILES['arquivo']['name'])) {
                    $configCert['upload_path'] = './uploads/certificados/';
                    $configCert['allowed_types'] = 'pdf|jpg|jpeg|png';
                    $configCert['max_size'] = 5120;
                    $configCert['encrypt_name'] = true;
                    
                    if (!is_dir($configCert['upload_path'])) {
                        mkdir($configCert['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($configCert);
                    if ($this->upload->do_upload('arquivo')) {
                        $dataCertificacao['arquivo'] = $this->upload->data('file_name');
                    }
                }
                $this->certificacao_mergulhador_model->add($dataCertificacao);
            }

            if ($this->clientes_model->edit('clientes', $data, 'idClientes', $this->input->post('idClientes')) == true) {
                $this->session->set_flashdata('success', 'Cliente editado com sucesso!');
                $activeTab = ltrim($this->input->post('active_tab'), '#');
                log_info('Alterou um cliente. ID' . $this->input->post('idClientes'));
                redirect(site_url('clientes/editar/') . $this->input->post('idClientes') . '?tab=' . $activeTab);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
            }
        }

        $this->data['result'] = $this->clientes_model->getById($this->uri->segment(3));
        $this->data['restricoes'] = $this->restricao_alimentar_model->getByCliente($this->uri->segment(3));
        $this->data['certificacoes'] = $this->certificacao_mergulhador_model->getByCliente($this->uri->segment(3));
        $this->data['active_tab'] = $this->input->get('tab');
        
        $tipos_certificacao_str = $this->mapos_model->get_ci_config('certificacao_tipos');
        $this->data['tipos_certificacao'] = !empty($tipos_certificacao_str) ? explode(',', $tipos_certificacao_str) : [];

        $tipos_certificadora_str = $this->mapos_model->get_ci_config('certificadora_tipos');
        $this->data['tipos_certificadora'] = !empty($tipos_certificadora_str) ? explode(',', $tipos_certificadora_str) : [];

        $this->data['view'] = 'clientes/editarCliente';

        return $this->layout();
    }

    public function visualizar()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar clientes.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->data['result'] = $this->clientes_model->getById($this->uri->segment(3));
        $this->data['results'] = $this->clientes_model->getOsByCliente($this->uri->segment(3));
        $this->data['result_vendas'] = $this->clientes_model->getAllVendasByClient($this->uri->segment(3));
        $this->data['restricoes'] = $this->restricao_alimentar_model->getByCliente($this->uri->segment(3));
        $this->data['cursos'] = $this->curso_alunos_model->getByCliente($this->uri->segment(3));
        $pesquisaViagem = $this->input->get('pesquisa_viagem');
        $this->data['viagens'] = $this->viagem_clientes_model->getByCliente($this->uri->segment(3), $pesquisaViagem);
        $this->data['certificacoes'] = $this->certificacao_mergulhador_model->getByCliente($this->uri->segment(3));
        $this->data['view'] = 'clientes/visualizar';

        return $this->layout();
    }

    public function remover_restricao($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
            redirect(base_url());
        }

        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! A restrição não existe.');
            redirect(base_url());
        }

        $restricao = $this->restricao_alimentar_model->getById($id);
        if ($restricao && $this->restricao_alimentar_model->delete($id)) {
            $this->session->set_flashdata('success', 'Restrição alimentar removida com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover restrição alimentar.');
        }

        redirect('clientes/editar/' . $restricao->cliente_id . '#restricoes');
    }

    public function remover_atestado($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
            redirect(base_url());
        }

        $cliente = $this->clientes_model->getById($id);
        if ($cliente && $cliente->atestado_medico_arquivo) {
            $arquivo = './assets/uploads/atestados/' . $cliente->atestado_medico_arquivo;
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
            $this->clientes_model->edit('clientes', ['atestado_medico_arquivo' => null], 'idClientes', $id);
            $this->session->set_flashdata('success', 'Atestado médico removido com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover atestado médico.');
        }

        redirect('clientes/editar/' . $id . '#saude');
    }

    public function remover_certificacao($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
            redirect(base_url());
        }

        $certificacao = $this->certificacao_mergulhador_model->getById($id);
        if ($certificacao && $this->certificacao_mergulhador_model->delete($id)) {
            $this->session->set_flashdata('success', 'Certificação removida com sucesso!');
            if ($certificacao->arquivo && file_exists('./uploads/certificados/' . $certificacao->arquivo)) {
                unlink('./uploads/certificados/' . $certificacao->arquivo);
            }
        } else {
            $this->session->set_flashdata('error', 'Erro ao remover certificação.');
        }

        redirect('clientes/editar/' . $certificacao->cliente_id . '#certificacoes');
    }

    public function excluir()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir clientes.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir cliente.');
            redirect(site_url('clientes/gerenciar/'));
        }

        $os = $this->clientes_model->getAllOsByClient($id);
        if ($os != null) {
            $this->clientes_model->removeClientOs($os);
        }

        // excluindo Vendas vinculadas ao cliente
        $vendas = $this->clientes_model->getAllVendasByClient($id);
        if ($vendas != null) {
            $this->clientes_model->removeClientVendas($vendas);
        }

        $this->clientes_model->delete('clientes', 'idClientes', $id);
        log_info('Removeu um cliente. ID' . $id);

        $this->session->set_flashdata('success', 'Cliente excluido com sucesso!');
        redirect(site_url('clientes/gerenciar/'));
    }
}
