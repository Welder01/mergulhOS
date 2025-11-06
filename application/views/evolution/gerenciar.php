<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/trumbowyg/ui/trumbowyg.min.css"/>
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
                    <img src="<?= base_url('assets/images/loading.gif') ?>" alt="Carregando..." />
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
                                        <small class="variable-tag" title="Copiar">{NOME_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{EMAIL_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{TELEFONE_CLIENTE}</small>
                                        <small class="variable-tag" title="Copiar">{NOME_USUARIO}</small>
                                        <small class="variable-tag" title="Copiar">{LINK_CLIENTE}</small>
                                    </p>
                                    <p>
                                        <small class="variable-tag" title="Copiar">{NOME_CURSO}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_INICIO_CURSO}</small>
                                        <small class="variable-tag" title="Copiar">{DATA_FIM_CURSO}</small>
                                    </p>
                                    <p>
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
        <form id="formEnviarMensagem" class="form-horizontal">
            <input type="hidden" id="mensagem_id" name="mensagem_id">
            <div class="control-group">
                <label class="control-label">Enviar para:</label>
                <div class="controls">
                    <label class="checkbox inline"><input type="checkbox" name="alvo[]" value="clientes"> Clientes</label>
                    <label class="checkbox inline"><input type="checkbox" name="alvo[]" value="usuarios"> Usuários</label>
                    <label class="checkbox inline"><input type="checkbox" name="alvo[]" value="especifico"> Número Específico</label>
                </div>
            </div>

            <!-- Seção Clientes -->
            <div id="div-clientes" class="well well-small" style="display:none;">
                <h4>Clientes</h4>
                <div class="control-group">
                    <div class="controls">
                        <label class="radio inline"><input type="radio" name="tipo_cliente" value="todos" checked> Todos</label>
                        <label class="radio inline"><input type="radio" name="tipo_cliente" value="selecionar"> Selecionar</label>
                    </div>
                </div>
                <div id="div-selecao-clientes" class="control-group" style="display:none;">
                    <label class="control-label" for="select_clientes">Selecionar Clientes</label>
                    <div class="controls">
                        <input type="hidden" name="clientes_ids" id="select_clientes" class="span11">
                    </div>
                </div>
                <div id="div-filtros-clientes">
                    <div class="control-group">
                        <label class="control-label" for="select_cursos">Filtrar por Cursos</label>
                        <div class="controls">
                            <input type="hidden" name="cursos_ids" id="select_cursos" class="span11">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="select_viagens">Filtrar por Viagens</label>
                        <div class="controls">
                            <input type="hidden" name="viagens_ids" id="select_viagens" class="span11">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção Usuários -->
            <div id="div-usuarios" class="well well-small" style="display:none;">
                <h4>Usuários</h4>
                <div class="control-group">
                    <div class="controls">
                        <label class="radio inline"><input type="radio" name="tipo_usuario" value="todos" checked> Todos</label>
                        <label class="radio inline"><input type="radio" name="tipo_usuario" value="selecionar"> Selecionar</label>
                    </div>
                </div>
                <div id="div-selecao-usuarios" class="control-group" style="display:none;">
                    <label class="control-label" for="select_usuarios">Selecionar Usuários</label>
                    <div class="controls">
                        <input type="hidden" name="usuarios_ids" id="select_usuarios" class="span11">
                    </div>
                </div>
            </div>

            <!-- Seção Número Específico -->
            <div id="div-numero-especifico" class="well well-small" style="display:none;">
                 <h4>Número Específico</h4>
                <div class="control-group">
                    <label class="control-label" for="inputNumeroEspecifico">Números</label>
                    <div class="controls">
                        <textarea id="inputNumeroEspecifico" name="numeros_especificos" class="span11" rows="3" placeholder="5511999998888, 5521888889999"></textarea>
                        <span class="help-block">Use vírgulas ou quebras de linha para separar os números.</span>
                    </div>
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

        // Função para inicializar o Select2 com busca AJAX
        function initSelect2(selector, placeholder, ajaxUrl) {
            if (typeof($.fn.select2) != 'undefined') {
                $(selector).select2({
                    placeholder: placeholder,
                    minimumInputLength: 2,
                    allowClear: true,
                    multiple: true,
                    width: '100%',
                    ajax: {
                        url: ajaxUrl,
                        dataType: 'json',
                        delay: 250,
                        data: function(term, page) {
                            return {
                                term: term,
                                page: page
                            };
                        },
                        results: function(data, page) {
                            return { results: data.results };
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Select2 AJAX Error:', textStatus, errorThrown);
                            console.error('Response Text:', jqXHR.responseText);
                            console.error('Status:', jqXHR.status);

                            // Send error to server-side log
                            $.ajax({
                                url: '<?= base_url() ?>index.php/evolution/log_ajax_error',
                                type: 'POST',
                                data: {
                                    error_message: 'Select2 AJAX Error: ' + textStatus + ' - ' + errorThrown,
                                    response_text: jqXHR.responseText,
                                    status_code: jqXHR.status,
                                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                                },
                                success: function(response) {
                                    console.log('Error logged on server:', response);
                                },
                                error: function(serverJqXHR, serverTextStatus, serverErrorThrown) {
                                    console.error('Failed to log error on server:', serverTextStatus, serverErrorThrown);
                                }
                            });
                        }
                    }
                });
            }
        }

        // Gerencia a visibilidade das seções principais
        $('input[name="alvo[]"]').on('change', function() {
            $('#div-clientes').toggle($('input[name="alvo[]"][value="clientes"]').is(':checked'));
            $('#div-usuarios').toggle($('input[name="alvo[]"][value="usuarios"]').is(':checked'));
            $('#div-numero-especifico').toggle($('input[name="alvo[]"][value="especifico"]').is(':checked'));
        });

        // Gerencia a visibilidade da seleção de clientes
        $('input[name="tipo_cliente"]').on('change', function() {
            if ($(this).val() === 'todos') {
                $('#div-selecao-clientes').hide();
                $('#div-filtros-clientes').show();
            } else {
                $('#div-selecao-clientes').show();
                $('#div-filtros-clientes').hide();
            }
        });

        // Gerencia a visibilidade da seleção de usuários
        $('input[name="tipo_usuario"]').on('change', function() {
            $('#div-selecao-usuarios').toggle($(this).val() === 'selecionar');
        }).trigger('change');


        // Abre e prepara o modal de envio
        $('.btn-enviar').on('click', function() {
            var id = $(this).data('id');
            var titulo = $(this).data('titulo');

            // Reseta o formulário
            $('#formEnviarMensagem')[0].reset();
            $('input[name="alvo[]"]').prop('checked', false).trigger('change');
            $('#select_clientes, #select_usuarios, #select_cursos, #select_viagens').val(null).trigger('change');
            
            // Define os valores iniciais
            $('#modalTitle').text('Enviar Mensagem: ' + titulo);
            $('#mensagem_id').val(id);

            // Garante o estado visual correto ao abrir o modal
            $('input[name="tipo_cliente"][value="todos"]').prop('checked', true).trigger('change');
            $('input[name="tipo_usuario"][value="todos"]').prop('checked', true).trigger('change');

            // Inicializa os Select2
            initSelect2('#select_clientes', 'Digite para buscar clientes...', '<?= base_url("index.php/evolution/autoComplete/clientes") ?>');
            initSelect2('#select_usuarios', 'Digite para buscar usuários...', '<?= base_url("index.php/evolution/autoComplete/usuarios") ?>');
            initSelect2('#select_cursos', 'Digite para buscar cursos...', '<?= base_url("index.php/cursos/autoCompleteCurso") ?>');
            initSelect2('#select_viagens', 'Digite para buscar viagens...', '<?= base_url("index.php/viagens/autoCompleteViagem") ?>');

            $('#modalEnviar').modal('show');
        });

        // Confirma e envia a mensagem
        $('#btnConfirmarEnvio').on('click', function() {
            var form = $('#formEnviarMensagem');
            var btn = $(this);
            btn.prop('disabled', true).text('Enviando...');

            var formData = form.serializeArray();
            var csrfData = {};
            csrfData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            formData.push({ name: csrfData.name, value: csrfData.value });

            $.ajax({
                url: '<?= base_url() ?>index.php/evolution/enviar_mensagem_novo',
                type: 'POST',
                data: $.param(formData),
                dataType: 'json',
                success: function(response) {
                    $('#modalEnviar').modal('hide');
                    if(response.success) {
                        Swal.fire('Sucesso!', response.message, 'success');
                    } else {
                        Swal.fire('Erro!', response.message, 'error');
                    }
                },
                error: function(jqXHR) {
                    var errorMessage = 'Ocorreu um erro desconhecido.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }
                    Swal.fire('Erro!', errorMessage, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Confirmar Envio');
                }
            });
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