<div class="widget-box">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-dollar-sign"></i>
        </span>
        <h5>Pagamento da Viagem</h5>
    </div>
    <div class="widget-content">
        <div class="row-fluid">
            <div class="span12">
                <h4><strong>Viagem:</strong> <?php echo htmlspecialchars($viagem->nome_viagem); ?></h4>
                <p><strong>Valor:</strong> R$ <?php echo number_format($viagem->preco_pessoa, 2, ',', '.'); ?></p>
                <p><strong>Status do Pagamento:</strong> <?php echo htmlspecialchars($viagem->status_pagamento); ?></p>
            </div>
        </div>

        <div id="gerar-pagamento" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
            <h4>Selecione a forma de pagamento:</h4>

            <?php if (isset($_ENV['PAYMENT_GATEWAYS_EFI_CREDENTIAIS_CLIENT_ID']) && $_ENV['PAYMENT_GATEWAYS_EFI_CREDENTIAIS_CLIENT_ID'] != '') : ?>
            <div class="row-fluid" style="margin-bottom: 20px;">
                <div class="span12">
                    <h5><img src="https://sejaefi.com.br/wp-content/themes/seja-efi/images/logo-efi-original.svg" width="80" alt="EFI Logo"></h5>
                    <button data-gateway="efi" data-method="pix" class="btn btn-primary btn-pagar"><i class="fas fa-qrcode"></i> Gerar PIX</button>
                    <button data-gateway="efi" data-method="boleto" class="btn btn-default btn-pagar"><i class="fas fa-barcode"></i> Gerar Boleto</button>
                    <button data-gateway="efi" data-method="link" class="btn btn-default btn-pagar"><i class="fas fa-link"></i> Gerar Link de Pagamento</button>
                </div>
            </div>
            <hr>
            <?php endif; ?>

            <?php if (isset($_ENV['PAYMENT_GATEWAYS_MERCADO_PAGO_CREDENTIALS_ACCESS_TOKEN']) && $_ENV['PAYMENT_GATEWAYS_MERCADO_PAGO_CREDENTIALS_ACCESS_TOKEN'] != '') : ?>
            <div class="row-fluid" style="margin-bottom: 20px;">
                <div class="span12">
                    <h5><img src="https://logospng.org/download/mercado-pago/logo-mercado-pago-256.png" width="120" alt="Mercado Pago Logo"></h5>
                    <button data-gateway="mercadopago" data-method="pix" class="btn btn-primary btn-pagar"><i class="fas fa-qrcode"></i> Gerar PIX</button>
                    <button data-gateway="mercadopago" data-method="boleto" class="btn btn-default btn-pagar"><i class="fas fa-barcode"></i> Gerar Boleto</button>
                    <button data-gateway="mercadopago" data-method="link" class="btn btn-default btn-pagar"><i class="fas fa-link"></i> Gerar Link de Pagamento</button>
                </div>
            </div>
            <hr>
            <?php endif; ?>

            <?php if (isset($_ENV['PAYMENT_GATEWAYS_ASAAS_CREDENTIAIS_API_KEY']) && $_ENV['PAYMENT_GATEWAYS_ASAAS_CREDENTIAIS_API_KEY'] != '') : ?>
            <div class="row-fluid" style="margin-bottom: 20px;">
                <div class="span12">
                    <h5><img src="https://www.asaas.com/assets/images/logo-blue.svg" width="100" alt="Asaas Logo"></h5>
                    <button data-gateway="asaas" data-method="pix" class="btn btn-primary btn-pagar"><i class="fas fa-qrcode"></i> Gerar PIX</button>
                    <button data-gateway="asaas" data-method="boleto" class="btn btn-default btn-pagar"><i class="fas fa-barcode"></i> Gerar Boleto</button>
                    <button data-gateway="asaas" data-method="link" class="btn btn-default btn-pagar"><i class="fas fa-link"></i> Gerar Link de Pagamento</button>
                </div>
            </div>
            <hr>
            <?php endif; ?>
        </div>

        <div id="resultado-pagamento" style="margin-top: 20px; display: none;">
            <!-- O resultado do pagamento será exibido aqui -->
        </div>

        <div class="form-actions">
            <a href="<?php echo base_url() ?>index.php/mine/minhasViagens" class="btn">Voltar</a>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btn-pagar').on('click', function() {
        var method = $(this).data('method');
        var gateway = $(this).data('gateway');
        var button = $(this);
        var originalText = button.html();

        button.html('<i class="fas fa-spinner fa-spin"></i> Gerando...').attr('disabled', true);
        $('#resultado-pagamento').hide().html('');

        $.ajax({
            url: '<?php echo base_url() ?>index.php/mine/gerarCobrancaViagem',
            type: 'POST',
            dataType: 'json',
            data: {
                viagem_cliente_id: '<?php echo $viagem->id; ?>',
                payment_method: method,
                gateway: gateway
            },
            success: function(response) {
                if (response.code === 200) {
                    var html = '';
                    if (method === 'pix') {
                        html = '<h4>Pague com PIX</h4><p>Copie o código abaixo ou leia o QR Code com seu aplicativo de banco:</p>';
                        html += '<img src="' + response.data.imagemQrcode + '" style="max-width: 250px; margin-bottom: 15px;"><br>';
                        html += '<textarea class="span12" rows="3" readonly>' + response.data.qrcode + '</textarea>';
                    } else if (method === 'boleto') {
                        html = '<h4>Boleto Gerado</h4><p>Clique no botão abaixo para visualizar e imprimir seu boleto.</p>';
                        html += '<a href="' + response.data.pdf.charge + '" target="_blank" class="btn btn-success"><i class="fas fa-barcode"></i> Visualizar Boleto</a>';
                    } else if (method === 'link') {
                        html = '<h4>Link de Pagamento</h4><p>Use o link abaixo para pagar:</p>';
                        html += '<a href="' + response.data.payment_url + '" target="_blank">' + response.data.payment_url + '</a>';
                    }
                    $('#resultado-pagamento').html(html).show();
                } else {
                    var errorMsg = response.error_description || response.error || 'Ocorreu um erro desconhecido.';
                    $('#resultado-pagamento').html('<div class="alert alert-danger">' + errorMsg + '</div>').show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var errorMsg = 'Falha na comunicação com o servidor. Tente novamente.';
                if(jqXHR.responseJSON && jqXHR.responseJSON.error) {
                    errorMsg = jqXHR.responseJSON.error;
                }
                $('#resultado-pagamento').html('<div class="alert alert-danger">' + errorMsg + '</div>').show();
            },
            complete: function() {
                button.html(originalText).attr('disabled', false);
            }
        });
    });
});
</script>