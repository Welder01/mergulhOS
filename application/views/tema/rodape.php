<div class="row-fluid">
    <div id="footer" class="span12">
        <a class="pecolor" href="<?= $_ENV['APP_URL_FOOTER'] ?? 'https://github.com/RamonSilva20/mapos' ?>" target="_blank">
            <?= $configuration['app_footer'] ?? '2025 © Ramon Silva - Map-OS' ?> - Versão: <?= $this->config->item('app_version') ?>
        </a>
    </div>
</div>
<!--end-Footer-part-->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/matrix.js"></script>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        var dataTableEnabled = '<?= $configuration['control_datatable'] ?>';
        if(dataTableEnabled == '1') {
            $('#tabela').dataTable( {
                "ordering": false,
                "info": false,
                "language": {
                    "url": "<?= base_url() ?>assets/js/dataTable_pt-br.json",
                },
                "oLanguage": {
                    "sSearch": "Pesquisa rápida na tabela abaixo:"
                }
            } );
        }
    } );
</script>
</html>
