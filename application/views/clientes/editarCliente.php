<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/funcoes.js"></script>
<style>
    /* Custom styles for the wizard */
    .wizard-content {
        margin-top: 20px;
    }
    .progress {
        margin-bottom: 20px;
        height: 25px;
        border-radius: 15px;
    }
    .progress-bar {
        font-size: 14px;
        line-height: 25px;
        color: #fff;
        font-weight: bold;
        text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
    }
    .nav-tabs > li > a {
        font-size: 1.1em;
    }
    .tab-content {
        border: 1px solid #ddd;
        border-top: 0;
        padding: 20px;
        border-radius: 0 0 5px 5px;
    }
    .form-actions {
        border-top: 0;
        background-color: transparent;
        padding: 20px 0 0 0;
    }
</style>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <h5>Editar Cliente</h5>
            </div>
            <div class="widget-content nopadding wizard-content">

                <div class="progress">
                    <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                    </div>
                </div>

                <?php if ($custom_error != '') {
                    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
                } ?>

                <form action="<?php echo current_url(); ?>" id="formCliente" method="post" class="form-horizontal" enctype="multipart/form-data">

                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#pessoal">Dados Pessoais</a></li>
                        <li><a data-toggle="tab" href="#contato">Contato</a></li>
                        <li><a data-toggle="tab" href="#endereco">Endereço</a></li>
                        <li><a data-toggle="tab" href="#equipamentos">Equipamentos</a></li>
                        <li><a data-toggle="tab" href="#saude">Saúde e Segurança</a></li>
                        <li><a data-toggle="tab" href="#restricoes">Restrições</a></li>
                        <li><a data-toggle="tab" href="#certificacoes">Certificações</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Aba Dados Pessoais -->
                        <div id="pessoal" class="tab-pane active">
                            <div class="control-group">
                                <label for="nomeCliente" class="control-label">Nome<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="nomeCliente" type="text" name="nomeCliente" value="<?php echo $result->nomeCliente; ?>" class="monitor-input" />
                                    <input id="idClientes" type="hidden" name="idClientes" value="<?php echo $result->idClientes; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="documento" class="control-label">CPF/CNPJ</label>
                                <div class="controls">
                                    <input id="documento" type="text" name="documento" value="<?php echo $result->documento; ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="fornecedor" class="control-label">Tipo de Cliente</label>
                                <div class="controls">
                                    <label>
                                        <input name="fornecedor" type="checkbox" value="1" <?php if ($result->fornecedor) echo 'checked'; ?> />
                                        <span class="lbl"> Fornecedor</span>
                                    </label>
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
                            <div class="control-group">
                                <label for="tamanho_colete" class="control-label">Tamanho do Colete</label>
                                <div class="controls">
                                    <input id="tamanho_colete" type="text" name="tamanho_colete" value="<?= $result->tamanho_colete ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="peso_lastro" class="control-label">Peso do Lastro (kg)</label>
                                <div class="controls">
                                    <input id="peso_lastro" type="text" name="peso_lastro" value="<?= $result->peso_lastro ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="tamanho_neoprene" class="control-label">Tamanho do Neoprene</label>
                                <div class="controls">
                                    <input id="tamanho_neoprene" type="text" name="tamanho_neoprene" value="<?= $result->tamanho_neoprene ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="tamanho_nadadeira" class="control-label">Tamanho da Nadadeira</label>
                                <div class="controls">
                                    <input id="tamanho_nadadeira" type="text" name="tamanho_nadadeira" value="<?= $result->tamanho_nadadeira ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
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
                            <h4>Atestado Médico</h4>
                            <div class="control-group">
                                <label for="atestado_medico_validade" class="control-label">Validade</label>
                                <div class="controls">
                                    <input id="atestado_medico_validade" type="date" name="atestado_medico_validade" value="<?= $result->atestado_medico_validade ?? '' ?>" class="monitor-input" />
                                </div>
                            </div>
                            <?php if (isset($result->atestado_medico_arquivo) && $result->atestado_medico_arquivo) : ?>
                                <div class="control-group">
                                    <label class="control-label">Atestado Atual</label>
                                    <div class="controls">
                                        <table class="table table-bordered" style="width: auto;">
                                            <thead>
                                                <tr>
                                                    <th>Arquivo</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="<?= base_url('assets/uploads/atestados/' . $result->atestado_medico_arquivo) ?>" target="_blank">
                                                            <i class="fas fa-file-alt"></i> <?= substr($result->atestado_medico_arquivo, 13) ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?= site_url('clientes/remover_atestado/' . $result->idClientes) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover este atestado?')">Remover</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="atestado_medico_arquivo_atual" value="<?= $result->atestado_medico_arquivo ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="control-group">
                                <label for="atestado_medico_arquivo" class="control-label"><?= (isset($result->atestado_medico_arquivo) && $result->atestado_medico_arquivo) ? 'Substituir Atestado' : 'Arquivo do Atestado' ?></label>
                                <div class="controls">
                                    <input id="atestado_medico_arquivo" type="file" name="atestado_medico_arquivo" />
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
                                                <td>
                                                    <a href="<?= site_url('clientes/remover_restricao/' . $r->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover esta restrição?')">Remover</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="3">Nenhuma restrição cadastrada.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Aba Certificações -->
                        <div id="certificacoes" class="tab-pane">
                            <h4>Adicionar Certificação</h4>
                            <div class="control-group">
                                <label for="nome_certificacao" class="control-label">Nome</label>
                                <div class="controls">
                                    <input id="nome_certificacao" type="text" name="nome_certificacao" value="" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="orgao_emissor" class="control-label">Órgão Emissor</label>
                                <div class="controls">
                                    <input id="orgao_emissor" type="text" name="orgao_emissor" value="" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="data_emissao" class="control-label">Data de Emissão</label>
                                <div class="controls">
                                    <input id="data_emissao" type="date" name="data_emissao" value="" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="arquivo" class="control-label">Arquivo</label>
                                <div class="controls">
                                    <input id="arquivo" type="file" name="arquivo" />
                                </div>
                            </div>
                            <hr>
                            <h4>Certificações Cadastradas</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Certificação</th>
                                        <th>Órgão Emissor</th>
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
                                                <td><?= $c->data_emissao ? date('d/m/Y', strtotime($c->data_emissao)) : '-' ?></td>
                                                <td>
                                                    <?php if ($c->arquivo) : ?>
                                                        <a href="<?= base_url('uploads/certificados/' . $c->arquivo) ?>" target="_blank" class="btn btn-mini"><i class="icon-download"></i></a>
                                                    <?php endif; ?>
                                                    <a href="<?= site_url('clientes/remover_certificacao/' . $c->id) ?>" class="btn btn-danger btn-mini" onclick="return confirm('Deseja remover esta certificação?')">Remover</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4">Nenhuma certificação cadastrada.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                <div class="form-actions">
                    <div class="span12">
                        <div class="span6 offset3" style="display:flex;justify-content: center">
                            <button type="submit" class="button btn btn-primary">
                                <span class="button__icon"><i class="bx bx-save"></i></span><span class="button__text2">Salvar Alterações</span></button>
                            <a href="<?php echo base_url() ?>index.php/clientes" id="" class="button btn btn-warning">
                                <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.getJSON('<?php echo base_url() ?>assets/json/estados.json', function(data) {
            for (i in data.estados) {
                $('#estado').append(new Option(data.estados[i].nome, data.estados[i].sigla));
            }
            var curState = '<?php echo $result->estado; ?>';
            if (curState) { 
                $("#estado option[value=" + curState + "]").prop("selected", true);
            }

        });
        $('#formCliente').validate({
            rules: {
                nomeCliente: {
                    required: true
                },
            },
            messages: {
                nomeCliente: {
                    required: 'Campo Requerido.'
                },
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
        $('#cep').mask('00000-000');
        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');

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
    });
</script>
