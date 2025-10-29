<style>
    .nav-tabs { margin-bottom: 20px; }
    .tab-content { padding-top: 20px; }
    #calendario-viagens { margin-top: 30px; }
    .trip-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }
    .trip-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .trip-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .trip-card-header h5 {
        margin: 0;
        font-size: 1.4em;
        color: #333;
    }
    .trip-card-body p {
        margin: 0 0 10px;
        color: #555;
    }
    .trip-card-footer {
        text-align: right;
        margin-top: 20px;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        color: #fff;
        font-size: 0.9em;
        font-weight: bold;
    }
    .filter-buttons {
        margin-bottom: 20px;
    }
</style>
<!-- FullCalendar -->
<link href='<?php echo base_url(); ?>assets/css/fullcalendar.css' rel='stylesheet' />
<script src='<?php echo base_url(); ?>assets/js/fullcalendar.min.js'></script>
<script src='<?php echo base_url(); ?>assets/js/locale/pt-br.js'></script>


<div class="widget-box">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-plane"></i>
        </span>
        <h5>Minhas Viagens</h5>
    </div>
    <div class="widget-content" style="padding: 20px;">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#minhas">Minhas Viagens</a></li>
            <li><a data-toggle="tab" href="#disponiveis">Viagens Disponíveis</a></li>
            <li><a data-toggle="tab" href="#calendario">Calendário</a></li>
        </ul>

        <div class="tab-content">
            <div id="minhas" class="tab-pane fade in active">
                <?php if (!$minhas_viagens) : ?>
                    <div class="alert alert-info">Você não está inscrito em nenhuma viagem.</div>
                <?php else : ?>
                    <?php foreach ($minhas_viagens as $r) :
                        $dataPartida = date('d/m/Y', strtotime($r->data_partida));
                        $dataRetorno = $r->data_retorno ? date('d/m/Y', strtotime($r->data_retorno)) : 'Não definida';
                        $cor = '#E0E4CC'; $corTexto = '#000';
                        switch ($r->status) {
                            case 'Agendada': $cor = '#8A2BE2'; $corTexto = '#fff'; break;
                            case 'Em andamento': $cor = '#436eee'; $corTexto = '#fff'; break;
                            case 'Finalizada': $cor = '#333'; $corTexto = '#fff'; break;
                        }
                    ?>
                        <div class="trip-card">
                            <div class="trip-card-header">
                                <h5><?php echo htmlspecialchars($r->nome_viagem); ?></h5>
                                <span class="status-badge" style="background-color: <?php echo $cor; ?>; color: <?php echo $corTexto; ?>;"><?php echo htmlspecialchars($r->status); ?></span>
                            </div>
                            <div class="trip-card-body">
                                <p><strong><i class="fas fa-calendar-alt"></i> Partida:</strong> <?php echo $dataPartida; ?></p>
                                <p><strong><i class="fas fa-calendar-check"></i> Retorno:</strong> <?php echo $dataRetorno; ?></p>
                            </div>
                            <div class="trip-card-footer">
                                <?php if ($r->status_pagamento != 'Pago' && $r->status != 'Finalizada') : ?>
                                    <a href="<?php echo base_url() . 'index.php/mine/pagarViagem/' . $r->viagem_cliente_id; ?>" class="button btn btn-success"><span class="button__icon"><i class='bx bx-dollar-circle'></i></span><span class="button__text2">Pagar</span></a>
                                <?php endif; ?>
                                <a href="<?php echo base_url() . 'index.php/mine/visualizarViagem/' . $r->viagem_id; ?>" class="button btn btn-info"><span class="button__icon"><i class='bx bx-show'></i></span><span class="button__text2">Ver Detalhes</span></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="disponiveis" class="tab-pane fade">
                <div class="filter-buttons">
                    <strong>Filtrar por status:</strong><br>
                    <button class="btn btn-mini filter-btn active" data-status="all">Todos</button>
                    <button class="btn btn-mini filter-btn" data-status="Agendada">Prevista</button>
                    <button class="btn btn-mini filter-btn" data-status="Disponível">Disponível</button>
                    <button class="btn btn-mini filter-btn" data-status="Indisponível">Indisponível</button>
                    <button class="btn btn-mini filter-btn" data-status="Adiada">Adiada</button>
                    <button class="btn btn-mini filter-btn" data-status="Cancelada">Cancelada</button>
                </div>
                <?php if (!$viagens_disponiveis) : ?>
                    <div class="alert alert-info">Nenhuma viagem disponível no momento.</div>
                <?php else : ?>
                    <?php foreach ($viagens_disponiveis as $r) :
                        $dataPartida = date('d/m/Y', strtotime($r->data_partida));
                        $dataRetorno = $r->data_retorno ? date('d/m/Y', strtotime($r->data_retorno)) : 'Não definida';
                    ?>
                        <div class="trip-card" data-status="<?php echo htmlspecialchars($r->status); ?>">
                            <div class="trip-card-header">
                                <h5><?php echo htmlspecialchars($r->nome_viagem); ?></h5>
                                <span class="badge badge-info" style="text-transform: capitalize;"><?php echo htmlspecialchars($r->status); ?></span>
                            </div>
                            <div class="trip-card-body">
                                <p><strong><i class="fas fa-calendar-alt"></i> Partida:</strong> <?php echo $dataPartida; ?></p>
                                <p><strong><i class="fas fa-calendar-check"></i> Retorno:</strong> <?php echo $dataRetorno; ?></p>
                                <p><strong><i class="fas fa-users"></i> Vagas:</strong> <?php echo $r->vagas; ?></p>
                            </div>
                            <div class="trip-card-footer">
                                <a href="<?php echo base_url() . 'index.php/mine/visualizarViagem/' . $r->id; ?>" class="button btn btn-primary"><span class="button__icon"><i class='bx bx-search-alt'></i></span><span class="button__text2">Saber Mais</span></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="calendario" class="tab-pane fade">
                <div id="calendario-viagens"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#calendario-viagens').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: '<?php echo date('Y-m-d'); ?>',
            navLinks: true,
            editable: false,
            eventLimit: true,
            events: '<?php echo base_url(); ?>index.php/mine/calendarioViagens',
            eventClick: function(event) {
                if (event.url) {
                    window.location.href = event.url;
                    return false;
                }
            }
        });

        $('.filter-btn').on('click', function() {
            var status = $(this).data('status');
            
            // Botão Ativo
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');

            if (status === 'all') {
                $('#disponiveis .trip-card').show();
            } else {
                $('#disponiveis .trip-card').hide();
                $('#disponiveis .trip-card[data-status="' + status + '"]').show();
            }

            if ($('#disponiveis .trip-card:visible').length === 0 && status !== 'all') {
                // Opcional: mostrar uma mensagem se nenhum item corresponder ao filtro
            }
        });

    });
</script>