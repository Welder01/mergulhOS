<style>
    .card-summary {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 5px solid #28b779;
        transition: transform 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-summary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .card-summary h3 {
        margin: 0;
        font-size: 26px;
        color: #333;
        font-weight: 600;
    }

    .card-summary p {
        margin: 5px 0 0;
        color: #777;
        font-size: 14px;
        font-weight: 500;
    }

    .card-summary .icon-bg {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 35px;
        opacity: 0.15;
    }

    /* Activity Card Styling */
    .activity-card {
        background: #fff;
        border: 1px solid #e1e4e8;
        border-radius: 8px;
        margin-bottom: 12px;
        padding: 15px 20px;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }

    .activity-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-color: #d1d4d8;
    }

    .activity-card.course {
        border-left: 4px solid #27a9e3;
    }

    .activity-card.trip {
        border-left: 4px solid #f74d4d;
    }

    .activity-card.training {
        border-left: 4px solid #ad32c7;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-header {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .activity-type-badge {
        font-size: 0.75em;
        padding: 3px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-right: 10px;
        background: #f0f2f5;
        color: #666;
    }

    .activity-type-badge.course {
        color: #27a9e3;
        background: #eaf7fd;
    }

    .activity-type-badge.trip {
        color: #f74d4d;
        background: #feeced;
    }

    .activity-type-badge.training {
        color: #ad32c7;
        background: #f6eafb;
    }

    .activity-title {
        font-size: 1.1em;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .activity-meta {
        color: #6c757d;
        font-size: 0.85em;
        display: flex;
        gap: 15px;
        margin-top: 4px;
    }

    .activity-meta i {
        margin-right: 4px;
        width: 14px;
        text-align: center;
    }

    .activity-financial {
        text-align: right;
        min-width: 150px;
        padding-left: 20px;
        border-left: 1px solid #f0f2f5;
    }

    .financial-value {
        font-size: 1.2em;
        font-weight: 700;
        color: #28b779;
        display: block;
    }

    .financial-status {
        display: inline-block;
        font-size: 0.75em;
        padding: 2px 8px;
        border-radius: 4px;
        margin-top: 4px;
    }

    .status-paid {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    /* Custom Tab Styling */
    .nav-tabs>li>a {
        color: #ccc;
        /* Inactive color */
    }

    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:hover,
    .nav-tabs>li.active>a:focus {
        color: #333;
        /* Active color */
        background-color: #fff;
    }

    .nav-tabs>li>a:hover {
        color: #fff;
    }
</style>

<?php
$total_receber = 0;
$total_recebido = 0;
$total_bloqueado = 0;

// Remove calculation from Lancamentos as per user request:
// "O TOTAL RECEBIDO É SÓ A SOMA DAS ATRIBUIÇÕES... O LANÇAMENTO QUE É O CONTROLE DO ADMINISTRADOR."

// Helper to process value
function process_activity_value($activity, &$t_recebido, &$t_receber, &$t_bloqueado)
{
    $valor = 0;
    if (isset($activity->tipo_pagamento) && $activity->tipo_pagamento == 'hora' && !empty($activity->hora_inicio) && !empty($activity->hora_fim)) {
        $inicio = new DateTime($activity->hora_inicio);
        $fim = new DateTime($activity->hora_fim);
        $diff = $inicio->diff($fim);
        $horas = $diff->h + ($diff->i / 60);
        $valor = $horas * $activity->valor_pagamento;
    } else {
        $valor = isset($activity->valor_pagamento) ? $activity->valor_pagamento : 0;
    }

    // Logic:
    // Paid -> Recebido (Strictly from activity status)
    // Accepted + Pending Payment -> Bloqueado
    // Pending Acceptance -> A Receber
    // Rejected -> Ignored

    $status_pagamento = isset($activity->status_pagamento) ? $activity->status_pagamento : 'pendente';
    $aceite = isset($activity->aceite) ? $activity->aceite : null; // null = pending

    if ($status_pagamento == 'pago') {
        $t_recebido += $valor;
    } elseif ($aceite === '1' || $aceite === 1) {
        $t_bloqueado += $valor;
    } elseif (is_null($aceite)) {
        $t_receber += $valor;
    }

    return $valor;
}


// Calculate from Courses
foreach ($cursos as $c) {
    $c->valor_total = process_activity_value($c, $total_recebido, $total_receber, $total_bloqueado);
}

// Calculate from Viagens
foreach ($viagens as $v) {
    $v->valor_total = process_activity_value($v, $total_recebido, $total_receber, $total_bloqueado);
}

// Calculate from Treinos
foreach ($treinos as $t) {
    $t->valor_total = process_activity_value($t, $total_recebido, $total_receber, $total_bloqueado);
}

// --- Hoist Event Aggregation for use in both tabs ---
$events = [];
if (!empty($cursos)) {
    foreach ($cursos as $c) {
        $c->type = 'course';
        $c->sort_date = $c->data_inicio;
        $events[] = $c;
    }
}
if (!empty($viagens)) {
    foreach ($viagens as $v) {
        $v->type = 'trip';
        $v->sort_date = $v->data_partida;
        $events[] = $v;
    }
}
if (!empty($treinos)) {
    foreach ($treinos as $t) {
        $t->type = 'training';
        $t->sort_date = $t->data_hora_inicio;
        $events[] = $t;
    }
}
usort($events, function ($a, $b) {
    return strtotime($b->sort_date) - strtotime($a->sort_date); // Descending
});
// ----------------------------------------------------
?>

<!-- Row 1: Financial Cards -->
<div class="row-fluid" style="margin-top: 10px;">
    <div class="span4">
        <div class="card-summary" style="border-left-color: #28b779; position: relative;">
            <i class="fas fa-check-circle icon-bg" style="color: #28b779;"></i>
            <h3>R$ <?= number_format($total_recebido, 2, ',', '.') ?></h3>
            <p>Total Recebido</p>
        </div>
    </div>
    <div class="span4">
        <div class="card-summary" style="border-left-color: #27a9e3; position: relative;">
            <i class="fas fa-lock icon-bg" style="color: #27a9e3;"></i>
            <h3>R$ <?= number_format($total_bloqueado, 2, ',', '.') ?></h3>
            <p>Bloqueado / Agendado</p>
        </div>
    </div>
    <div class="span4">
        <div class="card-summary" style="border-left-color: #e3a21a; position: relative;">
            <i class="fas fa-clock icon-bg" style="color: #e3a21a;"></i>
            <h3>R$ <?= number_format($total_receber, 2, ',', '.') ?></h3>
            <p>A Receber (Pendente Aceite)</p>
        </div>
    </div>
</div>

<!-- Row 2: Main Widget -->
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box" style="border: none; box-shadow: none; background: none;">
            <div class="widget-title" style="background: none; border-bottom: 1px solid #ddd; margin-bottom: 15px;">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tabAtividades">Minhas Atividades</a></li>
                    <li><a data-toggle="tab" href="#tabFinanceiro">Extrato Financeiro</a></li>
                </ul>
            </div>
            <div class="widget-content tab-content" style="border: none; padding: 0;">

                <!-- Activities Tab -->
                <div id="tabAtividades" class="tab-pane active">
                    <?php if (empty($events)): ?>
                        <div class="alert alert-info">Nenhuma atividade atribuída encontrada.</div>
                    <?php else: ?>

                        <?php foreach ($events as $event): ?>
                            <div class="activity-card <?= $event->type ?>">

                                <div class="activity-content">
                                    <div class="activity-header">
                                        <span class="activity-type-badge <?= $event->type ?>">
                                            <?= $event->type == 'course' ? 'Curso' : ($event->type == 'trip' ? 'Viagem' : 'Treino') ?>
                                        </span>
                                        <h4 class="activity-title">
                                            <?= isset($event->nome_curso) ? $event->nome_curso : (isset($event->nome_viagem) ? $event->nome_viagem : $event->nome_treino) ?>
                                        </h4>
                                    </div>

                                    <div class="activity-meta">
                                        <span>
                                            <i class="far fa-calendar-alt"></i>
                                            <?= date('d/m/Y H:i', strtotime($event->sort_date)) ?>
                                            <?php if (isset($event->data_fim))
                                                echo ' - ' . date('d/m/Y', strtotime($event->data_fim)); ?>
                                            <?php if (isset($event->data_retorno))
                                                echo ' - ' . date('d/m/Y', strtotime($event->data_retorno)); ?>
                                            <?php if (isset($event->data_hora_fim))
                                                echo ' - ' . date('H:i', strtotime($event->data_hora_fim)); ?>
                                        </span>

                                        <?php if ($event->type == 'course'): ?>
                                            <span><i class="fas fa-user-clock"></i> Atribuído:
                                                <?= date('d/m/y', strtotime($event->data_atribuicao)) ?></span>
                                        <?php endif; ?>

                                        <?php if (!empty($event->proposito)): ?>
                                            <span><i class="fas fa-tag"></i> <?= $event->proposito ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="activity-financial">
                                    <?php
                                    $val_color = '#333'; // Default
                                    if (isset($event->status_pagamento) && $event->status_pagamento == 'pago')
                                        $val_color = '#28b779'; // Green if paid
                                    elseif (isset($event->aceite) && $event->aceite == 1)
                                        $val_color = '#27a9e3'; // Blue if blocked
                                    elseif (!isset($event->aceite))
                                        $val_color = '#e3a21a'; // Orange if pending accept
                                    ?>
                                    <span class="financial-value" style="color: <?= $val_color ?>;">R$
                                        <?= number_format($event->valor_total, 2, ',', '.') ?></span>

                                    <?php if (isset($event->status_pagamento) && $event->status_pagamento == 'pago'): ?>
                                        <span class="financial-status status-paid">Pago</span>
                                        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'faturarAtribuicao')): ?>
                                            <button class="btn btn-mini btn-danger"
                                                onclick="estornarPagamento(<?= $event->id ?>, '<?= $event->type ?>')"
                                                title="Desfazer Pagamento" style="margin-top: 5px; width: 100%;"><i
                                                    class="fas fa-undo"></i> Estornar</button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- Acceptance Logic -->
                                        <?php if (is_null($event->aceite)): ?>
                                            <div class="row-fluid" style="margin-top: 5px;">
                                                <button class="btn btn-success btn-mini tip-top" title="Aceitar"
                                                    onclick="atualizarAceite(<?= $event->id ?>, '<?= $event->type ?>', 1)"><i
                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-danger btn-mini tip-top" title="Recusar"
                                                    onclick="atualizarAceite(<?= $event->id ?>, '<?= $event->type ?>', 0)"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        <?php elseif ($event->aceite == 1): ?>
                                            <span class="badge badge-info" style="margin-top: 5px;">Aceito</span>

                                            <!-- Admin Payment and Cancel Buttons -->
                                            <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'faturarAtribuicao')): ?>
                                                <div style="margin-top: 5px;">
                                                    <button class="btn btn-primary btn-mini tip-top" title="Gerar Pagamento"
                                                        onclick="faturarAtividade(<?= $event->id ?>, '<?= $event->type ?>', '<?= $event->valor_total ?>', 'Pagamento <?= ucfirst($event->type) ?> - <?= addslashes(isset($event->nome_curso) ? $event->nome_curso : (isset($event->nome_viagem) ? $event->nome_viagem : $event->nome_treino)) ?>')">
                                                        <i class="fas fa-money-bill-wave"></i> Pagar
                                                    </button>

                                                    <button class="btn btn-danger btn-mini tip-top" title="Cancelar Atribuição"
                                                        onclick="cancelarAtribuicao(<?= $event->id ?>, '<?= $event->type ?>')">
                                                        <i class="fas fa-trash"></i> Cancelar
                                                    </button>
                                                </div>
                                            <?php endif; ?>

                                        <?php elseif ($event->aceite == 0): ?>
                                            <span class="badge badge-important" style="margin-top: 5px;">Recusado</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Financial Tab -->
                <div id="tabFinanceiro" class="tab-pane">
                    <div
                        style="background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <table class="table table-bordered table-striped custom-table" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th style="background-color: #2e363f; color: white;">Descrição</th>
                                    <th style="background-color: #2e363f; color: white;">Data Vencimento</th>
                                    <th style="background-color: #2e363f; color: white;">Valor</th>
                                    <th style="background-color: #2e363f; color: white;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($events)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">Nenhuma atividade
                                            encontrada.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($events as $event): ?>
                                        <?php
                                        // Determine Descricao
                                        $descricao = isset($event->nome_curso) ? "Curso: " . $event->nome_curso : (isset($event->nome_viagem) ? "Viagem: " . $event->nome_viagem : "Treino: " . $event->nome_treino);

                                        // Determine Data
                                        $data_ref = date('d/m/Y', strtotime($event->sort_date));

                                        // Determine Status Label
                                        $status_pagamento = isset($event->status_pagamento) ? $event->status_pagamento : 'pendente';
                                        $aceite = isset($event->aceite) ? $event->aceite : null;

                                        $status_label = '<span class="badge badge-warning">Pendente</span>';
                                        if ($status_pagamento == 'pago') {
                                            $status_label = '<span class="badge badge-success">Recebido</span> <button class="btn btn-mini btn-danger" onclick="estornarPagamento(' . $event->id . ', \'' . $event->type . '\')" title="Desfazer Pagamento" style="margin-left:5px;"><i class="fas fa-undo"></i></button>';
                                        } elseif ($aceite === '1' || $aceite === 1) {
                                            $status_label = '<span class="badge badge-info">Bloqueado/Agendado</span>';
                                        } elseif ($aceite === '0' || $aceite === 0) {
                                            $status_label = '<span class="badge badge-important">Recusado</span>';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $descricao ?></td>
                                            <td><?= $data_ref ?></td>
                                            <td style="font-weight: bold; color: #2e363f;">R$
                                                <?= number_format($event->valor_total, 2, ',', '.') ?>
                                            </td>
                                            <td>
                                                <?= $status_label ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function atualizarAceite(id, type, status) {
        var action = status == 1 ? "aceitar" : "recusar";

        if (status == 1) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Ao ACEITAR esta atividade, você confirma sua participação. O pagamento permanecerá bloqueado até a conclusão do serviço!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, aceitar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed || result.value) { // Support for different SweetAlert versions
                    procederAtualizacao(id, type, status);
                }
            });
        } else {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Deseja realmente RECUSAR esta atividade? Esta ação informará que você não participará.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, recusar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    procederAtualizacao(id, type, status);
                }
            });
        }
    }

    function procederAtualizacao(id, type, status) {
        $.ajax({
            url: '<?= base_url() ?>index.php/atividades/atualizar_aceite',
            type: 'POST',
            dataType: 'json',
            data: { id: id, type: type, status: status },
            success: function (response) {
                if (response.result) {
                    Swal.fire(
                        'Sucesso!',
                        response.message,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Erro!',
                        response.message,
                        'error'
                    );
                }
            },
            error: function () {
                Swal.fire(
                    'Erro!',
                    'Ocorreu um erro ao processar a solicitação.',
                    'error'
                );
            }
        });
    }

    function estornarPagamento(id, type) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você deseja estornar este pagamento? O lançamento financeiro será excluído.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, estornar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/atividades/estornar_pagamento",
                    type: 'POST',
                    data: {
                        id: id,
                        type: type
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.result == true) {
                            Swal.fire(
                                'Estornado!',
                                'O pagamento foi estornado com sucesso.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                data.message,
                                'error'
                            );
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire(
                            'Erro!',
                            'Erro ao conectar com servidor: ' + textStatus,
                            'error'
                        );
                    }
                });
            }
        });
    }

    function faturarAtividade(id, type, valor, descricao) {
        Swal.fire({
            title: 'Gerar Pagamento',
            html: `
                <p>Gerar pagamento de <b>R$ ` + parseFloat(valor).toFixed(2).replace('.', ',') + `</b>?</p>
                <div style='text-align: left; margin-top: 15px;'>
                    <label>Forma de Pagamento</label>
                    <select id='forma_pgto' class='swal2-input' style='width: 100%; margin: 5px 0;'>
                        <option value='Dinheiro'>Dinheiro</option>
                        <option value='Pix'>Pix</option>
                        <option value='Boleto'>Boleto</option>
                        <option value='Cartão de Crédito'>Cartão de Crédito</option>
                        <option value='Cartão de Débito'>Cartão de Débito</option>
                        <option value='Cheque'>Cheque</option>
                        <option value='Cheque Pré-datado'>Cheque Pré-datado</option>
                        <option value='Depósito'>Depósito</option>
                        <option value='Transferência DOC'>Transferência DOC</option>
                        <option value='Transferência TED'>Transferência TED</option>
                        <option value='Promissória'>Promissória</option>
                    </select>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, faturar!',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                return document.getElementById('forma_pgto').value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var forma_pgto = result.value || document.getElementById('forma_pgto').value;

                // Show loading
                Swal.fire({
                    title: 'Processando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                         Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo base_url() ?>index.php/atividades/faturar_atividade',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        type: type,
                        valor: valor,
                        descricao: descricao,
                        forma_pgto: forma_pgto
                    },
                    success: function(response) {
                        if (response.result) {
                            Swal.fire(
                                'Sucesso!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao processar a solicitação.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function estornarPagamento(id, type) {
        Swal.fire({
            title: 'Confirmar Estorno?',
            text: "Isso excluirá o lançamento financeiro e a atividade voltará a ser pendente. Tem certeza?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, estornar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                 // Show loading
                Swal.fire({
                    title: 'Processando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                         Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo base_url() ?>index.php/atividades/estornar_pagamento',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        type: type
                    },
                    success: function(response) {
                        if (response.result) {
                            Swal.fire(
                                'Estornado!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Erro!',
                            'Ocorreu um erro ao processar a solicitação.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function cancelarAtribuicao(id, type) {
        Swal.fire({
            title: 'Cancelar Atribuição?',
            text: "Você deseja realmente remover esta atribuição? O instrutor será desvinculado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, cancelar!',
            cancelButtonText: 'Não, manter'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                $.ajax({
                    url: '<?= base_url() ?>index.php/atividades/cancelar_atribuicao',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id, type: type },
                    success: function (response) {
                        if (response.result) {
                            Swal.fire(
                                'Cancelado!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Erro!',
                            'Erro ao cancelar atribuição.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>