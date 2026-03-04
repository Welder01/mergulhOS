<link rel="stylesheet" href="<?= base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script>
    // Polyfill robusto (ES5) para interceptar e corrigir chamadas antigas do SweetAlert (Swal)
    (function() {
        function patch(name) {
            var val = window[name];
            if (val && typeof val === 'function' && val.fire && !val.isPolyfilled) {
                var wrapper = function() { return val.fire.apply(val, arguments); };
                for (var key in val) { wrapper[key] = val[key]; }
                wrapper.prototype = val.prototype;
                wrapper.isPolyfilled = true;
                try { window[name] = wrapper; } catch(e) {}
                val = wrapper;
            }
            var _val = val;
            try {
                Object.defineProperty(window, name, {
                    get: function() { return _val; },
                    set: function(v) {
                        if (v && typeof v === 'function' && v.fire && !v.isPolyfilled) {
                            var wrapper = function() { return v.fire.apply(v, arguments); };
                            for (var key in v) { wrapper[key] = v[key]; }
                            wrapper.prototype = v.prototype;
                            wrapper.isPolyfilled = true;
                            _val = wrapper;
                        } else { _val = v; }
                    },
                    configurable: true, enumerable: true
                });
            } catch(e) {}
        }
        patch('Swal');
        patch('swal');
        patch('sweetAlert');
    })();
</script>
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
    /* Garante que o SweetAlert2 apareça sobre o modal do Bootstrap */
    .swal2-container {
        z-index: 99999 !important; /* Valor extremamente alto para garantir a sobreposição */
        position: fixed; /* Garante que o posicionamento seja relativo à janela de visualização */

    }
    /* Ajustes para os campos de quantidade de equipamentos nos modais */
    .equipment-quantity-group .control-label {
        width: 100px; /* Largura ajustada para os rótulos */
        text-align: right;
    }
    .equipment-quantity-group .controls {
        margin-left: 120px; /* Margem ajustada para os campos de entrada */
    }
    .equipment-quantity-group .controls input[type="number"] {
        width: 60px !important; /* Largura fixa para os campos de quantidade */
        box-sizing: border-box;
        text-align: center;
    }
    .ui-autocomplete {
        z-index: 1060 !important;
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
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
                    <tr> <td><strong>Vagas Disponíveis:</strong></td> <td><?= $result->vagas ?> de <?= $result->vagas_total ?> vagas totais.</td> </tr>
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
                                <div class="input-append" style="display: flex;">
                                    <input type="text" class="span11" id="cliente" name="cliente" placeholder="Pesquisar cliente..." required style="flex-grow: 1;">
                                    <button type="button" id="syncClienteBtn" class="btn btn-info" disabled title="Sincronizar perfil do cliente para locação de equipamentos" style="border-radius: 0 5px 5px 0;">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="cliente_id" id="cliente_id" value="">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Bolsa Nº</label>
                            <div class="controls">
                                <input type="text" class="span4" name="numero_bolsa" id="numero_bolsa" placeholder="Pesquise a bolsa...">
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
                                    <?php if (isset($listaPropositos)) { foreach ($listaPropositos as $proposito) { ?>
                                        <option value="<?= $proposito ?>"><?= $proposito ?></option>
                                    <?php } } ?>
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
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_lanterna" value="1"><span class="slider"></span></label>
                                    <span>Lanterna</span>
                                </div>
                                <div class="toggle-container">
                                    <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_computador" value="1"><span class="slider"></span></label>
                                    <span>Computador</span>
                                </div>
                            </div>
                            <div class="controls" style="margin-top: 15px; display: flex; gap: 15px;">
                                <div style="display: flex; flex-direction: column;">
                                    <label>Cilindros:</label>
                                    <input type="number" name="locar_cilindro" value="0" class="span12" min="0">
                                </div>
                                <div style="display: flex; flex-direction: column;">
                                    <label>Reguladores:</label>
                                    <input type="number" name="locar_regulador" value="0" class="span12" min="0">
                                </div>
                                <div style="display: flex; flex-direction: column;">
                                    <label>Lanternas:</label>
                                    <input type="number" name="locar_lanterna" value="0" class="span12" min="0">
                                </div>
                                <div style="display: flex; flex-direction: column;">
                                    <label>Computadores:</label>
                                    <input type="number" name="locar_computador" value="0" class="span12" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions" style="background-color:transparent;border:none;text-align:center;margin-left:0;">
                    <button type="submit" class="btn btn-success" id="btnAddCliente">Adicionar Cliente</button>
                </div>
            </form>
            <hr>
            <h4>Clientes na Viagem</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>Telefone</th>
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
                                <td><?= html_escape($cliente->cpf) ?></td>
                                <td><?= html_escape($cliente->telefone) ?></td>
                                <td><?= html_escape($cliente->numero_bolsa) ?></td>
                                <td><?= $cliente->precisa_embarque ? '<span class="badge badge-success">Sim</span>' : '<span class="badge">Não</span>' ?></td>
                                <td><?= $cliente->precisa_hospedagem ? '<span class="badge badge-success">Sim</span>' : '<span class="badge">Não</span>' ?></td>
                                <td><?= html_escape($cliente->proposito) ?></td>
                                <td>
                                    <?php if ($cliente->locar_nadadeira) echo '<i class="fas fa-water" title="Nadadeira"></i> '; ?> <?php if ($cliente->locar_cilindro > 0) echo '<i class="fas fa-database" title="Cilindro"></i> ' . $cliente->locar_cilindro . ' '; ?> <?php if ($cliente->locar_colete) echo '<i class="fas fa-life-ring" title="Colete"></i> '; ?> <?php if ($cliente->locar_neoprene) echo '<i class="fas fa-user-ninja" title="Neoprene"></i> '; ?> <?php if ($cliente->locar_regulador > 0) echo '<i class="fas fa-cogs" title="Regulador"></i> ' . $cliente->locar_regulador . ' '; ?> <?php if ($cliente->locar_lastro) echo '<i class="fas fa-weight-hanging" title="Lastro"></i> '; ?>
                                    <?php if ($cliente->locar_lanterna) echo '<i class="fas fa-lightbulb" title="Lanterna"></i> '; ?> <?php if ($cliente->locar_computador) echo '<i class="fas fa-desktop" title="Computador"></i> '; ?>
                                </td>
                                <td><?= html_escape($cliente->status_pagamento) ?></td>
                                <td>
                                    <a href="#modalEditarCliente" data-toggle="modal" class="btn btn-info btn-mini" title="Editar Cliente" data-cliente-id="<?= $cliente->id ?>" data-cliente-nome="<?= html_escape($cliente->nomeCliente) ?>" data-cliente-data='<?= json_encode($cliente) ?>'>Editar</a>
                                    <a href="<?= site_url('viagens/remover_cliente_viagem/' . $cliente->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este cliente da viagem?')">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="10">Nenhum cliente inscrito nesta viagem.</td></tr>
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
                        <th>Cliente</th>
                        <th>Nº do Quarto</th>
                        <th>Tipo de Quarto</th>
                        <th>Nº de Camas</th>
                        <th>Observações</th>
                        <th>Ações</th>
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
                                <input type="hidden" name="active_tab" value="#tabHospedagem">
                                <?php
                                    $dadosPreenchidos = !empty($cliente->hospedagem_quarto_numero);
                                    $rowClass = !$dadosPreenchidos ? 'warning-row' : '';
                                    $buttonClass = $dadosPreenchidos ? 'btn-info' : 'btn-primary';
                                    $buttonText = $dadosPreenchidos ? 'Editar' : 'Salvar';
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td><?= html_escape($cliente->nomeCliente) ?></td>
                                    <td><input type="text" name="hospedagem_quarto_numero" value="<?= html_escape($cliente->hospedagem_quarto_numero ?? '') ?>" class="span12"></td>
                                    <td>
                                        <select name="hospedagem_tipo_quarto" class="span12">
                                            <option value=""></option>
                                            <option value="Solteiro" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Solteiro' ? 'selected' : '' ?>>Solteiro</option>
                                            <option value="Casal" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Casal' ? 'selected' : '' ?>>Casal</option>
                                            <option value="Família" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Família' ? 'selected' : '' ?>>Família</option>
                                            <option value="Compartilhado" <?= ($cliente->hospedagem_tipo_quarto ?? '') == 'Compartilhado' ? 'selected' : '' ?>>Compartilhado</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="hospedagem_numero_camas" value="<?= $cliente->hospedagem_numero_camas ?? '' ?>" class="span12"></td>
                                    <td>
                                        <textarea name="detalhes_hospedagem" rows="1" class="span12"><?= html_escape($cliente->detalhes_hospedagem ?? '') ?></textarea>
                                    </td>
                                    <td>
                                        <?php if (!$dadosPreenchidos) : ?>
                                            <i class="fas fa-exclamation-triangle" style="color: #c09853;" title="Dados de hospedagem pendentes."></i>
                                        <?php endif; ?>
                                        <button type="submit" class="btn <?= $buttonClass ?> btn-mini"><?= $buttonText ?></button>
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
                        <th>Instrutor</th>
                        <th>Nº do Quarto</th>
                        <th>Tipo de Quarto</th>
                        <th>Nº de Camas</th>
                        <th>Observações</th>
                        <th>Ações</th>
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
                                <input type="hidden" name="active_tab" value="#tabHospedagem">
                                <?php
                                    $dadosPreenchidosInstrutor = !empty($instrutor->hospedagem_quarto_numero);
                                    $rowClassInstrutor = !$dadosPreenchidosInstrutor ? 'warning-row' : '';
                                    $buttonClassInstrutor = $dadosPreenchidosInstrutor ? 'btn-info' : 'btn-primary';
                                    $buttonTextInstrutor = $dadosPreenchidosInstrutor ? 'Editar' : 'Salvar';
                                ?>
                                <tr class="<?= $rowClassInstrutor ?>">
                                    <td><?= html_escape($instrutor->nome_instrutor) ?></td>
                                    <td><input type="text" name="hospedagem_quarto_numero" value="<?= html_escape($instrutor->hospedagem_quarto_numero ?? '') ?>" class="span12"></td>
                                    <td>
                                        <select name="hospedagem_tipo_quarto" class="span12">
                                            <option value=""></option>
                                            <option value="Solteiro" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Solteiro' ? 'selected' : '' ?>>Solteiro</option>
                                            <option value="Casal" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Casal' ? 'selected' : '' ?>>Casal</option>
                                            <option value="Família" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Família' ? 'selected' : '' ?>>Família</option>
                                            <option value="Compartilhado" <?= ($instrutor->hospedagem_tipo_quarto ?? '') == 'Compartilhado' ? 'selected' : '' ?>>Compartilhado</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="hospedagem_numero_camas" value="<?= $instrutor->hospedagem_numero_camas ?? '' ?>" class="span12"></td>
                                    <td>
                                        <textarea name="detalhes_hospedagem" rows="1" class="span12"><?= html_escape($instrutor->detalhes_hospedagem ?? '') ?></textarea>
                                    </td>
                                    <td>
                                        <?php if (!$dadosPreenchidosInstrutor) : ?>
                                            <i class="fas fa-exclamation-triangle" style="color: #c09853;" title="Dados de hospedagem pendentes."></i>
                                        <?php endif; ?>
                                        <button type="submit" class="btn <?= $buttonClassInstrutor ?> btn-mini"><?= $buttonTextInstrutor ?></button>
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
                        <div class="input-append" style="display: flex;">
                            <input type="text" class="span11" id="instrutor" placeholder="Pesquisar usuário..." style="flex-grow: 1;">
                            <button type="button" id="syncInstrutorBtn" class="btn btn-info" disabled title="Sincronizar perfil do instrutor para locação de equipamentos" style="border-radius: 0 5px 5px 0;"><i class="fas fa-sync-alt"></i></button>
                        </div>
                        <input type="hidden" name="usuario_id" id="usuario_id">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Bolsa Nº</label>
                    <div class="controls">
                        <input type="text" class="span6" name="numero_bolsa_instrutor" id="numero_bolsa_instrutor" placeholder="Pesquise a bolsa...">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Pagamento</label>
                    <div class="controls">
                        <select name="status_pagamento_instrutor" class="span4">
                            <option value="Pendente">Pendente</option>
                            <option value="Pago">Pago</option>                            
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Propósito</label>
                    <div class="controls">
                        <select name="proposito_instrutor" class="span6">
                            <option value="Staff">Staff</option>
                            <option value="Instrutor">Instrutor</option>
                            <option value="Divemaster">Divemaster</option>
                        </select>
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
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_lanterna_instrutor" value="1"><span class="slider"></span></label>
                            <span>Lanterna</span>
                        </div>
                        <div class="toggle-container">
                            <label class="switch small-toggle" style="margin-right: 10px;"><input type="checkbox" name="locar_computador_instrutor" value="1"><span class="slider"></span></label>
                            <span>Computador</span>
                        </div>
                    </div>
                    <div class="controls" style="margin-top: 15px;">
                        <label class="control-label" style="width: 60px; text-align: left;">Cilindros:</label>
                        <input type="number" name="locar_cilindro_instrutor" value="0" class="span1" min="0">
                        <label class="control-label" style="width: 80px; text-align: left; margin-left: 10px;">Reguladores:</label> <input type="number" name="locar_regulador_instrutor" value="0" class="span1" min="0">
                        <label class="control-label" style="width: 80px; text-align: left; margin-left: 10px;">Lanternas:</label> <input type="number" name="locar_lanterna_instrutor" value="0" class="span1" min="0">
                        <label class="control-label" style="width: 80px; text-align: left; margin-left: 10px;">Computadores:</label> <input type="number" name="locar_computador_instrutor" value="0" class="span1" min="0">
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
        <a title="Imprimir Ficha de Operação" class="button btn btn-mini btn-primary" href="#modalFichaOperacao" data-toggle="modal">
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
        margin-bottom: 5px; /* Reduce vertical spacing */
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
            <input type="hidden" id="modal_cliente_id_for_sync" value="">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel" style="display: inline-block; margin-right: 10px;">Editar Cliente: <span id="nomeClienteModal"></span></h5>
            <button type="button" id="btnSyncModal" class="btn btn-info btn-mini" title="Sincronizar perfil do cliente para locação de equipamentos">
                <i class="fas fa-sync-alt"></i> Sincronizar Equipamentos
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="viagem_id" value="<?php echo $result->id; ?>">
            <div class="row-fluid">
                <div class="span6">
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
                            <div>
                                <label class="switch" style="margin-right: 10px;"><input type="checkbox" name="locar_lanterna" id="edit_locar_lanterna" value="1"><span class="slider"></span></label>
                                <label for="edit_locar_lanterna" style="margin: 0;">Lanterna</label>
                            </div>
                            <div>
                                <label class="switch" style="margin-right: 10px;"><input type="checkbox" name="locar_computador" id="edit_locar_computador" value="1"><span class="slider"></span></label>
                                <label for="edit_locar_computador" style="margin: 0;">Computador</label>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <select name="proposito" id="edit_proposito" class="span6">
                                <option value=""></option>
                                <?php if (isset($listaPropositos)) { foreach ($listaPropositos as $proposito) { ?>
                                    <option value="<?= $proposito ?>"><?= $proposito ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <!-- Campos de quantidade de equipamentos -->
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Cilindros:</label>
                        <div class="controls">
                            <input type="number" name="locar_cilindro" id="edit_locar_cilindro" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Reguladores:</label>
                        <div class="controls">
                            <input type="number" name="locar_regulador" id="edit_locar_regulador" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Lanternas:</label>
                        <div class="controls">
                            <input type="number" name="locar_lanterna" id="edit_locar_lanterna" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Computadores:</label>
                        <div class="controls">
                            <input type="number" name="locar_computador" id="edit_locar_computador" value="0" min="0">
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
    #modalEditarInstrutor .form-horizontal .control-group {
        margin-bottom: 5px;
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
            <input type="hidden" id="modal_instrutor_usuario_id_for_sync" value="">
            <input type="hidden" name="active_tab" value="#tabInstrutores">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel" style="display: inline-block; margin-right: 10px;">Editar Instrutor: <span id="nomeInstrutorModal"></span></h5>
            <button type="button" id="btnSyncModalInstrutor" class="btn btn-info btn-mini" title="Sincronizar perfil do instrutor para locação de equipamentos">
                <i class="fas fa-sync-alt"></i> Sincronizar Equipamentos
            </button>
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
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="locar_lanterna" id="edit_instrutor_locar_lanterna" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_locar_lanterna" style="margin: 0;">Lanterna</label>
                            </div>
                            <div class="toggle-container">
                                <label class="switch"><input type="checkbox" name="locar_computador" id="edit_instrutor_locar_computador" value="1"><span class="slider"></span></label>
                                <label for="edit_instrutor_locar_computador" style="margin: 0;">Computador</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label">Bolsa Nº</label>
                        <div class="controls">
                            <input type="text" name="numero_bolsa" id="edit_instrutor_numero_bolsa" class="span8">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Pagamento</label>
                        <div class="controls">
                            <select name="status_pagamento" id="edit_instrutor_status_pagamento" class="span8">
                                <option value="Pendente">Pendente</option>
                                <option value="Pago">Pago</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Propósito</label>
                        <div class="controls">
                            <select name="proposito" id="edit_instrutor_proposito" class="span8">
                                <option value="Staff">Staff</option>
                                <option value="Instrutor">Instrutor</option>
                                <option value="Divemaster">Divemaster</option>
                            </select>
                        </div>
                    </div>


                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Cilindros:</label>
                        <div class="controls">
                            <input type="number" name="locar_cilindro" id="edit_instrutor_locar_cilindro" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Reguladores:</label>
                        <div class="controls">
                            <input type="number" name="locar_regulador" id="edit_instrutor_locar_regulador" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Lanternas:</label>
                        <div class="controls">
                            <input type="number" name="locar_lanterna" id="edit_instrutor_locar_lanterna" value="0" min="0">
                        </div>
                    </div>
                    <div class="control-group equipment-quantity-group">
                        <label class="control-label">Computadores:</label>
                        <div class="controls">
                            <input type="number" name="locar_computador" id="edit_instrutor_locar_computador" value="0" min="0">
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

<!-- Modal Ficha Operacao -->
<div id="modalFichaOperacao" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?= base_url() ?>index.php/viagens/imprimirOperacao/<?= $result->id ?>" method="get" target="_blank">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Imprimir Ficha de Operação</h5>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">Selecione o período que deseja imprimir.</div>
            <div class="control-group">
                <label for="data_operacao_inicio" class="control-label">Data Inicial</label>
                <div class="controls">
                    <input id="data_operacao_inicio" type="date" name="data_inicial" value="<?= $result->data_partida ?>" class="span12" min="<?= $result->data_partida ?>" max="<?= $result->data_retorno ?: $result->data_partida ?>" required onclick="try{this.showPicker()}catch(e){}" />
                </div>
            </div>
            <div class="control-group">
                <label for="data_operacao_fim" class="control-label">Data Final</label>
                <div class="controls">
                    <input id="data_operacao_fim" type="date" name="data_final" value="<?= $result->data_retorno ?: $result->data_partida ?>" class="span12" min="<?= $result->data_partida ?>" max="<?= $result->data_retorno ?: $result->data_partida ?>" required onclick="try{this.showPicker()}catch(e){}" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary">Imprimir</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#cliente").autocomplete({
        source: "<?= site_url('viagens/autoCompleteCliente'); ?>",
        minLength: 2,
        select: function(event, ui) {
            $("#cliente").val(ui.item.nome);
            $("#cliente_id").val(ui.item.id);
            $("#syncClienteBtn").prop('disabled', false);
        }
    });
    $("#instrutor").autocomplete({
        source: "<?= site_url('viagens/autoCompleteUsuario'); ?>",
        minLength: 2,
        select: function(event, ui) {
            $("#instrutor").val(ui.item.nome); // Preenche o campo com o nome
            $("#usuario_id").val(ui.item.id); // Guarda o ID no campo oculto
            $("#syncInstrutorBtn").prop('disabled', false); // Habilita o botão de sincronização
        }
    });
    
    // Autocomplete para Bolsas (Adição - Fora de Modal)
    $("#numero_bolsa, #numero_bolsa_instrutor").autocomplete({
        source: "<?= site_url('viagens/autoCompleteBolsa'); ?>",
        minLength: 1,
        select: function(event, ui) {
        }
    });

    // Autocomplete para Bolsas (Edição Cliente - Dentro do Modal)
    $("#edit_numero_bolsa").autocomplete({
        source: "<?= site_url('viagens/autoCompleteBolsa'); ?>",
        minLength: 1,
        appendTo: "#modalEditarCliente",
        select: function(event, ui) {
        }
    });

    // Autocomplete para Bolsas (Edição Instrutor - Dentro do Modal)
    $("#edit_instrutor_numero_bolsa").autocomplete({
        source: "<?= site_url('viagens/autoCompleteBolsa'); ?>",
        minLength: 1,
        appendTo: "#modalEditarInstrutor",
        select: function(event, ui) {
        }
    });

    $('#syncClienteBtn').on('click', function() {
        var clienteId = $('#cliente_id').val();
        if (!clienteId) {
            alert('Por favor, selecione um cliente primeiro.');
            return;
        }

        $.ajax({
            url: '<?= site_url('viagens/getClienteData/') ?>' + clienteId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data) {
                    // Equipamentos de SIM/NÃO (checkbox)
                    // Se o cliente NÃO possui o item (valor diferente de 1), marca para locar.
                    $('input[name="locar_nadadeira"]').prop('checked', data.possui_nadadeira != 1);
                    $('input[name="locar_colete"]').prop('checked', data.possui_colete != 1);
                    $('input[name="locar_neoprene"]').prop('checked', data.possui_neoprene != 1);
                    $('input[name="locar_lastro"]').prop('checked', data.possui_lastro != 1);
                    $('input[name="locar_lanterna"]').prop('checked', data.possui_lanterna != 1);
                    $('input[name="locar_computador"]').prop('checked', data.possui_computador != 1);

                    // Equipamentos contáveis (number)
                    // Se o cliente JÁ POSSUI (qtd > 0), sugere 0 para locação. Senão, sugere 1.
                    $('input[name="locar_cilindro"]').val(1); // Regra de negócio: sempre sugerir 1 cilindro para locação
                    $('input[name="locar_regulador"]').val(parseInt(data.qtd_reguladores) > 0 ? 0 : 1);
                    $('input[name="qtd_lanterna"]').val(parseInt(data.qtd_lanterna) > 0 ? 0 : 1);
                    $('input[name="qtd_computador"]').val(parseInt(data.qtd_computador) > 0 ? 0 : 1);

                    Swal.fire('Sucesso!', 'Equipamentos sugeridos com base no perfil do cliente.', 'success');
                }
            }
        });
    });

    $('#syncInstrutorBtn').on('click', function() {
        var usuarioId = $('#usuario_id').val();
        if (!usuarioId) {
            Swal.fire('Atenção!', 'Por favor, selecione um instrutor primeiro.', 'warning');
            return;
        }

        var btn = $(this);
        btn.find('i').addClass('fa-spin');

        $.ajax({
            url: '<?= site_url('viagens/getUsuarioDataForSync/') ?>' + usuarioId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('input[name="locar_nadadeira_instrutor"]').prop('checked', data.possui_nadadeira != 1);
                    $('input[name="locar_colete_instrutor"]').prop('checked', data.possui_colete != 1);
                    $('input[name="locar_neoprene_instrutor"]').prop('checked', data.possui_neoprene != 1);
                    $('input[name="locar_lastro_instrutor"]').prop('checked', data.possui_lastro != 1);
                    $('input[name="locar_cilindro_instrutor"]').val(1);
                    $('input[name="locar_regulador_instrutor"]').val(data.possui_regulador ? 0 : 1);
                    $('input[name="locar_lanterna_instrutor"]').val(data.possui_lanterna ? 0 : 1);
                    $('input[name="locar_computador_instrutor"]').val(data.possui_computador ? 0 : 1);
                    Swal.fire('Sucesso!', 'Equipamentos sugeridos com base no perfil do instrutor.', 'success');
                }
            },
            error: function() {
                Swal.fire('Erro!', 'Não foi possível buscar os dados do instrutor.', 'error');
            },
            complete: function() {
                btn.find('i').removeClass('fa-spin');
            }
        });
    });
    $('.money').mask('#.##0,00', {reverse: true});

    $(document).on('click', 'a[href="#modalEditarCliente"]', function() {
        var clienteId = $(this).data('cliente-id');
        var clienteNome = $(this).data('cliente-nome');
        var clienteData = $(this).data('cliente-data');

        $('#formEditarCliente').attr('action', '<?= site_url('viagens/editar_cliente_viagem/') ?>' + clienteId);
        $('#nomeClienteModal').text(clienteNome);
        $('#modal_cliente_id_for_sync').val(clienteData.cliente_id); // Adiciona o ID do cliente ao campo oculto

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
        $('#edit_locar_lanterna').prop('checked', clienteData.locar_lanterna == 1);
        $('#edit_qtd_lanterna').val(clienteData.qtd_lanterna);
        $('#edit_locar_computador').prop('checked', clienteData.locar_computador == 1);
        $('#edit_qtd_computador').val(clienteData.qtd_computador);
    });

    // Sincronização dentro do Modal de Edição
    $('#btnSyncModal').on('click', function() {
        var clienteId = $('#modal_cliente_id_for_sync').val();
        if (!clienteId) {
            Swal.fire('Atenção!', 'ID do cliente não encontrado para sincronização.', 'warning');
            return;
        }

        var btn = $(this);
        btn.find('i').addClass('fa-spin');

        $.ajax({
            url: '<?= site_url('viagens/getClienteData/') ?>' + clienteId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#edit_locar_nadadeira').prop('checked', data.possui_nadadeira != 1);
                    $('#edit_locar_colete').prop('checked', data.possui_colete != 1);
                    $('#edit_locar_neoprene').prop('checked', data.possui_neoprene != 1);
                    $('#edit_locar_lastro').prop('checked', data.possui_lastro != 1);
                    $('#edit_locar_cilindro').val(1);
                    $('#edit_locar_regulador').val(parseInt(data.qtd_reguladores) > 0 ? 0 : 1);
                    $('#edit_locar_lanterna').val(data.possui_lanterna ? 0 : 1);
                    $('#edit_locar_computador').val(data.possui_computador ? 0 : 1);
                    Swal.fire('Sucesso!', 'Equipamentos sugeridos com base no perfil do cliente.', 'success');
                }
            },
            complete: function() {
                btn.find('i').removeClass('fa-spin');
            }
        });
    });

    // Sincronização dentro do Modal de Edição de Instrutor
    $('#btnSyncModalInstrutor').on('click', function() {
        var usuarioId = $('#modal_instrutor_usuario_id_for_sync').val();
        if (!usuarioId) {
            Swal.fire('Atenção!', 'ID do instrutor não encontrado para sincronização.', 'warning');
            return;
        }

        var btn = $(this);
        btn.find('i').addClass('fa-spin');

        $.ajax({
            url: '<?= site_url('viagens/getUsuarioDataForSync/') ?>' + usuarioId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data) {
                    $('#edit_instrutor_locar_nadadeira').prop('checked', data.possui_nadadeira != 1);
                    $('#edit_instrutor_locar_colete').prop('checked', data.possui_colete != 1);
                    $('#edit_instrutor_locar_neoprene').prop('checked', data.possui_neoprene != 1);
                    $('#edit_instrutor_locar_lastro').prop('checked', data.possui_lastro != 1);
                    $('#edit_instrutor_locar_lanterna').prop('checked', data.possui_lanterna != 1);
                    $('#edit_instrutor_locar_computador').prop('checked', data.possui_computador != 1);
                    $('#edit_instrutor_locar_cilindro').val(1);
                    $('#edit_instrutor_locar_regulador').val(data.possui_regulador ? 0 : 1);
                    $('#edit_instrutor_locar_lanterna').val(data.possui_lanterna ? 0 : 1);
                    $('#edit_instrutor_locar_computador').val(data.possui_computador ? 0 : 1);
                    Swal.fire('Sucesso!', 'Equipamentos sugeridos com base no perfil do instrutor.', 'success');
                }
            },
            error: function() {
                Swal.fire('Erro!', 'Não foi possível buscar os dados do instrutor.', 'error');
            },
            complete: function() {
                btn.find('i').removeClass('fa-spin');
            }
        });
    });
    $(document).on('click', 'a[href="#modalEditarInstrutor"]', function() {
        var instrutorId = $(this).data('instrutor-id');
        var instrutorNome = $(this).data('instrutor-nome');
        var instrutorData = $(this).data('instrutor-data');

        $('#formEditarInstrutor').attr('action', '<?= site_url('viagens/editar_instrutor_viagem/') ?>' + instrutorId);
        $('#nomeInstrutorModal').text(instrutorNome);
        $('#modal_instrutor_usuario_id_for_sync').val(instrutorData.usuario_id);

        $('#edit_instrutor_proposito').val(instrutorData.proposito);
        $('#edit_instrutor_status_pagamento').val(instrutorData.status_pagamento);
        $('#edit_instrutor_numero_bolsa').val(instrutorData.numero_bolsa);
        $('#edit_instrutor_precisa_embarque').prop('checked', instrutorData.precisa_embarque == 1);
        $('#edit_instrutor_precisa_hospedagem').prop('checked', instrutorData.precisa_hospedagem == 1);
        $('#edit_instrutor_locar_nadadeira').prop('checked', instrutorData.locar_nadadeira == 1);
        $('#edit_instrutor_locar_colete').prop('checked', instrutorData.locar_colete == 1);
        $('#edit_instrutor_locar_neoprene').prop('checked', instrutorData.locar_neoprene == 1);
        $('#edit_instrutor_locar_lastro').prop('checked', instrutorData.locar_lastro == 1);
        $('#edit_instrutor_locar_cilindro').val(instrutorData.locar_cilindro);
        $('#edit_instrutor_locar_regulador').val(instrutorData.locar_regulador);
        $('#edit_instrutor_locar_lanterna').val(instrutorData.locar_lanterna);
        $('#edit_instrutor_locar_computador').val(instrutorData.locar_computador);
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