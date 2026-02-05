<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<!-- Adicionando FullCalendar e jQuery UI para o calendário e datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" />
<!-- Adicionando a nova biblioteca DateTimePicker -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.datetimepicker.min.css"/ >
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #28a745;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
<script src="<?php echo base_url(); ?>assets/js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/fullcalendar.min.js"></script>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon"><i class="fas fa-dumbbell"></i></span>
        <h5>Agendamento de Treinos</h5>
    </div>
    <div class="widget-content">
        <div class="row-fluid">
            <div class="span12">
                <h4>Agendar Novo Treino</h4>
                <form action="<?php echo site_url('mine/agendarTreino'); ?>" method="post" id="formAgendarTreino">
                    <div class="span4">
                        <label for="treino_config_id">Tipo de Treino<span class="required">*</span></label>
                        <select name="treino_config_id" id="treino_config_id" class="span12" required>
                            <option value="">Selecione um treino</option>
                            <?php foreach ($treinos_config as $treino) : ?>
                                <option value="<?= $treino->id ?>" data-preco-sem="<?= $treino->preco_sem_instrutor ?>" data-preco-com="<?= $treino->preco_com_instrutor ?>">
                                    <?= htmlspecialchars($treino->nome) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="span4">
                        <label for="data_hora_treino">Data e Hora<span class="required">*</span></label>
                        <input type="text" name="data_hora_treino" id="data_hora_treino" class="span12" required readonly="readonly" placeholder="Selecione o tipo de treino primeiro">
                    </div>
                    <div class="span3" style="padding-top: 25px; display: flex; align-items: center; gap: 10px;">
                        <label class="switch">
                            <input type="checkbox" name="com_instrutor" id="com_instrutor" value="1">
                            <span class="slider"></span>
                        </label>
                        <span>Com Instrutor</span>
                    </div>
                    <div class="span4" id="instrutor_div" style="display: none;">
                        <label for="instrutor_id">Instrutor</label>
                        <select name="instrutor_id" id="instrutor_id" class="span12" disabled>
                            <option value="">Selecione um instrutor</option>
                        </select>
                    </div>
                    <div class="span12" id="instrutor_message" style="display: none; margin-left: 0;"></div>
                    <div class="span12" style="margin-left: 0;">
                        <div id="info-preco" style="font-size: 1.2em; margin-top: 10px;"></div>
                    </div>
                    <div class="span12" style="margin-left: 0; text-align: center; padding-top: 20px;">
                        <button id="btn-agendar" class="button btn btn-success"><span class="button__icon"><i class="bx bx-calendar-check"></i></span><span class="button__text2">Agendar</span></button>
                    </div>
                </form>
            </div>
        </div>

        <hr>

        <div class="row-fluid">
            <div class="span12">
                <h4>Meus Treinos Agendados</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Treino</th>
                            <th>Data e Hora</th>
                            <th>Instrutor</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($meus_treinos) : ?>
                            <?php foreach ($meus_treinos as $agendamento) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($agendamento->nome_treino ?? 'N/A') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($agendamento->data_hora_inicio)) ?></td>
                                    <td><?= $agendamento->com_instrutor ? ($agendamento->nome_instrutor ?? 'Aguardando') : 'Sem instrutor' ?></td>
                                    <td>R$ <?= number_format($agendamento->valor_cobrado, 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($agendamento->status) ?></td>
                                    <td>
                                        <button class="btn btn-info btn-mini tip-top btn-visualizar-treino" data-id="<?= $agendamento->id ?>" title="Visualizar Detalhes"><i class="fas fa-eye"></i></button>
                                        <?php if ($agendamento->status == 'Agendado') : ?>
                                            <a href="#modal-cancelar" data-toggle="modal" role="button" data-id="<?= $agendamento->id ?>" class="btn btn-danger btn-mini tip-top" title="Cancelar Agendamento"><i class="fas fa-times"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Nenhum treino agendado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalhes do Treino -->
<div class="modal fade" id="treinoModal" tabindex="-1" role="dialog" aria-labelledby="treinoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 class="modal-title" id="treinoModalLabel">Detalhes do Treino Agendado</h5>
            </div>
            <div class="modal-body">
                <div id="loading-treino" style="display: none; text-align: center;">
                    <img src="<?= base_url('assets/img/ajax-loader.gif') ?>" alt="Carregando..." style="width: 50px;"/>
                </div>
                <div id="detalhes-treino-content">
                    <p><strong>Treino:</strong> <span id="modal-nome-treino"></span></p>
                    <p><strong>Data e Hora:</strong> <span id="modal-data-hora"></span></p>
                    <p><strong>Duração:</strong> <span id="modal-duracao"></span></p>
                    <p><strong>Status:</strong> <span id="modal-status"></span></p>
                    <p><strong>Valor:</strong> R$ <span id="modal-valor"></span></p>
                    <p><strong>Instrutor:</strong> <span id="modal-instrutor"></span></p>
                    <p><strong>Observações:</strong></p>
                    <p><span id="modal-observacoes"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    #detalhes-treino-content p { margin-bottom: 10px; }
</style>

<!-- Modal Cancelar -->
<div id="modal-cancelar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo site_url('mine/cancelarTreino'); ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Cancelar Agendamento</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idAgendamento" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente cancelar este agendamento?</h5>
            <p style="text-align: center">A política de cancelamento se aplicará a esta ação.</p>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Voltar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class="bx bx-trash"></i></span> <span class="button__text2">Cancelar</span></button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var allowedTimes = [];

        // Adiciona uma regra de validação customizada para o select de instrutor
        $.validator.addMethod("requiredIfChecked", function(value, element) {
            if ($("#com_instrutor").is(":checked")) {
                // Se a opção "Com Instrutor" está marcada, o campo não pode ser vazio
                return value !== "";
            }
            return true;
        }, "Por favor, selecione um instrutor.");

        var disabledDates = [];

        $('#data_hora_treino').datetimepicker({
            format:'d/m/Y H:i',
            step: 30, // O passo será definido pela duração do treino
            minDate: 0, // Não permite agendar no passado
            disabled: true, // Inicia desabilitado
            onGenerate: function(ct) {
                // Esta função é chamada sempre que o calendário é gerado (ex: mudança de mês)
                // Aqui você pode buscar os horários disponíveis para o mês inteiro
            },
            onShow: function(ct, $i) {
                this.setOptions({
                    allowTimes: allowedTimes,
                    disabledDates: disabledDates.map(d => d.split(' ')[0]) // Desabilita o dia inteiro se todos os horários estiverem ocupados
                })
            },
            onSelectDate: function() {
                // Quando uma data é selecionada, busca os instrutores se necessário
                buscarInstrutores();
            }
        });

        function buscarInstrutores() {
            var dataHora = $('#data_hora_treino').val();
            var configId = $('#treino_config_id').val();

            $('#btn-agendar').prop('disabled', false);
            $('#instrutor_message').hide();

            if (dataHora && configId && $('#com_instrutor').is(':checked')) {
                $('#instrutor_id').prop('disabled', true).html('<option>Buscando...</option>');
                $.ajax({
                    url: '<?= site_url('mine/getInstrutoresDisponiveis') ?>',
                    type: 'GET',
                    data: { data_hora: dataHora, config_id: configId },
                    dataType: 'json',
                    success: function(response) {
                        var options = '<option value="">Qualquer um disponível</option>';
                        if (response.length > 0) {
                            response.forEach(function(instrutor) {
                                options += '<option value="' + instrutor.id + '">' + instrutor.nome + '</option>';
                            });
                        } else if ($('#com_instrutor').is(':checked')) {
                            options = '<option value="">Nenhum disponível</option>';
                            $('#instrutor_message').html('<div class="alert alert-warning">Não há instrutores disponíveis para este treino neste horário.</div>').show();
                            $('#btn-agendar').prop('disabled', true);
                        }
                        $('#instrutor_id').html(options).prop('disabled', false);
                    },
                    error: function() {
                        $('#instrutor_id').html('<option value="">Erro ao buscar</option>').prop('disabled', true);
                    }
                });
            }
        }

        // A função buscarInstrutores será chamada no 'onSelectDate' e no 'change' do checkbox
        $('#data_hora_treino').on('change', function() {
            if ($('#com_instrutor').is(':checked')) buscarInstrutores();
        });

        $('#treino_config_id').change(function() {
            var config_id = $('#treino_config_id').val();
            var datetimepicker = $('#data_hora_treino');

            if (config_id)
            {
                datetimepicker.val('').attr('placeholder', 'Carregando horários...').prop('disabled', true);
                $.ajax({
                    url: '<?= site_url('mine/getHorariosDisponiveis') ?>',
                    type: 'GET',
                    data: { config_id: config_id },
                    dataType: 'json',
                    success: function(response)
                    {
                        datetimepicker.datetimepicker('destroy'); // Destrói a instância anterior
                        datetimepicker.datetimepicker({ // Cria uma nova com as opções corretas
                            allowTimes: response.allowedTimes || [],
                            disabledWeekDays: response.disabledWeekDays || [],
                            format: 'd/m/Y H:i',
                            minDate: 0, // A partir de hoje
                            step: parseInt(response.duration) || 30,
                        });
                        datetimepicker.attr('placeholder', 'Selecione a data e hora').prop('disabled', false);
                    },
                    error: function() {
                         datetimepicker.attr('placeholder', 'Erro ao buscar horários.').prop('disabled', true);
                    }
                });
            }
        });

        // Lógica para mostrar o preço dinamicamente
        $('#treino_config_id, #com_instrutor').change(function(e) {
            var option = $('#treino_config_id').find('option:selected');
            var comInstrutor = $('#com_instrutor').is(':checked');
            var preco = comInstrutor ? option.data('preco-com') : option.data('preco-sem');
            
            if (comInstrutor) {
                $('#instrutor_div').slideDown();
                // Se o evento foi disparado pelo toggle e uma data já está selecionada, busca instrutores
                if (e.target.id === 'com_instrutor' && $('#data_hora_treino').val()) {
                    buscarInstrutores();
                }
            } else {
                $('#instrutor_div').slideUp();
                $('#btn-agendar').prop('disabled', false);
                $('#instrutor_message').hide();
            }

            if(preco) {
                $('#info-preco').html('Valor do treino: <strong>R$ ' + parseFloat(preco).toFixed(2).replace('.', ',') + '</strong>');
            } else {
                $('#info-preco').html('');
            }
        }).trigger('change');

        // Validação do formulário
        $('#formAgendarTreino').validate({
            rules: {
                instrutor_id: {
                    requiredIfChecked: true
                }
            },
            errorClass: "help-inline",
        });

        $(document).on('click', 'a[data-toggle="modal"]', function(event) {
            var id = $(this).data('id');
            $('#idAgendamento').val(id);
        });

        $(document).on('click', '.btn-visualizar-treino', function() {
            var treinoId = $(this).data('id');
            $('#detalhes-treino-content').hide();
            $('#loading-treino').show();
            $('#treinoModal').modal('show');

            $.ajax({
                url: '<?= site_url('treinos/getTreinoJson') ?>',
                type: 'POST',
                data: {
                    id: treinoId,
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('#detalhes-treino-content').html('<p class="text-error">' + response.error + '</p>');
                    } else {
                        var dataHora = new Date(response.data_hora_inicio).toLocaleString('pt-BR', {
                            day: '2-digit', month: '2-digit', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });
                        var valor = parseFloat(response.valor_cobrado).toLocaleString('pt-BR', {
                            minimumFractionDigits: 2, maximumFractionDigits: 2
                        });

                        $('#modal-nome-treino').text(response.nome_treino || 'N/A');
                        $('#modal-data-hora').text(dataHora);
                        $('#modal-duracao').text(response.duracao_minutos ? response.duracao_minutos + ' minutos' : 'N/A');
                        $('#modal-status').text(response.status || 'N/A');
                        $('#modal-valor').text(valor);
                        $('#modal-instrutor').text(response.nome_instrutor || 'Sem instrutor');
                        $('#modal-observacoes').text(response.observacoes || 'Nenhuma');
                    }
                    $('#loading-treino').hide();
                    $('#detalhes-treino-content').show();
                },
                error: function() {
                    $('#detalhes-treino-content').html('<p class="text-error">Ocorreu um erro ao buscar os detalhes do treino.</p>');
                    $('#loading-treino').hide();
                    $('#detalhes-treino-content').show();
                }
            });
        });
    });
</script>