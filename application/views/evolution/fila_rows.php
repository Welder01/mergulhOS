<?php if (isset($fila) && !empty($fila)) { ?>
    <?php foreach ($fila as $item) { ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->phone_number ?></td>
            <td><?= substr(strip_tags($item->message), 0, 50) ?>...</td>
            <td>
                <?php
                $statusClass = 'badge';
                if ($item->status == 'pending') $statusClass .= ' badge-warning';
                elseif ($item->status == 'sent') $statusClass .= ' badge-success';
                elseif ($item->status == 'error') $statusClass .= ' badge-important';
                elseif ($item->status == 'falhou') $statusClass .= ' badge-inverse';
                ?>
                <span class="<?= $statusClass ?>"><?= ucfirst($item->status) ?></span>
            </td>
            <td><?= $item->attempts ?? 0 ?></td>
            <td><?= isset($item->created_at) ? date('d/m/Y H:i', strtotime($item->created_at)) : '-' ?></td>
            <td>
                <button type="button" class="btn btn-mini btn-success btn-envio-manual tip-top" data-id="<?= $item->id ?>" title="Forçar Envio"><i class="fas fa-paper-plane"></i></button>
                <a href="<?= base_url('index.php/evolution/excluir_item_fila/' . $item->id) ?>" class="btn btn-mini btn-danger tip-top" title="Excluir"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="7">Nenhuma mensagem na fila.</td>
    </tr>
<?php } ?>