<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-bus"></i>
                </span>
                <h5>Cadastro de Transporte</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formTransporte" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome <span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo set_value('nome'); ?>" class="span8" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="tipo" class="control-label">Tipo <span class="required">*</span></label>
                        <div class="controls">
                            <select name="tipo" id="tipo">
                                <option value="onibus">Ônibus</option>
                                <option value="van">Van</option>
                                <option value="aviao">Avião</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="categoria" class="control-label">Categoria</label>
                        <div class="controls">
                            <input id="categoria" type="text" name="categoria" value="<?php echo set_value('categoria'); ?>" placeholder="Ex: Leito, Executivo, Convencional" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="qtd_assentos" class="control-label">Qtd. Assentos <span class="required">*</span></label>
                        <div class="controls">
                            <input id="qtd_assentos" type="number" name="qtd_assentos" value="<?php echo set_value('qtd_assentos'); ?>" required />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="placa" class="control-label">Placa/Identificação</label>
                        <div class="controls">
                            <input id="placa" type="text" name="placa" value="<?php echo set_value('placa'); ?>" />
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar</button>
                                <a href="<?php echo base_url() ?>index.php/transportes" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
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
        $('#formTransporte').validate({
            rules: { nome: { required: true }, qtd_assentos: { required: true } },
            messages: { nome: { required: 'Campo Requerido.' }, qtd_assentos: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });
    });
</script>
