<style>
    .widget-title h5 {
        font-weight: 500;
        padding: 5px;
        padding-left: 36px !important;
        line-height: 12px;
        margin: 5px 0 !important;
        font-size: 1.3em;
        color: var(--violeta1);
    }

    .icon-cli {
        color: #239683;
        margin-top: 3px;
        margin-left: 8px;
        position: absolute;
        font-size: 18px;
    }

    .icon-clic {
        color: #9faab7;
        top: 4px;
        right: 10px;
        position: absolute;
        font-size: 1.9em;
    }

    .icon-clic:hover {
        color: #3fadf6;
    }

    .widget-content {
        padding: 8px 12px 0;
    }

    .table td {
        padding: 5px;
    }

    .table {
        margin-bottom: 0;
    }

    .accordion .widget-box {
        margin-top: 10px;
        margin-bottom: 0;
        border-radius: 6px;
    }

    .accordion {
        margin-top: -25px;
    }

    .collapse.in {
        top: -15px
    }

    .button {
        min-width: 130px;
    }

    .form-actions {
        padding: 0;
        margin-top: 20px;
        margin-bottom: 20px;
        background-color: transparent;
        border-top: 0px;
    }

    .widget-content table tbody tr:hover {
        background: transparent;
    }

    @media (max-width: 480px) {
        .widget-content {
            padding: 10px 7px !important;
            margin-bottom: -15px;
        }
    }
</style>

<?php $permissoes = unserialize($result->permissoes); ?>
<div class="span12" style="margin-left: 0">
    <form action="<?php echo base_url(); ?>index.php/permissoes/editar" id="formPermissao" method="post">
        <div class="span12" style="margin-left: 0">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <h5 style="padding:12px;padding-left:18px!important;margin:-10px 0 0!important;font-size:1.7em;">
                        Editar Permissão</h5>
                </div>
                <div class="widget-content">
                    <div class="span4">
                        <label>Nome da Permissão</label>
                        <input name="nome" type="text" id="nome" class="span12" value="<?php echo $result->nome; ?>" />
                        <input type="hidden" name="idPermissao" value="<?php echo $result->idPermissao; ?>">
                    </div>
                    <div class="span3">
                        <label>Situação</label>
                        <select name="situacao" id="situacao" class="span12">
                            <?php if ($result->situacao == 1) {
                                $sim = 'selected';
                                $nao = '';
                            } else {
                                $sim = '';
                                $nao = 'selected';
                            } ?>
                            <option value="1" <?php echo $sim; ?>>Ativo</option>
                            <option value="0" <?php echo $nao; ?>>Inativo</option>
                        </select>
                    </div>
                    <div class="span4">
                        <label>
                            <input name="" type="checkbox" value="1" id="marcarTodos" />
                            <span class="lbl"> Marcar Todos</span>
                        </label>
                    </div>

                    <div class="control-group">
                        <label for="documento" class="control-label"></label>
                        <div class="controls">

                            <div class="widget-content" style="padding: 5px 0 !important">
                                <div id="tab1" class="tab-pane active" style="min-height: 300px">
                                    <div class="accordion" id="collapse-group">
                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGOne"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-group icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Clientes</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse in accordion-body" id="collapseGOne">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label......>
                                                                    <input <?php if (isset($permissoes['vCliente'])) {
                                                                        if ($permissoes['vCliente'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vCliente" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Cliente</span>
                                                                    </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aCliente'])) {
                                                                        if ($permissoes['aCliente'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aCliente" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Cliente</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eCliente'])) {
                                                                        if ($permissoes['eCliente'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eCliente" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Cliente</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dCliente'])) {
                                                                        if ($permissoes['dCliente'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dCliente" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Cliente</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aImportar'])) {
                                                                        if ($permissoes['aImportar'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aImportar"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Importar Clientes</span>
                                                                </label>
                                                            </td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGTwo"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-package icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Produtos</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGTwo">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vProduto'])) {
                                                                        if ($permissoes['vProduto'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vProduto" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Produto</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aProduto'])) {
                                                                        if ($permissoes['aProduto'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aProduto" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Produto</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eProduto'])) {
                                                                        if ($permissoes['eProduto'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eProduto" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Produto</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dProduto'])) {
                                                                        if ($permissoes['dProduto'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dProduto" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Produto</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGTreino"
                                                        data-toggle="collapse">
                                                        <span><i class='fas fa-dumbbell icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Treinos</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGTreino">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vTreino']) && $permissoes['vTreino'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="vTreino" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Visualizar Treino</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aTreino']) && $permissoes['aTreino'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="aTreino" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Adicionar Treino</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eTreino']) && $permissoes['eTreino'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="eTreino" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Editar Treino</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dTreino']) && $permissoes['dTreino'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="dTreino" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Excluir Treino</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input name="ePagamento" class="marcar" type="checkbox"
                                                                        <?php if (isset($permissoes['ePagamento']) && $permissoes['ePagamento'] == '1') {
                                                                            echo 'checked';
                                                                        } ?>
                                                                        value="1" />
                                                                    <span class="lbl"> Editar Pagamento</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input name="dPagamento" class="marcar" type="checkbox"
                                                                        <?php if (isset($permissoes['dPagamento']) && $permissoes['dPagamento'] == '1') {
                                                                            echo 'checked';
                                                                        } ?>
                                                                        value="1" />
                                                                    <span class="lbl"> Excluir Pagamento</span>
                                                                </label>
                                                            </td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGThree"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-stopwatch icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Serviços</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGThree">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vServico'])) {
                                                                        if ($permissoes['vServico'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vServico" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Serviço</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aServico'])) {
                                                                        if ($permissoes['aServico'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aServico" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Serviço</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eServico'])) {
                                                                        if ($permissoes['eServico'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eServico" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Serviço</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dServico'])) {
                                                                        if ($permissoes['dServico'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dServico" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Serviço</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGFour"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-spreadsheet icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Ordens de Serviço</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGFour">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vOs'])) {
                                                                        if ($permissoes['vOs'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vOs" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Visualizar OS</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aOs'])) {
                                                                        if ($permissoes['aOs'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aOs" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Adicionar OS</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eOs'])) {
                                                                        if ($permissoes['eOs'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eOs" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Editar OS</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dOs'])) {
                                                                        if ($permissoes['dOs'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dOs" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Excluir OS</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGFive"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-cart-alt icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Vendas</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGFive">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vVenda'])) {
                                                                        if ($permissoes['vVenda'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vVenda" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Visualizar Venda</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aVenda'])) {
                                                                        if ($permissoes['aVenda'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aVenda" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Adicionar Venda</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eVenda'])) {
                                                                        if ($permissoes['eVenda'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eVenda" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Editar Venda</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dVenda'])) {
                                                                        if ($permissoes['dVenda'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dVenda" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Excluir Venda</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGSix"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-credit-card-front icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Cobranças</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGSix">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vCobranca'])) {
                                                                        if ($permissoes['vCobranca'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vCobranca" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Cobranças</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aCobranca'])) {
                                                                        if ($permissoes['aCobranca'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aCobranca" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Cobranças</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eCobranca'])) {
                                                                        if ($permissoes['eCobranca'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eCobranca" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Cobranças</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dCobranca'])) {
                                                                        if ($permissoes['dCobranca'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dCobranca" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Cobranças</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGSeven"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-receipt icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Garantias</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGSeven">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vGarantia'])) {
                                                                        if ($permissoes['vGarantia'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vGarantia" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Garantia</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aGarantia'])) {
                                                                        if ($permissoes['aGarantia'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aGarantia" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Garantia</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eGarantia'])) {
                                                                        if ($permissoes['eGarantia'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eGarantia" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Garantia</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dGarantia'])) {
                                                                        if ($permissoes['dGarantia'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dGarantia" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Garantia</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGEight"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-box icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Arquivos</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGEight">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vArquivo'])) {
                                                                        if ($permissoes['vArquivo'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vArquivo" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Arquivo</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aArquivo'])) {
                                                                        if ($permissoes['aArquivo'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aArquivo" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Arquivo</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eArquivo'])) {
                                                                        if ($permissoes['eArquivo'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eArquivo" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Arquivo</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dArquivo'])) {
                                                                        if ($permissoes['dArquivo'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dArquivo" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Arquivo</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGNine"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-bar-chart-square icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Financeiro</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGNine">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vLancamento'])) {
                                                                        if ($permissoes['vLancamento'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="vLancamento"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Lançamento</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aLancamento'])) {
                                                                        if ($permissoes['aLancamento'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="aLancamento"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Lançamento</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eLancamento'])) {
                                                                        if ($permissoes['eLancamento'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="eLancamento"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Lançamento</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dLancamento'])) {
                                                                        if ($permissoes['dLancamento'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="dLancamento"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Lançamento</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['faturarAtribuicao'])) {
                                                                        if ($permissoes['faturarAtribuicao'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="faturarAtribuicao"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Faturar Atribuição</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input name="eEstorno" class="marcar" type="checkbox"
                                                                        <?php if (isset($permissoes['eEstorno']) && $permissoes['eEstorno'] == '1') {
                                                                            echo 'checked';
                                                                        } ?>
                                                                        value="1" />
                                                                    <span class="lbl"> Estornar Pagamento</span>
                                                                </label>
                                                            </td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGCurso"
                                                        data-toggle="collapse">
                                                        <span><i class='fas fa-graduation-cap icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Cursos</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGCurso">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vCurso']) && $permissoes['vCurso'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="vCurso" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Visualizar Curso</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aCurso']) && $permissoes['aCurso'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="aCurso" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Adicionar Curso</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eCurso']) && $permissoes['eCurso'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="eCurso" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Editar Curso</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dCurso']) && $permissoes['dCurso'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="dCurso" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Excluir Curso</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['aAlunoCurso']) && $permissoes['aAlunoCurso'] == '1') { echo 'checked'; } ?> name="aAlunoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Aluno</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dAlunoCurso']) && $permissoes['dAlunoCurso'] == '1') { echo 'checked'; } ?> name="dAlunoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Aluno</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['aInstrutorCurso']) && $permissoes['aInstrutorCurso'] == '1') { echo 'checked'; } ?> name="aInstrutorCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Instrutor</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dInstrutorCurso']) && $permissoes['dInstrutorCurso'] == '1') { echo 'checked'; } ?> name="dInstrutorCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Instrutor</span></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['aModuloCurso']) && $permissoes['aModuloCurso'] == '1') { echo 'checked'; } ?> name="aModuloCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Módulo</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dModuloCurso']) && $permissoes['dModuloCurso'] == '1') { echo 'checked'; } ?> name="dModuloCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Módulo</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['eConclusaoModulo']) && $permissoes['eConclusaoModulo'] == '1') { echo 'checked'; } ?> name="eConclusaoModulo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Concluir Módulo</span></label></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['aInstrutorModulo']) && $permissoes['aInstrutorModulo'] == '1') { echo 'checked'; } ?> name="aInstrutorModulo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Instrutor Aula</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dInstrutorModulo']) && $permissoes['dInstrutorModulo'] == '1') { echo 'checked'; } ?> name="dInstrutorModulo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Instrutor Aula</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['aRequisitoCurso']) && $permissoes['aRequisitoCurso'] == '1') { echo 'checked'; } ?> name="aRequisitoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Requisito</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dRequisitoCurso']) && $permissoes['dRequisitoCurso'] == '1') { echo 'checked'; } ?> name="dRequisitoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Requisito</span></label></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGViagem"
                                                        data-toggle="collapse">
                                                        <span><i class='fas fa-route icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Viagens</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGViagem">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vViagem']) && $permissoes['vViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="vViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Visualizar Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aViagem']) && $permissoes['aViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="aViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Adicionar Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eViagem']) && $permissoes['eViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="eViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Editar Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dViagem']) && $permissoes['dViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="dViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Excluir Viagem</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vClienteViagem']) && $permissoes['vClienteViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="vClienteViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Ver Clientes na Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aClienteViagem']) && $permissoes['aClienteViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="aClienteViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Add Clientes na Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eClienteViagem']) && $permissoes['eClienteViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="eClienteViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Editar Clientes na Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dClienteViagem']) && $permissoes['dClienteViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="dClienteViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Remover Clientes na Viagem</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aInstrutorViagem']) && $permissoes['aInstrutorViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="aInstrutorViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Add Instrutor na Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dInstrutorViagem']) && $permissoes['dInstrutorViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="dInstrutorViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Remover Instrutor na Viagem</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vFichaOperacao']) && $permissoes['vFichaOperacao'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="vFichaOperacao" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Emitir Ficha Operação</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vFichaViagem']) && $permissoes['vFichaViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="vFichaViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Emitir Ficha Viagem</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aCustoViagem']) && $permissoes['aCustoViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="aCustoViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Add Custo Extra</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dCustoViagem']) && $permissoes['dCustoViagem'] == '1') {
                                                                        echo 'checked';
                                                                    } ?> name="dCustoViagem" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Remover Custo Extra</span>
                                                                </label>
                                                            </td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGTen"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-chart icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Relatórios</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGTen">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['rCliente'])) {
                                                                        if ($permissoes['rCliente'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="rCliente" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Relatório Cliente</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['rServico'])) {
                                                                        if ($permissoes['rServico'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="rServico" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Relatório Serviço</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['rOs'])) {
                                                                        if ($permissoes['rOs'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="rOs" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Relatório OS</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['rProduto'])) {
                                                                        if ($permissoes['rProduto'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="rProduto" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Relatório Produto</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['rVenda'])) {
                                                                        if ($permissoes['rVenda'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="rVenda" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Relatório Venda</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['rFinanceiro'])) {
                                                                        if ($permissoes['rFinanceiro'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="rFinanceiro"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Relatório Financeiro</span>
                                                                </label>
                                                            </td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGAtivos"
                                                        data-toggle="collapse">
                                                        <span><i class='fas fa-box icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Ativos</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGAtivos">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['vAtivo']) && $permissoes['vAtivo'] == '1') { echo 'checked'; } ?> name="vAtivo" class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Visualizar Ativo</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['aAtivo']) && $permissoes['aAtivo'] == '1') { echo 'checked'; } ?> name="aAtivo" class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Adicionar Ativo</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['eAtivo']) && $permissoes['eAtivo'] == '1') { echo 'checked'; } ?> name="eAtivo" class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Editar Ativo</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['dAtivo']) && $permissoes['dAtivo'] == '1') { echo 'checked'; } ?> name="dAtivo" class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Excluir Ativo</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['vAtivoCategoria']) && $permissoes['vAtivoCategoria'] == '1') { echo 'checked'; } ?> name="vAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Visualizar Categoria</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['aAtivoCategoria']) && $permissoes['aAtivoCategoria'] == '1') { echo 'checked'; } ?> name="aAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Adicionar Categoria</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['eAtivoCategoria']) && $permissoes['eAtivoCategoria'] == '1') { echo 'checked'; } ?> name="eAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Categoria</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dAtivoCategoria']) && $permissoes['dAtivoCategoria'] == '1') { echo 'checked'; } ?> name="dAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Categoria</span></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['aBolsa']) && $permissoes['aBolsa'] == '1') { echo 'checked'; } ?> name="aBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Criar Bolsa</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['eBolsa']) && $permissoes['eBolsa'] == '1') { echo 'checked'; } ?> name="eBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Bolsa</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dBolsa']) && $permissoes['dBolsa'] == '1') { echo 'checked'; } ?> name="dBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Bolsa</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['mItensBolsa']) && $permissoes['mItensBolsa'] == '1') { echo 'checked'; } ?> name="mItensBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Gerenciar Itens Bolsa</span></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['mMovimentacao']) && $permissoes['mMovimentacao'] == '1') { echo 'checked'; } ?> name="mMovimentacao" class="marcar" type="checkbox" value="1" /><span class="lbl"> Movimentação</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['cCheckinCheckout']) && $permissoes['cCheckinCheckout'] == '1') { echo 'checked'; } ?> name="cCheckinCheckout" class="marcar" type="checkbox" value="1" /><span class="lbl"> Check-in / Check-out</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['rEntregarBolsa']) && $permissoes['rEntregarBolsa'] == '1') { echo 'checked'; } ?> name="rEntregarBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Receber / Entregar Bolsa</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['rBolsaSemConferir']) && $permissoes['rBolsaSemConferir'] == '1') { echo 'checked'; } ?> name="rBolsaSemConferir" class="marcar" type="checkbox" value="1" /><span class="lbl"> Receber s/ Conferir</span></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><label><input <?php if (isset($permissoes['tResponsavelBolsa']) && $permissoes['tResponsavelBolsa'] == '1') { echo 'checked'; } ?> name="tResponsavelBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Trocar Responsável</span></label></td>
                                                            <td><label><input <?php if (isset($permissoes['dAuditoriaAtivo']) && $permissoes['dAuditoriaAtivo'] == '1') { echo 'checked'; } ?> name="dAuditoriaAtivo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Auditoria</span></label></td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-group widget-box">
                                            <div class="accordion-heading">
                                                <div class="widget-title">
                                                    <a data-parent="#collapse-group" href="#collapseGEleven"
                                                        data-toggle="collapse">
                                                        <span><i class='bx bx-cog icon-cli'></i></span>
                                                        <h5 style="padding-left: 28px">Configurações e Sistema</h5>
                                                        <span><i class='bx bx-chevron-right icon-clic'></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse accordion-body" id="collapseGEleven">
                                                <div class="widget-content">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td colspan="4"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['cUsuario'])) {
                                                                        if ($permissoes['cUsuario'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="cUsuario" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Configurar Usuário</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['cEmitente'])) {
                                                                        if ($permissoes['cEmitente'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="cEmitente" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Configurar Emitente</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['cPermissao'])) {
                                                                        if ($permissoes['cPermissao'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="cPermissao"
                                                                        class="marcar" type="checkbox" value="1" />
                                                                    <span class="lbl"> Configurar Permissão</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php if (isset($permissoes['cBackup'])) {
                                                                        if ($permissoes['cBackup'] == '1') {
                                                                            echo 'checked';
                                                                        }
                                                                    } ?> name="cBackup" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Backup</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <label>
                                                                    <input <?php echo (isset($permissoes['cAuditoria']) && $permissoes['cAuditoria'] == 1) ? 'checked' : ''; ?> name="cAuditoria" class="marcar"
                                                                        type="checkbox" value="1" />
                                                                    <span class="lbl"> Auditoria</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php echo (isset($permissoes['cEmail']) && $permissoes['cEmail'] == 1) ? 'checked' : ''; ?>
                                                                        name="cEmail" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Emails</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php echo (isset($permissoes['cSistema']) && $permissoes['cSistema'] == 1) ? 'checked' : ''; ?>
                                                                        name="cSistema" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Sistema</span>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <label>
                                                                    <input <?php echo (isset($permissoes['cIntegracao']) && $permissoes['cIntegracao'] == 1) ? 'checked' : ''; ?>
                                                                        name="cIntegracao" class="marcar" type="checkbox"
                                                                        value="1" />
                                                                    <span class="lbl"> Integrações</span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <div class="span12">
                                                <div class="span6 offset3" style="display:flex;justify-content: center">
                                                    <button type="submit" class="button btn btn-primary">
                                                        <span class="button__icon"><i
                                                                class='bx bx-save'></i></span><span
                                                            class="button__text2">Salvar</span></button>
                                                    <a title="Voltar" class="button btn btn-mini btn-warning"
                                                        href="<?php echo site_url() ?>/permissoes">
                                                        <span class="button__icon"><i class="bx bx-undo"></i></span>
                                                        <span class="button__text2">Voltar</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    </form>
</div>


<script type="text/javascript" src="<?php echo base_url() ?>assets/js/validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#marcarTodos").change(function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
        $("#formPermissao").validate({
            rules: {
                nome: { required: true }
            },
            messages: {
                nome: { required: 'Campo obrigatório' }
            }
        });
    });
</script>