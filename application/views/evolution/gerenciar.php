<link rel="stylesheet" href="<?= base_url() ?>assets/js/trumbowyg/ui/trumbowyg.min.css">
<script src="<?= base_url() ?>assets/js/trumbowyg/trumbowyg.min.js"></script>
<script src="<?= base_url() ?>assets/js/trumbowyg/langs/pt_br.min.js"></script>
<style>
    .variable-tag {
        cursor: pointer;
        background-color: #f9f9f9;
        border: 1px solid #d1d1d1;
        padding: 2px 6px;
        border-radius: 4px;
        margin-right: 4px;
        margin-bottom: 4px;
        display: inline-block;
        font-family: monospace;
        font-size: 11px;
        color: #333;
        transition: all 0.2s ease;
    }
    .variable-tag:hover {
        background-color: #e6e6e6;
        border-color: #adadad;
        text-decoration: none;
        color: #000;
    }
</style>
<div class="widget-box">
    <div class="widget-title">
        <span class="icon"><i class="fas fa-rocket"></i></span>
        <h5>Integração com Evolution API</h5>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tabStatus">Status</a></li>
            <li><a data-toggle="tab" href="#tabMensagens">Mensagens</a></li>
            <li><a data-toggle="tab" href="#tabEventos">Eventos</a></li>
            <li><a data-toggle="tab" href="#tabFila">Fila de Envios</a></li>
            <li><a data-toggle="tab" href="#tabLogs">Logs</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <!-- Aba Status -->
        <div id="tabStatus" class="tab-pane active">
            <div class="span12 well">
                <p>Nesta seção, você pode verificar o status de conexão de uma instância da Evolution API.</p>
                <p>Certifique-se de que a <strong>URL da API</strong> e a <strong>Chave (apikey)</strong> estejam salvas corretamente em <strong>Configurações -> Sistema</strong>.</p>
            </div>

            <div class="span12" style="margin-left: 0">
                <form id="formVerificar" class="form-horizontal">
                    <div class="control-group">
                        <label for="instance_name" class="control-label">Nome da Instância<span class="required">*</span></label>
                        <div class="controls">
                            <input id="instance_name" type="text" name="instance_name" class="span6" required placeholder="Ex: meu-whatsapp" />
                            <button type="submit" class="btn btn-primary" id="btnVerificar">Verificar Status</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="span12" style="margin-left: 0; margin-top: 20px;">
                    <pre id="json-resultado"></pre>
                </div>
                <div id="loading" style="display:none; text-align: center;">
                    <img src="<?= base_url('assets/img/loading.gif') ?>" alt="Carregando...">
                    <p>Verificando...</p>
                </div>
            </div>



        <!-- Aba Eventos -->
        <div id="tabEventos" class="tab-pane">
            <div class="span12 well">
                <p>Configure aqui quais mensagens devem ser enviadas automaticamente quando certos eventos ocorrem no
                    sistema.
                </p>
                <form action="<?= base_url() ?>index.php/evolution/salvar_eventos" method="post">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Mensagem de Modelo</th>
                                <th style="text-align: center;">Ativo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($eventos) && !empty($eventos)): ?>
                                <?php foreach ($eventos as $evento): ?>
                                    <?php 
                                        $eventName = $evento->evento;
                                        $label = '';
                                        $rowClass = '';
                                        
                                        if (strpos($eventName, '_cliente') !== false) {
                                            $label = '<span class="label label-info"><i class="fas fa-user"></i> Cliente</span>';
                                            $cleanName = str_replace('_cliente', '', $eventName);
                                        } elseif (strpos($eventName, '_usuario') !== false) {
                                            $label = '<span class="label label-inverse"><i class="fas fa-user-cog"></i> Usuário (Equipe)</span>';
                                            $cleanName = str_replace('_usuario', '', $eventName);
                                            $rowClass = 'warning'; // Visual hint
                                        } else {
                                            // Fallback/Legacy
                                            $label = '<span class="label">Geral</span>';
                                            $cleanName = $eventName;
                                        }
                                        $cleanName = ucfirst(str_replace('_', ' ', $cleanName));
                                    ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td style="vertical-align: middle;">
                                            <strong><?= $cleanName ?></strong><br>
                                            <?= $label ?>
                                        </td>
                                        <td>
                                            <select name="eventos[<?= $evento->id ?>][mensagem_id]" class="span12 select2" style="width: 100%;">
                                                <option value="">-- Selecione uma Mensagem --</option>
                                                <?php foreach ($mensagens as $msg): ?>
                                                    <option value="<?= $msg->id ?>" <?= $evento->mensagem_id == $msg->id ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($msg->titulo) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <div class="switch switch-small" data-on="success" data-off="danger">
                                                <input type="checkbox" name="eventos[<?= $evento->id ?>][status]" value="1" <?= $evento->status == 1 ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">Nenhum evento configurável encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar
                            Configurações</button>
                    </div>
                </form>
            </div>
            </div>

        <!-- Aba Fila -->
        <div id="tabFila" class="tab-pane">
            <div class="span12" style="margin-left: 0">
                <div class="widget-box">
                    <div class="widget-title">
                        <span class="icon"><i class="fas fa-list"></i></span>
                        <h5>Fila de Envios</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <div class="span12" style="padding: 10px;">
                            <a href="<?= site_url('evolution/forcar_envio_fila') ?>" class="btn btn-inverse tip-top" title="Forçar Envio Manual"><i class="fas fa-paper-plane"></i> Forçar Envio Manual</a>
                            <a href="<?= site_url('evolution/limpar_fila') ?>" class="btn btn-danger tip-top" title="Limpar Fila"><i class="fas fa-trash"></i> Limpar Fila</a>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Número</th>
                                    <th>Mensagem</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($fila) && !empty($fila)) : ?>
                                    <?php foreach ($fila as $item) : ?>
                                        <tr>
                                            <td><?= $item->id ?></td>
                                            <td><?= isset($item->phone_number) ? $item->phone_number : (isset($item->phone) ? $item->phone : '') ?></td>
                                            <td><?= substr($item->message, 0, 50) . '...' ?></td>
                                            <td><?= $item->status ?></td>
                                            <td style="text-align: center;">
                                                <a href="<?= site_url('evolution/forcar_envio_item/' . $item->id) ?>" class="btn btn-mini btn-success tip-top" title="Forçar Envio Imediato" style="margin-right: 3px;"><i class="fas fa-paper-plane"></i></a>
                                                <a href="<?= site_url('evolution/excluir_item_fila/' . $item->id) ?>#tabFila" class="btn btn-mini btn-danger tip-top" title="Remover da Fila" onclick="return confirm('Remover este item da fila?');"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center;">A fila de envios está vazia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba Logs -->
        <div id="tabLogs" class="tab-pane">
            <div class="span12" style="padding: 1%; margin-left: 0;">
                <div class="widget-box" id="divLogs">
                    <div class="widget-header">
                        <h5 class="cardHeader"><i class="fas fa-history"></i> Logs de Envio</h5>
                        <div class="widget-buttons" style="float: right; margin: 5px 10px 0 0;">
                            <a href="<?= base_url('index.php/evolution/limpar_logs') ?>" class="btn btn-danger btn-mini"
                                onclick="return confirm('Tem certeza que deseja apagar TODOS os logs? Esta ação não pode ser desfeita.');"><i
                                    class="fas fa-trash"></i> Limpar Todos</a>
                        </div>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Data/Hora</th>
                                    <th style="width: 15%;">Destinatário</th>
                                    <th style="width: 10%;">Status</th>
                                    <th>Detalhes</th>
                                    <th style="width: 10%;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($logs) && count($logs)): ?>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i:s', strtotime($log->timestamp)); ?></td>
                                            <td><?= htmlspecialchars($log->phone_number); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'label-inverse';
                                                $statusText = $log->response_code;
                                                if ($log->response_code >= 200 && $log->response_code < 300) {
                                                    $statusClass = 'badge-success';
                                                } elseif ($log->response_code >= 400) {
                                                    $statusClass = 'badge-important';
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass; ?>"><?= $statusText; ?></span>
                                            </td>
                                            <td>
                                                <a href="#modal-log-details" data-toggle="modal" class="btn btn-mini btn-info"
                                                    data-title="Requisição"
                                                    data-content="<?= htmlspecialchars($log->request_payload); ?>"><i
                                                        class="fas fa-arrow-up"></i> Req</a>
                                                <a href="#modal-log-details" data-toggle="modal"
                                                    class="btn btn-mini btn-warning" data-title="Resposta"
                                                    data-content="<?= htmlspecialchars($log->response_body . ($log->curl_error ? ' | Erro cURL: ' . $log->curl_error : '')); ?>"><i
                                                        class="fas fa-arrow-down"></i> Resp</a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="<?= base_url('index.php/evolution/excluir_log/' . $log->id) ?>#tabLogs"
                                                    class="btn btn-danger btn-mini" title="Excluir Log"
                                                    onclick="return confirm('Deseja excluir este log?');"><i
                                                        class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center;">Nenhum log encontrado.</td>
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
                <div class="widget-title"><span class="icon"><i class="fas fa-comment-alt"></i></span>
                    <h5>Criar Nova Mensagem</h5>
                </div>
                <div class="widget-content">
                    <form action="<?= base_url() ?>index.php/evolution/adicionar_mensagem" method="post"
                        class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="active_tab" id="active_tab" value="#tabStatus">
                        <div class="control-group">
                            <label for="titulo" class="control-label">Título<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" name="titulo" id="titulo" class="span11" required
                                    placeholder="Ex: Lembrete de Vencimento">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="imagem_url" class="control-label">Mídia (URL ou Arquivo)</label>
                            <div class="controls">
                                <input type="url" name="imagem_url" id="imagem_url" class="span11"
                                    placeholder="https://exemplo.com/arquivo.pdf ou .jpg, .mp4">
                                <div style="margin-top: 5px;">
                                    <input type="file" name="userfile" class="span11" accept=".jpg,.jpeg,.png,.mp4,.pdf,.doc,.docx,.txt">
                                </div>
                                <div class="alert alert-info" style="margin-top: 8px; padding: 8px 14px; font-size: 13px; width: 88%;">
                                    <i class="fas fa-info-circle"></i> Cole uma URL direta <b>OU</b> envie um arquivo (Max 20MB).<br>
                                    <span style="font-size: 12px;">Suporta Imagens, Vídeos e Documentos.</span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="mensagem" class="control-label">Mensagem<span class="required">*</span></label>
                            <div class="controls">
                                <textarea name="mensagem" id="mensagem" rows="5" class="span11" required></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <div class="help-block">
                                    <strong>Variáveis Disponíveis:</strong>
                                    <div style="max-height: 150px; overflow-y: auto; font-size: 0.9em; line-height: 1.4em;">
                                        <p style="margin-bottom: 5px;">Você pode usar <strong>qualquer campo</strong> do banco de dados usando o formato <code>{OBJETO.CAMPO}</code> (Ex: <code>{CLIENTE.BAIRRO}</code>).</p>
                                        
                                        <strong>Cliente:</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{NOME_CLIENTE}</small> 
                                            <small class="variable-tag">{TELEFONE_CLIENTE}</small>
                                            <small class="variable-tag">{LINK_ACESSO_CLIENTE}</small>
                                        </div>

                                        <strong>Curso:</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{NOME_CURSO}</small> 
                                            <small class="variable-tag">{DATA_INICIO_CURSO}</small>
                                            <small class="variable-tag">{CURSO.LISTA_INSTRUTORES}</small>
                                        </div>

                                        <strong>Viagem:</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{NOME_VIAGEM}</small> 
                                            <small class="variable-tag">{VIAGEM.DATA_PARTIDA}</small>
                                            <small class="variable-tag">{VIAGEM.LISTA_INSTRUTORES}</small>
                                        </div>
                                        
                                        <strong>Financeiro (Pagamento):</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{LANCAMENTO.DESCRICAO}</small>
                                            <small class="variable-tag">{LANCAMENTO.VALOR_FORMATADO}</small>
                                            <small class="variable-tag">{LANCAMENTO.DATA_VENCIMENTO_FORMATADA}</small>
                                        </div>

                                        <strong>Tarefas (OS):</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{OS.IDOs}</small>
                                            <small class="variable-tag">{OS.STATUS}</small>
                                            <small class="variable-tag">{OS.VALOR_TOTAL_FORMATADO}</small>
                                            <small class="variable-tag">{OS.VALOR_TOTAL_FORMATADO}</small>
                                            <small class="variable-tag">{OS.LINK_VISUALIZAR}</small>
                                        </div>

                                        <strong>Empresa (Emitente):</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{EMITENTE.NOME}</small>
                                            <small class="variable-tag">{EMITENTE.CNPJ}</small>
                                            <small class="variable-tag">{EMITENTE.TELEFONE}</small>
                                            <small class="variable-tag">{EMITENTE.EMAIL}</small>
                                            <small class="variable-tag">{EMITENTE.RUA}</small>
                                            <small class="variable-tag">{EMITENTE.RUA}</small>
                                            <small class="variable-tag">{EMITENTE.CIDADE}</small>
                                        </div>

                                        <strong>Treino:</strong>
                                        <div style="margin-bottom: 5px;">
                                            <small class="variable-tag">{TREINO.DATA_AGENDAMENTO}</small>
                                            <small class="variable-tag">{TREINO.NOME_INSTRUTOR}</small>
                                            <small class="variable-tag">{TREINO.LOCAL}</small>
                                            <small class="variable-tag">{TREINO.OBSERVACOES}</small>
                                            <small class="variable-tag">{TREINO.STATUS}</small>
                                        </div>
                                    </div>
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
                <div class="widget-title"><span class="icon"><i class="fas fa-list"></i></span>
                    <h5>Mensagens Salvas</h5>
                </div>
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
                            <?php foreach ($mensagens as $msg): ?>
                                <tr>
                                    <td><?= html_escape($msg->titulo) ?></td>
                                    <td><small><?= nl2br(html_escape($msg->mensagem)) ?></small></td>
                                    <td>
                                        <button class="btn btn-primary btn-mini btn-enviar" data-id="<?= $msg->id ?>"
                                            data-titulo="<?= html_escape($msg->titulo) ?>">Enviar</button>
                                        <button class="btn btn-info btn-mini btn-editar" data-id="<?= $msg->id ?>"
                                            data-titulo="<?= html_escape($msg->titulo) ?>"
                                            data-mensagem="<?= html_escape($msg->mensagem) ?>"
                                            data-imagem="<?= html_escape($msg->imagem_url) ?>">Editar</button>
                                        <a href="<?= base_url('index.php/evolution/excluir_mensagem/' . $msg->id) ?>#tabMensagens"
                                            class="btn btn-danger btn-mini"
                                            onclick="return confirm('Deseja realmente excluir esta mensagem?')">Excluir</a>
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
</div>
</div>

<!-- Modal Editar Mensagem -->
<div id="modalEditar" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Editar Mensagem</h3>
    </div>
    <div class="modal-body">
        <form id="formEditarMensagem" action="<?= base_url() ?>index.php/evolution/editar_mensagem" method="post" enctype="multipart/form-data">
            <input type="hidden" id="edit_id" name="id">
            <div class="control-group">
                <label for="edit_titulo" class="control-label">Título<span class="required">*</span></label>
                <div class="controls">
                    <input type="text" name="titulo" id="edit_titulo" class="span12" style="width: 97%;" required>
                </div>
            </div>
            <div class="control-group">
                <label for="edit_imagem_url" class="control-label">Mídia (URL ou Arquivo)</label>
                <div class="controls">
                    <input type="url" name="imagem_url" id="edit_imagem_url" class="span12" style="width: 97%;">
                    <div id="current_media_container" style="margin-top: 5px; display: none;">
                        <span class="label label-info">Mídia Atual:</span>
                        <a href="" id="current_media_link" target="_blank" style="margin-left: 5px; word-break: break-all;">Visualizar Mídia</a>
                    </div>
                    <div style="margin-top: 5px;">
                        <input type="file" name="userfile" class="span12" style="width: 97%;" accept=".jpg,.jpeg,.png,.mp4,.pdf,.doc,.docx,.txt">
                    </div>
                    <div class="alert alert-info" style="margin-top: 8px; padding: 8px 14px; font-size: 13px; width: 93%;">
                        <i class="fas fa-info-circle"></i> Cole uma URL direta <b>OU</b> envie um arquivo (Max 20MB).<br>
                        <span style="font-size: 12px;">Suporta Imagens, Vídeos e Documentos.</span>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label for="edit_mensagem" class="control-label">Mensagem<span class="required">*</span></label>
                <div class="controls">
                    <textarea name="mensagem" id="edit_mensagem" rows="5" class="span12" style="width: 97%;" required></textarea>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="help-block">
                        <strong>Variáveis Disponíveis:</strong>
                        <div style="max-height: 150px; overflow-y: auto; font-size: 0.9em; line-height: 1.4em;">
                            <p style="margin-bottom: 5px;">Você pode usar <strong>qualquer campo</strong> do banco de dados usando o formato <code>{OBJETO.CAMPO}</code> (Ex: <code>{CLIENTE.BAIRRO}</code>).</p>
                            
                            <strong>Cliente:</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{NOME_CLIENTE}</small> 
                                <small class="variable-tag">{TELEFONE_CLIENTE}</small>
                                <small class="variable-tag">{LINK_ACESSO_CLIENTE}</small>
                            </div>

                            <strong>Curso:</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{NOME_CURSO}</small> 
                                <small class="variable-tag">{DATA_INICIO_CURSO}</small>
                                <small class="variable-tag">{CURSO.LISTA_INSTRUTORES}</small>
                            </div>

                            <strong>Viagem:</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{NOME_VIAGEM}</small> 
                                <small class="variable-tag">{VIAGEM.DATA_PARTIDA}</small>
                                <small class="variable-tag">{VIAGEM.LISTA_INSTRUTORES}</small>
                            </div>
                            
                            <strong>Financeiro (Pagamento):</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{LANCAMENTO.DESCRICAO}</small>
                                <small class="variable-tag">{LANCAMENTO.VALOR_FORMATADO}</small>
                                <small class="variable-tag">{LANCAMENTO.DATA_VENCIMENTO_FORMATADA}</small>
                            </div>

                            <strong>Tarefas (OS):</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{OS.IDOs}</small>
                                <small class="variable-tag">{OS.STATUS}</small>
                                <small class="variable-tag">{OS.VALOR_TOTAL_FORMATADO}</small>
                                <small class="variable-tag">{OS.LINK_VISUALIZAR}</small>
                            </div>

                            <strong>Empresa (Emitente):</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{EMITENTE.NOME}</small>
                                <small class="variable-tag">{EMITENTE.CNPJ}</small>
                                <small class="variable-tag">{EMITENTE.TELEFONE}</small>
                                <small class="variable-tag">{EMITENTE.EMAIL}</small>
                                <small class="variable-tag">{EMITENTE.RUA}</small>
                                <small class="variable-tag">{EMITENTE.RUA}</small>
                                <small class="variable-tag">{EMITENTE.CIDADE}</small>
                            </div>

                            <strong>Treino:</strong>
                            <div style="margin-bottom: 5px;">
                                <small class="variable-tag">{TREINO.DATA_AGENDAMENTO}</small>
                                <small class="variable-tag">{TREINO.NOME_INSTRUTOR}</small>
                                <small class="variable-tag">{TREINO.LOCAL}</small>
                                <small class="variable-tag">{TREINO.OBSERVACOES}</small>
                                <small class="variable-tag">{TREINO.STATUS}</small>
                            </div>
                        </div>
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
                    <label class="checkbox inline"><input type="checkbox" name="alvo[]" value="clientes">
                        Clientes</label>
                    <label class="checkbox inline"><input type="checkbox" name="alvo[]" value="usuarios">
                        Usuários</label>
                    <label class="checkbox inline"><input type="checkbox" name="alvo[]" value="especifico"> Número
                        Específico</label>
                </div>
            </div>

            <!-- Seção Clientes -->
            <div id="div-clientes" class="well well-small" style="display:none;">
                <h4>Clientes</h4>
                <div class="control-group">
                    <div class="controls">
                        <label class="radio inline"><input type="radio" name="tipo_cliente" value="todos" checked>
                            Todos</label>
                        <label class="radio inline"><input type="radio" name="tipo_cliente" value="selecionar">
                            Selecionar</label>
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
                            <input type="hidden" name="cursos_ids" id="select_cursos_filtro" class="span11">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="select_viagens">Filtrar por Viagens</label>
                        <div class="controls">
                            <input type="hidden" name="viagens_ids" id="select_viagens_filtro" class="span11">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Outros Filtros</label>
                        <div class="controls">
                            <label class="checkbox inline"><input type="checkbox" name="aniversariantes_semana"
                                    value="1"> Aniversariantes da Semana</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção Usuários -->
            <div id="div-usuarios" class="well well-small" style="display:none;">
                <h4>Usuários</h4>
                <div class="control-group">
                    <div class="controls">
                        <label class="radio inline"><input type="radio" name="tipo_usuario" value="todos" checked>
                            Todos</label>
                        <label class="radio inline"><input type="radio" name="tipo_usuario" value="selecionar">
                            Selecionar</label>
                    </div>
                </div>
                <div id="div-filtros-usuarios">
                    <div class="control-group">
                        <label class="control-label" for="select_permissoes_filtro">Filtrar por Permissão</label>
                        <div class="controls">
                            <input type="hidden" name="permissoes_ids" id="select_permissoes_filtro" class="span11">
                        </div>
                    </div>
                </div>

                <div id="div-filtros-usuarios-cursos" style="display:none;">
                    <div class="control-group">
                        <label class="control-label" for="select_cursos_filtro_usuarios">Filtrar por Cursos</label>
                        <div class="controls">
                            <input type="hidden" name="cursos_ids_usuarios" id="select_cursos_filtro_usuarios"
                                class="span11">
                        </div>
                    </div>
                </div>
                <div id="div-filtros-usuarios-viagens" style="display:none;">
                    <div class="control-group">
                        <label class="control-label" for="select_viagens_filtro_usuarios">Filtrar por Viagens</label>
                        <div class="controls">
                            <input type="hidden" name="viagens_ids_usuarios" id="select_viagens_filtro_usuarios"
                                class="span11">
                        </div>
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
                        <textarea id="inputNumeroEspecifico" name="numeros_especificos" class="span11" rows="3"
                            placeholder="5511999998888, 5521888889999"></textarea>
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
        // Tab persistence
        var hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }
        $('.nav-tabs a').click(function (e) {
            $(this).tab('show');
            window.location.hash = this.hash;
        });

        $('#formVerificar').on('submit', function(e) {
            e.preventDefault();

            $('#btnVerificar').prop('disabled', true);
            $('#resultado').hide();
            $('#loading').show();

            $.ajax({
                url: '<?= site_url('evolution/fetch_instance') ?>',
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
@ -777,317 +141,11 @@
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
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var currentTab = $(e.target).attr('href');
            $('#active_tab').val(currentTab);
        });

        $('#btnConfirmarEdicao').on('click', function () {
            $('#formEditarMensagem').submit();
        });

        //--- LÓGICA DA ABA MENSAGENS ---//

        // Função para inicializar o Select2 com busca AJAX
        function initSelect2(selector, placeholder, ajaxUrl) {
            if (typeof ($.fn.select2) != 'undefined') {
                $(selector).select2({
                    placeholder: placeholder,
                    minimumInputLength: 2,
                    allowClear: true,
                    multiple: true,
                    width: '100%', // Garante que o select ocupe todo o espaço do contêiner
                    dropdownParent: $(document.body),
                    ajax: {
                        url: ajaxUrl,
                        dataType: 'json',
                        delay: 250,
                        data: function (term, page) {
                            return {
                                term: term,
                                page: page
                            };
                        },
                        results: function (data, page) {
                            return { results: data };
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
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
                                success: function (response) {
                                    console.log('Error logged on server:', response);
                                },
                                error: function (serverJqXHR, serverTextStatus, serverErrorThrown) {
                                    console.error('Failed to log error on server:', serverTextStatus, serverErrorThrown);
                                }
                            });
                        }
                    },
                    formatResult: function (item) {
                        return item.text || item.nome; // Adapta para diferentes nomes de propriedade
                    },
                    formatSelection: function (item) {
                        return item.text || item.nome;
                    },
                    initSelection: function (element, callback) {
                        var ids = $(element).val();
                        if (ids !== "") {
                            $.ajax(ajaxUrl, {
                                data: {
                                    ids: ids // Envia os IDs para a API
                                },
                                dataType: "json",
                                async: false // Garante que a busca seja concluída antes de continuar
                            }).done(function (data) {
                                // O Select2 para múltiplos valores espera um array
                                var results = Array.isArray(data) ? data : [data];
                                callback(results);
                            });
                        }
                    }
                });
            }
        }

        // Gerencia a visibilidade das seções principais
        $('input[name="alvo[]"]').on('change', function () {
            $('#div-clientes').toggle($('input[name="alvo[]"][value="clientes"]').is(':checked'));
            $('#div-usuarios').toggle($('input[name="alvo[]"][value="usuarios"]').is(':checked'));
            $('#div-numero-especifico').toggle($('input[name="alvo[]"][value="especifico"]').is(':checked'));
        });

        // Gerencia a visibilidade da seleção de clientes
        $('input[name="tipo_cliente"]').on('change', function () {
            if ($(this).val() === 'todos') {
                $('#div-selecao-clientes').hide();
                $('#div-filtros-clientes').show();
            } else {
                $('#div-selecao-clientes').show();
                $('#div-filtros-clientes').hide();
            }
        });

        // Gerencia a visibilidade da seleção de usuários
        $('input[name="tipo_usuario"]').on('change', function () {
            if ($(this).val() === 'todos') {
                $('#div-selecao-usuarios').hide();
                $('#div-filtros-usuarios').show();
                $('#div-filtros-usuarios-cursos').show();
                $('#div-filtros-usuarios-viagens').show();
            } else {
                $('#div-selecao-usuarios').show();
                $('#div-filtros-usuarios').hide();
                $('#div-filtros-usuarios-cursos').hide();
                $('#div-filtros-usuarios-viagens').hide();
            }
        }).trigger('change');


        // Abre e prepara o modal de envio
        $('.btn-enviar').on('click', function () {
            var id = $(this).data('id');
            var titulo = $(this).data('titulo');

            // Reseta o formulário
            $('#formEnviarMensagem')[0].reset();
            $('input[name="alvo[]"]').prop('checked', false).trigger('change');
            $('#select_clientes, #select_usuarios, #select_cursos_filtro, #select_viagens_filtro, #select_permissoes_filtro, #select_cursos_filtro_usuarios, #select_viagens_filtro_usuarios').val(null).trigger('change');

            // Define os valores iniciais
            $('#modalTitle').text('Enviar Mensagem: ' + titulo);
            $('#mensagem_id').val(id);

            // Garante o estado visual correto ao abrir o modal
            $('input[name="tipo_cliente"][value="todos"]').prop('checked', true).trigger('change');
            $('input[name="tipo_usuario"][value="todos"]').prop('checked', true).trigger('change');

            // Inicializa os Select2
            initSelect2('#select_clientes', 'Digite para buscar clientes...', '<?= base_url("index.php/evolution/autoComplete/clientes") ?>');
            initSelect2('#select_usuarios', 'Digite para buscar usuários...', '<?= base_url("index.php/evolution/autoComplete/usuarios") ?>');
            initSelect2('#select_cursos_filtro', 'Digite para buscar cursos...', '<?= base_url("index.php/cursos/autoCompleteCurso") ?>');
            initSelect2('#select_viagens_filtro', 'Digite para buscar viagens...', '<?= base_url("index.php/viagens/autoCompleteViagem") ?>');
            initSelect2('#select_permissoes_filtro', 'Digite para buscar permissões...', '<?= base_url("index.php/evolution/autoCompletePermissao") ?>');
            initSelect2('#select_cursos_filtro_usuarios', 'Digite para buscar cursos...', '<?= base_url("index.php/cursos/autoCompleteCurso") ?>');
            initSelect2('#select_viagens_filtro_usuarios', 'Digite para buscar viagens...', '<?= base_url("index.php/viagens/autoCompleteViagem") ?>');

            $('#modalEnviar').modal('show');
        });

        // Confirma e envia a mensagem
        $('#btnConfirmarEnvio').on('click', function () {
            var form = $('#formEnviarMensagem');
            var btn = $(this);

            var formData = form.serializeArray();
            var csrfData = {};
            csrfData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            formData.push({ name: csrfData.name, value: csrfData.value });

            // Fecha o modal e exibe o alerta de carregamento
            $('#modalEnviar').modal('hide');
            Swal.fire({
                title: 'Enviando Mensagens',
                text: 'Isso pode levar alguns minutos. Por favor, aguarde...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.ajax({
                url: '<?= base_url() ?>index.php/evolution/enviar_mensagem_novo',
                type: 'POST',
                data: $.param(formData),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Sucesso!', response.message, 'success');
                    } else {
                        Swal.fire('Erro!', response.message, 'error');
                    }
                },
                error: function (jqXHR) {
                    var errorMessage = 'Ocorreu um erro desconhecido.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }
                    Swal.fire('Erro!', errorMessage, 'error');
                }
            });
        });

        // Adiciona o editor de texto Trumbowyg
        if (typeof ($.fn.trumbowyg) != 'undefined') {
            $.trumbowyg.svgPath = '<?= base_url() ?>assets/js/trumbowyg/ui/icons.svg';
            var trumbowygConfig = {
                lang: 'pt_br',
                autogrow: true,
                removeformatPasted: true,
                btns: [
                    ['strong', 'em', 'del']
                ]
            };
            $('#mensagem').trumbowyg(trumbowygConfig);
            $('#edit_mensagem').trumbowyg(trumbowygConfig);
        }

        $(document).on('click', 'a[href="#modal-log-details"]', function () {
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
            if (typeof (str) == "string") {
                str = str.replace(/&amp;/g, "&");
                str = str.replace(/&quot;/g, "\"");
                str = str.replace(/&#039;/g, "'");
                str = str.replace(/&lt;/g, "<");
                str = str.replace(/&gt;/g, ">");
            }
            return str;
        }

        //--- LÓGICA DE EDIÇÃO ---//
        $(document).on('click', '.btn-editar', function () {
            var id = $(this).data('id');
            var titulo = $(this).data('titulo');
            var mensagem = $(this).data('mensagem');
            var imagem = $(this).data('imagem');

            $('#edit_id').val(id);
            $('#edit_titulo').val(titulo);
            $('#edit_imagem_url').val(imagem);
            $('#edit_mensagem').trumbowyg('html', mensagem);

            if (imagem) {
                $('#current_media_link').attr('href', imagem);
                $('#current_media_container').show();
            } else {
                $('#current_media_link').attr('href', '#');
                $('#current_media_container').hide();
            }

            $('#modalEditar').modal('show');
        });

        //--- LÓGICA DE INSERÇÃO DE VARIÁVEL ---//
        var lastFocusedEditor = null;

        // Rastreia qual editor teve o foco por último (Trumbowyg e Textarea comum)
        $('#mensagem, #edit_mensagem')
            .on('tbwfocus', function () {
                lastFocusedEditor = $(this);
            })
            .on('focus', function () {
                lastFocusedEditor = $(this);
            });

        // Usa mousedown para evitar perder o foco do editor antes do click
        $(document).on('mousedown', '.variable-tag', function (e) {
            e.preventDefault();
        });

        $(document).on('click', '.variable-tag', function (e) {
            e.preventDefault();
            var textToInsert = $(this).text();
            var targetEditor = lastFocusedEditor;

            // Se nenhum editor foi focado, tenta identificar pelo contexto (Modal aberto ou Principal)
            if (!targetEditor || targetEditor.length === 0) {
                if ($('#modalEditar').is(':visible')) {
                    targetEditor = $('#edit_mensagem');
                } else {
                    targetEditor = $('#mensagem');
                }
            }

            if (targetEditor && targetEditor.data('trumbowyg')) {
                try {
                    targetEditor.trumbowyg('restoreRange');
                    targetEditor.trumbowyg('execCmd', {
                        cmd: 'insertHtml',
                        param: textToInsert,
                        forceCss: false
                    });
                } catch (e) {
                    // Fallback: insere no final se não conseguir restaurar a posição
                    targetEditor.trumbowyg('html', targetEditor.trumbowyg('html') + textToInsert);
                }
                // Garante que o textarea seja atualizado
                targetEditor.trigger('tbwchange');
            } else {
                // Fallback para Textarea comum (se o Trumbowyg não carregar)
                var el = targetEditor[0];
                if (el && (el.selectionStart || el.selectionStart == '0')) {
                    var startPos = el.selectionStart;
                    var endPos = el.selectionEnd;
                    el.value = el.value.substring(0, startPos)
                        + textToInsert
                        + el.value.substring(endPos, el.value.length);
                    el.selectionStart = startPos + textToInsert.length;
                    el.selectionEnd = startPos + textToInsert.length;
                } else if (el) {
                    el.value += textToInsert;
                }
            }
        });

        // Função para converter HTML do editor para Markdown do WhatsApp
        function convertHtmlToWhatsapp(html) {
            var text = html;
            // Converte quebras de linha
            text = text.replace(/<br\s*\/?>/gi, "\n");
            text = text.replace(/<\/p>\s*<p>/gi, "\n\n");
            text = text.replace(/<\/p>/gi, "\n");
            text = text.replace(/<p>/gi, "");
            
            // Converte formatação
            text = text.replace(/<(b|strong)>(.*?)<\/\1>/gi, "*$2*");
            text = text.replace(/<(i|em)>(.*?)<\/\1>/gi, "_$2_");
            text = text.replace(/<(del|s|strike)>(.*?)<\/\1>/gi, "~$2~");
            
            // Remove tags restantes e decodifica entidades
            var tmp = document.createElement("DIV");
            tmp.innerHTML = text;
            return tmp.textContent || tmp.innerText || "";
        }

        // Intercepta o envio dos formulários para converter o conteúdo
        $('form[action*="adicionar_mensagem"], #formEditarMensagem').on('submit', function() {
            var form = $(this);
            var textarea = form.find('textarea[name="mensagem"]');
            if(textarea.length && textarea.data('trumbowyg')) {
                var html = textarea.trumbowyg('html');
                var markdown = convertHtmlToWhatsapp(html);
                textarea.val(markdown);
            }
        });

        // Solução alternativa sugerida: desativa a rolagem do body quando o modal está aberto
        // para evitar que o dropdown do Select2 se desprenda do campo.
        $('#modalEnviar').on('show', function () {
            $('body').css('overflow', 'hidden');
        }).on('hidden', function () {
            // Garante que a rolagem seja reativada ao fechar o modal
            $('body').css('overflow', 'auto');
        });

    });

</script>