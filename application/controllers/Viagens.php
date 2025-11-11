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
        $this->load->model('clientes_model');
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
            'vagas_total' => (int)$this->input->post('vagas'),
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
            'vagas_total' => (int)$this->input->post('vagas'),
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
        log_message('debug', 'Viagens/visualizar: Instructors data for viagem_id ' . $id . ': ' . json_encode($this->data['instrutores']));
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

        $clientes_viagem = $this->viagem_clientes_model->getClientesComEquipamentos($id);
        $clientes_completos = [];

        // Itera sobre os clientes da viagem para buscar dados completos e certificações
        foreach ($clientes_viagem as $cliente_participante) {
            // Busca todos os dados do cliente da tabela 'clientes'
            $dados_cliente = $this->clientes_model->getById($cliente_participante->cliente_id);
            if ($dados_cliente) {
                // Mescla os dados da viagem com os dados completos do cliente
                $cliente_final = (object) array_merge((array) $dados_cliente, (array) $cliente_participante);
                $cliente_final->certificacoes = $this->certificacao_mergulhador_model->getByCliente($cliente_participante->cliente_id);
                $clientes_completos[] = $cliente_final;
            }
        }
        $this->data['clientes'] = $clientes_completos;

        // Template para resumo de equipamentos
        $resumoTemplate = [
            'cilindro' => 0, 'regulador' => 0, 'lastro' => ['qtd' => 0, 'peso' => 0.0], 'colete' => [], 'nadadeira' => [], 'neoprene' => [], 'lanterna' => 0, 'computador' => 0,
        ];

        $resumoMergulhadores = $resumoTemplate;
        $resumoInstrutores = $resumoTemplate;

        // Itera sobre os clientes para determinar necessidades de locação e calcular o resumo
        foreach ($this->data['clientes'] as $c) {
            // Se não possui o equipamento, marca para locar (a menos que já esteja marcado)
            $c->locar_colete = $c->locar_colete || !$c->possui_colete;
            $c->locar_lastro = $c->locar_lastro || !$c->possui_lastro;
            $c->locar_neoprene = $c->locar_neoprene || !$c->possui_neoprene;
            $c->locar_nadadeira = $c->locar_nadadeira || !$c->possui_nadadeira;
            $c->locar_regulador = $c->locar_regulador > 0 ? $c->locar_regulador : (!$c->possui_regulador ? 1 : 0);
            $c->locar_lanterna = $c->locar_lanterna > 0 ? $c->locar_lanterna : (!$c->possui_lanterna ? 1 : 0);
            $c->locar_computador = $c->locar_computador > 0 ? $c->locar_computador : (!$c->possui_computador ? 1 : 0);

            // Atualiza o resumo de equipamentos
            if ($c->locar_cilindro > 0) $resumoMergulhadores['cilindro'] += (int)$c->locar_cilindro;
            if ($c->locar_regulador > 0) $resumoMergulhadores['regulador'] += (int)$c->locar_regulador;
            if ($c->locar_lastro) {
                $resumoMergulhadores['lastro']['qtd']++;
                $resumoMergulhadores['lastro']['peso'] += (float)($c->peso_lastro ?: 0);
            }
            if ($c->locar_colete) $resumoMergulhadores['colete'][$c->tamanho_colete ?: 'N/I'] = ($resumoMergulhadores['colete'][$c->tamanho_colete ?: 'N/I'] ?? 0) + 1;
            if ($c->locar_nadadeira) $resumoMergulhadores['nadadeira'][$c->tamanho_nadadeira ?: 'N/I'] = ($resumoMergulhadores['nadadeira'][$c->tamanho_nadadeira ?: 'N/I'] ?? 0) + 1;
            if ($c->locar_neoprene) $resumoMergulhadores['neoprene'][$c->tamanho_neoprene ?: 'N/I'] = ($resumoMergulhadores['neoprene'][$c->tamanho_neoprene ?: 'N/I'] ?? 0) + 1;
            if ($c->locar_lanterna > 0) $resumoMergulhadores['lanterna'] += (int)$c->locar_lanterna;
            if ($c->locar_computador > 0) $resumoMergulhadores['computador'] += (int)$c->locar_computador;
        }

        $this->data['instrutores'] = $this->viagem_instrutores_model->getByViagem($id);

        // Adiciona equipamentos dos instrutores ao resumo
        $this->load->model('certificacao_usuario_model');
        foreach ($this->data['instrutores'] as $instrutor) {
            $instrutor->certificacoes = $this->certificacao_usuario_model->getByUsuario($instrutor->usuario_id);

            // Lógica para instrutores: se não possui e não marcou para locar, marca para locar 1.
            $instrutor->locar_regulador = $instrutor->locar_regulador > 0 ? $instrutor->locar_regulador : (!$instrutor->possui_regulador ? 1 : 0);
            $instrutor->locar_lanterna = $instrutor->locar_lanterna > 0 ? $instrutor->locar_lanterna : (!$instrutor->possui_lanterna ? 1 : 0);
            $instrutor->locar_computador = $instrutor->locar_computador > 0 ? $instrutor->locar_computador : (!$instrutor->possui_computador ? 1 : 0);

            if ($instrutor->locar_cilindro > 0) $resumoInstrutores['cilindro'] += (int)$instrutor->locar_cilindro;
            if ($instrutor->locar_regulador > 0) $resumoInstrutores['regulador'] += (int)$instrutor->locar_regulador;
            if ($instrutor->locar_lastro) {
                $resumoInstrutores['lastro']['qtd']++;
                $resumoInstrutores['lastro']['peso'] += (float)($instrutor->peso_lastro ?: 0);
            }
            if ($instrutor->locar_colete) $resumoInstrutores['colete'][$instrutor->tamanho_colete ?: 'N/I'] = ($resumoInstrutores['colete'][$instrutor->tamanho_colete ?: 'N/I'] ?? 0) + 1;
            if ($instrutor->locar_nadadeira) $resumoInstrutores['nadadeira'][$instrutor->tamanho_nadadeira ?: 'N/I'] = ($resumoInstrutores['nadadeira'][$instrutor->tamanho_nadadeira ?: 'N/I'] ?? 0) + 1;
            if ($instrutor->locar_neoprene) $resumoInstrutores['neoprene'][$instrutor->tamanho_neoprene ?: 'N/I'] = ($resumoInstrutores['neoprene'][$instrutor->tamanho_neoprene ?: 'N/I'] ?? 0) + 1;
            if ($instrutor->locar_lanterna > 0) $resumoInstrutores['lanterna'] += (int)$instrutor->locar_lanterna;
            if ($instrutor->locar_computador > 0) $resumoInstrutores['computador'] += (int)$instrutor->locar_computador;
        }

        $this->data['resumoMergulhadores'] = $resumoMergulhadores;
        $this->data['resumoInstrutores'] = $resumoInstrutores;
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
                    'locar_lanterna' => (int)$this->input->post('locar_lanterna') ?: 0,
                    'locar_computador' => (int)$this->input->post('locar_computador') ?: 0,
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

        // Se a aba ativa for 'tabHospedagem', salva apenas os dados de hospedagem.
        // Caso contrário, assume que a edição vem do modal e salva todos os dados.
        $data = ($activeTab === 'tabHospedagem')
            ? $this->get_hospedagem_data_from_post()
            : $this->get_all_cliente_viagem_data_from_post();

        if ($this->viagem_clientes_model->edit('viagem_clientes', $data, 'id', $cliente_viagem_id)) {
            $this->session->set_flashdata('success', 'Detalhes de hospedagem atualizados com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao atualizar os detalhes da hospedagem.');
        }
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . ($activeTab ?: 'tabClientes'));
    }

    public function editar_instrutor_viagem($instrutor_viagem_id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eViagem')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar instrutores da viagem.');
            redirect(base_url());
        }
        $viagem_id = $this->input->post('viagem_id');
        $activeTab = ltrim($this->input->post('active_tab'), '#');

        // Se a aba ativa for 'tabHospedagem', salva apenas os dados de hospedagem.
        // Caso contrário, assume que a edição vem do modal e salva todos os dados.
        $data = ($activeTab === 'tabHospedagem')
            ? $this->get_hospedagem_data_from_post()
            : $this->get_all_instrutor_viagem_data_from_post();

        if ($this->viagem_instrutores_model->edit('viagem_instrutores', $data, 'id', $instrutor_viagem_id)) {
            $this->session->set_flashdata('success', 'Detalhes de hospedagem do instrutor atualizados com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao atualizar os detalhes da hospedagem.');
        }
        redirect('viagens/visualizar/' . $viagem_id . '?tab=' . ($activeTab ?: 'tabInstrutores'));
    }

    private function get_hospedagem_data_from_post()
    {
        return [
            'detalhes_hospedagem' => $this->input->post('detalhes_hospedagem'),
            'hospedagem_quarto_numero' => $this->input->post('hospedagem_quarto_numero'),
            'hospedagem_tipo_quarto' => $this->input->post('hospedagem_tipo_quarto'),
            'hospedagem_numero_camas' => $this->input->post('hospedagem_numero_camas'),
        ];
    }

    private function get_all_cliente_viagem_data_from_post()
    {
        return [
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
            'locar_lanterna' => $this->input->post('locar_lanterna') ? 1 : 0,
            'qtd_lanterna' => (int)$this->input->post('qtd_lanterna'),
            'locar_computador' => $this->input->post('locar_computador') ? 1 : 0,
            'qtd_computador' => (int)$this->input->post('qtd_computador'),
        ];
    }

    private function get_all_instrutor_viagem_data_from_post()
    {
        return [
            'precisa_embarque' => $this->input->post('precisa_embarque') ? 1 : 0,
            'precisa_hospedagem' => $this->input->post('precisa_hospedagem') ? 1 : 0,
            'numero_bolsa' => $this->input->post('numero_bolsa'),
            'proposito' => $this->input->post('proposito'),
            'status_pagamento' => $this->input->post('status_pagamento'),
            'locar_nadadeira' => $this->input->post('locar_nadadeira') ? 1 : 0,
            'locar_colete' => $this->input->post('locar_colete') ? 1 : 0,
            'locar_neoprene' => $this->input->post('locar_neoprene') ? 1 : 0,
            'locar_lastro' => $this->input->post('locar_lastro') ? 1 : 0,
            'locar_cilindro' => (int)$this->input->post('locar_cilindro'),
            'locar_regulador' => (int)$this->input->post('locar_regulador'),
            'locar_lanterna' => (int)$this->input->post('locar_lanterna'),
            'locar_computador' => (int)$this->input->post('locar_computador'),
        ];
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
        $usuario_id = $this->input->post('usuario_id');
        $activeTab = ltrim($this->input->post('active_tab'), '#') ?: 'tabInstrutores';

        $this->load->library('form_validation');
        $this->form_validation->set_rules('viagem_id', 'ID da Viagem', 'required');
        $this->form_validation->set_rules('usuario_id', 'Instrutor', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
        } elseif ($this->viagem_instrutores_model->isInstrutorInViagem($viagem_id, $usuario_id)) {
            $this->session->set_flashdata('error', 'Este instrutor já está atribuído a esta viagem.');
        } else {
            $data = [
                'viagem_id' => $viagem_id,
                'usuario_id' => $usuario_id,
                'precisa_embarque' => $this->input->post('precisa_embarque_instrutor') ? 1 : 0,
                'numero_bolsa' => $this->input->post('numero_bolsa_instrutor'),
                'proposito' => $this->input->post('proposito_instrutor'),
                'status_pagamento' => $this->input->post('status_pagamento_instrutor'),
                'locar_nadadeira' => $this->input->post('locar_nadadeira_instrutor') ? 1 : 0,
                'locar_cilindro' => (int)$this->input->post('locar_cilindro_instrutor') ?: 0,
                'locar_colete' => $this->input->post('locar_colete_instrutor') ? 1 : 0,
                'locar_neoprene' => $this->input->post('locar_neoprene_instrutor') ? 1 : 0,
                'locar_regulador' => (int)$this->input->post('locar_regulador_instrutor') ?: 0,
                'locar_lastro' => $this->input->post('locar_lastro_instrutor') ? 1 : 0,
                'locar_lanterna' => (int)$this->input->post('locar_lanterna_instrutor') ?: 0,
                'locar_computador' => (int)$this->input->post('locar_computador_instrutor') ?: 0,
                'precisa_hospedagem' => $this->input->post('precisa_hospedagem_instrutor') ? 1 : 0,
            ];
            if ($this->viagem_instrutores_model->add($data)) {
                $this->session->set_flashdata('success', 'Instrutor adicionado à viagem com sucesso!');
                log_info('Adicionou instrutor ID: ' . $usuario_id . ' à viagem ID: ' . $viagem_id);
            } else {
                $this->session->set_flashdata('error', 'Ocorreu um erro ao adicionar o instrutor.');
            }
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
            $this->db->select('idClientes, nomeCliente, documento, telefone');
            $this->db->group_start();
            $this->db->like('LOWER(nomeCliente)', $q);
            $this->db->or_like('documento', $q);
            $this->db->group_end();
            $this->db->limit(5);
            $query = $this->db->get('clientes');
            $result = array_map(function ($cliente) {
                $label = $cliente->nomeCliente . ' (CPF: ' . $cliente->documento . ' | Tel: ' . $cliente->telefone . ')';
                return ['id' => $cliente->idClientes, 'label' => $label, 'nome' => $cliente->nomeCliente];
            }, $query->result());
            echo json_encode($result);
        }
    }

    public function getClienteData($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $cliente = $this->clientes_model->getById($id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($cliente));
    }

    public function getUsuarioData($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vUsuario')) {
            return $this->output->set_status_header(403)->set_output(json_encode(['error' => 'Acesso não autorizado.']));
        }

        $this->load->model('usuarios_model');
        $usuario = $this->usuarios_model->getById($id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($usuario));
    }

    public function getUsuarioDataForSync($id)
    {
        $this->load->model('usuarios_model');
        $usuario = $this->usuarios_model->getById($id);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($usuario));
    }
    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select('idUsuarios, nome, cpf, telefone');
            $this->db->group_start();
            $this->db->like('LOWER(nome)', $q);
            $this->db->or_like('cpf', $q);
            $this->db->group_end();
            $this->db->limit(5);
            $query = $this->db->get('usuarios');
            $result = array_map(function ($usuario) {
                $label = $usuario->nome . ' (CPF: ' . $usuario->cpf . ' | Tel: ' . $usuario->telefone . ')';
                return ['id' => $usuario->idUsuarios, 'label' => $label, 'nome' => $usuario->nome];
            }, $query->result());
            echo json_encode($result);
        }
    }

    public function autoCompleteCurso()
    {
        // Este método foi movido para o controller de Cursos para melhor organização.
        // A view agora aponta para 'cursos/autoCompleteCurso'
    }

    public function autoCompleteViagem()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select('id, nome_viagem, data_partida, preco_pessoa');
            $this->db->like('nome_viagem', $q);
            $this->db->limit(5);
            $query = $this->db->get('viagens');
            $result = array_map(function ($viagem) {
                return [
                    'id' => $viagem->id,
                    'text' => 'ID: ' . $viagem->id . ' | Viagem: ' . $viagem->nome_viagem . ' | Partida: ' . date('d/m/Y', strtotime($viagem->data_partida)),
                    'preco' => $viagem->preco_pessoa,
                ];
            }, $query->result());
            return $this->output->set_content_type('application/json')->set_output(json_encode(['results' => $result]));
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode([]));
    }
}