<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/trumbowyg/ui/trumbowyg.min.css" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/trumbowyg/trumbowyg.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/trumbowyg/langs/pt_br.min.js"></script>

<style>
    .variable-tag {
        background-color: #e8f0fe;
        color: #1a73e8;
        border: 1px solid #d2e3fc;
        border-radius: 16px;
        padding: 4px 12px;
        margin: 2px;
        cursor: pointer;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 0.9em;
        display: inline-block;
        transition: all 0.2s;
    }

    .variable-tag:hover {
        background-color: #d2e3fc;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Fix para o z-index do Select2 dentro do modal */
    .select2-drop,
    .select2-drop-mask {
        z-index: 99999 !important;
        /* Garante que fique acima de qualquer modal ou overlay */
    }
</style>

<div class="widget-box">
    <div class="widget-title" style="margin: 0;font-size: 1.1em">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tabStatus">Status da Instância</a></li>
            <li><a data-toggle="tab" href="#tabLogs">Logs de Envio</a></li>
            <li><a data-toggle="tab" href="#tabMensagens">Mensagens</a></li>
            <li><a data-toggle="tab" href="#tabEventos">Eventos Automáticos</a></li>
            <li><a data-toggle="tab" href="#tabFila">Fila de Envio</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <!-- Aba Status -->
        <div id="tabStatus" class="tab-pane active">
            <div class="span12 well">
                <p>Nesta seção, você pode verificar o status de conexão da instância da Evolution API configurada no
                    sistema.</p>
                <p>Certifique-se de que a <strong>URL da API</strong>, a <strong>Chave (apikey)</strong> e o
                    <strong>Nome da Instância</strong> estejam salvos corretamente em <strong>Configurações ->
                        Sistema</strong>.
                </p>
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
                    <img src="<?= base_url('assets/img/ajax-loader.gif') ?>" alt="Carregando..." />
                    <p>Verificando...</p>
                </div>
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
            <div class="span12" style="padding: 1%; margin-left: 0;">
                <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
                    <button type="button" id="btn-refresh-fila" class="btn btn-default" style="margin-right: 5px;"><i class="fas fa-sync"></i> Atualizar Lista</button>
                    <button type="button" id="btn-force-cron-fila" class="btn btn-inverse"><i class="fas fa-sync"></i> Forçar Envio (Cron)</button>
                </div>
                
                <div class="alert alert-info" style="border: 1px solid #bce8f1; background-color: #f7fcff;">
                    <button class="close" data-dismiss="alert">×</button>
                    <h4 style="margin-bottom: 10px; color: #2c93b5;"><i class="fas fa-robot"></i> Automação da Fila de Mensagens</h4>
                    <p>Para que as mensagens desta fila sejam enviadas automaticamente em segundo plano, você precisa configurar uma tarefa agendada (Cron Job) no seu painel de hospedagem (cPanel, Plesk, etc).</p>
                    
                    <p style="margin-top: 10px;"><strong>Comando para Execução:</strong></p>
                    <div style="background: #fff; padding: 12px; border: 1px dashed #ced4da; border-radius: 4px; font-family: 'Courier New', monospace; color: #333; margin-top: 5px; font-size: 13px;">
                        curl -s "<?= base_url() ?>index.php/evolution/process_queue" >/dev/null 2>&1
                    </div>
                    
                    <p style="margin-top: 12px; font-size: 0.9em; color: #555;">
                        <i class="fas fa-clock"></i> <strong>Frequência Recomendada:</strong> Executar a cada <strong>1 minuto</strong> (`* * * * *`).
                    </p>
                    <p style="font-size: 0.9em; margin-bottom: 0; color: #555;">
                        <i class="fas fa-info-circle"></i> O comando irá processar as mensagens pendentes em lotes para evitar sobrecarga.
                    </p>
                </div>

                <div class="widget-box">
                    <div class="widget-header">
                        <h5 class="cardHeader"><i class="fas fa-list-ol"></i> Fila de Envio</h5>
                        <div class="widget-buttons" style="float: right; margin: 5px 10px 0 0;">
                            <a href="<?= base_url('index.php/evolution/limpar_fila') ?>" class="btn btn-danger btn-mini"
                                onclick="return confirm('Tem certeza que deseja limpar TODA a fila? Mensagens não enviadas serão perdidas.');"><i
                                    class="fas fa-trash"></i> Limpar Fila</a>
                        </div>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 15%;">Destinatário</th>
                                    <th>Mensagem</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Tentativas</th>
                                    <th style="width: 15%;">Criado em</th>
                                    <th style="width: 10%;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="fila-tbody">
                                <?php $this->load->view('evolution/fila_rows', ['fila' => $fila]); ?>
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
                    <form action="<?= base_url() ?>index.php/evolution/adicionar_mensagem" method="post" enctype="multipart/form-data"
                        class="form-horizontal">
                        <input type="hidden" name="active_tab" id="active_tab" value="#tabStatus">
                        <div class="control-group">
                            <label for="titulo" class="control-label">Título<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" name="titulo" id="titulo" class="span11" required
                                    placeholder="Ex: Lembrete de Vencimento">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="imagem_url" class="control-label">URL da Imagem (Opcional)</label>
                            <div class="controls">
                                <input type="url" name="imagem_url" id="imagem_url" class="span11"
                                    placeholder="https://exemplo.com/imagem.jpg">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="userfile" class="control-label">Upload de Mídia</label>
                            <div class="controls">
                                <input type="file" name="userfile" id="userfile" class="span11">
                                <span class="help-block">Selecione um arquivo para upload (Imagem, Vídeo, Áudio ou Documento). O upload terá prioridade sobre a URL.</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="mensagem" class="control-label">Mensagem<span class="required">*</span></label>
                            <div class="controls">
                                <textarea name="mensagem" id="mensagem" rows="5" class="span11" required></textarea>
                                <div class="help-block" style="margin-top: 10px;">
                                    <p><strong>Variáveis Disponíveis:</strong></p>
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
                <label for="edit_imagem_url" class="control-label">URL da Imagem (Opcional)</label>
                <div class="controls">
                    <input type="url" name="imagem_url" id="edit_imagem_url" class="span12" style="width: 97%;">
                </div>
            </div>
            <div class="control-group">
                <label for="edit_userfile" class="control-label">Upload de Mídia</label>
                <div class="controls">
                    <input type="file" name="userfile" id="edit_userfile" class="span12" style="width: 97%;">
                </div>
            </div>
            <div class="control-group">
                <label for="edit_mensagem" class="control-label">Mensagem<span class="required">*</span></label>
                <div class="controls">
                    <textarea name="mensagem" id="edit_mensagem" rows="5" class="span12" style="width: 97%;" required></textarea>
                    <div class="help-block" style="margin-top: 10px;">
                        <p><strong>Variáveis Disponíveis:</strong></p>
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
    $(document).ready(function () {
        // Script para manter a aba ativa após redirecionamento
        var hash = window.location.hash;
        if (hash) {
            $('a[href="' + hash + '"]').tab('show');
        } else if (window.location.search.includes('tab=')) {
            var urlParams = new URLSearchParams(window.location.search);
            const urlTab = '#' + urlParams.get('tab');
            if (urlTab === '#tabMensagens') {
                $('.nav-tabs a[href="#tabMensagens"]').tab('show');
            } else if (urlTab === '#tabLogs') {
                $('.nav-tabs a[href="#tabLogs"]').tab('show');
            } else if (urlTab === '#tabEventos') {
                $('.nav-tabs a[href="#tabEventos"]').tab('show');
            } else if (urlTab === '#tabFila') {
                $('.nav-tabs a[href="#tabFila"]').tab('show');
            }
        }

        //--- LÓGICA DA ABA STATUS ---//
        $('#btnVerificar').on('click', function (e) {
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
                success: function (response) {
                    var jsonString = JSON.stringify(response, null, 2);
                    $('#json-resultado').text(jsonString);
                    $('#resultado').show();
                },
                error: function (jqXHR, textStatus, errorThrown) {
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
                complete: function () {
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
                        atualizarFila(); // Atualiza a fila após adicionar mensagens
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
        // Adiciona o editor de texto Trumbowyg
        if (typeof ($.fn.trumbowyg) != 'undefined') {
            var trumbowygConfig = {
                lang: 'pt_br',
                autogrow: true,
                btns: [
                    ['viewHTML'],
                    ['strong', 'em', 'del'],
                    ['removeformat']
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

            $('#modalEditar').modal('show');
        });

        //--- LÓGICA DE INSERÇÃO DE VARIÁVEL ---//
        var lastFocusedEditor = null;

        // Rastreia qual editor teve o foco por último
        $('#mensagem, #edit_mensagem')
            .on('tbwfocus', function () {
                lastFocusedEditor = $(this);
            })
            .on('tbwblur', function () {
                // Mantém a referência, pois o clique no botão tira o foco
                lastFocusedEditor = $(this);
            });

        $(document).on('click', '.variable-tag', function () {
            var textToInsert = $(this).text();

            if (lastFocusedEditor) {
                lastFocusedEditor.trumbowyg('restoreRange');
                lastFocusedEditor.trumbowyg('execCmd', {
                    cmd: 'insertText',
                    param: textToInsert,
                    forceCss: false
                });
            } else {
                // Fallback se nenhum editor foi focado ainda (ex: usuário acabou de abrir a página)
                // Tenta inserir no primeiro editor visível ou alerta
                 if ($('#mensagem').is(':visible')) {
                    $('#mensagem').trumbowyg('execCmd', {
                        cmd: 'insertText',
                        param: textToInsert,
                        forceCss: false
                    });
                } else if ($('#edit_mensagem').is(':visible')) {
                     $('#edit_mensagem').trumbowyg('execCmd', {
                        cmd: 'insertText',
                        param: textToInsert,
                        forceCss: false
                    });
                }
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

        // Função para atualizar a tabela da fila via AJAX
        function atualizarFila() {
            $.ajax({
                url: '<?= base_url() ?>index.php/evolution/refresh_queue',
                type: 'GET',
                success: function(data) {
                    $('#fila-tbody').html(data);
                }
            });
        }

        // Botão de atualizar fila manualmente
        $('#btn-refresh-fila').click(function() {
            var btn = $(this);
            var icon = btn.find('i');
            icon.addClass('fa-spin');
            atualizarFila();
            setTimeout(function() { icon.removeClass('fa-spin'); }, 1000);
        });

        // Forçar Cron (Processar Fila)
        $('#btn-force-cron-fila').click(function() {
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i> Processando...');

            $.ajax({
                url: '<?= base_url() ?>index.php/evolution/process_queue',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    btn.prop('disabled', false).html(originalText);
                    alert('Processamento concluído.\nEnviados: ' + (data.processed || 0) + '\nFalhas: ' + (data.failed || 0));
                    atualizarFila(); // Atualiza a tabela sem recarregar a página
                },
                error: function(xhr, status, error) {
                    btn.prop('disabled', false).html(originalText);
                    alert('Erro na requisição: ' + error);
                }
            });
        });

        // Envio Manual Individual
        $(document).on('click', '.btn-envio-manual', function() {
            var btn = $(this);
            var id = btn.data('id');
            
            Swal.fire({
                title: 'Deseja forçar o envio?',
                text: "Esta ação tentará enviar a mensagem imediatamente.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, enviar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
                    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
                    var data = {};
                    data[csrfName] = csrfHash;

                    $.ajax({
                        url: '<?= base_url() ?>index.php/evolution/enviar_item_fila/' + id,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                Swal.fire('Sucesso!', data.message, 'success').then(() => {
                                    atualizarFila(); // Atualiza a tabela sem recarregar a página
                                });
                            } else {
                                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                                Swal.fire('Erro!', data.message || 'Erro desconhecido.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                            var msg = 'Erro ao processar requisição.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    var json = JSON.parse(xhr.responseText);
                                    if(json.message) msg = json.message;
                                } catch(e) {
                                    msg = 'Erro HTTP ' + xhr.status + ': ' + error;
                                }
                            }
                            Swal.fire('Erro!', msg, 'error');
                        }
                    });
                }
            });
        });
    });

</script>