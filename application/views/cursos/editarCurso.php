<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?= base_url() ?>assets/js/maskmoney.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/funcoes.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </span>
                <h5>Editar Curso</h5>
            </div>
            <?php if ($custom_error != '') : ?>
                <div class="alert alert-danger"><?= $custom_error ?></div>
            <?php endif; ?>
            <form action="<?= current_url(); ?>" id="formCurso" method="post" class="form-horizontal">
                <div class="widget-content nopadding tab-content">
                    <div class="span12">
                        <?= form_hidden('id', $result->id) ?>
                        <div class="control-group">
                            <label for="nome_curso" class="control-label">Nome do Curso<span class="required">*</span></label>
                            <div class="controls">
                                <input id="nome_curso" type="text" name="nome_curso" value="<?= html_escape($result->nome_curso); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="descricao" class="control-label">Descrição</label>
                            <div class="controls">
                                <textarea id="descricao" name="descricao" rows="5"><?= html_escape($result->descricao); ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_inicio" class="control-label">Data de Início<span class="required">*</span></label>
                            <div class="controls">
                                <input id="data_inicio" type="text" name="data_inicio" value="<?= date('d/m/Y', strtotime($result->data_inicio)); ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_fim" class="control-label">Data de Fim</label>
                            <div class="controls">
                                <input id="data_fim" type="text" name="data_fim" value="<?= $result->data_fim ? date('d/m/Y', strtotime($result->data_fim)) : ''; ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="status" class="control-label">Status<span class="required">*</span></label>
                            <div class="controls">
                                <select id="status" name="status">
                                    <option value="ativo" <?= ($result->status == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                                    <option value="inativo" <?= ($result->status == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                                    <option value="em andamento" <?= ($result->status == 'em andamento') ? 'selected' : ''; ?>>Em Andamento</option>
                                    <option value="finalizado" <?= ($result->status == 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="preco" class="control-label">Preço<span class="required">*</span></label>
                            <div class="controls">
                                <input id="preco" type="text" name="preco" value="<?= number_format($result->preco, 2, ',', '.'); ?>" class="money" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-mini btn-primary"><span class="button__icon"><i class='bx bx-sync'></i></span> <span class="button__text2">Atualizar</span></button>
                            <a title="Voltar" class="button btn btn-warning" href="<?= site_url() ?>/cursos"><span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
            dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });

        $('.money').maskMoney({ decimal: ',', thousands: '.', allowZero: true });

        $('#formCurso').validate({
            rules: {
                nome_curso: { required: true },
                data_inicio: { required: true },
                status: { required: true },
                preco: { required: true }
            },
            messages: {
                nome_curso: { required: 'Campo Requerido.' },
                data_inicio: { required: 'Campo Requerido.' },
                status: { required: 'Campo Requerido.' },
                preco: { required: 'Campo Requerido.' }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>