<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/trumbowyg/ui/trumbowyg.min.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/trumbowyg.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/langs/pt_br.min.js"></script>
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
                <h5>Adicionar Curso</h5>
            </div>
            <?php if ($custom_error != '') : ?>
                <div class="alert alert-danger"><?= $custom_error ?></div>
            <?php endif; ?>
            <form action="<?= current_url(); ?>" id="formCurso" method="post" class="form-horizontal">
                <div class="widget-content nopadding tab-content">
                    <div class="span12">
                        <div class="control-group">
                            <label for="nome_curso" class="control-label">Nome do Curso<span class="required">*</span></label>
                            <div class="controls">
                                <input id="nome_curso" type="text" name="nome_curso" value="<?= set_value('nome_curso'); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="descricao" class="control-label">Descrição</label>
                            <div class="controls">
                                <textarea class="trumbowyg" id="descricao" name="descricao" rows="5"><?= set_value('descricao'); ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_inicio" class="control-label">Data de Início<span class="required">*</span></label>
                            <div class="controls">
                                <input id="data_inicio" type="text" name="data_inicio" value="<?= set_value('data_inicio'); ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_fim" class="control-label">Data de Fim</label>
                            <div class="controls">
                                <input id="data_fim" type="text" name="data_fim" value="<?= set_value('data_fim'); ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="status" class="control-label">Status<span class="required">*</span></label>
                            <div class="controls">
                                <select id="status" name="status">
                                    <option value="ativo" <?= set_select('status', 'ativo'); ?>>Ativo</option>
                                    <option value="inativo" <?= set_select('status', 'inativo'); ?>>Inativo</option>
                                    <option value="em andamento" <?= set_select('status', 'em andamento'); ?>>Em Andamento</option>
                                    <option value="finalizado" <?= set_select('status', 'finalizado'); ?>>Finalizado</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="preco" class="control-label">Preço<span class="required">*</span></label>
                            <div class="controls">
                                <input id="preco" type="text" name="preco" value="<?= set_value('preco'); ?>" class="money" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="vagas" class="control-label">Vagas<span class="required">*</span></label>
                            <div class="controls">
                                <input id="vagas" type="number" name="vagas" value="<?= set_value('vagas'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-mini btn-success"><span class="button__icon"><i class='bx bx-save'></i></span> <span class="button__text2">Salvar</span></button>
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
            dateFormat: 'dd/mm/yy'
        });
        $('.money').maskMoney({ decimal: ',', thousands: '.', allowZero: true });

        $('#formCurso').validate({
            rules: {
                nome_curso: { required: true },
                data_inicio: { required: true },
                vagas: { required: true },
                status: { required: true },
                preco: { required: true }
            },
            messages: {
                nome_curso: { required: 'Campo Requerido.' },
                data_inicio: { required: 'Campo Requerido.' },
                vagas: { required: 'Campo Requerido.' },
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