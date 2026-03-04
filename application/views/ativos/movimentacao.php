<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </span>
                <h5>Check-in / Check-out de Ativos e Bolsas</h5>
            </div>
            <div class="widget-content">
                <div class="control-group">
                    <label>Pesquisar (Nome, Patrimônio, Código ou QR Code):</label>
                    <div class="controls">
                        <input type="text" id="termo_busca" class="span10" placeholder="Digite ou escaneie o código..." autofocus>
                        <button class="btn btn-primary span2" id="btn-pesquisar"><i class="fas fa-search"></i> Pesquisar</button>
                    </div>
                </div>
                
                <div id="resultado_busca" style="margin-top: 20px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Ação -->
<div id="modal-acao" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="modalTitulo">Confirmar Ação</h5>
    </div>
    <div class="modal-body">
        <form id="formMovimentacao">
            <input type="hidden" id="mov_id" name="id">
            <input type="hidden" id="mov_tipo" name="tipo">
            <input type="hidden" id="mov_acao" name="acao">
            
            <div id="divResponsavel" style="display:none;">
                <label>Tipo de Responsável</label>
                <select name="responsavel_tipo" id="responsavel_tipo">
                    <option value="">Selecione...</option>
                    <option value="cliente">Cliente</option>
                    <option value="usuario">Usuário</option>
                </select>
                
                <label>Responsável</label>
                <input type="text" id="responsavel_nome" placeholder="Digite o nome..." style="width: 90%;">
                <input type="hidden" id="responsavel_id" name="responsavel_id">
            </div>

            <label>Observações</label>
            <textarea name="observacoes" rows="3" style="width: 90%;"></textarea>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        <button class="btn btn-primary" id="btnConfirmarMovimentacao">Confirmar</button>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#termo_busca").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/ativos/buscar_movimentacao",
                    type: "POST",
                    dataType: "json",
                    data: { termo: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.codigo + ' - ' + item.nome + ' (' + item.status + ')',
                                value: item.codigo,
                                data: item
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                var item = ui.item.data;
                var acao = '';
                
                if(item.status == 'disponivel' || item.status == 'estoque') {
                    acao = 'checkout';
                } else if(item.status == 'em_uso' || item.status == 'viagem') {
                    acao = 'checkin';
                }
                
                if(acao) {
                    $('#mov_id').val(item.id);
                    $('#mov_tipo').val(item.tipo);
                    $('#mov_acao').val(acao);
                    
                    $('#modalTitulo').text(acao == 'checkout' ? 'Realizar Saída (Check-out)' : 'Realizar Devolução (Check-in)');
                    $('#modalTitulo').append(' - ' + item.nome);
                    
                    if(acao == 'checkout' && item.tipo == 'bolsa') {
                        $('#divResponsavel').show();
                    } else {
                        $('#divResponsavel').hide();
                    }
                    
                    $('#modal-acao').modal('show');
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção",
                        text: "Status inválido para movimentação: " + item.status
                    });
                }
                $(this).val('');
                return false;
            }
        });
        
        // Função para pesquisa manual (Botão ou Enter)
        function realizarPesquisa() {
            var termo = $("#termo_busca").val();
            if(termo.length < 1) return;
            
            $('#resultado_busca').html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');

            $.post('<?php echo base_url(); ?>index.php/ativos/buscar_movimentacao', {termo: termo}, function(data) {
                var resultados = JSON.parse(data);
                var html = '<table class="table table-bordered"><thead><tr><th>Tipo</th><th>Nome</th><th>Código</th><th>Status</th><th>Responsável</th><th>Ação</th></tr></thead><tbody>';
                
                if(resultados.length > 0) {
                    $.each(resultados, function(i, item) {
                        var tipoLabel = item.tipo == 'ativo' ? '<span class="label label-info">Ativo</span>' : '<span class="label label-warning">Bolsa</span>';
                        var btnAcao = '';
                        
                        if(item.status == 'disponivel' || item.status == 'estoque') {
                            btnAcao = '<button class="btn btn-success btn-mini btn-acao" data-id="'+item.id+'" data-tipo="'+item.tipo+'" data-acao="checkout"><i class="fas fa-sign-out-alt"></i> Check-out</button>';
                        } else if(item.status == 'em_uso' || item.status == 'viagem') {
                            btnAcao = '<button class="btn btn-inverse btn-mini btn-acao" data-id="'+item.id+'" data-tipo="'+item.tipo+'" data-acao="checkin"><i class="fas fa-sign-in-alt"></i> Check-in</button>';
                        }

                        html += '<tr>'+
                                '<td>'+tipoLabel+'</td>'+
                                '<td>'+item.nome+'</td>'+
                                '<td>'+item.codigo+'</td>'+
                                '<td>'+item.status+'</td>'+
                                '<td>'+(item.nome_responsavel ? item.nome_responsavel : '-')+'</td>'+
                                '<td>'+btnAcao+'</td>'+
                                '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="6">Nenhum resultado encontrado.</td></tr>';
                }
                html += '</tbody></table>';
                $('#resultado_busca').html(html);
            });
        }

        $('#btn-pesquisar').click(function() { realizarPesquisa(); });

        $(document).on('click', '.btn-acao', function() {
            var id = $(this).data('id');
            var tipo = $(this).data('tipo');
            var acao = $(this).data('acao');
            
            $('#mov_id').val(id);
            $('#mov_tipo').val(tipo);
            $('#mov_acao').val(acao);
            
            $('#modalTitulo').text(acao == 'checkout' ? 'Realizar Saída (Check-out)' : 'Realizar Devolução (Check-in)');
            
            if(acao == 'checkout' && tipo == 'bolsa') {
                $('#divResponsavel').show();
            } else {
                $('#divResponsavel').hide();
            }
            
            $('#modal-acao').modal('show');
        });

        $('#responsavel_tipo').change(function() {
            var tipo = $(this).val();
            $('#responsavel_nome').val('');
            $('#responsavel_id').val('');
            if (tipo) {
                $('#responsavel_nome').focus();
            }
        });

        $("#responsavel_nome").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/ativos/autoCompleteResponsavel",
                    dataType: "json",
                    data: { term: request.term, tipo: $('#responsavel_tipo').val() },
                    success: function(data) { response(data); }
                });
            },
            minLength: 1,
            select: function(event, ui) { $("#responsavel_id").val(ui.item.id); }
        });

        $('#btnConfirmarMovimentacao').click(function() {
            var dados = $('#formMovimentacao').serialize();
            $.post('<?php echo base_url(); ?>index.php/ativos/processar_movimentacao', dados, function(data) {
                var json = JSON.parse(data);
                if(json.result) {
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso",
                        text: json.message
                    });
                    $('#modal-acao').modal('hide');
                    $('#termo_busca').trigger('keyup'); // Recarrega a busca
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro",
                        text: json.message
                    });
                }
            });
        });
    });
</script>