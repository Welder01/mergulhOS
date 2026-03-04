<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-box"></i>
                </span>
                <h5>Cadastro de Ativo</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formAtivo" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo set_value('nome'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="patrimonio" class="control-label">Patrimônio<span class="required">*</span></label>
                        <div class="controls">
                            <input id="patrimonio" type="text" name="patrimonio" value="<?php echo set_value('patrimonio'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="status" class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="disponivel">Disponível</option>
                                <option value="em_uso">Em Uso</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="baixado">Baixado</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar</button>
                                <a href="<?php echo base_url() ?>index.php/ativos" id="" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
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
        $('#formAtivo').validate({
            rules: { nome: { required: true }, patrimonio: { required: true } },
            messages: { nome: { required: 'Campo Requerido.' }, patrimonio: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });
    });
</script>