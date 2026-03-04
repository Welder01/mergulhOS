<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-box"></i>
                </span>
                <h5>Editar Bolsa/Caixa</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formBolsa" method="post" class="form-horizontal">
                    <?php echo form_hidden('idBolsa', $result->idBolsa) ?>
                    <div class="control-group">
                        <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nome" type="text" name="nome" value="<?php echo $result->nome; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="codigo_identificador" class="control-label">Código Identificador<span class="required">*</span></label>
                        <div class="controls">
                            <input id="codigo_identificador" type="text" name="codigo_identificador" value="<?php echo $result->codigo_identificador; ?>" />
                            <button id="gerarCodigo" type="button" class="btn btn-inverse btn-mini" style="margin-left: 5px;">Gerar</button>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="status" class="control-label">Status<span class="required">*</span></label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="estoque" <?php echo ($result->status == 'estoque' ? 'selected' : ''); ?>>Estoque</option>
                                <option value="viagem" <?php echo ($result->status == 'viagem' ? 'selected' : ''); ?>>Viagem</option>
                                <option value="manutencao" <?php echo ($result->status == 'manutencao' ? 'selected' : ''); ?>>Manutenção</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="responsavel_tipo" class="control-label">Tipo de Responsável</label>
                        <div class="controls">
                            <select name="responsavel_tipo" id="responsavel_tipo">
                                <option value="">Selecione...</option>
                                <option value="cliente" <?php echo ($result->responsavel_tipo == 'cliente' ? 'selected' : ''); ?>>Cliente</option>
                                <option value="usuario" <?php echo ($result->responsavel_tipo == 'usuario' ? 'selected' : ''); ?>>Usuário</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group" id="divResponsavel" style="<?php echo ($result->responsavel_tipo ? '' : 'display:none;'); ?>">
                        <label for="responsavel" class="control-label">Responsável</label>
                        <div class="controls">
                            <input id="responsavel" type="text" name="responsavel" value="<?php echo $result->nome_responsavel ?? ''; ?>" placeholder="Digite o nome..." />
                            <input id="responsavel_id" type="hidden" name="responsavel_id" value="<?php echo $result->responsavel_id; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">QR Code</label>
                        <div class="controls">
                            <div id="qrcode" style="margin-top: 10px;"></div>
                            <input type="hidden" id="codigo_qr_valor" value="<?php echo (isset($result->codigo_qr)) ? $result->codigo_qr : ''; ?>">
                            <?php if (empty($result->codigo_qr)) { ?>
                                <span class="help-inline" style="color: #b94a48;">Salve a bolsa para gerar o QR Code.</span>
                            <?php } ?>
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

    <div class="span12" style="margin-left: 0">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="fas fa-list"></i></span>
                <h5>Itens na Bolsa</h5>
            </div>
            <div class="widget-content">
                <div class="control-group">
                    <label>Adicionar Ativo:</label>
                    <input type="text" id="add_ativo" placeholder="Digite o nome ou patrimônio do ativo" style="width: 70%;">
                    <button id="btn-bulk-delete" class="btn btn-danger btn-mini" style="float: right; margin-right: 20px; display: none;"><i class="fas fa-trash"></i> Excluir Selecionados</button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Patrimônio</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabela_itens">
                        <?php foreach ($itens as $item) { ?>
                            <tr id="tr-<?php echo $item->id_item_bolsa; ?>">
                                <td><input type="checkbox" class="item-checkbox" value="<?php echo $item->id_item_bolsa; ?>"></td>
                                <td><?php echo $item->patrimonio; ?></td>
                                <td><?php echo $item->nome; ?></td>
                                <td><?php echo ucfirst($item->status); ?></td>
                                <td><button class="btn btn-danger btn-mini btn-remove-item" data-id="<?php echo $item->id_item_bolsa; ?>" data-ativo="<?php echo $item->idAtivo; ?>"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#formBolsa').validate({
            rules: { nome: { required: true }, codigo_identificador: { required: true } },
            messages: { nome: { required: 'Campo Requerido.' }, codigo_identificador: { required: 'Campo Requerido.' } },
            errorClass: "help-inline", errorElement: "span", highlight: function(element, errorClass, validClass) { $(element).parents('.control-group').addClass('error'); }, unhighlight: function(element, errorClass, validClass) { $(element).parents('.control-group').removeClass('error'); $(element).parents('.control-group').addClass('success'); }
        });

        $('#responsavel_tipo').change(function() {
            var tipo = $(this).val();
            if (tipo) {
                $('#divResponsavel').show();
                $('#responsavel').val('');
                $('#responsavel_id').val('');
                $('#responsavel').focus();
            } else {
                $('#divResponsavel').hide();
                $('#responsavel').val('');
                $('#responsavel_id').val('');
            }
        });

        $("#responsavel").autocomplete({
            source: function(request, response) {
                $.ajax({ url: "<?php echo base_url(); ?>index.php/ativos/autoCompleteResponsavel", dataType: "json", data: { term: request.term, tipo: $('#responsavel_tipo').val() }, success: function(data) { response(data); } });
            }, minLength: 1, select: function(event, ui) { $("#responsavel_id").val(ui.item.id); }
        });

        var codigoQr = $('#codigo_qr_valor').val();
        if(codigoQr) { new QRCode(document.getElementById("qrcode"), { text: codigoQr, width: 128, height: 128 }); }

        $("#add_ativo").autocomplete({
            source: "<?php echo base_url(); ?>index.php/ativos/autoCompleteAtivo",
            minLength: 1,
            select: function(event, ui) {
                $.post("<?php echo base_url(); ?>index.php/ativos/adicionarItemBolsa", { bolsa_id: <?php echo $result->idBolsa; ?>, ativo_id: ui.item.id }, function(data) {
                    if(data.result) {
                        var newRow = '<tr id="tr-'+data.id_item_bolsa+'">' +
                            '<td><input type="checkbox" class="item-checkbox" value="'+data.id_item_bolsa+'"></td>' +
                            '<td>'+data.ativo.patrimonio+'</td>' +
                            '<td>'+data.ativo.nome+'</td>' +
                            '<td>'+data.ativo.status.charAt(0).toUpperCase() + data.ativo.status.slice(1)+'</td>' +
                            '<td><button class="btn btn-danger btn-mini btn-remove-item" data-id="'+data.id_item_bolsa+'" data-ativo="'+data.ativo.idAtivo+'"><i class="fas fa-trash"></i></button></td>' +
                            '</tr>';
                        $('#tabela_itens').prepend(newRow);
                        Swal.fire({
                            icon: "success",
                            title: "Sucesso",
                            text: "Item adicionado!"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: "Erro ao adicionar item."
                        });
                    }
                }, "json");
                $(this).val(""); return false;
            }
        });

        $(document).on('click', '.btn-remove-item', function() {
            var id = $(this).data('id');
            var ativo = $(this).data('ativo');
            if(confirm("Remover este item da bolsa?")) { 
                $.post("<?php echo base_url(); ?>index.php/ativos/removerItemBolsa", { id: id, ativo_id: ativo, bolsa_id: <?php echo $result->idBolsa; ?> }, function(data) { 
                    if(data.result) {
                        $('#tr-' + id).remove();
                        Swal.fire({
                            icon: "success",
                            title: "Sucesso",
                            text: "Item removido!"
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: "Erro ao remover item."
                        });
                    }
                }, "json"); 
            }
        });

        // Gerar Código Manual/Automático
        $('#gerarCodigo').click(function() {
            var nome = $('#nome').val();
            if (nome == '') {
                alert('Por favor, preencha o campo Nome primeiro.');
                $('#nome').focus();
                return;
            }
            var prefixo = nome.substring(0, 3).toUpperCase().replace(/[^A-Z0-9]/g, 'X');
            var sufixo = Date.now().toString().slice(-6);
            $('#codigo_identificador').val(prefixo + '-' + sufixo);
        });

        // Exclusão em Massa
        $('#selectAll').click(function() {
            $('.item-checkbox').prop('checked', this.checked);
            toggleBulkButton();
        });

        $(document).on('change', '.item-checkbox', function() {
            toggleBulkButton();
        });

        function toggleBulkButton() {
            if($('.item-checkbox:checked').length > 0) { $('#btn-bulk-delete').show(); } else { $('#btn-bulk-delete').hide(); }
        }

        $('#btn-bulk-delete').click(function(e) {
            e.preventDefault();
            if(confirm('Deseja excluir os itens selecionados?')) {
                var ids = [];
                $('.item-checkbox:checked').each(function() { ids.push($(this).val()); });
                $.post("<?php echo base_url(); ?>index.php/ativos/removerItensBolsaMassa", { ids: ids, bolsa_id: <?php echo $result->idBolsa; ?> }, function(data) {
                    if(data.result) {
                        $.each(ids, function(i, id) { $('#tr-' + id).remove(); });
                        $('#selectAll').prop('checked', false);
                        toggleBulkButton();
                        Swal.fire({
                            icon: "success",
                            title: "Sucesso",
                            text: "Itens removidos!"
                        });
                    }
                }, "json");
            }
        });
    });
</script>