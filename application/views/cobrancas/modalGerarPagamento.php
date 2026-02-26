<?php
$this->load->config('payment_gateways');
?>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>

<div id="modal-gerar-pagamento" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="form-gerar-cobranca" name="cobranca" method="post" action="<?php echo base_url() . 'index.php/cobrancas/adicionar'; ?>">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Escolher Forma de Pagamento</h3>
        </div>
        <div class="modal-body">
            <div id="forma-pag" class="">
                <div class="form-group">
                    <input value="<?php echo $id ?>" name="id" hidden>
                    <input value="<?php echo $tipo ?>" name="tipo" hidden>

                    <?php if (isset($parcelas) && !empty($parcelas)) : ?>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label>Selecione as Parcelas (Opcional):</label>
                            <div style="border: 1px solid #ccc; padding: 10px; max-height: 200px; overflow-y: auto; border-radius: 4px;">
                                <label style="font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 5px; display: block;">
                                    <input type="checkbox" id="select_all_parcelas" checked> Selecionar Todas
                                </label>
                                <?php foreach ($parcelas as $p) : ?>
                                    <?php
                                        $disabled = $p->baixado == 1 ? 'disabled' : '';
                                        $style = $p->baixado == 1 ? 'text-decoration: line-through; color: #888;' : '';
                                        $checked = $p->baixado == 0 ? 'checked' : '';
                                    ?>
                                    <div class="checkbox">
                                        <label style="<?= $style ?>">
                                            <input type="checkbox" class="parcela_checkbox" name="parcelas_id[]" value="<?= $p->idLancamentos ?>" <?= $checked ?> <?= $disabled ?>>
                                            <?= $p->descricao ?> - R$ <?= str_replace(',00', '', number_format($p->valor, 2, ',', '.')) ?> <small>(<?= date('d/m/Y', strtotime($p->data_vencimento)) ?>)</small>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <label for="gateway_de_pagamento">Gateway de Pagamento: </label>
                    <select id="gateway_de_pagamento" class="form-control span12" name="gateway_de_pagamento" required>
                        <option value="" selected>Escolha o gateway de pagamento</option>
                        <?php
                            $gateways = $this->config->item('payment_gateways');
                            if ($gateways && is_array($gateways)) {
                                foreach ($gateways as $paymentGateway) {
                                    echo '<option value="' . $paymentGateway['library_name'] . '">' . $paymentGateway['name'] . '</option>';
                                }
                            }
                        ?>
                    </select>
                    <label id="label_forma_pagamento" for="forma_pagamento" hidden>Forma de Pagamento: </label>
                    <select id="forma_pagamento" class="form-control span12" name="forma_pagamento" required hidden>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true">
                <span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span>
            </button>
            <button id="payment" type="submit" class="button btn btn-info">
                <span class="button__icon"><i class='bx bx-qr'></i></span><span class="button__text2">Gerar Pagamento</span>
            </button>
        </div>
    </form>
</div>

<script>
    (function() {
        try {
            function initPaymentModal() {
                if (typeof jQuery === 'undefined') {
                    setTimeout(initPaymentModal, 100);
                    return;
                }
                
                var $ = jQuery;
                window.paymentGatewaysConfig = <?php echo json_encode($this->config->item('payment_gateways') ?: []); ?>;

                $("#forma_pagamento").hide();
                $("#label_forma_pagamento").hide();

                $('#select_all_parcelas').off('click').on('click', function(event) {   
                    var isChecked = this.checked;
                    $('.parcela_checkbox:not(:disabled)').each(function() {
                        this.checked = isChecked;                        
                    });
                });

                $.getScript("<?= base_url('assets/js/script-payments.js'); ?>");
            }
            initPaymentModal();
        } catch (e) {
            console.error("Erro ao iniciar modal de pagamento:", e);
        }
    })();
</script>
