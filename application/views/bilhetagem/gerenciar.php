<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aBilhete')) { ?>
    <a href="<?php echo base_url(); ?>index.php/bilhetagem/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Emitir Bilhete</a>
    <a href="<?php echo base_url(); ?>index.php/expedicoes" class="btn btn-primary"><i class="fas fa-map-marked-alt"></i> Gerenciar Expedições</a>
<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-ticket-alt"></i>
        </span>
        <h5>Bilhetes Emitidos</h5>
    </div>
    <div class="widget-content nopadding">
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Expedição</th>
                    <th>Cliente</th>
                    <th>Transporte</th>
                    <th>Data Emissão</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!$results) {
                    echo '<tr>
                                <td colspan="7">Nenhum Bilhete Emitido</td>
                            </tr>';
                }
                foreach ($results as $r) {
                    $status_label = ($r->status == 'ativo') ? 'label-success' : (($r->status == 'cancelado') ? 'label-important' : 'label-info');
                    echo '<tr>';
                    echo '<td>' . $r->idBilhete . '</td>';
                    echo '<td>' . $r->expedicao . '</td>';
                    echo '<td>' . $r->nomeCliente . '</td>';
                    echo '<td>' . ucfirst(str_replace('_', ' ', $r->tipo_transporte)) . ' (' . $r->empresa_emissora . ')</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($r->data_emissao)) . '</td>';
                    echo '<td><span class="label ' . $status_label . '">' . ucfirst($r->status) . '</span></td>';
                    echo '<td>';
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vBilhete')) {
                        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/bilhetagem/visualizar/' . $r->idBilhete . '" class="btn btn-inverse tip-top" title="Visualizar"><i class="fas fa-eye"></i></a>';
                        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/bilhetagem/imprimirVoucher/' . $r->idBilhete . '" target="_blank" class="btn btn-info tip-top" title="Imprimir Voucher"><i class="fas fa-print"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eBilhete')) {
                        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/bilhetagem/editar/' . $r->idBilhete . '" class="btn btn-info tip-top" title="Editar Bilhete"><i class="fas fa-edit"></i></a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo $this->pagination->create_links(); ?>