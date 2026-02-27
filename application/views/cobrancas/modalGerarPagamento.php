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
    (function($) {
        // Garantir que o jQuery está carregado
        if (typeof $ === 'undefined') {
            console.error('jQuery não está carregado. O script não pode ser executado.');
            return;
        }

        window.paymentGatewaysConfig = <?php echo json_encode($this->config->item('payment_gateways') ?: []); ?>;

        function initPaymentGatewayScripts() {
            if ($("#gateway_de_pagamento").length) {
                $.getScript("<?= base_url('assets/js/script-payments.js'); ?>");
            }
        }

        // Delegação de evento para o checkbox de selecionar todas as parcelas
        $(document).on('click', '#select_all_parcelas', function() {
            var isChecked = this.checked;
            $('.parcela_checkbox:not(:disabled)').prop('checked', isChecked);
        });

        // Delegação de evento para o formulário de geração de cobrança
        $(document).on('submit', '#form-gerar-cobranca', function(e) {
            e.preventDefault();

            const form = $(this);
            const parcelasSelecionadas = $('.parcela_checkbox:checked:not(:disabled)');
            const totalParcelas = parcelasSelecionadas.length;

            if (totalParcelas === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nenhuma Parcela Selecionada',
                    text: 'Por favor, selecione ao menos uma parcela para gerar a cobrança.'
                });
                return;
            }

            const formData = form.serializeArray().reduce((obj, item) => {
                // Exclui o campo 'parcelas_id[]' original para não ser enviado
                if (item.name !== 'parcelas_id[]') {
                    obj[item.name] = item.value;
                }
                return obj;
            }, {});

            const parcelasIds = parcelasSelecionadas.map(function() {
                return $(this).val();
            }).get();

            let logHtml = `
                <div id="swal-log-container" style="margin-top: 20px; text-align: left; max-height: 150px; overflow-y: auto; background-color: #f5f5f5; border: 1px solid #ddd; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px;">
                    <p>Iniciando processo...</p>
                </div>
            `;
            
            Swal.fire({
                title: 'Gerando Boletos...',
                html: `Por favor, aguarde.<div id="swal-progress-text"></div>${logHtml}`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const logContainer = $('#swal-log-container');
            const progressText = $('#swal-progress-text');

            function appendLog(message, isError = false) {
                const color = isError ? 'red' : 'green';
                logContainer.append(`<p style="color: ${color}; margin: 2px 0;">${message}</p>`);
                logContainer.scrollTop(logContainer[0].scrollHeight); // Auto-scroll
            }

            function processarParcela(index) {
                if (index >= totalParcelas) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Processo Concluído!',
                        text: 'Todos os boletos foram gerados com sucesso.',
                        timer: 2000
                    }).then(() => {
                        // Pode redirecionar ou recarregar a página aqui
                        location.reload();
                    });
                    return;
                }

                const parcelaId = parcelasIds[index];
                const parcelaDesc = parcelasSelecionadas.eq(index).closest('label').text().trim();
                
                progressText.text(`Processando ${index + 1} de ${totalParcelas}...`);
                
                const dataToSend = { ...formData, 'parcelas_id': parcelaId };

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: dataToSend,
                    dataType: 'json',
                    success: function(response) {
                        appendLog(`[SUCESSO] ${parcelaDesc}`);
                        // Chama a próxima iteração
                        processarParcela(index + 1);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = 'Ocorreu um erro desconhecido.';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            errorMessage = jqXHR.responseJSON.message;
                        } else if (typeof errorThrown === 'string') {
                            errorMessage = errorThrown;
                        }
                        
                        appendLog(`[ERRO] ${parcelaDesc}: ${errorMessage}`, true);

                        Swal.update({
                           icon: 'error',
                           title: 'Erro na Geração',
                           html: `Ocorreu um erro ao gerar o boleto para a parcela:<br><strong>${parcelaDesc}</strong><br><small>${errorMessage}</small><br><br>O processo foi interrompido.${logHtml}`,
                           showConfirmButton: true,
                        });
                        // Interrompe o processo
                    }
                });
            }

            // Inicia o processo com a primeira parcela
            processarParcela(0);
        });

        // Inicializa os scripts de pagamento quando o modal é aberto
        $('#modal-gerar-pagamento').on('shown', function() {
            initPaymentGatewayScripts();
        });

    })(jQuery);
</script>
