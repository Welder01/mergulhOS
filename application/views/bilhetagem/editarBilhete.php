<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<style>
    /* Estilos para corrigir responsividade e alinhamento */
    .form-horizontal .control-group {
        margin-bottom: 10px !important;
    }
    
    .form-horizontal .control-label {
        text-align: left;
        width: 100%;
        font-weight: bold;
        padding-top: 5px;
        display: block;
        margin-bottom: 5px;
    }

    .form-horizontal .controls {
        margin-left: 0;
    }

    input, textarea, select, .uneditable-input {
        max-width: 100%;
        box-sizing: border-box;
        height: 32px !important;
    }

    .input-append {
        display: flex;
        width: 100%;
    }
    .input-append input {
        border-radius: 4px 0 0 4px !important;
        flex: 1;
        width: auto !important;
        height: 32px !important;
    }
    .input-append button {
        border-radius: 0 4px 4px 0 !important;
        margin-left: -1px;
        height: 32px !important;
    }
    
    .widget-content {
        padding: 15px !important;
    }

    .row-fluid {
        margin-bottom: 10px;
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

    .checkbox.inline {
        margin-top: 5px;
        font-size: 14px;
    }

    /* Custom Radio Buttons */
    .radio-btn {
        display: inline-block;
        margin-right: 10px;
        cursor: pointer;
        position: relative;
    }
    .radio-btn input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .radio-btn span {
        display: inline-block;
        padding: 6px 12px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 12px;
        color: #333;
        transition: all 0.2s;
    }
    .radio-btn input:checked + span {
        background-color: #2ecc71;
        color: white;
        border-color: #27ae60;
    }

    /* Custom Switch */
    .switch-container {
        display: inline-flex;
        align-items: center;
        margin-right: 20px;
        cursor: pointer;
        margin-bottom: 0;
    }
    .switch-container input {
        display: none;
    }
    .switch-container .slider {
        position: relative;
        width: 34px;
        height: 18px;
        background-color: #ccc;
        border-radius: 34px;
        transition: .4s;
        margin-right: 8px;
    }
    .switch-container .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }
    .switch-container input:checked + .slider {
        background-color: #2ecc71;
    }
    .switch-container input:checked + .slider:before {
        transform: translateX(16px);
    }
    .switch-container .text {
        font-size: 12px;
    }
</style>

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
                    
                    <!-- Linha 1 -->
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label for="expedicao" class="control-label">Expedição <span class="required">*</span></label>
                                <div class="controls">
                                    <input id="expedicao" type="text" class="span12" value="<?php echo $result->expedicao; ?>" placeholder="Digite para buscar a expedição..." required />
                                    <input id="expedicao_id" type="hidden" name="expedicao_id" value="<?php echo $result->expedicao_id; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label for="cliente" class="control-label">Cliente <span class="required">*</span></label>
                                <div class="controls">
                                    <input id="cliente" type="text" name="cliente" class="span12" value="<?php echo $result->nomeCliente; ?>" placeholder="Digite para buscar o cliente..." required />
                                    <input id="cliente_id" type="hidden" name="cliente_id" value="<?php echo $result->cliente_id; ?>" />
                                    <input id="nomeCliente" type="hidden" name="nomeCliente" value="<?php echo $result->nomeCliente; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Assentos (Hidden by default) -->
                    <div class="row-fluid" id="info_assentos" style="display:none; margin-bottom: 10px;">
                        <div class="span12">
                            <div class="alert alert-info" style="margin-bottom: 0; padding: 8px;">
                                <strong>Capacidade:</strong> <span id="badge_capacidade">0</span> | 
                                <strong>Ocupados:</strong> <span id="badge_ocupados">0</span> | 
                                <strong>Disponíveis:</strong> <span id="badge_disponiveis">0</span>
                                <div class="progress progress-striped active" style="height: 8px; margin-top: 5px; margin-bottom: 0; background: #fff;">
                                    <div class="bar" id="bar_ocupacao" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Linha 2 -->
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label for="viagem_id" class="control-label">Vincular Hospedagem</label>
                                <div class="controls">
                                    <select name="viagem_id" id="viagem_id" class="span12">
                                        <option value="">Selecione um cliente primeiro...</option>
                                        <?php if($result->viagem_id && $result->nome_viagem): ?>
                                            <option value="<?= $result->viagem_id ?>" selected><?= $result->nome_viagem ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget-title" style="margin: 15px 0 10px 0;">
                        <span class="icon"><i class="fas fa-plane"></i></span>
                        <h5>Dados do Transporte</h5>
                    </div>
                    
                    <!-- Linha 3 -->
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Tipo Transporte</label>
                                <div class="controls">
                                    <label class="radio-btn">
                                        <input type="radio" name="tipo_transporte" value="fretado" <?= ($result->tipo_transporte == 'fretado') ? 'checked' : '' ?>> <span>Fretado</span>
                                    </label>
                                    <label class="radio-btn">
                                        <input type="radio" name="tipo_transporte" value="companhia_externa" <?= ($result->tipo_transporte == 'companhia_externa') ? 'checked' : '' ?>> <span>Cia Externa</span>
                                    </label>
                                    <label class="radio-btn">
                                        <input type="radio" name="tipo_transporte" value="meios_proprios" <?= ($result->tipo_transporte == 'meios_proprios') ? 'checked' : '' ?>> <span>Meios Próprios</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linha 4 -->
                    <div class="row-fluid" id="detalhes_transporte">
                        <div class="span4">
                            <div class="control-group">
                                <label for="empresa_emissora" class="control-label">Empresa/Cia</label>
                                <div class="controls">
                                    <input type="text" name="empresa_emissora" id="empresa_emissora" class="span12" value="<?php echo $result->empresa_emissora; ?>" />
                                    <select id="select_transporte" class="span12" style="display:none;"></select>
                                </div>
                            </div>
                        </div>
                        <div class="span3" id="div_tipo_veiculo" style="display:none;">
                            <div class="control-group">
                                <label for="tipo_veiculo" class="control-label">Tipo Veículo</label>
                                <div class="controls">
                                    <select name="tipo_veiculo" id="tipo_veiculo" class="span12">
                                        <option value="onibus" <?= ($result->tipo_veiculo == 'onibus') ? 'selected' : '' ?>>Ônibus</option>
                                        <option value="van" <?= ($result->tipo_veiculo == 'van') ? 'selected' : '' ?>>Van</option>
                                        <option value="aviao" <?= ($result->tipo_veiculo == 'aviao') ? 'selected' : '' ?>>Avião</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label for="codigo_bilhete" class="control-label">Localizador</label>
                                <div class="controls">
                                    <input type="text" name="codigo_bilhete" id="codigo_bilhete" class="span12" value="<?php echo $result->codigo_bilhete; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="control-group">
                                <label for="assento" class="control-label">Assento</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input type="text" name="assento" id="assento" value="<?php echo $result->assento; ?>" />
                                        <button type="button" id="btn-selecionar-assento" class="btn btn-info" style="display:none;"><i class="fas fa-chair"></i> Mapa</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linha 5 (Detalhes Específicos) -->
                    <div class="row-fluid" id="detalhes_especificos" style="display:none;">
                        <div class="span2">
                            <div class="control-group">
                                <label for="terminal" class="control-label">Terminal</label>
                                <div class="controls">
                                    <input type="text" name="terminal" id="terminal" class="span12" value="<?php echo $result->terminal; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span2" id="div_plataforma">
                            <div class="control-group">
                                <label for="plataforma" class="control-label">Plataforma</label>
                                <div class="controls">
                                    <input type="text" name="plataforma" id="plataforma" class="span12" value="<?php echo $result->plataforma; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span2" id="div_portao" style="display:none;">
                            <div class="control-group">
                                <label for="portao" class="control-label">Portão</label>
                                <div class="controls">
                                    <input type="text" name="portao" id="portao" class="span12" value="<?php echo $result->portao; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label for="ponto_encontro" class="control-label">Ponto Encontro</label>
                                <div class="controls">
                                    <input type="text" name="ponto_encontro" id="ponto_encontro" class="span12" value="<?php echo $result->ponto_encontro; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label for="numero_antt_anac" class="control-label">ANTT/ANAC</label>
                                <div class="controls">
                                    <input type="text" name="numero_antt_anac" id="numero_antt_anac" class="span12" value="<?php echo $result->numero_antt_anac; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget-title" style="margin: 15px 0 10px 0;">
                        <span class="icon"><i class="fas fa-money-bill-wave"></i></span>
                        <h5>Valores e Serviços</h5>
                    </div>

                    <!-- Linha 6 -->
                    <div class="row-fluid">
                        <div class="span2">
                            <div class="control-group">
                                <label class="control-label">Moeda</label>
                                <div class="controls">
                                    <select name="moeda_venda" id="moeda_venda" class="span12">
                                        <option value="BRL" <?= ($result->moeda_venda == 'BRL') ? 'selected' : '' ?>>BRL (R$)</option>
                                        <option value="USD" <?= ($result->moeda_venda == 'USD') ? 'selected' : '' ?>>USD ($)</option>
                                        <option value="EUR" <?= ($result->moeda_venda == 'EUR') ? 'selected' : '' ?>>EUR (€)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="control-group">
                                <label class="control-label">Cotação</label>
                                <div class="controls">
                                    <input type="text" name="cotacao_venda" id="cotacao_venda" value="<?php echo $result->cotacao_venda; ?>" class="span12 money" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Valor Bilhete</label>
                                <div class="controls">
                                    <?php 
                                        $val_orig = ($result->moeda_venda == 'BRL') ? $result->valor_bilhete_brl : ($result->valor_bilhete_brl / $result->cotacao_venda);
                                    ?>
                                    <input type="text" name="valor_original" id="valor_original" class="span12 money" value="<?php echo number_format($val_orig, 2, ',', '.'); ?>" required />
                                </div>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="control-group">
                                <label class="control-label">Taxa Serviço</label>
                                <div class="controls">
                                    <input type="text" name="taxa_servico_emissao" class="span12 money" value="<?php echo number_format($result->taxa_servico_emissao, 2, ',', '.'); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Bagagem Extra</label>
                                <div class="controls">
                                    <input type="text" name="valor_bagagem_extra" class="span12 money" value="<?php echo number_format($result->valor_bagagem_extra, 2, ',', '.'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linha 7 -->
                    <div class="row-fluid">
                        <div class="span3">
                            <div class="control-group">
                                <label class="control-label">Dias Navegação</label>
                                <div class="controls">
                                    <input type="number" name="qtd_dias_navegacao" value="<?php echo $result->qtd_dias_navegacao; ?>" class="span12" />
                                </div>
                            </div>
                        </div>
                        <div class="span9">
                            <div class="control-group">
                                <label class="control-label">Adicionais</label>
                                <div class="controls">
                                    <label class="switch-container">
                                        <input type="checkbox" name="pagou_taxa_parque" value="1" <?= ($result->pagou_taxa_parque) ? 'checked' : '' ?>>
                                        <div class="slider"></div> <span class="text">Taxa Parque</span>
                                    </label>
                                    
                                    <label class="switch-container">
                                        <input type="checkbox" name="estadia_estendida" value="1" <?= ($result->estadia_estendida) ? 'checked' : '' ?>>
                                        <div class="slider"></div> <span class="text">Estadia Estendida</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <span class="help-block text-info" id="info_valores_expedicao" style="font-weight:bold;"></span>
                        </div>
                    </div>

                    <div class="widget-title" style="margin: 15px 0 10px 0;">
                        <span class="icon"><i class="fas fa-swimmer"></i></span>
                        <h5>Equipamentos</h5>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <div class="controls">
                                    <label class="switch-container">
                                        <input type="checkbox" id="chk_locar_equipamentos" <?= (!empty($equipamentos_atuais)) ? 'checked' : '' ?>>
                                        <div class="slider"></div> <span class="text">Desejo locar equipamentos para esta expedição</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid" id="div_equipamentos" style="<?= (!empty($equipamentos_atuais)) ? '' : 'display:none;' ?>">
                        <div class="span12">
                            <div class="control-group">
                                <label for="busca_equipamento" class="control-label">Buscar Equipamento</label>
                                <div class="controls">
                                    <input type="text" id="busca_equipamento" class="span12" placeholder="Digite o nome ou patrimônio do equipamento...">
                                </div>
                            </div>
                            <table class="table table-bordered table-condensed" id="tabela_equipamentos" style="margin-top: 10px;">
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

                    <div class="form-actions" style="background:none; border-top:none; text-align:center;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                        <a href="<?php echo base_url() ?>index.php/bilhetagem" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
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

            $('#div_tipo_veiculo').hide();
            $('#detalhes_especificos').hide();

            if (tipo == 'fretado') {
                $('#btn-selecionar-assento').show();
                labelEmpresa.text('Transporte (Busca)');
                $('#empresa_emissora').attr('placeholder', 'Digite para buscar o transporte...');
                $('#detalhes_especificos').show();
            } else {
                $('#btn-selecionar-assento').hide();
                labelEmpresa.text('Empresa/Cia');
                $('#empresa_emissora').attr('placeholder', 'Ex: Latam, Gol...');
                
                if (tipo == 'companhia_externa') {
                    $('#div_tipo_veiculo').show();
                    $('#detalhes_especificos').show();
                }

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
            $('#tipo_veiculo').trigger('change');
        }

        $('#tipo_veiculo').change(function() {
            var tipo = $(this).val();
            if (tipo == 'aviao') {
                $('#div_plataforma').hide();
                $('#div_portao').show();
            } else {
                $('#div_plataforma').show();
                $('#div_portao').hide();
            }
        });

        // Atualiza transporte ao mudar expedição na edição
        $('#expedicao_id').change(function() {
            var id = $(this).val();
            if(id && $('input[name=tipo_transporte]:checked').val() == 'fretado') {
                $.post('<?php echo base_url(); ?>index.php/bilhetagem/get_expedicao_detalhes', {id: id}, function(data){
                    var exp = JSON.parse(data);
                    if(exp.nome_transporte) {
                        $('#empresa_emissora').val(exp.nome_transporte);
                        if(exp.capacidade_transporte > 0) capacidadeAtual = parseInt(exp.capacidade_transporte);
                        
                        // Se houver transportes vinculados, tenta pegar o tipo do primeiro (ou lógica mais complexa se necessário)
                        if(exp.transportes && exp.transportes.length > 0) {
                             $('#tipo_veiculo').val(exp.transportes[0].tipo).trigger('change');
                        }
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