<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<style>
    .switch { position: relative; display: inline-block; width: 50px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; -webkit-transition: .4s; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #28a745; }
    input:focus + .slider { box-shadow: 0 0 1px #28a745; }
    input:checked + .slider:before { -webkit-transform: translateX(26px); -ms-transform: translateX(26px); transform: translateX(26px); }
    .concluido-label { margin-left: 10px; font-weight: bold; }
    .concluido { color: #28a745; }
    .pendente { color: #dc3545; }
    .instrutor-tag { display: inline-block; background-color: #e9ecef; border-radius: 5px; padding: 2px 8px; margin: 2px; font-size: 0.9em; }
    .instrutor-tag .remove-instrutor { margin-left: 5px; color: #dc3545; cursor: pointer; font-weight: bold; }
</style>

<div class="widget-box">
    <div class="widget-title" style="margin: 0;font-size: 1.1em">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">Detalhes do Curso</a></li>
            <li><a data-toggle="tab" href="#tab2">Instrutores</a></li>
            <li><a data-toggle="tab" href="#tab3">Alunos</a></li>
            <li><a data-toggle="tab" href="#modulos">Módulos do Curso</a></li>
            <li><a data-toggle="tab" href="#requisitos">Requisitos</a></li>
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
                                        <td style="text-align: right"><strong>Preço:</strong></td>
                                        <td>R$ <?= number_format($result->preco, 2, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Vagas:</strong></td>
                                        <td><?= $result->vagas ?> de <?= $result->vagas_total ?> vagas totais.</td>
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
                    <form id="formAdicionarAluno" action="<?= base_url() ?>index.php/cursos/adicionar_aluno" method="post" class="form-horizontal">
                        <input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <input type="hidden" name="curso_id" id="curso_id_aluno" value="<?= $result->id ?>">
                        <div class="control-group">
                            <label for="aluno" class="control-label">Adicionar Aluno</label>
                            <div class="controls">
                                <input id="aluno" type="text" placeholder="Pesquise o nome do cliente" />
                                <input type="hidden" id="cliente_id" name="cliente_id" />
                                <button type="button" id="btnAdicionarAluno" class="btn btn-success" disabled>Adicionar</button>
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
                                            <a href="<?= base_url() ?>index.php/cursos/remover_aluno/<?= $a->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente remover este aluno do curso?')">
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

    <!-- Tab 4: Módulos do Curso -->
    <div id="modulos" class="tab-pane" style="min-height: 300px">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="fas fa-tasks"></i></span><h5>Adicionar Novo Módulo</h5></div>
            <div class="widget-content">
                <form action="<?= base_url() ?>index.php/cursos/adicionar_modulo" method="post" class="form-horizontal">
                    <input type="hidden" name="curso_id" value="<?= $result->id ?>">
                    <div class="control-group">
                        <label for="nome_modulo" class="control-label">Nome do Módulo<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome_modulo" type="text" name="nome_modulo" class="span11" required />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="descricao_modulo" class="control-label">Descrição</label>
                        <div class="controls">
                            <textarea id="descricao_modulo" name="descricao_modulo" class="span11" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-actions" style="background-color:transparent;border:none;padding-left:180px;">
                        <button type="submit" class="btn btn-success">Adicionar Módulo</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="fas fa-list-ul"></i></span><h5>Módulos Cadastrados</h5></div>
            <div class="widget-content nopadding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Módulo</th>
                            <th>Instrutores da Aula</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($modulos) && !empty($modulos)) : ?>
                            <?php foreach ($modulos as $modulo) : ?>
                                <tr>
                                    <td>
                                        <strong><?= html_escape($modulo->nome) ?></strong><br>
                                        <small><?= html_escape($modulo->descricao) ?></small>
                                    </td>
                                    <td>
                                        <?php foreach ($modulo->instrutores as $instrutor_modulo) : ?>
                                            <span class="instrutor-tag">
                                                <?= html_escape($instrutor_modulo->nome_instrutor) ?>
                                                <a href="<?= base_url() ?>index.php/cursos/remover_instrutor_modulo/<?= $instrutor_modulo->id ?>" onclick="return confirm('Remover este instrutor do módulo?')" class="remove-instrutor" title="Remover Instrutor">&times;</a>
                                            </span>
                                        <?php endforeach; ?>
                                        
                                        <form action="<?= base_url() ?>index.php/cursos/adicionar_instrutor_modulo" method="post" style="display: inline-flex; align-items: center; margin-top: 5px;">
                                            <input type="hidden" name="curso_id" value="<?= $result->id ?>">
                                            <input type="hidden" name="curso_modulo_id" value="<?= $modulo->id ?>">
                                            <input type="text" class="instrutor-autocomplete" placeholder="Adicionar instrutor" style="width: 150px; margin-right: 5px;" />
                                            <input type="hidden" class="usuario_id_modulo" name="usuario_id" />
                                            <button type="submit" class="btn btn-primary btn-mini btn-add-instrutor-modulo" disabled>Add</button>
                                        </form>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <label class="switch">
                                            <input type="checkbox" class="toggle-conclusao" data-id="<?= $modulo->id ?>" <?= $modulo->concluido ? 'checked' : '' ?>>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="concluido-label <?= $modulo->concluido ? 'concluido' : 'pendente' ?>">
                                            <?= $modulo->concluido ? 'Concluído' : 'Pendente' ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <a href="<?= base_url() ?>index.php/cursos/remover_modulo/<?= $modulo->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente remover este módulo?')">
                                            <i class="icon-trash icon-white"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4">Nenhum módulo cadastrado para este curso.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab 5: Requisitos do Curso -->
    <div id="requisitos" class="tab-pane" style="min-height: 300px">
        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="fas fa-check-circle"></i></span><h5>Adicionar Novo Requisito</h5></div>
            <div class="widget-content">
                <form action="<?= base_url() ?>index.php/cursos/adicionar_requisito" method="post" class="form-horizontal">
                    <input type="hidden" name="curso_id" value="<?= $result->id ?>">
                    <div class="control-group">
                        <label for="requisito" class="control-label">Certificação Necessária<span class="required">*</span></label>
                        <div class="controls">
                            <select name="requisito" id="requisito" class="span6" required>
                                <option value="">Selecione um requisito</option>
                                <?php foreach ($tipos_certificacao as $tipo) : ?>
                                    <option value="<?= trim($tipo) ?>"><?= html_escape(trim($tipo)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions" style="background-color:transparent;border:none;padding-left:180px;">
                        <button type="submit" class="btn btn-success">Adicionar Requisito</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="widget-box">
            <div class="widget-title"><span class="icon"><i class="fas fa-list-alt"></i></span><h5>Requisitos Cadastrados</h5></div>
            <div class="widget-content nopadding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Requisito</th>
                            <th style="width: 80px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requisitos as $r) : ?>
                            <tr>
                                <td><?= html_escape($r->requisito) ?></td>
                                <td><a href="<?= base_url() ?>index.php/cursos/remover_requisito/<?= $r->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este requisito?')"><i class="icon-trash icon-white"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
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
            select: function(event, ui) { // Quando um aluno é selecionado
                $("#cliente_id").val(ui.item.id);
                $("#btnAdicionarAluno").prop('disabled', false); // Habilita o botão
            },
            change: function(event, ui) { // Quando o campo perde o foco
                if (!ui.item) {
                    $("#cliente_id").val('');
                    $("#btnAdicionarAluno").prop('disabled', true); // Desabilita se nada for selecionado
                }
            }
        });

        // Ação de clique no botão Adicionar Aluno
        $('#btnAdicionarAluno').on('click', function() {
            var cursoId = $('#curso_id_aluno').val();
            var clienteId = $('#cliente_id').val();

            if (!clienteId) {
                return;
            }

            // Faz a verificação de requisitos via AJAX
            $.post('<?= base_url() ?>index.php/cursos/ajax_check_requisitos', {
                curso_id: cursoId,
                cliente_id: clienteId,
                '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
            }, function(response) {
                $('#csrf_token').val(response.csrf_token); // Atualiza o token CSRF
                if (response.status === 'success') {
                    // Se não houver problemas, submete o formulário
                    $('#formAdicionarAluno').submit();
                } else {
                    // Se faltarem requisitos, mostra um alerta de erro e impede a inscrição
                    var missingList = '<ul>' + response.missing.map(item => `<li>${item}</li>`).join('') + '</ul>';
                    Swal.fire({
                        title: 'Requisitos Faltantes',
                        html: `O aluno não pode ser inscrito pois não possui os seguintes requisitos:${missingList}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                // Exibe um alerta se a requisição AJAX falhar (ex: erro 500, 404)
                Swal.fire({
                    title: 'Erro Inesperado',
                    html: 'Ocorreu um erro no servidor ao tentar verificar os requisitos. Por favor, verifique os logs para mais detalhes.<br><br><i>Detalhe: ' + (jqXHR.responseJSON ? jqXHR.responseJSON.message : errorThrown) + '</i>',
                    icon: 'error'
                });
            });
        });

        // Script para manter a aba ativa após redirecionamento
        var hash = window.location.hash;
        if (hash) {
            var tab = hash.replace('#', '');
            if (tab) {
                $('.nav-tabs a[href="#' + tab + '"]').tab('show');
                $('html, body').animate({ scrollTop: $('#' + tab).offset().top - 100 }, 800);
            }
        } else if (window.location.search.includes('tab=')) { // Fallback for tab parameter
            var urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) {
                $('.nav-tabs a[href="#' + tab + '"]').tab('show');
            }
        }

        // Autocomplete para Instrutores nos Módulos
        $('.instrutor-autocomplete').each(function() {
            var $input = $(this);
            var $form = $input.closest('form');
            var $idInput = $form.find('.usuario_id_modulo');
            var $submitButton = $form.find('.btn-add-instrutor-modulo');

            $input.autocomplete({
                source: "<?= base_url(); ?>index.php/cursos/autoCompleteUsuario",
                minLength: 2,
                select: function(event, ui) {
                    $idInput.val(ui.item.id);
                    $submitButton.prop('disabled', false);
                },
                change: function(event, ui) {
                    if (!ui.item) {
                        $input.val('');
                        $idInput.val('');
                        $submitButton.prop('disabled', true);
                    }
                }
            });
        });

        // Toggle para conclusão do módulo
        $('.toggle-conclusao').change(function() {
            var id = $(this).data('id');
            var concluido = $(this).is(':checked');
            var label = $(this).closest('td').find('.concluido-label');

            $.post('<?= base_url() ?>index.php/cursos/toggle_conclusao_modulo', { id: id, concluido: concluido, '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>' }, function(res) {
                label.text(concluido ? 'Concluído' : 'Pendente').toggleClass('concluido pendente');
            }, 'json').fail(function() { alert('Ocorreu um erro ao atualizar o status do módulo.'); });
        });
    });
</script>