<div class="span12" style="margin-left: 0">
    <form action="<?php echo base_url(); ?>index.php/permissoes/adicionar" id="formPermissao" method="post">
        <div class="span12" style="margin-left: 0">
            <div class="widget-box">
                <div class="widget-title" style="margin: -20px 0 0">
                    <span class="icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <h5>Cadastro de Permissão</h5>
                </div>
                <div class="widget-content">
                    <div class="span6">
                        <label>Nome da Permissão</label>
                        <input name="nome" type="text" id="nome" class="span12" />
                    </div>
                    <div class="span6">
                        <br />
                        <label>
                            <input name="marcarTodos" type="checkbox" value="1" id="marcarTodos" />
                            <span class="lbl"> Marcar Todos</span>
                        </label>
                        <br />
                    </div>
                    <div class="accordion" id="collapse-group">
                        <div class="accordion-group widget-box">
                            <div class="accordion-heading">
                                <div class="widget-title">
                                    <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                        <span><i class='bx bx-group icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Clientes</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse in accordion-body" id="collapseGOne">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vCliente" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Cliente</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aCliente" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Cliente</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eCliente" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Cliente</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dCliente" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Cliente</span>
                                                    </label>
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
                                        <span><i class='bx bx-package icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Produtos</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGTwo">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vProduto" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Produto</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aProduto" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Produto</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eProduto" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Produto</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dProduto" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Produto</span>
                                                    </label>
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
                                        <span><i class='bx bx-stopwatch icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Serviços</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vServico" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Serviço</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aServico" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Serviço</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eServico" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Serviço</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dServico" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Serviço</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGThree3" data-toggle="collapse">
                                        <span><i class='bx bx-spreadsheet icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Ordem de Serviços - OS</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree3">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vOs" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar OS</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aOs" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Adicionar OS</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eOs" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Editar OS</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dOs" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Excluir OS</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGThree33" data-toggle="collapse">
                                        <span><i class='bx bx-cart-alt icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Vendas</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree33">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vVenda" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Venda</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aVenda" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Adicionar Venda</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eVenda" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Editar Venda</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dVenda" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Excluir Venda</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGThree333" data-toggle="collapse">
                                        <span><i class='bx bx-credit-card-front icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Cobranças</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree333">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vCobranca" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Cobranças</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aCobranca" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Cobranças</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eCobranca" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Cobranças</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dCobranca" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Cobranças</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGThree3333" data-toggle="collapse">
                                        <span><i class='bx bx-receipt icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Garantias</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree3333">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vGarantia" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Garantia</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aGarantia" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Garantia</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eGarantia" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Garantia</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dGarantia" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Garantia</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGThree33333" data-toggle="collapse">
                                        <span><i class='bx bx-box icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Arquivos</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree33333">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vArquivo" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Arquivo</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aArquivo" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Arquivo</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eArquivo" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Arquivo</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dArquivo" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Arquivo</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGThree333343"
                                        data-toggle="collapse">
                                        <span><i class="bx bx-bar-chart-square icon-cli"></i></span>
                                        <h5 style="padding-left: 28px">Financeiro</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree333343">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vPagamento" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Pagamento</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aPagamento" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Pagamento</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="ePagamento" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Pagamento</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dPagamento" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Pagamento</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vLancamento" class="marcar" type="checkbox"
                                                            checked="checked" value="1" />
                                                        <span class="lbl"> Visualizar Lançamento</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aLancamento" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Lançamento</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eLancamento" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Lançamento</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dLancamento" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Lançamento</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="faturarAtribuicao" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Faturar Atribuição</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eEstorno" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Estornar Pagamento</span>
                                                    </label>
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group widget-box">
                            <div class="accordion-heading">
                                <div class="widget-title">
                                    <a data-parent="#collapse-group" href="#collapseGThree333335"
                                        data-toggle="collapse">
                                        <span><i class="bx bx-chart icon-cli"></i></span>
                                        <h5 style="padding-left: 28px">Relatórios</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree333335">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="rCliente" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Relatório Cliente</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="rServico" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Relatório Serviço</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="rOs" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Relatório OS</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="rProduto" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Relatório Produto</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="rVenda" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Relatório Venda</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="rFinanceiro" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Relatório Financeiro</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGTreino" data-toggle="collapse">
                                        <span><i class='bx bx-dumbbell icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Treinos</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGTreino">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vTreino" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Visualizar Treinos</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aTreino" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Adicionar Treinos</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eTreino" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Editar Treinos</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dTreino" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Excluir Treinos</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGCurso" data-toggle="collapse">
                                        <span><i class='fas fa-graduation-cap icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Cursos</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGCurso">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><label><input name="vCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Visualizar Curso</span></label></td>
                                                <td><label><input name="aCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Adicionar Curso</span></label></td>
                                                <td><label><input name="eCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Curso</span></label></td>
                                                <td><label><input name="dCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Curso</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="aAlunoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Aluno</span></label></td>
                                                <td><label><input name="dAlunoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Aluno</span></label></td>
                                                <td><label><input name="aInstrutorCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Instrutor</span></label></td>
                                                <td><label><input name="dInstrutorCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Instrutor</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="aModuloCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Módulo</span></label></td>
                                                <td><label><input name="dModuloCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Módulo</span></label></td>
                                                <td><label><input name="eConclusaoModulo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Concluir Módulo</span></label></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="aInstrutorModulo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Instrutor Aula</span></label></td>
                                                <td><label><input name="dInstrutorModulo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Instrutor Aula</span></label></td>
                                                <td><label><input name="aRequisitoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Requisito</span></label></td>
                                                <td><label><input name="dRequisitoCurso" class="marcar" type="checkbox" value="1" /><span class="lbl"> Del Requisito</span></label></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group widget-box">
                            <div class="accordion-heading">
                                <div class="widget-title">
                                    <a data-parent="#collapse-group" href="#collapseGViagem" data-toggle="collapse">
                                        <span><i class='fas fa-route icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Viagens</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGViagem">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><label><input name="vViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Visualizar Viagem</span></label></td>
                                                <td><label><input name="aViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Adicionar Viagem</span></label></td>
                                                <td><label><input name="eViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Viagem</span></label></td>
                                                <td><label><input name="dViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Viagem</span></label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vClienteViagem" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Ver Clientes na Viagem</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aClienteViagem" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Add Clientes na Viagem</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eClienteViagem" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Editar Clientes na Viagem</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dClienteViagem" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Remover Clientes na Viagem</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="aInstrutorViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Instrutor na Viagem</span></label></td>
                                                <td><label><input name="dInstrutorViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Remover Instrutor na Viagem</span></label></td>
                                                <td><label><input name="vFichaOperacao" class="marcar" type="checkbox" value="1" /><span class="lbl"> Emitir Ficha Operação</span></label></td>
                                                <td><label><input name="vFichaViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Emitir Ficha Viagem</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="aCustoViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Add Custo Extra</span></label></td>
                                                <td><label><input name="dCustoViagem" class="marcar" type="checkbox" value="1" /><span class="lbl"> Remover Custo Extra</span></label></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group widget-box">
                            <div class="accordion-heading">
                                <div class="widget-title">
                                    <a data-parent="#collapse-group" href="#collapseGAtivos" data-toggle="collapse">
                                        <span><i class='fas fa-box icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Ativos</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGAtivos">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="vAtivo" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Visualizar Ativo</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="aAtivo" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Adicionar Ativo</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="eAtivo" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Editar Ativo</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="dAtivo" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Excluir Ativo</span>
                                                    </label>
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
                                    <a data-parent="#collapse-group" href="#collapseGBilhetagem" data-toggle="collapse">
                                        <span><i class='fas fa-ticket-alt icon-cli'></i></span>
                                        <h5 style="padding-left: 28px">Bilhetagem</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGBilhetagem">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><label><input name="vBilhete" class="marcar" type="checkbox" value="1" /><span class="lbl"> Visualizar Bilhete</span></label></td>
                                                <td><label><input name="aBilhete" class="marcar" type="checkbox" value="1" /><span class="lbl"> Adicionar Bilhete</span></label></td>
                                                <td><label><input name="eBilhete" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Bilhete</span></label></td>
                                                <td><label><input name="dBilhete" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Bilhete</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="vTransporte" class="marcar" type="checkbox" value="1" /><span class="lbl"> Visualizar Transporte</span></label></td>
                                                <td><label><input name="aTransporte" class="marcar" type="checkbox" value="1" /><span class="lbl"> Adicionar Transporte</span></label></td>
                                                <td><label><input name="eTransporte" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Transporte</span></label></td>
                                                <td><label><input name="dTransporte" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Transporte</span></label></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group widget-box">
                            <div class="accordion-heading">
                                <div class="widget-title">
                                    <a data-parent="#collapse-group" href="#collapseGThree333338"
                                        data-toggle="collapse">
                                        <span><i class="bx bx-cog icon-cli"></i></span>
                                        <h5 style="padding-left: 28px">Configurações e Sistema</h5>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse accordion-body" id="collapseGThree333338">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="cUsuario" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Configurar Usuário</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="cEmitente" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Configurar Emitente</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="cPermissao" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Configurar Permissão</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="cBackup" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Backup</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="cAuditoria" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Auditoria</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="cEmail" class="marcar" type="checkbox" value="1" />
                                                        <span class="lbl"> Emails</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="cSistema" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Sistema</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input name="cIntegracao" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Integrações</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input name="aImportar" class="marcar" type="checkbox"
                                                            value="1" />
                                                        <span class="lbl"> Importar Clientes</span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="vAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Visualizar Categoria</span></label></td>
                                                <td><label><input name="aAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Adicionar Categoria</span></label></td>
                                                <td><label><input name="eAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Categoria</span></label></td>
                                                <td><label><input name="dAtivoCategoria" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Categoria</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="aBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Criar Bolsa</span></label></td>
                                                <td><label><input name="eBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Editar Bolsa</span></label></td>
                                                <td><label><input name="dBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Bolsa</span></label></td>
                                                <td><label><input name="mItensBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Gerenciar Itens Bolsa</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="mMovimentacao" class="marcar" type="checkbox" value="1" /><span class="lbl"> Movimentação</span></label></td>
                                                <td><label><input name="cCheckinCheckout" class="marcar" type="checkbox" value="1" /><span class="lbl"> Check-in / Check-out</span></label></td>
                                                <td><label><input name="rEntregarBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Receber / Entregar Bolsa</span></label></td>
                                                <td><label><input name="rBolsaSemConferir" class="marcar" type="checkbox" value="1" /><span class="lbl"> Receber s/ Conferir</span></label></td>
                                            </tr>
                                            <tr>
                                                <td><label><input name="tResponsavelBolsa" class="marcar" type="checkbox" value="1" /><span class="lbl"> Trocar Responsável</span></label></td>
                                                <td><label><input name="dAuditoriaAtivo" class="marcar" type="checkbox" value="1" /><span class="lbl"> Excluir Auditoria</span></label></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display:flex;justify-content: center">
                                <button type="submit" class="button btn btn-success"><span class="button__icon"><i
                                            class='bx bx-plus-circle'></i></span><span
                                        class="button__text2">Confirmar</span></button>
                                <a title="Voltar" class="button btn btn-mini btn-warning"
                                    href="<?php echo site_url() ?>/permissoes">
                                    <span class="button__icon"><i class="bx bx-undo"></i></span> <span
                                        class="button__text2">Voltar</span></a>
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
                nome: {
                    required: true
                }
            },
            messages: {
                nome: {
                    required: 'Campo obrigatório'
                }
            }
        });
    });
</script>