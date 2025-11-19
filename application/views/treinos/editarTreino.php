<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon"><i class="fas fa-dumbbell"></i></span>
                <h5>Editar Configuração de Treino</h5>
            </div>
            <div class="widget-content nopadding tab-content">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formTreino" method="post" class="form-horizontal">
                    <?php echo form_hidden('id', $result->id) ?>

                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo $result->nome; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="descricao" class="control-label">Descrição</label>
                        <div class="controls">
                            <textarea id="descricao" name="descricao" rows="3"><?php echo $result->descricao; ?></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="duracao_minutos" class="control-label">Duração (minutos)<span class="required">*</span></label>
                        <div class="controls">
                            <input id="duracao_minutos" type="number" name="duracao_minutos" value="<?php echo $result->duracao_minutos; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="preco_sem_instrutor" class="control-label">Preço Sem Instrutor<span class="required">*</span></label>
                        <div class="controls">
                            <input id="preco_sem_instrutor" class="money" type="text" name="preco_sem_instrutor" value="<?php echo number_format($result->preco_sem_instrutor, 2, ',', '.'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="preco_com_instrutor" class="control-label">Preço Com Instrutor<span class="required">*</span></label>
                        <div class="controls">
                            <input id="preco_com_instrutor" class="money" type="text" name="preco_com_instrutor" value="<?php echo number_format($result->preco_com_instrutor, 2, ',', '.'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Dias Disponíveis<span class="required">*</span></label>
                        <div class="controls">
                            <?php $dias = explode(',', $result->dias_semana_disponiveis); ?>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="1" <?php echo in_array('1', $dias) ? 'checked' : ''; ?>> Segunda-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="2" <?php echo in_array('2', $dias) ? 'checked' : ''; ?>> Terça-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="3" <?php echo in_array('3', $dias) ? 'checked' : ''; ?>> Quarta-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="4" <?php echo in_array('4', $dias) ? 'checked' : ''; ?>> Quinta-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="5" <?php echo in_array('5', $dias) ? 'checked' : ''; ?>> Sexta-feira</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="6" <?php echo in_array('6', $dias) ? 'checked' : ''; ?>> Sábado</label>
                            <label><input type="checkbox" name="dias_semana_disponiveis[]" value="0" <?php echo in_array('0', $dias) ? 'checked' : ''; ?>> Domingo</label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="horario_inicio" class="control-label">Horário de Início<span class="required">*</span></label>
                        <div class="controls">
                            <input id="horario_inicio" type="time" name="horario_inicio" value="<?php echo $result->horario_inicio; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="horario_fim" class="control-label">Horário de Fim<span class="required">*</span></label>
                        <div class="controls">
                            <input id="horario_fim" type="time" name="horario_fim" value="<?php echo $result->horario_fim; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Status</label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="1" <?php echo $result->status == 1 ? 'selected' : ''; ?>>Ativo</option>
                                <option value="0" <?php echo $result->status == 0 ? 'selected' : ''; ?>>Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-primary"><span class="button__icon"><i class="bx bx-save"></i></span><span class="button__text2">Salvar</span></button>
                                <a href="<?php echo base_url() ?>index.php/treinos" class="button btn btn-mini btn-warning"><span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".money").maskMoney({ decimal: ",", thousands: "." });
    });
</script>