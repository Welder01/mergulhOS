<div class="widget-box">
    <div class="widget-title" style="margin: 0;font-size: 1.1em">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">Dados do Cliente</a></li>
            <li><a data-toggle="tab" href="#tab2">Ordens de Serviço</a></li>
            <li><a data-toggle="tab" href="#tab3">Vendas</a></li>
            <li><a data-toggle="tab" href="#tab4">Dados Extras</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
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
                                    <td><?= html_escape($result->nomeCliente) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Contato</strong></td>
                                    <td><?= html_escape($result->contato) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Documento</strong></td>
                                    <td>
                                        <?php echo $result->documento ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Data de Cadastro</strong></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($result->dataCadastro)) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Tipo do Cliente</strong></td>
                                    <td>
                                        <?= $result->fornecedor == true ? 'Fornecedor' : 'Cliente'; ?>
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
                                    <td style="text-align: right; width: 30%"><strong>Telefone</strong></td>
                                    <td>
                                        <?= html_escape($result->telefone) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Celular</strong></td>
                                    <td><?= html_escape($result->celular) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Email</strong></td>
                                    <td><?= html_escape($result->email) ?></td>
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
                                    <td><?= html_escape($result->rua) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Número</strong></td>
                                    <td><?= html_escape($result->numero) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Complemento</strong></td>
                                    <td><?= html_escape($result->complemento) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Bairro</strong></td>
                                    <td><?= html_escape($result->bairro) ?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>Cidade</strong></td>
                                    <td>
                                        <?= html_escape($result->cidade) ?> -
                                        <?= html_escape($result->estado) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right"><strong>CEP</strong></td>
                                    <td><?= html_escape($result->cep) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Tab 2-->
        <div id="tab2" class="tab-pane" style="min-height: 300px">
            <?php if (!$results): ?>
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
            <?php else: ?>
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
                    <?php foreach ($results as $r): ?>
                        <?php
                        $dataInicial = date('d/m/Y', strtotime($r->dataInicial));
                        $dataFinal = $r->dataFinal ? date('d/m/Y', strtotime($r->dataFinal)) : '';
                        ?>
                        <tr>
                            <td><?= $r->idOs ?></td>
                            <td><?= $dataInicial ?></td>
                            <td><?= $dataFinal ?></td>
                            <td><?= html_escape($r->descricaoProduto) ?></td>
                            <td><?= html_escape($r->defeito) ?></td>
                            <td>
                                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vOs')): ?>
                                    <a href="<?= base_url() ?>index.php/os/visualizar/<?= $r->idOs ?>" style="margin-right: 1%" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>
                                <?php endif; ?>
                                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eOs')): ?>
                                    <a href="<?= base_url() ?>index.php/os/editar/<?= $r->idOs ?>" class="btn btn-info tip-top" title="Editar OS"><i class="fas fa-edit"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <!--Tab 3-->
        <div id="tab3" class="tab-pane" style="min-height: 300px">
            <?php if (!$result_vendas): ?>
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
                        <td colspan="6">Nenhuma OS Cadastrada</td>
                    </tr>
                    </tbody>
                </table>
            <?php else: ?>
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
                    <?php foreach ($result_vendas as $r): ?>
                        <?php
                        $dataVenda = date('d/m/Y', strtotime($r->dataVenda));
                        $faturado = ($r->faturado == 1) ? 'Sim' : 'Não';
                        ?>
                        <tr>
                            <td><?= $r->idVendas ?></td>
                            <td><?= $dataVenda ?></td>
                            <td><?= $faturado ?></td>
                            <td>R$ <?= number_format($r->valorTotal, 2, ',', '.') ?></td>
                            <td>
                                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')): ?>
                                    <a href="<?= base_url() ?>index.php/vendas/visualizar/<?= $r->idVendas ?>" style="margin-right: 1%" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>
                                <?php endif; ?>
                                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')): ?>
                                    <a href="<?= base_url() ?>index.php/vendas/editar/<?= $r->idVendas ?>" class="btn btn-info tip-top" title="Editar Venda"><i class="fas fa-edit"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <!--Tab 4-->
        <div id="tab4" class="tab-pane" style="min-height: 300px">
            <!-- Seção de Equipamentos de Mergulho -->
            <div class="widget-box" id="equipamentos">
                <div class="widget-title">
                    <span class="icon">
                        <i class="icon-tag"></i>
                    </span>
                    <h5>Equipamentos de Mergulho</h5>
                </div>
                <div class="widget-content nopadding">
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
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Seção de Restrições Alimentares -->
            <div class="widget-box" id="restricoes">
                <div class="widget-title">
                    <span class="icon">
                        <i class="icon-ban-circle"></i>
                    </span>
                    <h5>Restrições Alimentares</h5>
                </div>
                <div class="widget-content">
                    <form action="<?= base_url() ?>index.php/clientes/adicionar_restricao" method="post">
                        <input type="hidden" name="cliente_id" value="<?= $result->idClientes ?>">
                        <div class="control-group">
                            <label for="restricao" class="control-label">Restrição Alimentar</label>
                            <div class="controls">
                                <input id="restricao" type="text" name="restricao" required />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="observacoes" class="control-label">Observações</label>
                            <div class="controls">
                                <textarea id="observacoes" name="observacoes" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-actions" style="background-color:transparent;border:none;padding:10px 0;">
                            <button type="submit" class="btn btn-success">Adicionar Restrição</button>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Restrição</th>
                                <th>Observações</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($restricoes) && !empty($restricoes)): ?>
                                <?php foreach ($restricoes as $r): ?>
                                <tr>
                                    <td><?= html_escape($r->restricao) ?></td>
                                    <td><?= html_escape($r->observacoes) ?></td>
                                    <td><?= date('d/m/Y', strtotime($r->data_cadastro)) ?></td>
                                    <td>
                                        <a href="<?= base_url() ?>index.php/clientes/remover_restricao/<?= $r->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente excluir esta restrição?')">
                                        <i class="icon-trash icon-white"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Nenhuma restrição cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Seção de Certificações de Mergulhador -->
            <div class="widget-box" id="certificacoes">
                <div class="widget-title">
                    <span class="icon">
                        <i class="icon-certificate"></i>
                    </span>
                    <h5>Certificações de Mergulhador</h5>
                </div>
                <div class="widget-content">
                    <form action="<?= base_url() ?>index.php/clientes/adicionar_certificacao" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="cliente_id" value="<?= $result->idClientes ?>">
                        
                        <div class="control-group">
                            <label for="nome_certificacao" class="control-label">Nome da Certificação*</label>
                            <div class="controls">
                                <input id="nome_certificacao" type="text" name="nome_certificacao" required />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label for="orgao_emissor" class="control-label">Órgão Emissor</label>
                            <div class="controls">
                                <input id="orgao_emissor" type="text" name="orgao_emissor" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label for="data_emissao" class="control-label">Data de Emissão</label>
                            <div class="controls">
                                <input id="data_emissao" type="date" name="data_emissao" class="datepicker" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label for="data_validade" class="control-label">Data de Validade</label>
                            <div class="controls">
                                <input id="data_validade" type="date" name="data_validade" class="datepicker" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label for="arquivo" class="control-label">Arquivo (PDF, JPG, PNG - Máx. 5MB)</label>
                            <div class="controls">
                                <input id="arquivo" type="file" name="arquivo" accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label for="observacoes_cert" class="control-label">Observações</label>
                            <div class="controls">
                                <textarea id="observacoes_cert" name="observacoes" rows="2"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-actions" style="background-color:transparent;border:none;padding:10px 0;">
                            <button type="submit" class="btn btn-success">Adicionar Certificação</button>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Certificação</th>
                                <th>Órgão Emissor</th>
                                <th>Emissão</th>
                                <th>Validade</th>
                                <th>Arquivo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($certificacoes) && !empty($certificacoes)): ?>
                                <?php foreach ($certificacoes as $c): ?>
                                <tr>
                                    <td><?= html_escape($c->nome_certificacao) ?></td>
                                    <td><?= html_escape($c->orgao_emissor) ?></td>
                                    <td><?= $c->data_emissao ? date('d/m/Y', strtotime($c->data_emissao)) : '-' ?></td>
                                    <td><?= $c->data_validade ? date('d/m/Y', strtotime($c->data_validade)) : '-' ?></td>
                                    <td>
                                        <?php if ($c->arquivo): ?>
                                        <a href="<?= base_url('uploads/certificados/' . $c->arquivo) ?>" target="_blank" class="btn btn-mini">
                                            <i class="icon-download"></i> Baixar
                                        </a>
                                        <?php else: ?>
                                        Nenhum arquivo
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url() ?>index.php/clientes/remover_certificacao/<?= $c->id ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja realmente excluir esta certificação?')">
                                        <i class="icon-trash icon-white"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Nenhuma certificação cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" style="display:flex;justify-content: center">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')): ?>
            <a title="Editar Cliente" class="button btn btn-mini btn-info" style="min-width: 140px; top:10px" href="<?= base_url() ?>index.php/clientes/editar/<?= $result->idClientes ?>">
                <span class="button__icon"><i class="bx bx-edit"></i></span> <span class="button__text2"> Editar</span>
            </a>
        <?php endif; ?>
        <a title="Voltar" class="button btn btn-mini btn-warning" style="min-width: 140px; top:10px" href="<?= site_url() ?>/clientes">
          <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
    </div>

<!-- Adicione este script para inicializar os datepickers -->
<script type="text/javascript">
$(document).ready(function(){
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });
});
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // Verifica se existe uma âncora na URL
        var hash = window.location.hash;
        if (hash) {
            // Encontra a aba que contém a âncora
            var tabPane = $(hash).closest('.tab-pane');
            if (tabPane.length) {
                var tabId = tabPane.attr('id');
                // Ativa a aba correta
                $('.nav-tabs a[href="#' + tabId + '"]').tab('show');
                // Rola a página suavemente para a âncora
                $('html, body').animate({
                    scrollTop: $(hash).offset().top - 100 // Ajuste de 100px para melhor visualização
                }, 800);
            }
        }
    });
</script>
</div>
