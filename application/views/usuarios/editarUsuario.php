<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<?php $this->load->view('usuarios/editarUsuario_style'); ?>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <h5>Editar Usuário</h5>
            </div>
            <div class="widget-content nopadding wizard-content">
                <?php if ($custom_error != '') {
    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
} ?>
                <div class="progress">
                    <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                    </div>
                </div>

                <form action="<?php echo current_url(); ?>" id="formUsuario" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="idUsuarios" value="<?php echo $result->idUsuarios; ?>" />
                    <input type="hidden" name="active_tab" id="active_tab" value="#pessoal">

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a data-toggle="tab" href="#pessoal"><i class="bx bx-user"></i> Dados Pessoais</a></li>
                        <li><a data-toggle="tab" href="#contato"><i class="bx bx-phone"></i> Contato</a></li>
                        <li><a data-toggle="tab" href="#endereco"><i class="bx bx-map"></i> Endereço</a></li>
                        <li><a data-toggle="tab" href="#equipamentos"><i class="bx bx-swim"></i> Equipamentos</a></li>
                        <li><a data-toggle="tab" href="#saude"><i class="bx bx-first-aid"></i> Saúde e Segurança</a></li>
                        <li><a data-toggle="tab" href="#restricoes"><i class="bx bx-food-menu"></i> Restrições</a></li>
                        <li><a data-toggle="tab" href="#certificacoes"><i class="bx bx-certification"></i> Certificações</a></li>
                        <li><a data-toggle="tab" href="#sistema"><i class="bx bx-cog"></i> Sistema</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Aba Dados Pessoais -->
                        <div id="pessoal" class="tab-pane active">
                            <div class="control-group">
                                <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="nome" type="text" name="nome" value="<?php echo $result->nome; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="rg" class="control-label">RG</label>
                                <div class="controls">
                                    <input id="rg" type="text" name="rg" value="<?php echo $result->rg; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="cpf" class="control-label">CPF<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="cpf" type="text" name="cpf" value="<?php echo $result->cpf; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="sexo" class="control-label">Sexo</label>
                                <div class="controls">
                                    <select name="sexo" id="sexo" class="monitor-input">
                                        <option value="Masculino" <?= ($result->sexo ?? '') == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                        <option value="Feminino" <?= ($result->sexo ?? '') == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                    </select>
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
                                    <input id="contato" type="text" name="contato" value="<?php echo $result->contato ?? ''; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="telefone" class="control-label">Telefone<span class="required">*</span></label>
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
                                <label for="email" class="control-label">Email<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="email" type="text" name="email" value="<?php echo $result->email; ?>" class="monitor-input" />
                                </div>
                            </div>
                        </div>

                        <!-- Aba Endereço -->
                        <div id="endereco" class="tab-pane">
                            <div class="control-group">
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
                                    <input id="complemento" type="text" name="complemento" value="<?php echo $result->complemento ?? ''; ?>" class="monitor-input" />
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
                                            <div class="colete-details" style="display: none;">
                                                <span>Sabe o tamanho?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#tamanho_colete" <?= ($result->tamanho_colete ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="tamanho_colete" type="text" name="tamanho_colete" value="<?= $result->tamanho_colete ?? '' ?>" placeholder="Qual tamanho?" class="span3 monitor-input" style="display: none;"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lastro</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_lastro" value="1" class="equip-owner-toggle" data-target-group=".lastro-details" <?= ($result->possui_lastro ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="lastro-details" style="display: none;">
                                                <span>Sabe o peso?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#peso_lastro" <?= ($result->peso_lastro ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="peso_lastro" type="text" name="peso_lastro" value="<?= $result->peso_lastro ?? '' ?>" placeholder="Qual peso (kg)?" class="span3 monitor-input" style="display: none;"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Neoprene</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_neoprene" value="1" class="equip-owner-toggle" data-target-group=".neoprene-details" <?= ($result->possui_neoprene ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="neoprene-details" style="display: none;">
                                                <span>Sabe o tamanho?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#tamanho_neoprene" <?= ($result->tamanho_neoprene ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="tamanho_neoprene" type="text" name="tamanho_neoprene" value="<?= $result->tamanho_neoprene ?? '' ?>" placeholder="Qual tamanho?" class="span3 monitor-input" style="display: none;"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nadadeira</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_nadadeira" value="1" class="equip-owner-toggle" data-target-group=".nadadeira-details" <?= ($result->possui_nadadeira ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="nadadeira-details" style="display: none;">
                                                <span>Sabe o tamanho?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#tamanho_nadadeira" <?= ($result->tamanho_nadadeira ?? '') ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="tamanho_nadadeira" type="text" name="tamanho_nadadeira" value="<?= $result->tamanho_nadadeira ?? '' ?>" placeholder="Qual tamanho?" class="span3 monitor-input" style="display: none;"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Regulador</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_regulador" value="1" class="equip-owner-toggle" data-target-group=".regulador-details" <?= ($result->possui_regulador ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="regulador-details" style="display: none;">
                                                <span>Sabe a quantidade?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#qtd_reguladores" <?= ($result->qtd_reguladores ?? 0) > 0 ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="qtd_reguladores" type="number" name="qtd_reguladores" value="<?= $result->qtd_reguladores ?? 0 ?>" placeholder="Quantos?" class="span2 monitor-input" style="display: none;" min="0"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lanterna</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_lanterna" value="1" class="equip-owner-toggle" data-target-group=".lanterna-details" <?= ($result->possui_lanterna ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="lanterna-details" style="display: none;">
                                                <span>Sabe a quantidade?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#qtd_lanterna" <?= ($result->qtd_lanterna ?? 0) > 0 ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="qtd_lanterna" type="number" name="qtd_lanterna" value="<?= $result->qtd_lanterna ?? 0 ?>" placeholder="Quantas?" class="span2 monitor-input" style="display: none;" min="0"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Computador de Mergulho</strong></td>
                                        <td class="equip-row">
                                            <span>Possui?</span>
                                            <label class="switch small-toggle"><input type="checkbox" name="possui_computador" value="1" class="equip-owner-toggle" data-target-group=".computador-details" <?= ($result->possui_computador ?? 0) ? 'checked' : '' ?>><span class="slider"></span></label>
                                            <div class="computador-details" style="display: none;">
                                                <span>Sabe a quantidade?</span>
                                                <label class="switch small-toggle"><input type="checkbox" class="equip-detail-toggle" data-target="#qtd_computador" <?= ($result->qtd_computador ?? 0) > 0 ? 'checked' : '' ?>><span class="slider"></span></label>
                                                <input id="qtd_computador" type="number" name="qtd_computador" value="<?= $result->qtd_computador ?? 0 ?>" placeholder="Quantos?" class="span2 monitor-input" style="display: none;" min="0"/>
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
                                    <input id="atestado_medico_emissao" type="date" name="atestado_medico_emissao" value="<?= $result->atestado_medico_emissao ?? '' ?>" class="monitor-input" />
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
                                                        <a href="<?= base_url('assets/uploads/atestados_usuarios/' . $result->atestado_medico_arquivo) ?>" target="_blank">
                                                            <i class="fas fa-file-alt"></i> <?= substr($result->atestado_medico_arquivo, 13) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= site_url('usuarios/remover_atestado_usuario/' . $result->idUsuarios) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este atestado?')">Remover</a>
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

                        <!-- Aba Restrições -->
                        <div id="restricoes" class="tab-pane">
                            <h4>Adicionar Restrição Alimentar</h4>
                            <div class="control-group">
                                <label for="restricao" class="control-label">Restrição</label>
                                <div class="controls">
                                    <input id="restricao" type="text" name="restricao" value="" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="observacoes_restricao" class="control-label">Observações</label>
                                <div class="controls">
                                    <input id="observacoes_restricao" type="text" name="observacoes_restricao" value="" />
                                </div>
                            </div>
                            <hr>
                            <h4>Restrições Cadastradas</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Restrição</th>
                                        <th>Observações</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($restricoes) && !empty($restricoes)) : ?>
                                        <?php foreach ($restricoes as $r) : ?>
                                            <tr>
                                                <td><?= html_escape($r->restricao) ?></td>
                                                <td><?= html_escape($r->observacoes) ?></td>
                                                <td><a href="<?= site_url('usuarios/remover_restricao_usuario/' . $r->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover esta restrição?')">Remover</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr><td colspan="3">Nenhuma restrição cadastrada.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Aba Certificações -->
                        <div id="certificacoes" class="tab-pane">
                            <h4>Adicionar Certificação</h4>
                            <div class="control-group">
                               <label for="nome_certificacao" class="control-label">Formação</label>
                               <div class="controls">
                                   <select id="nome_certificacao" name="nome_certificacao" class="span12 monitor-input">
                                       <option value="">Selecione</option>
                                       <?php foreach ($tipos_certificacao as $tipo) : ?>
                                           <option value="<?= trim($tipo) ?>"><?= trim($tipo) ?></option>
                                       <?php endforeach; ?>
                                   </select>
                               </div>
                            </div>
                            <div class="control-group">
                               <label for="orgao_emissor" class="control-label">Certificadora</label>
                               <div class="controls">
                                   <select id="orgao_emissor" name="orgao_emissor" class="span12 monitor-input">
                                       <option value="">Selecione</option>
                                       <?php foreach ($tipos_certificadora as $certificadora) : ?>
                                           <option value="<?= trim($certificadora) ?>"><?= trim($certificadora) ?></option>
                                       <?php endforeach; ?>
                                   </select>
                               </div>
                            </div>
                            <div class="control-group">
                               <label for="numero_certificacao" class="control-label">Nº do Certificado</label>
                               <div class="controls">
                                   <input id="numero_certificacao" type="text" name="numero_certificacao" class="span12 monitor-input" value="" />
                               </div>
                            </div>
                            <div class="control-group">
                               <label for="data_emissao" class="control-label">Data de Emissão</label>
                               <div class="controls">
                                   <input id="data_emissao" type="date" name="data_emissao" class="span12 monitor-input" value="" />
                               </div>
                            </div>
                            <div class="control-group">
                               <label for="arquivo_certificacao" class="control-label">Arquivo</label>
                               <div class="controls">
                                   <input id="arquivo_certificacao" type="file" name="arquivo_certificacao" class="span12 monitor-input" />
                               </div>
                            </div>
                            <hr>
                            <h4>Certificações Cadastradas</h4>
                            <table class="table table-bordered">
                               <thead>
                                   <tr>
                                       <th>Formação</th>
                                       <th>Certificadora</th>
                                       <th>Nº Certificado</th>
                                       <th>Emissão</th>
                                       <th>Ações</th>
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
                                                       <a href="<?= base_url('uploads/certificados_usuarios/' . $c->arquivo) ?>" target="_blank" class="btn btn-mini"><i class="icon-download"></i></a>
                                                   <?php endif; ?>
                                                   <a href="<?= site_url('usuarios/remover_certificacao_usuario/' . $c->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover esta certificação?')">Remover</a>
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

                        <!-- Aba Sistema -->
                        <div id="sistema" class="tab-pane">
                            <div class="control-group">
                                <label for="senha" class="control-label">Senha</label>
                                <div class="controls">
                                    <input id="senha" type="password" name="senha" value="" placeholder="Não preencha se não quiser alterar." />
                                    <span class="help-inline">Deixe em branco para não alterar.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Situação*</label>
                                <div class="controls">
                                    <select name="situacao" id="situacao">
                                        <option value="1" <?= ($result->situacao == 1) ? 'selected' : '' ?>>Ativo</option>
                                        <option value="0" <?= ($result->situacao == 0) ? 'selected' : '' ?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Permissões<span class="required">*</span></label>
                                <div class="controls">
                                    <select name="permissoes_id" id="permissoes_id">
                                        <?php foreach ($permissoes as $p) {
    $selected = ($p->idPermissao == $result->permissoes_id) ? 'selected' : '';
    echo '<option value="' . $p->idPermissao . '"' . $selected . '>' . $p->nome . '</option>';
} ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="dataExpiracao" class="control-label">Expira em</label>
                                <div class="controls">
                                    <input id="dataExpiracao" type="date" name="dataExpiracao" value="<?php echo $result->dataExpiracao; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-primary">
                                    <span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar</span>
                                </button>
                                <a href="<?php echo base_url() ?>index.php/usuarios" class="button btn btn-warning">
                                    <span class="button__icon"><i class="bx bx-undo"></i></span> <span class="button__text2">Voltar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#formUsuario').validate({
            rules: {
                nome: {
                    required: true
                },
                cpf: {
                    required: true
                },
                telefone: {
                    required: true
                },
                email: {
                    required: true
                },
                contato_emergencia_nome: {
                    required: function(element) {
                        return $("#contato_emergencia_telefone").val() != '' || $("#contato_emergencia_parentesco").val() != '';
                    }
                },
                contato_emergencia_telefone: {
                    required: function(element) {
                        return $("#contato_emergencia_nome").val() != '' || $("#contato_emergencia_parentesco").val() != '';
                    }
                }
            },
            messages: {
                nome: {
                    required: 'Campo Requerido.'
                },
                cpf: {
                    required: 'Campo Requerido.'
                },
                telefone: {
                    required: 'Campo Requerido.'
                },
                email: {
                    required: 'Campo Requerido.'
                },
                contato_emergencia_nome: {
                    required: 'Preencha o nome do contato de emergência.'
                },
                contato_emergencia_telefone: {
                    required: 'Preencha o telefone do contato de emergência.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });

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
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#cep').mask('00000-000');
        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');
        $('#contato_emergencia_telefone').mask('(00) 00000-0000');
        $('#altura').mask('0,00', {reverse: true});
        $('#peso').mask('000,00', {reverse: true});

        // Busca de CEP
        $("#cep").blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep != "" && /^[0-9]{8}$/.test(cep)) {
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) { // Adicionado callback para JSONP
                    if (!("erro" in dados)) {
                        $("#rua").val(dados.logradouro); $("#bairro").val(dados.bairro); $("#cidade").val(dados.localidade); $("#estado").val(dados.uf);
                        updateProgressBar(); // Atualiza progresso após preencher
                    }
                });
            }
        });

        // Lógica para mostrar/ocultar campos de equipamento
        function toggleEquipDetails(ownerCheckbox) {
            var targetGroup = $(ownerCheckbox.data('target-group'));
            if (ownerCheckbox.is(':checked')) {
                targetGroup.show();
            } else {
                targetGroup.hide();
                targetGroup.find('input[type=checkbox]').prop('checked', false).trigger('change');
            }
        }

        function toggleEquipInput(detailCheckbox) {
            var targetInput = $(detailCheckbox.data('target'));
            if (detailCheckbox.is(':checked')) {
                targetInput.show();
            } else {
                targetInput.hide().val('');
            }
        }

        // Inicialização
        $('.equip-owner-toggle').each(function() {
            toggleEquipDetails($(this));
        });
        $('.equip-detail-toggle').each(function() {
            toggleEquipInput($(this));
        });

        // Eventos de mudança
        $('.equip-owner-toggle').on('change', function() {
            toggleEquipDetails($(this));
        });
        $('.equip-detail-toggle').on('change', function() {
            toggleEquipInput($(this));
        });

        // Lógica para manter a aba ativa
        var activeTabFromUrl = '<?php echo $this->input->get('tab') ?? ''; ?>';
        if (activeTabFromUrl) {
            $('a[href="#' + activeTabFromUrl + '"]').tab('show');
            $('#active_tab').val('#' + activeTabFromUrl);
        }

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var currentTab = $(e.target).attr('href');
            $('#active_tab').val(currentTab);
        });
    });
</script>