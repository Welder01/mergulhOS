<div class="widget-box">
    <div class="widget-title">
        <span class="icon"><i class="fas fa-rocket"></i></span>
        <h5>Integração com Evolution API</h5>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tabStatus">Status</a></li>
            <li><a data-toggle="tab" href="#tabFila">Fila de Envios</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
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
                <div id="resultado" style="display:none;">
                    <h4>Resultado:</h4>
                    <pre id="json-resultado"></pre>
                </div>
                <div id="loading" style="display:none; text-align: center;">
                    <img src="<?= base_url('assets/img/loading.gif') ?>" alt="Carregando...">
                    <p>Verificando...</p>
                </div>
            </div>
        </div>

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
                                            <td>
                                                <a href="<?= site_url('evolution/forcar_envio_item/' . $item->id) ?>" class="btn btn-mini btn-success tip-top" title="Forçar Envio Imediato"><i class="fas fa-paper-plane"></i></a>
                                                <a href="<?= site_url('evolution/excluir_item_fila/' . $item->id) ?>" class="btn btn-mini btn-danger tip-top" title="Excluir"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5">Fila vazia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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

            var instanceName = $('#instance_name').val();
            if (!instanceName) {
                Swal.fire('Atenção', 'Por favor, informe o nome da instância.', 'warning');
                return;
            }

            $('#btnVerificar').prop('disabled', true);
            $('#resultado').hide();
            $('#loading').show();

            $.ajax({
                url: '<?= site_url('evolution/fetch_instance') ?>',
                type: 'POST',
                data: {
                    instance_name: instanceName,
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
    });
</script>