<!DOCTYPE html>
<html>
<head>
    <title>Ficha de Viagem</title>
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
            color: #333;
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
            border-top: 1px solid #dddddd;
        }
        .table th {
            font-weight: bold;
            background-color: #f9f9f9;
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
            padding: 20px;
        }
        h2, h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
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
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 11.844px;
            font-weight: bold;
            line-height: 14px;
            color: #ffffff;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            white-space: nowrap;
            vertical-align: baseline;
            background-color: #999999;
            border-radius: 3px;
        }
        .badge-success {
            background-color: #468847;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 10px;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="invoice-content">
            <div class="invoice-head">
                <table class="table">
                    <tbody>
                        <?php if ($emitente == null) : ?>
                            <tr>
                                <td colspan="3" class="alert">Você precisa configurar os dados do emitente. >>><a href="<?php echo base_url(); ?>index.php/mapos/emitente">Configurar</a><<<</td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td style="width: 25%;"><img src="<?php echo $emitente->url_logo; ?>" class="logo"></td>
                                <td>
                                    <span style="font-size: 20px; font-weight: bold;"><?php echo $emitente->nome; ?></span><br>
                                    <span><?php echo $emitente->cnpj; ?></span><br>
                                    <span><?php echo $emitente->rua . ', ' . $emitente->numero . ' - ' . $emitente->bairro; ?></span><br>
                                    <span><?php echo $emitente->cidade . ' - ' . $emitente->uf; ?></span>
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

            <h2>Ficha da Viagem: <?= html_escape($result->nome_viagem) ?></h2>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 20%;"><strong>Partida:</strong></td>
                        <td><?= $result->data_partida ? date('d/m/Y', strtotime($result->data_partida)) : '' ?></td>
                        <td style="width: 20%;"><strong>Retorno:</strong></td>
                        <td><?= $result->data_retorno ? date('d/m/Y', strtotime($result->data_retorno)) : '' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Vagas Restantes:</strong></td>
                        <td><?= $result->vagas ?></td>
                        <td><strong>Status:</strong></td>
                        <td><?= html_escape($result->status) ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Logística de Participantes</div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="text-center">Embarque</th>
                        <th class="text-center">Hospedagem</th>
                        <th>Detalhes da Hospedagem</th>
                        <th class="text-center">Pagamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)) : ?>
                        <?php foreach ($clientes as $c) : ?>
                            <tr>
                                <td><?= html_escape($c->nomeCliente) ?></td>
                                <td class="text-center"><?= $c->precisa_embarque ? 'Sim' : 'Não' ?></td>
                                <td class="text-center"><?= $c->precisa_hospedagem ? 'Sim' : 'Não' ?></td>
                                <td>
                                    <?php if ($c->precisa_hospedagem) : ?>
                                        <strong>Quarto:</strong> <?= html_escape($c->hospedagem_quarto_numero) ?><br>
                                        <strong>Tipo:</strong> <?= html_escape($c->hospedagem_tipo_quarto) ?><br>
                                        <strong>Camas:</strong> <?= html_escape($c->hospedagem_numero_camas) ?><br>
                                        <strong>Obs:</strong> <?= html_escape($c->detalhes_hospedagem) ?>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= html_escape($c->status_pagamento) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="5" class="text-center">Nenhum participante inscrito.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="section-title">Instrutores</div>
            <table class="table table-bordered">
                <tbody>
                    <?php if (!empty($instrutores)) : ?>
                        <?php foreach ($instrutores as $i) : ?>
                            <tr>
                                <td><?= html_escape($i->nome_instrutor) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td>Nenhum instrutor designado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>