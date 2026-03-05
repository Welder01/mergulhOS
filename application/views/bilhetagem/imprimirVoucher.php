<!DOCTYPE html>
<html>
<head>
    <title>Voucher de Embarque</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; border: 1px solid #000; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .col { flex: 1; }
        .label { font-weight: bold; display: block; font-size: 10px; color: #666; }
        .value { font-size: 14px; }
        .box { background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 10px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?php echo $emitente->url_logo; ?>" style="max-height: 60px;">
            <h2>CARTÃO DE EMBARQUE / VOUCHER</h2>
            <p>Bilhete #<?php echo str_pad($result->idBilhete, 6, '0', STR_PAD_LEFT); ?></p>
        </div>

        <div class="box">
            <div class="row">
                <div class="col">
                    <span class="label">PASSAGEIRO</span>
                    <span class="value"><?php echo $result->nomeCliente; ?></span>
                </div>
                <div class="col">
                    <span class="label">DOCUMENTO</span>
                    <span class="value"><?php echo $result->documento; ?></span>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="row">
                <div class="col">
                    <span class="label">EXPEDIÇÃO</span>
                    <span class="value"><?php echo $result->expedicao; ?></span>
                </div>
                <div class="col">
                    <span class="label">DATA IDA</span>
                    <span class="value"><?php echo date('d/m/Y H:i', strtotime($result->data_ida)); ?></span>
                </div>
                <div class="col">
                    <span class="label">DATA VOLTA</span>
                    <span class="value"><?php echo date('d/m/Y H:i', strtotime($result->data_volta)); ?></span>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="row">
                <div class="col">
                    <span class="label">TRANSPORTE</span>
                    <span class="value"><?php echo ucfirst(str_replace('_', ' ', $result->tipo_transporte)); ?></span>
                </div>
                <div class="col">
                    <span class="label">EMPRESA</span>
                    <span class="value"><?php echo $result->empresa_emissora; ?></span>
                </div>
                <div class="col">
                    <span class="label">LOCALIZADOR</span>
                    <span class="value"><?php echo $result->codigo_bilhete; ?></span>
                </div>
                <div class="col">
                    <span class="label">ASSENTO</span>
                    <span class="value"><?php echo $result->assento; ?></span>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="row">
                <div class="col">
                    <span class="label">SERVIÇOS INCLUÍDOS</span>
                    <ul style="margin: 5px 0 0 20px; padding: 0;">
                        <?php if($result->qtd_dias_navegacao > 0) echo "<li>{$result->qtd_dias_navegacao} Dias de Navegação</li>"; ?>
                        <?php if($result->pagou_taxa_parque) echo "<li>Taxa de Parque Nacional Paga</li>"; ?>
                        <?php if($result->estadia_estendida) echo "<li>Estadia Estendida</li>"; ?>
                        <?php if($result->valor_bagagem_extra > 0) echo "<li>Bagagem Extra Contratada</li>"; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Emitido em: <?php echo date('d/m/Y H:i', strtotime($result->data_emissao)); ?></p>
            <p>Este documento é pessoal e intransferível. Apresente-o juntamente com um documento com foto.</p>
        </div>
    </div>
    <script>window.print();</script>
</body>
</html>
