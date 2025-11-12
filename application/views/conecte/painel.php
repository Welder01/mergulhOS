<style>
    /* Estilos para os cards de atalho - Padrão Dashboard */
    .card-atalho {
        position: relative;
        background: linear-gradient(45deg, #fff, #f9f9f9);
        padding: 10px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s ease-in-out;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        width: 110px;
        height: 90px;
        text-align: center;
        border: 1px solid #eee;
    }

    .card-atalho:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .card-atalho .icon-atalho {
        font-size: 2em;
        margin-bottom: 5px;
        transition: 0.3s;
    }

    .card-atalho span {
        font-size: 0.85em;
        font-weight: 500;
        color: #555;
        transition: 0.3s;
    }

    .card-atalho:hover .icon-atalho,
    .card-atalho:hover span {
        color: #fff;
    }

    .card-atalho.c1 .icon-atalho { color: #28a745; } .card-atalho.c1:hover { background: #28a745; border-color: #28a745; }
    .card-atalho.c2 .icon-atalho { color: #17a2b8; } .card-atalho.c2:hover { background: #17a2b8; border-color: #17a2b8; }
    .card-atalho.c3 .icon-atalho { color: #ffc107; } .card-atalho.c3:hover { background: #ffc107; border-color: #ffc107; }
    .card-atalho.c4 .icon-atalho { color: #dc3545; } .card-atalho.c4:hover { background: #dc3545; border-color: #dc3545; }
    .card-atalho.c5 .icon-atalho { color: #6f42c1; } .card-atalho.c5:hover { background: #6f42c1; border-color: #6f42c1; }
    .card-atalho.c6 .icon-atalho { color: #fd7e14; } .card-atalho.c6:hover { background: #fd7e14; border-color: #fd7e14; }

    /* Estilos do alerta */
    .modern-alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 8px;
        display: flex;
        align-items: center;
        font-size: 1.1em;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .modern-alert.alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    .modern-alert .alert-icon {
        font-size: 1.5em;
        margin-right: 15px;
    }
</style>
<div class="span12" style="margin-left: 0">

    <div class="span12" style="margin-left: 0; display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin-bottom: 20px;">
        <div class="card-atalho c1" onclick="location.href='<?= base_url() ?>index.php/mine/conta'">
            <i class='bx bx-user-circle icon-atalho'></i>
            <span>Minha Conta</span>
        </div>
        <div class="card-atalho c2" onclick="location.href='<?= base_url() ?>index.php/mine/os'">
            <i class='bx bx-spreadsheet icon-atalho'></i>
            <span>Ordens</span>
        </div>
        <div class="card-atalho c3" onclick="location.href='<?= base_url() ?>index.php/mine/compras'">
            <i class='bx bx-cart-alt icon-atalho'></i>
            <span>Compras</span>
        </div>
        <div class="card-atalho c4" onclick="location.href='<?= base_url() ?>index.php/mine/cobrancas'">
            <i class='bx bx-credit-card-front icon-atalho'></i>
            <span>Cobranças</span>
        </div>
        <div class="card-atalho c5" onclick="location.href='<?= base_url() ?>index.php/mine/minhasViagens'">
            <i class='bx bxs-paper-plane icon-atalho'></i>
            <span>Viagens</span>
        </div>
        <div class="card-atalho c6" onclick="location.href='<?= base_url() ?>index.php/mine/meusCursos'">
            <i class='bx bxs-book-bookmark icon-atalho'></i>
            <span>Cursos</span>
        </div>
    </div>

    <?php if ($alerta_perfil_incompleto) : ?>
        <div class="modern-alert alert-warning">
            <i class="fas fa-exclamation-triangle alert-icon"></i>
            <div>
                <strong>Atenção!</strong> Seu perfil de saúde e equipamentos está incompleto. Para garantir que tudo esteja pronto para sua próxima viagem, por favor, 
                <a href="<?= site_url('mine/conta?tab=saude') ?>" style="font-weight: bold; text-decoration: underline;">clique aqui para atualizar suas informações</a>.
            </div>
        </div>
    <?php endif; ?>

    <div class="widget-box">
        <div class="widget-title">
            <span class="icon"><i class="fas fa-signal"></i></span>
            <h5>Últimas Compras</h5>
            <div class="buttons">
                <a title="Ver mais" class="btn btn-mini" href="<?= site_url('mine/compras') ?>"><i class="fas fa-eye"></i></a>
            </div>
        </div>
        <div class="widget-content">
            <table id="tabela" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data da Compra</th>
                        <th>Responsável</th>
                        <th>Faturado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($compras != null) {
                        foreach ($compras as $p) {
                            if ($p->faturado == 1) {
                                $faturado = 'Sim';
                            } else {
                                $faturado = 'Não';
                            }
                            echo '<tr>';
                            echo '<td>' . $p->idVendas . '</td>';
                            echo '<td>' . date('d/m/Y', strtotime($p->dataVenda)) . '</td>';
                            echo '<td>' . $p->nome . '</td>';
                            echo '<td>' . $faturado . '</td>';
                            echo '<td> <a href="' . base_url() . 'index.php/mine/visualizarCompra/' . $p->idVendas . '" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show"></i></a>
                                  <a href="' . base_url() . 'index.php/mine/imprimirCompra/' . $p->idVendas . '" target="_blank" class="btn-nwe6" title="Imprimir"><i class="bx bx-printer"></i></a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">Nenhuma compra realizada</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="span12" style="margin-left: 0">
    <div class="widget-box">
        <div class="widget-title">
            <span class="icon"><i class="fas fa-diagnoses"></i></span>
            <h5>Últimas Ordens</h5>
            <div class="buttons">
                <a title="Ver mais" class="btn btn-mini" href="<?= site_url('mine/os') ?>"><i class="fas fa-eye"></i></a>
            </div>
        </div>
        <div class="widget-content">
            <table id="tabela" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data Inicial</th>
                        <th>Data Final</th>
                        <th>Responsável</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($os != null) {
                        foreach ($os as $o) {
                            echo '<tr>';
                            echo '<td>' . $o->idOs . '</td>';
                            echo '<td>' . date('d/m/Y', strtotime($o->dataInicial)) . '</td>';
                            echo '<td>' . date('d/m/Y', strtotime($o->dataFinal)) . '</td>';
                            echo '<td>' . $o->nome . '</td>';
                            echo '<td>' . $o->status . '</td>';
                            echo '<td> <a href="' . base_url() . 'index.php/mine/visualizarOs/' . $o->idOs . '" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show"></i></a>
                                  <a href="' . base_url() . 'index.php/mine/imprimirOs/' . $o->idOs . '" target="_blank" class="btn-nwe6" title="Imprimir"><i class="bx bx-printer"></i></a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6">Nenhuma OS cadastrada</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>