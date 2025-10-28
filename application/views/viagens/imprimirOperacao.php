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
            color: #333;
            font-size: 9px;
            background-color: #f9f9f9;
        }
        .document-title {
            font-size: 12px; font-weight: bold; text-align: center; margin-bottom: 10px;
        }
        .container-fluid {
            background-color: #fff;
            padding: 10px;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 4px;
            line-height: 1.4;
            text-align: left;
            vertical-align: middle !important;
            border-top: 1px solid #e9e9e9;
        }
        .table th {
            font-weight: bold;
            background-color: #f0f0f0;
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
            padding: 5px;
        }
        h2, h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
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
            background-color: #444;
            color: #fff;
            padding: 10px;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .equip-box {
            text-align: center;
            width: 40px;
        }
        .equip-box .size {
            font-size: 9px;
            color: #555;
        }
        .check-box {
            border: 1px solid #333;
            display: inline-block;
            width: 12px; height: 12px;
            text-align: center; line-height: 12px;
            font-weight: bold;
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
                <h2 class="document-title">FICHA DE OPERAÇÃO: <?= html_escape(strtoupper($result->nome_viagem)) ?></h2>

                <table class="table">
                    <tbody>
                        <?php if ($emitente != null) : ?>
                            <tr>
                                <td style="width: 20%; vertical-align: middle;"><img src="<?php echo $emitente->url_logo; ?>" class="logo"></td>
                                <td>
                                    <span style="font-size: 20px; font-weight: bold;"><?php echo $emitente->nome; ?></span><br>
                                    <span>CNPJ: <?php echo $emitente->cnpj; ?></span><br>
                                    <span><?php echo $emitente->rua . ', ' . $emitente->numero . ' - ' . $emitente->bairro; ?></span><br>
                                    <span><?php echo $emitente->cidade . ' - ' . $emitente->uf . ' - CEP: ' . $emitente->cep; ?></span><br>
                                    <span>Telefone: <?php echo $emitente->telefone; ?> - Email: <?php echo $emitente->email; ?></span>
                                </td>
                                <td style="width: 20%; text-align: right;">
                                    <strong>#Viagem: </strong><span><?php echo $result->id ?></span><br>
                                    <strong>Emissão: </strong><span><?php echo date('d/m/Y'); ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

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
            <table class="table table-bordered" style="font-size: 9px;">
                <thead>
                    <tr>
                        <th style="width: 2%;">#</th>
                        <th>Nome do Mergulhador</th>
                        <th class="text-center" style="width: 8%;">Atestado</th>
                        <th>Certificações</th>
                        <th class="equip-box">CI</th>
                        <th class="equip-box">RGR</th>
                        <th class="equip-box">CLT</th>
                        <th class="equip-box">NEO</th>
                        <th class="equip-box">NDA</th>
                        <th class="equip-box">LSO</th>
                        <th style="width: 8%;">Bolsa</th>
                        <th style="width: 12%;">Obs.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)) : ?>
                        <?php $count = 1; ?>
                        <?php foreach ($clientes as $c) : ?>
                            <tr>
                                <td class="text-center"><?= $count++ ?></td>
                                <td><?= html_escape($c->nomeCliente) ?></td>
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
                                <td style="vertical-align: top; font-size: 8px;">
                                    <?php if (!empty($c->certificacoes)) : ?>
                                        <?php foreach ($c->certificacoes as $cert) : ?>
                                            - <?= html_escape($cert->nome_certificacao) ?><br>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        Nenhuma
                                    <?php endif; ?>
                                </td>
                                <td class="equip-box"><div class="check-box"><?= $c->locar_cilindro > 0 ? 'X' : '&nbsp;' ?></div><div class="size"><?= $c->locar_cilindro > 0 ? $c->locar_cilindro : '' ?></div></td>
                                <td class="equip-box"><div class="check-box"><?= $c->locar_regulador > 0 ? 'X' : '&nbsp;' ?></div><div class="size"><?= $c->locar_regulador > 0 ? $c->locar_regulador : '' ?></div></td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $c->locar_colete ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $c->locar_colete ? html_escape($c->tamanho_colete ?: 'N/I') : '' ?></div>
                                </td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $c->locar_neoprene ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $c->locar_neoprene ? html_escape($c->tamanho_neoprene ?: 'N/I') : '' ?></div>
                                </td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $c->locar_nadadeira ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $c->locar_nadadeira ? html_escape($c->tamanho_nadadeira ?: 'N/I') : '' ?></div>
                                </td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $c->locar_lastro ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $c->locar_lastro ? (html_escape($c->peso_lastro) . ' kg') : '' ?></div>
                                </td>
                                <td><?= html_escape($c->numero_bolsa) ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="12" class="text-center">Nenhum mergulhador inscrito.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="section-title">Equipe de Instrutores</div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 2%;">#</th>
                        <th>Nome do Instrutor</th>
                        <th class="text-center" style="width: 8%;">Atestado</th>
                        <th>Certificações</th>
                        <th class="equip-box">CI</th>
                        <th class="equip-box">RGR</th>
                        <th class="equip-box">CLT</th>
                        <th class="equip-box">NEO</th>
                        <th class="equip-box">NDA</th>
                        <th class="equip-box">LSO</th>
                        <th style="width: 8%;">Bolsa</th>
                        <th style="width: 12%;">Obs.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count_instrutor = 1; ?>
                    <?php if (!empty($instrutores)) : ?>
                        <?php foreach ($instrutores as $i) : ?>
                            <tr>
                                <td class="text-center"><?= $count_instrutor++ ?></td>
                                <td><?= html_escape($i->nome_instrutor) ?></td>
                                <td class="text-center">N/A</td>
                                <td>N/A</td>
                                <td class="equip-box"><div class="check-box"><?= $i->locar_cilindro > 0 ? 'X' : '&nbsp;' ?></div><div class="size"><?= $i->locar_cilindro > 0 ? $i->locar_cilindro : '' ?></div></td>
                                <td class="equip-box"><div class="check-box"><?= $i->locar_regulador > 0 ? 'X' : '&nbsp;' ?></div><div class="size"><?= $i->locar_regulador > 0 ? $i->locar_regulador : '' ?></div></td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $i->locar_colete ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $i->locar_colete ? html_escape($i->tamanho_colete ?: 'N/I') : '' ?></div>
                                </td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $i->locar_neoprene ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $i->locar_neoprene ? html_escape($i->tamanho_neoprene ?: 'N/I') : '' ?></div>
                                </td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $i->locar_nadadeira ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $i->locar_nadadeira ? html_escape($i->tamanho_nadadeira ?: 'N/I') : '' ?></div>
                                </td>
                                <td class="equip-box">
                                    <div class="check-box"><?= $i->locar_lastro ? 'X' : '&nbsp;' ?></div>
                                    <div class="size"><?= $i->locar_lastro ? (html_escape($i->peso_lastro) . ' kg') : '' ?></div>
                                </td>
                                <td><?= html_escape($i->numero_bolsa) ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="12" class="text-center">Nenhum instrutor designado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="section-title">Resumo de Equipamentos para Locação</div>
            <table class="table table-bordered" style="width: 60%;">
                <tbody>
                    <tr><td style="width: 30%;"><strong>Cilindros (CI)</strong></td><td><?= $resumoEquipamentos['cilindro'] ?></td></tr>
                    <tr><td><strong>Reguladores (RGR)</strong></td><td><?= $resumoEquipamentos['regulador'] ?></td></tr>
                    <tr><td><strong>Lastros (LSO)</strong></td><td><?= $resumoEquipamentos['lastro'] ?></td></tr>
                    <tr>
                        <td style="vertical-align: top;"><strong>Coletes (CLT)</strong></td>
                        <td>
                            <?php foreach ($resumoEquipamentos['colete'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><strong>Nadadeiras (NDA)</strong></td>
                        <td>
                            <?php foreach ($resumoEquipamentos['nadadeira'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><strong>Neoprene (NEO)</strong></td>
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