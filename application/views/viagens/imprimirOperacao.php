<!DOCTYPE html>
<html>
<head>
    <title>Ficha de Operação</title>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            color: #444;
            background-color: #f9f9f9;
        }
        .container-fluid {
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
            border-top: 1px solid #e9e9e9;
        }
        .table th {
            font-weight: bold;
            background-color: #f2f2f2;
            color: #333;
        }
        .table-bordered {
            border: 1px solid #dddddd;
            border-radius: 4px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dddddd;
        }
        .invoice-head td {
            border: 0 !important;
        }
        .invoice-content {
            padding: 10px;
        }
        h2, h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        .logo {
            max-height: 100px;
            max-width: 200px;
            object-fit: contain;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .check-box {
            width: 15px;
            height: 15px;
            border: 1px solid #333;
            display: inline-block;
            text-align: center;
            line-height: 15px;
            font-weight: bold;
            color: #333;
        }
        .signature-box {
            margin-top: 60px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 300px;
            margin: 0 auto;
            padding-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="invoice-content">
            <div class="invoice-head">
                <table class="table">
                    <tbody>
                        <?php if ($emitente != null) : ?>
                            <tr>
                                <td style="width: 25%; vertical-align: middle;"><img src="<?php echo $emitente->url_logo; ?>" class="logo"></td>
                                <td>
                                    <span style="font-size: 20px; font-weight: bold;"><?php echo $emitente->nome; ?></span><br>
                                    <span>CNPJ: <?php echo $emitente->cnpj; ?></span><br>
                                    <span><?php echo $emitente->rua . ', ' . $emitente->numero . ' - ' . $emitente->bairro; ?></span><br>
                                    <span><?php echo $emitente->cidade . ' - ' . $emitente->uf . ' - CEP: ' . $emitente->cep; ?></span><br>
                                    <span>Telefone: <?php echo $emitente->telefone; ?> - Email: <?php echo $emitente->email; ?></span>
                                </td>
                                <td style="width: 25%; text-align: right;">
                                    <strong>#Viagem: </strong><span><?php echo $result->id ?></span><br>
                                    <strong>Emissão: </strong><span><?php echo date('d/m/Y'); ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h2>Ficha de Operação: <?= html_escape($result->nome_viagem) ?></h2>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 15%;"><strong>Partida:</strong></td>
                        <td><?= $result->data_partida ? date('d/m/Y', strtotime($result->data_partida)) : '' ?></td>
                        <td style="width: 15%;"><strong>Retorno:</strong></td>
                        <td><?= $result->data_retorno ? date('d/m/Y', strtotime($result->data_retorno)) : '' ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Mergulhadores e Equipamentos</div>
            <table class="table table-bordered" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th>Nome do Mergulhador</th>
                        <th class="text-center">Atestado Médico</th>
                        <th>Certificações</th>
                        <th colspan="2">Equipamentos Locados (Checklist)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)) : ?>
                        <?php foreach ($clientes as $c) : ?>
                            <tr>
                                <td style="vertical-align: top;"><?= html_escape($c->nomeCliente) ?></td>
                                <td style="vertical-align: top; text-align: center;">
                                    <?php
                                        $statusAtestado = 'Não informado';
                                        $corAtestado = '';
                                        if ($c->atestado_medico_validade) {
                                            $validade = new DateTime($c->atestado_medico_validade);
                                            $hoje = new DateTime();
                                            if ($validade < $hoje) {
                                                $statusAtestado = 'Vencido';
                                                $corAtestado = 'style="color: red; font-weight: bold;"';
                                            } else {
                                                $statusAtestado = 'Válido';
                                                $corAtestado = 'style="color: green;"';
                                            }
                                        }
                                        echo "<span $corAtestado>$statusAtestado</span>";
                                    ?>
                                </td>
                                <td style="vertical-align: top;">
                                    <?php if (!empty($c->certificacoes)) : ?>
                                        <?php foreach ($c->certificacoes as $cert) : ?>
                                            - <?= html_escape($cert->nome_certificacao) ?><br>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        Nenhuma
                                    <?php endif; ?>
                                </td>
                                <td style="width: 15%; vertical-align: top;">
                                    <span class="check-box"><?= $c->locar_cilindro ? 'X' : '&nbsp;' ?></span> CI - Cilindro<br>
                                    <span class="check-box"><?= $c->locar_regulador ? 'X' : '&nbsp;' ?></span> RGR - Regulador<br>
                                    <span class="check-box"><?= $c->locar_lastro ? 'X' : '&nbsp;' ?></span> LSO - Lastro (<?= html_escape($c->peso_lastro ?: 'N/I') ?> kg)
                                </td>
                                <td style="width: 20%; vertical-align: top;">
                                    <span class="check-box"><?= $c->locar_colete ? 'X' : '&nbsp;' ?></span> CLT - Colete (Tam: <?= html_escape($c->tamanho_colete ?: 'N/I') ?>)<br>
                                    <span class="check-box"><?= $c->locar_nadadeira ? 'X' : '&nbsp;' ?></span> NDA - Nadadeira (Tam: <?= html_escape($c->tamanho_nadadeira ?: 'N/I') ?>)<br>
                                    <span class="check-box"><?= $c->locar_neoprene ? 'X' : '&nbsp;' ?></span> NEO - Neoprene (Tam: <?= html_escape($c->tamanho_neoprene ?: 'N/I') ?>)
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="5" class="text-center">Nenhum mergulhador inscrito.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="section-title">Equipe de Instrutores</div>
            <table class="table table-bordered">
                <tbody>
                    <?php if (!empty($instrutores)) : ?>
                        <?php foreach ($instrutores as $i) : ?>
                            <tr><td><?= html_escape($i->nome_instrutor) ?></td></tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td>Nenhum instrutor designado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="section-title">Resumo de Equipamentos para Locação</div>
            <table class="table table-bordered" style="width: 50%;">
                <tbody>
                    <tr><td style="width: 40%;"><strong>Cilindros (CI)</strong></td><td><?= $resumoEquipamentos['cilindro'] ?></td></tr>
                    <tr><td><strong>Reguladores (RGR)</strong></td><td><?= $resumoEquipamentos['regulador'] ?></td></tr>
                    <tr><td><strong>Lastros (LSO)</strong></td><td><?= $resumoEquipamentos['lastro'] ?></td></tr>
                    <tr>
                        <td style="vertical-align: middle;"><strong>Coletes (CLT)</strong></td>
                        <td>
                            <?php foreach ($resumoEquipamentos['colete'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;"><strong>Nadadeiras (NDA)</strong></td>
                        <td>
                            <?php foreach ($resumoEquipamentos['nadadeira'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;"><strong>Neoprene (NEO)</strong></td>
                        <td>
                            <?php foreach ($resumoEquipamentos['neoprene'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="signature-box">
                <div class="signature-line"></div>
                <p><?= $emitente->nome ?? 'Responsável' ?></p>
            </div>
        </div>
    </div>
</body>
</html>