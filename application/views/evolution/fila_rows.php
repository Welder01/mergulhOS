<?php if (isset($fila) && count($fila)): ?>
    <?php foreach ($fila as $item): ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= htmlspecialchars($item->phone_number) ?></td>
            <td>
                <small><?= mb_strimwidth(htmlspecialchars($item->message), 0, 80, "...") ?></small>
                <a href="#modal-fila-details-<?= $item->id ?>" data-toggle="modal" class="btn btn-mini btn-link"><i class="fas fa-eye"></i></a>
                
                <!-- Modal Detalhes Item Fila -->
                <div id="modal-fila-details-<?= $item->id ?>" class="modal hide fade" tabindex="-1" role="dialog">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h3>Detalhes da Mensagem #<?= $item->id ?></h3>
                    </div>
                    <div class="modal-body">
                        <p><strong>Destinatário:</strong> <?= htmlspecialchars($item->phone_number) ?></p>
                        <p><strong>Status:</strong> <?= ucfirst($item->status) ?></p>
                        <p><strong>Erro:</strong> <?= $item->last_error ? htmlspecialchars($item->last_error) : 'Nenhum' ?></p>
                        <hr>
                        <pre><?= htmlspecialchars($item->message) ?></pre>
                    </div>
                </div>
            </td>
            <td style="text-align:center;">
                <?php
                $statusClass = 'label-info';
                switch ($item->status) {
                    case 'pending': $statusClass = 'label-warning'; break;
                    case 'sending': $statusClass = 'label-info'; break;
                    case 'sent': $statusClass = 'label-success'; break;
                    case 'failed': $statusClass = 'label-important'; break;
                }
                ?>
                <span class="label <?= $statusClass ?>"><?= ucfirst($item->status) ?></span>
            </td>
            <td style="text-align:center;"><?= $item->attempts ?></td>
            <td style="text-align:center;"><?= date('d/m/Y H:i', strtotime($item->created_at)) ?></td>
            <td style="text-align: center;">
                <button class="btn btn-mini btn-success btn-envio-manual" data-id="<?= $item->id ?>" title="Enviar Agora"><i class="fas fa-paper-plane"></i></button>
                <a href="<?= base_url('index.php/evolution/excluir_item_fila/' . $item->id) ?>#tabFila"
                    class="btn btn-danger btn-mini" title="Remover da Fila"
                    onclick="return confirm('Remover este item da fila?');"><i
                        class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" style="text-align: center;">A fila de envios está vazia.</td>
    </tr>
<?php endif; ?>