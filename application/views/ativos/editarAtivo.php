<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-box"></i>
                </span>
                <h5>Editar Ativo</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formAtivo" enctype="multipart/form-data" method="post" class="form-horizontal">
                    <?php echo form_hidden('idAtivo', $result->idAtivo) ?>
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo $result->nome; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="patrimonio" class="control-label">Patrimônio<span class="required">*</span></label>
                        <div class="controls">
                            <input id="patrimonio" type="text" name="patrimonio" value="<?php echo $result->patrimonio; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="status" class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="disponivel" <?php echo ($result->status == 'disponivel' ? 'selected' : ''); ?>>Disponível</option>
                                <option value="em_uso" <?php echo ($result->status == 'em_uso' ? 'selected' : ''); ?>>Em Uso</option>
                                <option value="manutencao" <?php echo ($result->status == 'manutencao' ? 'selected' : ''); ?>>Manutenção</option>
                                <option value="baixado" <?php echo ($result->status == 'baixado' ? 'selected' : ''); ?>>Baixado</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="userfile" class="control-label">Foto</label>
                        <div class="controls">
                            <?php if ($result->foto) { ?>
                                <img src="<?php echo base_url('assets/uploads/ativos/' . $result->foto); ?>" alt="Foto do Ativo" style="max-width: 150px; margin-bottom: 10px; display: block;" />
                            <?php } ?>
                            <input type="file" name="userfile" id="userfile" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">QR Code</label>
                        <div class="controls">
                            <div id="qrcode" style="margin-top: 10px;"></div>
                            <input type="hidden" id="codigo_qr_valor" value="<?php echo $result->codigo_qr; ?>">
                            <span class="help-block">Código: <?php echo $result->codigo_qr; ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                                <a href="<?php echo base_url() ?>index.php/ativos" id="" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#formAtivo').validate({
            rules: { nome: { required: true }, patrimonio: { required: true } },
            messages: { nome: { required: 'Campo Requerido.' }, patrimonio: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });

        // Gerar QR Code
        var codigoQr = $('#codigo_qr_valor').val();
        if(codigoQr) {
            new QRCode(document.getElementById("qrcode"), {
                text: codigoQr,
                width: 128,
                height: 128
            });
        }
    });
</script>