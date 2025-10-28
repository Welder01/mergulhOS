<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<div class="widget-box">
    <div class="widget-title">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tabDetalhes">Detalhes da Viagem</a></li>
            <li><a data-toggle="tab" href="#tabClientes">Clientes</a></li>
            <li><a data-toggle="tab" href="#tabHospedagem">Hospedagem</a></li>
            <li><a data-toggle="tab" href="#tabInstrutores">Instrutores</a></li>
            <li><a data-toggle="tab" href="#tabCustos">Custos Extras</a></li>
            <li><a data-toggle="tab" href="#tabCursos">Cursos Associados</a></li>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <!-- Aba Detalhes -->
        <div id="tabDetalhes" class="tab-pane active">
            <table class="table table-bordered">
                <tbody>
                    <tr> <td><strong>Nome:</strong></td> <td><?= html_escape($result->nome_viagem) ?></td> </tr>
                    <tr> <td><strong>Descrição:</strong></td> <td><?= html_escape($result->descricao) ?></td> </tr>
                    <tr> <td><strong>Partida:</strong></td> <td><?= $result->data_partida ? date('d/m/Y', strtotime($result->data_partida)) : '' ?></td> </tr>
                    <tr> <td><strong>Retorno:</strong></td> <td><?= $result->data_retorno ? date('d/m/Y', strtotime($result->data_retorno)) : '' ?></td> </tr>
                    <tr> <td><strong>Vagas:</strong></td> <td><?= $result->vagas ?></td> </tr>
                    <tr> <td><strong>Preço/Pessoa:</strong></td> <td>R$ <?= number_format($result->preco_pessoa, 2, ',', '.') ?></td> </tr>
                    <tr> <td><strong>Status:</strong></td> <td><?= html_escape($result->status) ?></td> </tr>
                </tbody>
            </table>
        </div>

        <!-- Aba Clientes / Embarque -->
        <div id="tabClientes" class="tab-pane">
            <h4>Adicionar Cliente</h4>
            <form action="<?= site_url('viagens/adicionar_cliente') ?>" method="post" class="form-horizontal">
                <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                <div class="control-group">
                    <label class="control-label">Cliente</label>
                    <div class="controls">
                        <input type="text" class="span6" id="cliente" placeholder="Pesquisar cliente...">
                        <input type="hidden" name="cliente_id" id="cliente_id">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Bolsa Nº</label>
                    <div class="controls">
                        <input type="text" class="span2" name="numero_bolsa">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Pagamento</label>
                    <div class="controls">
                        <select name="status_pagamento" class="span3">
                            <option value="Pendente">Pendente</option>
                            <option value="Pago">Pago</option>
                            <option value="Parcial">Parcial</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Opções</label>
                    <div class="controls">
                        <label><input type="checkbox" name="precisa_embarque" value="1"> Precisa de Embarque</label>
                        <label><input type="checkbox" name="precisa_hospedagem" value="1"> Precisa de Hospedagem</label>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Locar Equipamentos</label>
                    <div class="controls">
                        <label class="checkbox inline"><input type="checkbox" name="locar_nadadeira" value="1"> Nadadeira</label>
                        <label class="checkbox inline"><input type="checkbox" name="locar_colete" value="1"> Colete</label>
                        <label class="checkbox inline"><input type="checkbox" name="locar_neoprene" value="1"> Neoprene</label>
                        <label class="checkbox inline"><input type="checkbox" name="locar_lastro" value="1"> Lastro</label>
                    </div>
                    <div class="controls" style="margin-top: 5px;">
                        <label class="control-label" style="width: 60px; text-align: left;">Cilindros:</label>
                        <input type="number" name="locar_cilindro" value="0" class="span1" min="0">
                        <label class="control-label" style="width: 80px; text-align: left; margin-left: 10px;">Reguladores:</label>
                        <input type="number" name="locar_regulador" value="0" class="span1" min="0">
                    </div>
                </div>
                <div class="form-actions" style="background-color:transparent;border:none;padding-left:180px;">
                    <button type="submit" class="btn btn-success">Adicionar Cliente</button>
                </div>
            </form>
            <hr>
            <h4>Clientes na Viagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Bolsa Nº</th>
                        <th>Embarque</th>
                        <th>Hospedagem</th>
                        <th>Equipamentos</th>
                        <th>Pagamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)) : ?>
                        <?php foreach ($clientes as $cliente) : ?>
                            <tr>
                                <td><?= html_escape($cliente->nomeCliente) ?></td>
                                <td><?= html_escape($cliente->numero_bolsa) ?></td>
                                <td><?= $cliente->precisa_embarque ? '<span class="badge badge-success">Sim</span>' : '<span class="badge">Não</span>' ?></td>
                                <td><?= $cliente->precisa_hospedagem ? '<span class="badge badge-success">Sim</span>' : '<span class="badge">Não</span>' ?></td>
                                <td>
                                    <?php if ($cliente->locar_nadadeira) echo '<i class="fas fa-water" title="Nadadeira"></i> '; ?>
                                    <?php if ($cliente->locar_cilindro) echo '<i class="fas fa-database" title="Cilindro"></i> '; ?>
                                    <?php if ($cliente->locar_colete) echo '<i class="fas fa-life-ring" title="Colete"></i> '; ?>
                                    <?php if ($cliente->locar_neoprene) echo '<i class="fas fa-user-ninja" title="Neoprene"></i> '; ?>
                                    <?php if ($cliente->locar_regulador) echo '<i class="fas fa-cogs" title="Regulador"></i> '; ?>
                                </td>
                                <td><?= html_escape($cliente->status_pagamento) ?></td>
                                <td><a href="<?= site_url('viagens/remover_cliente_viagem/' . $cliente->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este cliente da viagem?')">Remover</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="7">Nenhum cliente inscrito nesta viagem.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Aba Hospedagem -->
        <div id="tabHospedagem" class="tab-pane">
            <h4>Clientes com Hospedagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Detalhes da Hospedagem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente) : ?>
                        <?php if ($cliente->precisa_hospedagem) : ?>
                            <tr>
                                <td><?= html_escape($cliente->nomeCliente) ?></td>
                                <td>
                                    <form action="<?= site_url('viagens/editar_cliente_viagem/' . $cliente->id) ?>" method="post">
                                        <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                                        <textarea name="detalhes_hospedagem" rows="2" class="span12"><?= html_escape($cliente->detalhes_hospedagem) ?></textarea>
                                        <button type="submit" class="btn btn-primary btn-mini">Salvar</button>
                                    </form>
                                </td>
                                <td><a href="<?= site_url('viagens/remover_cliente_viagem/' . $cliente->id) ?>" class="btn btn-danger btn-mini">Remover</a></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Aba Instrutores -->
        <div id="tabInstrutores" class="tab-pane">
            <h4>Adicionar Instrutor</h4>
            <form action="<?= site_url('viagens/adicionar_instrutor_viagem') ?>" method="post" class="form-horizontal">
                <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                <div class="control-group">
                    <label for="instrutor" class="control-label">Instrutor</label>
                    <div class="controls">
                        <input type="text" class="span6" id="instrutor" placeholder="Pesquisar usuário...">
                        <input type="hidden" name="usuario_id" id="usuario_id">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Bolsa Nº</label>
                    <div class="controls">
                        <input type="text" class="span2" name="numero_bolsa_instrutor">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Locar Equipamentos</label>
                    <div class="controls">
                        <label class="checkbox inline"><input type="checkbox" name="locar_nadadeira_instrutor" value="1"> Nadadeira</label>
                        <label class="checkbox inline"><input type="checkbox" name="locar_colete_instrutor" value="1"> Colete</label>
                        <label class="checkbox inline"><input type="checkbox" name="locar_neoprene_instrutor" value="1"> Neoprene</label>
                        <label class="checkbox inline"><input type="checkbox" name="locar_lastro_instrutor" value="1"> Lastro</label>
                    </div>
                    <div class="controls" style="margin-top: 5px;">
                        <label class="control-label" style="width: 60px; text-align: left;">Cilindros:</label>
                        <input type="number" name="locar_cilindro_instrutor" value="0" class="span1" min="0">
                        <label class="control-label" style="width: 80px; text-align: left; margin-left: 10px;">Reguladores:</label>
                        <input type="number" name="locar_regulador_instrutor" value="0" class="span1" min="0">
                    </div>
                </div>
                <div class="form-actions" style="background-color:transparent;border:none;padding-left:180px;">
                    <button type="submit" class="btn btn-success">Adicionar Instrutor</button>
                </div>
            </form>
            <hr>
            <h4>Instrutores na Viagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Bolsa Nº</th>
                        <th>Equipamentos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($instrutores as $instrutor) : ?>
                        <tr>
                            <td><?= html_escape($instrutor->nome_instrutor) ?></td>
                            <td><?= html_escape($instrutor->numero_bolsa) ?></td>
                            <td>
                                <?php if ($instrutor->locar_nadadeira) echo '<i class="fas fa-water" title="Nadadeira"></i> '; ?>
                                <?php if ($instrutor->locar_cilindro > 0) echo '<i class="fas fa-database" title="Cilindro"></i> ' . $instrutor->locar_cilindro . ' '; ?>
                                <?php if ($instrutor->locar_colete) echo '<i class="fas fa-life-ring" title="Colete"></i> '; ?>
                                <?php if ($instrutor->locar_neoprene) echo '<i class="fas fa-user-ninja" title="Neoprene"></i> '; ?>
                                <?php if ($instrutor->locar_regulador > 0) echo '<i class="fas fa-cogs" title="Regulador"></i> ' . $instrutor->locar_regulador . ' '; ?>
                                <?php if ($instrutor->locar_lastro) echo '<i class="fas fa-weight-hanging" title="Lastro"></i> '; ?>
                            </td>
                            <td><a href="<?= site_url('viagens/remover_instrutor_viagem/' . $instrutor->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este instrutor da viagem?')">Remover</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Aba Custos -->
        <div id="tabCustos" class="tab-pane">
            <h4>Adicionar Custo</h4>
            <form action="<?= site_url('viagens/adicionar_custo') ?>" method="post" class="form-horizontal">
                <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                <div class="control-group">
                    <label for="descricao_custo" class="control-label">Descrição</label>
                    <div class="controls">
                        <input type="text" id="descricao_custo" name="descricao" class="span6">
                    </div>
                </div>
                <div class="control-group">
                    <label for="valor_custo" class="control-label">Valor</label>
                    <div class="controls">
                        <input type="text" id="valor_custo" name="valor" class="span2 money">
                    </div>
                </div>
                <div class="form-actions" style="background-color:transparent;border:none;padding-left:180px;">
                    <button type="submit" class="btn btn-success">Adicionar Custo</button>
                </div>
            </form>
            <hr>
            <h4>Custos da Viagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($custos as $custo) : ?>
                        <tr>
                            <td><?= html_escape($custo->descricao) ?></td>
                            <td>R$ <?= number_format($custo->valor, 2, ',', '.') ?></td>
                            <td><a href="<?= site_url('viagens/remover_custo/' . $custo->id) ?>" class="btn btn-danger btn-mini">Remover</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Aba Cursos Associados -->
        <div id="tabCursos" class="tab-pane">
            <h4>Cursos Associados a esta Viagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome do Curso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cursos_associados)) : ?>
                        <?php foreach ($cursos_associados as $curso) : ?>
                            <tr>
                                <td><?= html_escape($curso->nome_curso) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td>Nenhum curso associado a esta viagem.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer" style="display:flex;justify-content: center">
    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eViagem')) : ?>
        <a title="Editar Viagem" class="button btn btn-mini btn-info" href="<?= base_url() ?>index.php/viagens/editar/<?= $result->id ?>">
            <span class="button__icon"><i class="bx bx-edit"></i></span> <span class="button__text2"> Editar</span>
        </a>
        <a title="Imprimir Ficha de Viagem" class="button btn btn-mini btn-inverse" href="<?= base_url() ?>index.php/viagens/imprimir/<?= $result->id ?>">
            <span class="button__icon"><i class="bx bx-printer"></i></span> <span class="button__text2"> Ficha Viagem</span>
        </a>
        <a title="Imprimir Ficha de Operação" class="button btn btn-mini btn-primary" href="<?= base_url() ?>index.php/viagens/imprimirOperacao/<?= $result->id ?>">
            <span class="button__icon"><i class="fas fa-ship"></i></span> <span class="button__text2"> Ficha Operação</span>
        </a>
    <?php endif; ?>
    <a title="Voltar" class="button btn btn-mini btn-warning" href="<?= site_url() ?>/viagens">
        <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
</div>

<script>
$(document).ready(function() {
    $("#cliente").autocomplete({
        source: "<?= site_url('viagens/autoCompleteCliente'); ?>",
        minLength: 2,
        select: function(event, ui) {
            $("#cliente_id").val(ui.item.id);
        }
    });
    $("#instrutor").autocomplete({
        source: "<?= site_url('viagens/autoCompleteUsuario'); ?>",
        minLength: 2,
        select: function(event, ui) {
            $("#usuario_id").val(ui.item.id);
        }
    });
    $('.money').mask('#.##0,00', {reverse: true});
});
</script>