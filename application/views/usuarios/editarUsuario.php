<link rel="stylesheet" href="<?php echo base_url();?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery.mask.min.js"></script>

<style>
    .form-horizontal .control-label {
        width: 120px;
    }
    .form-horizontal .controls {
        margin-left: 140px;
    }
    .tab-content {
        border: 1px solid #ddd;
        border-top: 0;
        padding: 20px;
        border-radius: 0 0 5px 5px;
    }
</style>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-user"></i>
                </span>
                <h5>Editar Usuário</h5>
            </div>
            <div class="widget-content nopadding">
                <?php if ($custom_error != '') {
    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
} ?>
                <form action="<?php echo current_url(); ?>" id="formUsuario" method="post" class="form-horizontal">

                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#pessoal">Dados Pessoais</a></li>
                        <li><a data-toggle="tab" href="#equipamentos">Equipamentos</a></li>
                        <li><a data-toggle="tab" href="#saude">Saúde e Segurança</a></li>
                        <li><a data-toggle="tab" href="#certificacoes">Certificações</a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Aba Dados Pessoais -->
                        <div id="pessoal" class="tab-pane active">
                            <div class="control-group">
                                <?php echo form_hidden('idUsuarios', $result->idUsuarios) ?>
                                <label for="nome" class="control-label">Nome<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="nome" type="text" name="nome" value="<?php echo $result->nome; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="rg" class="control-label">RG<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="rg" type="text" name="rg" value="<?php echo $result->rg; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="cpf" class="control-label">CPF<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="cpf" type="text" name="cpf" value="<?php echo $result->cpf; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="cep" class="control-label">CEP<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="cep" type="text" name="cep" value="<?php echo $result->cep; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="rua" class="control-label">Rua<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="rua" type="text" name="rua" value="<?php echo $result->rua; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="numero" class="control-label">Número<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="numero" type="text" name="numero" value="<?php echo $result->numero; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="bairro" class="control-label">Bairro<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="bairro" type="text" name="bairro" value="<?php echo $result->bairro; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="cidade" class="control-label">Cidade<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="cidade" type="text" name="cidade" value="<?php echo $result->cidade; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="estado" class="control-label">Estado<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="estado" type="text" name="estado" value="<?php echo $result->estado; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="email" class="control-label">Email<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="email" type="text" name="email" value="<?php echo $result->email; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="senha" class="control-label">Senha</label>
                                <div class="controls">
                                    <input id="senha" type="password" name="senha" value="" placeholder="Não preencha se não quiser alterar." />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="telefone" class="control-label">Telefone<span class="required">*</span></label>
                                <div class="controls">
                                    <input id="telefone" type="text" name="telefone" value="<?php echo $result->telefone; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="celular" class="control-label">Celular</label>
                                <div class="controls">
                                    <input id="celular" type="text" name="celular" value="<?php echo $result->celular; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="situacao" class="control-label">Situação<span class="required">*</span></label>
                                <div class="controls">
                                    <select name="situacao" id="situacao">
                                        <?php if ($result->situacao == 1) {
    $ativo = 'selected';
    $inativo = '';
} else {
    $ativo = '';
    $inativo = 'selected';
} ?>
                                        <option value="1" <?php echo $ativo; ?>>Ativo</option>
                                        <option value="0" <?php echo $inativo; ?>>Inativo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Permissões<span class="required">*</span></label>
                                <div class="controls">
                                    <select name="permissoes_id" id="permissoes_id">
                                        <?php foreach ($permissoes as $p) {
    if ($p->idPermissao == $result->permissoes_id) {
        $selected = 'selected';
    } else {
        $selected = '';
    }
    echo '<option value="' . $p->idPermissao . '"' . $selected . '>' . $p->nome . '</option>';
} ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Aba Equipamentos -->
                        <div id="equipamentos" class="tab-pane">
                            <div class="control-group">
                                <label for="tamanho_colete" class="control-label">Tamanho do Colete</label>
                                <div class="controls">
                                    <input id="tamanho_colete" type="text" name="tamanho_colete" value="<?= $result->tamanho_colete ?? '' ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="peso_lastro" class="control-label">Peso do Lastro (kg)</label>
                                <div class="controls">
                                    <input id="peso_lastro" type="text" name="peso_lastro" value="<?= $result->peso_lastro ?? '' ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="tamanho_neoprene" class="control-label">Tamanho do Neoprene</label>
                                <div class="controls">
                                    <input id="tamanho_neoprene" type="text" name="tamanho_neoprene" value="<?= $result->tamanho_neoprene ?? '' ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="tamanho_nadadeira" class="control-label">Tamanho da Nadadeira</label>
                                <div class="controls">
                                    <input id="tamanho_nadadeira" type="text" name="tamanho_nadadeira" value="<?= $result->tamanho_nadadeira ?? '' ?>" />
                                </div>
                            </div>
                        </div>

                        <!-- Aba Saúde e Segurança -->
                        <div id="saude" class="tab-pane">
                            <h4>Contato de Emergência</h4>
                            <div class="control-group">
                                <label for="contato_emergencia_nome" class="control-label">Nome</label>
                                <div class="controls">
                                    <input id="contato_emergencia_nome" type="text" name="contato_emergencia_nome" value="<?= $result->contato_emergencia_nome ?? '' ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="contato_emergencia_telefone" class="control-label">Telefone</label>
                                <div class="controls">
                                    <input id="contato_emergencia_telefone" type="text" name="contato_emergencia_telefone" value="<?= $result->contato_emergencia_telefone ?? '' ?>" class="telefone" />
                                </div>
                            </div>
                            <hr>
                            <h4>Atestado Médico</h4>
                            <div class="control-group">
                                <label for="atestado_medico_validade" class="control-label">Validade</label>
                                <div class="controls">
                                    <input id="atestado_medico_validade" type="date" name="atestado_medico_validade" value="<?= $result->atestado_medico_validade ?? '' ?>" />
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
                                <label for="arquivo_certificacao" class="control-label">Arquivo</label>
                                <div class="controls">
                                    <input id="arquivo_certificacao" type="file" name="arquivo_certificacao" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-primary"><span class="button__icon"><i class='bx bx-save'></i></span><span class="button__text2">Salvar</span></button>
                                <a href="<?php echo base_url() ?>index.php/usuarios" id="" class="button btn btn-warning"><span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
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
                rua: {
                    required: true
                },
                numero: {
                    required: true
                },
                bairro: {
                    required: true
                },
                cidade: {
                    required: true
                },
                estado: {
                    required: true
                },
                cep: {
                    required: true
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
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
                rua: {
                    required: 'Campo Requerido.'
                },
                numero: {
                    required: 'Campo Requerido.'
                },
                bairro: {
                    required: 'Campo Requerido.'
                },
                cidade: {
                    required: 'Campo Requerido.'
                },
                estado: {
                    required: 'Campo Requerido.'
                },
                cep: {
                    required: 'Campo Requerido.'
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

        $('#cep').mask('00000-000');
        $('.telefone').mask('(00) 0000-0000');

        $("#cep").blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep != "" && /^[0-9]{8}$/.test(cep)) {
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        $("#rua").val(dados.logradouro); $("#bairro").val(dados.bairro); $("#cidade").val(dados.localidade); $("#estado").val(dados.uf);
                    }
                });
            }
        });
    });
</script>