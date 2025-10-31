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
        $this->load->model('mapos_model');
        $this->load->model('certificacao_mergulhador_model');
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
        $pesquisa = $this->input->get('pesquisa');

        $this->load->library('pagination');
        $this->data['configuration']['base_url'] = site_url('viagens/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->viagens_model->count('viagens', $pesquisa);
        $this->pagination->initialize($this->data['configuration']);
        $this->data['results'] = $this->viagens_model->get('viagens', '*', $pesquisa, $this->data['configuration']['per_page'], $this->uri->segment(3));
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

    public function imprimir($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para imprimir a ficha da viagem.');
            redirect(base_url());
        }

        $this->data['result'] = $this->viagens_model->getById($id);
        if (!$this->data['result']) {
            $this->session->set_flashdata('error', 'Viagem não encontrada.');
            redirect(site_url('viagens'));
        }

        $this->data['clientes'] = $this->viagem_clientes_model->getByViagem($id);
        $this->data['instrutores'] = $this->viagem_instrutores_model->getByViagem($id);
        $this->data['emitente'] = $this->mapos_model->getEmitente();

        $this->load->helper('mpdf');
        $html = $this->load->view('viagens/imprimirViagem', $this->data, true);
        pdf_create($html, 'ficha_viagem_' . $id, true);
    }

    public function imprimirOperacao($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para imprimir a ficha de operação.');
            redirect(base_url());
        }

        $this->data['result'] = $this->viagens_model->getById($id);
        if (!$this->data['result']) {
            $this->session->set_flashdata('error', 'Viagem não encontrada.');
            redirect(site_url('viagens'));
        }

        $clientes = $this->viagem_clientes_model->getClientesComEquipamentos($id);
        
        // Adiciona todas as certificações para cada cliente
        foreach ($clientes as $cliente) {
            $cliente->certificacoes = $this->certificacao_mergulhador_model->getByCliente($cliente->cliente_id);
        }
        $this->data['clientes'] = $clientes;

        // Calcula o resumo de equipamentos
        $resumoEquipamentos = [
            'cilindro' => 0, 'regulador' => 0, 'lastro' => 0, 'colete' => [], 'nadadeira' => [], 'neoprene' => [],
        ];

        foreach ($clientes as $c) {
            if ($c->locar_cilindro) $resumoEquipamentos['cilindro']++;
            if ($c->locar_regulador) $resumoEquipamentos['regulador']++;
            if ($c->locar_lastro) $resumoEquipamentos['lastro']++;
            if ($c->locar_colete) $resumoEquipamentos['colete'][$c->tamanho_colete ?: 'N/I'] = ($resumoEquipamentos['colete'][$c->tamanho_colete ?: 'N/I'] ?? 0) + 1;
            if ($c->locar_nadadeira) $resumoEquipamentos['nadadeira'][$c->tamanho_nadadeira ?: 'N/I'] = ($resumoEquipamentos['nadadeira'][$c->tamanho_nadadeira ?: 'N/I'] ?? 0) + 1;
            if ($c->locar_neoprene) $resumoEquipamentos['neoprene'][$c->tamanho_neoprene ?: 'N/I'] = ($resumoEquipamentos['neoprene'][$c->tamanho_neoprene ?: 'N/I'] ?? 0) + 1;
        }
        $this->data['resumoEquipamentos'] = $resumoEquipamentos;

        $this->data['instrutores'] = $this->viagem_instrutores_model->getByViagem($id);

        // Adiciona equipamentos dos instrutores ao resumo
        foreach ($this->data['instrutores'] as $instrutor) {
            if ($instrutor->locar_cilindro > 0) $this->data['resumoEquipamentos']['cilindro'] += $instrutor->locar_cilindro;
            if ($instrutor->locar_regulador > 0) $this->data['resumoEquipamentos']['regulador'] += $instrutor->locar_regulador;
            if ($instrutor->locar_lastro) $this->data['resumoEquipamentos']['lastro']++;
            if ($instrutor->locar_colete) {
                $this->data['resumoEquipamentos']['colete'][$instrutor->tamanho_colete ?: 'N/I'] = ($this->data['resumoEquipamentos']['colete'][$instrutor->tamanho_colete ?: 'N/I'] ?? 0) + 1;
            }
            if ($instrutor->locar_nadadeira) {
                $this->data['resumoEquipamentos']['nadadeira'][$instrutor->tamanho_nadadeira ?: 'N/I'] = ($this->data['resumoEquipamentos']['nadadeira'][$instrutor->tamanho_nadadeira ?: 'N/I'] ?? 0) + 1;
            }
            if ($instrutor->locar_neoprene) {
                $this->data['resumoEquipamentos']['neoprene'][$instrutor->tamanho_neoprene ?: 'N/I'] = ($this->data['resumoEquipamentos']['neoprene'][$instrutor->tamanho_neoprene ?: 'N/I'] ?? 0) + 1;
            }
        }

        $this->data['emitente'] = $this->mapos_model->getEmitente();

        $this->load->helper('mpdf');
        $html = $this->load->view('viagens/imprimirOperacao', $this->data, true);
        pdf_create($html, 'ficha_operacao_' . $id, true);
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

        if ($this->viagem_clientes_model->isClienteInViagem($viagem_id, $this->input->post('cliente_id'))) {
            $this->session->set_flashdata('error', 'Este cliente já está inscrito nesta viagem.');
        } else {
            if ($viagem->vagas > 0) {
                $data = [
                    'viagem_id' => $viagem_id,
                    'cliente_id' => $this->input->post('cliente_id'),
                    'status_pagamento' => $this->input->post('status_pagamento'),
                    'precisa_embarque' => $this->input->post('precisa_embarque') ? 1 : 0,
                    'precisa_hospedagem' => $this->input->post('precisa_hospedagem') ? 1 : 0,
                    'detalhes_hospedagem' => $this->input->post('detalhes_hospedagem'),
                    'locar_nadadeira' => $this->input->post('locar_nadadeira') ? 1 : 0,
                    'locar_cilindro' => $this->input->post('locar_cilindro') ? 1 : 0,
                    'locar_colete' => $this->input->post('locar_colete') ? 1 : 0,
                    'locar_neoprene' => $this->input->post('locar_neoprene') ? 1 : 0,
                    'locar_regulador' => $this->input->post('locar_regulador') ? 1 : 0,
                    'numero_bolsa' => $this->input->post('numero_bolsa'),
                    'proposito' => $this->input->post('proposito'),
                ];

                if ($this->viagem_clientes_model->add('viagem_clientes', $data)) {
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
        }
        $activeTab = ltrim($this->input->post('active_tab'), '#');
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . $activeTab);
    }
    public function editar_cliente_viagem($cliente_viagem_id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes da viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $activeTab = ltrim($this->input->post('active_tab'), '#');
        $data = array_merge(
            [
                'detalhes_hospedagem' => $this->input->post('detalhes_hospedagem'),
                'hospedagem_quarto_numero' => $this->input->post('hospedagem_quarto_numero'),
                'hospedagem_tipo_quarto' => $this->input->post('hospedagem_tipo_quarto'),
                'hospedagem_numero_camas' => $this->input->post('hospedagem_numero_camas'),
                'numero_bolsa' => $this->input->post('numero_bolsa'),
                'status_pagamento' => $this->input->post('status_pagamento'),
                'proposito' => $this->input->post('proposito'),
                'precisa_embarque' => $this->input->post('precisa_embarque') ? 1 : 0,
                'precisa_hospedagem' => $this->input->post('precisa_hospedagem') ? 1 : 0,
                'locar_nadadeira' => $this->input->post('locar_nadadeira') ? 1 : 0,
                'locar_colete' => $this->input->post('locar_colete') ? 1 : 0,
                'locar_neoprene' => $this->input->post('locar_neoprene') ? 1 : 0,
                'locar_lastro' => $this->input->post('locar_lastro') ? 1 : 0,
                'locar_cilindro' => (int)$this->input->post('locar_cilindro'),
                'locar_regulador' => (int)$this->input->post('locar_regulador'),
            ]
        );
        if ($this->viagem_clientes_model->edit('viagem_clientes', $data, 'id', $cliente_viagem_id)) {
            $this->session->set_flashdata('success', 'Detalhes de hospedagem atualizados com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao atualizar os detalhes da hospedagem.');
        }
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . $activeTab);
    }

    public function editar_instrutor_viagem($instrutor_viagem_id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar instrutores da viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $activeTab = ltrim($this->input->post('active_tab'), '#');
        $data = [
            'detalhes_hospedagem' => $this->input->post('detalhes_hospedagem'),
            'hospedagem_quarto_numero' => $this->input->post('hospedagem_quarto_numero'),
            'hospedagem_tipo_quarto' => $this->input->post('hospedagem_tipo_quarto'),
            'hospedagem_numero_camas' => $this->input->post('hospedagem_numero_camas'),
        ];
        if ($this->viagem_instrutores_model->edit($instrutor_viagem_id, $data)) {
            $this->session->set_flashdata('success', 'Detalhes de hospedagem do instrutor atualizados com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao atualizar os detalhes da hospedagem.');
        }
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . $activeTab);
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
            if ($this->viagem_clientes_model->delete('viagem_clientes', 'id', $id)) {
                // Incrementa o número de vagas
                $this->db->set('vagas', 'vagas + 1', false);
                $this->db->where('id', $cliente_viagem->viagem_id);
                $this->db->update('viagens');
                $this->session->set_flashdata('success', 'Cliente removido da viagem!');
            }
            redirect('viagens/visualizar/' . $cliente_viagem->viagem_id . '?tab=tabClientes');
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
        $activeTab = ltrim($this->input->post('active_tab'), '#');
        $usuario_id = $this->input->post('usuario_id');

        if ($this->viagem_instrutores_model->isInstrutorInViagem($viagem_id, $usuario_id)) {
            $this->session->set_flashdata('error', 'Este instrutor já está nesta viagem.');
        } else {
            $data = [
                'viagem_id' => $viagem_id,
                'usuario_id' => $usuario_id,
                'precisa_embarque' => $this->input->post('precisa_embarque_instrutor') ? 1 : 0,
                'numero_bolsa' => $this->input->post('numero_bolsa_instrutor'),
                'locar_nadadeira' => $this->input->post('locar_nadadeira_instrutor') ? 1 : 0,
                'locar_cilindro' => (int)$this->input->post('locar_cilindro_instrutor') ?: 0,
                'locar_colete' => $this->input->post('locar_colete_instrutor') ? 1 : 0,
                'locar_neoprene' => $this->input->post('locar_neoprene_instrutor') ? 1 : 0,
                'locar_regulador' => (int)$this->input->post('locar_regulador_instrutor') ?: 0,
                'locar_lastro' => $this->input->post('locar_lastro_instrutor') ? 1 : 0,
                'precisa_hospedagem' => $this->input->post('precisa_hospedagem_instrutor') ? 1 : 0,
            ];
            $this->viagem_instrutores_model->add($data);
        }
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . $activeTab);
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
            redirect('viagens/visualizar/' . $instrutor_viagem->viagem_id . '?tab=tabInstrutores');
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
        $activeTab = ltrim($this->input->post('active_tab'), '#');
        $data = [
            'viagem_id' => $viagem_id,
            'descricao' => $this->input->post('descricao'),
            'valor' => $this->input->post('valor'),
        ];
        $this->viagem_custos_model->add($data);
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . $activeTab);
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
            redirect('viagens/visualizar/' . $custo_viagem->viagem_id . '?tab=tabCustos');
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
        $activeTab = ltrim($this->input->post('active_tab'), '#');
        $data = [
            'viagem_id' => $viagem_id,
            'curso_id' => $this->input->post('curso_id'),
        ];
        $this->viagem_cursos_model->add($data);
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . $activeTab);
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
            redirect('viagens/visualizar/' . $curso_viagem->viagem_id . '?tab=tabCursos');
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