<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-map-marked-alt"></i>
                </span>
                <h5>Cadastro de Expedição</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formExpedicao" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="titulo" class="control-label">Título<span class="required">*</span></label>
                        <div class="controls">
                            <input id="titulo" type="text" name="titulo" value="<?php echo set_value('titulo'); ?>" class="span8" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="moeda_base" class="control-label">Moeda Base</label>
                        <div class="controls">
                            <select name="moeda_base" id="moeda_base">
                                <option value="BRL">BRL (R$)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="data_ida" class="control-label">Data Ida<span class="required">*</span></label>
                        <div class="controls">
                            <input id="data_ida" type="datetime-local" name="data_ida" value="<?php echo set_value('data_ida'); ?>" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="data_volta" class="control-label">Data Volta<span class="required">*</span></label>
                        <div class="controls">
                            <input id="data_volta" type="datetime-local" name="data_volta" value="<?php echo set_value('data_volta'); ?>" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="taxa_parque_unitaria" class="control-label">Taxa Parque (Unit)</label>
                        <div class="controls">
                            <input id="taxa_parque_unitaria" type="text" name="taxa_parque_unitaria" value="<?php echo set_value('taxa_parque_unitaria'); ?>" class="money" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="preco_saida_barco_dia" class="control-label">Preço Barco (Dia)</label>
                        <div class="controls">
                            <input id="preco_saida_barco_dia" type="text" name="preco_saida_barco_dia" value="<?php echo set_value('preco_saida_barco_dia'); ?>" class="money" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Regra Cancelamento</label>
                        <div class="controls">
                            <input type="number" name="cancelamento_limite" value="24" class="span1" />
                            <select name="cancelamento_tipo" class="span2">
                                <option value="horas">Horas antes</option>
                                <option value="dias">Dias antes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar</button>
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
    });
</script>