<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/trumbowyg/ui/trumbowyg.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/trumbowyg/trumbowyg.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/trumbowyg/langs/pt_br.min.js"></script>

<style>
    .variable-tag {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 2px 6px;
        cursor: pointer;
        font-family: monospace;
    }
    .variable-tag:hover {
        background-color: #e0e0e0;
    }
</style>

<div class="widget-box">
    <div class="widget-title" style="margin: 0;font-size: 1.1em">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tabStatus">Status da Instância</a></li>
            <li><a data-toggle="tab" href="#tabLogs">Logs de Envio</a></li>
            <li><a data-toggle="tab" href="#tabMensagens">Mensagens</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <!-- Aba Status -->
        <div id="tabStatus" class="tab-pane active">
            <div class="span12 well">
                <p>Nesta seção, você pode verificar o status de conexão da instância da Evolution API configurada no sistema.</p>
                <p>Certifique-se de que a <strong>URL da API</strong>, a <strong>Chave (apikey)</strong> e o <strong>Nome da Instância</strong> estejam salvos corretamente em <strong>Configurações -> Sistema</strong>.</p>
            </div>

            <div class="span12" style="margin-left: 0">
                <button type="button" class="btn btn-primary" id="btnVerificar">Verificar Status da Instância</button>
            </div>

            <div class="span12" style="margin-left: 0; margin-top: 20px;">
                <div id="resultado" style="display:none;">
                    <h4>Resultado:</h4>
                    <pre id="json-resultado"></pre>
                </div>
                <div id="loading" style="display:none; text-align: center;">
                    <img src="<?= base_url('assets/img/loading.gif') ?>" alt="Carregando..." />
                    <p>Verificando...</p>
                </div>
            </div>
        </div>

        <!-- Aba Logs -->
        <div id="tabLogs" class="tab-pane">
            <div class="span12" style="padding: 1%; margin-left: 0;">
                <div class="widget-box" id="divLogs">
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Data/Hora</th>
                                    <th style="width: 15%;">Número</th>
                                    <th style="width: 10%;">Status HTTP</th>
                                    <th>Requisição</th>
                                    <th>Resposta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($logs) && count($logs)) : ?>
                                    <?php foreach ($logs as $log) : ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i:s', strtotime($log->timestamp)); ?></td>
                                            <td><?= htmlspecialchars($log->phone_number); ?></td>
                                            <td>
                                                <?php
                                                    $statusClass = 'label-inverse';
                                                    if ($log->response_code >= 200 && $log->response_code < 300) {
                                                        $statusClass = 'label-success';
                                                    } elseif ($log->response_code >= 400) {
                                                        $statusClass = 'label-important';
                                                    }
                                                ?>
                                                <span class="label <?= $statusClass; ?>"><?= $log->response_code; ?></span>
                                            </td>
                                            <td><a href="#modal-log-details" data-toggle="modal" class="btn btn-mini btn-info" data-title="Requisição" data-content="<?= htmlspecialchars($log->request_payload); ?>">Ver</a></td>
                                            <td><a href="#modal-log-details" data-toggle="modal" class="btn btn-mini btn-info" data-title="Resposta" data-content="<?= htmlspecialchars($log->response_body . ($log->curl_error ? ' | Erro cURL: ' . $log->curl_error : '')); ?>">Ver</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5">Nenhum log encontrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba Mensagens -->
        <div id="tabMensagens" class="tab-pane">
            <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="fas fa-comment-alt"></i></span><h5>Criar Nova Mensagem</h5></div>
                <div class="widget-content">
                    <form action="<?= base_url() ?>index.php/evolution/adicionar_mensagem" method="post" class="form-horizontal">
                        <input type="hidden" name="active_tab" id="active_tab" value="#tabStatus">
                        <div class="control-group">
                            <label for="titulo" class="control-label">Título<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" name="titulo" id="titulo" class="span11" required placeholder="Ex: Lembrete de Vencimento">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="imagem_url" class="control-label">URL da Imagem (Opcional)</label>
                            <div class="controls">
                                <input type="url" name="imagem_url" id="imagem_url" class="span11" placeholder="https://exemplo.com/imagem.jpg">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="mensagem" class="control-label">Mensagem<span class="required">*</span></label>
                            <div class="controls">
                                <textarea name="mensagem" id="mensagem" rows="5" class="span11" required></textarea>
                                <div class="help-block" style="margin-top: 10px;">
                                    <p><strong>Variáveis disponíveis (clique para copiar):</strong></p>
                                    <p>
                                        <strong>Cliente:</strong>
                                        <small class="variable-tag" title="Copiar">{NOME_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{EMAIL_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{DOCUMENTO_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{TELEFONE_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{CELULAR_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_CADASTRO}</small>
                                        <small class="variable-tag" title="Copiar">{LINK_CLIENTE}</small>
                                    </p>
                                    <p>
                                        <strong>Usuário:</strong>
                                        <small class="variable-tag" title="Copiar">{NOME_USUARIO}</small>
                                    </p>
                                    <p>
                                        <strong>Curso:</strong>
                                        <small class="variable-tag" title="Copiar">{NOME_CURSO}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_INICIO_CURSO}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_FIM_CURSO}</small>
                                    </p>
                                    <p>
                                        <strong>Viagem:</strong>
                                        <small class="variable-tag" title="Copiar">{NOME_VIAGEM}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_PARTIDA_VIAGEM}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_RETORNO_VIAGEM}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions" style="background-color:transparent;border:none;padding-left:180px;">
                            <button type="submit" class="btn btn-success">Salvar Mensagem</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="fas fa-list"></i></span><h5>Mensagens Salvas</h5></div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Mensagem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mensagens as $msg) : ?>
                                <tr>
                                    <td><?= html_escape($msg->titulo) ?></td>
                                    <td><small><?= nl2br(html_escape($msg->mensagem)) ?></small></td>
                                    <td>
                                        <button class="btn btn-primary btn-mini btn-enviar" data-id="<?= $msg->id ?>" data-titulo="<?= html_escape($msg->titulo) ?>">Enviar</button>
                                        <button class="btn btn-info btn-mini btn-editar" data-id="<?= $msg->id ?>" data-titulo="<?= html_escape($msg->titulo) ?>" data-mensagem="<?= html_escape($msg->mensagem) ?>" data-imagem="<?= html_escape($msg->imagem_url) ?>">Editar</button>
                                        <a href="<?= base_url('index.php/evolution/excluir_mensagem/' . $msg->id) ?>#tabMensagens" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente excluir esta mensagem?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Mensagem -->
<div id="modalEditar" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Editar Mensagem</h3>
    </div>
    <div class="modal-body">
        <form id="formEditarMensagem" action="<?= base_url() ?>index.php/evolution/editar_mensagem" method="post" class="form-horizontal">
            <input type="hidden" id="edit_id" name="id">
            <div class="control-group">
                <label for="edit_titulo" class="control-label">Título<span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="titulo" id="edit_titulo" class="span11" required>
                </div>
            </div>
            <div class="control-group">
                <label for="edit_imagem_url" class="control-label">URL da Imagem (Opcional)</label>
                <div class="controls">
                    <input type="url" name="imagem_url" id="edit_imagem_url" class="span11">
                </div>
            </div>
            <div class="control-group">
                <label for="edit_mensagem" class="control-label">Mensagem<span class="required">*</span></label>
                <div class="controls">
                    <textarea name="mensagem" id="edit_mensagem" rows="5" class="span11" required></textarea>
                    <div class="help-block" style="margin-top: 10px;">
                        <p><strong>Variáveis disponíveis (clique para copiar):</strong></p>
                        <p>
                            <strong>Cliente:</strong>
                            <small class="variable-tag" title="Copiar">{NOME_CLIENTE}</small>
                            <small class="variable-tag" title="Copiar">{EMAIL_CLIENTE}</small>
                            <small class="variable-tag" title="Copiar">{DOCUMENTO_CLIENTE}</small>
                            <small class="variable-tag" title="Copiar">{TELEFONE_CLIENTE}</small>
                            <small class="variable-tag" title="Copiar">{CELULAR_CLIENTE}</small>
                            <small class="variable-tag" title="Copiar">{DATA_CADASTRO}</small>
                            <small class="variable-tag" title="Copiar">{LINK_CLIENTE}</small>
                        </p>
                        <p>
                            <strong>Usuário:</strong>
                            <small class="variable-tag" title="Copiar">{NOME_USUARIO}</small>
                        </p>
                        <p>
                            <strong>Curso:</strong>
                            <small class="variable-tag" title="Copiar">{NOME_CURSO}</small>
                            <small class="variable-tag" title="Copiar">{DATA_INICIO_CURSO}</small>
                            <small class="variable-tag" title="Copiar">{DATA_FIM_CURSO}</small>
                        </p>
                        <p>
                            <strong>Viagem:</strong>
                            <small class="variable-tag" title="Copiar">{NOME_VIAGEM}</small>
                            <small class="variable-tag" title="Copiar">{DATA_PARTIDA_VIAGEM}</small>
                            <small class="variable-tag" title="Copiar">{DATA_RETORNO_VIAGEM}</small>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        <button class="btn btn-primary" id="btnConfirmarEdicao">Salvar Alterações</button>
    </div>
</div>

<!-- Modal Enviar Mensagem -->
<div id="modalEnviar" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="modalTitle">Enviar Mensagem: </h3>
    </div>
    <div class="modal-body">
        <form id="formEnviarMensagem">
            <input type="hidden" id="mensagem_id" name="mensagem_id">
            <div class="control-group">
                <label class="control-label">Enviar para:</label>
                <div class="controls">
                    <select id="selectAlvo" name="alvo" class="span12">
                        <option value="clientes">Clientes</option>
                        <option value="usuarios">Usuários</option>
                        <option value="especifico">Número Específico</option>
                    </select>
                </div>
            </div>
            <div id="div-selecao-multipla" class="control-group">
                <label class="control-label" id="label-selecao">Selecione os Clientes:</label>
                <div class="controls">
                    <input type="text" id="cliente_autocomplete" class="span12" placeholder="Digite o nome do cliente...">
                </div>
            </div>
            <div id="div-selecao-curso" class="control-group" style="display:none;">
                <label class="control-label" for="curso_autocomplete">Vincular a um Curso (Opcional):</label>
                <div class="controls">
                    <input type="text" id="curso_autocomplete" name="curso_id" class="span12" placeholder="Digite o nome do curso...">
                    <input type="hidden" id="curso_id" name="curso_id">
                </div>
            </div>
            <div id="div-selecao-viagem" class="control-group" style="display:none;">
                <label class="control-label" for="viagem_autocomplete">Vincular a uma Viagem (Opcional):</label>
                <div class="controls">
                    <input type="text" id="viagem_autocomplete" name="viagem_id" class="span12" placeholder="Digite o nome da viagem...">
                    <input type="hidden" id="viagem_id" name="viagem_id">
                </div>
            </div>
            <div id="div-numero-especifico" class="control-group" style="display:none;">
                <label class="control-label">Números:</label>
                <div class="controls">
                    <input type="text" id="inputNumeroEspecifico" name="numeros[]" class="span12" placeholder="5511999998888, 5521888889999">
                    <span class="help-block">Separe múltiplos números por vírgula.</span>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        <button class="btn btn-primary" id="btnConfirmarEnvio">Confirmar Envio</button>
    </div>
</div>

<!-- Modal Detalhes do Log -->
<div id="modal-log-details" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="log-details-title">Detalhes</h3>
    </div>
    <div class="modal-body">
        <pre id="log-details-content" style="white-space: pre-wrap; word-wrap: break-word;"></pre>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Script para manter a aba ativa após redirecionamento
        var hash = window.location.hash;
        if (hash) {
            $('a[href="' + hash + '"]').tab('show');
        } else if (window.location.search.includes('tab=')) {
            var urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) {
                $('a[href="#' + tab + '"]').tab('show');
            }
        }

        //--- LÓGICA DA ABA STATUS ---//
        $('#btnVerificar').on('click', function(e) {
            e.preventDefault();

            $('#btnVerificar').prop('disabled', true);
            $('#resultado').hide();
            $('#loading').show();

            $.ajax({
                url: '<?= base_url() ?>index.php/evolution/fetch_instance',
                type: 'POST',
                data: {
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    var jsonString = JSON.stringify(response, null, 2);
                    $('#json-resultado').text(jsonString);
                    $('#resultado').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var errorMessage = 'Ocorreu um erro desconhecido.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        errorMessage = jqXHR.responseJSON.error;
                    } else if (jqXHR.responseText) {
                        try {
                            var response = JSON.parse(jqXHR.responseText);
                            errorMessage = response.error || response.message || jqXHR.responseText;
                        } catch (e) {
                            errorMessage = jqXHR.responseText;
                        }
                    }
                    Swal.fire('Erro!', errorMessage, 'error');
                },
                complete: function() {
                    $('#loading').hide();
                    $('#btnVerificar').prop('disabled', false);
                }
            });
        });

        // Atualiza o campo hidden sempre que uma nova aba é mostrada
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var currentTab = $(e.target).attr('href');
            $('#active_tab').val(currentTab);
        });

        $('#btnConfirmarEdicao').on('click', function() {
            $('#formEditarMensagem').submit();
        });

        //--- LÓGICA DA ABA MENSAGENS ---//
        var clientesData = <?= json_encode(array_map(function($c) { return ['id' => $c->idClientes, 'text' => $c->nomeCliente . ' (' . $c->celular . ')']; }, $clientes)) ?>;
        var usuariosData = <?= json_encode(array_map(function($u) { return ['id' => $u->idUsuarios, 'text' => $u->nome . ' (' . $u->celular . ')']; }, $usuarios)) ?>;

        function popularSelect(data) {
            var select = $('#selectNumeros');
            select.empty();
            if(data) {
                $.each(data, function(index, item) {
                    select.append(new Option(item.text, item.id, false, false));
                });
            }
        }

        function inicializarSelect2() {
            if(typeof($.fn.select2) != 'undefined') {
                $('#selectNumeros').select2({
                    placeholder: 'Selecione...',
                    allowClear: true,
                    width: '100%'
                });
            }
        }

        $('#selectAlvo').on('change', function() {
            var alvo = $(this).val();
            $('#cliente_autocomplete').val('');
            $('#inputNumeroEspecifico').val('');

            if (alvo === 'clientes') {
                $('#label-selecao').text('Selecione os Clientes:');
                $('#div-selecao-multipla').show();
                $('#div-selecao-curso').show();
                $('#div-selecao-viagem').show();
                $('#div-numero-especifico').hide();
            } else if (alvo === 'usuarios') {
                $('#label-selecao').text('Selecione os Usuários:');
                $('#div-selecao-multipla').show();
                $('#div-selecao-curso').hide();
                $('#div-selecao-viagem').hide();
                $('#div-numero-especifico').hide();
            } else {
                $('#div-selecao-multipla').hide();
                $('#div-selecao-curso').hide();
                $('#div-selecao-viagem').hide();
                $('#div-numero-especifico').show();
            }
        });

        $('.btn-enviar').on('click', function() {
            var id = $(this).data('id');
            var titulo = $(this).data('titulo');
            $('#modalTitle').text('Enviar Mensagem: ' + titulo);
            $('#mensagem_id').val(id);
            $('#selectAlvo').val('clientes').trigger('change');
            $('#modalEnviar').modal('show');
        });

        $('#btnConfirmarEnvio').on('click', function() {
            var form = $('#formEnviarMensagem');
            var btn = $(this);
            btn.prop('disabled', true).text('Enviando...');

            var alvo = $('#selectAlvo').val();
            if (alvo === 'clientes' || alvo === 'usuarios') {
                var selectedId = $('#cliente_autocomplete').data('id');
                form.find('input[name="numeros[]"]').remove(); // Limpa antes de adicionar
                form.append('<input type="hidden" name="numeros[]" value="' + selectedId + '">');
            }

            var formData = form.serializeArray();
            var csrfData = {};
            csrfData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            formData.push({ name: csrfData.name, value: csrfData.value });

            $.ajax({
                url: '<?= base_url() ?>index.php/evolution/enviar_mensagem',
                type: 'POST',
                data: $.param(formData),
                dataType: 'json',
                success: function(response) {
                    $('#modalEnviar').modal('hide');
                    Swal.fire('Sucesso!', response.message, 'success');
                },
                error: function(jqXHR) {
                    var errorMessage = 'Ocorreu um erro desconhecido.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        errorMessage = jqXHR.responseJSON.error;
                    }
                    Swal.fire('Erro!', errorMessage, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Confirmar Envio');
                }
            });
        });

        $("#cliente_autocomplete").autocomplete({
            source: function(request, response) {
                var url = $('#selectAlvo').val() === 'clientes' ? "<?= base_url(); ?>index.php/evolution/autoCompleteCliente" : "<?= base_url(); ?>index.php/evolution/autoCompleteUsuario";
                $.ajax({
                    url: url,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $(this).data('id', ui.item.id);
            }
        });

        $("#curso_autocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url(); ?>index.php/cursos/autoCompleteCurso",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $("#curso_id").val(ui.item.id);
            }
        });

        $("#viagem_autocomplete").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?= base_url(); ?>index.php/viagens/autoCompleteViagem",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $("#viagem_id").val(ui.item.id);
            }
        });

        // Adiciona o editor de texto Trumbowyg
        if(typeof($.fn.trumbowyg) != 'undefined') {
            $('#mensagem').trumbowyg({
                lang: 'pt_br',
                autogrow: true
            });
            $('#edit_mensagem').trumbowyg({
                lang: 'pt_br',
                autogrow: true
            });
        }

        $(document).on('click', 'a[href="#modal-log-details"]', function() {
            var title = $(this).data('title');
            var content = htmlspecialchars_decode($(this).data('content'));
            try {
                // Tenta formatar o conteúdo como JSON se for uma string JSON válida
                content = JSON.stringify(content, null, 2);
            } catch (e) {
                // Se não for um JSON válido, exibe como está (já é uma string)
            }
            $('#log-details-title').text(title);
            $('#log-details-content').text(content);
        });

        function htmlspecialchars_decode(str) {
            if (typeof(str) == "string") {
                str = str.replace(/&amp;/g, "&");
                str = str.replace(/&quot;/g, "\"");
                str = str.replace(/&#039;/g, "'");
                str = str.replace(/&lt;/g, "<");
                str = str.replace(/&gt;/g, ">");
            }
            return str;
        }

        //--- LÓGICA DE EDIÇÃO ---//
        $(document).on('click', '.btn-editar', function() {
            var id = $(this).data('id');
            var titulo = $(this).data('titulo');
            var mensagem = $(this).data('mensagem');
            var imagem = $(this).data('imagem');

            $('#edit_id').val(id);
            $('#edit_titulo').val(titulo);
            $('#edit_imagem_url').val(imagem);
            $('#edit_mensagem').trumbowyg('html', mensagem);

            $('#modalEditar').modal('show');
        });

        //--- LÓGICA DE COPIAR VARIÁVEL ---//
        $(document).on('click', '.variable-tag', function() {
            var textToCopy = $(this).text();
            navigator.clipboard.writeText(textToCopy).then(() => {
                var originalText = $(this).text();
                $(this).text('Copiado!');
                setTimeout(() => {
                    $(this).text(originalText);
                }, 1000);
            }).catch(err => {
                console.error('Erro ao copiar: ', err);
            });
        });

    });

</script>