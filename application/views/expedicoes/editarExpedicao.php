<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-map-marked-alt"></i>
                </span>
                <h5>Editar Expedição</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formExpedicao" method="post" class="form-horizontal">
                    <?php echo form_hidden('idExpedicao', $result->idExpedicao) ?>
                    
                    <div class="control-group">
                        <label for="titulo" class="control-label">Título<span class="required">*</span></label>
                        <div class="controls">
                            <input id="titulo" type="text" name="titulo" value="<?php echo $result->titulo; ?>" class="span8" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="moeda_base" class="control-label">Moeda Base</label>
                        <div class="controls">
                            <select name="moeda_base" id="moeda_base">
                                <option value="BRL" <?= ($result->moeda_base == 'BRL') ? 'selected' : '' ?>>BRL (R$)</option>
                                <option value="USD" <?= ($result->moeda_base == 'USD') ? 'selected' : '' ?>>USD ($)</option>
                                <option value="EUR" <?= ($result->moeda_base == 'EUR') ? 'selected' : '' ?>>EUR (€)</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="transporte" class="control-label">Transportes</label>
                        <div class="controls">
                            <input id="transporte" type="text" class="span8" placeholder="Digite para adicionar transportes..." />
                            <div id="listaTransportes" style="margin-top: 10px;">
                                <?php foreach ($transportes as $t) { ?>
                                    <div id="transporte_<?php echo $t->idTransporte; ?>" style="margin-bottom: 5px;">
                                        <input type="hidden" name="transportes_id[]" value="<?php echo $t->idTransporte; ?>" />
                                        <span class="label label-info" style="font-size: 12px; padding: 5px;"><?php echo $t->nome . ' (' . $t->qtd_assentos . ' lug.)'; ?> <i class="fas fa-times" style="cursor: pointer;" onclick="$(this).parent().parent().remove();"></i></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="data_ida" class="control-label">Data Ida<span class="required">*</span></label>
                        <div class="controls">
                            <input id="data_ida" type="datetime-local" name="data_ida" value="<?php echo date('Y-m-d\TH:i', strtotime($result->data_ida)); ?>" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="data_volta" class="control-label">Data Volta<span class="required">*</span></label>
                        <div class="controls">
                            <input id="data_volta" type="datetime-local" name="data_volta" value="<?php echo date('Y-m-d\TH:i', strtotime($result->data_volta)); ?>" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="taxa_parque_unitaria" class="control-label">Taxa Parque (Unit)</label>
                        <div class="controls">
                            <input id="taxa_parque_unitaria" type="text" name="taxa_parque_unitaria" value="<?php echo $result->taxa_parque_unitaria; ?>" class="money" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="preco_saida_barco_dia" class="control-label">Preço Barco (Dia)</label>
                        <div class="controls">
                            <input id="preco_saida_barco_dia" type="text" name="preco_saida_barco_dia" value="<?php echo $result->preco_saida_barco_dia; ?>" class="money" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Regra Cancelamento</label>
                        <div class="controls">
                            <input type="number" name="cancelamento_limite" value="<?php echo $result->cancelamento_limite; ?>" class="span1" />
                            <select name="cancelamento_tipo" class="span2">
                                <option value="horas" <?= ($result->cancelamento_tipo == 'horas') ? 'selected' : '' ?>>Horas antes</option>
                                <option value="dias" <?= ($result->cancelamento_tipo == 'dias') ? 'selected' : '' ?>>Dias antes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                                <a href="<?php echo base_url() ?>index.php/expedicoes" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#formExpedicao').validate({
            rules: { titulo: { required: true }, data_ida: { required: true }, data_volta: { required: true } },
            messages: { titulo: { required: 'Campo Requerido.' }, data_ida: { required: 'Campo Requerido.' }, data_volta: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });

        $("#transporte").autocomplete({
            source: "<?php echo base_url(); ?>index.php/transportes/autoComplete",
            minLength: 1,
            select: function(event, ui) {
                if ($('#transporte_' + ui.item.id).length == 0) {
                    var html = '<div id="transporte_' + ui.item.id + '" style="margin-bottom: 5px;">' +
                               '<input type="hidden" name="transportes_id[]" value="' + ui.item.id + '" />' +
                               '<span class="label label-info" style="font-size: 12px; padding: 5px;">' + ui.item.label + ' <i class="fas fa-times" style="cursor: pointer;" onclick="$(this).parent().parent().remove();"></i></span></div>';
                    $('#listaTransportes').append(html);
                }
                $(this).val('');
                return false;
            }
        });
    });
</script>