<?php
class Ativos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {
        $this->db->select($fields);
        $this->db->from($table);
        
        if ($table == 'ativos') {
            $this->db->select('ativos_categorias.nome as categoria');
            $this->db->join('ativos_categorias', 'ativos_categorias.idAtivoCategoria = ativos.categoria_id', 'left');
            $this->db->order_by('idAtivo', 'desc');
        } elseif ($table == 'ativos_categorias') {
            $this->db->order_by('idAtivoCategoria', 'desc');
        } elseif ($table == 'ativos_bolsas') {
            $this->db->order_by('idBolsa', 'desc');
        } elseif ($table == 'ativos_logs') {
            $this->db->order_by('idLog', 'desc');
        } else {
            $this->db->order_by('idAtivo', 'desc');
        }

        if ($perpage > 0) {
            $this->db->limit($perpage, $start);
        }
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        if (!$query) {
            return $one ? false : [];
        }
        
        $result =  !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id) {
        $this->db->where('idAtivo', $id);
        $this->db->limit(1);
        return $this->db->get('ativos')->row();
    }

    public function getByIdCategoria($id) {
        $this->db->where('idAtivoCategoria', $id);
        $this->db->limit(1);
        return $this->db->get('ativos_categorias')->row();
    }

    public function getByIdBolsa($id) {
        $this->db->where('idBolsa', $id);
        $this->db->limit(1);
        $bolsa = $this->db->get('ativos_bolsas')->row();

        if ($bolsa) {
            if ($bolsa->responsavel_tipo == 'cliente' && $bolsa->responsavel_id) {
                $this->db->select('nomeCliente as nome_responsavel');
                $bolsa->nome_responsavel = $this->db->get_where('clientes', ['idClientes' => $bolsa->responsavel_id])->row()->nome_responsavel ?? '';
            } elseif ($bolsa->responsavel_tipo == 'usuario' && $bolsa->responsavel_id) {
                $this->db->select('nome as nome_responsavel');
                $bolsa->nome_responsavel = $this->db->get_where('usuarios', ['idUsuarios' => $bolsa->responsavel_id])->row()->nome_responsavel ?? '';
            }
        }

        return $bolsa;
    }

    public function add($table, $data) {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return $this->db->insert_id();
        }
        return false;
    }

    public function edit($table, $data, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() >= 0) {
            return true;
        }
        return false;
    }

    public function delete($table, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        return false;
    }

    public function count($table) {
        return $this->db->count_all($table);
    }
    
    public function log_acao($ativo_id, $acao, $detalhes, $bolsa_id = null) {
        $data = [
            'ativo_id' => $ativo_id,
            'bolsa_id' => $bolsa_id,
            'usuario_id_acao' => $this->session->userdata('id_admin'),
            'acao' => $acao,
            'detalhes' => $detalhes,
            'data_acao' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('ativos_logs', $data);
    }

    public function getEstatisticasStatus() {
        $this->db->select('status, COUNT(*) as total');
        $this->db->from('ativos');
        $this->db->group_by('status');
        return $this->db->get()->result();
    }

    public function getEstatisticasResponsavel() {
        // Une a contagem de ativos em posse de Clientes e Usuários (via Bolsas)
        $sql = "SELECT c.nomeCliente as nome, COUNT(aib.ativo_id) as total 
                FROM ativos_bolsas ab 
                JOIN ativos_itens_bolsa aib ON ab.idBolsa = aib.bolsa_id 
                JOIN clientes c ON ab.responsavel_id = c.idClientes 
                WHERE ab.responsavel_tipo = 'cliente' 
                GROUP BY ab.responsavel_id
                UNION ALL
                SELECT u.nome as nome, COUNT(aib.ativo_id) as total 
                FROM ativos_bolsas ab 
                JOIN ativos_itens_bolsa aib ON ab.idBolsa = aib.bolsa_id 
                JOIN usuarios u ON ab.responsavel_id = u.idUsuarios 
                WHERE ab.responsavel_tipo = 'usuario' 
                GROUP BY ab.responsavel_id";
        
        return $this->db->query($sql)->result();
    }

    public function getBolsas($perpage = 0, $start = 0) {
        $this->db->select('ab.*, (SELECT COUNT(*) FROM ativos_itens_bolsa aib WHERE aib.bolsa_id = ab.idBolsa) as qtd_itens');
        $this->db->from('ativos_bolsas ab');
        $this->db->order_by('ab.idBolsa', 'desc');
        if ($perpage > 0) {
            $this->db->limit($perpage, $start);
        }
        return $this->db->get()->result();
    }

    public function getItensBolsa($idBolsa) {
        $this->db->select('a.*, aib.id as id_item_bolsa');
        $this->db->from('ativos a');
        $this->db->join('ativos_itens_bolsa aib', 'aib.ativo_id = a.idAtivo');
        $this->db->where('aib.bolsa_id', $idBolsa);
        return $this->db->get()->result();
    }

    public function buscarAtivoOuBolsa($termo) {
        // Busca Ativos
        $this->db->select('idAtivo as id, nome, patrimonio as codigo, codigo_qr, status, "ativo" as tipo, foto');
        $this->db->from('ativos');
        $this->db->group_start();
        $this->db->where('patrimonio', $termo);
        $this->db->or_where('codigo_qr', $termo);
        $this->db->or_like('nome', $termo);
        $this->db->group_end();
        $queryAtivos = $this->db->get()->result();
        
        // Busca Bolsas
        $this->db->select('idBolsa as id, nome, codigo_identificador as codigo, codigo_qr, status, "bolsa" as tipo, "" as foto, responsavel_tipo, responsavel_id');
        $this->db->from('ativos_bolsas');
        $this->db->group_start();
        $this->db->where('codigo_identificador', $termo);
        $this->db->or_where('codigo_qr', $termo);
        $this->db->or_like('nome', $termo);
        $this->db->group_end();
        $queryBolsas = $this->db->get()->result();

        // Enriquecer Bolsas com nome do responsável
        foreach($queryBolsas as $b) {
            $b->nome_responsavel = 'N/A';
            if($b->responsavel_tipo == 'cliente' && $b->responsavel_id) {
                $c = $this->db->select('nomeCliente')->where('idClientes', $b->responsavel_id)->get('clientes')->row();
                $b->nome_responsavel = $c ? $c->nomeCliente : 'Cliente não encontrado';
            } elseif($b->responsavel_tipo == 'usuario' && $b->responsavel_id) {
                $u = $this->db->select('nome')->where('idUsuarios', $b->responsavel_id)->get('usuarios')->row();
                $b->nome_responsavel = $u ? $u->nome : 'Usuário não encontrado';
            }
        }

        return array_merge($queryAtivos, $queryBolsas);
    }

    public function fazer_checkin_checkout($id, $tipo, $acao, $dados_adicionais = []) {
        $tabela = ($tipo == 'ativo') ? 'ativos' : 'ativos_bolsas';
        $campo_id = ($tipo == 'ativo') ? 'idAtivo' : 'idBolsa';
        
        $data = [];
        if ($acao == 'checkout') {
            $data['status'] = ($tipo == 'ativo') ? 'em_uso' : 'viagem';
            if ($tipo == 'bolsa') {
                $data['responsavel_tipo'] = $dados_adicionais['responsavel_tipo'];
                $data['responsavel_id'] = $dados_adicionais['responsavel_id'];
            }
        } else { // checkin
            $data['status'] = ($tipo == 'ativo') ? 'disponivel' : 'estoque';
            if ($tipo == 'bolsa') {
                $data['responsavel_tipo'] = null;
                $data['responsavel_id'] = null;
            }
        }

        return $this->edit($tabela, $data, $campo_id, $id);
    }

    public function getPendentes() {
        // Ativos em uso
        $this->db->select('a.idAtivo as id, a.nome, a.patrimonio as codigo, a.status, "ativo" as tipo, 
                           (SELECT detalhes FROM ativos_logs WHERE ativo_id = a.idAtivo AND acao = "checkout" ORDER BY idLog DESC LIMIT 1) as observacao,
                           (SELECT data_acao FROM ativos_logs WHERE ativo_id = a.idAtivo AND acao = "checkout" ORDER BY idLog DESC LIMIT 1) as data_acao');
        $this->db->from('ativos a');
        $this->db->where('a.status', 'em_uso');
        $ativos = $this->db->get()->result();

        // Bolsas em viagem
        $this->db->select('b.idBolsa as id, b.nome, b.codigo_identificador as codigo, b.status, "bolsa" as tipo, 
                           (SELECT detalhes FROM ativos_logs WHERE bolsa_id = b.idBolsa AND acao = "checkout" ORDER BY idLog DESC LIMIT 1) as observacao,
                           (SELECT data_acao FROM ativos_logs WHERE bolsa_id = b.idBolsa AND acao = "checkout" ORDER BY idLog DESC LIMIT 1) as data_acao,
                           b.responsavel_tipo, b.responsavel_id');
        $this->db->from('ativos_bolsas b');
        $this->db->where('b.status', 'viagem');
        $bolsas = $this->db->get()->result();

        foreach($bolsas as $b) {
            $b->nome_responsavel = 'N/A';
            if($b->responsavel_tipo == 'cliente' && $b->responsavel_id) {
                $c = $this->db->select('nomeCliente')->where('idClientes', $b->responsavel_id)->get('clientes')->row();
                $b->nome_responsavel = $c ? $c->nomeCliente : 'Cliente não encontrado';
            } elseif($b->responsavel_tipo == 'usuario' && $b->responsavel_id) {
                $u = $this->db->select('nome')->where('idUsuarios', $b->responsavel_id)->get('usuarios')->row();
                $b->nome_responsavel = $u ? $u->nome : 'Usuário não encontrado';
            }
        }
        
        return array_merge($ativos, $bolsas);
    }

    public function getLogs($perpage = 0, $start = 0, $where = []) {
        $this->db->select('al.*, u.nome as usuario, a.nome as ativo_nome, ab.nome as bolsa_nome');
        $this->db->from('ativos_logs al');
        $this->db->join('usuarios u', 'u.idUsuarios = al.usuario_id_acao', 'left');
        $this->db->join('ativos a', 'a.idAtivo = al.ativo_id', 'left');
        $this->db->join('ativos_bolsas ab', 'ab.idBolsa = al.bolsa_id', 'left');
        
        if (!empty($where)) {
            if (!empty($where['data_inicial'])) {
                $this->db->where('DATE(al.data_acao) >=', $where['data_inicial']);
            }
            if (!empty($where['data_final'])) {
                $this->db->where('DATE(al.data_acao) <=', $where['data_final']);
            }
            if (!empty($where['usuario'])) {
                $this->db->like('u.nome', $where['usuario']);
            }
            if (!empty($where['termo'])) {
                $this->db->group_start();
                $this->db->like('al.acao', $where['termo']);
                $this->db->or_like('al.detalhes', $where['termo']);
                $this->db->or_like('a.nome', $where['termo']);
                $this->db->or_like('ab.nome', $where['termo']);
                $this->db->group_end();
            }
        }

        $this->db->order_by('al.data_acao', 'desc');
        if ($perpage > 0) {
            $this->db->limit($perpage, $start);
        }
        return $this->db->get()->result();
    }

    public function countLogs($where = []) {
        $this->db->from('ativos_logs al');
        $this->db->join('usuarios u', 'u.idUsuarios = al.usuario_id_acao', 'left');
        $this->db->join('ativos a', 'a.idAtivo = al.ativo_id', 'left');
        $this->db->join('ativos_bolsas ab', 'ab.idBolsa = al.bolsa_id', 'left');

        if (!empty($where)) {
             if (!empty($where['data_inicial'])) {
                $this->db->where('DATE(al.data_acao) >=', $where['data_inicial']);
            }
            if (!empty($where['data_final'])) {
                $this->db->where('DATE(al.data_acao) <=', $where['data_final']);
            }
            if (!empty($where['usuario'])) {
                $this->db->like('u.nome', $where['usuario']);
            }
            if (!empty($where['termo'])) {
                $this->db->group_start();
                $this->db->like('al.acao', $where['termo']);
                $this->db->or_like('al.detalhes', $where['termo']);
                $this->db->or_like('a.nome', $where['termo']);
                $this->db->or_like('ab.nome', $where['termo']);
                $this->db->group_end();
            }
        }
        return $this->db->count_all_results();
    }

    public function deleteLog($id) {
        $this->db->where('idLog', $id);
        return $this->db->delete('ativos_logs');
    }

    public function deleteAllLogs() {
        return $this->db->empty_table('ativos_logs');
    }
}