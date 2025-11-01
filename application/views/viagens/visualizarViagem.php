<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<?php $this->load->view('clientes/editarCliente_style'); ?>
<style>
    /* Toggles menores para uma UI mais limpa */
    .small-toggle.switch {
        width: 40px;
        height: 20px;
    }
    .small-toggle .slider:before {
        height: 12px;
        width: 12px;
        left: 4px;
        bottom: 4px;
    }
    .small-toggle input:checked + .slider:before {
        transform: translateX(20px);
    }
    /* Alinhamento dos toggles com seus labels */
    .toggle-container {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
</style>

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
                <div class="row-fluid">
                    <input type="hidden" name="active_tab" value="#tabClientes">
                    <div class="span6">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                        <div class="control-group">
                            <label class="control-label">Cliente<span class="required">*</span></label>
                            <div class="controls">
                                <input type="text" class="span12" id="cliente" name="cliente" placeholder="Pesquisar cliente..." required>
                                <input type="hidden" name="cliente_id" id="cliente_id">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Bolsa Nº</label>
                            <div class="controls">
                                <input type="text" class="span4" name="numero_bolsa">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Pagamento</label>
                            <div class="controls">
                                <select name="status_pagamento" class="span6">
                                    <option value="Pendente">Pendente</option>
                                    <option value="Pago">Pago</option>
                                    <option value="Parcial">Parcial</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Propósito</label>
                            <div class="controls">
                                <select name="proposito" class="span6">
                                    <option value=""></option>
                                    <option value="Checkout">Checkout</option>
                                    <option value="Acompanhante">Acompanhante</option>
                                    <option value="Turismo">Turismo</option>
                                    <option value="Batismo">Batismo</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Opções</label>
                            <div class="controls">
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="precisa_embarque" value="1"><span class="slider"></span></label>
                                    <span>Embarque</span>
                                </div>
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="precisa_hospedagem" value="1"><span class="slider"></span></label>
                                    <span>Hospedagem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label">Locar Equipamentos</label>
                            <div class="controls" style="display: flex; flex-wrap: wrap; gap: 15px;">
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_nadadeira" value="1"><span class="slider"></span></label>
                                    <span>Nadadeira</span>
                                </div>
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_colete" value="1"><span class="slider"></span></label>
                                    <span>Colete</span>
                                </div>
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_neoprene" value="1"><span class="slider"></span></label>
                                    <span>Neoprene</span>
                                </div>
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_lastro" value="1"><span class="slider"></span></label>
                                    <span>Lastro</span>
                                </div>
                            </div>
                            <div class="controls" style="margin-top: 15px;">
                                <label class="control-label" style="width: 60px; text-align: left;">Cilindros:</label>
                                <input type="number" name="locar_cilindro" value="0" class="span2" min="0">
                                <label class="control-label" style="width: 80px; text-align: left; margin-left: 10px;">Reguladores:</label>
                                <input type="number" name="locar_regulador" value="0" class="span2" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions" style="background-color:transparent;border:none;text-align:center;margin-left:0;">
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
                        <th>Propósito</th>
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
                                <td><?= html_escape($cliente->proposito) ?></td>
                                <td>
                                    <?php if ($cliente->locar_nadadeira) echo '<i class="fas fa-water" title="Nadadeira"></i> '; ?> <?php if ($cliente->locar_cilindro > 0) echo '<i class="fas fa-database" title="Cilindro"></i> ' . $cliente->locar_cilindro . ' '; ?> <?php if ($cliente->locar_colete) echo '<i class="fas fa-life-ring" title="Colete"></i> '; ?> <?php if ($cliente->locar_neoprene) echo '<i class="fas fa-user-ninja" title="Neoprene"></i> '; ?> <?php if ($cliente->locar_regulador > 0) echo '<i class="fas fa-cogs" title="Regulador"></i> ' . $cliente->locar_regulador . ' '; ?> <?php if ($cliente->locar_lastro) echo '<i class="fas fa-weight-hanging" title="Lastro"></i> '; ?>
                                </td>
                                <td><?= html_escape($cliente->status_pagamento) ?></td>
                                <td>
                                    <a href="#modalEditarCliente" data-toggle="modal" class="btn btn-info btn-mini" title="Editar Cliente" data-cliente-id="<?= $cliente->id ?>" data-cliente-nome="<?= html_escape($cliente->nomeCliente) ?>" data-cliente-data='<?= json_encode($cliente) ?>'>Editar</a>
                                    <a href="<?= site_url('viagens/remover_cliente_viagem/' . $cliente->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este cliente da viagem?')">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="8">Nenhum cliente inscrito nesta viagem.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Aba Hospedagem -->
        <div id="tabHospedagem" class="tab-pane">
            <h4>Clientes com Hospedagem</h4>
            <table class="table table-bordered" style="margin-bottom: 30px;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Cliente</th>
                        <th colspan="2">Detalhes da Hospedagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $clientes_hospedagem = array_filter($clientes, function ($c) {
                            return $c->precisa_hospedagem;
                        });
                    ?>
                    <?php if (!empty($clientes_hospedagem)) : ?>
                        <?php foreach ($clientes_hospedagem as $cliente) : ?>
                            <form action="<?= site_url('viagens/editar_cliente_viagem/' . $cliente->id) ?>" method="post">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                                <tr>
                                    <td rowspan="4" style="vertical-align: top;"><?= html_escape($cliente->nomeCliente) ?></td>
                                    <td style="width: 20%;"><strong>Nº do Quarto:</strong></td>
                                    <td><input type="text" name="hospedagem_quarto_numero" value="<?= html_escape($cliente->hospedagem_quarto_numero ?? '') ?>" class="span6"></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo de Quarto:</strong></td>
                                    <td>
                                        <select name="hospedagem_tipo_quarto" class="span6">
                                            <option value=""></option>
                                            <option value="Solteiro" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Solteiro' ? 'selected' : '' ?>>Solteiro</option>
                                            <option value="Casal" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Casal' ? 'selected' : '' ?>>Casal</option>
                                            <option value="Família" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Família' ? 'selected' : '' ?>>Família</option>
                                            <option value="Compartilhado" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Compartilhado' ? 'selected' : '' ?>>Compartilhado</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Nº de Camas:</strong></td>
                                    <td><input type="number" name="hospedagem_numero_camas" value="<?= $cliente->hospedagem_numero_camas ?? '' ?>" class="span2"></td>
                                </tr>
                                <tr>
                                    <td><strong>Observações:</strong></td>
                                    <td>
                                        <textarea name="detalhes_hospedagem" rows="2" class="span12"><?= html_escape($cliente->detalhes_hospedagem) ?></textarea>
                                        <button type="submit" class="btn btn-primary btn-mini">Salvar</button>
                                    </td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="3">Nenhum cliente necessita de hospedagem para esta viagem.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h4>Instrutores com Hospedagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 20%;">Instrutor</th>
                        <th colspan="2">Detalhes da Hospedagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $instrutores_hospedagem = array_filter($instrutores, function ($i) {
                            return $i->precisa_hospedagem;
                        });
                    ?>
                    <?php if (!empty($instrutores_hospedagem)) : ?>
                        <?php foreach ($instrutores_hospedagem as $instrutor) : ?>
                            <form action="<?= site_url('viagens/editar_instrutor_viagem/' . $instrutor->id) ?>" method="post">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="viagem_id" value="<?= $result->id ?>">
                                <tr>
                                    <td rowspan="4" style="vertical-align: top;"><?= html_escape($instrutor->nome_instrutor) ?></td>
                                    <td style="width: 20%;"><strong>Nº do Quarto:</strong></td>
                                    <td><input type="text" name="hospedagem_quarto_numero" value="<?= html_escape($instrutor->hospedagem_quarto_numero ?? '') ?>" class="span6"></td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo de Quarto:</strong></td>
                                    <td>
                                        <select name="hospedagem_tipo_quarto" class="span6">
                                            <option value=""></option>
                                            <option value="Solteiro" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Solteiro' ? 'selected' : '' ?>>Solteiro</option>
                                            <option value="Casal" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Casal' ? 'selected' : '' ?>>Casal</option>
                                            <option value="Família" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Família' ? 'selected' : '' ?>>Família</option>
                                            <option value="Compartilhado" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Compartilhado' ? 'selected' : '' ?>>Compartilhado</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Nº de Camas:</strong></td>
                                    <td><input type="number" name="hospedagem_numero_camas" value="<?= $instrutor->hospedagem_numero_camas ?? '' ?>" class="span2"></td>
                                </tr>
                                <tr>
                                    <td><strong>Observações:</strong></td>
                                    <td>
                                        <textarea name="detalhes_hospedagem" rows="2" class="span12"><?= html_escape($instrutor->detalhes_hospedagem) ?></textarea>
                                        <button type="submit" class="btn btn-primary btn-mini">Salvar</button>
                                    </td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="3">Nenhum instrutor necessita de hospedagem para esta viagem.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Aba Instrutores -->
        <div id="tabInstrutores" class="tab-pane">
            <h4>Adicionar Instrutor</h4>
            <form action="<?= site_url('viagens/adicionar_instrutor_viagem') ?>" method="post" class="form-horizontal">
                <input type="hidden" name="active_tab" value="#tabInstrutores">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
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
                    <label class="control-label">Opções</label>
                    <div class="controls">
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="precisa_embarque_instrutor" value="1"><span class="slider"></span></label>
                            <span>Embarque</span>
                        </div>
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="precisa_hospedagem_instrutor" value="1"><span class="slider"></span></label>
                            <span>Hospedagem</span>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Locar Equipamentos</label>
                    <div class="controls">
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_nadadeira_instrutor" value="1"><span class="slider"></span></label>
                            <span>Nadadeira</span>
                        </div>
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_colete_instrutor" value="1"><span class="slider"></span></label>
                            <span>Colete</span>
                        </div>
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_neoprene_instrutor" value="1"><span class="slider"></span></label>
                            <span>Neoprene</span>
                        </div>
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_lastro_instrutor" value="1"><span class="slider"></span></label>
                            <span>Lastro</span>
                        </div>
                    </div>
                    <div class="controls" style="margin-top: 15px;">
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
            <div class="table-responsive" style="overflow-x: auto;">
                <h4>Instrutores na Viagem</h4>
                <table class="table table-bordered" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Bolsa Nº</th>
                            <th>Embarque</th>
                            <th>Hospedagem</th>
                            <th>Equipamentos</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($instrutores as $instrutor) : ?>
                            <tr>
                                <td><?= html_escape($instrutor->nome_instrutor) ?></td>
                                <td><?= html_escape($instrutor->cpf_instrutor) ?></td>
                                <td><?= html_escape($instrutor->telefone_instrutor) ?></td>
                                <td><?= html_escape($instrutor->numero_bolsa) ?></td>
                                <td><?= $instrutor->precisa_embarque ? '<span class="badge badge-success">Sim</span>' : '<span class="badge">Não</span>' ?></td>
                                <td><?= $instrutor->precisa_hospedagem ? '<span class="badge badge-success">Sim</span>' : '<span class="badge">Não</span>' ?></td>
                                <td>
                                    <?php if ($instrutor->locar_nadadeira) echo '<i class="fas fa-water" title="Nadadeira"></i> '; ?>
                                    <?php if ($instrutor->locar_cilindro > 0) echo '<i class="fas fa-database" title="Cilindro"></i> ' . $instrutor->locar_cilindro . ' '; ?>
                                    <?php if ($instrutor->locar_colete) echo '<i class="fas fa-life-ring" title="Colete"></i> '; ?>
                                    <?php if ($instrutor->locar_neoprene) echo '<i class="fas fa-user-ninja" title="Neoprene"></i> '; ?>
                                    <?php if ($instrutor->locar_regulador > 0) echo '<i class="fas fa-cogs" title="Regulador"></i> ' . $instrutor->locar_regulador . ' '; ?>
                                    <?php if ($instrutor->locar_lastro) echo '<i class="fas fa-weight-hanging" title="Lastro"></i> '; ?>
                                </td>
                                <td>
                                    <a href="#modalEditarInstrutor" data-toggle="modal" class="btn btn-info btn-mini" title="Editar Instrutor" data-instrutor-id="<?= $instrutor->id ?>" data-instrutor-nome="<?= html_escape($instrutor->nome_instrutor) ?>" data-instrutor-data='<?= json_encode($instrutor) ?>'>Editar</a>
                                    <a href="<?= site_url('viagens/remover_instrutor_viagem/' . $instrutor->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este instrutor da viagem?')">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Aba Custos -->
        <div id="tabCustos" class="tab-pane">
            <h4>Adicionar Custo</h4>
            <form action="<?= site_url('viagens/adicionar_custo') ?>" method="post" class="form-horizontal">
                <input type="hidden" name="active_tab" value="#tabCustos">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
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

<!-- Modal Editar Cliente -->
<style>
    /* Specific styles for the "Editar Cliente" modal */
    #modalEditarCliente .form-horizontal .control-group {
        margin-bottom: 8px; /* Reduce vertical spacing */
    }
    #modalEditarCliente .form-horizontal .control-label {
        width: 90px; /* Fixed width for labels */
        text-align: right;
        padding-top: 5px; /* Align with input/toggle */
    }
    #modalEditarCliente .form-horizontal .controls {
        margin-left: 100px; /* Adjust controls margin based on label width */
    }
    /* Smaller toggle switch */
    #modalEditarCliente .controls .switch {
        width: 40px;
        height: 20px;
    }
    #modalEditarCliente .controls .slider {
        border-radius: 20px; /* Adjust border-radius for smaller size */
    }
    #modalEditarCliente .controls .slider:before {
        height: 12px;
        width: 12px;
        left: 4px;
        bottom: 4px;
        border-radius: 50%; /* Ensure it remains circular */
    }
    #modalEditarCliente input:checked + .slider:before {
        transform: translateX(20px); /* Adjust based on new width */
    }
    /* Alignment for toggle groups */
    #modalEditarCliente .controls.toggle-group {
        display: flex;
        flex-direction: column; /* Stack toggles vertically */
        align-items: flex-start; /* Align toggles to the left */
    }
    #modalEditarCliente .controls.toggle-group > div {
        display: flex;
        align-items: center;
        margin-bottom: 5px; /* Space between toggle items */
    }
    /* Alignment for number inputs */
    #modalEditarCliente .controls input[type="number"] {
        width: 60px; /* Fixed width for number inputs */
    }

    /* Responsive adjustments for mobile */
    @media (max-width: 767px) { /* Standard Bootstrap breakpoint for small devices */
        #modalEditarCliente .form-horizontal .control-group {
            margin-bottom: 10px; /* Add some vertical spacing between stacked groups */
            display: block; /* Ensure control group stacks vertically */
        }
        #modalEditarCliente .form-horizontal .control-label {
            width: auto; /* Allow label to take full width */
            text-align: left; /* Align text to left on mobile */
            padding-top: 0; /* Reset padding if it causes issues */
            float: none; /* Ensure it doesn't float */
            margin-bottom: 5px; /* Space between label and input */
        }
        #modalEditarCliente .form-horizontal .controls {
            margin-left: 0; /* Remove fixed margin-left */
            display: block; /* Ensure controls stack */
        }
        #modalEditarCliente .span6 {
            width: 100%; /* Make columns full width on mobile */
            margin-left: 0; /* Remove left margin for stacked columns */
        }
        /* Adjust input widths for mobile if necessary */
        #modalEditarCliente .controls input[type="text"],
        #modalEditarCliente .controls select,
        #modalEditarCliente .controls input[type="number"] {
            width: 100%; /* Make inputs full width */
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
        }
        /* Adjust toggle group layout for mobile */
        #modalEditarCliente .controls.toggle-group {
            flex-direction: column; /* Stack toggles vertically on mobile */
            align-items: flex-start; /* Align toggles to the left */
        }
        #modalEditarCliente .controls.toggle-group > div {
            margin-bottom: 8px; /* Space between stacked toggle items */
            margin-right: 0; /* Remove horizontal margin */
        }
        #modalEditarCliente .controls .switch {
            margin-right: 10px; /* Keep some space between toggle and its label */
        }
    }
</style>
<div id="modalEditarCliente" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formEditarCliente" action="" method="post" class="form-horizontal">
        <div class="modal-header">
            <input type="hidden" name="active_tab" value="#tabClientes">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Editar Cliente: <span id="nomeClienteModal"></span></h5>
        </div>
        <div class="modal-body">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="viagem_id" value="<?php echo $result->id; ?>">
            <div class="row-fluid" style="display: flex; flex-wrap: wrap;">
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label">Bolsa Nº</label>
                        <div class="controls">
                            <input type="text" class="span6" name="numero_bolsa" id="edit_numero_bolsa">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Pagamento</label>
                        <div class="controls">
                            <select name="status_pagamento" id="edit_status_pagamento" class="span8">
                                <option value="Pendente">Pendente</option>
                                <option value="Pago">Pago</option>
                                <option value="Parcial">Parcial</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Propósito</label>
                        <div class="controls">
                            <select name="proposito" id="edit_proposito" class="span8">
                                <option value=""></option>
                                <option value="Checkout">Checkout</option>
                                <option value="Acompanhante">Acompanhante</option>
                                <option value="Turismo">Turismo</option>
                                <option value="Batismo">Batismo</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Opções</label> <!-- Label for the group -->
                        <div class="controls toggle-group"> <!-- Applied toggle-group class -->
                            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                <label class="switch" style="margin-right: 10px;">
                                    <input type="checkbox" name="precisa_embarque" id="edit_precisa_embarque" value="1">
                                    <span class="slider"></span>
                                </label>
                                <label for="edit_precisa_embarque" style="margin: 0;">Embarque</label>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <label class="switch" style="margin-right: 10px;">
                                    <input type="checkbox" name="precisa_hospedagem" id="edit_precisa_hospedagem" value="1">
                                    <span class="slider"></span>
                                </label>
                                <label for="edit_precisa_hospedagem" style="margin: 0;">Hospedagem</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label">Locar Equipamentos</label> <!-- Label for the group -->
                        <div class="controls toggle-group"> <!-- Applied toggle-group class -->
                            <div>
                                <label class="switch" style="margin-right: 10px;"><input type="checkbox" name="locar_nadadeira" id="edit_locar_nadadeira" value="1"><span class="slider"></span></label>
                                <label for="edit_locar_nadadeira" style="margin: 0;">Nadadeira</label>
                            </div>
                            <div>
                                <label class="switch" style="margin-right: 10px;"><input type="checkbox" name="locar_colete" id="edit_locar_colete" value="1"><span class="slider"></span></label>
                                <label for="edit_locar_colete" style="margin: 0;">Colete</label>
                            </div>
                            <div>
                                <label class="switch" style="margin-right: 10px;"><input type="checkbox" name="locar_neoprene" id="edit_locar_neoprene" value="1"><span class="slider"></span></label>
                                <label for="edit_locar_neoprene" style="margin: 0;">Neoprene</label>
                            </div>
                            <div>
                                <label class="switch" style="margin-right: 10px;"><input type="checkbox" name="locar_lastro" id="edit_locar_lastro" value="1"><span class="slider"></span></label>
                                <label for="edit_locar_lastro" style="margin: 0;">Lastro</label>
                            </div>
                        </div>
                    </div>
                    <!-- Separate control groups for Cilindros and Reguladores for better alignment -->
                    <div class="control-group">
                        <label class="control-label">Cilindros:</label>
                        <div class="controls">
                            <input type="number" name="locar_cilindro" id="edit_locar_cilindro" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Reguladores:</label>
                        <div class="controls">
                            <input type="number" name="locar_regulador" id="edit_locar_regulador" value="0" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>

<!-- Estilos para o Modal Editar Instrutor -->
<style>
    #modalEditarInstrutor .form-horizontal .control-label {
        width: 120px;
    }
    #modalEditarInstrutor .form-horizontal .controls {
        margin-left: 130px;
    }
    #modalEditarInstrutor .controls .switch {
        width: 40px;
        height: 20px;
        margin-right: 10px;
    }
    #modalEditarInstrutor .controls .slider:before {
        height: 12px;
        width: 12px;
        left: 4px;
        bottom: 4px;
    }
    #modalEditarInstrutor input:checked + .slider:before {
        transform: translateX(20px);
    }
    #modalEditarInstrutor .toggle-container {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    /* Ajustes responsivos */
    @media (max-width: 767px) {
        #modalEditarInstrutor .form-horizontal .control-label {
            width: auto;
            text-align: left;
        }
        #modalEditarInstrutor .form-horizontal .controls {
            margin-left: 0;
        }
        #modalEditarInstrutor .span6 {
            width: 100%; margin-left: 0;
        }
    }
</style>
<!-- Modal Editar Instrutor -->
<div id="modalEditarInstrutor" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formEditarInstrutor" action="" method="post" class="form-horizontal">
        <div class="modal-header">
            <input type="hidden" name="active_tab" value="#tabInstrutores">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Editar Instrutor: <span id="nomeInstrutorModal"></span></h5>
        </div>
        <div class="modal-body">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="viagem_id" value="<?php echo $result->id; ?>">
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label">Opções</label>
                        <div class="controls">
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="precisa_embarque" id="edit_instrutor_precisa_embarque" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_precisa_embarque" style="margin: 0;">Embarque</label>
                            </div>
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="precisa_hospedagem" id="edit_instrutor_precisa_hospedagem" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_precisa_hospedagem" style="margin: 0;">Hospedagem</label>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Locar Equipamentos</label>
                        <div class="controls">
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="locar_nadadeira" id="edit_instrutor_locar_nadadeira" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_locar_nadadeira" style="margin: 0;">Nadadeira</label>
                            </div>
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="locar_colete" id="edit_instrutor_locar_colete" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_locar_colete" style="margin: 0;">Colete</label>
                            </div>
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="locar_neoprene" id="edit_instrutor_locar_neoprene" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_locar_neoprene" style="margin: 0;">Neoprene</label>
                            </div>
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="locar_lastro" id="edit_instrutor_locar_lastro" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_locar_lastro" style="margin: 0;">Lastro</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label">Bolsa Nº</label>
                        <div class="controls">
                            <input type="text" name="numero_bolsa" id="edit_instrutor_numero_bolsa" class="span6">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Cilindros</label>
                        <div class="controls">
                            <input type="number" name="locar_cilindro" id="edit_instrutor_locar_cilindro" value="0" min="0" class="span6">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Reguladores</label>
                        <div class="controls">
                            <input type="number" name="locar_regulador" id="edit_instrutor_locar_regulador" value="0" min="0" class="span6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
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
            $("#instrutor").val(ui.item.nome); // Preenche o campo com o nome
            $("#usuario_id").val(ui.item.id); // Guarda o ID no campo oculto
        }
    });
    $('.money').mask('#.##0,00', {reverse: true});

    $(document).on('click', 'a[href="#modalEditarCliente"]', function() {
        var clienteId = $(this).data('cliente-id');
        var clienteNome = $(this).data('cliente-nome');
        var clienteData = $(this).data('cliente-data');

        $('#formEditarCliente').attr('action', '<?= site_url('viagens/editar_cliente_viagem/') ?>' + clienteId);
        $('#nomeClienteModal').text(clienteNome);

        $('#edit_numero_bolsa').val(clienteData.numero_bolsa);
        $('#edit_status_pagamento').val(clienteData.status_pagamento);
        $('#edit_proposito').val(clienteData.proposito);
        $('#edit_precisa_embarque').prop('checked', clienteData.precisa_embarque == 1);
        $('#edit_precisa_hospedagem').prop('checked', clienteData.precisa_hospedagem == 1);
        $('#edit_locar_nadadeira').prop('checked', clienteData.locar_nadadeira == 1);
        $('#edit_locar_colete').prop('checked', clienteData.locar_colete == 1); // Assuming locar_colete is boolean
        $('#edit_locar_neoprene').prop('checked', clienteData.locar_neoprene == 1); // Assuming locar_neoprene is boolean
        $('#edit_locar_lastro').prop('checked', clienteData.locar_lastro == 1); // Assuming locar_lastro is boolean
        $('#edit_locar_cilindro').val(clienteData.locar_cilindro);
        $('#edit_locar_regulador').val(clienteData.locar_regulador);
    });

    $(document).on('click', 'a[href="#modalEditarInstrutor"]', function() {
        var instrutorId = $(this).data('instrutor-id');
        var instrutorNome = $(this).data('instrutor-nome');
        var instrutorData = $(this).data('instrutor-data');

        $('#formEditarInstrutor').attr('action', '<?= site_url('viagens/editar_instrutor_viagem/') ?>' + instrutorId);
        $('#nomeInstrutorModal').text(instrutorNome);

        $('#edit_instrutor_numero_bolsa').val(instrutorData.numero_bolsa);
        $('#edit_instrutor_precisa_embarque').prop('checked', instrutorData.precisa_embarque == 1);
        $('#edit_instrutor_precisa_hospedagem').prop('checked', instrutorData.precisa_hospedagem == 1);
        $('#edit_instrutor_locar_nadadeira').prop('checked', instrutorData.locar_nadadeira == 1);
        $('#edit_instrutor_locar_colete').prop('checked', instrutorData.locar_colete == 1);
        $('#edit_instrutor_locar_neoprene').prop('checked', instrutorData.locar_neoprene == 1);
        $('#edit_instrutor_locar_lastro').prop('checked', instrutorData.locar_lastro == 1);
        $('#edit_instrutor_locar_cilindro').val(instrutorData.locar_cilindro);
        $('#edit_instrutor_locar_regulador').val(instrutorData.locar_regulador);
    });


    // Lógica para manter a aba ativa após salvar
    var activeTab = "<?= $this->input->get('tab') ?>";
    if (activeTab) {
        // Remove a classe 'active' de todas as abas e painéis
        $('.nav-tabs li, .tab-content .tab-pane').removeClass('active');
        // Adiciona a classe 'active' à aba e ao painel corretos
        $('a[href="#' + activeTab + '"]').parent().addClass('active');
        $('#' + activeTab).addClass('active');
    }
});
</script>