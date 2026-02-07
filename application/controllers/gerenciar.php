<div class="widget-box">
    <div class="widget-title">
        <span class="icon"><i class="fas fa-rocket"></i></span>
        <h5>Integração com Evolution API</h5>
    </div>
    <div class="widget-content">
        <div class="span12 well">
            <p>Nesta seção, você pode verificar o status de conexão de uma instância da Evolution API.</p>
            <p>Certifique-se de que a <strong>URL da API</strong> e a <strong>Chave (apikey)</strong> estejam salvas corretamente em <strong>Configurações -> Sistema</strong>.</p>
        </div>

        <div class="span12 alert alert-info" style="margin-left: 0">
            <h4>Automação da Fila de Mensagens (Cron Job)</h4>
            <p>Para que as mensagens sejam enviadas automaticamente, configure uma tarefa agendada (Cron Job) no seu painel de hospedagem.</p>
            <p><strong>Comando para Execução:</strong><br>
            <code>/usr/bin/php8.3 /home/mergulhar/web/bhdivers.com.br/public_html/index.php evolution_cron process</code></p>
            <p><strong>Frequência:</strong> A cada minuto (<code>* * * * *</code>)</p>
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
</div>

<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
                url: '<?= base_url() ?>index.php/evolution/fetch_instance',
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