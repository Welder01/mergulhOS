<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-box"></i>
                </span>
                <h5>Cadastro de Ativo</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formAtivo" enctype="multipart/form-data" method="post" class="form-horizontal">
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo set_value('nome'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="categoria_id" class="control-label">Categoria</label>
                        <div class="controls">
                            <select name="categoria_id" id="categoria_id">
                                <option value="">Selecione...</option>
                                <?php foreach ($categorias as $cat) { ?>
                                    <option value="<?php echo $cat->idAtivoCategoria; ?>"><?php echo $cat->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="patrimonio" class="control-label">Patrimônio<span class="required">*</span></label>
                        <div class="controls">
                            <input id="patrimonio" type="text" name="patrimonio" value="<?php echo set_value('patrimonio'); ?>" />
                            <button id="gerarPatrimonio" type="button" class="btn btn-inverse btn-mini" style="margin-left: 5px;">Gerar</button>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="status" class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="disponivel">Disponível</option>
                                <option value="em_uso">Em Uso</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="baixado">Baixado</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="userfile" class="control-label">Foto</label>
                        <div class="controls">
                            <div id="drop-zone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; cursor: pointer; background: #f9f9f9; border-radius: 5px;">
                                <p><i class="fas fa-cloud-upload-alt fa-3x" style="color: #ccc;"></i></p>
                                <p>Arraste a foto aqui ou clique para selecionar</p>
                                <input type="file" name="userfile" id="userfile" style="display: none;" accept="image/*" />
                                <div id="preview-container" style="margin-top: 10px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar</button>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#formAtivo').validate({
            rules: { nome: { required: true }, patrimonio: { required: true } },
            messages: { nome: { required: 'Campo Requerido.' }, patrimonio: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });

        $('#gerarPatrimonio').click(function() {
            var nome = $('#nome').val();
            if (nome == '') {
                alert('Por favor, preencha o campo Nome primeiro.');
                $('#nome').focus();
                return;
            }
            
            // Gera um código baseado nas 3 primeiras letras do nome + timestamp curto
            var prefixo = nome.substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, 'X');
            var sufixo = Date.now().toString().slice(-6);
            
            $('#patrimonio').val(prefixo + '-' + sufixo);
        });

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