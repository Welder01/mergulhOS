<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aAtivo')) { ?>
    <a href="<?php echo base_url(); ?>index.php/ativos/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Ativo</a>
<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-box"></i>
        </span>
        <h5>Ativos</h5>
    </div>
    <div class="widget-content nopadding">
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
                        echo '<a href="' . base_url() . 'index.php/ativos/editar/' . $r->idAtivo . '" class="btn btn-info tip-top" title="Editar Ativo"><i class="fas fa-edit"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAtivo')) {
                        echo '<a href="#modal-excluir" role="button" data-toggle="modal" ativo="' . $r->idAtivo . '" class="btn btn-danger tip-top" title="Excluir Ativo"><i class="fas fa-trash-alt"></i></a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                } ?>
            </tbody>
        </table>
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

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', 'a', function(event) {
            var ativo = $(this).attr('ativo');
            $('#idAtivo').val(ativo);
        });
    });
</script>