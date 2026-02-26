<?php

use CodePhix\Asaas\Asaas as AsaasSdk;
use Libraries\Gateways\BasePaymentGateway;
use Libraries\Gateways\Contracts\PaymentGateway;

class Asaas extends BasePaymentGateway
{
    /** @var AsaasSdk */
    private $asaasApi;

    private $asaasConfig;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->config('payment_gateways');
        $this->ci->load->model('Os_model');
        $this->ci->load->model('vendas_model');
        $this->ci->load->model('cobrancas_model');
        $this->ci->load->model('mapos_model');
        $this->ci->load->model('email_model');
        $this->ci->load->model('clientes_model');

        $asaasConfig = $this->ci->config->item('payment_gateways')['Asaas'];
        $this->asaasConfig = $asaasConfig;
        $this->asaasApi = new AsaasSdk(
            $asaasConfig['credentials']['api_key'],
            $asaasConfig['production'] === true ? 'producao' : 'homologacao'
        );
    }

    public function cancelar($id)
    {
        $cobranca = $this->ci->cobrancas_model->getById($id);
        if (! $cobranca) {
            throw new \Exception('Cobrança não existe!');
        }

        if ($cobranca->payment_method == 'boleto') {
            $this->asaasApi->Cobranca()->delete($cobranca->charge_id);
        } else {
            $this->asaasApi->LinkPagamento()->delete($cobranca->charge_id);
        }

        return $this->atualizarDados($id);
    }

    public function enviarPorEmail($id)
    {
        $cobranca = $this->ci->cobrancas_model->getById($id);
        if (! $cobranca) {
            throw new \Exception('Cobrança não existe!');
        }

        $emitente = $this->ci->mapos_model->getEmitente();
        if (! $emitente) {
            throw new \Exception('Emitente não configurado!');
        }

        $html = $this->ci->load->view(
            'cobrancas/emails/cobranca',
            [
                'cobranca' => $cobranca,
                'emitente' => $emitente,
                'paymentGatewaysConfig' => $this->ci->config->item('payment_gateways'),
            ],
            true
        );

        $assunto = 'Cobrança - ' . $emitente->nome;
        if ($cobranca->os_id) {
            $assunto .= ' - OS #' . $cobranca->os_id;
        } else {
            $assunto .= ' - Venda #' . $cobranca->vendas_id;
        }

        $emailRemetente = !empty($_ENV['EMAIL_SMTP_USER']) ? $_ENV['EMAIL_SMTP_USER'] : $emitente->email;

        $remetentes = [$cobranca->email];
        foreach ($remetentes as $remetente) {
            $headers = [
                'From' => $emailRemetente,
                'Subject' => $assunto,
                'Return-Path' => '',
                'Reply-To' => $emitente->email,
            ];
            $email = [
                'to' => $remetente,
                'message' => $html,
                'status' => 'pending',
                'date' => date('Y-m-d H:i:s'),
                'headers' => serialize($headers),
            ];
            $this->ci->email_model->add('email_queue', $email);
        }
    }

    public function atualizarDados($id)
    {
        $cobranca = $this->ci->cobrancas_model->getById($id);
        if (! $cobranca) {
            throw new \Exception('Cobrança não existe!');
        }

        if ($cobranca->payment_method == 'boleto') {
            $result = $this->asaasApi->Cobranca()->getById($cobranca->charge_id);
        } else {
            throw new Exception('Devido à limitação da Asaas, somente é possível atualizar cobranças com boletos!');
        }

        // Verifica se a cobrança já foi deletada
        if ($result->deleted) {
            // Atribui o valor DELETED ao status para atualizar o status no banco de dados
            $result->status = 'DELETED';
        }
        
        // Cobrança foi paga ou foi confirmada de forma manual, então damos baixa
        if ($result->status == 'RECEIVED' || $result->status == 'CONFIRMED' || $result->status == 'DUNNING_RECEIVED') {
            // TODO: dar baixa no lançamento caso exista
        }

        $databaseResult = $this->ci->cobrancas_model->edit(
            'cobrancas',
            [
                'status' => $result->status,
            ],
            'idCobranca',
            $id
        );

        if ($databaseResult == true) {
            $this->ci->session->set_flashdata('success', 'Cobrança atualizada com sucesso!');
            log_info('Alterou um status de cobrança. ID' . $id);
        } else {
            $this->ci->session->set_flashdata('error', 'Erro ao atualizar cobrança!');
            throw new \Exception('Erro ao atualizar cobrança!');
        }
    }

    public function confirmarPagamento($id)
    {
        $cobranca = $this->ci->cobrancas_model->getById($id);
        if (! $cobranca) {
            throw new \Exception('Cobrança não existe!');
        }

        if ($cobranca->payment_method == 'boleto') {
            $result = $this->asaasApi->Cobranca()->confirmacao(
                $cobranca->charge_id,
                [
                    'paymentDate' => (new DateTime())->format('Y-m-d'),
                    'value' => round($cobranca->total / 100, 2),
                ]
            );
            if ($result && ! empty($result->errors)) {
                // A resposta da API inclui erros
                foreach ($result->errors as $error) {
                    throw new \Exception('Erro ao chamar Asaas.\n\n' . $error->description);
                }
            } elseif (! $result) {
                // A chamada para a API falhou de alguma forma
                throw new \Exception('Falha na chamada para a API Asaas');
            }
        } else {
            throw new Exception('Devido à limitação da Asaas, somente é possível confirmar cobranças com boletos!');
        }

        return $this->atualizarDados($id);
    }

    public function gerarCobrancaBoleto($id, $tipo, $dadosParcela = null)
    {
        $entity = $this->findEntity($id, $tipo);
        $produtos = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getProdutos($id)
            : $this->ci->vendas_model->getProdutos($id);
        $servicos = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getServicos($id)
            : [];
        $cursos = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getCursos($id)
            : [];
        $viagens = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getViagens($id)
            : [];

        $desconto = [$tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getById($id)
            : $this->ci->vendas_model->getById($id)];

        $tipo_desconto = [$tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getById($id)
            : $this->ci->vendas_model->getById($id)];

        $totalProdutos = array_reduce(
            $produtos,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $totalServicos = array_reduce(
            $servicos,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $totalCursos = array_reduce(
            $cursos,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $totalViagens = array_reduce(
            $viagens,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $tipoDesconto = array_reduce(
            $tipo_desconto,
            function ($total, $item) {
                return $item->tipo_desconto;
            },
            0
        );
        $totalDesconto = array_reduce(
            $desconto,
            function ($total, $item) {
                return $item->desconto;
            },
            0
        );

        if (empty($entity)) {
            throw new \Exception('OS ou venda não existe!');
        }

        if (($totalProdutos + $totalServicos + $totalCursos + $totalViagens) <= 0) {
            throw new \Exception('OS ou venda com valor negativo ou zero!');
        }

        if ($err = $this->errosCadastro($entity)) {
            throw new \Exception($err);
        }

        if ($dadosParcela) {
            $valor = $dadosParcela['valor'];
            $expirationDate = $dadosParcela['vencimento'];
            $description = $dadosParcela['descricao'];
        } else {
            $valor = $this->valorTotal($totalProdutos, $totalServicos, $totalDesconto, $tipoDesconto, $totalCursos, $totalViagens);
            $expirationDate = (new DateTime())->add(new DateInterval($this->asaasConfig['boleto_expiration']))->format('Y-m-d');
            $description = $tipo === PaymentGateway::PAYMENT_TYPE_OS ? "OS #$id" : "Venda #$id";
        }

        $body = [
            'customer' => $this->criarOuRetornarClienteAsaasId($entity->clientes_id),
            'billingType' => 'BOLETO',
            'dueDate' => $expirationDate,
            'value' => $valor,
            'description' => $description,
            'externalReference' => $id,
            'postalService' => false,
        ];

        $result = $this->asaasApi->Cobranca()->create($body);

        if ($result && ! empty($result->errors)) {
            // A resposta da API inclui erros
            foreach ($result->errors as $error) {
                throw new \Exception('Erro na criação da cobrança: ' . $error->description);
            }
        } elseif (! $result) {
            // A chamada para a API falhou de alguma forma
            throw new \Exception('Falha na chamada para a API Asaas');
        }

        $data = [
            'barcode' => '',
            'link' => $result->invoiceUrl,
            'payment_url' => $result->invoiceUrl,
            'pdf' => $result->bankSlipUrl,
            'expire_at' => $result->dueDate,
            'charge_id' => $result->id,
            'status' => $result->status,
            'total' => getMoneyAsCents($valor),
            'payment' => $result->billingType,
            'clientes_id' => $entity->idClientes,
            'payment_method' => 'boleto',
            'payment_gateway' => 'Asaas',
            'message' => 'Pagamento referente a ' . $description,
        ];

        if ($tipo === PaymentGateway::PAYMENT_TYPE_OS) {
            $data['os_id'] = $id;
        } else {
            $data['vendas_id'] = $id;
        }

        if ($id = $this->ci->cobrancas_model->add('cobrancas', $data, true)) {
            $data['idCobranca'] = $id;
            log_info('Cobrança criada com successo. ID: ' . $result->id);
        } else {
            throw new \Exception('Erro ao salvar cobrança!');
        }

        return $data;
    }

    public function gerarCobrancaLink($id, $tipo, $dadosParcela = null)
    {
        $entity = $this->findEntity($id, $tipo);
        $produtos = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getProdutos($id)
            : $this->ci->vendas_model->getProdutos($id);
        $servicos = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getServicos($id)
            : [];
        $cursos = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getCursos($id)
            : [];
        $viagens = $tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getViagens($id)
            : [];
        $tipo_desconto = [$tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getById($id)
            : $this->ci->vendas_model->getById($id)];

        $desconto = [$tipo === PaymentGateway::PAYMENT_TYPE_OS
            ? $this->ci->Os_model->getById($id)
            : $this->ci->vendas_model->getById($id)];

        $totalProdutos = array_reduce(
            $produtos,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $totalServicos = array_reduce(
            $servicos,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $totalCursos = array_reduce(
            $cursos,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $totalViagens = array_reduce(
            $viagens,
            function ($total, $item) {
                return $total + (floatval($item->preco) * intval($item->quantidade));
            },
            0
        );
        $tipoDesconto = array_reduce(
            $tipo_desconto,
            function ($total, $item) {
                return $item->tipo_desconto;
            },
            0
        );
        $totalDesconto = array_reduce(
            $desconto,
            function ($total, $item) {
                return $item->desconto;
            },
            0
        );

        if (empty($entity)) {
            throw new \Exception('OS ou venda não existe!');
        }

        if (($totalProdutos + $totalServicos + $totalCursos + $totalViagens) <= 0) {
            throw new \Exception('OS ou venda com valor negativo ou zero!');
        }

        if ($err = $this->errosCadastro($entity)) {
            throw new \Exception($err);
        }

        if ($dadosParcela) {
            $valor = $dadosParcela['valor'];
            $expirationDate = $dadosParcela['vencimento'];
            $description = $dadosParcela['descricao'];
        } else {
            $valor = $this->valorTotal($totalProdutos, $totalServicos, $totalDesconto, $tipoDesconto, $totalCursos, $totalViagens);
            $expirationDate = (new DateTime())->add(new DateInterval($this->asaasConfig['boleto_expiration']))->format('Y-m-d');
            $description = $tipo === PaymentGateway::PAYMENT_TYPE_OS ? "OS #$id" : "Venda #$id";
        }

        $body = [
            'name' => $description,
            'description' => $description,
            'endDate' => $expirationDate,
            'value' => $valor,
            'billingType' => 'UNDEFINED',
            'chargeType' => 'DETACHED',
            'dueDateLimitDays' => preg_replace('/[^0-9]/', '', $this->asaasConfig['boleto_expiration']),
            'subscriptionCycle' => null,
            'maxInstallmentCount' => 1,
        ];

        $result = $this->asaasApi->LinkPagamento()->create($body);
        if ($result && ! empty($result->errors)) {
            // A resposta da API inclui erros
            foreach ($result->errors as $error) {
                throw new \Exception('Erro na criação da cobrança: ' . $error->description);
            }
        } elseif (! $result) {
            // A chamada para a API falhou de alguma forma
            throw new \Exception('Falha na chamada para a API Asaas');
        }

        $data = [
            'expire_at' => $result->endDate,
            'charge_id' => $result->id,
            'status' => 'PENDING',
            'total' => getMoneyAsCents($valor),
            'clientes_id' => $entity->idClientes,
            'payment_method' => 'link',
            'payment_gateway' => 'Asaas',
            'payment_url' => $result->url,
            'link' => $result->url,
            'message' => 'Pagamento referente a ' . $description,
        ];

        if ($tipo === PaymentGateway::PAYMENT_TYPE_OS) {
            $data['os_id'] = $id;
        } else {
            $data['vendas_id'] = $id;
        }

        if ($id = $this->ci->cobrancas_model->add('cobrancas', $data, true)) {
            $data['idCobranca'] = $id;
            log_info('Cobrança criada com successo. ID: ' . $result->id);
        } else {
            throw new \Exception('Erro ao salvar cobrança!');
        }

        return $data;
    }

    private function valorTotal($produtosValor, $servicosValor, $desconto, $tipo_desconto, $cursosValor = 0, $viagensValor = 0)
    {
        $totalItens = $produtosValor + $servicosValor + $cursosValor + $viagensValor;

        if ($tipo_desconto == 'porcento') {
            $def_desconto = $desconto * $totalItens / 100;
        } elseif ($tipo_desconto == 'real') {
            $def_desconto = $desconto;
        } else {
            $def_desconto = 0;
        }

        return $totalItens - $def_desconto;
    }

    private function criarOuRetornarClienteAsaasId($clienteId)
    {
        $cliente = (array) $this->ci->clientes_model->getById($clienteId);
        if (! $cliente) {
            throw new Exception('Cliente não encontrado: ' . $clienteId);
        }

        if (isset($cliente['asaas_id'])) {
            return $cliente['asaas_id'];
        }

        $result = $this->asaasApi->Cliente()->create([
            'name' => $cliente['nomeCliente'],
            'email' => $cliente['email'],
            'phone' => preg_replace('/[^0-9]/', '', $cliente['telefone']),
            'mobilePhone' => preg_replace('/[^0-9]/', '', $cliente['celular']),
            'cpfCnpj' => preg_replace('/[^0-9]/', '', $cliente['documento']),
            'postalCode' => $cliente['cep'],
            'address' => $cliente['rua'],
            'addressNumber' => $cliente['numero'],
            'complement' => $cliente['complemento'],
            'province' => $cliente['bairro'],
            'city' => $cliente['cidade'],
            'state' => $cliente['estado'],
            'country' => 'Brasil',
            'externalReference' => $clienteId,
            'notificationDisabled' => $this->asaasConfig['notify'] === false,
            'observations' => '',
            'groupName' => 'mapos',
        ]);

        if (! $result) {
            // Lide com o caso em que $id é nulo
            throw new \Exception('Erro ao criar cliente na Asaas!');
        }

        $success = $this->ci->clientes_model->edit(
            'clientes',
            [
                'asaas_id' => $result->id,
            ],
            'idClientes',
            $clienteId
        );

        if ($success) {
            return $result->id;
        } else {
            throw new Exception('Erro ao criar cliente na Asaas!');
        }
    }
}
