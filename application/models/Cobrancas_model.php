<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cobrancas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage, $start);
        $this->db->order_by('idCobranca', 'desc');
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();
        $result = ! $one ? $query->result() : $query->row();

        return $result;
    }

    public function get_by_lancamento($lancamentoId)
    {
        $this->db->from('cobrancas');
        $this->db->group_start();
        $this->db->like('external_reference', '-L' . $lancamentoId, 'before');
        $this->db->or_like('external_reference', '-L' . $lancamentoId, 'after');
        $this->db->or_like('external_reference', '-L' . $lancamentoId, 'both');
        $this->db->group_end();
        $this->db->where('status !=', 'cancelled');
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row();
    }

    public function getById($id)
    {
        $this->db->select('cobrancas.*, clientes.*');
        $this->db->from('cobrancas');
        $this->db->where('cobrancas.idCobranca', $id);
        $this->db->join('clientes', 'clientes.idClientes = cobrancas.clientes_id');
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function getByOs($id)
    {
        return $this->db->query("SELECT DISTINCT `cobrancas`.*,`clientes`.*,`os`.* FROM `cobrancas`,`clientes`,`os` WHERE `charge_id` = ? AND `os`.`idOs` = `cobrancas`.`os_id`", [$id])->row();
    }

    public function getByVendas($id)
    {
        return $this->db->query("SELECT DISTINCT `cobrancas`.*,`clientes`.*,`vendas`.* FROM `cobrancas`,`clientes`,`vendas` WHERE `charge_id` = ? AND `vendas`.`idVendas` = `cobrancas`.`vendas_id`", [$id])->row();
    }

    public function add($table, $data, $returnId = false)
    {
        // Log forçado dos dados antes da inserção para identificar arrays aninhados
        log_message('error', "Cobrancas_model::add - Tentativa de inserção em {$table}. Dados: " . print_r($data, true));

        // Sanitização: Converte arrays/objetos para JSON para evitar erro "Array to string conversion"
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                log_message('error', "Cobrancas_model::add - Campo '{$key}' é array/objeto. Convertendo para string.");
                $data[$key] = json_encode($value);
            }
        }

        // Correção para datas no formato ISO 8601 (ex: MercadoPago) que causam erro no MySQL
        if (isset($data['expire_at']) && is_string($data['expire_at']) && strpos($data['expire_at'], 'T') !== false) {
            try {
                $date = new DateTime($data['expire_at']);
                $data['expire_at'] = $date->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                log_message('error', 'Cobrancas_model::add - Erro ao formatar data: ' . $e->getMessage());
                $timestamp = strtotime($data['expire_at']);
                if ($timestamp) {
                    $data['expire_at'] = date('Y-m-d H:i:s', $timestamp);
                }
            }
        }

        $db_debug = $this->db->db_debug;
        $this->db->db_debug = false;
        $result = $this->db->insert($table, $data);
        $error = $this->db->error();
        $this->db->db_debug = $db_debug;

        if ($result && $this->db->affected_rows() == '1') {
            log_message('info', "Cobrancas_model::add - Sucesso ao adicionar em {$table}. ID: " . $this->db->insert_id($table));
            if ($returnId == true) {
                return $this->db->insert_id($table);
            }

            return true;
        }

        log_message('error', "Cobrancas_model::add - Erro ao adicionar em {$table}. Erro DB: " . json_encode($error) . " | Dados: " . json_encode($data));
        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }

        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function atualizarStatus($idCobranca)
    {
        $cobranca = $this->getById($idCobranca);
        if (empty($cobranca)) {
            return $this->session->set_flashdata('error', 'Cobrança não existe!');
        }

        $gatewayDePagamento = $cobranca->payment_gateway;
        $this->load->library("Gateways/$gatewayDePagamento", null, 'PaymentGateway');

        $result = $this->PaymentGateway->atualizarDados($cobranca->idCobranca);

        return $result;
    }

    public function confirmarPagamento($idCobranca)
    {
        $cobranca = $this->getById($idCobranca);
        if (empty($cobranca)) {
            return $this->session->set_flashdata('error', 'Cobrança não existe!');
        }

        $gatewayDePagamento = $cobranca->payment_gateway;
        $this->load->library("Gateways/$gatewayDePagamento", null, 'PaymentGateway');

        $result = $this->PaymentGateway->confirmarPagamento($cobranca->idCobranca);

        return $result;
    }

    public function cancelarPagamento($idCobranca)
    {
        $cobranca = $this->getById($idCobranca);
        if (empty($cobranca)) {
            return $this->session->set_flashdata('error', 'Cobrança não existe!');
        }

        $gatewayDePagamento = $cobranca->payment_gateway;
        $this->load->library("Gateways/$gatewayDePagamento", null, 'PaymentGateway');

        $result = $this->PaymentGateway->cancelar($cobranca->idCobranca);

        return $result;
    }

    public function enviarEmail($idCobranca)
    {
        $cobranca = $this->getById($idCobranca);
        if (empty($cobranca)) {
            return $this->session->set_flashdata('error', 'Cobrança não existe!');
        }

        $gatewayDePagamento = $cobranca->payment_gateway;
        $this->load->library("Gateways/$gatewayDePagamento", null, 'PaymentGateway');

        $result = $this->PaymentGateway->enviarPorEmail($cobranca->idCobranca);

        return $result;
    }
}
