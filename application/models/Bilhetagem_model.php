<?php
class Bilhetagem_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idBilhete', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        $result =  !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getBilhetes($perpage = 0, $start = 0) {
        $this->db->select('bilhetes.*, clientes.nomeCliente, expedicoes.titulo as expedicao, viagens.nome_viagem');
        $this->db->from('bilhetes');
        $this->db->join('clientes', 'clientes.idClientes = bilhetes.cliente_id');
        $this->db->join('expedicoes', 'expedicoes.idExpedicao = bilhetes.expedicao_id');
        $this->db->join('viagens', 'viagens.id = bilhetes.viagem_id', 'left');
        $this->db->order_by('bilhetes.idBilhete', 'desc');
        $this->db->limit($perpage, $start);
        $query = $this->db->get();
        return $query ? $query->result() : [];
    }

    public function getById($id) {
        $this->db->select('bilhetes.*, clientes.nomeCliente, clientes.documento, clientes.email, clientes.telefone, expedicoes.titulo as expedicao, expedicoes.data_ida, expedicoes.data_volta, viagens.nome_viagem');
        $this->db->from('bilhetes');
        $this->db->join('clientes', 'clientes.idClientes = bilhetes.cliente_id');
        $this->db->join('expedicoes', 'expedicoes.idExpedicao = bilhetes.expedicao_id');
        $this->db->join('viagens', 'viagens.id = bilhetes.viagem_id', 'left');
        $this->db->where('bilhetes.idBilhete', $id);
        return $this->db->get()->row();
    }

    public function getEquipamentosByBilhete($id) {
        $this->db->select('be.*, a.nome, a.patrimonio');
        $this->db->from('bilhetes_equipamentos be');
        $this->db->join('ativos a', 'a.idAtivo = be.ativo_id');
        $this->db->where('be.bilhete_id', $id);
        return $this->db->get()->result();
    }

    public function getExpedicoesAtivas() {
        $this->db->where('data_ida >=', date('Y-m-d'));
        return $this->db->get('expedicoes')->result();
    }
    
    public function getExpedicaoById($id) {
        $this->db->where('idExpedicao', $id);
        return $this->db->get('expedicoes')->row();
    }

    public function getCotacao($moeda) {
        $this->db->where('moeda', $moeda);
        $row = $this->db->get('config_cambio')->row();
        return $row ? $row->valor_brl : 1.00;
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
        return ($this->db->affected_rows() >= 0);
    }

    public function delete($table, $fieldID, $ID) {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        return ($this->db->affected_rows() == '1');
    }

    public function count($table) {
        if (!$this->db->table_exists($table)) {
            return 0;
        }
        return $this->db->count_all($table);
    }

    // Busca viagens onde o cliente está cadastrado (assumindo tabela de ligação ou direta)
    public function getViagensCliente($cliente_id) {
        // Ajuste conforme a estrutura real do seu módulo de viagens. 
        // Exemplo genérico assumindo que existe uma tabela viagens_clientes ou similar
        // Se não existir, precisará adaptar para sua lógica de "Clientes na Viagem"
        
        // Tentativa de busca genérica baseada em padrões comuns
        $this->db->select('v.id, v.nome_viagem, v.data_partida, v.data_retorno');
        $this->db->from('viagens v');
        // $this->db->join('viagens_clientes vc', 'vc.viagem_id = v.id'); // Descomentar se existir
        // $this->db->where('vc.cliente_id', $cliente_id); // Descomentar se existir
        $this->db->where('v.status !=', 'cancelada');
        $this->db->where('v.data_partida >=', date('Y-m-d'));
        return $this->db->get()->result();
    }

    public function verificarCancelamento($bilhete_id) {
        $bilhete = $this->getById($bilhete_id);
        if (!$bilhete) return false;

        $expedicao = $this->getExpedicaoById($bilhete->expedicao_id);
        if (!$expedicao) return true; // Se não tem expedição, permite (fallback)

        $dataPartida = strtotime($expedicao->data_ida);
        $agora = time();
        $diferencaHoras = ($dataPartida - $agora) / 3600;
        $limiteHoras = ($expedicao->cancelamento_tipo == 'dias') ? $expedicao->cancelamento_limite * 24 : $expedicao->cancelamento_limite;

        return $diferencaHoras >= $limiteHoras;
    }

    public function getAssentosOcupados($expedicao_id) {
        $this->db->select('assento');
        $this->db->from('bilhetes');
        $this->db->where('expedicao_id', $expedicao_id);
        $this->db->where('status !=', 'cancelado');
        $query = $this->db->get();
        
        $ocupados = [];
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $ocupados[] = $row->assento;
            }
        }
        return $ocupados;
    }
}
