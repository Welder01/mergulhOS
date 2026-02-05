<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetimepicker.min.css" />
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>

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

<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-header">
                <h5 class="cardHeader"><i class="fas fa-dumbbell"></i> Configurações de Treinos</h5>
            </div>
            <div class="widget-content nopadding">
                <div class="span12" style="padding: 10px; margin-left: 0">
                    <a href="<?php echo site_url('treinos/adicionarConfiguracao'); ?>" class="btn btn-success">
                        <i class='bx bx-plus-circle'></i> Nova Configuração
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Duração (min)</th>
                            <th>Preço s/ Instrutor</th>
                            <th>Preço c/ Instrutor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$results) {
                            echo '<tr><td colspan="7">Nenhuma Configuração de Treino Cadastrada</td></tr>';
                        }
                        foreach ($results as $r) {
                            echo '<tr>';
                            echo '<td>' . $r->id . '</td>';
                            echo '<td>' . $r->nome . '</td>';
                            echo '<td>' . $r->duracao_minutos . '</td>';
                            echo '<td>R$ ' . number_format($r->preco_sem_instrutor, 2, ',', '.') . '</td>';
                            echo '<td>R$ ' . number_format($r->preco_com_instrutor, 2, ',', '.') . '</td>';
                            echo '<td>' . ($r->status ? 'Ativo' : 'Inativo') . '</td>';
                            echo '<td>';
                            echo '<a href="' . site_url('treinos/editarConfiguracao/' . $r->id) . '" class="btn-nwe tip-top" title="Editar Configuração" style="margin-right: 5px;"><i class="bx bx-edit"></i></a>';
                            echo '<a href="#modal-excluir" role="button" data-toggle="modal" configuracao_id="' . $r->id . '" class="btn-nwe3 tip-top" title="Excluir Configuração"><i class="bx bx-trash-alt"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid" style="margin-top: 20px;">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-header">
                <h5 class="cardHeader"><i class="fas fa-calendar-check"></i> Treinos Agendados</h5>
            </div>
            <div class="widget-content nopadding">
                <div style="padding: 20px;">
                    <div class="span12" style="margin-left: 0; margin-bottom: 15px;">
                        <a href="#modal-agendar-treino" role="button" data-toggle="modal" class="btn btn-success">
                            <i class="fas fa-plus"></i> Novo Agendamento
                        </a>
                    </div>
                    <form action="<?= site_url('treinos') ?>" method="get">
                        <div class="row-fluid"
                            style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                            <div class="span2">
                                <label for="pesquisa_cliente">Cliente</label>
                                <input type="text" name="pesquisa_cliente" id="pesquisa_cliente"
                                    value="<?= $this->input->get('pesquisa_cliente') ?>" class="span12"
                                    placeholder="Nome do Cliente">
                            </div>
                            <div class="span2">
                                <label for="pesquisa_treino">Treino</label>
                                <input type="text" name="pesquisa_treino" id="pesquisa_treino"
                                    value="<?= $this->input->get('pesquisa_treino') ?>" class="span12"
                                    placeholder="Nome do Treino">
                            </div>
                            <div class="span2">
                                <label for="data_inicial">Data Inicial</label>
                                <input type="text" name="data_inicial" id="data_inicial"
                                    value="<?= $this->input->get('data_inicial') ?>" class="span12 datepicker"
                                    placeholder="DD/MM/AAAA">
                            </div>
                            <div class="span2">
                                <label for="data_final">Data Final</label>
                                <input type="text" name="data_final" id="data_final"
                                    value="<?= $this->input->get('data_final') ?>" class="span12 datepicker"
                                    placeholder="DD/MM/AAAA">
                            </div>
                            <div class="span2">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="span12">
                                    <option value="">Todos</option>
                                    <option value="Agendado" <?= $this->input->get('status') == 'Agendado' ? 'selected' : '' ?>>Agendado</option>
                                    <option value="Realizado" <?= $this->input->get('status') == 'Realizado' ? 'selected' : '' ?>>Realizado</option>
                                    <option value="Cancelado" <?= $this->input->get('status') == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                </select>
                            </div>
                            <div style="padding-bottom: 10px;">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                                    Filtrar</button>
                                <a href="<?= site_url('treinos') ?>" class="btn btn-default"><i
                                        class="fas fa-eraser"></i> Limpar</a>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Treino</th>
                            <th>Data/Hora Início</th>
                            <th>Custo Instrutor</th>
                            <th>Faturado</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$agendamentos) {
                            echo '<tr><td colspan="6">Nenhum Treino Agendado</td></tr>';
                        }
                        foreach ($agendamentos as $agendamento) {
                            $cor = '#808080'; // Cor padrão
                            switch (strtolower($agendamento->status)) {
                                case 'agendado':
                                    $cor = '#00cd00';
                                    break;
                                case 'realizado':
                                    $cor = '#436eee';
                                    break;
                                case 'cancelado':
                                    $cor = '#CD0000';
                                    break;
                            }
                            $faturado = (isset($agendamento->faturado) && $agendamento->faturado == 1) ? 'Sim' : 'Não';
                            echo '<tr>';
                            echo '<td>' . $agendamento->id . '</td>';
                            echo '<td>' . htmlspecialchars($agendamento->nome_cliente) . '</td>';
                            echo '<td>' . htmlspecialchars($agendamento->nome_treino) . '</td>';
                            echo '<td>' . date('d/m/Y H:i', strtotime($agendamento->data_hora_inicio)) . '</td>';
                            echo '<td>R$ ' . number_format($agendamento->valor_pagamento, 2, ',', '.') . '</td>';
                            echo '<td>' . $faturado . '</td>';
                            echo '<td><span class="badge" style="background-color: ' . $cor . '; border-color: ' . $cor . '">' . $agendamento->status . '</span></td>';
                            echo '<td>';
                            echo '<a href="' . base_url() . 'index.php/treinos/visualizarTreino/' . $agendamento->id . '" class="btn-nwe tip-top" title="Ver Detalhes" style="margin-right: 5px;"><i class="fas fa-eye"></i></a>';
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eTreino')) {
                                echo '<button class="btn-nwe3 tip-top btn-reagendar" data-id="' . $agendamento->id . '" title="Reagendar" style="margin-right: 5px;"><i class="fas fa-calendar-alt"></i></button>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eTreino') && $agendamento->com_instrutor) {
                                echo '<button class="btn-inverse tip-top btn-pagar-instrutor" data-id="' . $agendamento->id . '" data-valor="' . ($agendamento->valor_pagamento > 0 ? $agendamento->valor_pagamento : $agendamento->preco_com_instrutor) . '" title="Pagar Instrutor" style="margin-right: 5px;"><i class="fas fa-money-bill-wave"></i></button>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aLancamento') && (!isset($agendamento->faturado) || $agendamento->faturado != 1)) {
                                echo '<a href="#modal-faturar" role="button" data-toggle="modal" treino_id="' . $agendamento->id . '" valor="' . $agendamento->valor_cobrado . '" cliente="' . htmlspecialchars($agendamento->nome_cliente) . '" cliente_id="' . $agendamento->cliente_id . '" class="btn-nwe5 tip-top" title="Faturar" style="margin-right: 5px;"><i class="bx bx-dollar"></i></a>';
                            }
                            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dTreino')) {
                                echo '<a href="#modal-excluir-agendamento" role="button" data-toggle="modal" agendamento_id="' . $agendamento->id . '" class="btn-nwe4 tip-top" title="Excluir"><i class="fas fa-trash-alt"></i></a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .widget-box {
        border-radius: 8px;
        overflow: hidden;
        border-top: 2px solid #2C3E50;
        /* A dark color fitting modern themes */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background: #fff;
    }

    .widget-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .cardHeader {
        font-weight: 600;
        font-size: 1.1em;
        margin: 0;
        color: #333;
    }

    .table th {
        background: #f1f2f3 !important;
        color: #555;
        font-weight: 600;
    }
    /* Fix para o calendário aparecer sobre o modal */
    .xdsoft_datetimepicker {
        z-index: 100000 !important;
    }
    #ui-datepicker-div {
        z-index: 100000 !important;
    }
    .form-modal .control-label {
        width: 120px;
    }
    .form-modal .controls {
        margin-left: 140px;
    }
    .ui-autocomplete {
        z-index: 100000 !important;
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .ui-menu .ui-menu-item a {
        font-size: 12px;
        white-space: normal;
    }
</style>


<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', 'a[data-toggle="modal"]', function (event) {
            var configuracao_id = $(this).attr('configuracao_id');
            $('#configuracao_id').val(configuracao_id);

            var agendamento_id = $(this).attr('agendamento_id');
            if (agendamento_id) {
                $('#agendamento_id_excluir').val(agendamento_id);
            }
        });

        $(document).on('click', '.btn-pagar-instrutor', function () {
            var id = $(this).data('id');
            var valor = $(this).data('valor');
            $('#pagar_id_agendamento').val(id);
            $('#valor_pagamento_instrutor').val(valor);
            $('#valor_pagamento_instrutor').maskMoney({decimal:",", thousands:"."});
            $('#valor_pagamento_instrutor').maskMoney('mask', parseFloat(valor));
            $('#modal-pagar-instrutor').modal('show');
        });

        $(document).on('click', 'a[href="#modal-faturar"]', function(event) {
            var treino_id = $(this).attr('treino_id');
            var valor = $(this).attr('valor');
            var cliente = $(this).attr('cliente');
            var cliente_id = $(this).attr('cliente_id');

            $('#treino_id').val(treino_id);
            $('#descricao').val('Fatura de Treino - #' + treino_id);
            $('#cliente').val(cliente);
            $('#clientes_id').val(cliente_id);
            $('#valor').val(valor);
            $('#valor').maskMoney('mask');
            $('#vencimento').val('<?php echo date('d/m/Y'); ?>');
        });

        $('#recebido').click(function(event) {
            var flag = $(this).is(':checked');
            if (flag == true) {
                $('#divRecebimento').show();
            } else {
                $('#divRecebimento').hide();
            }
        });

        $("#formFaturar").validate({
            rules: {
                descricao: { required: true },
                cliente: { required: true },
                valor: { required: true },
                vencimento: { required: true }
            },
            messages: {
                descricao: { required: 'Campo Requerido.' },
                cliente: { required: 'Campo Requerido.' },
                valor: { required: 'Campo Requerido.' },
                vencimento: { required: 'Campo Requerido.' }
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();
                $('#btn-cancelar-faturar').trigger('click');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/treinos/faturar",
                    data: dados,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            window.location.reload(true);
                        } else {
                            alert('Ocorreu um erro ao tentar faturar treino.');
                        }
                    }
                });
                return false;
            }
        });

        $('#vencimento, #recebimento, #data_pagamento_instrutor').datetimepicker({
            format: 'd/m/Y',
            timepicker: false,
            zIndex: 100000
        });

        // Autocomplete Cliente no Modal Novo Agendamento
        $("#cliente_nome").autocomplete({
            source: "<?php echo base_url(); ?>index.php/treinos/autoCompleteCliente",
            minLength: 1,
            select: function(event, ui) {
                $("#cliente_id").val(ui.item.id);
                $("#cliente_nome").val(ui.item.nome);
                return false;
            }
        });

        // Datepicker para Novo Agendamento
        var datetimepickerNovo = $('#data_hora_treino_novo').datetimepicker({
            format: 'd/m/Y H:i', step: 30, zIndex: 99999
        });

        // Atualiza calendário ao selecionar treino
        $('#treino_config_id_novo').change(function() {
            var config_id = $(this).val();
            if (config_id) {
                $('#data_hora_treino_novo').val('').prop('disabled', true).attr('placeholder', 'Carregando horários...');
                $.getJSON('<?= site_url('treinos/getHorariosDisponiveis') ?>', { config_id: config_id }, function(response) {
                    $('#data_hora_treino_novo').datetimepicker('destroy');
                    $('#data_hora_treino_novo').datetimepicker({
                        format: 'd/m/Y H:i',
                        step: parseInt(response.duration) || 30,
                        allowTimes: response.allowedTimes || [],
                        disabledWeekDays: response.disabledWeekDays || [],
                        zIndex: 99999
                    });
                    $('#data_hora_treino_novo').prop('disabled', false).attr('placeholder', 'DD/MM/AAAA HH:MM');
                });
            } else {
                $('#data_hora_treino_novo').val('').prop('disabled', true);
            }
        });

        // Lógica de Instrutor no Modal Novo Agendamento
        $('#com_instrutor_novo, #treino_config_id_novo, #data_hora_treino_novo').on('change', function () {
            var comInstrutor = $('#com_instrutor_novo').is(':checked');
            $('#instrutor_message_novo').hide().html('');
            if (comInstrutor) {
                buscarInstrutoresNovo();
            } else {
                $('#instrutor_div_novo').slideUp();
                $('#instrutor_id_novo').html('<option value="">Selecione um instrutor</option>');
            }
        });
 
        function buscarInstrutoresNovo() {
            var configId = $('#treino_config_id_novo').val();
            var dataHora = $('#data_hora_treino_novo').val();

            if (configId) {
                $('#instrutor_id_novo').prop('disabled', true).html('<option>Buscando...</option>');
                $.get('<?= site_url('treinos/getInstrutoresDisponiveis') ?>', { config_id: configId, data_hora: dataHora }, function (data) {
                    if(data.length > 0) {
                        var options = '<option value="">Qualquer um disponível</option>';
                        data.forEach(function (instrutor) {
                            options += '<option value="' + instrutor.id + '">' + instrutor.nome + '</option>';
                        });
                        $('#instrutor_id_novo').html(options).prop('disabled', false);
                        $('#instrutor_div_novo').slideDown();
                    } else {
                        $('#instrutor_div_novo').slideUp();
                        $('#com_instrutor_novo').prop('checked', false);
                        $('#instrutor_message_novo').html('<div class="alert alert-info" style="margin-bottom: 0;">Nenhum instrutor disponível para este treino. Prossiga com o agendamento sem instrutor.</div>').slideDown();
                    }
                }, 'json');
            } else {
                $('#com_instrutor_novo').prop('checked', false);
            }
        }
    });
</script>

<!-- Modal Reagendamento -->
<div id="modal-reagendar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form action="<?php echo site_url('treinos/reagendarTreino'); ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Reagendar Treino</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="id_agendamento" name="id_agendamento" value="" />
            <div class="control-group">
                <label for="treino_config_id_reagendar" class="control-label">Tipo de Treino<span
                        class="required">*</span></label>
                <div class="controls">
                    <select name="treino_config_id" id="treino_config_id_reagendar" class="span12" required>
                        <option value="">Selecione um treino</option>
                        <?php foreach ($results as $treino): ?>
                            <option value="<?= $treino->id ?>" data-duracao="<?= $treino->duracao_minutos ?>"
                                data-preco-sem="<?= $treino->preco_sem_instrutor ?>"
                                data-preco-com="<?= $treino->preco_com_instrutor ?>">
                                <?= htmlspecialchars($treino->nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Com Instrutor</label>
                <div class="controls">
                    <label class="switch" style="display: inline-block; vertical-align: middle;">
                        <input type="checkbox" name="com_instrutor" id="com_instrutor_reagendar" value="1">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="control-group" id="instrutor_div_reagendar" style="display: none;">
                <label for="instrutor_id_reagendar" class="control-label">Instrutor</label>
                <div class="controls">
                    <select name="instrutor_id" id="instrutor_id_reagendar" class="span12">
                        <option value="">Selecione um instrutor</option>
                        <!-- Options carregadas via AJAX -->
                    </select>
                </div>
            </div>


            <div class="control-group">
                <label for="data_hora_reagendamento" class="control-label">Nova Data e Hora<span
                        class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="data_hora_reagendamento" id="data_hora_reagendamento" class="span12"
                        required>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i
                        class="bx bx-x"></i></span><span class="button__text2">Voltar</span></button>
            <button class="button btn btn-primary"><span class="button__icon"><i
                        class="bx bx-calendar-check"></i></span> <span class="button__text2">Reagendar</span></button>
        </div>
    </form>
</div>

<!-- Modal Novo Agendamento -->
<div id="modal-agendar-treino" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('treinos/adicionarAgendamento'); ?>" method="post" class="form-horizontal form-modal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Novo Agendamento</h5>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label for="cliente_nome" class="control-label">Cliente<span class="required">*</span></label>
                <div class="controls">
                    <input id="cliente_nome" type="text" name="cliente_nome" class="span12" placeholder="Digite o nome do cliente" required />
                    <input id="cliente_id" type="hidden" name="cliente_id" value="" />
                </div>
            </div>
            <div class="control-group">
                <label for="treino_config_id_novo" class="control-label">Tipo de Treino<span class="required">*</span></label>
                <div class="controls">
                    <select name="treino_config_id" id="treino_config_id_novo" class="span12" required>
                        <option value="">Selecione um treino</option>
                        <?php foreach ($results as $treino): ?>
                            <option value="<?= $treino->id ?>" data-duracao="<?= $treino->duracao_minutos ?>"
                                data-preco-sem="<?= $treino->preco_sem_instrutor ?>"
                                data-preco-com="<?= $treino->preco_com_instrutor ?>">
                                <?= htmlspecialchars($treino->nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Com Instrutor</label>
                <div class="controls">
                    <label class="switch" style="display: inline-block; vertical-align: middle;">
                        <input type="checkbox" name="com_instrutor" id="com_instrutor_novo" value="1">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="control-group" id="instrutor_div_novo" style="display: none;">
                <label for="instrutor_id_novo" class="control-label">Instrutor</label>
                <div class="controls">
                    <select name="instrutor_id" id="instrutor_id_novo" class="span12">
                        <option value="">Selecione um instrutor</option>
                    </select>
                </div>
            </div>
            <div id="instrutor_message_novo" style="display:none; margin-left: 140px; margin-right: 20px; margin-bottom: 10px;"></div>
            <div class="control-group">
                <label for="data_hora_treino_novo" class="control-label">Data e Hora<span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="data_hora_treino" id="data_hora_treino_novo" class="span12" required>
                </div>
            </div>
             <div class="control-group">
                <label for="observacoes" class="control-label">Observações</label>
                <div class="controls">
                    <textarea name="observacoes" id="observacoes" class="span12" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-primary"><span class="button__icon"><i class="bx bx-check"></i></span> <span class="button__text2">Agendar</span></button>
        </div>
    </form>
</div>

<!-- Modal Pagar Instrutor -->
<div id="modal-pagar-instrutor" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('treinos/pagarInstrutor'); ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Pagar Instrutor</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="pagar_id_agendamento" name="id" value="" />
            <div class="control-group">
                <label for="valor_pagamento_instrutor" class="control-label">Valor a Pagar</label>
                <div class="controls">
                    <input type="text" name="valor_pagamento" id="valor_pagamento_instrutor" class="money" required />
                </div>
            </div>
            <div class="control-group">
                <label for="data_pagamento_instrutor" class="control-label">Data do Pagamento</label>
                <div class="controls">
                    <input type="text" name="data_pagamento" id="data_pagamento_instrutor" value="<?php echo date('d/m/Y'); ?>" required />
                </div>
            </div>
            <div class="control-group">
                <label for="forma_pgto_instrutor" class="control-label">Forma de Pagamento</label>
                <div class="controls">
                    <select name="forma_pgto" id="forma_pgto_instrutor">
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Pix">Pix</option>
                        <option value="Transferência Bancária">Transferência Bancária</option>
                        <option value="Cheque">Cheque</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-primary"><span class="button__icon"><i class="bx bx-check"></i></span> <span class="button__text2">Faturar</span></button>
        </div>
    </form>
</div>

<!-- Modal Excluir Configuração -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/treinos/excluirConfiguracao" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Configuração</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="configuracao_id" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir esta configuração de treino?</h5>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
        </div>
    </form>
</div>

<!-- Modal Excluir Agendamento -->
<div id="modal-excluir-agendamento" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/treinos/excluirAgendamento" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Agendamento</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="agendamento_id_excluir" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este agendamento?</h5>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
        </div>
    </form>
</div>

<!-- Modal Faturar -->
<div id="modal-faturar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formFaturar" action="<?php echo current_url() ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Faturar Treino</h3>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
            <div class="span12" style="margin-left: 0">
                <label for="descricao">Descrição*</label>
                <input class="span12" id="descricao" type="text" name="descricao" value="" required readonly />
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span12" style="margin-left: 0">
                    <label for="cliente">Cliente*</label>
                    <input class="span12" id="cliente" type="text" name="cliente" value="" required readonly />
                    <input type="hidden" name="clientes_id" id="clientes_id" value="">
                    <input type="hidden" name="treino_id" id="treino_id" value="">
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="valor">Valor*</label>
                    <input type="hidden" id="tipo" name="tipo" value="receita" />
                    <input class="span12 money" id="valor" type="text" name="valor" value="" required />
                </div>
                <div class="span4">
                    <label for="vencimento">Data Vencimento*</label>
                    <input class="span12" id="vencimento" type="text" name="vencimento" required />
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="recebido">Recebido?</label>
                    &nbsp &nbsp &nbsp &nbsp <input id="recebido" type="checkbox" name="recebido" value="1" />
                </div>
                <div id="divRecebimento" class="span8" style=" display: none">
                    <div class="span6">
                        <label for="recebimento">Data Recebimento</label>
                        <input class="span12" id="recebimento" type="text" name="recebimento" />
                    </div>
                    <div class="span6">
                        <label for="formaPgto">Forma Pgto</label>
                        <select name="formaPgto" id="formaPgto" class="span12">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Depósito">Depósito</option>
                            <option value="Débito">Débito</option>
                            <option value="Pix">Pix</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true" id="btn-cancelar-faturar">Cancelar</button>
            <button class="btn btn-primary">Faturar</button>
        </div>
    </form>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetimepicker.min.css" />
<script src="<?php echo base_url(); ?>assets/js/jquery.datetimepicker.full.min.js"></script>
<script>
    $(document).ready(function () {
        $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });

        $(document).on('click', '.btn-reagendar', function () {
            var agendamentoId = $(this).data('id');
            $('#id_agendamento').val(agendamentoId);

            // AJAX para buscar os dados atuais do agendamento
            $.ajax({
                url: '<?= site_url('treinos/getTreinoJson') ?>',
                type: 'POST',
                data: {
                    id: agendamentoId,
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function (response) {
                    if (response && !response.error) {
                        $('#treino_config_id_reagendar').val(response.config_id);
                        $('#data_hora_reagendamento').val(new Date(response.data_hora_inicio).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(',', ''));

                        if (response.com_instrutor == '1') {
                            $('#com_instrutor_reagendar').prop('checked', true).trigger('change');
                            // A função buscarInstrutoresReagendar() é chamada pelo trigger.
                            // Usamos um listener para o evento ajaxStop para garantir que a seleção do instrutor
                            // ocorra somente após a lista de instrutores ser carregada.
                            $(document).one('ajaxStop', function () {
                                $('#instrutor_id_reagendar').val(response.instrutor_id);
                            });
                        } else {
                            $('#com_instrutor_reagendar').prop('checked', false).trigger('change');
                        }

                        $('#modal-reagendar').modal('show');
                    } else {
                        alert('Erro ao carregar dados do agendamento.');
                    }
                },
                error: function () {
                    alert('Erro de comunicação ao carregar dados do agendamento.');
                }
            });
        });

        $('#data_hora_reagendamento').datetimepicker({
            format: 'd/m/Y H:i',
            step: 30,
            zIndex: 99999 // Garante que o seletor apareça sobre o modal
        });

        // Lógica para mostrar/ocultar e buscar instrutores no modal de reagendamento
        $('#com_instrutor_reagendar, #treino_config_id_reagendar, #data_hora_reagendamento').on('change', function () {
            var comInstrutor = $('#com_instrutor_reagendar').is(':checked');
            if (comInstrutor) {
                $('#instrutor_div_reagendar').slideDown();
                buscarInstrutoresReagendar();
            } else {
                $('#instrutor_div_reagendar').slideUp();
                $('#instrutor_id_reagendar').html('<option value="">Selecione um instrutor</option>');
            }
        });

        function buscarInstrutoresReagendar() {
            var configId = $('#treino_config_id_reagendar').val();
            var dataHora = $('#data_hora_reagendamento').val();

            if (configId) {
                $('#instrutor_id_reagendar').prop('disabled', true).html('<option>Buscando...</option>');
                $.get('<?= site_url('treinos/getInstrutoresDisponiveis') ?>', { config_id: configId, data_hora: dataHora }, function (data) {
                    var options = '<option value="">Qualquer um disponível</option>';
                    data.forEach(function (instrutor) {
                        options += '<option value="' + instrutor.id + '">' + instrutor.nome + '</option>';
                    });
                    $('#instrutor_id_reagendar').html(options).prop('disabled', false);
                }, 'json');
            }
        }
    });
</script>