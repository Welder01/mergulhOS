<style>
    .card-summary {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 4px solid #28b779;
        transition: transform 0.2s;
        /* Ensure cards behave well in grid */
        box-sizing: border-box;
    }

    .card-summary:hover {
        transform: translateY(-2px);
    }

    .card-summary h3 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .card-summary p {
        margin: 5px 0 0;
        color: #777;
        font-size: 14px;
    }

    .card-summary .icon-bg {
        float: right;
        font-size: 30px;
        opacity: 0.2;
    }

    .activity-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        position: relative;
    }

    .activity-card.course {
        border-left: 4px solid #27a9e3;
    }

    .activity-card.trip {
        border-left: 4px solid #f74d4d;
    }

    .activity-date {
        font-weight: bold;
        color: #555;
        font-size: 0.9em;
    }

    .activity-title {
        font-size: 1.2em;
        margin: 5px 0;
        color: #333;
    }

    .activity-details {
        font-size: 0.9em;
        color: #666;
    }

    .badge-status {
        position: absolute;
        top: 15px;
        right: 15px;
    }
</style>

<?php
$total_receber = 0;
$total_recebido = 0;
foreach ($lancamentos as $l) {
    if ($l->baixado == 1) {
        $total_recebido += $l->valor;
    } else {
        $total_receber += $l->valor;
    }
}
?>

<!-- Row 1: Financial Cards -->
<div class="row-fluid" style="margin-top: 10px;">
    <div class="span6">
        <div class="card-summary" style="border-left-color: #28b779;">
            <i class="fas fa-check-circle icon-bg" style="color: #28b779;"></i>
            <h3>R$ <?= number_format($total_recebido, 2, ',', '.') ?></h3>
            <p>Total Recebido</p>
        </div>
    </div>
    <div class="span6">
        <div class="card-summary" style="border-left-color: #e3a21a;">
            <i class="fas fa-clock icon-bg" style="color: #e3a21a;"></i>
            <h3>R$ <?= number_format($total_receber, 2, ',', '.') ?></h3>
            <p>A Receber</p>
        </div>
    </div>
</div>

<!-- Row 2: Main Widget -->
<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tabAtividades">Minhas Atividades</a></li>
                    <li><a data-toggle="tab" href="#tabFinanceiro">Extrato Financeiro</a></li>
                </ul>
            </div>
            <div class="widget-content tab-content">

                <!-- Activities Tab -->
                <div id="tabAtividades" class="tab-pane active">
                    <?php if (empty($cursos) && empty($viagens)): ?>
                        <div class="alert alert-info">Nenhuma atividade atribuída encontrada.</div>
                    <?php else: ?>
                        <!-- <h4>Próximos Eventos</h4> -->
                        <?php
                        // Merge and sort
                        $events = [];
                        foreach ($cursos as $c) {
                            $c->type = 'course';
                            $c->sort_date = $c->data_inicio;
                            $events[] = $c;
                        }
                        foreach ($viagens as $v) {
                            $v->type = 'trip';
                            $v->sort_date = $v->data_partida;
                            $events[] = $v;
                        }
                        usort($events, function ($a, $b) {
                            return strtotime($b->sort_date) - strtotime($a->sort_date); // Descending
                        });
                        ?>

                        <?php foreach ($events as $event): ?>
                            <div class="activity-card <?= $event->type ?>">
                                <span class="badge badge-<?= $event->type == 'course' ? 'info' : 'important' ?> badge-status">
                                    <?= $event->type == 'course' ? 'Curso' : 'Viagem' ?>
                                </span>
                                <div class="activity-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d/m/Y', strtotime($event->sort_date)) ?>
                                    <?php if (isset($event->data_fim))
                                        echo ' - ' . date('d/m/Y', strtotime($event->data_fim)); ?>
                                    <?php if (isset($event->data_retorno))
                                        echo ' - ' . date('d/m/Y', strtotime($event->data_retorno)); ?>
                                </div>
                                <div class="activity-title">
                                    <?= isset($event->nome_curso) ? $event->nome_curso : $event->nome_viagem ?>
                                </div>
                                <div class="activity-details">
                                    <?php if ($event->type == 'course'): ?>
                                        <p><i class="fas fa-info-circle"></i> Atribuído em:
                                            <?= date('d/m/Y', strtotime($event->data_atribuicao)) ?>
                                        </p>
                                    <?php else: ?>
                                        <p><i class="fas fa-money-bill-wave"></i> Status Pagamento:
                                            <strong><?= ucfirst($event->status_pagamento ?: 'N/A') ?></strong>
                                        </p>
                                        <?php if ($event->proposito): ?>
                                            <p>Propósito: <?= $event->proposito ?></p><?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Financial Tab -->
                <div id="tabFinanceiro" class="tab-pane">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Descrição</th>
                                <th>Data Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lancamentos)): ?>
                                <tr>
                                    <td colspan="5">Nenhum lançamento encontrado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($lancamentos as $l): ?>
                                    <tr>
                                        <td><?= $l->idLancamentos ?></td>
                                        <td><?= $l->descricao ?></td>
                                        <td><?= date('d/m/Y', strtotime($l->data_vencimento)) ?></td>
                                        <td>R$ <?= number_format($l->valor, 2, ',', '.') ?></td>
                                        <td>
                                            <?php if ($l->baixado == 1): ?>
                                                <span class="label label-success">Pago</span>
                                            <?php else: ?>
                                                <span class="label label-warning">Pendente</span>
                                            <?php endif; ?>
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