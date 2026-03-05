<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                <h5>Emissão de Bilhete</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formBilhete" method="post" class="form-horizontal">
                    
                    <!-- Linha 1: Expedição e Cliente -->
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label for="expedicao" class="control-label">Expedição <span class="required">*</span></label>
                                <div class="controls">
                                    <input id="expedicao" type="text" class="span11" placeholder="Digite para buscar a expedição..." required />
                                    <input id="expedicao_id" type="hidden" name="expedicao_id" />
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label for="cliente" class="control-label">Cliente <span class="required">*</span></label>
                                <div class="controls">
                                    <input id="cliente" type="text" name="cliente" class="span11" placeholder="Digite para buscar o cliente..." required />
                                    <input id="cliente_id" type="hidden" name="cliente_id" />
                                    <input id="nomeCliente" type="hidden" name="nomeCliente" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linha 2: Hospedagem -->
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label for="viagem_id" class="control-label">Vincular Hospedagem</label>
                                <div class="controls">
                                    <select name="viagem_id" id="viagem_id" class="span11">
                                        <option value="">Selecione um cliente primeiro...</option>
                                    </select>
                                    <span class="help-inline" style="color: #999; font-size: 0.9em;">(Opcional - Selecione o cliente para carregar)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dados do Transporte -->
                    <div class="widget-title">
                        <span class="icon"><i class="fas fa-plane"></i></span>
                        <h5>Dados do Transporte</h5>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">Tipo Transporte</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" name="tipo_transporte" value="fretado" checked> Fretado</label>
                            <label class="radio inline"><input type="radio" name="tipo_transporte" value="companhia_externa"> Cia Externa</label>
                            <label class="radio inline"><input type="radio" name="tipo_transporte" value="meios_proprios"> Meios Próprios</label>
                        </div>
                    </div>

                    <!-- Linha 4: Detalhes Transporte -->
                    <div class="row-fluid" id="detalhes_transporte">
                        <div class="span4">
                            <div class="control-group">
                                <label for="empresa_emissora" class="control-label">Empresa/Cia</label>
                                <div class="controls">
                                    <input type="text" name="empresa_emissora" id="empresa_emissora" class="span11" />
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label for="codigo_bilhete" class="control-label">Localizador</label>
                                <div class="controls">
                                    <input type="text" name="codigo_bilhete" id="codigo_bilhete" class="span11" />
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label for="assento" class="control-label">Assento</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input type="text" name="assento" id="assento" class="span6" />
                                        <button type="button" id="btn-selecionar-assento" class="btn btn-info" style="display:none;"><i class="fas fa-chair"></i> Mapa</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financeiro e Câmbio -->
                    <div class="widget-title">
                        <span class="icon"><i class="fas fa-money-bill-wave"></i></span>
                        <h5>Valores e Serviços</h5>
                    </div>

                    <!-- Linha 5: Valores -->
                    <div class="row-fluid">
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Moeda</label>
                                <div class="controls">
                                    <select name="moeda_venda" id="moeda_venda" class="span11">
                                        <option value="BRL">BRL (R$)</option>
                                        <option value="USD">USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Cotação</label>
                                <div class="controls">
                                    <input type="text" name="cotacao_venda" id="cotacao_venda" value="1.0000" class="span11 money" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Valor Bilhete</label>
                                <div class="controls">
                                    <input type="text" name="valor_original" id="valor_original" class="span11 money" required />
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Taxa Serviço</label>
                                <div class="controls">
                                    <input type="text" name="taxa_servico_emissao" class="span11 money" value="0.00" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linha 6: Adicionais -->
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Bagagem Extra</label>
                                <div class="controls">
                                    <input type="text" name="valor_bagagem_extra" class="span11 money" value="0.00" />
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Dias Navegação</label>
                                <div class="controls">
                                    <input type="number" name="qtd_dias_navegacao" value="0" class="span11" />
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label">Opções</label>
                                <div class="controls">
                                    <label class="checkbox inline">
                                        <input type="checkbox" name="pagou_taxa_parque" value="1"> Taxa Parque
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="checkbox" name="estadia_estendida" value="1"> Estadia Estendida
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="controls">
                            <span class="help-inline text-info" id="info_valores_expedicao"></span>
                        </div>
                    </div>

                    <div class="widget-title">
                        <span class="icon"><i class="fas fa-swimmer"></i></span>
                        <h5>Equipamentos</h5>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Locação</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" id="chk_locar_equipamentos"> Desejo locar equipamentos para esta expedição
                            </label>
                        </div>
                    </div>

                    <div class="control-group" id="div_equipamentos" style="display:none;">
                        <label for="equipamentos" class="control-label">Selecionar Itens</label>
                        <div class="controls">
                            <select name="equipamentos[]" id="equipamentos" multiple class="span11" style="height: 150px;">
                                <?php foreach ($ativos_disponiveis as $ativo) { ?>
                                    <option value="<?= $ativo->idAtivo ?>"><?= $ativo->nome ?> (<?= $ativo->patrimonio ?>)</option>
                                <?php } ?>
                            </select>
                            <span class="help-block">Segure Ctrl para selecionar múltiplos itens.</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="text-align: center">
                                <button type="submit" class="btn btn-success btn-large"><i class="fas fa-check"></i> Emitir Bilhete</button>
                                <a href="<?php echo base_url() ?>index.php/bilhetagem" class="btn btn-large"><i class="fas fa-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mapa de Assentos (Adicionar) -->
<div id="modal-assentos" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h5 id="myModalLabel">Mapa de Assentos</h5>
    </div>
    <div class="modal-body">
        <div class="bus-legend" style="text-align: center; margin-bottom: 15px;">
            <span class="label label-success">Livre</span>
            <span class="label label-important">Ocupado</span>
            <span class="label label-info">Selecionado</span>
        </div>
        <div id="bus-map" class="bus-container">
            <!-- Assentos gerados via JS -->
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Fechar</button>
    </div>
</div>

<style>
    .bus-container { width: 220px; margin: 0 auto; background: #f0f0f0; padding: 20px; border-radius: 10px; border: 1px solid #ccc; }
    .bus-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
    .seat { 
        width: 40px; height: 40px; background: #2ecc71; border: 1px solid #27ae60; border-radius: 5px; 
        cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff; 
    }
    .seat.occupied { background: #e74c3c; border-color: #c0392b; cursor: not-allowed; opacity: 0.7; }
    .seat.selected { background: #3498db; border-color: #2980b9; }
    .aisle { width: 40px; text-align: center; font-size: 10px; color: #999; line-height: 40px; }
    .driver-area { text-align: right; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
    .steering-wheel { font-size: 24px; color: #555; }
</style>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.money').mask('#.##0,00', {reverse: true});

        // Autocomplete Expedição
        $("#expedicao").autocomplete({
            source: "<?php echo base_url(); ?>index.php/bilhetagem/autoCompleteExpedicao",
            minLength: 1,
            select: function(event, ui) {
                $("#expedicao_id").val(ui.item.id);
                $('#info_valores_expedicao').text('Custo Barco/Dia: R$ ' + ui.item.preco_barco + ' | Taxa Parque: R$ ' + ui.item.taxa_parque);
            }
        });

        // Autocomplete Cliente
        $("#cliente").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
            minLength: 1,
            select: function(event, ui) {
                $("#cliente_id").val(ui.item.id);
                $("#nomeCliente").val(ui.item.label);
                buscarViagens(ui.item.id);
            }
        });

        // Atualiza Cotação
        $('#moeda_venda').change(function(){
            var moeda = $(this).val();
            if(moeda == 'BRL') {
                $('#cotacao_venda').val('1.0000').prop('readonly', true);
            } else {
                $.post('<?php echo base_url(); ?>index.php/bilhetagem/get_cotacao', {moeda: moeda}, function(data){
                    var json = JSON.parse(data);
                    $('#cotacao_venda').val(json.valor).prop('readonly', false);
                });
            }
        });

        // Lógica Meios Próprios
        $('input[name=tipo_transporte]').change(function(){
            if($(this).val() == 'meios_proprios') {
                $('#valor_original').val('0.00').prop('readonly', true);
                $('#empresa_emissora').val('Próprio').prop('readonly', true);
                $('#codigo_bilhete').prop('readonly', true);
                $('#btn-selecionar-assento').hide();
            } else {
                $('#valor_original').prop('readonly', false);
                $('#empresa_emissora').val('').prop('readonly', false);
                $('#codigo_bilhete').prop('readonly', false);
                toggleMapaButton();
            }
        });

        function buscarViagens(clienteId) {
            $.post('<?php echo base_url(); ?>index.php/bilhetagem/buscar_viagens_cliente', {cliente_id: clienteId}, function(data){
                var viagens = JSON.parse(data);
                var options = '<option value="">Selecione a Viagem...</option>';
                if(viagens.length === 0) {
                    options = '<option value="">Nenhuma viagem encontrada para este cliente</option>';
                }
                for(var i=0; i<viagens.length; i++) {
                    options += '<option value="'+viagens[i].id+'">'+viagens[i].nome_viagem+' ('+viagens[i].data_partida+')</option>';
                }
                $("#viagem_id").html(options);
            });
        }

        // Lógica do Mapa de Assentos
        function toggleMapaButton() {
            var tipo = $('input[name=tipo_transporte]:checked').val();
            if (tipo == 'fretado') {
                $('#btn-selecionar-assento').show();
            } else {
                $('#btn-selecionar-assento').hide();
            }
        }
        
        // Inicializa estado do botão
        toggleMapaButton();
        $('input[name=tipo_transporte]').change(toggleMapaButton);

        $('#btn-selecionar-assento').click(function() {
            var expedicaoId = $('#expedicao_id').val();
            if (!expedicaoId) {
                alert('Por favor, selecione uma expedição primeiro.');
                return;
            }

            $('#modal-assentos').modal('show');
            $('#bus-map').html('<div style="text-align:center"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>');

            $.post('<?php echo base_url(); ?>index.php/bilhetagem/get_assentos_ocupados', {expedicao_id: expedicaoId}, function(data) {
                var ocupados = JSON.parse(data);
                renderBusMap(ocupados);
            });
        });

        function renderBusMap(ocupados) {
            var html = '<div class="driver-area"><i class="fas fa-dharmachakra steering-wheel"></i> Motorista</div>';
            var totalSeats = 46; // Padrão ônibus executivo/leito
            var seatsPerRow = 4;
            
            for (var i = 1; i <= totalSeats; i += seatsPerRow) {
                html += '<div class="bus-row">';
                // Lado Esquerdo (Janela, Corredor)
                html += renderSeat(i, ocupados);
                html += renderSeat(i + 1, ocupados);
                
                html += '<div class="aisle"></div>';
                
                // Lado Direito (Corredor, Janela)
                html += renderSeat(i + 2, ocupados);
                html += renderSeat(i + 3, ocupados);
                html += '</div>';
            }
            $('#bus-map').html(html);
        }

        function renderSeat(num, ocupados) {
            if (num > 46) return '<div class="seat" style="visibility:hidden"></div>'; // Espaço vazio se passar do total
            var isOccupied = ocupados.includes(num.toString());
            var classOccupied = isOccupied ? 'occupied' : '';
            var onClick = isOccupied ? '' : 'onclick="$(\'input[name=assento]\').val('+num+'); $(\'#modal-assentos\').modal(\'hide\');"';
            return '<div class="seat ' + classOccupied + '" ' + onClick + '>' + num + '</div>';
        }

        // Toggle Equipamentos
        $('#chk_locar_equipamentos').change(function() {
            if($(this).is(':checked')) {
                $('#div_equipamentos').slideDown();
            } else {
                $('#div_equipamentos').slideUp();
                $('#equipamentos').val(null); // Limpa seleção
            }
        });
    });
</script>
