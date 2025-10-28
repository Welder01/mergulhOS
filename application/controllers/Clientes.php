<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clientes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('clientes_model');
        $this->load->model('restricao_alimentar_model');
        $this->load->model('certificacao_mergulhador_model');
        $this->data['menuClientes'] = 'clientes';
        $this->data['menuCursos'] = 'cursos'; // Adicione esta linha
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
                $senha = $this->input->post('senha');
            if ($senha != null) {
                $senha = password_hash($senha, PASSWORD_DEFAULT);

                $data = [
                    'nomeCliente' => $this->input->post('nomeCliente'),
                    'contato' => $this->input->post('contato'),
                    'documento' => $this->input->post('documento'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'email' => $this->input->post('email'),
                    'senha' => $senha,
                    'rua' => $this->input->post('rua'),
                    'numero' => $this->input->post('numero'),
                    'complemento' => $this->input->post('complemento'),
                    'bairro' => $this->input->post('bairro'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'cep' => $this->input->post('cep'),
                    'fornecedor' => (set_value('fornecedor') == true ? 1 : 0),
                    'tamanho_colete' => $this->input->post('tamanho_colete'),
                    'peso_lastro' => $this->input->post('peso_lastro'),
                    'tamanho_neoprene' => $this->input->post('tamanho_neoprene'),
                    'tamanho_nadadeira' => $this->input->post('tamanho_nadadeira'),
                ];
            } else {
                $data = [
                    'nomeCliente' => $this->input->post('nomeCliente'),
                    'contato' => $this->input->post('contato'),
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
                    'fornecedor' => (set_value('fornecedor') == true ? 1 : 0),
                    'tamanho_colete' => $this->input->post('tamanho_colete'),
                    'peso_lastro' => $this->input->post('peso_lastro'),
                    'tamanho_neoprene' => $this->input->post('tamanho_neoprene'),
                    'tamanho_nadadeira' => $this->input->post('tamanho_nadadeira'),
                ];
            }

            if ($this->clientes_model->edit('clientes', $data, 'idClientes', $this->input->post('idClientes')) == true) {
                $this->session->set_flashdata('success', 'Cliente editado com sucesso!');
                log_info('Alterou um cliente. ID' . $this->input->post('idClientes'));
                redirect(site_url('clientes/editar/') . $this->input->post('idClientes'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
            }
        }

        $this->data['result'] = $this->clientes_model->getById($this->uri->segment(3));
        $this->data['view'] = 'clientes/editarCliente';

        return $this->layout();
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

    // Adicione esses métodos dentro da classe Clientes

public function adicionar_restricao() {
    if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
        $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
        redirect(base_url());
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('restricao', 'Restrição', 'trim|required');

    if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
    } else {
        $data = [
            'cliente_id' => $this->input->post('cliente_id'),
            'restricao' => $this->input->post('restricao'),
            'observacoes' => $this->input->post('observacoes')
        ];

        if ($this->restricao_alimentar_model->add($data)) {
            $this->session->set_flashdata('success', 'Restrição alimentar adicionada com sucesso!');
            log_info('Adicionou restrição alimentar ao cliente ID: ' . $this->input->post('cliente_id'));
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar restrição alimentar.');
        }
    }
    redirect('clientes/visualizar/' . $this->input->post('cliente_id') . '#restricoes');
}

public function remover_restricao($id = null) {
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

    redirect('clientes/visualizar/' . $restricao->cliente_id . '#restricoes');
}

public function adicionar_certificacao() {
    if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
        $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
        redirect(base_url());
    }

    $this->load->library('form_validation');
    $this->form_validation->set_rules('nome_certificacao', 'Nome da Certificação', 'trim|required');

    if ($this->form_validation->run() == false) {
        $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
    } else {
        $data = [
            'cliente_id' => $this->input->post('cliente_id'),
            'nome_certificacao' => $this->input->post('nome_certificacao'),
            'orgao_emissor' => $this->input->post('orgao_emissor'),
            'data_emissao' => $this->input->post('data_emissao') ?: null,
            'data_validade' => $this->input->post('data_validade') ?: null,
            'observacoes' => $this->input->post('observacoes')
        ];

        // Upload do arquivo
        if (!empty($_FILES['arquivo']['name'])) {
            $config['upload_path'] = './uploads/certificados/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size'] = 5120; // 5MB
            $config['encrypt_name'] = true;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('arquivo')) {
                $upload_data = $this->upload->data();
                $data['arquivo'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Erro no upload: ' . $this->upload->display_errors());
                redirect('clientes/visualizar/' . $this->input->post('cliente_id') . '#certificacoes');
                return;
            }
        }

        if ($this->certificacao_mergulhador_model->add($data)) {
            $this->session->set_flashdata('success', 'Certificação adicionada com sucesso!');
            log_info('Adicionou certificação ao cliente ID: ' . $this->input->post('cliente_id'));
        } else {
            $this->session->set_flashdata('error', 'Erro ao adicionar certificação.');
        }
    }
    redirect('clientes/visualizar/' . $this->input->post('cliente_id') . '#certificacoes');
}

public function remover_certificacao($id = null) {
    if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
        $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
        redirect(base_url());
    }

    if ($id == null || !is_numeric($id)) {
        $this->session->set_flashdata('error', 'Erro! A certificação não existe.');
        redirect(base_url());
    }

    $certificacao = $this->certificacao_mergulhador_model->getById($id);
    
    if ($certificacao && $this->certificacao_mergulhador_model->delete($id)) {
        $this->session->set_flashdata('success', 'Certificação removida com sucesso!');
        log_info('Removeu certificação ID ' . $id . ' do cliente ID: ' . $certificacao->cliente_id);
        // Remove o arquivo se existir
        if ($certificacao->arquivo && file_exists('./uploads/certificados/' . $certificacao->arquivo)) {
            unlink('./uploads/certificados/' . $certificacao->arquivo);
        }
    } else {
        $this->session->set_flashdata('error', 'Erro ao remover certificação.');
    }

    redirect('clientes/visualizar/' . $certificacao->cliente_id . '#certificacoes');
}

    // Atualize o método visualizar para carregar os dados
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

        $cliente_id = $this->uri->segment(3);
        
        $this->data['custom_error'] = '';
        $this->data['result'] = $this->clientes_model->getById($cliente_id);
        $this->data['results'] = $this->clientes_model->getOsByCliente($cliente_id);
        $this->data['result_vendas'] = $this->clientes_model->getAllVendasByClient($cliente_id);
        $this->data['restricoes'] = $this->restricao_alimentar_model->getByCliente($cliente_id);
        $this->data['certificacoes'] = $this->certificacao_mergulhador_model->getByCliente($cliente_id);
        $this->data['view'] = 'clientes/visualizar';

        return $this->layout();
    }
}
