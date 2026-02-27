<?php

use Libraries\Gateways\BasePaymentGateway;
use Libraries\Gateways\Contracts\PaymentGateway;
use MercadoPago\Payment;
use MercadoPago\SDK;

class MercadoPago extends BasePaymentGateway
{
    /** @var SDK */
    private $mercadoPagoApi;

    private $mercadoPagoConfig;

    private $configError = null;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->config('payment_gateways');
        $this->ci->load->model('Os_model');
        $this->ci->load->model('vendas_model');
        $this->ci->load->model('cobrancas_model');
        $this->ci->load->model('mapos_model');
        $this->ci->load->model('email_model');

        $mercadoPagoConfig = $this->ci->config->item('payment_gateways')['MercadoPago'];
        $this->mercadoPagoConfig = $mercadoPagoConfig;

        $mercadoPagoApi = new SDK();
        
        if (!empty($mercadoPagoConfig['credentials']['access_token'])) {
            try {
                $mercadoPagoApi->setAccessToken($mercadoPagoConfig['credentials']['access_token']);
            } catch (\Throwable $e) {
                $errorMsg = $e->getMessage();
                // Este erro específico do SDK significa que a validação do token falhou ao buscar dados do usuário.
                if (strpos($errorMsg, 'Undefined array key "id"') !== false) {
                    $errorMsg = 'O Access Token é inválido, expirou, ou não pertence à conta/ambiente correto. Gere um novo token no painel do MercadoPago.';
                }
                $this->configError = 'Falha na autenticação com MercadoPago: ' . $errorMsg;
                log_message('error', 'MercadoPago: ' . $this->configError);
            }
        } else {
            $this->configError = 'Access Token do MercadoPago não está configurado.';
        }

        if (!empty($mercadoPagoConfig['credentials']['public_key'])) {
            $mercadoPagoApi->setPublicKey($mercadoPagoConfig['credentials']['public_key']);
        }
        if (!empty($mercadoPagoConfig['credentials']['client_secret'])) {
            $mercadoPagoApi->setClientSecret($mercadoPagoConfig['credentials']['client_secret']);
        }
        if (!empty($mercadoPagoConfig['credentials']['client_id'])) {
            $mercadoPagoApi->setClientId($mercadoPagoConfig['credentials']['client_id']);
        }
        if (!empty($mercadoPagoConfig['credentials']['integrator_id'])) {
            $mercadoPagoApi->setIntegratorId($mercadoPagoConfig['credentials']['integrator_id']);
        }
        if (!empty($mercadoPagoConfig['credentials']['platform_id'])) {
            $mercadoPagoApi->setPlatformId($mercadoPagoConfig['credentials']['platform_id']);
        }
        if (!empty($mercadoPagoConfig['credentials']['corporation_id'])) {
            $mercadoPagoApi->setCorporationId($mercadoPagoConfig['credentials']['corporation_id']);
        }

        $this->mercadoPagoApi = $mercadoPagoApi;
    }

    public function cancelar($id)
    {
        $cobranca = $this->ci->cobrancas_model->getById($id);
        if (! $cobranca) {
            throw new \Exception('Cobrança não existe!');
        }

        $payment = Payment::find_by_id($cobranca->charge_id);
        if ($payment->Error()) {
            throw new \Exception($payment->Error());
        }

        // Se o status for 'cancelled', não podemos cancelar novamente
        if ($payment->status === 'cancelled') {
            return $this->atualizarDados($id);
        }

        $payment->status = 'cancelled';
        @$payment->update();
        if ($payment->Error()) {
            $error = $payment->Error();
            $errorMsg = is_string($error) ? $error : json_encode($error);

            if (strpos($errorMsg, 'The action requested is not valid for the current payment state') !== false) {
                return $this->atualizarDados($id);
            }
            throw new \Exception($errorMsg);
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
                'emitente' => $emitente[0],
                'paymentGatewaysConfig' => $this->ci->config->item('payment_gateways'),
            ],
            true
        );

        $assunto = 'Cobrança - ' . $emitente[0]->nome;
        if ($cobranca->os_id) {
            $assunto .= ' - OS #' . $cobranca->os_id;
        } else {
            $assunto .= ' - Venda #' . $cobranca->vendas_id;
        }

        $remetentes = [$cobranca->email];
        foreach ($remetentes as $remetente) {
            $headers = [
                'From' => $emitente[0]->email,
                'Subject' => $assunto,
                'Return-Path' => '',
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

        $payment = Payment::find_by_id($cobranca->charge_id);
        if ($payment->Error()) {
            throw new \Exception($payment->Error());
        }

        // Cobrança foi paga ou foi confirmada de forma manual, então damos baixa
        if ($payment->status === 'approved') {
            // TODO: dar baixa no lançamento caso exista
        }

        $databaseResult = $this->ci->cobrancas_model->edit(
            'cobrancas',
            [
                'status' => $payment->status,
            ],
            'idCobranca',
            $id
        );

        if ($databaseResult == true) {
            $this->ci->session->set_flashdata('success', 'Cobrança atualizada com sucesso!');
            log_message('info', 'Alterou um status de cobrança. ID' . $id);
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

        $payment = Payment::find_by_id($cobranca->charge_id);
        if ($payment->Error()) {
            throw new \Exception($payment->Error());
        }

        $payment->capture();
        if ($payment->Error()) {
            throw new \Exception($payment->Error());
        }

        return $this->atualizarDados($id);
    }

    public function gerarCobrancaBoleto($id, $tipo, $dadosParcela = null)
    {
        if ($this->configError) {
            throw new \Exception($this->configError);
        }

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

        $clientNameParts = explode(' ', $entity->nomeCliente);
        $documento = preg_replace('/[^0-9]/', '', $entity->documento);
        
        if (empty($documento)) {
            throw new \Exception('O cliente não possui CPF/CNPJ válido cadastrado!');
        }

        if ($dadosParcela) {
            $valor = is_array($dadosParcela['valor']) ? array_sum(array_map('floatval', $dadosParcela['valor'])) : $dadosParcela['valor'];
            $rawDate = $dadosParcela['vencimento'];
            $expirationDate = null;

            if (!empty($rawDate)) {
                $expirationDate = DateTime::createFromFormat('Y-m-d H:i:s', $rawDate);
                if (!$expirationDate) {
                    $expirationDate = DateTime::createFromFormat('Y-m-d', $rawDate);
                }
                if (!$expirationDate) {
                    $expirationDate = DateTime::createFromFormat('d/m/Y', $rawDate);
                }
                if (!$expirationDate) {
                    try {
                        $expirationDate = new DateTime($rawDate);
                    } catch (\Throwable $e) {
                        // Se tudo falhar, anula para usar o padrão abaixo
                        $expirationDate = null;
                        log_message('info', "Não foi possível parsear a data '{$rawDate}'. Usando expiração padrão. Erro: {$e->getMessage()}");
                    }
                }
            }

            // Se $expirationDate não foi definida com sucesso, usa o padrão.
            if (!$expirationDate) {
                $expirationDate = new DateTime();
                if (isset($this->mercadoPagoConfig['boleto_expiration'])) {
                    try {
                        $expirationDate->add(new \DateInterval($this->mercadoPagoConfig['boleto_expiration']));
                    } catch (\Exception $e) {
                        // Silencioso se o intervalo for inválido
                    }
                }
            }
            
            // Define o horário para o final do dia para evitar problemas de fuso horário
            $expirationDate->setTime(23, 59, 59);
            
            $today = new \DateTime();
            $today->setTime(0, 0, 0);
            
            // Se a data da parcela já passou (vencida), gera para hoje + dias de expiração configurados
            // Se for hoje ou futuro, MANTÉM a data original da parcela (lancamentos.data_vencimento)
            if ($expirationDate < $today) {
                $expirationDate = new \DateTime();
                if (isset($this->mercadoPagoConfig['boleto_expiration'])) {
                    try {
                        $expirationDate->add(new \DateInterval($this->mercadoPagoConfig['boleto_expiration']));
                    } catch (\Exception $e) {}
                }
                $expirationDate->setTime(23, 59, 59);
            }
            
            // Formata para ISO 8601 conforme exigido pela API do Mercado Pago
            $expirationDate = $expirationDate->format('Y-m-d\TH:i:s.000P');
            $description = is_array($dadosParcela['descricao']) ? implode(', ', $dadosParcela['descricao']) : $dadosParcela['descricao'];
        } else {
            $valor = $this->valorTotal($totalProdutos, $totalServicos, $totalDesconto, $tipoDesconto, $totalCursos, $totalViagens);
            $expirationDate = (new \DateTime())->add(new \DateInterval($this->mercadoPagoConfig['boleto_expiration']));
            $expirationDate = $expirationDate->format('Y-m-d\TH:i:s.000P');
            $description = $tipo === PaymentGateway::PAYMENT_TYPE_OS ? "OS #$id" : "Venda #$id";
        }

        $payment = new Payment();
        $payment->transaction_amount = floatval($valor);
        $payment->description = $description;
        $payment->payment_method_id = 'bolbradesco';
        $payment->date_of_expiration = $expirationDate;
        $payerData = [
            'email' => $entity->email,
            'first_name' => $clientNameParts[0],
            'last_name' => $clientNameParts[count($clientNameParts) - 1],
            'identification' => [
                'type' => strlen($documento) == 11 ? 'CPF' : 'CNPJ',
                'number' => $documento,
            ],
            'address' => [
                'zip_code' => preg_replace('/[^0-9]/', '', $entity->cep),
                'street_name' => $entity->rua ?: 'Rua não informada',
                'street_number' => $entity->numero ?: 'S/N',
                'neighborhood' => $entity->bairro ?: 'Bairro não informado',
                'city' => $entity->cidade,
                'federal_unit' => $entity->estado,
            ],
        ];
        $payment->payer = $payerData;


        if (isset($dadosParcela['id'])) {
            $refPrefix = $tipo === PaymentGateway::PAYMENT_TYPE_OS ? 'OS' : 'Venda';
            $parcelaId = is_array($dadosParcela['id']) ? implode(',', $dadosParcela['id']) : $dadosParcela['id'];
            $payment->external_reference = "{$refPrefix}{$id}-L{$parcelaId}";
        }

        $payment->installments = 1;

        // Prepara payload para log manual (garante visualização dos dados)
        $debugPayload = [
            'transaction_amount' => $payment->transaction_amount,
            'description' => $payment->description,
            'payment_method_id' => $payment->payment_method_id,
            'date_of_expiration' => $payment->date_of_expiration,
            'payer' => $payerData,
            'external_reference' => $payment->external_reference ?? 'N/A'
        ];
        // Força log como ERROR para garantir gravação mesmo com threshold baixo
        log_message('error', '[MercadoPago] Payload Enviado: ' . json_encode($debugPayload, JSON_PRETTY_PRINT));

        try {
            $payment->save();

            // Se houver erro na resposta da API
            if ($payment->Error()) {
                $error = $payment->Error();
                // A resposta de erro pode ser um objeto, array ou string
                $errorDetails = is_object($error) || is_array($error) ? json_encode($error, JSON_PRETTY_PRINT) : strval($error);
                
                log_message('error', '[MercadoPago] API Error on save: ' . $errorDetails);
                log_message('error', '[MercadoPago] Payload enviado (Erro): ' . json_encode($debugPayload, JSON_PRETTY_PRINT));
                if (strpos($errorDetails, 'UNAUTHORIZED') !== false) {
                    throw new \Exception('Erro 403 (Não Autorizado). Verifique se o Access Token é válido e pertence ao ambiente correto.');
                }
                throw new \Exception('Erro na API do MercadoPago: ' . $errorDetails);
            }

            // Log da resposta de sucesso
            log_message('debug', '[MercadoPago] Success Response: ' . json_encode($payment, JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {
            // Log de exceção durante a chamada da API
            $errorMessage = "[MercadoPago] Exception during API call: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}";
            log_message('error', $errorMessage . "\nStack Trace:\n" . $e->getTraceAsString());
            log_message('error', '[MercadoPago] Payload enviado (Exception): ' . json_encode($debugPayload, JSON_PRETTY_PRINT));
            
            // Re-lança a exceção para que o controller possa tratá-la
            throw $e;
        }

        $expireAt = $payment->date_of_expiration;
        try {
            $date = new DateTime($expireAt);
            $expireAt = $date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            log_message('error', '[MercadoPago] Erro ao formatar data de expiração: ' . $e->getMessage());
            $timestamp = strtotime($expireAt);
            if ($timestamp) {
                $expireAt = date('Y-m-d H:i:s', $timestamp);
            }
        }

        $data = [
            'barcode' => isset($payment->barcode) ? (is_array($payment->barcode) ? ($payment->barcode['content'] ?? '') : ($payment->barcode->content ?? '')) : '',
            'link' => isset($payment->transaction_details) ? (is_array($payment->transaction_details) ? ($payment->transaction_details['external_resource_url'] ?? '') : ($payment->transaction_details->external_resource_url ?? '')) : '',
            'pdf' => isset($payment->transaction_details) ? (is_array($payment->transaction_details) ? ($payment->transaction_details['external_resource_url'] ?? '') : ($payment->transaction_details->external_resource_url ?? '')) : '',
            'expire_at' => $expireAt,
            'charge_id' => $payment->id,
            'status' => $payment->status,
            'total' => getMoneyAsCents($valor),
            'clientes_id' => $entity->idClientes,
            'payment_method' => 'boleto',
            'payment_gateway' => 'MercadoPago',
        ];

        if ($tipo === PaymentGateway::PAYMENT_TYPE_OS) {
            $data['os_id'] = $id;
        } else {
            $data['vendas_id'] = $id;
        }

        if ($id = $this->ci->cobrancas_model->add('cobrancas', $data, true)) {
            $data['idCobranca'] = $id;
            log_message('info', 'Cobrança criada com successo. ID: ' . $payment->id);
        } else {
            throw new \Exception('Erro ao salvar cobrança!');
        }

        return $data;
    }

    public function gerarCobrancaLink($id, $tipo, $dadosParcela = null)
    {
        throw new Exception('MercadoPago não suporta gerar link pela API, somente pelo painel!');
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
}
