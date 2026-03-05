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
                    
                    <!-- Seleção de Expedição e Cliente -->
                    <div class="control-group">
                        <label for="expedicao_id" class="control-label">Expedição<span class="required">*</span></label>
                        <div class="controls">
                            <select name="expedicao_id" id="expedicao_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($expedicoes as $e) { ?>
                                    <option value="<?= $e->idExpedicao ?>"><?= $e->titulo ?> (<?= date('d/m/Y', strtotime($e->data_ida)) ?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="cliente" class="control-label">Cliente<span class="required">*</span></label>
                        <div class="controls">
                            <input id="cliente" type="text" name="cliente" class="span8" required />
                            <input id="cliente_id" type="hidden" name="cliente_id" />
                            <input id="nomeCliente" type="hidden" name="nomeCliente" />
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

                    <div class="control-group">
                        <label for="empresa_emissora" class="control-label">Empresa/Cia</label>
                        <div class="controls"><input type="text" name="empresa_emissora" class="span6" /></div>
                    </div>
                    
                    <div class="control-group">
                        <label for="codigo_bilhete" class="control-label">Localizador/Bilhete</label>
                        <div class="controls">
                            <input type="text" name="codigo_bilhete" class="span4" />
                            <span class="help-inline">Assento:</span>
                            <input type="text" name="assento" class="span2" />
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
                                <option value="BRL">BRL (R$)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                            </select>
                            <span class="help-inline">Cotação:</span>
                            <input type="text" name="cotacao_venda" id="cotacao_venda" value="1.0000" class="span2 money" readonly />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="valor_original" class="control-label">Valor Bilhete</label>
                        <div class="controls">
                            <input type="text" name="valor_original" id="valor_original" class="money" required />
                            <span class="help-inline">Taxa Serviço:</span>
                            <input type="text" name="taxa_servico_emissao" class="money span2" value="0.00" />
                            <span class="help-inline">Bagagem Extra:</span>
                            <input type="text" name="valor_bagagem_extra" class="money span2" value="0.00" />
                        </div>
                    </div>

                    <!-- Serviços Adicionais -->
                    <div class="control-group">
                        <label class="control-label">Adicionais Mergulho</label>
                        <div class="controls">
                            <label class="checkbox inline">
                                <input type="checkbox" name="pagou_taxa_parque" value="1"> Pagar Taxa Parque
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="estadia_estendida" value="1"> Estadia Estendida
                            </label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="qtd_dias_navegacao" class="control-label">Dias Navegação</label>
                        <div class="controls">
                            <input type="number" name="qtd_dias_navegacao" value="0" class="span2" />
                            <span class="help-inline text-info" id="info_valores_expedicao"></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Emitir Bilhete</button>
                                <a href="<?php echo base_url() ?>index.php/bilhetagem" class="btn"><i class="fas fa-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.money').mask('#.##0,00', {reverse: true});

        // Autocomplete Cliente
        $("#cliente").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
            minLength: 1,
            select: function(event, ui) {
                $("#cliente_id").val(ui.item.id);
                $("#nomeCliente").val(ui.item.label);
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

        // Busca detalhes da expedição para mostrar valores
        $('#expedicao_id').change(function(){
            var id = $(this).val();
            if(id) {
                $.post('<?php echo base_url(); ?>index.php/bilhetagem/get_expedicao_detalhes', {id: id}, function(data){
                    var json = JSON.parse(data);
                    $('#info_valores_expedicao').text('Custo Barco/Dia: R$ ' + json.preco_saida_barco_dia + ' | Taxa Parque: R$ ' + json.taxa_parque_unitaria);
                });
            }
        });
    });
</script>
