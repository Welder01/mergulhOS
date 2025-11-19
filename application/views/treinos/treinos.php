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

<!-- Tabela de Treinos Agendados -->
<div class="widget-box" style="margin-top: 20px;">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-calendar-check"></i>
        </span>
        <h5>Treinos Agendados</h5>
    </div>

    <div class="widget-content nopadding">
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Treino</th>
                    <th>Cliente</th>
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
                foreach ($agendamentos as $a) {
                    echo '<tr>';
                    echo '<td>' . $a->id . '</td>';
                    echo '<td>' . $a->nome_treino . '</td>';
                    echo '<td>' . $a->nome_cliente . '</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($a->data_hora_inicio)) . '</td>';
                    echo '<td>' . ucfirst($a->status) . '</td>';
                    echo '<td>';
                    // Adicione aqui links para visualizar ou editar o agendamento, se necessário
                    // Exemplo: echo '<a href="' . site_url('treinos/visualizarAgendamento/' . $a->id) . '" class="btn-nwe" title="Visualizar"><i class="bx bx-show"></i></a>';
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
        $(document).on('click', 'a', function(event) {
            var configuracao_id = $(this).attr('configuracao_id');
            $('#configuracao_id').val(configuracao_id);
        });
    });
</script>