<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aBilhete')) { ?>
    <a href="<?php echo base_url(); ?>index.php/expedicoes/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Expedição</a>
    <a href="<?php echo base_url(); ?>index.php/bilhetagem" class="btn btn-info"><i class="fas fa-ticket-alt"></i> Voltar para Bilhetagem</a>
<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-map-marked-alt"></i>
        </span>
        <h5>Expedições</h5>
    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Data Ida</th>
                    <th>Data Volta</th>
                    <th>Moeda</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!$results) {
                    echo '<tr>
                                <td colspan="6">Nenhuma Expedição Cadastrada</td>
                            </tr>';
                }
                foreach ($results as $r) {
                    echo '<tr>';
                    echo '<td>' . $r->idExpedicao . '</td>';
                    echo '<td>' . $r->titulo . '</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($r->data_ida)) . '</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($r->data_volta)) . '</td>';
                    echo '<td>' . $r->moeda_base . '</td>';
                    echo '<td>';
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eBilhete')) {
                        echo '<a href="' . base_url() . 'index.php/expedicoes/editar/' . $r->idExpedicao . '" class="btn btn-info tip-top" title="Editar Expedição"><i class="fas fa-edit"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dBilhete')) {
                        echo '<a href="#modal-excluir" role="button" data-toggle="modal" expedicao="' . $r->idExpedicao . '" class="btn btn-danger tip-top" title="Excluir Expedição"><i class="fas fa-trash-alt"></i></a>';
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
    <form action="<?php echo base_url() ?>index.php/expedicoes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Expedição</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idExpedicao" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir esta expedição?</h5>
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
            var expedicao = $(this).attr('expedicao');
            $('#idExpedicao').val(expedicao);
        });
    });
</script>