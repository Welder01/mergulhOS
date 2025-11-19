<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-dumbbell"></i></span>
                <h5>Nova Configuração de Treino</h5>
            </div>
            <div class="widget-content nopadding tab-content">
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
        // Adicione aqui as regras de validação do formulário com jQuery Validate
    });
</script>