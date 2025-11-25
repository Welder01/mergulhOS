<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-calendar-check"></i>
        </span>
        <h5>Detalhes do Treino Agendado</h5>
    </div>
    <div class="widget-content nopadding">
        <div class="invoice-content">
            <div class="invoice-head" style="margin-bottom: 0">
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td style="width: 25%;"><strong>Treino</strong></td>
                            <td><?php echo htmlspecialchars($result->nome_treino); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Cliente</strong></td>
                            <td><?php echo htmlspecialchars($result->nome_cliente); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Data/Hora</strong></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($result->data_hora_inicio)); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><?php echo htmlspecialchars($result->status); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Instrutor</strong></td>
                            <td><?php echo $result->com_instrutor ? htmlspecialchars($result->nome_instrutor ?? 'Não definido') : 'Sem instrutor'; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-actions" style="background-color:transparent;border:none;padding: 10px;margin-bottom: 0">
                <?php
                $isCliente = $this->session->userdata('tipo_usuario') == 'cliente';
                $backUrl = $isCliente ? site_url('mine/treinos') : site_url('treinos');
                ?>
                <a href="<?php echo $backUrl; ?>" class="button btn btn-mini btn-warning">
                    <span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span>
                </a>
            </div>
        </div>
    </div>
</div>