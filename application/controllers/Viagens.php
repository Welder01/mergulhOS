<?php
class Viagens extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('viagens_model');
        $this->load->model('viagem_clientes_model');
        $this->load->model('viagem_instrutores_model');
        $this->load->model('viagem_custos_model');
        $this->load->model('viagem_cursos_model');
        $this->load->model('cursos_model');
        $this->data['menuViagens'] = 'viagens';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar viagens.');
            redirect(base_url());
        }
        $this->load->library('pagination');
        $this->data['configuration']['base_url'] = site_url('viagens/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->viagens_model->count('viagens');
        $this->pagination->initialize($this->data['configuration']);
        $this->data['results'] = $this->viagens_model->get('viagens', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
        $this->data['view'] = 'viagens/viagens';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar viagens.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome_viagem', 'Nome da Viagem', 'required');
        $this->form_validation->set_rules('vagas', 'Vagas', 'required|integer');

        if ($this->form_validation->run() == false) {
            $this->data['view'] = 'viagens/adicionarViagem';
            return $this->layout();
        }

        $precoPessoa = $this->input->post('preco_pessoa');
        $precoPessoa = str_replace('.', '', $precoPessoa); // Remove separador de milhares
        $precoPessoa = str_replace(',', '.', $precoPessoa); // Troca vírgula por ponto decimal
        $dataPartida = $this->input->post('data_partida');
        $dataRetorno = $this->input->post('data_retorno');

        try {
            $dataPartida = $dataPartida ? DateTime::createFromFormat('d/m/Y', $dataPartida)->format('Y-m-d') : null;
            $dataRetorno = $dataRetorno ? DateTime::createFromFormat('d/m/Y', $dataRetorno)->format('Y-m-d') : null;
        } catch (Exception $e) {
            $dataPartida = null;
            $dataRetorno = null;
        }

        $data = [
            'nome_viagem' => $this->input->post('nome_viagem'),
            'descricao' => $this->input->post('descricao'),
            'data_partida' => $dataPartida,
            'data_retorno' => $dataRetorno,
            'vagas' => (int)$this->input->post('vagas'),
            'preco_pessoa' => (float)$precoPessoa,
            'status' => $this->input->post('status'),
        ];

        $viagem_id = $this->viagens_model->add('viagens', $data);
        if ($viagem_id) {
            $cursos = $this->input->post('cursos');
            if ($cursos) {
                foreach ($cursos as $curso_id) {
                    $this->viagem_cursos_model->add(['viagem_id' => $viagem_id, 'curso_id' => $curso_id]);
                }
            }
            $this->session->set_flashdata('success', 'Viagem adicionada com sucesso!');
            redirect(site_url('viagens'));
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao adicionar a viagem.');
            redirect(site_url('viagens/adicionar'));
        }
    }

    public function editar($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar viagens.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nome_viagem', 'Nome da Viagem', 'required');
        $this->form_validation->set_rules('vagas', 'Vagas', 'required|integer');

        if ($this->form_validation->run() == false) {
            $this->data['result'] = $this->viagens_model->getById($id);
            $this->data['cursos_viagem'] = $this->viagem_cursos_model->getByViagem($id);
            $this->data['view'] = 'viagens/editarViagem';
            return $this->layout();
        }

        $precoPessoa = $this->input->post('preco_pessoa');
        $precoPessoa = str_replace('.', '', $precoPessoa); // Remove separador de milhares
        $precoPessoa = str_replace(',', '.', $precoPessoa); // Troca vírgula por ponto decimal
        $dataPartida = $this->input->post('data_partida');
        $dataRetorno = $this->input->post('data_retorno');

        try {
            $dataPartida = $dataPartida ? DateTime::createFromFormat('d/m/Y', $dataPartida)->format('Y-m-d') : null;
            $dataRetorno = $dataRetorno ? DateTime::createFromFormat('d/m/Y', $dataRetorno)->format('Y-m-d') : null;
        } catch (Exception $e) {
            $dataPartida = null;
            $dataRetorno = null;
        }

        $data = [
            'nome_viagem' => $this->input->post('nome_viagem'),
            'descricao' => $this->input->post('descricao'),
            'data_partida' => $dataPartida,
            'data_retorno' => $dataRetorno,
            'vagas' => (int)$this->input->post('vagas'),
            'preco_pessoa' => (float)$precoPessoa,
            'status' => $this->input->post('status'),
        ];

        if ($this->viagens_model->edit('viagens', $data, 'id', $id)) {
            // Limpa os cursos antigos e adiciona os novos
            $this->viagem_cursos_model->clearViagemCursos($id);
            $cursos = $this->input->post('cursos');
            if ($cursos) {
                foreach ($cursos as $curso_id) {
                    $this->viagem_cursos_model->add(['viagem_id' => $id, 'curso_id' => $curso_id]);
                }
            }
            $this->session->set_flashdata('success', 'Viagem editada com sucesso!');
            redirect(site_url('viagens/editar/' . $id));
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao editar a viagem.');
            redirect(site_url('viagens/editar/' . $id));
        }
    }

    public function visualizar($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar viagens.');
            redirect(base_url());
        }
        $this->data['result'] = $this->viagens_model->getById($id);
        $this->data['clientes'] = $this->viagem_clientes_model->getByViagem($id);
        $this->data['instrutores'] = $this->viagem_instrutores_model->getByViagem($id);
        $this->data['custos'] = $this->viagem_custos_model->getByViagem($id);
        $this->data['cursos_associados'] = $this->viagem_cursos_model->getByViagem($id);
        $this->data['cursos_disponiveis'] = $this->cursos_model->get('cursos', 'id, nome_curso');
        $this->data['view'] = 'viagens/visualizarViagem';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir viagens.');
            redirect(base_url());
        }
        $id = $this->input->post('id');
        if ($this->viagens_model->delete('viagens', 'id', $id)) {
            $this->session->set_flashdata('success', 'Viagem excluída com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao excluir a viagem.');
        }
        redirect(site_url('viagens'));
    }

    // Métodos para gerenciar clientes na viagem
    public function adicionar_cliente()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar clientes à viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $viagem = $this->viagens_model->getById($viagem_id);

        if ($viagem->vagas > 0) {
            $data = [
                'viagem_id' => $viagem_id,
                'cliente_id' => $this->input->post('cliente_id'),
                'status_pagamento' => $this->input->post('status_pagamento'),
                'precisa_embarque' => $this->input->post('precisa_embarque') ? 1 : 0,
                'precisa_hospedagem' => $this->input->post('precisa_hospedagem') ? 1 : 0,
                'detalhes_hospedagem' => $this->input->post('detalhes_hospedagem'),
                'numero_bolsa' => $this->input->post('numero_bolsa'),
            ];

            if ($this->viagem_clientes_model->add($data)) {
                // Decrementa o número de vagas
                $this->db->set('vagas', 'vagas - 1', false);
                $this->db->where('id', $viagem_id);
                $this->db->update('viagens');
                $this->session->set_flashdata('success', 'Cliente adicionado à viagem!');
            } else {
                $this->session->set_flashdata('error', 'Ocorreu um erro ao adicionar o cliente.');
            }
        } else {
            $this->session->set_flashdata('error', 'Não há mais vagas disponíveis para esta viagem.');
        }
        redirect('viagens/visualizar/' . $viagem_id);
    }
    public function editar_cliente_viagem($cliente_viagem_id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes da viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $data = [
            'status_pagamento' => $this->input->post('status_pagamento'),
            'precisa_embarque' => $this->input->post('precisa_embarque') ? 1 : 0,
            'precisa_hospedagem' => $this->input->post('precisa_hospedagem') ? 1 : 0,
            'detalhes_hospedagem' => $this->input->post('detalhes_hospedagem'),
            'numero_bolsa' => $this->input->post('numero_bolsa'),
        ];
        $this->viagem_clientes_model->edit($cliente_viagem_id, $data);
        redirect('viagens/visualizar/' . $viagem_id);
    }

    public function remover_cliente_viagem($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para remover clientes da viagem.');
            redirect(base_url());
        }
        // Lógica para obter o viagem_id antes de deletar para o redirect
        $this->db->where('id', $id);
        $cliente_viagem = $this->db->get('viagem_clientes')->row();
        if ($cliente_viagem) {
            if ($this->viagem_clientes_model->delete($id)) {
                // Incrementa o número de vagas
                $this->db->set('vagas', 'vagas + 1', false);
                $this->db->where('id', $cliente_viagem->viagem_id);
                $this->db->update('viagens');
                $this->session->set_flashdata('success', 'Cliente removido da viagem!');
            }
            redirect('viagens/visualizar/' . $cliente_viagem->viagem_id);
        } else {
            redirect('viagens');
        }
    }

    // Métodos para gerenciar instrutores na viagem
    public function adicionar_instrutor_viagem()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar instrutores à viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $data = [
            'viagem_id' => $viagem_id,
            'usuario_id' => $this->input->post('usuario_id'),
        ];
        $this->viagem_instrutores_model->add($data);
        redirect('viagens/visualizar/' . $viagem_id);
    }

    public function remover_instrutor_viagem($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para remover instrutores da viagem.');
            redirect(base_url());
        }
        $this->db->where('id', $id);
        $instrutor_viagem = $this->db->get('viagem_instrutores')->row();
        if ($instrutor_viagem) {
            $this->viagem_instrutores_model->delete($id);
            redirect('viagens/visualizar/' . $instrutor_viagem->viagem_id);
        } else {
            redirect('viagens');
        }
    }

    // Métodos para gerenciar custos da viagem
    public function adicionar_custo()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar custos à viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $data = [
            'viagem_id' => $viagem_id,
            'descricao' => $this->input->post('descricao'),
            'valor' => $this->input->post('valor'),
        ];
        $this->viagem_custos_model->add($data);
        redirect('viagens/visualizar/' . $viagem_id);
    }

    public function remover_custo($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para remover custos da viagem.');
            redirect(base_url());
        }
        $this->db->where('id', $id);
        $custo_viagem = $this->db->get('viagem_custos')->row();
        if ($custo_viagem) {
            $this->viagem_custos_model->delete($id);
            redirect('viagens/visualizar/' . $custo_viagem->viagem_id);
        } else {
            redirect('viagens');
        }
    }

    // Métodos para gerenciar cursos na viagem
    public function adicionar_curso_viagem()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar cursos à viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $data = [
            'viagem_id' => $viagem_id,
            'curso_id' => $this->input->post('curso_id'),
        ];
        $this->viagem_cursos_model->add($data);
        redirect('viagens/visualizar/' . $viagem_id . '#tabCursos');
    }

    public function remover_curso_viagem($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para remover cursos da viagem.');
            redirect(base_url());
        }
        $curso_viagem = $this->viagem_cursos_model->getById($id);
        if ($curso_viagem) {
            $this->viagem_cursos_model->delete($id);
            redirect('viagens/visualizar/' . $curso_viagem->viagem_id . '#tabCursos');
        } else {
            redirect('viagens');
        }
    }

    // Autocomplete
    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->like('nomeCliente', $q);
            $this->db->limit(5);
            $query = $this->db->get('clientes');
            $result = array_map(function ($cliente) {
                return ['id' => $cliente->idClientes, 'label' => $cliente->nomeCliente];
            }, $query->result());
            echo json_encode($result);
        }
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->like('nome', $q);
            $this->db->limit(5);
            $query = $this->db->get('usuarios');
            $result = array_map(function ($usuario) {
                return ['id' => $usuario->idUsuarios, 'label' => $usuario->nome];
            }, $query->result());
            echo json_encode($result);
        }
    }

    public function autoCompleteCurso()
    {
        // Este método foi movido para o controller de Cursos para melhor organização.
        // A view agora aponta para 'cursos/autoCompleteCurso'
    }
}