<?php if (!$results) : ?>
    <div class="widget-box">
        <div class="widget-title" style="margin: -20px 0 0">
            <span class="icon">
                <i class="fas fa-book"></i>
            </span>
            <h5>Meus Cursos</h5>
        </div>
        <div class="widget-content nopadding">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome do Curso</th>
                        <th>Data de Início</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">Nenhum curso encontrado.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php else : ?>
    <div class="widget-box">
        <div class="widget-title">
            <span class="icon">
                <i class="fas fa-book"></i>
            </span>
            <h5>Meus Cursos</h5>
        </div>
        <div class="widget-content nopadding">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome do Curso</th>
                        <th>Data de Início</th>
                        <th>Status do Aluno</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $r) : ?>
                        <?php
                        $dataInicial = date('d/m/Y', strtotime($r->data_inicio));
                        $cor = '#808080'; // Cor padrão
                        switch (strtolower($r->status_aluno)) {
                            case 'inscrito':
                                $cor = '#00cd00';
                                break;
                            case 'concluido':
                                $cor = '#436eee';
                                break;
                            case 'cancelado':
                                $cor = '#CD0000';
                                break;
                        }
                        ?>
                        <tr>
                            <td><?= $r->id ?></td>
                            <td><?= html_escape($r->nome_curso) ?></td>
                            <td><?= $dataInicial ?></td>
                            <td><span class="badge" style="background-color: <?= $cor ?>; border-color: <?= $cor ?>"><?= html_escape(ucfirst($r->status_aluno)) ?></span></td>
                            <td><a href="<?= base_url('index.php/cursos/visualizar/' . $r->id) ?>" class="btn-nwe" title="Visualizar Curso"><i class="bx bx-show-alt"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>