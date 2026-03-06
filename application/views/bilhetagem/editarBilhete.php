<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                <h5>Editar Bilhete #<?php echo $result->idBilhete; ?></h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formBilhete" method="post" class="form-horizontal">
                    <?php echo form_hidden('idBilhete', $result->idBilhete) ?>
                    
                    <!-- Seleção de Expedição e Cliente -->
                    <div class="control-group">
                        <label for="expedicao_id" class="control-label">Expedição<span class="required">*</span></label>
                        <div class="controls">
                            <select name="expedicao_id" id="expedicao_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($expedicoes as $e) { ?>
                                    <option value="<?= $e->idExpedicao ?>" <?= ($result->expedicao_id == $e->idExpedicao) ? 'selected' : '' ?>><?= $e->titulo ?> (<?= date('d/m/Y', strtotime($e->data_ida)) ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="cliente" class="control-label">Cliente<span class="required">*</span></label>
                        <div class="controls">
                            <input id="cliente" type="text" name="cliente" class="span8" value="<?php echo $result->nomeCliente; ?>" required />
                            <input id="cliente_id" type="hidden" name="cliente_id" value="<?php echo $result->cliente_id; ?>" />
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="viagem_id" class="control-label">Vincular Hospedagem (Viagem)</label>
                        <div class="controls">
                            <select name="viagem_id" id="viagem_id" class="span8">
                                <option value="">Selecione...</option>
                                <?php if($result->viagem_id && $result->nome_viagem): ?>
                                    <option value="<?= $result->viagem_id ?>" selected><?= $result->nome_viagem ?></option>
                                <?php endif; ?>
                            </select>
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
                            <label class="radio inline"><input type="radio" name="tipo_transporte" value="fretado" <?= ($result->tipo_transporte == 'fretado') ? 'checked' : '' ?>> Fretado</label>
                            <label class="radio inline"><input type="radio" name="tipo_transporte" value="companhia_externa" <?= ($result->tipo_transporte == 'companhia_externa') ? 'checked' : '' ?>> Cia Externa</label>
                            <label class="radio inline"><input type="radio" name="tipo_transporte" value="meios_proprios" <?= ($result->tipo_transporte == 'meios_proprios') ? 'checked' : '' ?>> Meios Próprios</label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="empresa_emissora" class="control-label">Empresa/Cia (Fretado/Externa)</label>
                        <div class="controls"><input type="text" name="empresa_emissora" id="empresa_emissora" class="span6" value="<?php echo $result->empresa_emissora; ?>" /></div>
                    </div>
                    
                    <div class="control-group">
                        <label for="codigo_bilhete" class="control-label">Localizador/Bilhete</label>
                        <div class="controls">
                            <input type="text" name="codigo_bilhete" id="codigo_bilhete" class="span4" value="<?php echo $result->codigo_bilhete; ?>" />
                            <span class="help-inline">Assento:</span>
                            <button type="button" id="btn-selecionar-assento" class="btn btn-mini btn-info" style="display:none;"><i class="fas fa-chair"></i> Mapa</button>
                            <input type="text" name="assento" class="span2" value="<?php echo $result->assento; ?>" />
                        </div>
                    </div>

                    <!-- Financeiro e Câmbio -->
                    <div class="widget-title">
                        <span class="icon"><i class="fas fa-money-bill-wave"></i></span>
                        <h5>Valores e Serviços</h5>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Moeda Venda</label>
                        <div class="controls">
                            <select name="moeda_venda" id="moeda_venda" class="span2">
                                <option value="BRL" <?= ($result->moeda_venda == 'BRL') ? 'selected' : '' ?>>BRL (R$)</option>
                                <option value="USD" <?= ($result->moeda_venda == 'USD') ? 'selected' : '' ?>>USD ($)</option>
                                <option value="EUR" <?= ($result->moeda_venda == 'EUR') ? 'selected' : '' ?>>EUR (€)</option>
                            </select>
                            <span class="help-inline">Cotação:</span>
                            <input type="text" name="cotacao_venda" id="cotacao_venda" value="<?php echo $result->cotacao_venda; ?>" class="span2 money" readonly />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="valor_original" class="control-label">Valor Bilhete (Original)</label>
                        <div class="controls">
                            <!-- Recalculando valor original aproximado se não salvo, ou usando valor_bilhete_brl se moeda for BRL -->
                            <?php 
                                $val_orig = ($result->moeda_venda == 'BRL') ? $result->valor_bilhete_brl : ($result->valor_bilhete_brl / $result->cotacao_venda);
                            ?>
                            <input type="text" name="valor_original" id="valor_original" class="money" value="<?php echo number_format($val_orig, 2, ',', '.'); ?>" required />
                            <span class="help-inline">Taxa Serviço:</span>
                            <input type="text" name="taxa_servico_emissao" class="money span2" value="<?php echo number_format($result->taxa_servico_emissao, 2, ',', '.'); ?>" />
                            <span class="help-inline">Bagagem Extra:</span>
                            <input type="text" name="valor_bagagem_extra" class="money span2" value="<?php echo number_format($result->valor_bagagem_extra, 2, ',', '.'); ?>" />
                        </div>
                    </div>

                    <!-- Serviços Adicionais -->
                    <div class="control-group">
                        <label class="control-label">Adicionais Mergulho</label>
                        <div class="controls">
                            <label class="checkbox inline">
                                <input type="checkbox" name="pagou_taxa_parque" value="1" <?= ($result->pagou_taxa_parque) ? 'checked' : '' ?>> Pagar Taxa Parque
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="estadia_estendida" value="1" <?= ($result->estadia_estendida) ? 'checked' : '' ?>> Estadia Estendida
                            </label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="qtd_dias_navegacao" class="control-label">Dias Navegação</label>
                        <div class="controls">
                            <input type="number" name="qtd_dias_navegacao" value="<?php echo $result->qtd_dias_navegacao; ?>" class="span2" />
                            <span class="help-inline text-info" id="info_valores_expedicao"></span>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="busca_equipamento" class="control-label">Locação de Equipamentos</label>
                        <div class="controls">
                            <input type="text" id="busca_equipamento" class="span12" placeholder="Digite o nome ou patrimônio do equipamento...">
                        </div>
                        <div class="controls" style="margin-top: 10px;">
                            <table class="table table-bordered table-condensed" id="tabela_equipamentos">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Patrimônio</th>
                                        <th style="width: 50px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($equipamentos_atuais)) { foreach($equipamentos_atuais as $eq) { ?>
                                    <tr id="equipamento_<?= $eq->ativo_id ?>">
                                        <td><?= $eq->nome ?></td>
                                        <td><?= $eq->patrimonio ?></td>
                                        <td>
                                            <input type="hidden" name="equipamentos[]" value="<?= $eq->ativo_id ?>" />
                                            <button type="button" class="btn btn-mini btn-danger" onclick="$(this).closest('tr').remove();"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                                <a href="<?php echo base_url() ?>index.php/bilhetagem" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mapa de Assentos (Editar) -->
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

    /* Fix input height in append group */
    .input-append input {
        height: 30px !important;
    }
    
    #info_valores_expedicao {
        font-weight: bold;
        color: #2980b9;
        font-size: 1.1em;
        background: #eaf2f8;
        padding: 8px 15px;
        border-radius: 4px;
        border: 1px solid #d6eaf8;
        display: inline-block;
    }
</style>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.money').mask('#.##0,00', {reverse: true});
        var capacidadeAtual = <?php echo (isset($result->capacidade_transporte) && $result->capacidade_transporte > 0) ? $result->capacidade_transporte : 46; ?>;

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
                $('#empresa_emissora').prop('readonly', false);
                $('#codigo_bilhete').prop('readonly', false);
                toggleMapaButton();
            }
        });
        
        // Trigger change on load to set initial state
        $('input[name=tipo_transporte]:checked').trigger('change');

        // Lógica do Mapa de Assentos
        function toggleMapaButton() {
            var tipo = $('input[name=tipo_transporte]:checked').val();
            var labelEmpresa = $("label[for='empresa_emissora']");

            if (tipo == 'fretado') {
                $('#btn-selecionar-assento').show();
                labelEmpresa.text('Transporte (Busca)');
                $('#empresa_emissora').attr('placeholder', 'Digite para buscar o transporte...');
            } else {
                $('#btn-selecionar-assento').hide();
                labelEmpresa.text('Empresa/Cia');
                $('#empresa_emissora').attr('placeholder', 'Ex: Latam, Gol...');

                if ($("#empresa_emissora").data('autocomplete')) {
                    $("#empresa_emissora").autocomplete("destroy");
                }
            }
            
            if (tipo == 'fretado') {
                $("#empresa_emissora").autocomplete({
                    source: "<?php echo base_url(); ?>index.php/transportes/autoComplete",
                    minLength: 1
                });
            }
        }

        // Atualiza transporte ao mudar expedição na edição
        $('#expedicao_id').change(function() {
            var id = $(this).val();
            if(id && $('input[name=tipo_transporte]:checked').val() == 'fretado') {
                $.post('<?php echo base_url(); ?>index.php/bilhetagem/get_expedicao_detalhes', {id: id}, function(data){
                    var exp = JSON.parse(data);
                    if(exp.nome_transporte) {
                        $('#empresa_emissora').val(exp.nome_transporte);
                        if(exp.capacidade_transporte > 0) capacidadeAtual = parseInt(exp.capacidade_transporte);
                    }
                });
            }
        });

        $('#btn-selecionar-assento').click(function() {
            var expedicaoId = $('#expedicao_id').val();
            var transporteNome = $('#empresa_emissora').val();
            $('#modal-assentos').modal('show');
            $('#bus-map').html('<div style="text-align:center"><i class="fas fa-spinner fa-spin"></i> Carregando...</div>');

            $.post('<?php echo base_url(); ?>index.php/bilhetagem/get_assentos_ocupados', {expedicao_id: expedicaoId, transporte: transporteNome}, function(data) {
                var ocupados = JSON.parse(data);
                renderBusMap(ocupados);
            });
        });

        function renderBusMap(ocupados) {
            var html = '<div class="driver-area"><i class="fas fa-dharmachakra steering-wheel"></i> Motorista</div>';
            var totalSeats = capacidadeAtual;
            var seatsPerRow = 4;
            var currentSeat = $('input[name=assento]').val();
            
            for (var i = 1; i <= totalSeats; i += seatsPerRow) {
                html += '<div class="bus-row">';
                html += renderSeat(i, ocupados, currentSeat);
                html += renderSeat(i + 1, ocupados, currentSeat);
                html += '<div class="aisle"></div>';
                html += renderSeat(i + 2, ocupados, currentSeat);
                html += renderSeat(i + 3, ocupados, currentSeat);
                html += '</div>';
            }
            $('#bus-map').html(html);
        }

        function renderSeat(num, ocupados, current) {
            if (num > capacidadeAtual) return '<div class="seat" style="visibility:hidden"></div>';
            var isOccupied = ocupados.includes(num.toString()) && num.toString() != current; // Não marca como ocupado se for o assento atual do bilhete sendo editado
            var isSelected = num.toString() == current;
            
            var classOccupied = isOccupied ? 'occupied' : (isSelected ? 'selected' : '');
            var onClick = isOccupied ? '' : 'onclick="$(\'input[name=assento]\').val('+num+'); $(\'#modal-assentos\').modal(\'hide\');"';
            
            return '<div class="seat ' + classOccupied + '" ' + onClick + '>' + num + '</div>';
        }

        // Autocomplete Equipamento
        $("#busca_equipamento").autocomplete({
            source: "<?php echo base_url(); ?>index.php/ativos/autoCompleteAtivo",
            minLength: 1,
            select: function(event, ui) {
                if ($("#equipamento_" + ui.item.id).length == 0) {
                    var html = '<tr id="equipamento_' + ui.item.id + '">' +
                               '<td>' + ui.item.nome + '</td>' +
                               '<td>' + ui.item.patrimonio + '</td>' +
                               '<td>' +
                               '<input type="hidden" name="equipamentos[]" value="' + ui.item.id + '" />' +
                               '<button type="button" class="btn btn-mini btn-danger" onclick="$(this).closest(\'tr\').remove();"><i class="fas fa-trash"></i></button>' +
                               '</td>' +
                               '</tr>';
                    $("#tabela_equipamentos tbody").append(html);
                }
                $(this).val("");
                return false;
            }
        });
    });
</script>