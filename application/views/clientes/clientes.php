<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>
<style>
    select {
        width: 70px;
    }
    /* Correção para o menu lateral permitir rolagem sem barra visível */
    #sidebar {
        position: fixed !important;
        top: 0;
        bottom: 0;
        left: 0;
        width: 220px !important;
        overflow-y: auto !important;
        padding-top: 60px !important;
        padding-bottom: 100px !important;
        z-index: 99;
        box-sizing: border-box !important;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE 10+ */
    }
    #sidebar::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }
</style>
<div class="new122">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <h5>Clientes</h5>
    </div>
    <div class="span12" style="margin-left: 0">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) { ?>
            <div class="span3">
                <a href="<?= base_url() ?>index.php/clientes/adicionar" class="button btn btn-mini btn-success"
                    style="max-width: 165px">
                    <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">
                        Cliente / Fornecedor
                    </span>
                </a>
            </div>
        <?php } ?>
        <form class="span9" method="get" action="<?= base_url() ?>index.php/clientes"
            style="display: flex; justify-content: flex-end;">
            <div class="span3">
                <input type="text" name="pesquisa" id="pesquisa"
                    placeholder="Buscar por Nome, Doc, Email ou Telefone..." class="span12"
                    value="<?= $this->input->get('pesquisa') ?>">
            </div>
            <div class="span1">
                <button class="button btn btn-mini btn-warning" style="min-width: 30px">
                    <span class="button__icon"><i class='bx bx-search-alt'></i></span></button>
            </div>
        </form>
    </div>

    <div class="widget-box">
        <h5 style="padding: 3px 0"></h5>
        <div class="widget-content nopadding tab-content">
            <table id="tabela" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Cod.</th>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>CPF/CNPJ</th>
                        <th>Telefone</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>Tipo</th> <!-- Nova coluna para Fornecedor/Cliente -->
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$results) {
                        echo '<tr>
                    <td colspan="9">Nenhum Cliente Cadastrado</td>
                  </tr>';
                    }
                    foreach ($results as $r) {
                        echo '<tr>';
                        echo '<td>' . $r->idClientes . '</td>';
                        $warning = '';
                        if (isset($r->importacao_inconsistente) && $r->importacao_inconsistente == 1) {
                            $warning = ' <i class="fas fa-exclamation-triangle" style="color: #f39c12;" title="Cliente com dados inconsistentes da importação (Email ou Documento gerados automaticamente)"></i>';
                        }
                        echo '<td><a href="' . base_url() . 'index.php/clientes/visualizar/' . $r->idClientes . '" style="margin-right: 1%">' . $r->nomeCliente . '</a>' . $warning . '</td>';
                        echo '<td>' . $r->contato . '</td>';
                        echo '<td>' . $r->documento . '</td>';
                        echo '<td>' . $r->telefone . '</td>';
                        echo '<td>' . $r->celular . '</td>';
                        echo '<td>' . $r->email . '</td>';

                        // Verifica se é Fornecedor ou Cliente
                        if ($r->fornecedor == 1) {
                            echo '<td><span class="label label-primary">Fornecedor</span></td>';
                        } else {
                            echo '<td><span class="label label-success">Cliente</span></td>';
                        }

                        echo '<td>';
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
                            echo '<a href="' . base_url() . 'index.php/clientes/visualizar/' . $r->idClientes . '" style="margin-right: 1%" class="btn-nwe" title="Ver mais detalhes"><i class="bx bx-show bx-xs"></i></a>';
                            echo '<a href="' . base_url() . 'index.php/mine?e=' . $r->email . '" target="new" style="margin-right: 1%" class="btn-nwe2" title="Área do cliente"><i class="bx bx-key bx-xs"></i></a>';
                        }
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
                            echo '<a href="' . base_url() . 'index.php/clientes/editar/' . $r->idClientes . '" style="margin-right: 1%" class="btn-nwe3" title="Editar Cliente"><i class="bx bx-edit bx-xs"></i></a>';
                        }
                        if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dCliente')) {
                            echo '<a href="javascript:void(0)" role="button" cliente="' . $r->idClientes . '" style="margin-right: 1%" class="btn-nwe4" title="Excluir Cliente"><i class="bx bx-trash-alt bx-xs"></i></a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>

<form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post" id="formExcluir" style="display: none;">
    <input type="hidden" id="idClienteExcluir" name="id" value="" />
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', 'a[title="Excluir Cliente"]', function (event) {
            event.preventDefault();
            var cliente = $(this).attr('cliente');
            $('#idClienteExcluir').val(cliente);

            Swal.fire({
                title: 'ATENÇÃO! Exclusão Irreversível',
                html: "Ao excluir este cliente, <b>TODOS</b> os dados vinculados serão apagados permanentemente:<br><br>" +
                      "<ul style='text-align: left; list-style-position: inside; margin-left: 20px;'>" +
                      "<li>Ordens de Serviço e Vendas</li>" +
                      "<li>Lançamentos Financeiros e Cobranças</li>" +
                      "<li>Agendamentos de Treinos</li>" +
                      "<li>Inscrições em Viagens e Cursos</li>" +
                      "</ul>" +
                      "<br><b>Esta ação não pode ser desfeita!</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir tudo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formExcluir').submit();
                }
            });
        });
    });
</script>