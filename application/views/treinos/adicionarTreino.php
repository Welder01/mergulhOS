<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-dumbbell"></i></span>
                <h5>Nova Configuração de Treino</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formTreino" method="post" class="form-horizontal">

                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo set_value('nome'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="descricao" class="control-label">Descrição</label>
                        <div class="controls">
                            <textarea id="descricao" name="descricao" rows="3"><?php echo set_value('descricao'); ?></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="duracao_minutos" class="control-label">Duração (minutos)<span class="required">*</span></label>
                        <div class="controls">
                            <input id="duracao_minutos" type="number" name="duracao_minutos" value="60" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="limite_vagas" class="control-label">Vagas por Horário</label>
                        <div class="controls">
                            <input id="limite_vagas" type="number" name="limite_vagas" value="<?php echo set_value('limite_vagas', 10); ?>" />
                            <span class="help-inline">Máximo de alunos por horário de treino. Deixe em branco para ilimitado.</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="limite_alunos_instrutor" class="control-label">Alunos por Instrutor</label>
                        <div class="controls">
                            <input id="limite_alunos_instrutor" type="number" name="limite_alunos_instrutor" value="<?php echo set_value('limite_alunos_instrutor', 2); ?>" />
                            <span class="help-inline">Máximo de alunos que um instrutor pode supervisionar.</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="cancelamento_limite_dias" class="control-label">Prazo para Cancelamento</label>
                        <div class="controls">
                            <input id="cancelamento_limite_dias" type="number" name="cancelamento_limite_dias" value="<?php echo set_value('cancelamento_limite_dias', 1); ?>" style="width: 50px;" /> Dias e 
                            <input id="cancelamento_limite_horas" type="number" name="cancelamento_limite_horas" value="<?php echo set_value('cancelamento_limite_horas', 0); ?>" style="width: 50px;" /> Horas antes do início.
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="instrutores" class="control-label">Instrutores</label>
                        <div class="controls">
                            <input id="instrutores_input" type="text" name="instrutores_input" placeholder="Digite para buscar..." />
                            <div id="instrutores_selecionados" style="margin-top: 5px;"></div>
                            <input id="instrutores_ids" type="hidden" name="instrutores_ids" value="" />
                        </div>
                    </div>


                    <div class="control-group">
                        <label for="preco_sem_instrutor" class="control-label">Preço Sem Instrutor<span class="required">*</span></label>
                        <div class="controls">
                            <input id="preco_sem_instrutor" class="money" type="text" name="preco_sem_instrutor" value="<?php echo set_value('preco_sem_instrutor'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="preco_com_instrutor" class="control-label">Preço Com Instrutor<span class="required">*</span></label>
                        <div class="controls">
                            <input id="preco_com_instrutor" class="money" type="text" name="preco_com_instrutor" value="<?php echo set_value('preco_com_instrutor'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Dias Disponíveis<span class="required">*</span></label>
                        <div class="controls">
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="1"> Segunda-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="2"> Terça-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="3"> Quarta-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="4"> Quinta-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="5"> Sexta-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="6"> Sábado</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="0"> Domingo</label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="horario_inicio" class="control-label">Horário de Início<span class="required">*</span></label>
                        <div class="controls">
                            <input id="horario_inicio" type="time" name="horario_inicio" value="08:00" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="horario_fim" class="control-label">Horário de Fim<span class="required">*</span></label>
                        <div class="controls">
                            <input id="horario_fim" type="time" name="horario_fim" value="18:00" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Status</label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-success"><span class="button__icon"><i class="bx bx-plus-circle"></i></span><span class="button__text2">Adicionar</span></button>
                                <a href="<?php echo base_url() ?>index.php/treinos" class="button btn btn-mini btn-warning"><span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
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
        $(".money").maskMoney({decimal:",", thousands:"."});

        $('#formTreino').validate({
            // Suas regras de validação aqui
            // Exemplo:
        });

        $("#instrutores_input").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/treinos/autoCompleteInstrutores",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                var instrutores_ids = $("#instrutores_ids").val().split(',');
                if (instrutores_ids.indexOf(ui.item.id) == -1) {
                    $('#instrutores_selecionados').append('<div class="instrutor_tag" style="display: inline-block; background: #eee; padding: 5px; margin: 2px; border-radius: 5px;">' + ui.item.label + ' <a href="#" data-id="' + ui.item.id + '" class="remover_instrutor" style="color: red; text-decoration: none;">&times;</a></div>');
                    
                    if ($("#instrutores_ids").val() != "") {
                        $("#instrutores_ids").val($("#instrutores_ids").val() + ',' + ui.item.id);
                    } else {
                        $("#instrutores_ids").val(ui.item.id);
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: 'Este instrutor já foi adicionado.'
                    });
                }
                $("#instrutores_input").val('');
                return false;
            }
        });

        $(document).on('click', '.remover_instrutor', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var instrutores_ids = $("#instrutores_ids").val().split(',');
            var new_ids = instrutores_ids.filter(function(item) { return item != id; });
            $("#instrutores_ids").val(new_ids.join(','));
            $(this).parent().remove();
        });
    });
</script>