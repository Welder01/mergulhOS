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
            padding: 5px;
            line-height: 1.4;
            text-align: left;
            vertical-align: middle !important;
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
            padding: 5px 10px;
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
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            line-height: 14px;
            color: #ffffff;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            white-space: nowrap;
            vertical-align: baseline;
            background-color: #999999;
            border-radius: 3px;
        }
        .badge-info { background-color: #3a87ad; }
        .badge-success { background-color: #468847; }
        .badge-warning { background-color: #f89406; }
        .badge-inverse { background-color: #333333;
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
                                    <span style="font-size: 16px; font-weight: bold;"><?php echo $emitente->nome; ?></span><br>
                                    <span>CNPJ: <?php echo $emitente->cnpj; ?></span><br>
                                    <span><?php echo $emitente->rua . ', ' . $emitente->numero . ' - ' . $emitente->bairro; ?></span><br>
                                    <span><?php echo $emitente->cidade . ' - ' . $emitente->uf . ' - CEP: ' . $emitente->cep; ?></span><br>
                                    <span>Telefone: <?php echo $emitente->telefone; ?> - Email: <?php echo $emitente->email; ?></span>
                                </td>
                                <td style="width: 25%; text-align: right; vertical-align: top;">
                                    <strong>#Viagem: </strong><span><?php echo $result->id ?></span><br>
                                    <strong>Emissão: </strong><span><?php echo date('d/m/Y'); ?></span><br><br>
                                    <strong>Partida: </strong><span><?= $result->data_partida ? date('d/m/Y', strtotime($result->data_partida)) : '' ?></span><br>
                                    <strong>Retorno: </strong><span><?= $result->data_retorno ? date('d/m/Y', strtotime($result->data_retorno)) : '' ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center; padding-top: 15px;"><h2 class="document-title" style="margin:0; border:0;"><?= html_escape(strtoupper($result->nome_viagem)) ?></h2></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="section-title">Mergulhadores e Equipamentos</div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 2%;">#</th>
                        <th>Nome do Mergulhador</th>
                        <th class="text-center" style="width: 10%;">Atestado</th>
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
                                <td style="vertical-align: top; font-size: 9px;">
                                    <?php if (!empty($c->certificacoes)) : ?>
                                        <?php foreach ($c->certificacoes as $cert) : ?>
                                            - <?= html_escape($cert->nome_certificacao) ?><br>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        Nenhuma
                                    <?php endif; ?>
                                </td>
                                <td class="equip-box"><?= $c->locar_cilindro > 0 ? $c->locar_cilindro : '<i class="fas fa-lock"></i>' ?></td>
                                <td class="equip-box"><?= $c->locar_regulador > 0 ? $c->locar_regulador : '<i class="fas fa-lock"></i>' ?></td>
                                <td class="equip-box">
                                    <?= $c->locar_colete ? html_escape($c->tamanho_colete ?: 'N/I') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td class="equip-box">
                                    <?= $c->locar_neoprene ? html_escape($c->tamanho_neoprene ?: 'N/I') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td class="equip-box">
                                    <?= $c->locar_nadadeira ? html_escape($c->tamanho_nadadeira ?: 'N/I') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td class="equip-box">
                                    <?= $c->locar_lastro ? (html_escape($c->peso_lastro) . 'kg') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td><?= html_escape($c->numero_bolsa) ?></td>
                                <td class="text-center">
                                    <?php
                                        $proposito = html_escape($c->proposito);
                                        $badgeClass = '';
                                        switch ($proposito) {
                                            case 'Checkout': $badgeClass = 'badge-info'; break;
                                            case 'Acompanhante': $badgeClass = 'badge-inverse'; break;
                                            case 'Turismo': $badgeClass = 'badge-success'; break;
                                            case 'Batismo': $badgeClass = 'badge-warning'; break;
                                        }
                                        if ($proposito) {
                                            echo '<span class="badge ' . $badgeClass . '">' . $proposito . '</span>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="12" class="text-center">Nenhum mergulhador inscrito.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="section-title">Equipe de Instrutores</div>
            <table class="table table-bordered" style="font-size: 9px;">
                <thead>
                    <tr>
                        <th style="width: 2%;">#</th>
                        <th>Nome do Instrutor</th>
                        <th class="text-center" style="width: 10%;">Atestado</th>
                        <th>Certificações</th>
                        <th class="equip-box">CI</th>
                        <th class="equip-box">RGR</th>
                        <th class="equip-box">CLT</th>
                        <th class="equip-box">NEO</th>
                        <th class="equip-box">NDA</th>
                        <th class="equip-box">LSO</th>
                        <th style="width: 10%;">Bolsa</th>
                        <th style="width: 10%;">Obs.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count_instrutor = 1; ?>
                    <?php if (!empty($instrutores)) : ?>
                        <?php foreach ($instrutores as $i) : ?>
                            <tr>
                                <td class="text-center"><?= $count_instrutor++ ?></td>
                                <td><?= html_escape($i->nome_instrutor) ?></td>
                                <td class="text-center">N/A</td> <!-- Atestado não disponível diretamente para instrutor (usuário) -->
                                <td>N/A</td> <!-- Certificações não disponíveis diretamente para instrutor (usuário) -->
                                <td class="equip-box"><?= $i->locar_cilindro > 0 ? $i->locar_cilindro : '<i class="fas fa-lock"></i>' ?></td>
                                <td class="equip-box"><?= $i->locar_regulador > 0 ? $i->locar_regulador : '<i class="fas fa-lock"></i>' ?></td>
                                <td class="equip-box">
                                    <?= $i->locar_colete ? html_escape($i->tamanho_colete ?: 'N/I') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td class="equip-box">
                                    <?= $i->locar_neoprene ? html_escape($i->tamanho_neoprene ?: 'N/I') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td class="equip-box">
                                    <?= $i->locar_nadadeira ? html_escape($i->tamanho_nadadeira ?: 'N/I') : '<i class="fas fa-lock"></i>' ?>
                                </td>
                                <td class="equip-box">
                                    <?= $i->locar_lastro ? (html_escape($i->peso_lastro) . 'kg') : '<i class="fas fa-lock"></i>' ?>
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
            <table class="table table-bordered" style="width: 100%; font-size: 9px;">
                <thead>
                    <tr>
                        <th class="text-center">Cilindros (CI)</th>
                        <th class="text-center">Reguladores (RGR)</th>
                        <th class="text-center">Lastros (LSO)</th>
                        <th class="text-center">Coletes (CLT)</th>
                        <th class="text-center">Nadadeiras (NDA)</th>
                        <th class="text-center">Neoprene (NEO)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" style="vertical-align: top;"><?= $resumoEquipamentos['cilindro'] ?></td>
                        <td class="text-center" style="vertical-align: top;"><?= $resumoEquipamentos['regulador'] ?></td>
                        <td class="text-center" style="vertical-align: top;"><?= $resumoEquipamentos['lastro'] ?></td>
                        <td style="vertical-align: top;">
                            <?php foreach ($resumoEquipamentos['colete'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td style="vertical-align: top;">
                            <?php foreach ($resumoEquipamentos['nadadeira'] as $tamanho => $qtd) : ?>
                                Tam: <?= $tamanho ?> - Qtd: <?= $qtd ?><br>
                            <?php endforeach; ?>
                        </td>
                        <td style="vertical-align: top;">
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