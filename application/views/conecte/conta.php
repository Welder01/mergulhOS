<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
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
    .equip-row {
        display: flex; align-items: center; gap: 15px;
    }
</style>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <h5>Meus Dados</h5>
            </div>
            <div class="widget-content nopadding wizard-content">

                <div class="progress" style="margin: 10px 20px;">
                    <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                    </div>
                </div>

                <?php if ($this->session->flashdata('success') != null) : ?>
                    <div class="alert alert-success" style="margin: 10px 20px;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error') != null) : ?>
                    <div class="alert alert-danger" style="margin: 10px 20px;">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo site_url('mine/editarDados'); ?>" id="formCliente" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="idClientes" value="<?php echo $result->idClientes; ?>" />
                    <input type="hidden" name="active_tab" id="active_tab" value="#pessoal">

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a data-toggle="tab" href="#pessoal"><i class="bx bx-user"></i> Dados Pessoais</a></li>
                        <li><a data-toggle="tab" href="#contato"><i class="bx bx-phone"></i> Contato</a></li>
                        <li><a data-toggle="tab" href="#endereco"><i class="bx bx-map"></i> Endereço</a></li>
                        <li><a data-toggle="tab" href="#equipamentos"><i class="bx bx-swim"></i> Meus Equipamentos</a></li>
                        <li><a data-toggle="tab" href="#saude"><i class="bx bx-first-aid"></i> Saúde e Segurança</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Aba Dados Pessoais -->
                        <div id="pessoal" class="tab-pane active">
                            <div class="control-group">
                                <label for="nomeCliente" class="control-label">Nome<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="nomeCliente" type="text" name="nomeCliente" value="<?php echo $result->nomeCliente; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="documento" class="control-label">CPF/CNPJ</label>
                                <div class="controls">
                                    <input id="documento" type="text" name="documento" value="<?php echo $result->documento; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="sexo" class="control-label">Sexo</label>
                                <div class="controls">
                                    <select name="sexo" id="sexo" class="monitor-input">
                                        <option value="">Selecione</option>
                                        <option value="Masculino" <?php if (($result->sexo ?? '') == 'Masculino') echo 'selected'; ?>>Masculino</option>
                                        <option value="Feminino" <?php if (($result->sexo ?? '') == 'Feminino') echo 'selected'; ?>>Feminino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="data_nascimento_conecte" class="control-label">Data de Nascimento</label>
                                <div class="controls">
                                    <input id="data_nascimento_conecte" type="text" name="data_nascimento" value="<?= $result->data_nascimento ? date('d/m/Y', strtotime($result->data_nascimento)) : ''; ?>" class="monitor-input datepicker" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="altura" class="control-label">Altura (m)</label>
                                <div class="controls">
                                    <input id="altura" type="text" name="altura" value="<?= $result->altura ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="peso" class="control-label">Peso (kg)</label>
                                <div class="controls">
                                    <input id="peso" type="text" name="peso" value="<?= $result->peso ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                        </div>

                        <!-- Aba Contato -->
                        <div id="contato" class="tab-pane">
                            <div class="control-group">
                                <label for="contato" class="control-label">Contato</label>
                                <div class="controls">
                                    <input id="contato" type="text" name="contato" value="<?php echo $result->contato; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="telefone" class="control-label">Telefone</label>
                                <div class="controls">
                                    <input id="telefone" type="text" name="telefone" value="<?php echo $result->telefone; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="celular" class="control-label">Celular</label>
                                <div class="controls">
                                    <input id="celular" type="text" name="celular" value="<?php echo $result->celular; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="email" class="control-label">Email</label>
                                <div class="controls">
                                    <input id="email" type="text" name="email" value="<?php echo $result->email; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="senha" class="control-label">Senha</label>
                                <div class="controls">
                                    <input id="senha" type="password" name="senha" value="" placeholder="Deixe em branco para não alterar" />
                                    <span class="help-inline">Deixe em branco para não alterar.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Aba Endereço -->
                        <div id="endereco" class="tab-pane">
                            <div class="control-group" class="control-label">
                                <label for="cep" class="control-label">CEP</label>
                                <div class="controls">
                                    <input id="cep" type="text" name="cep" value="<?php echo $result->cep; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="rua" class="control-label">Rua</label>
                                <div class="controls">
                                    <input id="rua" type="text" name="rua" value="<?php echo $result->rua; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="numero" class="control-label">Número</label>
                                <div class="controls">
                                    <input id="numero" type="text" name="numero" value="<?php echo $result->numero; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="complemento" class="control-label">Complemento</label>
                                <div class="controls">
                                    <input id="complemento" type="text" name="complemento" value="<?php echo $result->complemento; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="bairro" class="control-label">Bairro</label>
                                <div class="controls">
                                    <input id="bairro" type="text" name="bairro" value="<?php echo $result->bairro; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="cidade" class="control-label">Cidade</label>
                                <div class="controls">
                                    <input id="cidade" type="text" name="cidade" value="<?php echo $result->cidade; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="estado" class="control-label">Estado</label>
                                <div class="controls">
                                    <input id="estado" type="text" name="estado" value="<?php echo $result->estado; ?>" class="monitor-input" />
                                </div>
                            </div>
                        </div>

                        <!-- Aba Equipamentos -->
                        <div id="equipamentos" class="tab-pane">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="width: 25%;"><strong>Colete</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_colete" value="1" class="equip-owner-toggle" data-target-group=".colete-details" <?= ($result->possui_colete ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="colete-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe o tamanho?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#tamanho_colete" <?= ($result->tamanho_colete ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="tamanho_colete" type="text" name="tamanho_colete" value="<?= $result->tamanho_colete ?? '' ?>" placeholder="Qual tamanho?" class="span3 monitor-input"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lastro</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_lastro" value="1" class="equip-owner-toggle" data-target-group=".lastro-details" <?= ($result->possui_lastro ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="lastro-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe o peso?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#peso_lastro" <?= ($result->peso_lastro ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="peso_lastro" type="text" name="peso_lastro" value="<?= $result->peso_lastro ?? '' ?>" placeholder="Qual peso (kg)?" class="span3 monitor-input"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Neoprene</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_neoprene" value="1" class="equip-owner-toggle" data-target-group=".neoprene-details" <?= ($result->possui_neoprene ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="neoprene-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe o tamanho?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#tamanho_neoprene" <?= ($result->tamanho_neoprene ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="tamanho_neoprene" type="text" name="tamanho_neoprene" value="<?= $result->tamanho_neoprene ?? '' ?>" placeholder="Qual tamanho?" class="span3 monitor-input"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nadadeira</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_nadadeira" value="1" class="equip-owner-toggle" data-target-group=".nadadeira-details" <?= ($result->possui_nadadeira ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="nadadeira-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe o tamanho?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#tamanho_nadadeira" <?= ($result->tamanho_nadadeira ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="tamanho_nadadeira" type="text" name="tamanho_nadadeira" value="<?= $result->tamanho_nadadeira ?? '' ?>" placeholder="Qual tamanho?" class="span3 monitor-input"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Regulador</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_regulador" value="1" <?= ($result->possui_regulador ?? 0) == 1 ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="regulador-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe a quantidade?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#qtd_reguladores" <?= ($result->qtd_reguladores ?? 0) > 0 ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="qtd_reguladores" type="number" name="qtd_reguladores" value="<?= $result->qtd_reguladores ?? 0 ?>" placeholder="Quantos?" class="span2 monitor-input" min="0"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lanterna</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_lanterna" value="1" class="equip-owner-toggle" data-target-group=".lanterna-details" <?= ($result->possui_lanterna ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="lanterna-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe a quantidade?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#qtd_lanterna" <?= ($result->qtd_lanterna ?? 0) > 0 ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="qtd_lanterna" type="number" name="qtd_lanterna" value="<?= $result->qtd_lanterna ?? 0 ?>" placeholder="Quantas?" class="span2 monitor-input" min="0"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Computador de Mergulho</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_computador" value="1" class="equip-owner-toggle" data-target-group=".computador-details" <?= ($result->possui_computador ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="computador-details" style="display: inline-flex; align-items: center; gap: 15px;">
                                                <span>Sabe a quantidade?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#qtd_computador" <?= ($result->qtd_computador ?? 0) > 0 ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="qtd_computador" type="number" name="qtd_computador" value="<?= $result->qtd_computador ?? 0 ?>" placeholder="Quantos?" class="span2 monitor-input" min="0"/>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Aba Saúde e Segurança -->
                        <div id="saude" class="tab-pane">
                            <h4>Contato de Emergência</h4>
                            <div class="control-group">
                                <label for="contato_emergencia_nome" class="control-label">Nome</label>
                                <div class="controls">
                                    <input id="contato_emergencia_nome" type="text" name="contato_emergencia_nome" value="<?= $result->contato_emergencia_nome ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="contato_emergencia_telefone" class="control-label">Telefone</label>
                                <div class="controls">
                                    <input id="contato_emergencia_telefone" type="text" name="contato_emergencia_telefone" value="<?= $result->contato_emergencia_telefone ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="contato_emergencia_parentesco" class="control-label">Parentesco</label>
                                <div class="controls">
                                    <input id="contato_emergencia_parentesco" type="text" name="contato_emergencia_parentesco" value="<?= $result->contato_emergencia_parentesco ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <hr>
                            <h4>Atestado Médico
                                <?php
                                if (isset($result->atestado_medico_validade) && $result->atestado_medico_validade) {
                                    $dataValidade = new DateTime($result->atestado_medico_validade);
                                    $dataAtual = new DateTime();
                                    if ($dataValidade >= $dataAtual) {
                                        echo '<span class="badge badge-success" style="margin-left: 10px; vertical-align: middle;">Válido</span>';
                                    } else {
                                        echo '<span class="badge badge-important" style="margin-left: 10px; vertical-align: middle;">Vencido</span>';
                                    }
                                }
                                ?>
                            </h4>
                            <div class="control-group">
                                <label for="atestado_medico_emissao" class="control-label">Data de Emissão</label>
                                <div class="controls">
                                    <input id="atestado_medico_emissao" type="text" name="atestado_medico_emissao" value="<?= $result->atestado_medico_emissao ? date('d/m/Y', strtotime($result->atestado_medico_emissao)) : '' ?>" class="monitor-input datepicker" />
                                    <span class="help-inline">A validade será calculada para 1 ano a partir desta data.</span>
                                </div>
                            </div>
                            <?php if (isset($result->atestado_medico_arquivo) && $result->atestado_medico_arquivo) : ?>
                                <div class="control-group">
                                    <label class="control-label">Atestado Atual</label>
                                    <div class="controls">
                                        <table class="table table-bordered" style="width: auto;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="<?= base_url('assets/uploads/atestados/' . $result->atestado_medico_arquivo) ?>" target="_blank">
                                                            <i class="fas fa-file-alt"></i> <?= substr($result->atestado_medico_arquivo, 13) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= site_url('mine/remover_atestado/') ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este atestado?')">Remover</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="control-group">
                                <label for="atestado_medico_arquivo" class="control-label"><?= (isset($result->atestado_medico_arquivo) && $result->atestado_medico_arquivo) ? 'Substituir Atestado' : 'Arquivo do Atestado' ?></label>
                                <div class="controls">
                                    <input id="atestado_medico_arquivo" type="file" name="atestado_medico_arquivo" />
                                    <span class="help-inline">Enviar um novo arquivo substituirá o atual.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="nome_medico" class="control-label">Nome do Médico</label>
                                <div class="controls">
                                    <input id="nome_medico" type="text" name="nome_medico" value="<?= $result->nome_medico ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="crm_medico" class="control-label">CRM do Médico</label>
                                <div class="controls">
                                    <input id="crm_medico" type="text" name="crm_medico" value="<?= $result->crm_medico ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="codigo_validacao_atestado" class="control-label">Código de Validação</label>
                                <div class="controls">
                                    <input id="codigo_validacao_atestado" type="text" name="codigo_validacao_atestado" value="<?= $result->codigo_validacao_atestado ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class="bx bx-save"></i></span><span class="button__text2">Salvar Alterações</span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Função para atualizar a barra de progresso
        function updateProgressBar() {
            var totalInputs = $('.monitor-input').length;
            var filledInputs = 0;
            $('.monitor-input').each(function() {
                if ($(this).val() != '') {
                    filledInputs++;
                }
            });

            var progress = (totalInputs > 0) ? (filledInputs / totalInputs) * 100 : 0;
            var progressBar = $('#progressBar');
            progressBar.css('width', progress + '%');
            progressBar.text(Math.round(progress) + '%');

            progressBar.removeClass('progress-bar-danger progress-bar-warning progress-bar-success');
            if (progress < 40) {
                progressBar.addClass('progress-bar-danger');
            } else if (progress < 80) {
                progressBar.addClass('progress-bar-warning');
            } else {
                progressBar.addClass('progress-bar-success');
            }
        }

        // Monitora mudanças nos campos
        $('.monitor-input').on('input change', function() {
            updateProgressBar();
        });

        // Atualiza a barra ao carregar a página
        updateProgressBar();

        // Máscaras
        $('#cep').mask('00000-000');
        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');
        $('#contato_emergencia_telefone').mask('(00) 00000-0000');
        $('#altura').mask('0,00', {reverse: true});
        $('#peso').mask('000,00', {reverse: true});

        // Busca de CEP
        $("#cep").blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if(validacep.test(cep)) {
                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#estado").val(dados.uf);
                            updateProgressBar(); // Atualiza progresso após preencher
                        }
                    });
                }
            }
        });

        // Lógica para manter a aba ativa após salvar
        var activeTabFromUrl = '<?php echo $this->input->get('tab') ?? ''; ?>';
        if (activeTabFromUrl) {
            $('a[href="#' + activeTabFromUrl + '"]').tab('show');
            $('#active_tab').val('#' + activeTabFromUrl);
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var currentTab = $(e.target).attr('href');
            $('#active_tab').val(currentTab);
        });

        // Lógica para manter a aba ativa após salvar
        var activeTabFromUrl = '<?php echo $this->input->get('tab') ?? ''; ?>';
        if (activeTabFromUrl) {
            // Garante que o valor não tenha o # para o seletor
            var tabId = activeTabFromUrl.replace('#', '');
            $('a[href="#' + tabId + '"]').tab('show');
            $('#active_tab').val('#' + tabId);
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var currentTab = $(e.target).attr('href');
            $('#active_tab').val(currentTab);
        });

        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
</script>