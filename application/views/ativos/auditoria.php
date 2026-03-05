<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="fas fa-search"></i></span>
                <h5>Filtrar Auditoria</h5>
            </div>
            <div class="widget-content">
                <form method="get" action="<?php echo current_url(); ?>" class="form-inline">
                    <input type="date" name="data_inicial" value="<?php echo $this->input->get('data_inicial'); ?>" class="span2" placeholder="Data Inicial">
                    <input type="date" name="data_final" value="<?php echo $this->input->get('data_final'); ?>" class="span2" placeholder="Data Final">
                    <input type="text" name="usuario" value="<?php echo $this->input->get('usuario'); ?>" class="span3" placeholder="Usuário">
                    <input type="text" name="termo" value="<?php echo $this->input->get('termo'); ?>" class="span3" placeholder="Ação, Detalhes, Ativo...">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                    <a href="<?php echo current_url(); ?>" class="btn btn-default"><i class="fas fa-eraser"></i> Limpar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-history"></i>
        </span>
        <h5>Auditoria de Ativos e Bolsas</h5>
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAuditoriaAtivo')) { ?>
            <div class="buttons">
                <a href="#modal-excluir-todos" role="button" data-toggle="modal" class="btn btn-danger btn-mini"><i class="fas fa-trash"></i> Excluir Todos</a>
            </div>
        <?php } ?>
    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Ativo / Bolsa</th>
                    <th>Detalhes</th>
                    <th>Data</th>
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAuditoriaAtivo')) { ?>
                        <th>Ações</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!$results) {
                    echo '<tr><td colspan="7">Nenhum registro encontrado.</td></tr>';
                } else {
                    foreach ($results as $r) {
                        $alvo = '';
                        if($r->ativo_nome) $alvo .= 'Ativo: ' . $r->ativo_nome;
                        if($r->bolsa_nome) $alvo .= ($alvo ? ' | ' : '') . 'Bolsa: ' . $r->bolsa_nome;
                        
                        echo '<tr>';
                        echo '<td>' . $r->idLog . '</td>';
                        echo '<td>' . $r->usuario . '</td>';
                        echo '<td>' . ucfirst(str_replace('_', ' ', $r->acao)) . '</td>';
                        echo '<td>' . $alvo . '</td>';
                        echo '<td>' . $r->detalhes . '</td>';
                        echo '<td>' . date('d/m/Y H:i:s', strtotime($r->data_acao)) . '</td>';
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAuditoriaAtivo')) {
                            echo '<td><a href="#modal-excluir" role="button" data-toggle="modal" log_id="'.$r->idLog.'" class="btn btn-danger btn-mini tip-top" title="Excluir Log"><i class="fas fa-trash-alt"></i></a></td>';
                        }
                        echo '</tr>';
                    }
                } ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>
<a href="<?php echo base_url() ?>index.php/ativos" class="btn btn-warning"><i class="fas fa-arrow-left"></i> Voltar</a>

<!-- Modal Excluir Log -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/ativos/excluirLog" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Log</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idLog" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este registro de auditoria?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>

<!-- Modal Excluir Todos -->
<div id="modal-excluir-todos" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/ativos/excluirTodosLogs" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Todos os Logs</h5>
        </div>
        <div class="modal-body">
            <h5 style="text-align: center; color: red;">ATENÇÃO!</h5>
            <p style="text-align: center">Deseja realmente excluir <b>TODOS</b> os registros de auditoria de ativos?</p>
            <p style="text-align: center">Esta ação é irreversível.</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir Tudo</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', 'a[href="#modal-excluir"]', function(event) {
            var id = $(this).attr('log_id');
            $('#idLog').val(id);
        });
    });
</script>