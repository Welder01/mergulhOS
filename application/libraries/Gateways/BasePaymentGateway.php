<?php

namespace Libraries\Gateways;

use Libraries\Gateways\Contracts\PaymentGateway;

abstract class BasePaymentGateway implements PaymentGateway
{
    public function gerarCobranca($id, $tipo, $metodoPagamento, $data = [])
    {
        switch ($metodoPagamento) {
            case PaymentGateway::PAYMENT_METHOD_BILLET:
                return $this->gerarCobrancaBoleto($id, $tipo, $data);
                break;
            case PaymentGateway::PAYMENT_METHOD_LINK:
                return $this->gerarCobrancaLink($id, $tipo, $data);
                break;
            default:
                throw new \Exception('Método de pagamento inválido!');
        }
    }

    public function findEntity($id, $tipo)
    {
        switch ($tipo) {
            case PaymentGateway::PAYMENT_TYPE_OS:
                return $this->ci->Os_model->getById($id);
                break;
            case PaymentGateway::PAYMENT_TYPE_VENDAS:
                return $this->ci->vendas_model->getById($id);
                break;
            default:
                throw new \Exception('Tipo de entidade a ser gerado a cobrança inválido!');
                break;
        }
    }

    public function errosCadastro($entity = null)
    {
        if ($entity == null) {
            return null;
        }
        $error_list = "Por favor preencher os seguintes dados do(a) seu(ua) cliente!\n\n";
        $check = false;
        $list = ['rua', 'numero', 'bairro', 'cep', 'cidade', 'estado', 'documento', 'nomeCliente', 'email'];
        foreach ($entity as $key => $value) {
            if (in_array($key, $list)) {
                if ((empty($value) || strlen($value) < 2) && ! is_numeric($value)) {
                    $error_list .= '-' . $key . "\n";
                    $check = true;
                }
            }
        }

        $telefone = isset($entity->telefone) ? $entity->telefone : '';
        $celular = isset($entity->celular) ? $entity->celular : '';

        if ((empty($telefone) || strlen($telefone) < 2) && (empty($celular) || strlen($celular) < 2)) {
            $error_list .= '-telefone ou celular' . "\n";
            $check = true;
        }

        if (! $check) {
            return null;
        }

        return $error_list;
    }

    public function enviarPorEmail($id)
    {
        throw new \Exception('Não implementado');
    }

    public function atualizarDados($id)
    {
        throw new \Exception('Não implementado');
    }

    public function cancelar($id)
    {
        throw new \Exception('Não implementado');
    }

    public function confirmarPagamento($id)
    {
        throw new \Exception('Não implementado');
    }

    abstract protected function gerarCobrancaBoleto($id, $tipo, $data = null);

    abstract protected function gerarCobrancaLink($id, $tipo, $data = null);
}
