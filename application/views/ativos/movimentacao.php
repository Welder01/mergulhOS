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
        <button class="btn btn-info" id="btnConferirItens" style="display:none;"><i class="fas fa-list-ol"></i> Conferir Itens</button>
        <button class="btn btn-primary" id="btnConfirmarMovimentacao">Confirmar</button>
    </div>
</div>

<!-- Modal de Conferência -->
<div id="modal-conferencia" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="tituloConferencia">Conferência de Bolsa</h5>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="span12">
                <label><strong>Escanear/Digitar Item:</strong></label>
                <input type="text" id="input-conferencia" class="span12" placeholder="Bipe o código do ativo aqui..." autocomplete="off">
            </div>
        </div>
        
        <div class="progress progress-striped active">
            <div class="bar" id="progresso-conferencia" style="width: 0%;"></div>
        </div>
        <p id="texto-progresso" style="text-align: center; font-weight: bold;">0/0 Itens conferidos</p>

        <div style="max-height: 300px; overflow-y: auto;">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th style="width: 10%">Status</th>
                        <th>Patrimônio/Código</th>
                        <th>Nome</th>
                    </tr>
                </thead>
                <tbody id="lista-itens-conferencia">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
        <button class="btn btn-success" id="btnFinalizarConferencia" disabled><i class="fas fa-check"></i> Finalizar Movimentação</button>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var dadosMovimentacaoPendente = {};
        var itensConferencia = [];
        var itensConferidosCount = 0;

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
                        $('#btnConferirItens').show();
                    } else {
                        $('#divResponsavel').hide();
                        if(item.tipo == 'bolsa') $('#btnConferirItens').show(); else $('#btnConferirItens').hide();
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

        function finalizarMovimentacao(dados) {
            // Se vier da conferência, adiciona observação automática
            if(dados.conferencia) {
                dados.observacoes = (dados.observacoes ? dados.observacoes + " " : "") + "[Conferência Realizada]";
            }

            $.post('<?php echo base_url(); ?>index.php/ativos/processar_movimentacao', dados, function(data) {
                var json = JSON.parse(data);
                if(json.result) {
                    Swal.fire({
                        icon: "success",
                        title: "Sucesso",
                        text: json.message
                    });
                    $('#modal-acao').modal('hide');
                    $('#modal-conferencia').modal('hide');
                    $('#termo_busca').trigger('keyup'); // Recarrega a busca
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro",
                        text: json.message
                    });
                }
            });
        }

        $('#btnConfirmarMovimentacao').click(function() {
            var dados = $('#formMovimentacao').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            finalizarMovimentacao(dados);
        });

        // Lógica de Conferência
        $('#btnConferirItens').click(function() {
            var idBolsa = $('#mov_id').val();
            var acao = $('#mov_acao').val();
            
            // Valida responsável se for checkout
            if(acao == 'checkout' && ($('#responsavel_id').val() == '' && $('#responsavel_tipo').val() != '')) {
                 Swal.fire('Atenção', 'Selecione um responsável válido antes de conferir.', 'warning');
                 return;
            }

            // Salva dados do formulário atual
            dadosMovimentacaoPendente = $('#formMovimentacao').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            dadosMovimentacaoPendente.conferencia = true;

            $('#modal-acao').modal('hide');
            
            // Carrega itens
            $.post('<?php echo base_url(); ?>index.php/ativos/get_itens_bolsa_json', {id: idBolsa}, function(data) {
                itensConferencia = JSON.parse(data);
                itensConferidosCount = 0;
                atualizarInterfaceConferencia();
                $('#modal-conferencia').modal('show');
                setTimeout(function(){ $('#input-conferencia').focus(); }, 500);
            });
        });

        function atualizarInterfaceConferencia() {
            var html = '';
            var total = itensConferencia.length;
            
            $.each(itensConferencia, function(i, item) {
                var statusIcon = item.conferido ? '<i class="fas fa-check-circle" style="color:green"></i>' : '<i class="fas fa-circle" style="color:#ccc"></i>';
                var rowClass = item.conferido ? 'success' : '';
                
                html += '<tr class="'+rowClass+' acao-conferir-item" data-index="'+i+'" style="cursor: pointer;">'+
                        '<td style="text-align:center; font-size: 1.2em;">'+statusIcon+'</td>'+
                        '<td>'+item.patrimonio+'</td>'+
                        '<td>'+item.nome+'</td>'+
                        '</tr>';
            });
            
            $('#lista-itens-conferencia').html(html);
            
            var percent = total > 0 ? (itensConferidosCount / total) * 100 : 100;
            $('#progresso-conferencia').css('width', percent + '%');
            $('#texto-progresso').text(itensConferidosCount + '/' + total + ' Itens conferidos');

            if(itensConferidosCount >= total) {
                $('#btnFinalizarConferencia').prop('disabled', false).removeClass('btn-warning').addClass('btn-success').html('<i class="fas fa-check-double"></i> Finalizar Movimentação');
                $('#progresso-conferencia').parent().removeClass('active').addClass('progress-success');
            } else {
                $('#btnFinalizarConferencia').prop('disabled', false).removeClass('btn-success').addClass('btn-warning').html('<i class="fas fa-exclamation-triangle"></i> Finalizar com Pendências');
            }
        }

        $('#input-conferencia').on('keyup', function(e) {
            if(e.key === 'Enter' || e.keyCode === 13) {
                var codigo = $(this).val().trim();
                if(codigo) {
                    var encontrado = false;
                    $.each(itensConferencia, function(i, item) {
                        if((item.patrimonio == codigo || item.codigo_qr == codigo) && !item.conferido) {
                            item.conferido = true;
                            itensConferidosCount++;
                            encontrado = true;
                            return false; // break
                        }
                    });
                    
                    if(encontrado) {
                        atualizarInterfaceConferencia();
                        $(this).val('');
                    } else {
                        // Opcional: Som de erro ou alerta visual
                    }
                }
            }
        });

        $(document).on('click', '.acao-conferir-item', function() {
            var index = $(this).data('index');
            if (itensConferencia[index]) {
                if (!itensConferencia[index].conferido) {
                    itensConferencia[index].conferido = true;
                    itensConferidosCount++;
                } else {
                    itensConferencia[index].conferido = false;
                    itensConferidosCount--;
                }
                atualizarInterfaceConferencia();
            }
        });

        $('#btnFinalizarConferencia').click(function() {
            var total = itensConferencia.length;
            if(itensConferidosCount < total) {
                Swal.fire({
                    title: 'Itens Pendentes!',
                    text: "Você conferiu apenas " + itensConferidosCount + " de " + total + " itens. Deseja finalizar mesmo assim?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, finalizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        dadosMovimentacaoPendente.observacoes = (dadosMovimentacaoPendente.observacoes ? dadosMovimentacaoPendente.observacoes + ". " : "") + "Obs: Conferência parcial ("+itensConferidosCount+"/"+total+").";
                        finalizarMovimentacao(dadosMovimentacaoPendente);
                    }
                });
            } else {
                finalizarMovimentacao(dadosMovimentacaoPendente);
            }
        });
    });
</script>