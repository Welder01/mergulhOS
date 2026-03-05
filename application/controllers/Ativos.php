<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Ativos extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'codegen_helper'));
        $this->load->model('ativos_model');
        $this->data['menuAtivos'] = 'Ativos';
    }

    public function index() {
        $this->gerenciar();
    }

    public function gerenciar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar ativos.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('ativos/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->ativos_model->count('ativos');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->ativos_model->get('ativos', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
        $this->data['bolsas'] = $this->ativos_model->getBolsas();
        $this->data['pendentes'] = $this->ativos_model->getPendentes();

        $this->data['view'] = 'ativos/ativos';
        return $this->layout();
    }

    public function adicionar() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar ativos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('ativos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
                'patrimonio' => $this->input->post('patrimonio'),
                'codigo_qr' => md5(uniqid(rand(), true)), // Temporário, ideal é gerar URL curta
                'status' => $this->input->post('status'),
                'categoria_id' => $this->input->post('categoria_id'),
                'data_cadastro' => date('Y-m-d H:i:s')
            ];

            if (!empty($_FILES['userfile']['name'])) {
                $upload = $this->do_upload();
                if (isset($upload['upload_data'])) {
                    $data['foto'] = $upload['upload_data']['file_name'];
                } else {
                    $this->session->set_flashdata('error', $upload['error']);
                    redirect(site_url('ativos/adicionar/'));
                }
            }

            if ($id = $this->ativos_model->add('ativos', $data)) {
                $this->ativos_model->log_acao($id, 'criacao', 'Ativo cadastrado no sistema');
                $this->session->set_flashdata('success', 'Ativo adicionado com sucesso!');
                redirect(site_url('ativos/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['categorias'] = $this->ativos_model->get('ativos_categorias', '*');
        $this->data['view'] = 'ativos/adicionarAtivo';
        return $this->layout();
    }

    public function adicionarBolsa() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar bolsas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('bolsas') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
                'codigo_identificador' => $this->input->post('codigo_identificador'),
                'codigo_qr' => md5(uniqid(rand(), true)),
                'status' => $this->input->post('status'),
                'responsavel_tipo' => $this->input->post('responsavel_tipo'),
                'responsavel_id' => $this->input->post('responsavel_id'),
                'data_atualizacao' => date('Y-m-d H:i:s')
            ];

            if ($id = $this->ativos_model->add('ativos_bolsas', $data)) {
                // Passa null como ativo_id e o ID da bolsa como 4º parâmetro
                $this->ativos_model->log_acao(null, 'criacao_bolsa', 'Bolsa cadastrada no sistema', $id);
                $this->session->set_flashdata('success', 'Bolsa adicionada com sucesso!');
                redirect(site_url('ativos/gerenciar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'ativos/adicionarBolsa';
        return $this->layout();
    }

    public function editarBolsa() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar bolsas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('bolsas') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            // Verifica se já existe QR Code para não sobrescrever
            $bolsaAtual = $this->ativos_model->getByIdBolsa($this->input->post('idBolsa'));
            $codigoQr = isset($bolsaAtual->codigo_qr) && !empty($bolsaAtual->codigo_qr) 
                        ? $bolsaAtual->codigo_qr 
                        : md5(uniqid(rand(), true));

            $data = [
                'nome' => $this->input->post('nome'),
                'codigo_identificador' => $this->input->post('codigo_identificador'),
                'codigo_qr' => $codigoQr,
                'status' => $this->input->post('status'),
                'responsavel_tipo' => $this->input->post('responsavel_tipo'),
                'responsavel_id' => $this->input->post('responsavel_id'),
                'data_atualizacao' => date('Y-m-d H:i:s')
            ];

            if ($this->ativos_model->edit('ativos_bolsas', $data, 'idBolsa', $this->input->post('idBolsa')) == true) {
                $this->ativos_model->log_acao(null, 'edicao_bolsa', 'Bolsa atualizada no sistema', $this->input->post('idBolsa'));
                $this->session->set_flashdata('success', 'Bolsa editada com sucesso!');
                redirect(site_url('ativos/editarBolsa/') . $this->input->post('idBolsa'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['result'] = $this->ativos_model->getByIdBolsa($this->uri->segment(3));
        $this->data['itens'] = $this->ativos_model->getItensBolsa($this->uri->segment(3));
        $this->data['view'] = 'ativos/editarBolsa';
        return $this->layout();
    }

    public function editar() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar ativos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('ativos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
                'patrimonio' => $this->input->post('patrimonio'),
                'status' => $this->input->post('status'),
                'categoria_id' => $this->input->post('categoria_id'),
            ];

            if (!empty($_FILES['userfile']['name'])) {
                $upload = $this->do_upload();
                if (isset($upload['upload_data'])) {
                    $data['foto'] = $upload['upload_data']['file_name'];
                } else {
                    $this->session->set_flashdata('error', $upload['error']);
                    redirect(site_url('ativos/editar/') . $this->input->post('idAtivo'));
                }
            }

            if ($this->ativos_model->edit('ativos', $data, 'idAtivo', $this->input->post('idAtivo')) == true) {
                $this->ativos_model->log_acao($this->input->post('idAtivo'), 'edicao', 'Dados do ativo atualizados');
                $this->session->set_flashdata('success', 'Ativo editado com sucesso!');
                redirect(site_url('ativos/editar/') . $this->input->post('idAtivo'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['result'] = $this->ativos_model->getById($this->uri->segment(3));
        $this->data['categorias'] = $this->ativos_model->get('ativos_categorias', '*');
        $this->data['view'] = 'ativos/editarAtivo';
        return $this->layout();
    }
    
    public function excluir() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir ativos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir ativo.');
            redirect(site_url('ativos/gerenciar/'));
        }

        if ($this->ativos_model->delete('ativos', 'idAtivo', $id)) {
            $this->ativos_model->log_acao($id, 'exclusao', 'Ativo excluído do sistema');
            $this->session->set_flashdata('success', 'Ativo excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir ativo.');
        }

        redirect(site_url('ativos/gerenciar/'));
    }

    public function consultar_ativo() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $termo = $this->input->post('termo');
        
        // Busca por ID, Patrimonio ou QR Code
        $this->db->where('idAtivo', $termo);
        $this->db->or_where('patrimonio', $termo);
        $this->db->or_where('codigo_qr', $termo);
        $ativo = $this->db->get('ativos')->row();

        if ($ativo) {
            // Busca histórico de logs para este ativo
            // Assumindo que a tabela de logs é 'ativos_logs' conforme visto em Ativos_api.php
            // ou usando o model se disponível, mas aqui faremos direto para garantir os campos
            $this->db->select('al.*, u.nome as usuario_nome');
            $this->db->from('ativos_logs al');
            $this->db->join('usuarios u', 'u.idUsuarios = al.usuario_id_acao', 'left');
            $this->db->where('al.ativo_id', $ativo->idAtivo);
            $this->db->order_by('al.idLog', 'DESC');
            $this->db->limit(5);
            $historico = $this->db->get()->result();

            echo json_encode(['found' => true, 'ativo' => $ativo, 'historico' => $historico]);
        } else {
            echo json_encode(['found' => false]);
        }
    }

    private function do_upload() {
        $config['upload_path'] = './assets/uploads/ativos/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            return array('error' => $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            
            // Redimensionamento automático (GD Library)
            $config_resize['image_library'] = 'gd2';
            $config_resize['source_image'] = $data['full_path'];
            $config_resize['create_thumb'] = FALSE;
            $config_resize['maintain_ratio'] = TRUE;
            $config_resize['width'] = 800;
            $config_resize['height'] = 600;
            $config_resize['quality'] = '80%';

            $this->load->library('image_lib', $config_resize);
            $this->image_lib->resize();
            $this->image_lib->clear();

            return array('upload_data' => $data);
        }
    }

    public function autoCompleteResponsavel()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $tipo = strtolower($_GET['tipo']);

            if ($tipo == 'cliente') {
                $this->db->select('idClientes as id, nomeCliente as label');
                $this->db->like('nomeCliente', $q);
                $this->db->limit(10);
                $query = $this->db->get('clientes');
            } else {
                $this->db->select('idUsuarios as id, nome as label');
                $this->db->like('nome', $q);
                $this->db->where('situacao', 1);
                $this->db->limit(10);
                $query = $this->db->get('usuarios');
            }

            $result = [];
            foreach ($query->result() as $row) {
                $result[] = ['id' => $row->id, 'label' => $row->label];
            }
            echo json_encode($result);
        }
    }

    public function dados_dashboard() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAtivo')) {
            echo json_encode(['error' => 'Sem permissão']);
            return;
        }
        
        $status = $this->ativos_model->getEstatisticasStatus();
        $responsaveis = $this->ativos_model->getEstatisticasResponsavel();
        
        echo json_encode([
            'status' => $status,
            'responsaveis' => $responsaveis
        ]);
    }

    public function visualizar_bolsa_ajax() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['result' => false, 'message' => 'ID não fornecido']);
            return;
        }

        $itens = $this->ativos_model->getItensBolsa($id);
        $bolsa = $this->ativos_model->getByIdBolsa($id);
        
        echo json_encode(['result' => true, 'itens' => $itens, 'bolsa' => $bolsa]);
    }

    public function autoCompleteAtivo()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->db->select('a.idAtivo, a.nome, a.patrimonio, a.status, a.foto, ab.nome as nome_bolsa, c.nomeCliente, u.nome as nome_usuario');
            $this->db->from('ativos a');
            $this->db->join('ativos_itens_bolsa aib', 'aib.ativo_id = a.idAtivo', 'left');
            $this->db->join('ativos_bolsas ab', 'ab.idBolsa = aib.bolsa_id', 'left');
            $this->db->join('clientes c', 'ab.responsavel_tipo = "cliente" AND ab.responsavel_id = c.idClientes', 'left');
            $this->db->join('usuarios u', 'ab.responsavel_tipo = "usuario" AND ab.responsavel_id = u.idUsuarios', 'left');

            $this->db->group_start();
            $this->db->like('a.nome', $q);
            $this->db->or_like('a.patrimonio', $q);
            $this->db->or_like('a.codigo_qr', $q);
            $this->db->group_end();
            $this->db->limit(10);
            $query = $this->db->get();
            $result = [];
            foreach ($query->result() as $row) {
                $responsavel = $row->nomeCliente ? $row->nomeCliente : ($row->nome_usuario ? $row->nome_usuario : null);

                $result[] = [
                    'id' => $row->idAtivo,
                    'label' => $row->nome . ' | Pat: ' . $row->patrimonio,
                    'value' => $row->patrimonio,
                    'nome' => $row->nome,
                    'patrimonio' => $row->patrimonio,
                    'status' => $row->status,
                    'foto' => $row->foto,
                    'bolsa' => $row->nome_bolsa,
                    'responsavel' => $responsavel
                ];
            }
            echo json_encode($result);
        }
    }

    public function adicionarItemBolsa()
    {
        $bolsa_id = $this->input->post('bolsa_id');
        $ativo_id = $this->input->post('ativo_id');
        $id_item = $this->ativos_model->add('ativos_itens_bolsa', ['bolsa_id' => $bolsa_id, 'ativo_id' => $ativo_id]);
        if ($id_item) {
             $this->ativos_model->log_acao($ativo_id, 'movimentacao', 'Ativo adicionado à bolsa ID: ' . $bolsa_id, $bolsa_id);
             $ativo = $this->ativos_model->getById($ativo_id);
             echo json_encode(['result' => true, 'ativo' => $ativo, 'id_item_bolsa' => $id_item]);
        } else {
             echo json_encode(['result' => false, 'message' => 'Erro ao adicionar']);
        }
    }

    public function removerItemBolsa()
    {
        $id = $this->input->post('id');
        $ativo_id = $this->input->post('ativo_id');
        $bolsa_id = $this->input->post('bolsa_id');
        if ($this->ativos_model->delete('ativos_itens_bolsa', 'id', $id)) {
            $this->ativos_model->log_acao($ativo_id, 'movimentacao', 'Ativo removido da bolsa ID: ' . $bolsa_id, $bolsa_id);
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false, 'message' => 'Erro ao remover']);
        }
    }

    public function movimentacao() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para movimentar ativos.');
            redirect(base_url());
        }
        $this->data['view'] = 'ativos/movimentacao';
        return $this->layout();
    }

    public function buscar_movimentacao() {
        $termo = $this->input->post('termo');
        if (!$termo) {
            echo json_encode([]);
            return;
        }
        $resultados = $this->ativos_model->buscarAtivoOuBolsa($termo);
        echo json_encode($resultados);
    }

    public function processar_movimentacao() {
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $id = $this->input->post('id');
        $tipo = $this->input->post('tipo'); // ativo ou bolsa
        $acao = $this->input->post('acao'); // checkin ou checkout
        
        $dados_adicionais = [
            'responsavel_tipo' => $this->input->post('responsavel_tipo'),
            'responsavel_id' => $this->input->post('responsavel_id')
        ];
        $obs = $this->input->post('observacoes');

        if ($this->ativos_model->fazer_checkin_checkout($id, $tipo, $acao, $dados_adicionais)) {
            $detalhes = ucfirst($acao) . " realizado. " . ($obs ? "Obs: $obs" : "");
            $this->ativos_model->log_acao(($tipo == 'ativo' ? $id : null), $acao, $detalhes, ($tipo == 'bolsa' ? $id : null));
            echo json_encode(['result' => true, 'message' => 'Movimentação realizada com sucesso!']);
        } else {
            echo json_encode(['result' => false, 'message' => 'Erro ao realizar movimentação.']);
        }
    }

    public function removerItensBolsaMassa()
    {
        $ids = $this->input->post('ids');
        $bolsa_id = $this->input->post('bolsa_id');
        if (!empty($ids)) {
            $this->db->where_in('id', $ids);
            $this->db->delete('ativos_itens_bolsa');
            $this->ativos_model->log_acao(null, 'movimentacao', 'Remoção em massa de itens da bolsa ID: ' . $bolsa_id, $bolsa_id);
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false]);
        }
    }

    public function get_itens_bolsa_json() {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode([]);
            return;
        }
        $itens = $this->ativos_model->getItensBolsa($id);
        echo json_encode($itens);
    }

    public function imprimirEtiqueta($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar ativos.');
            redirect(base_url());
        }

        $this->data['result'] = $this->ativos_model->getById($id);
        $this->load->view('ativos/imprimirEtiqueta', $this->data);
    }

    public function imprimirEtiquetaBolsa($id)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar bolsas.');
            redirect(base_url());
        }

        $this->data['result'] = $this->ativos_model->getByIdBolsa($id);
        $this->load->view('ativos/imprimirEtiquetaBolsa', $this->data);
    }

    public function auditoria() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar auditoria de ativos.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $dataInicial = $this->input->get('data_inicial');
        $dataFinal = $this->input->get('data_final');
        $usuario = $this->input->get('usuario');
        $termo = $this->input->get('termo');

        $where = [
            'data_inicial' => $dataInicial,
            'data_final' => $dataFinal,
            'usuario' => $usuario,
            'termo' => $termo
        ];

        $queryString = http_build_query(array_filter($where));

        $this->data['configuration']['base_url'] = site_url('ativos/auditoria');
        $this->data['configuration']['total_rows'] = $this->ativos_model->countLogs($where);
        $this->data['configuration']['suffix'] = '?' . $queryString;
        $this->data['configuration']['first_url'] = site_url('ativos/auditoria') . '?' . $queryString;

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->ativos_model->getLogs($this->data['configuration']['per_page'], $this->uri->segment(3), $where);

        $this->data['view'] = 'ativos/auditoria';
        return $this->layout();
    }

    public function excluirLog() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAuditoriaAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir logs.');
            redirect(site_url('ativos/auditoria'));
        }
        
        $id = $this->input->post('id');
        if ($this->ativos_model->deleteLog($id)) {
            $this->session->set_flashdata('success', 'Log excluído com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir log.');
        }
        redirect(site_url('ativos/auditoria'));
    }

    public function excluirTodosLogs() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAuditoriaAtivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir logs.');
            redirect(site_url('ativos/auditoria'));
        }

        if ($this->ativos_model->deleteAllLogs()) {
            $this->session->set_flashdata('success', 'Todos os logs foram excluídos com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir logs.');
        }
        redirect(site_url('ativos/auditoria'));
    }

    public function categorias() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAtivoCategoria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar categorias de ativos.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('ativos/categorias/');
        $this->data['configuration']['total_rows'] = $this->ativos_model->count('ativos_categorias');

        $this->pagination->initialize($this->data['configuration']);

        $this->data['results'] = $this->ativos_model->get('ativos_categorias', '*', '', isset($this->data['configuration']['per_page']) ? $this->data['configuration']['per_page'] : 10, $this->uri->segment(3));

        $this->data['view'] = 'ativos/categorias';
        return $this->layout();
    }

    public function adicionarCategoria() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aAtivoCategoria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar categorias de ativos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
            ];

            if ($this->ativos_model->add('ativos_categorias', $data)) {
                $this->session->set_flashdata('success', 'Categoria adicionada com sucesso!');
                redirect(site_url('ativos/categorias/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'ativos/adicionarCategoria';
        return $this->layout();
    }

    public function editarCategoria() {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivoCategoria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar categorias de ativos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('nome', 'Nome', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nome' => $this->input->post('nome'),
            ];

            if ($this->ativos_model->edit('ativos_categorias', $data, 'idAtivoCategoria', $this->input->post('idAtivoCategoria')) == true) {
                $this->session->set_flashdata('success', 'Categoria editada com sucesso!');
                redirect(site_url('ativos/editarCategoria/') . $this->input->post('idAtivoCategoria'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['result'] = $this->ativos_model->getByIdCategoria($this->uri->segment(3));
        $this->data['view'] = 'ativos/editarCategoria';
        return $this->layout();
    }

    public function excluirCategoria() {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAtivoCategoria')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir categorias de ativos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir categoria.');
            redirect(site_url('ativos/categorias/'));
        }

        if ($this->ativos_model->delete('ativos_categorias', 'idAtivoCategoria', $id)) {
            $this->session->set_flashdata('success', 'Categoria excluída com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir categoria. Verifique se não há ativos vinculados.');
        }

        redirect(site_url('ativos/categorias/'));
    }
}