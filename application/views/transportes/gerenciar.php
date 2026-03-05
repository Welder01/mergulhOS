<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aTransporte')) { ?>
    <a href="<?php echo base_url(); ?>index.php/transportes/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Transporte</a>
    <a href="<?php echo base_url(); ?>index.php/bilhetagem" class="btn btn-info"><i class="fas fa-ticket-alt"></i> Voltar para Bilhetagem</a>
<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-bus"></i>
        </span>
        <h5>Transportes Cadastrados</h5>
    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th>Assentos</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!$results) {
                    echo '<tr><td colspan="7">Nenhum Transporte Cadastrado</td></tr>';
                }
                foreach ($results as $r) {
                    $status = $r->status ? '<span class="label label-success">Ativo</span>' : '<span class="label label-important">Inativo</span>';
                    echo '<tr>';
                    echo '<td>' . $r->idTransporte . '</td>';
                    echo '<td>' . $r->nome . '</td>';
                    echo '<td>' . ucfirst($r->tipo) . '</td>';
                    echo '<td>' . $r->categoria . '</td>';
                    echo '<td>' . $r->qtd_assentos . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td>';
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eTransporte')) {
                        echo '<a href="' . base_url() . 'index.php/transportes/editar/' . $r->idTransporte . '" class="btn btn-info tip-top" title="Editar"><i class="fas fa-edit"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dTransporte')) {
                        echo '<a href="#modal-excluir" role="button" data-toggle="modal" transporte="' . $r->idTransporte . '" class="btn btn-danger tip-top" title="Excluir"><i class="fas fa-trash-alt"></i></a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo $this->pagination->create_links(); ?>

<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/transportes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Transporte</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idTransporte" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este transporte?</h5>
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
            var transporte = $(this).attr('transporte');
            $('#idTransporte').val(transporte);
        });
    });
</script>
