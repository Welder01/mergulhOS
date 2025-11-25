<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Treinos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar treinos.');
            redirect(base_url());
        }

        $this->load->model('treinos_model');
    }

    public function index()
    {
        $this->data['results'] = $this->treinos_model->get('treinos_config', '*', '', 100, 0, false);
    
        // Filtros
        $pesquisaCliente = $this->input->get('pesquisa_cliente');
        $pesquisaTreino = $this->input->get('pesquisa_treino');
        $dataInicial = $this->input->get('data_inicial');
        $dataFinal = $this->input->get('data_final');
        $status = $this->input->get('status');
    
        $this->db->select('ta.*, tc.nome as nome_treino, c.nomeCliente as nome_cliente');
        $this->db->from('treinos_agendados as ta');
        $this->db->join('treinos_config as tc', 'tc.id = ta.config_id');
        $this->db->join('clientes as c', 'c.idClientes = ta.cliente_id');
    
        if ($pesquisaCliente) {
            $this->db->like('c.nomeCliente', $pesquisaCliente);
        }
        if ($pesquisaTreino) {
            $this->db->like('tc.nome', $pesquisaTreino);
        }
        if ($dataInicial) {
            $this->db->where('DATE(ta.data_hora_inicio) >=', date('Y-m-d', strtotime(str_replace('/', '-', $dataInicial))));
        }
        if ($dataFinal) {
            $this->db->where('DATE(ta.data_hora_inicio) <=', date('Y-m-d', strtotime(str_replace('/', '-', $dataFinal))));
        }
        if ($status) {
            $this->db->where('ta.status', $status);
        }
    
        $this->db->order_by('ta.data_hora_inicio', 'DESC');
        $this->data['agendamentos'] = $this->db->get()->result();
    
        $this->data['menuTreinos'] = 'active';
        $this->data['view'] = 'treinos/treinos';

        return $this->layout();
    }

    public function adicionarConfiguracao()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'aTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar configurações de treino.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('duracao_minutos', 'Duração', 'required|integer');
        $this->form_validation->set_rules('preco_sem_instrutor', 'Preço Sem Instrutor', 'required');
        $this->form_validation->set_rules('preco_com_instrutor', 'Preço Com Instrutor', 'required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $preco_sem_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_sem_instrutor'));
            $preco_com_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_com_instrutor'));

            $data = [
                'nome' => $this->input->post('nome'),
                'descricao' => $this->input->post('descricao'),
                'duracao_minutos' => $this->input->post('duracao_minutos'),
                'preco_sem_instrutor' => $preco_sem_instrutor,
                'preco_com_instrutor' => $preco_com_instrutor,
                'dias_semana_disponiveis' => implode(',', $this->input->post('dias_semana_disponiveis') ?: []),
                'horario_inicio' => $this->input->post('horario_inicio'),
                'horario_fim' => $this->input->post('horario_fim'),
                'status' => $this->input->post('status'),
                'limite_vagas' => $this->input->post('limite_vagas') ?: null,
                'limite_alunos_instrutor' => $this->input->post('limite_alunos_instrutor') ?: null,
                'cancelamento_limite_dias' => $this->input->post('cancelamento_limite_dias') ?: 0,
                'cancelamento_limite_horas' => $this->input->post('cancelamento_limite_horas') ?: 0,
                'instrutores_ids' => $this->input->post('instrutores_ids'),
            ];

            if ($this->treinos_model->add('treinos_config', $data) == true) {
                $this->session->set_flashdata('success', 'Configuração de treino adicionada com sucesso!');
                log_info('Adicionou uma configuração de treino');
                redirect(site_url('treinos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['menuTreinos'] = 'active';
        $this->data['view'] = 'treinos/adicionarTreino';
        return $this->layout();
    }

    public function editarConfiguracao()
    {
        if (! $this->uri->segment(3) || ! is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('treinos');
        }

        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar configurações de treino.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('duracao_minutos', 'Duração', 'required|integer');
        $this->form_validation->set_rules('preco_sem_instrutor', 'Preço Sem Instrutor', 'required');
        $this->form_validation->set_rules('preco_com_instrutor', 'Preço Com Instrutor', 'required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $preco_sem_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_sem_instrutor'));
            $preco_com_instrutor = str_replace(['.', ','], ['', '.'], $this->input->post('preco_com_instrutor'));

            $data = [
                'nome' => $this->input->post('nome'),
                'descricao' => $this->input->post('descricao'),
                'duracao_minutos' => $this->input->post('duracao_minutos'),
                'preco_sem_instrutor' => $preco_sem_instrutor,
                'preco_com_instrutor' => $preco_com_instrutor,
                'dias_semana_disponiveis' => implode(',', $this->input->post('dias_semana_disponiveis') ?: []),
                'horario_inicio' => $this->input->post('horario_inicio'),
                'horario_fim' => $this->input->post('horario_fim'),
                'status' => $this->input->post('status'),
                'limite_vagas' => $this->input->post('limite_vagas') ?: null,
                'limite_alunos_instrutor' => $this->input->post('limite_alunos_instrutor') ?: null,
                'cancelamento_limite_dias' => $this->input->post('cancelamento_limite_dias') ?: 0,
                'cancelamento_limite_horas' => $this->input->post('cancelamento_limite_horas') ?: 0,
                'instrutores_ids' => $this->input->post('instrutores_ids'),
            ];

            if ($this->treinos_model->edit('treinos_config', $data, 'id', $this->input->post('id')) == true) {
                $this->session->set_flashdata('success', 'Configuração de treino editada com sucesso!');
                log_info('Alterou uma configuração de treino. ID: ' . $this->input->post('id'));
                redirect(site_url('treinos/editarConfiguracao/') . $this->input->post('id'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->treinos_model->getById($this->uri->segment(3));
        $this->data['view'] = 'treinos/editarTreino';
        return $this->layout();
    }

    public function excluirConfiguracao()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir configurações de treino.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir configuração de treino.');
            redirect(site_url('treinos'));
        }

        $this->treinos_model->delete('treinos_config', 'id', $id);
        log_info('Removeu uma configuração de treino. ID: ' . $id);
        $this->session->set_flashdata('success', 'Configuração de treino excluída com sucesso!');
        redirect(site_url('treinos'));
    }

    public function autoCompleteInstrutores()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($this->input->get('term'));
            $this->db->select('idUsuarios, nome, cpf, celular, telefone');
            $this->db->group_start();
            $this->db->like('LOWER(nome)', $q);
            $this->db->or_like('cpf', $q);
            $this->db->group_end();
            $this->db->limit(10);
            $query = $this->db->get('usuarios');
            
            $result = [];
            foreach ($query->result() as $row) {
                $nomeCompleto = explode(' ', $row->nome);
                $nomeCurto = $nomeCompleto[0] . (isset($nomeCompleto[1]) ? ' ' . $nomeCompleto[1] : '');
                $telefone = !empty($row->celular) ? $row->celular : $row->telefone;

                $label = $nomeCurto . ' (CPF: ' . $row->cpf . ' | Cel: ' . $telefone . ')';
                $result[] = ['id' => $row->idUsuarios, 'label' => $label];
            }
            echo json_encode($result);
        }
    }

    public function getInstrutoresInfo()
    {
        $ids = $this->input->post('ids');
        if (!empty($ids)) {
            $this->db->select('idUsuarios, nome, cpf, celular, telefone');
            $this->db->where_in('idUsuarios', $ids);
            $query = $this->db->get('usuarios');
            
            $result = [];
            foreach ($query->result() as $row) {
                $nomeCompleto = explode(' ', $row->nome);
                $nomeCurto = $nomeCompleto[0] . (isset($nomeCompleto[1]) ? ' ' . $nomeCompleto[1] : '');
                $telefone = !empty($row->celular) ? $row->celular : $row->telefone;
                $label = $nomeCurto . ' (CPF: ' . $row->cpf . ' | Cel: ' . $telefone . ')';
                
                $result[] = ['id' => $row->idUsuarios, 'label' => $label];
            }
            echo json_encode($result);
        }
    }

    public function excluirAgendamento()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'dTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir agendamentos de treino.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir agendamento.');
            redirect(site_url('treinos'));
        }

        $this->treinos_model->delete('treinos_agendados', 'id', $id);
        log_info('Removeu um agendamento de treino. ID: ' . $id);
        $this->session->set_flashdata('success', 'Agendamento de treino excluído com sucesso!');
        redirect(site_url('treinos'));
    }

    public function visualizarTreino()
    {
        $isCliente = $this->session->userdata('tipo_usuario') == 'cliente';
        $redirect_path = $isCliente ? 'mine/treinos' : 'treinos';

        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect($redirect_path);
        }

        $this->data['result'] = $this->treinos_model->getAgendamentoById($this->uri->segment(3));

        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Treino não encontrado.');
            redirect(base_url() . 'index.php/' . $redirect_path);
        }

        if ($isCliente && $this->data['result']->cliente_id == $this->session->userdata('cliente_id')) {
            $this->data['view'] = 'mine/visualizarTreino';
            $this->load->view('conecte/template', $this->data);
        } else {
            $this->data['view'] = 'treinos/visualizarTreino';
            return $this->layout();
        }
    }

    public function getTreinoJson()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $id = $this->input->post('id');
        $treino = $this->treinos_model->getAgendamentoById($id);

        if ($treino) {
            echo json_encode($treino);
        } else {
            echo json_encode(['error' => 'Treino não encontrado.']);
        }
    }

    public function reagendarTreino()
    {
        if (! $this->permission->checkPermission($this->session->userdata('permissao'), 'eTreino')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para reagendar treinos.');
            redirect(site_url('treinos'));
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_agendamento', 'ID do Agendamento', 'required|integer');
        $this->form_validation->set_rules('data_hora_reagendamento', 'Nova Data e Hora', 'required');
        $this->form_validation->set_rules('treino_config_id', 'Tipo de Treino', 'required|integer');

        if ($this->form_validation->run() == false)
        {
            $this->session->set_flashdata('error', 'Erro de validação: ' . validation_errors());
            redirect(site_url('treinos'));
            return;
        }

        $idAgendamento = $this->input->post('id_agendamento');
        $novaDataHoraStr = $this->input->post('data_hora_reagendamento');
        $configId = $this->input->post('treino_config_id');
        $comInstrutor = $this->input->post('com_instrutor') ? 1 : 0;
        $instrutorId = $this->input->post('instrutor_id') ?: null;

        $config = $this->treinos_model->getById($configId);
        if (!$config)
        {
            // Se a configuração não for encontrada, pode ser que o treino não foi alterado.
            // Vamos buscar o agendamento original para pegar o config_id.
            $agendamentoOriginal = $this->treinos_model->getAgendamentoById($idAgendamento);
            if ($agendamentoOriginal) {
                $config = $this->treinos_model->getById($agendamentoOriginal->config_id);
            }
        }

        if (!$config) {
            $this->session->set_flashdata('error', 'Configuração de treino inválida.');
            redirect(site_url('treinos'));
        }

        $inicioTreino = DateTime::createFromFormat('d/m/Y H:i', $novaDataHoraStr);
        $fimTreino = clone $inicioTreino;
        $fimTreino->add(new DateInterval('PT' . $config->duracao_minutos . 'M'));

        $data = [
            'data_hora_inicio' => $inicioTreino->format('Y-m-d H:i:s'),
            'data_hora_fim' => $fimTreino->format('Y-m-d H:i:s'),
            'config_id' => $configId,
            'com_instrutor' => $comInstrutor,
            'instrutor_id' => $instrutorId,
            'valor_cobrado' => $comInstrutor ? $config->preco_com_instrutor : $config->preco_sem_instrutor,
        ];

        if ($this->treinos_model->edit('treinos_agendados', $data, 'id', $idAgendamento)) {
            $this->session->set_flashdata('success', 'Treino reagendado com sucesso!');
            log_info('Reagendou um treino. ID do Agendamento: ' . $idAgendamento);
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao reagendar o treino.');
        }

        redirect(site_url('treinos'));
    }

    public function getInstrutoresDisponiveis()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
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

        // Busca os IDs dos instrutores configurados para este treino
        $instrutores_configurados_ids = !empty($config->instrutores_ids) ? explode(',', $config->instrutores_ids) : [];

        if (empty($instrutores_configurados_ids)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([])); // Retorna vazio se nenhum instrutor configurado
        }

        $this->db->select('u.idUsuarios as id, u.nome');
        $this->db->from('usuarios u');
        $this->db->where('u.situacao', 1);
        $this->db->where_in('u.idUsuarios', $instrutores_configurados_ids); // Filtra apenas pelos instrutores configurados
        
        // Adicionar lógica para verificar disponibilidade futura (se necessário)
        // Por exemplo, verificar se o instrutor já tem um treino agendado no mesmo horário.
        
        $instrutores = $this->db->get()->result();

        return $this->output->set_content_type('application/json')->set_output(json_encode($instrutores));
    }
}