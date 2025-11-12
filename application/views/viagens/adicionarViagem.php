<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/trumbowyg/ui/trumbowyg.min.css">
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/trumbowyg.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/trumbowyg/langs/pt_br.min.js"></script>
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
    /* Garante que o contêiner pai do autocomplete seja posicionado */
    /* Garante que o autocomplete apareça sobre outros elementos */
    .ui-autocomplete {
        z-index: 9999;
        max-height: 200px; /* Limita a altura para que não ocupe a tela toda */
        overflow-y: auto; /* Adiciona scroll vertical se o conteúdo exceder a altura máxima */
        box-sizing: border-box; /* Inclui padding e borda na largura e altura total */
    }
</style>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-route"></i>
                </span>
                <h5>Adicionar Viagem</h5>
            </div>
            <?php if (isset($custom_error) && $custom_error != '') : ?>
                <div class="alert alert-danger"><?= $custom_error ?></div>
            <?php endif; ?>
            <form action="<?= current_url(); ?>" id="formViagem" method="post" class="form-horizontal">
                <div class="widget-content nopadding tab-content">
                    <div class="span12">
                        <div class="control-group">
                            <label for="nome_viagem" class="control-label">Nome da Viagem<span class="required">*</span></label>
                            <div class="controls">
                                <input id="nome_viagem" type="text" name="nome_viagem" value="<?= set_value('nome_viagem'); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="descricao" class="control-label">Descrição</label>
                            <div class="controls">
                                <textarea class="trumbowyg" id="descricao" name="descricao" rows="5"><?= set_value('descricao'); ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_partida" class="control-label">Data da Partida</label>
                            <div class="controls">
                                <input id="data_partida" type="text" name="data_partida" value="<?= set_value('data_partida'); ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="data_retorno" class="control-label">Data do Retorno</label>
                            <div class="controls">
                                <input id="data_retorno" type="text" name="data_retorno" value="<?= set_value('data_retorno'); ?>" class="datepicker" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="vagas" class="control-label">Vagas Disponíveis<span class="required">*</span></label>
                            <div class="controls">
                                <input id="vagas" type="number" name="vagas" value="<?= set_value('vagas', 0); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="preco_pessoa" class="control-label">Preço por Pessoa</label>
                            <div class="controls">
                                <input id="preco_pessoa" type="text" name="preco_pessoa" value="<?= set_value('preco_pessoa', '0.00'); ?>" class="money" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="status" class="control-label">Status<span class="required">*</span></label>
                            <div class="controls">
                                <select id="status" name="status">
                                    <option value="Aberta" <?= set_select('status', 'Aberta'); ?>>Aberta</option>
                                    <option value="Concluída" <?= set_select('status', 'Concluída'); ?>>Concluída</option>
                                    <option value="Prevista" <?= set_select('status', 'Prevista'); ?>>Prevista</option>
                                    <option value="Disponível" <?= set_select('status', 'Disponível'); ?>>Disponível</option>
                                    <option value="Indisponível" <?= set_select('status', 'Indisponível'); ?>>Indisponível</option>
                                    <option value="Adiada" <?= set_select('status', 'Adiada'); ?>>Adiada</option>
                                    <option value="Cancelada" <?= set_select('status', 'Cancelada'); ?>>Cancelada</option>
                                    <option value="Prorrogada" <?= set_select('status', 'Prorrogada'); ?>>Prorrogada</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="curso_id" class="control-label">Atrelar a Curso</label>
                            <div class="controls" id="cursos-container">
                                <input type="text" id="curso-autocomplete" placeholder="Pesquisar curso...">
                                <div id="cursos-selecionados"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-mini btn-success"><span class="button__icon"><i class='bx bx-save'></i></span> <span class="button__text2">Salvar</span></button>
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
        $('.trumbowyg').trumbowyg({
            lang: 'pt_br',
            autogrow: true
        });
        $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true, yearRange: '-100:+10' });
        $('.money').maskMoney({ decimal: ',', thousands: '.', allowZero: true });

        $('#formViagem').validate({
            rules: {
                nome_viagem: { required: true },
                vagas: { required: true, number: true },
                status: { required: true }
            },
            messages: {
                nome_viagem: { required: 'Campo Requerido.' },
                vagas: { required: 'Campo Requerido.', number: 'Insira um número válido.' },
                status: { required: 'Campo Requerido.' }
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

        $("#curso-autocomplete").autocomplete({
            source: "<?= site_url('cursos/autoCompleteCurso'); ?>",
            minLength: 2,
            appendTo: "#cursos-container", // Anexa o menu ao contêiner para melhor posicionamento
            open: function() {
                $(this).autocomplete("widget").width($(this).outerWidth()); // Ajusta a largura do menu para a largura do input
            },
            select: function(event, ui) {
                // Verifica se o curso já foi adicionado
                if ($('input[name="cursos[]"][value="' + ui.item.id + '"]').length > 0) {
                    $(this).val(''); // Limpa o campo de busca
                    return false; // Impede a adição do curso duplicado
                }

                // Adiciona a tag visual
                $('#cursos-selecionados').append(
                    '<div class="curso-tag" data-id="' + ui.item.id + '">' +
                    ui.item.label +
                    ' <span class="remove-tag">x</span>' +
                    '</div>'
                );
                // Adiciona o input hidden
                $('#cursos-container').append('<input type="hidden" name="cursos[]" value="' + ui.item.id + '">');
                
                $(this).val(''); // Limpa o campo de busca
                return false; // Previne que o valor seja inserido no input
            }
        });

        $(document).on('click', '.remove-tag', function() {
            var cursoId = $(this).parent().data('id');
            $('input[name="cursos[]"][value="' + cursoId + '"]').remove();
            $(this).parent().remove();
        });
    });
</script>