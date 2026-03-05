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
                        <label for="categoria_id" class="control-label">Categoria</label>
                        <div class="controls">
                            <select name="categoria_id" id="categoria_id">
                                <option value="">Selecione...</option>
                                <?php foreach ($categorias as $cat) { ?>
                                    <option value="<?php echo $cat->idAtivoCategoria; ?>" <?php echo ($result->categoria_id == $cat->idAtivoCategoria) ? 'selected' : ''; ?>><?php echo $cat->nome; ?></option>
                                <?php } ?>
                            </select>
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
                            <div id="drop-zone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; cursor: pointer; background: #f9f9f9; border-radius: 5px;">
                                <p><i class="fas fa-cloud-upload-alt fa-3x" style="color: #ccc;"></i></p>
                                <p>Arraste a nova foto aqui ou clique para alterar</p>
                                <input type="file" name="userfile" id="userfile" style="display: none;" accept="image/*" />
                                <div id="preview-container" style="margin-top: 10px;"></div>
                            </div>
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

        // Drag and Drop Logic
        var dropZone = document.getElementById('drop-zone');
        var fileInput = document.getElementById('userfile');

        dropZone.addEventListener('click', function() { fileInput.click(); });

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.style.borderColor = '#000';
            dropZone.style.background = '#e9e9e9';
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.style.borderColor = '#ccc';
            dropZone.style.background = '#f9f9f9';
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.style.borderColor = '#ccc';
            dropZone.style.background = '#f9f9f9';
            fileInput.files = e.dataTransfer.files;
            updatePreview(fileInput.files[0]);
        });

        fileInput.addEventListener('change', function() { updatePreview(this.files[0]); });

        function updatePreview(file) {
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-container').html('<img src="'+e.target.result+'" style="max-height: 150px; border: 1px solid #ddd; padding: 3px;"> <br> ' + file.name);
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>