<?php $this->load->view('clientes/editarCliente_style'); ?>
<div class="widget-box">
    <div class="widget-title" style="margin: 0;font-size: 1.1em">
        <span class="icon"><i class="fas fa-user"></i></span><h5>Dados do Cliente</h5>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1"><i class="bx bx-user"></i> Dados do Cliente</a></li>
            <li><a data-toggle="tab" href="#tabEquipamentos"><i class="bx bx-swim"></i> Equipamentos</a></li>
            <li><a data-toggle="tab" href="#tabSaude"><i class="bx bx-first-aid"></i> Saúde e Segurança</a></li>
            <li><a data-toggle="tab" href="#tabRestricoes"><i class="bx bx-food-menu"></i> Restrições Alimentares</a></li>
            <li><a data-toggle="tab" href="#tabOs"><i class="bx bx-spreadsheet"></i> Ordens de Serviço</a></li>
            <li><a data-toggle="tab" href="#tabVendas"><i class="bx bx-cart-alt"></i> Vendas</a></li>
            <li><a data-toggle="tab" href="#tabCursos"><i class="bx bxs-graduation"></i> Cursos</a></li>
            <li><a data-toggle="tab" href="#tabViagens"><i class="bx bxs-plane-alt"></i> Viagens</a></li>
            <li><a data-toggle="tab" href="#tabCertificacoes"><i class="bx bx-certification"></i> Certificações</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <div class="progress" style="margin: 10px 20px;">
            <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                0%
            </div>
        </div>


        <div id="tab1" class="tab-pane active" style="min-height: 300px">
            <div class="accordion" id="collapse-group">
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                <span><i class='bx bx-user icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Dados Pessoais</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseGOne">
                        <div class="widget-content">
                            <table class="table table-bordered" style="border: 1px solid #ddd">
                                <tbody>
                                <tr>
                                    <td style="text-align: right; width: 30%"><strong>Nome</strong></td>
                                    <td>
                                        <?php echo $result->nomeCliente ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Documento</strong></td>
                                    <td>
                                        <?php echo $result->documento ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Sexo</strong></td>
                                    <td>
                                        <?php echo $result->sexo ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Data de Nascimento</strong></td>
                                    <td>
                                        <?php echo $result->data_nascimento ? date('d/m/Y', strtotime($result->data_nascimento)) : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Altura</strong></td>
                                    <td><?php echo isset($result->altura) ? $result->altura . ' m' : 'Não informado'; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Peso</strong></td>
                                    <td><?php echo isset($result->peso) ? $result->peso . ' kg' : 'Não informado'; ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Data de Cadastro</strong></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($result->dataCadastro)) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Tipo do Cliente</strong></td>
                                    <td>
                                        <?php echo $result->fornecedor == true ? 'Fornecedor' : 'Cliente'; ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse">
                                <span><i class='bx bx-phone icon-cli'></i></span>
                                <h5 style="padding-left: 28px">Contatos</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGTwo">
                        <div class="widget-content">
                            <table class="table table-bordered" style="border: 1px solid #ddd">
                                <tbody>
                                <tr>
                                    <td style="text-align: right; width: 30%"><strong>Contato:</strong></td>
                                    <td>
                                        <?php echo $result->contato ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; width: 30%"><strong>Telefone</strong></td>
                                    <td>
                                        <?php echo $result->telefone ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Celular</strong></td>
                                    <td>
                                        <?php echo $result->celular ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Email</strong></td>
                                    <td>
                                        <?php echo $result->email ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGThree" data-toggle="collapse">
                                <span><i class='bx bx-map-alt icon-cli' ></i></span>
                                <h5 style="padding-left: 28px">Endereço</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGThree">
                        <div class="widget-content">
                            <table class="table table-bordered th" style="border: 1px solid #ddd;border-left: 1px solid #ddd">
                                <tbody>
                                <tr>
                                    <td style="text-align: right; width: 30%;"><strong>Rua</strong></td>
                                    <td>
                                        <?php echo $result->rua ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Número</strong></td>
                                    <td>
                                        <?php echo $result->numero ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Complemento</strong></td>
                                    <td>
                                        <?php echo $result->complemento ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Bairro</strong></td>
                                    <td>
                                        <?php echo $result->bairro ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Cidade</strong></td>
                                    <td>
                                        <?php echo $result->cidade ?> -
                                        <?php echo $result->estado ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>CEP</strong></td>
                                    <td>
                                        <?php echo $result->cep ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba Equipamentos -->
        <div id="tabEquipamentos" class="tab-pane" style="min-height: 300px">
            <div class="widget-box">
                <div class="widget-title"><h5><i class='bx bx-swim icon-cli'></i> Equipamentos de Mergulho</h5></div>
                <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%;"><strong>Tamanho do Colete:</strong></td>
                                        <td><?= html_escape($result->tamanho_colete ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Peso do Lastro (kg):</strong></td>
                                        <td><?= html_escape($result->peso_lastro ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Tamanho do Neoprene:</strong></td>
                                        <td><?= html_escape($result->tamanho_neoprene ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Tamanho da Nadadeira:</strong></td>
                                        <td><?= html_escape($result->tamanho_nadadeira ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Reguladores:</strong></td>
                                        <td><?= html_escape($result->qtd_reguladores ?? '0') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Lanternas:</strong></td>
                                        <td><?= html_escape($result->qtd_lanterna ?? '0') ?> unidade(s)</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Computadores:</strong></td>
                                        <td><?= html_escape($result->qtd_computador ?? '0') ?> unidade(s)</td>
                                    </tr>
                                </tbody>
                            </table>
                </div>
            </div>
        </div>

        <!-- Aba Saúde e Segurança -->
        <div id="tabSaude" class="tab-pane" style="min-height: 300px">
            <div class="accordion" id="collapse-saude">
                <!-- Seção de Contato de Emergência -->
                <div class="accordion-group widget-box" style="margin-bottom: 10px;">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-saude" href="#collapseEmergencia" data-toggle="collapse">
                                <span><i class="fas fa-first-aid icon-cli"></i></span>
                                <h5 style="padding-left: 28px">Contato de Emergência</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseEmergencia">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%;"><strong>Nome:</strong></td>
                                        <td><?= html_escape($result->contato_emergencia_nome ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Telefone:</strong></td>
                                        <td><?= html_escape($result->contato_emergencia_telefone ?? 'Não informado') ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Parentesco:</strong></td>
                                        <td><?= html_escape($result->contato_emergencia_parentesco ?? 'Não informado') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Seção de Atestado Médico -->
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-saude" href="#collapseAtestado" data-toggle="collapse">
                                <span><i class="fas fa-file-medical-alt icon-cli"></i>
                                    <?php
                                    if (isset($result->atestado_medico_validade) && $result->atestado_medico_validade) {
                                        $dataValidade = new DateTime($result->atestado_medico_validade);
                                        $dataAtual = new DateTime();
                                        echo '<span class="badge ' . ($dataValidade >= $dataAtual ? 'badge-success' : 'badge-important') . '" style="margin-left: 5px; vertical-align: text-top;">' . ($dataValidade >= $dataAtual ? 'Válido' : 'Vencido') . '</span>';
                                    }
                                    ?>
                                </span>
                                <h5 style="padding-left: 28px">Atestado Médico</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseAtestado">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%;"><strong>Data de Emissão:</strong></td>
                                        <td><?= isset($result->atestado_medico_emissao) && $result->atestado_medico_emissao ? date('d/m/Y', strtotime($result->atestado_medico_emissao)) : 'Não informado' ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Validade:</strong></td>
                                        <td><?= isset($result->atestado_medico_validade) && $result->atestado_medico_validade ? date('d/m/Y', strtotime($result->atestado_medico_validade)) : 'Não informado' ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;"><strong>Arquivo:</strong></td>
                                        <td>
                                            <?php if (isset($result->atestado_medico_arquivo) && $result->atestado_medico_arquivo) : ?>
                                                <a href="<?= base_url('assets/uploads/atestados/' . $result->atestado_medico_arquivo) ?>" target="_blank" class="btn btn-mini"><i class="icon-download"></i> Baixar Atestado</a>
                                            <?php else : ?>
                                                Nenhum arquivo
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba Restrições Alimentares -->
        <div id="tabRestricoes" class="tab-pane" style="min-height: 300px">
            <div class="widget-box">
                <div class="widget-title"><h5><i class='bx bx-food-menu icon-cli'></i> Restrições Alimentares</h5></div>
                <div class="widget-content">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Restrição</th>
                                        <th>Observações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($restricoes) && !empty($restricoes)) : ?>
                                        <?php foreach ($restricoes as $r) : ?>
                                            <tr>
                                                <td><?= html_escape($r->restricao) ?></td>
                                                <td><?= html_escape($r->observacoes) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="2">Nenhuma restrição cadastrada.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                </div>
            </div>
        </div>

        <!--Aba Ordens de Serviço-->
        <div id="tabOs" class="tab-pane" style="min-height: 300px">
            <?php if (!$results) { ?>
                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th>N° OS</th>
                        <th>Data Inicial</th>
                        <th>Data Final</th>
                        <th>Descricao</th>
                        <th>Defeito</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="6">Nenhuma OS Cadastrada</td>
                    </tr>
                    </tbody>
                </table>
                <?php
            } else { ?>
                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th>N° OS</th>
                        <th>Data Inicial</th>
                        <th>Data Final</th>
                        <th>Descricao</th>
                        <th>Defeito</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($results as $r) {
                        $dataInicial = date(('d/m/Y'), strtotime($r->dataInicial));
                        $dataFinal = date(('d/m/Y'), strtotime($r->dataFinal));
                        echo '<tr>';
                        echo '<td>' . $r->idOs . '</td>';
                        echo '<td>' . $dataInicial . '</td>';
                        echo '<td>' . $dataFinal . '</td>';
                        echo '<td>' . $r->descricaoProduto . '</td>';
                        echo '<td>' . $r->defeito . '</td>';

                        echo '<td>';
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vOs')) {
                            echo '<a href="' . base_url() . 'index.php/os/visualizar/' . $r->idOs . '" style="margin-right: 1%" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                        }
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eOs')) {
                            echo '<a href="' . base_url() . 'index.php/os/editar/' . $r->idOs . '" class="btn btn-info tip-top" title="Editar OS"><i class="fas fa-edit"></i></a>';
                        }

                        echo  '</td>';
                        echo '</tr>';
                    } ?>
                    <tr>
                    </tr>
                    </tbody>
                </table>
                <?php
            } ?>
        </div>

        <!--Aba Vendas-->
        <div id="tabVendas" class="tab-pane" style="min-height: 300px">
            <?php if (!$result_vendas) { ?>
                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th>N° Venda</th>
                        <th>Data</th>
                        <th>Faturado</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="5">Nenhuma Venda Cadastrada</td>
                    </tr>
                    </tbody>
                </table>
                <?php
            } else { ?>
                <table class="table table-bordered ">
                    <thead>
                    <tr>
                        <th>N° Venda</th>
                        <th>Data</th>
                        <th>Faturado</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($result_vendas as $r) {
                        $dataVenda = date(('d/m/Y'), strtotime($r->dataVenda));
                        $faturado = $r->faturado == 1 ? 'Sim' : 'Não';
                        echo '<tr>';
                        echo '<td>' . $r->idVendas . '</td>';
                        echo '<td>' . $dataVenda . '</td>';
                        echo '<td>' . $faturado . '</td>';
                        echo '<td>R$ ' . number_format($r->valorTotal, 2, ',', '.') . '</td>';
                        echo '<td><a href="' . base_url() . 'index.php/vendas/visualizar/' . $r->idVendas . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a></td>';
                        echo '</tr>';
                    } ?>
                    </tbody>
                </table>
                <?php
            } ?>
        </div>

        <!--Aba Cursos-->
        <div id="tabCursos" class="tab-pane" style="min-height: 300px">
            <?php if (!$cursos) { ?>
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Data de Início</th>
                            <th>Status</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">Nenhum curso cadastrado</td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Data de Início</th>
                            <th>Status</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $c) : ?>
                            <tr>
                                <td><a href="<?= base_url() ?>index.php/cursos/visualizar/<?= $c->curso_id ?>"><?= html_escape($c->nome_curso) ?></a></td>
                                <td><?= date('d/m/Y', strtotime($c->data_inicio)) ?></td>
                                <td><?= html_escape(ucfirst($c->status_aluno)) ?></td>
                                <td>R$ <?= number_format($c->preco, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>

        <!--Aba Viagens-->
        <div id="tabViagens" class="tab-pane" style="min-height: 300px">
            <form class="form-inline" method="get" action="<?= current_url(); ?>" style="margin-bottom: 20px;">
                <input type="hidden" name="id" value="<?= $result->idClientes; ?>">
                <div class="input-append">
                    <input type="text" name="pesquisa_viagem" placeholder="Pesquisar por nome da viagem..." class="span4">
                    <button class="btn"><i class="icon-search"></i></button>
                </div>
            </form>
            <?php if (!$viagens) { ?>
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Viagem</th>
                            <th>Data de Partida</th>
                            <th>Status da Viagem</th>
                            <th>Status Pagamento</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">Nenhuma viagem cadastrada</td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Viagem</th>
                            <th>Data de Partida</th>
                            <th>Status da Viagem</th>
                            <th>Status Pagamento</th>
                            <th>Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($viagens as $v) : ?>
                            <tr>
                                <td><a href="<?= base_url() ?>index.php/viagens/visualizar/<?= $v->viagem_id ?>"><?= html_escape($v->nome_viagem) ?></a></td>
                                <td><?= date('d/m/Y', strtotime($v->data_partida)) ?></td>
                                <td><?= html_escape(ucfirst($v->status_viagem)) ?></td>
                                <td><?= html_escape(ucfirst($v->status_pagamento)) ?></td>
                                <td>R$ <?= number_format($v->preco_pessoa, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>

        <!--Aba Certificações-->
        <div id="tabCertificacoes" class="tab-pane" style="min-height: 300px">
            <div class="accordion" id="collapse-group-cert">
                <!-- Seção de Certificações de Mergulhador -->
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group-cert" href="#collapseCertificacoes" data-toggle="collapse">
                                <span><i class="icon-certificate icon-cli"></i></span>
                                <h5 style="padding-left: 28px">Certificações de Mergulhador</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseCertificacoes">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Formação</th>
                                        <th>Certificadora</th>
                                        <th>Nº Certificado</th>
                                        <th>Emissão</th>
                                        <th>Arquivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($certificacoes) && !empty($certificacoes)) : ?>
                                        <?php foreach ($certificacoes as $c) : ?>
                                            <tr>
                                                <td><?= html_escape($c->nome_certificacao) ?></td>
                                                <td><?= html_escape($c->orgao_emissor) ?></td>
                                                <td><?= html_escape($c->numero_certificacao) ?></td>
                                                <td><?= $c->data_emissao ? date('d/m/Y', strtotime($c->data_emissao)) : '-' ?></td>
                                                <td>
                                                    <?php if ($c->arquivo) : ?>
                                                        <a href="<?= base_url('uploads/certificados/' . $c->arquivo) ?>" target="_blank" class="btn btn-mini"><i class="icon-download"></i> Baixar</a>
                                                    <?php else : ?>
                                                        Nenhum arquivo
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="5">Nenhuma certificação cadastrada.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" style="display:flex;justify-content: center">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            echo '<a title="Icon Title" class="button btn btn-mini btn-info" style="min-width: 140px; top:10px" href="' . base_url() . 'index.php/clientes/editar/' . $result->idClientes . '">
<span class="button__icon"><i class="bx bx-edit"></i></span> <span class="button__text2"> Editar</span></a>';
        } ?>
        <a title="Voltar" class="button btn btn-mini btn-warning" style="min-width: 140px; top:10px" href="<?php echo site_url() ?>/clientes">
          <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
    </div>
</div>

<script>
$(document).ready(function() {
    // Função para atualizar a barra de progresso
    function updateProgressBar() {
        var fields = [
            'nomeCliente', 'documento', 'altura', 'peso', 'contato', 'telefone', 'celular', 'email',
            'cep', 'rua', 'numero', 'bairro', 'cidade', 'estado',
            'tamanho_colete', 'peso_lastro', 'tamanho_neoprene', 'tamanho_nadadeira',
            'contato_emergencia_nome', 'contato_emergencia_telefone', 'contato_emergencia_parentesco',
            'atestado_medico_emissao'
        ];
        
        var result = <?= json_encode($result) ?>;
        var totalFields = fields.length;
        var filledFields = 0;

        fields.forEach(function(field) {
            if (result[field] && result[field].trim() !== '') {
                filledFields++;
            }
        });

        var progress = (totalFields > 0) ? (filledFields / totalFields) * 100 : 0;
        var progressBar = $('#progressBar');
        progressBar.css('width', progress + '%').text(Math.round(progress) + '%');

        progressBar.removeClass('progress-bar-danger progress-bar-warning progress-bar-success');
        if (progress < 40) progressBar.addClass('progress-bar-danger');
        else if (progress < 80) progressBar.addClass('progress-bar-warning');
        else progressBar.addClass('progress-bar-success');
    }

    updateProgressBar();
});
</script>
