<div class="widget-box">
    <div class="widget-title">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">Dados do Bilhete</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <div id="tab1" class="tab-pane active">
            <div class="accordion" id="collapse-group">
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                                <h5>Detalhes do Bilhete #<?php echo $result->idBilhete; ?></h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseGOne">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%"><strong>Cliente</strong></td>
                                        <td><?php echo $result->nomeCliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Expedição</strong></td>
                                        <td><?php echo $result->expedicao; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Data Ida</strong></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($result->data_ida)); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Data Volta</strong></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($result->data_volta)); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Transporte</strong></td>
                                        <td><?php echo ucfirst(str_replace('_', ' ', $result->tipo_transporte)); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Empresa</strong></td>
                                        <td><?php echo $result->empresa_emissora; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Código/Localizador</strong></td>
                                        <td><?php echo $result->codigo_bilhete; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Assento</strong></td>
                                        <td><?php echo $result->assento; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Status</strong></td>
                                        <td><span class="label <?php echo ($result->status == 'ativo' ? 'label-success' : 'label-important'); ?>"><?php echo ucfirst($result->status); ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($equipamentos)) { ?>
            <div class="accordion" id="collapse-group2">
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group2" href="#collapseGTwo" data-toggle="collapse">
                                <span class="icon"><i class="fas fa-box"></i></span>
                                <h5>Equipamentos Alugados</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseGTwo">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Patrimônio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($equipamentos as $e) { ?>
                                    <tr>
                                        <td><?php echo $e->nome; ?></td>
                                        <td><?php echo $e->patrimonio; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="form-actions">
                <div class="span12">
                    <div class="span6 offset3">
                        <a href="<?php echo base_url() ?>index.php/bilhetagem" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
                        <a href="<?php echo base_url() ?>index.php/bilhetagem/imprimirVoucher/<?php echo $result->idBilhete; ?>" target="_blank" class="btn btn-inverse"><i class="fas fa-print"></i> Imprimir Voucher</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>