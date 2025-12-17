<!-- Action boxes -->
<ul class="cardBox" style="margin-bottom: 20px;">
    <li class="card">
        <a class="cardLink" href="<?= base_url() ?>index.php/mine/conta">
            <div class="grid-blak">
                <div class="numbers">Minha Conta</div>
                <div class="cardName">Dados</div>
            </div>
            <div class="lord-icon02">
                <i class='bx bx-user-circle iconBx02'></i>
            </div>
        </a>
    </li>

    <li class="card">
        <a class="cardLink" href="<?= base_url() ?>index.php/mine/os">
            <div class="grid-blak">
                <div class="numbers">Ordens</div>
                <div class="cardName">Serviço</div>
            </div>
            <div class="lord-icon04">
                <i class='bx bx-spreadsheet iconBx04'></i>
            </div>
        </a>
    </li>

    <li class="card">
        <a class="cardLink" href="<?= base_url() ?>index.php/mine/compras">
            <div class="grid-blak">
                <div class="numbers">Compras</div>
                <div class="cardName">Produtos</div>
            </div>
            <div class="lord-icon05">
                <i class='bx bx-cart-alt iconBx05'></i>
            </div>
        </a>
    </li>

    <li class="card">
        <a class="cardLink" href="<?= base_url() ?>index.php/mine/cobrancas">
            <div class="grid-blak">
                <div class="numbers">Cobranças</div>
                <div class="cardName">Financeiro</div>
            </div>
            <div class="lord-icon06">
                <i class='bx bx-credit-card-front iconBx06'></i>
            </div>
        </a>
    </li>

    <li class="card">
        <a class="cardLink" href="<?= base_url() ?>index.php/mine/minhasViagens">
            <div class="grid-blak">
                <div class="numbers">Viagens</div>
                <div class="cardName">Agendadas</div>
            </div>
            <div class="lord-icon03">
                <i class='bx bxs-paper-plane iconBx03'></i>
            </div>
        </a>
    </li>

    <li class="card">
        <a class="cardLink" href="<?= base_url() ?>index.php/mine/meusCursos">
            <div class="grid-blak">
                <div class="numbers">Cursos</div>
                <div class="cardName">Inscritos</div>
            </div>
            <div class="lord-icon07">
                <i class='bx bxs-book-bookmark iconBx07'></i>
            </div>
        </a>
    </li>
</ul>
<!-- End-Action boxes -->

<div class="span12" style="margin-left: 0">


    <?php if ($alerta_perfil_incompleto): ?>
        <div class="modern-alert alert-warning">
            <i class="fas fa-exclamation-triangle alert-icon"></i>
            <div>
                <strong>Atenção!</strong> Seu perfil de saúde e equipamentos está incompleto. Para garantir que tudo esteja
                pronto para sua próxima viagem, por favor,
                <a href="<?= site_url('mine/conta?tab=saude') ?>"
                    style="font-weight: bold; text-decoration: underline;">clique aqui para atualizar suas informações</a>.
            </div>
        </div>
    <?php endif; ?>

    <div class="widget-box">
        <div class="widget-title">
            <span class="icon"><i class="fas fa-signal"></i></span>
            <h5>Últimas Compras</h5>
            <div class="buttons">
                <a title="Ver mais" class="btn btn-mini" href="<?= site_url('mine/compras') ?>"><i
                        class="fas fa-eye"></i></a>
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
                <a title="Ver mais" class="btn btn-mini" href="<?= site_url('mine/os') ?>"><i
                        class="fas fa-eye"></i></a>
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