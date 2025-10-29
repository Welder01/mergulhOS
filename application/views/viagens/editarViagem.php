<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.ui.datepicker-pt-BR.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/funcoes.js"></script>

<style>
    .curso-tag {
        display: inline-block;
        padding: 4px 8px;
        margin: 2px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 13px;
    }
    .curso-tag .remove-tag {
        margin-left: 8px;
        color: #d9534f;
        cursor: pointer;
        font-weight: bold;
    }
    .curso-tag .remove-tag:hover {
        color: #c9302c;
    }
</style>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-route"></i>
                </span>
                <h5>Editar Viagem</h5>
            </div>
            <?php if (isset($custom_error) && $custom_error != '') : ?>
                <div class="alert alert-danger"><?= $custom_error ?></div>
            <?php endif; ?>
            <form action="<?= current_url(); ?>" id="formViagem" method="post" class="form-horizontal">
                <div class="widget-content nopadding tab-content">
                    <div class="span12">
                        <?= form_hidden('id', $result->id) ?>
                        <div class="control-group">
                            <label for="nome_viagem" class="control-label">Nome da Viagem<span class="required">*</span></label>
                            <div class="controls">
                                <input id="nome_viagem" type="text" name="nome_viagem" value="<?= html_escape($result->nome_viagem); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="descricao" class="control-label">Descrição</label>
                            <div class="controls">
                                <textarea id="descricao" name="descricao" rows="5"><?= html_escape($result->descricao); ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_partida" class="control-label">Data da Partida</label>
                            <div class="controls">
                                <input id="data_partida" type="text" name="data_partida" value="<?= $result->data_partida ? date('d/m/Y', strtotime($result->data_partida)) : ''; ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_retorno" class="control-label">Data do Retorno</label>
                            <div class="controls">
                                <input id="data_retorno" type="text" name="data_retorno" value="<?= $result->data_retorno ? date('d/m/Y', strtotime($result->data_retorno)) : ''; ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="vagas" class="control-label">Vagas Disponíveis<span class="required">*</span></label>
                            <div class="controls">
                                <input id="vagas" type="number" name="vagas" value="<?= $result->vagas; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="preco_pessoa" class="control-label">Preço por Pessoa</label>
                            <div class="controls">
                                <input id="preco_pessoa" type="text" name="preco_pessoa" value="<?= number_format($result->preco_pessoa, 2, ',', '.'); ?>" class="money" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="status" class="control-label">Status<span class="required">*</span></label>
                            <div class="controls">
                                <select id="status" name="status">
                                    <option value="Aberta" <?= ($result->status == 'Aberta') ? 'selected' : ''; ?>>Aberta</option>
                                    <option value="Concluida" <?= ($result->status == 'Concluida') ? 'selected' : ''; ?>>Concluída</option>
                                    <option value="Prevista" <?= ($result->status == 'Prevista') ? 'selected' : ''; ?>>Prevista</option>
                                    <option value="Disponível" <?= ($result->status == 'Disponível') ? 'selected' : ''; ?>>Disponível</option>
                                    <option value="Indisponível" <?= ($result->status == 'Indisponível') ? 'selected' : ''; ?>>Indisponível</option>
                                    <option value="Adiada" <?= ($result->status == 'Adiada') ? 'selected' : ''; ?>>Adiada</option>
                                    <option value="Cancelada" <?= ($result->status == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                    <option value="Prorrogada" <?= ($result->status == 'Prorogada') ? 'selected' : ''; ?>>Prorrogada</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="curso_id" class="control-label">Atrelar a Curso</label>
                            <div class="controls" id="cursos-container">
                                <input type="text" id="curso-autocomplete" placeholder="Pesquisar curso...">
                                <div id="cursos-selecionados">
                                    <?php foreach ($cursos_viagem as $curso) : ?>
                                        <div class="curso-tag" data-id="<?= $curso->curso_id ?>">
                                            <?= html_escape($curso->nome_curso) ?>
                                            <span class="remove-tag">x</span>
                                        </div>
                                        <input type="hidden" name="cursos[]" value="<?= $curso->curso_id ?>">
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-mini btn-primary"><span class="button__icon"><i class='bx bx-sync'></i></span> <span class="button__text2">Atualizar</span></button>
                            <a title="Voltar" class="button btn btn-warning" href="<?= site_url() ?>/viagens"><span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?= base_url() ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // A inicialização do datetimepicker e validação permanecem as mesmas
        $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });
        $('.money').maskMoney({ decimal: ',', thousands: '.', allowZero: true });

        $("#curso-autocomplete").autocomplete({
            source: "<?= site_url('cursos/autoCompleteCurso'); ?>",
            minLength: 2,
            select: function(event, ui) {
                $('#cursos-selecionados').append('<div class="curso-tag" data-id="' + ui.item.id + '">' + ui.item.label + ' <span class="remove-tag">x</span></div>');
                $('#cursos-container').append('<input type="hidden" name="cursos[]" value="' + ui.item.id + '">');
                $(this).val('');
                return false;
            }
        });

        $(document).on('click', '.remove-tag', function() {
            var cursoId = $(this).parent().data('id');
            $('input[name="cursos[]"][value="' + cursoId + '"]').remove();
            $(this).parent().remove();
        });
    });
</script>