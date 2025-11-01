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

        <div class="span12" style="margin-left: 0">
            <button type="button" class="btn btn-primary" id="btnVerificar">Verificar Status da Instância</button>
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
    });
</script>