<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-upload"></i>
                </span>
                <h5>Importar Clientes</h5>
            </div>
            <div class="widget-content nopadding">

                <?php if ($this->session->flashdata('success') != null): ?>
                    <div class="alert alert-success" style="margin: 10px;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($import_error_main) && $import_error_main != null): ?>
                    <div class="alert alert-danger" style="margin: 10px;">
                        <?= $import_error_main; ?>
                    </div>
                <?php endif; ?>

                <?php
                if (isset($import_errors_list) && !empty($import_errors_list)):
                    ?>
                    <div class="alert alert-warning" style="margin: 10px;">
                        <strong>Por favor, corrija os seguintes erros na sua planilha e tente novamente:</strong>
                        <a href="<?= site_url('importar/limpar_erros'); ?>" class="btn btn-mini btn-danger"
                            style="float: right;">Limpar Relatório de Erros</a>
                        <table class="table table-bordered table-striped" style="margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th>Linha</th>
                                    <th>Coluna</th>
                                    <th>Valor com Problema</th>
                                    <th>Erro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($import_errors_list as $error): ?>
                                    <tr>
                                        <td><?= isset($error['linha']) ? html_escape($error['linha']) : 'N/A'; ?></td>
                                        <td><?= isset($error['coluna']) ? html_escape($error['coluna']) : 'N/A'; ?></td>
                                        <td><?= isset($error['valor']) ? html_escape($error['valor']) : ''; ?></td>
                                        <td><?= isset($error['erro']) ? html_escape($error['erro']) : html_escape($error); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('importar/upload_clientes'); ?>" id="formImportar" method="post"
                    class="form-horizontal" enctype="multipart/form-data">
                    <div class="control-group">
                        <label for="file" class="control-label">Arquivo (XLS, XLSX)<span
                                class="required">*</span></label>
                        <div class="controls">
                            <input id="file" type="file" name="file" required />
                        </div>
                    </div>

                    <!-- Progress Bar Overlay -->
                    <div id="progressOverlay" style="display:none; text-align:center; padding: 20px;">
                        <h4>Processando importação...</h4>
                        <div class="progress progress-striped active">
                            <div id="progressBar" class="bar" style="width: 0%;">0%</div>
                        </div>
                        <p id="progressMessage">Iniciando...</p>
                    </div>

                    <div id="resultMessage" style="display:none; text-align:center; padding: 10px;"></div>

                    <script>
                        $(document).ready(function () {
                            $('#formImportar').on('submit', function (e) {
                                e.preventDefault();

                                var formData = new FormData(this);
                                var fileInput = $('#file')[0];

                                if (fileInput.files.length === 0) {
                                    alert('Selecione um arquivo.');
                                    return;
                                }

                                $('#progressOverlay').show();
                                $('button[type="submit"]').hide();
                                $('#resultMessage').hide().html('');

                                // Generate Import ID in Frontend
                                var importId = 'import_' + new Date().getTime() + '_' + Math.floor(Math.random() * 10000);
                                formData.append('importId', importId);

                                // Passo 2: Polling de Progresso
                                var pollingInterval = setInterval(function () {
                                    $.get('<?= base_url() ?>index.php/importar/get_progress/' + importId, function (data) {
                                        try {
                                            var progress = JSON.parse(data);
                                            if (progress.progress) {
                                                updateProgress(progress.progress, progress.message);
                                            }
                                        } catch (e) {
                                            // Ignora erros de parse no polling, pode ser que o arquivo ainda não exista
                                        }
                                    });
                                }, 1000);

                                // Passo 1: Iniciar Upload e Processamento
                                $.ajax({
                                    url: '<?= base_url() ?>index.php/importar/upload_clientes',
                                    type: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    timeout: 0, // Sem timeout do lado do cliente (importante para arquivos grandes)
                                    xhr: function () {
                                        var xhr = new window.XMLHttpRequest();
                                        xhr.upload.addEventListener("progress", function (evt) {
                                            if (evt.lengthComputable) {
                                                // Upload é 5% do processo
                                                var percentComplete = (evt.loaded / evt.total) * 5;
                                                updateProgress(percentComplete, 'Enviando arquivo...');
                                            }
                                        }, false);
                                        return xhr;
                                    },
                                    success: function (response) {
                                        clearInterval(pollingInterval); // Para o polling
                                        try {
                                            var res = JSON.parse(response);
                                            if (res.error) {
                                                if (res.error === 'validation_errors') {
                                                    window.location.reload();
                                                } else if (res.error === 'db_errors') {
                                                    window.location.reload();
                                                } else {
                                                    showResult('error', res.error);
                                                }
                                            } else if (res.success) {
                                                updateProgress(100, 'Concluído!');
                                                setTimeout(function () {
                                                    window.location.reload();
                                                }, 1000);
                                            }
                                        } catch (e) {
                                            showResult('error', 'Erro ao interpretar resposta: ' + response);
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        // Se for timeout, não limpa o polling, pois o servidor pode ainda estar rodando
                                        if (status !== 'timeout') {
                                            clearInterval(pollingInterval);
                                            showResult('error', 'Erro na requisição: ' + error);
                                        } else {
                                            console.warn('Timeout do cliente detectado. O servidor ainda pode estar processando.');
                                        }
                                    }
                                });
                            });

                            function updateProgress(percent, message) {
                                $('#progressBar').css('width', percent + '%').text(Math.round(percent) + '%');
                                $('#progressMessage').text(message);
                            }

                            function showResult(type, msg) {
                                $('#progressOverlay').hide();
                                $('button[type="submit"]').show();
                                var color = type === 'error' ? 'red' : 'green';
                                $('#resultMessage').show().css('color', color).html(msg);
                            }
                        });
                    </script>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-success">
                                    <span class="button__icon"><i class="fas fa-upload"></i></span><span
                                        class="button__text2">Importar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>