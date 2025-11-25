<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-dumbbell"></i>
        </span>
        <h5>Configurações de Treinos</h5>
    </div>

    <div class="widget-content nopadding">
        <div class="text-left" style="margin: 10px;">
            <a href="<?php echo site_url('treinos/adicionarConfiguracao'); ?>" class="button btn btn-mini btn-success" style="width: 180px;">
                <span class="button__icon"><i class='bx bx-plus-circle'></i></span>
                <span class="button__text2">Nova Configuração</span>
            </a>
        </div>

        <table class="table table-bordered ">
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
                    echo '<a href="' . site_url('treinos/editarConfiguracao/' . $r->id) . '" style="margin-right: 1%" class="btn-nwe" title="Editar Configuração"><i class="bx bx-edit"></i></a>';
                    echo '<a href="#modal-excluir" role="button" data-toggle="modal" configuracao_id="' . $r->id . '" style="margin-right: 1%" class="btn-nwe3" title="Excluir Configuração"><i class="bx bx-trash-alt"></i></a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Excluir -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('treinos/excluirConfiguracao'); ?>" method="post">
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
    <form action="<?php echo site_url('treinos/excluirAgendamento'); ?>" method="post">
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

<!-- Tabela de Treinos Agendados -->
<div class="widget-box" style="margin-top: 20px;">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-calendar-check"></i>
        </span>
        <h5>Treinos Agendados</h5>
    </div>

<div class="widget-content nopadding">

<div style="padding: 15px;">
    <form action="<?= site_url('treinos') ?>" method="get" class="form-inline">
        <div class="form-group">
            <input type="text" name="pesquisa_cliente" value="<?= $this->input->get('pesquisa_cliente') ?>" class="form-control" placeholder="Nome do Cliente">
        </div>
        <div class="form-group">
            <input type="text" name="pesquisa_treino" value="<?= $this->input->get('pesquisa_treino') ?>" class="form-control" placeholder="Nome do Treino">
        </div>
        <div class="form-group">
            <input type="text" name="data_inicial" value="<?= $this->input->get('data_inicial') ?>" class="form-control datepicker" placeholder="Data de">
        </div>
        <div class="form-group">
            <input type="text" name="data_final" value="<?= $this->input->get('data_final') ?>" class="form-control datepicker" placeholder="Data até">
        </div>
        <div class="form-group">
            <select name="status" class="form-control">
                <option value="">Todos Status</option>
                <option value="Agendado" <?= $this->input->get('status') == 'Agendado' ? 'selected' : '' ?>>Agendado</option>
                <option value="Realizado" <?= $this->input->get('status') == 'Realizado' ? 'selected' : '' ?>>Realizado</option>
                <option value="Cancelado" <?= $this->input->get('status') == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="<?= site_url('treinos') ?>" class="btn btn-default">Limpar</a>
    </form>
</div>

<style>
    .form-inline .form-group { margin-right: 10px; margin-bottom: 10px; }
</style>
<style>
    .xdsoft_datetimepicker { z-index: 99999 !important; }
</style>


<table class="table table-bordered ">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Treino</th>
            <th>Data/Hora Início</th>
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
                case 'agendado': $cor = '#00cd00'; break;
                case 'realizado': $cor = '#436eee'; break;
                case 'cancelado': $cor = '#CD0000'; break;
            }
            echo '<tr>';
            echo '<td>' . $agendamento->id . '</td>';
            echo '<td>' . htmlspecialchars($agendamento->nome_cliente) . '</td>';
            echo '<td>' . htmlspecialchars($agendamento->nome_treino) . '</td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($agendamento->data_hora_inicio)) . '</td>';
            echo '<td><span class="badge" style="background-color: ' . $cor . '; border-color: ' . $cor . '">' . $agendamento->status . '</span></td>';
            echo '<td>';
            echo '<a href="' . base_url() . 'index.php/treinos/visualizarTreino/' . $agendamento->id . '" class="btn btn-info tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eTreino')) {
                echo '<button class="btn btn-primary tip-top btn-reagendar" data-id="' . $agendamento->id . '" title="Reagendar Treino"><i class="fas fa-calendar-alt"></i></button>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dTreino')) {
                echo '<a href="#modal-excluir-agendamento" role="button" data-toggle="modal" agendamento_id="' . $agendamento->id . '" class="btn btn-danger tip-top" title="Excluir Agendamento"><i class="fas fa-trash-alt"></i></a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
</div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', 'a[data-toggle="modal"]', function(event) {
            var configuracao_id = $(this).attr('configuracao_id');
            $('#configuracao_id').val(configuracao_id);

            var agendamento_id = $(this).attr('agendamento_id');
            if (agendamento_id) {
                $('#agendamento_id_excluir').val(agendamento_id);
            }
        });
    });
</script>

<!-- Modal Reagendamento -->
<div id="modal-reagendar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('treinos/reagendarTreino'); ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Reagendar Treino</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="id_agendamento" name="id_agendamento" value="" />
            <div class="control-group">
                <label for="treino_config_id_reagendar" class="control-label">Tipo de Treino<span class="required">*</span></label>
                <div class="controls">
                    <select name="treino_config_id" id="treino_config_id_reagendar" class="span12" required>
                        <option value="">Selecione um treino</option>
                        <?php foreach ($results as $treino) : ?>
                            <option value="<?= $treino->id ?>" data-duracao="<?= $treino->duracao_minutos ?>" data-preco-sem="<?= $treino->preco_sem_instrutor ?>" data-preco-com="<?= $treino->preco_com_instrutor ?>">
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
                <label for="data_hora_reagendamento" class="control-label">Nova Data e Hora<span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="data_hora_reagendamento" id="data_hora_reagendamento" class="span12" required>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Voltar</span></button>
            <button class="button btn btn-primary"><span class="button__icon"><i class="bx bx-calendar-check"></i></span> <span class="button__text2">Reagendar</span></button>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetimepicker.min.css"/ >
<script src="<?php echo base_url(); ?>assets/js/jquery.datetimepicker.full.min.js"></script>
<script>
$(document).ready(function() {
    $(".datepicker").datepicker({ dateFormat: 'dd/mm/yy' });

    $(document).on('click', '.btn-reagendar', function() {
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
            success: function(response) {
                if (response && !response.error) {
                    $('#treino_config_id_reagendar').val(response.config_id);
                    $('#data_hora_reagendamento').val(new Date(response.data_hora_inicio).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }).replace(',', ''));
                    
                    if (response.com_instrutor == '1') {
                        $('#com_instrutor_reagendar').prop('checked', true).trigger('change');
                        // A função buscarInstrutoresReagendar() é chamada pelo trigger.
                        // Usamos um listener para o evento ajaxStop para garantir que a seleção do instrutor
                        // ocorra somente após a lista de instrutores ser carregada.
                        $(document).one('ajaxStop', function() {
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
            error: function() {
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
    $('#com_instrutor_reagendar, #treino_config_id_reagendar, #data_hora_reagendamento').on('change', function() {
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

        if (configId && dataHora) {
            $('#instrutor_id_reagendar').prop('disabled', true).html('<option>Buscando...</option>');
            $.get('<?= site_url('treinos/getInstrutoresDisponiveis') ?>', { config_id: configId, data_hora: dataHora }, function(data) {
                var options = '<option value="">Qualquer um disponível</option>';
                data.forEach(function(instrutor) {
                    options += '<option value="' + instrutor.id + '">' + instrutor.nome + '</option>';
                });
                $('#instrutor_id_reagendar').html(options).prop('disabled', false);
            }, 'json');
        }
    }
});
</script>