<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-graduation-cap"></i>
        </span>
        <h5>Cursos</h5>
    </div>
    <div class="span12" style="margin-left: 0">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCurso')) : ?>
            <div class="span3">
                <a href="<?= base_url() ?>index.php/cursos/adicionar" class="button btn btn-mini btn-success" style="max-width: 165px">
                    <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">
                        Adicionar Curso
                    </span>
                </a>
            </div>
        <?php endif; ?>
        <form class="span9" method="get" action="<?= base_url() ?>index.php/cursos" style="display: flex; justify-content: flex-end;">
            <div class="span3">
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Buscar por nome do curso..." class="span12" value="<?= $this->input->get('pesquisa') ?>">
            </div>
            <div class="span1">
                <button class="button btn btn-mini btn-warning" style="min-width: 30px">
                    <span class="button__icon"><i class='bx bx-search-alt'></i></span></button>
            </div>
        </form>
    </div>

    <div class="widget-box">
        <h5 style="padding: 3px 0"></h5>
        <div class="widget-content nopadding tab-content">
            <table id="tabela" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Curso</th>
                        <th>Data Início</th>
                        <th>Data Fim</th>
                        <th>Preço</th>
                        <th>Vagas</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$results) : ?>
                        <tr>
                            <td colspan="8">Nenhum Curso Cadastrado</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($results as $r) : ?>
                            <tr>
                                <td><?= $r->id ?></td>
                                <td><a href="<?= base_url() ?>index.php/cursos/visualizar/<?= $r->id ?>" style="margin-right: 1%"><?= html_escape($r->nome_curso) ?></a></td>
                                <td><?= date('d/m/Y', strtotime($r->data_inicio)) ?></td>
                                <td><?= $r->data_fim ? date('d/m/Y', strtotime($r->data_fim)) : 'N/A' ?></td>
                                <td>R$ <?= number_format($r->preco, 2, ',', '.') ?></td>
                                <td><?= $r->vagas ?> de <?= $r->vagas_total ?></td>
                                <td><?= html_escape(ucfirst($r->status)) ?></td>
                                <td>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) : ?>
                                        <a href="<?= base_url() ?>index.php/cursos/visualizar/<?= $r->id ?>" style="margin-right: 1%" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show bx-xs"></i></a>
                                    <?php endif; ?>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) : ?>
                                        <a href="<?= base_url() ?>index.php/cursos/editar/<?= $r->id ?>" style="margin-right: 1%" class="btn-nwe3" title="Editar Curso"><i class="bx bx-edit bx-xs"></i></a>
                                    <?php endif; ?>
                                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dCurso')) : ?>
                                        <a href="#modal-excluir" role="button" data-toggle="modal" curso="<?= $r->id ?>" style="margin-right: 1%" class="btn-nwe4" title="Excluir Curso"><i class="bx bx-trash-alt bx-xs"></i></a>
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
<?php echo $this->pagination->create_links(); ?>

<!-- Modal Excluir -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/cursos/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Curso</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idCurso" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este curso?</h5>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', 'a', function(event) {
            var curso = $(this).attr('curso');
            $('#idCurso').val(curso);
        });
    });
</script>