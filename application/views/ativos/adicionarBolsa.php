<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-box"></i>
                </span>
                <h5>Cadastro de Bolsa/Caixa</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formBolsa" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo set_value('nome'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="codigo_identificador" class="control-label">Código Identificador<span class="required">*</span></label>
                        <div class="controls">
                            <input id="codigo_identificador" type="text" name="codigo_identificador" value="<?php echo set_value('codigo_identificador'); ?>" />
                            <button id="gerarCodigo" type="button" class="btn btn-inverse btn-mini" style="margin-left: 5px;">Gerar</button>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="status" class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="estoque">Estoque</option>
                                <option value="viagem">Viagem</option>
                                <option value="manutencao">Manutenção</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="responsavel_tipo" class="control-label">Tipo de Responsável</label>
                        <div class="controls">
                            <select name="responsavel_tipo" id="responsavel_tipo">
                                <option value="">Selecione...</option>
                                <option value="cliente">Cliente</option>
                                <option value="usuario">Usuário</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group" id="divResponsavel" style="display:none;">
                        <label for="responsavel" class="control-label">Responsável</label>
                        <div class="controls">
                            <input id="responsavel" type="text" name="responsavel" value="" placeholder="Digite o nome..." />
                            <input id="responsavel_id" type="hidden" name="responsavel_id" value="" />
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
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#formBolsa').validate({
            rules: { nome: { required: true }, codigo_identificador: { required: true } },
            messages: { nome: { required: 'Campo Requerido.' }, codigo_identificador: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });

        $('#responsavel_tipo').change(function() {
            var tipo = $(this).val();
            if (tipo) {
                $('#divResponsavel').show();
                $('#responsavel').val('');
                $('#responsavel_id').val('');
                $('#responsavel').focus();
            } else {
                $('#divResponsavel').hide();
                $('#responsavel').val('');
                $('#responsavel_id').val('');
            }
        });

        $("#responsavel").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/ativos/autoCompleteResponsavel",
                    dataType: "json",
                    data: { term: request.term, tipo: $('#responsavel_tipo').val() },
                    success: function(data) { response(data); }
                });
            },
            minLength: 1,
            select: function(event, ui) { $("#responsavel_id").val(ui.item.id); }
        });

        $('#gerarCodigo').click(function() {
            var nome = $('#nome').val();
            if (nome == '') {
                alert('Por favor, preencha o campo Nome primeiro.');
                $('#nome').focus();
                return;
            }
            var prefixo = nome.substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, 'X');
            var sufixo = Date.now().toString().slice(-6);
            $('#codigo_identificador').val(prefixo + '-' + sufixo);
        });
    });
</script>