<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<div class="widget-box">
    <div class="widget-title" style="margin: 0;font-size: 1.1em">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">Detalhes do Curso</a></li>
            <li><a data-toggle="tab" href="#tab2">Instrutores</a></li>
            <li><a data-toggle="tab" href="#tab3">Alunos</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <div id="tab1" class="tab-pane active" style="min-height: 300px">
            <div class="accordion" id="collapse-group">
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                <span><i class='bx bx-book-open icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Informações do Curso</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseGOne">
                        <div class="widget-content">
                            <table class="table table-bordered" style="border: 1px solid #ddd">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%"><strong>ID:</strong></td>
                                        <td><?= $result->id ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; width: 30%"><strong>Nome do Curso:</strong></td>
                                        <td><?= html_escape($result->nome_curso) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Descrição:</strong></td>
                                        <td><?= html_escape($result->descricao) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Data de Início:</strong></td>
                                        <td><?= date('d/m/Y', strtotime($result->data_inicio)) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Data de Fim:</strong></td>
                                        <td><?= $result->data_fim ? date('d/m/Y', strtotime($result->data_fim)) : 'N/A' ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Status:</strong></td>
                                        <td><?= html_escape(ucfirst($result->status)) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Data de Cadastro:</strong></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($result->data_cadastro)) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Instrutores -->
        <div id="tab2" class="tab-pane" style="min-height: 300px">
            <div class="widget-box" id="instrutores">
                <div class="widget-title">
                    <span class="icon"><i class="icon-user"></i></span>
                    <h5>Instrutores do Curso</h5>
                </div>
                <div class="widget-content">
                    <form action="<?= base_url() ?>index.php/cursos/adicionar_instrutor" method="post">
                        <input type="hidden" name="curso_id" value="<?= $result->id ?>">
                        <div class="control-group">
                            <label for="instrutor" class="control-label">Adicionar Instrutor</label>
                            <div class="controls">
                                <input id="instrutor" type="text" placeholder="Pesquise o nome do usuário" />
                                <input type="hidden" id="usuario_id" name="usuario_id" />
                                <button type="submit" class="btn btn-success">Adicionar</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome do Instrutor</th>
                                <th>Data de Atribuição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($instrutores) && !empty($instrutores)) : ?>
                                <?php foreach ($instrutores as $i) : ?>
                                    <tr>
                                        <td><?= html_escape($i->nome_instrutor) ?></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($i->data_atribuicao)) ?></td>
                                        <td>
                                            <a href="<?= base_url() ?>index.php/cursos/remover_instrutor/<?= $i->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente remover este instrutor?')">
                                                <i class="icon-trash icon-white"></i> Remover
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3">Nenhum instrutor atribuído a este curso.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: Alunos -->
        <div id="tab3" class="tab-pane" style="min-height: 300px">
            <div class="widget-box" id="alunos">
                <div class="widget-title">
                    <span class="icon"><i class="icon-group"></i></span>
                    <h5>Alunos do Curso</h5>
                </div>
                <div class="widget-content">
                    <form action="<?= base_url() ?>index.php/cursos/adicionar_aluno" method="post">
                        <input type="hidden" name="curso_id" value="<?= $result->id ?>">
                        <div class="control-group">
                            <label for="aluno" class="control-label">Adicionar Aluno</label>
                            <div class="controls">
                                <input id="aluno" type="text" placeholder="Pesquise o nome do cliente" />
                                <input type="hidden" id="cliente_id" name="cliente_id" />
                                <button type="submit" class="btn btn-success">Adicionar</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>Data de Inscrição</th>
                                <th>Status do Aluno</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($alunos) && !empty($alunos)) : ?>
                                <?php foreach ($alunos as $a) : ?>
                                    <tr>
                                        <td><?= html_escape($a->nome_aluno) ?></td>
                                        <td><?= date('d/m/Y H:i:s', strtotime($a->data_inscricao)) ?></td>
                                        <td><?= html_escape(ucfirst($a->status_aluno)) ?></td>
                                        <td>
                                            <a href="<?= base_url() ?>index.php/cursos/remover_aluno/<?= $a->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente remover este aluno?')">
                                                <i class="icon-trash icon-white"></i> Remover
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4">Nenhum aluno inscrito neste curso.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer" style="display:flex;justify-content: center">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCurso')) : ?>
            <a title="Editar Curso" class="button btn btn-mini btn-info" style="min-width: 140px; top:10px" href="<?= base_url() ?>index.php/cursos/editar/<?= $result->id ?>">
                <span class="button__icon"><i class="bx bx-edit"></i></span> <span class="button__text2"> Editar</span>
            </a>
        <?php endif; ?>
        <a title="Voltar" class="button btn btn-mini btn-warning" style="min-width: 140px; top:10px" href="<?= site_url() ?>/cursos">
            <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Autocomplete para Instrutores
        $("#instrutor").autocomplete({
            source: "<?= base_url(); ?>index.php/cursos/autoCompleteUsuario",
            minLength: 2,
            select: function(event, ui) {
                $("#usuario_id").val(ui.item.id);
            }
        });

        // Autocomplete para Alunos
        $("#aluno").autocomplete({
            source: "<?= base_url(); ?>index.php/cursos/autoCompleteCliente",
            minLength: 2,
            select: function(event, ui) {
                $("#cliente_id").val(ui.item.id);
            }
        });

        // Script para manter a aba ativa após redirecionamento
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 100
            }, 800);
        }
    });
</script>