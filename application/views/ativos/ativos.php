<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aAtivo')) { ?>
    <a href="<?php echo base_url(); ?>index.php/ativos/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Ativo</a>
    <a href="<?php echo base_url(); ?>index.php/ativos/adicionarBolsa" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Bolsa/Caixa</a>
    <a href="<?php echo base_url(); ?>index.php/ativos/movimentacao" class="btn btn-inverse"><i class="fas fa-exchange-alt"></i> Movimentação (Check-in/Out)</a>
<?php } ?>

<div class="row-fluid" style="margin-top: 20px;">
    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="fas fa-chart-pie"></i></span>
                <h5>Status Global dos Ativos</h5>
            </div>
            <div class="widget-content">
                <canvas id="chartStatus" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="fas fa-chart-bar"></i></span>
                <h5>Ativos por Responsável (Em Bolsas)</h5>
            </div>
            <div class="widget-content">
                <canvas id="chartResponsavel" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="widget-box">
    <div class="widget-title">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">Ativos</a></li>
            <li><a data-toggle="tab" href="#tab2">Bolsas/Caixas</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content nopadding">
        <div id="tab1" class="tab-pane active">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Patrimônio</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$results) {
                        echo '<tr>
                                    <td colspan="5">Nenhum Ativo Cadastrado</td>
                                </tr>';
                    }
                    foreach ($results as $r) {
                        $status_color = 'success';
                        if($r->status == 'em_uso') $status_color = 'warning';
                        if($r->status == 'manutencao') $status_color = 'important';
                        if($r->status == 'baixado') $status_color = 'inverse';
                        
                        echo '<tr>';
                        echo '<td>' . $r->idAtivo . '</td>';
                        echo '<td>' . $r->nome . '</td>';
                        echo '<td>' . $r->patrimonio . '</td>';
                        echo '<td><span class="label label-'.$status_color.'">' . ucfirst(str_replace('_', ' ', $r->status)) . '</span></td>';
                        echo '<td>';
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivo')) {
                            echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/ativos/editar/' . $r->idAtivo . '" class="btn btn-info btn-mini tip-top" title="Editar Ativo"><i class="fas fa-edit"></i></a>';
                        }
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAtivo')) {
                            echo '<a style="margin-right: 1%" href="#modal-excluir" role="button" data-toggle="modal" ativo="' . $r->idAtivo . '" class="btn btn-danger btn-mini tip-top" title="Excluir Ativo"><i class="fas fa-trash-alt"></i></a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    } ?>
                </tbody>
            </table>
        </div>
        
        <div id="tab2" class="tab-pane">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Status</th>
                        <th>Qtd. Itens</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$bolsas) {
                        echo '<tr>
                                    <td colspan="6">Nenhuma Bolsa/Caixa Cadastrada</td>
                                </tr>';
                    }
                    foreach ($bolsas as $b) {
                        $status_color = 'info';
                        if($b->status == 'viagem') $status_color = 'warning';
                        if($b->status == 'manutencao') $status_color = 'important';
                        
                        echo '<tr>';
                        echo '<td>' . $b->idBolsa . '</td>';
                        echo '<td>' . $b->nome . '</td>';
                        echo '<td>' . $b->codigo_identificador . '</td>';
                        echo '<td><span class="label label-'.$status_color.'">' . ucfirst($b->status) . '</span></td>';
                        echo '<td><span class="badge badge-inverse">' . $b->qtd_itens . '</span></td>';
                        echo '<td>';
                        
                        echo '<a style="margin-right: 1%" href="#modal-visualizar-bolsa" role="button" data-toggle="modal" bolsa_id="' . $b->idBolsa . '" class="btn btn-inverse btn-mini tip-top btn-visualizar-bolsa" title="Ver Conteúdo"><i class="fas fa-eye"></i></a>';

                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivo')) {
                            echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/ativos/editarBolsa/' . $b->idBolsa . '" class="btn btn-info btn-mini tip-top" title="Editar Bolsa"><i class="fas fa-edit"></i></a>';
                        }
                        // Adicionar exclusão de bolsa se necessário
                        echo '</td>';
                        echo '</tr>';
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->pagination->create_links(); ?>

<!-- Modal Excluir -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/ativos/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Ativo</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idAtivo" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este ativo?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>

<!-- Modal Visualizar Bolsa -->
<div id="modal-visualizar-bolsa" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="myModalLabel">Conteúdo da Bolsa/Caixa</h5>
    </div>
    <div class="modal-body">
        <h5 id="tituloBolsa"></h5>
        <p id="responsavelBolsa"></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Patrimônio</th>
                    <th>Item</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="conteudoBolsa">
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    $(document).ready(function() {
        $(document).on('click', 'a', function(event) {
            var ativo = $(this).attr('ativo');
            $('#idAtivo').val(ativo);
        });

        $('.btn-visualizar-bolsa').click(function() {
            var id = $(this).attr('bolsa_id');
            $('#conteudoBolsa').html('<tr><td colspan="3">Carregando...</td></tr>');
            
            $.post(base_url + 'index.php/ativos/visualizar_bolsa_ajax', {id: id}, function(data) {
                var json = JSON.parse(data);
                if(json.result) {
                    $('#tituloBolsa').text(json.bolsa.nome + ' (' + json.bolsa.codigo_identificador + ')');
                    $('#responsavelBolsa').text('Responsável: ' + (json.bolsa.nome_responsavel ? json.bolsa.nome_responsavel : 'N/A'));
                    var html = '';
                    $.each(json.itens, function(index, item) {
                        html += '<tr><td>' + item.patrimonio + '</td><td>' + item.nome + '</td><td>' + item.status + '</td></tr>';
                    });
                    $('#conteudoBolsa').html(html ? html : '<tr><td colspan="3">Vazia</td></tr>');
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var ctxStatus = document.getElementById('chartStatus').getContext('2d');
        var ctxResponsavel = document.getElementById('chartResponsavel').getContext('2d');
        
        var chartStatus = new Chart(ctxStatus, {
            type: 'pie',
            data: { labels: [], datasets: [{ data: [], backgroundColor: ['#2ecc71', '#f39c12', '#e74c3c', '#34495e'] }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
        
        var chartResponsavel = new Chart(ctxResponsavel, {
            type: 'bar',
            data: { labels: [], datasets: [{ label: 'Qtd. Ativos', data: [], backgroundColor: '#3498db' }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        function updateDashboard() {
            fetch(base_url + 'index.php/ativos/dados_dashboard')
                .then(response => response.json())
                .then(data => {
                    if(data.error) return;

                    // Atualiza Gráfico de Status
                    var statusLabels = data.status.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1).replace('_', ' '));
                    var statusData = data.status.map(item => item.total);
                    
                    // Cores dinâmicas baseadas no status
                    var statusColors = data.status.map(item => {
                        switch(item.status) {
                            case 'disponivel': return '#2ecc71'; // Verde
                            case 'em_uso': return '#f39c12'; // Laranja
                            case 'manutencao': return '#e74c3c'; // Vermelho
                            case 'baixado': return '#34495e'; // Azul Escuro/Cinza
                            default: return '#95a5a6';
                        }
                    });

                    chartStatus.data.labels = statusLabels;
                    chartStatus.data.datasets[0].data = statusData;
                    chartStatus.data.datasets[0].backgroundColor = statusColors;
                    chartStatus.update();

                    // Atualiza Gráfico de Responsáveis
                    var respLabels = data.responsaveis.map(item => item.nome);
                    var respData = data.responsaveis.map(item => item.total);

                    chartResponsavel.data.labels = respLabels;
                    chartResponsavel.data.datasets[0].data = respData;
                    chartResponsavel.update();
                })
                .catch(err => console.error('Erro dashboard:', err));
        }

        updateDashboard(); // Carregamento inicial
        setInterval(updateDashboard, 30000); // Atualiza a cada 30s
        window.addEventListener('ativos-synced', updateDashboard); // Atualiza após sincronização offline->online
    });
</script>
<script src="<?php echo base_url(); ?>assets/js/ativos-offline.js"></script>