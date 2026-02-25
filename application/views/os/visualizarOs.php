<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet">
<style>
    .ui-datepicker {
        z-index: 99999 !important;
    }
</style>
<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: 10px 0 0">
                <div class="buttons">
                    <?php if ($result->faturado == 0) { ?>
                        <a href="#modal-faturar" id="btn-faturar" role="button" data-toggle="modal" class="button btn btn-mini btn-danger">
                            <span class="button__icon"><i class='bx bx-dollar'></i></span> <span class="button__text">Faturar</span>
                        </a>
                    <?php } else { ?>
                        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dLancamento')) { ?>
                            <a href="#modal-cancelar-faturamento" id="btn-cancelar-faturamento" role="button" data-toggle="modal" class="button btn btn-mini btn-danger">
                                <span class="button__icon"><i class='bx bx-x'></i></span> <span class="button__text">Cancelar Faturamento</span>
                            </a>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($editavel) {
                        echo '<a title="Editar OS" class="button btn btn-mini btn-success" href="' . base_url() . 'index.php/os/editar/' . $result->idOs . '">
                            <span class="button__icon"><i class="bx bx-edit"></i> </span> <span class="button__text">Editar</span>
                        </a>';
                    } ?>

                    <div class="button-container">
                        <a target="_blank" title="Imprimir Ordem de Serviço" class="button btn btn-mini btn-inverse"> <span class="button__icon"><i class="bx bx-printer"></i></span><span class="button__text">Imprimir</span></a>
                        <div class="cascading-buttons">
                            <a target="_blank" title="Impressão em Papel A4" class="button btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/imprimir/<?php echo $result->idOs; ?>">
                                <span class="button__icon"><i class='bx bx-file'></i></span> <span class="button__text">Papel A4</span>
                            </a>
                            <a target="_blank" title="Impressão Cupom Não Fical" class="button btn btn-mini btn-inverse" href="<?php echo site_url() ?>/os/imprimirTermica/<?php echo $result->idOs; ?>">
                                <span class="button__icon"><i class='bx bx-receipt'></i></span> <span class="button__text">Cupom 80mm</span>
                            </a>
                            <?php if ($result->garantias_id) { ?>
                                <a target="_blank" title="Imprimir Termo de Garantia" class="button btn btn-mini btn-inverse" href="<?php echo site_url() ?>/garantias/imprimirGarantiaOs/<?php echo $result->garantias_id; ?>">
                                    <span class="button__icon"><i class="bx bx-paperclip"></i></span> <span class="button__text">Termo Garantia</span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vOs')) {
                        $this->load->model('os_model');
                        $zapnumber = preg_replace("/[^0-9]/", "", $result->celular_cliente);
                        
                        $totalCursos = 0;
                        if ($cursos) { foreach ($cursos as $c) { $totalCursos += $c->preco * ($c->quantidade ?: 1); } }
                        $totalViagens = 0;
                        if ($viagens) { foreach ($viagens as $v) { $totalViagens += $v->preco * ($v->quantidade ?: 1); } }

                        $troca = [$result->nomeCliente, $result->idOs, $result->status, 'R$ ' . ($result->desconto != 0 && $result->valor_desconto != 0 ? number_format($result->valor_desconto, 2, ',', '.') : number_format($totalProdutos + $totalServico + $totalCursos + $totalViagens, 2, ',', '.')), strip_tags($result->descricaoProduto), ($emitente ? $emitente->nome : ''), ($emitente ? $emitente->telefone : ''), strip_tags($result->observacoes), strip_tags($result->defeito), strip_tags($result->laudoTecnico), date('d/m/Y', strtotime($result->dataFinal)), date('d/m/Y', strtotime($result->dataInicial)), $result->garantia . ' dias'];
                        $texto_de_notificacao = $this->os_model->criarTextoWhats($texto_de_notificacao, $troca);
                        if (!empty($zapnumber)) {
                            echo '<a title="Enviar Por WhatsApp" class="button btn btn-mini btn-success" id="enviarWhatsApp" target="_blank" href="https://api.whatsapp.com/send?phone=55' . $zapnumber . '&text=' . $texto_de_notificacao . '">
                                <span class="button__icon"><i class="bx bxl-whatsapp"></i></span> <span class="button__text">WhatsApp</span>
                            </a>';
                        }
                    } ?>

                    <a title="Enviar OS por E-mail" class="button btn btn-mini btn-warning" href="<?php echo site_url() ?>/os/enviar_email/<?php echo $result->idOs; ?>">
                        <span class="button__icon"><i class="bx bx-envelope"></i></span> <span class="button__text">via E-mail</span>
                    </a>

                    <a href="#modal-gerar-pagamento" id="btn-forma-pagamento" role="button" data-toggle="modal" class="button btn btn-mini btn-primary">
                        <span class="button__icon"><i class='bx bx-dollar'></i></span><span class="button__text">Gerar Pagamento</span>
                    </a>

                    <?php if ($qrCode): ?>
                        <a href="#modal-pix" id="btn-pix" role="button" data-toggle="modal" class="button btn btn-mini btn-info">
                            <span class="button__icon"><i class='bx bx-qr'></i></span><span class="button__text">Chave PIX</span>
                        </a>
                    <?php endif ?>
                </div>
            </div>
            <div class="widget-content" id="printOs">
                <div class="invoice-content">
                    <div class="invoice-head" style="margin-bottom: 0; margin-top:-30px">
                        <table class="table table-condensed">
                            <tbody>
                                <?php if ($emitente == null) { ?>
                                    <tr>
                                        <td colspan="3" class="alert">Você precisa configurar os dados do emitente. >>><a href="<?php echo base_url(); ?>index.php/mapos/emitente">Configurar <<< </a></td>
                                    </tr>
                                <?php } ?>
                                <h3><i class='bx bx-file'></i> Ordem de Serviço #<?php echo sprintf('%04d', $result->idOs) ?></h3>
                            </tbody>
                        </table>
                        <table class="table table-condensend">
                            <tbody>
                                <tr>
                                    <td style="width: 60%; padding-left: 0">
                                        <span>
                                            <h5><b>CLIENTE</b></h5>
                                            <span><i class='bx bxs-business'></i> <b><?php echo $result->nomeCliente ?></b></span><br />
                                            <?php if (!empty($result->celular_cliente) || !empty($result->telefone_cliente) || !empty($result->contato_cliente)): ?>
                                                <span><i class='bx bxs-phone'></i>
                                                    <?= !empty($result->contato_cliente) ? $result->contato_cliente . ' ' : "" ?>
                                                    <?php if ($result->celular_cliente == $result->telefone_cliente) { ?>
                                                        <?= $result->celular_cliente ?>
                                                    <?php } else { ?>
                                                        <?= !empty($result->telefone_cliente) ? $result->telefone_cliente : "" ?>
                                                        <?= !empty($result->celular_cliente) && !empty($result->telefone_cliente) ? ' / ' : "" ?>
                                                        <?= !empty($result->celular_cliente) ? $result->celular_cliente : "" ?>
                                                    <?php } ?>
                                                </span></br>
                                            <?php endif; ?>
                                            <?php
                                            $retorno_end = array_filter([$result->rua, $result->numero, $result->complemento, $result->bairro . ' - ']);
                                            $endereco = implode(', ', $retorno_end);
                                            echo '<i class="fas fa-map-marker-alt"></i> ';
                                            if (!empty($endereco)) {
                                                echo $endereco;
                                            }
                                            if (!empty($result->cidade) || !empty($result->estado) || !empty($result->cep)) {
                                                echo "<span> {$result->cep}, {$result->cidade}/{$result->estado}</span><br>";
                                            }
                                            ?>
                                            <?php if (!empty($result->email)): ?>
                                                <span><i class="fas fa-envelope"></i>
                                                    <?php echo $result->email ?></span><br>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td style="width: 40%; padding-left: 0">
                                        <ul>
                                            <li>
                                                <span>
                                                    <h5><b>RESPONSÁVEL</b></h5>
                                                </span>
                                                <span><b><i class="fas fa-user"></i>
                                                        <?php echo $result->nome ?></b></span><br />
                                                <span><i class="fas fa-phone"></i>
                                                    <?php echo $result->telefone_usuario ?></span><br />
                                                <span><i class="fas fa-envelope"></i>
                                                    <?php echo $result->email_usuario ?></span>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div style="margin-top: 0; padding-top: 0">
                        <table class="table table-condensed">
                            <tbody>
                                <?php if ($result->dataInicial != null) { ?>
                                    <tr>
                                        <td>
                                            <b>STATUS OS: </b><br>
                                            <?php echo $result->status ?>
                                        </td>

                                        <td>
                                            <b>DATA INICIAL: </b><br>
                                            <?php echo date('d/m/Y', strtotime($result->dataInicial)); ?>
                                        </td>

                                        <td>
                                            <b>DATA FINAL: </b><br>
                                            <?php echo $result->dataFinal ? date('d/m/Y', strtotime($result->dataFinal)) : ''; ?>
                                        </td>

                                        <td>
                                            <?php if ($result->garantia) { ?>
                                                <b>GARANTIA: </b><br><?php echo $result->garantia . ' dia(s)'; ?>
                                            <?php } ?>
                                        </td>

                                        <?php if (in_array($result->status, ['Finalizado', 'Faturado', 'Orçamento', 'Aberto'])): ?>
                                            <td>
                                                <b>VENC. DA GARANTIA:</b><br>
                                                <?= dateInterval($result->dataFinal, $result->garantia); ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php } ?>

                                <?php if ($result->descricaoProduto != null) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <b>DESCRIÇÃO: </b>
                                            <?php echo htmlspecialchars_decode($result->descricaoProduto) ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if ($result->defeito != null) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <b>DEFEITO APRESENTADO: </b>
                                            <?php echo htmlspecialchars_decode($result->defeito) ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if ($result->observacoes != null) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <b>OBSERVAÇÕES: </b>
                                            <?php echo htmlspecialchars_decode($result->observacoes) ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if ($result->laudoTecnico != null) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <b>LAUDO TÉCNICO: </b>
                                            <?php echo htmlspecialchars_decode($result->laudoTecnico) ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if ($result->garantias_id != null) { ?>
                                    <tr>
                                        <td colspan="5">
                                            <strong>TERMO DE GARANTIA </strong><br>
                                            <?php echo htmlspecialchars_decode($result->textoGarantia) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <?php if ($anotacoes != null) { ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Anotação</th>
                                        <th>Data/Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($anotacoes as $a) {
                                        echo '<tr>';
                                        echo '<td>' . $a->anotacao . '</td>';
                                        echo '<td>' . date('d/m/Y H:i:s', strtotime($a->data_hora)) . '</td>';
                                        echo '</tr>';
                                    }
                                    if (!$anotacoes) {
                                        echo '<tr><td colspan="2">Nenhuma anotação cadastrada</td></tr>';
                                    } ?>
                                </tbody>
                            </table>
                        <?php } ?>

                        <?php if ($anexos != null) { ?>
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Anexo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <th colspan="5">
                                        <?php foreach ($anexos as $a) {
                                            if ($a->thumb == null) {
                                                $thumb = base_url() . 'assets/img/icon-file.png';
                                                $link = base_url() . 'assets/img/icon-file.png';
                                            } else {
                                                $thumb = $a->url . '/thumbs/' . $a->thumb;
                                                $link = $a->url . '/' . $a->anexo;
                                            }
                                            echo '<div class="span3" style="min-height: 150px; margin-left: 0"><a style="min-height: 150px;" href="#modal-anexo" imagem="' . $a->idAnexos . '" link="' . $link . '" role="button" class="btn anexo span12" data-toggle="modal"><img src="' . $thumb . '" alt=""></a></div>';
                                        } ?>
                                    </th>
                                </tbody>
                            </table>
                        <?php } ?>

                        <?php if ($produtos != null) { ?>
                            <br />
                            <table class="table table-bordered table-condensed" id="tblProdutos">
                                <thead>
                                    <tr>
                                        <th style="width: 55%">PRODUTO</th>
                                        <th style="width: 10%">QTD</th>
                                        <th style="width: 15%">UNT</th>
                                        <th style="width: 20%">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtos as $p) {
                                        echo '<tr>';
                                        echo '<td>' . $p->descricao . '</td>';
                                        echo '<td>' . $p->quantidade . '</td>';
                                        echo '<td>R$ ' . $p->preco ?: $p->precoVenda . '</td>';
                                        echo '<td>R$ ' . number_format($p->subTotal, 2, ',', '.') . '</td>';
                                        echo '</tr>';
                                    } ?>
                                    <tr>
                                        <td></td>
                                        <td colspan="2" style="text-align: right"><strong>TOTAL:</strong></td>
                                        <td><strong>R$ <?php echo number_format($totalProdutos, 2, ',', '.'); ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                        <?php if ($servicos != null) { ?>
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th style="width: 55%">SERVIÇO</th>
                                        <th style="width: 10%">QTD</th>
                                        <th style="width: 15%">UNT</th>
                                        <th style="width: 20%">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php setlocale(LC_MONETARY, 'en_US');
                                    foreach ($servicos as $s) {
                                        $preco = $s->preco ?: $s->precoVenda;
                                        $subtotal = $preco * ($s->quantidade ?: 1);
                                        echo '<tr>';
                                        echo '<td>' . $s->nome . '</td>';
                                        echo '<td>' . ($s->quantidade ?: 1) . '</td>';
                                        echo '<td>R$ ' . $preco . '</td>';
                                        echo '<td>R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                                        echo '</tr>';
                                    } ?>
                                    <tr>
                                        <td colspan="3" style="text-align: right"><strong>TOTAL:</strong></td>
                                        <td><strong>R$ <?php echo number_format($totalServico, 2, ',', '.'); ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                        <?php if ($cursos != null) { ?>
                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="width: 35%">CURSO</th>
                                    <th style="width: 15%">DATA INÍCIO</th>
                                    <th style="width: 15%">DATA FIM</th>
                                    <th style="width: 5%">QTD</th>
                                    <th style="width: 15%">UNT</th>
                                    <th style="width: 15%">SUBTOTAL</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalCursos = 0;
                                    foreach ($cursos as $c) {
                                        $totalCursos += $c->preco;
                                        $subtotal = $c->preco * 1;
                                        echo '<tr>';
                                        echo '<td>' . $c->nome . '</td>';
                                        echo '<td>' . ($c->data_inicio ? date('d/m/Y', strtotime($c->data_inicio)) : '') . '</td>';
                                        echo '<td>' . ($c->data_fim ? date('d/m/Y', strtotime($c->data_fim)) : '') . '</td>';
                                        echo '<td>1</td>';
                                        echo '<td>R$ ' . number_format($c->preco, 2, ',', '.') . '</td>';
                                        echo '<td>R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="5" style="text-align: right"><strong>TOTAL:</strong></td>
                                        <td><strong>R$ <?php echo number_format($totalCursos, 2, ',', '.'); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                        <?php if ($viagens != null) { ?>
                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="width: 35%">VIAGEM</th>
                                    <th style="width: 15%">DATA PARTIDA</th>
                                    <th style="width: 15%">DATA RETORNO</th>
                                    <th style="width: 5%">QTD</th>
                                    <th style="width: 15%">UNT</th>
                                    <th style="width: 15%">SUBTOTAL</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalViagens = 0;
                                    foreach ($viagens as $v) {
                                        $totalViagens += $v->preco;
                                        $subtotal = $v->preco * 1;
                                        echo '<tr>';
                                        echo '<td>' . $v->nome . '</td>';
                                        echo '<td>' . ($v->data_partida ? date('d/m/Y', strtotime($v->data_partida)) : '') . '</td>';
                                        echo '<td>' . ($v->data_retorno ? date('d/m/Y', strtotime($v->data_retorno)) : '') . '</td>';
                                        echo '<td>1</td>';
                                        echo '<td>R$ ' . number_format($v->preco, 2, ',', '.') . '</td>';
                                        echo '<td>R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="5" style="text-align: right"><strong>TOTAL:</strong></td>
                                        <td><strong>R$ <?php echo number_format($totalViagens, 2, ',', '.'); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                        <table class="table table-bordered table-condensed">
                            <?php 
                            $totalCursos = 0;
                            if ($cursos) {
                                foreach ($cursos as $c) {
                                    $totalCursos += $c->preco * ($c->quantidade ?: 1);
                                }
                            }
                            $totalViagens = 0;
                            if ($viagens) {
                                foreach ($viagens as $v) {
                                    $totalViagens += $v->preco * ($v->quantidade ?: 1);
                                }
                            }
                            if ($totalProdutos != 0 || $totalServico != 0 || $totalCursos != 0 || $totalViagens != 0) {
                                if ($result->valor_desconto != 0) {
                                    echo "<td>
                                            <h4 style='text-align: right'>SUBTOTAL: R$ " . number_format($totalProdutos + $totalServico + $totalCursos + $totalViagens, 2, ',', '.') . "</h4>
                                            <h4 style='text-align: right'>DESCONTO: R$ " . number_format($result->desconto, 2, ',', '.') . "</h4>
                                            <h4 style='text-align: right'>TOTAL: R$ " . number_format($result->valor_desconto, 2, ',', '.') . "</h4>
                                          </td>";
                                } else {
                                    echo "<td>
                                            <h4 style='text-align: right'>TOTAL: R$ " . number_format($totalProdutos + $totalServico + $totalCursos + $totalViagens, 2, ',', '.') . "</h4>
                                          </td>";
                                }
                            } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $modalGerarPagamento ?>

<!-- Modal Faturar-->
<div id="modal-faturar" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formFaturar" action="<?php echo base_url() ?>index.php/os/faturar" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Faturar OS</h3>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
            <div class="span12" style="margin-left: 0">
                <label for="descricao">Descrição</label>
                <input class="span12" id="descricao" type="text" name="descricao" value="Fatura de OS Nº: <?php echo $result->idOs; ?> " />
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span12" style="margin-left: 0">
                    <label for="cliente">Cliente*</label>
                    <input class="span12" id="cliente" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                    <input type="hidden" name="clientes_id" id="clientes_id" value="<?php echo $result->clientes_id ?>">
                    <input type="hidden" name="os_id" id="os_id" value="<?php echo $result->idOs; ?>">
                    <input type="hidden" name="tipoDesconto" id="tipoDesconto" value="<?php echo $result->tipo_desconto; ?>">
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span6" style="margin-left: 0">
                    <label for="valor">Valor*</label>
                    <input type="hidden" id="tipo" name="tipo" value="receita" />
                    <input class="span12 money" id="valor" type="text" data-affixes-stay="true" data-thousands="" data-decimal="." name="valor" value="<?php echo number_format($totalProdutos + $totalServico + $totalCursos + $totalViagens, 2, '.', ''); ?>" />
                </div>
                <div class="span6" style="margin-left: 2;">
                    <label for="valor">Valor Com Desconto*</label>
                    <input class="span12 money" id="faturar-desconto" type="text" name="faturar-desconto" value="<?php echo number_format($result->valor_desconto, 2, '.', ''); ?> " />
                    <strong><span style="color: red" id="resultado"></span></strong>
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="vencimento">Data Entrada*</label>
                    <input class="span12 datepicker" autocomplete="off" id="vencimento" type="text" name="vencimento" />
                </div>
                <div class="span4">
                    <label for="qtd_parcelas">Qtd Parcelas</label>
                    <input class="span12" id="qtd_parcelas" type="number" name="qtd_parcelas" value="1" />
                </div>
                <div class="span4">
                    <label for="entrada">Entrada</label>
                    <input class="span12 money" id="entrada" type="text" name="entrada" value="0,00" />
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="recebido">Recebido?</label>
                    &nbsp &nbsp &nbsp &nbsp <input id="recebido" type="checkbox" name="recebido" value="1" />
                </div>
                <div id="divRecebimento" class="span8" style=" display: none">
                    <div class="span6">
                        <label for="recebimento">Data Recebimento</label>
                        <input class="span12 datepicker" autocomplete="off" id="recebimento" type="text" name="recebimento" />
                    </div>
                    <div class="span6">
                        <label for="formaPgto">Forma Pgto</label>
                        <select name="formaPgto" id="formaPgto" class="span12">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Cartão de Débito">Cartão de Débito</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Pix">Pix</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true" id="btn-cancelar-faturar"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-dollar'></i></span> <span class="button__text2">Faturar</span></button>
        </div>
    </form>
</div>

<!-- Modal Cancelar Faturamento -->
<div id="modal-cancelar-faturamento" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/os/cancelar_faturamento" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Cancelar Faturamento</h3>
        </div>
        <div class="modal-body">
            <input type="hidden" name="idOs" value="<?php echo $result->idOs; ?>" />
            <h5 style="text-align: center">Deseja realmente cancelar o faturamento desta OS?</h5>
            <p style="text-align: center">Isso excluirá os lançamentos financeiros associados e reabrirá a OS.</p>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Confirmar</span></button>
        </div>
    </form>
</div>

<!-- Modal visualizar anexo -->
<div id="modal-anexo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Visualizar Anexo</h3>
    </div>
    <div class="modal-body">
        <div class="span12" id="div-visualizar-anexo" style="text-align: center">
            <div class='progress progress-info progress-striped active'>
                <div class='bar' style='width: 100%'></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
        <a href="" id-imagem="" class="btn btn-inverse" id="download">Download</a>
        <a href="" link="" class="btn btn-danger" id="excluir-anexo">Excluir Anexo</a>
    </div>
</div>

<!-- Modal PIX -->
<div id="modal-pix" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Pagamento via PIX</h3>
    </div>
    <div class="modal-body">
        <div class="span12" id="div-pix" style="text-align: center">
            <td style="width: 15%; padding: 0;text-align:center;">
                <img src="<?php echo base_url(); ?>assets/img/logo_pix.png" alt="QR Code de Pagamento" /></br>
                <img id="qrCodeImage" width="50%" src="<?= $qrCode ?>" alt="QR Code de Pagamento" /></br>
                <?php echo '<span>Chave PIX: ' . $chaveFormatada . '</span>'; ?></br>
                <?php
                $totalCursos = 0;
                if ($cursos) { foreach ($cursos as $c) { $totalCursos += $c->preco * ($c->quantidade ?: 1); } }
                $totalViagens = 0;
                if ($viagens) { foreach ($viagens as $v) { $totalViagens += $v->preco * ($v->quantidade ?: 1); } }
                $totalGeral = $totalProdutos + $totalServico + $totalCursos + $totalViagens;
                if ($totalGeral != 0) {
                    if ($result->valor_desconto != 0) {
                        echo "Valor Total: R$ " . number_format($result->valor_desconto, 2, ',', '.');
                    } else {
                        echo "Valor Total: R$ " . number_format($totalGeral, 2, ',', '.');
                    }
                } ?>
            </td>
        </div>
    </div>
    <div class="modal-footer">
        <?php if (isset($zapnumber) && !empty($zapnumber)) {
            echo "<button id='pixWhatsApp' class='btn btn-success' data-dismiss='modal' aria-hidden='true' style='color: #FFF'><i class='bx bxl-whatsapp'></i> WhatsApp</button>";
        } ?>
        <button class="btn btn-primary" id="copyButton" style="margin:5px; color: #FFF"><i class="fas fa-copy"></i> Copia e Cola</button>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true" style="color: #FFF">Fechar</button>
    </div>
</div>
<script src="https://cdn.rawgit.com/cozmo/jsQR/master/dist/jsQR.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.anexo', function(event) {
            event.preventDefault();
            var link = $(this).attr('link');
            var id = $(this).attr('imagem');
            var url = '<?php echo base_url(); ?>index.php/os/excluirAnexo/';
            $("#div-visualizar-anexo").html('<img src="' + link + '" alt="">');
            $("#excluir-anexo").attr('link', url + id);
            $("#download").attr('href', "<?php echo base_url(); ?>index.php/os/downloadanexo/" + id);

        });

        $(document).on('click', '#excluir-anexo', function(event) {
            event.preventDefault();

            var link = $(this).attr('link');
            var idOS = "<?php echo $result->idOs; ?>"

            $('#modal-anexo').modal('hide');
            $("#divAnexos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");

            $.ajax({
                type: "POST",
                url: link,
                dataType: 'json',
                data: "idOs=" + idOS,
                success: function(data) {
                    if (data.result == true) {
                        $("#divAnexos").load("<?php echo current_url(); ?> #divAnexos");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Atenção",
                            text: data.mensagem
                        });
                    }
                }
            });
        });

        $(".money").maskMoney();

        $('#recebido').click(function(event) {
            var flag = $(this).is(':checked');
            if (flag == true) {
                $('#divRecebimento').show();
            } else {
                $('#divRecebimento').hide();
            }
        });

        $("#formFaturar").validate({
            rules: {
                descricao: { required: true },
                cliente: { required: true },
                valor: { required: true },
                vencimento: { required: true }
            },
            messages: {
                descricao: { required: 'Campo Requerido.' },
                cliente: { required: 'Campo Requerido.' },
                valor: { required: 'Campo Requerido.' },
                vencimento: { required: 'Campo Requerido.' }
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();
                var qtdProdutos = <?= count($produtos) ?>;
                var qtdServicos = <?= count($servicos) ?>;
                var qtdCursos = <?= count($cursos) ?>;
                var qtdViagens = <?= count($viagens) ?>;
                var qtdTotal = qtdProdutos + qtdServicos + qtdCursos + qtdViagens;

                $('#btn-cancelar-faturar').trigger('click');

                if (qtdTotal <= 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Atenção",
                        text: "Não é possível faturar uma OS sem serviços, produtos, cursos ou viagens"
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/os/faturar",
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            if (data.result == true) {
                                window.location.reload(true);
                            } else {
                                Swal.fire({ icon: "error", title: "Atenção", text: "Ocorreu um erro ao tentar faturar OS." });
                            }
                        }
                    });
                    return false;
                }
            }
        });
        $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
    });

    $('#copyButton').on('click', function() {
        var $qrCodeImage = $('#qrCodeImage');
        var canvas = document.createElement('canvas');
        canvas.width = $qrCodeImage.width();
        canvas.height = $qrCodeImage.height();
        var context = canvas.getContext('2d');
        context.drawImage($qrCodeImage[0], 0, 0, $qrCodeImage.width(), $qrCodeImage.height());
        var imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height);
        if (code) {
            navigator.clipboard.writeText(code.data).then(function() {
                $('#modal-pix').modal('hide');
                Swal.fire({
                    icon: "success",
                    title: "Sucesso!",
                    text: "QR Code copiado com sucesso: " + code.data,
                    timer: 3000,
                    showConfirmButton: false,
                });

            }).catch(function(err) {
                Swal.fire({
                    icon: "error",
                    title: "Atenção",
                    text: "Erro ao copiar QR Code: ",
                    err
                });
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Não foi possível decodificar o QR Code.",
            });
        }
    });

    $('#pixWhatsApp').on('click', function() {
        var $qrCodeImage = $('#qrCodeImage');
        var canvas = document.createElement('canvas');
        canvas.width = $qrCodeImage.width();
        canvas.height = $qrCodeImage.height();
        var context = canvas.getContext('2d');
        context.drawImage($qrCodeImage[0], 0, 0, $qrCodeImage.width(), $qrCodeImage.height());
        var imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height);
        if (code) {
            var whatsappLink = 'https://api.whatsapp.com/send?phone=55' + <?= isset($zapnumber) ? $zapnumber : "" ?> + '&text=' + code.data;
            window.open(whatsappLink, '_blank');
        } else {
            Swal.fire({
                icon: "error",
                title: "Atenção",
                text: "Não foi possível decodificar o QR Code.",
            });
        }
    });
</script>
