<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aAtivoCategoria')) { ?>
    <a href="<?php echo base_url(); ?>index.php/ativos/adicionarCategoria" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Categoria</a>
<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-tags"></i>
        </span>
        <h5>Categorias de Ativos</h5>
    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!$results) {
                    echo '<tr>
                                <td colspan="3">Nenhuma Categoria Cadastrada</td>
                            </tr>';
                }
                foreach ($results as $r) {
                    echo '<tr>';
                    echo '<td>' . $r->idAtivoCategoria . '</td>';
                    echo '<td>' . $r->nome . '</td>';
                    echo '<td>';
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eAtivoCategoria')) {
                        echo '<a href="' . base_url() . 'index.php/ativos/editarCategoria/' . $r->idAtivoCategoria . '" class="btn btn-info tip-top" title="Editar Categoria"><i class="fas fa-edit"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAtivoCategoria')) {
                        echo '<a href="#modal-excluir" role="button" data-toggle="modal" categoria="' . $r->idAtivoCategoria . '" class="btn btn-danger tip-top" title="Excluir Categoria"><i class="fas fa-trash-alt"></i></a>';
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
    <form action="<?php echo base_url() ?>index.php/ativos/excluirCategoria" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Categoria</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idCategoria" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir esta categoria?</h5>
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
            var categoria = $(this).attr('categoria');
            $('#idCategoria').val(categoria);
        });
    });
</script>