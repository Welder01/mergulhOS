<!--sidebar-menu-->
<style>
    #sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 220px;
        border-right: 1px solid #e7e7e7;
        z-index: 1000;
        transition: all 0.3s ease;
    }
    #content {
        margin-left: 220px !important;
        transition: all 0.3s ease;
    }
    /* Desativa transições durante o redimensionamento para evitar bugs de renderização */
    body.is-resizing #sidebar,
    body.is-resizing #content {
        transition: none !important;
    }
    .menu-bar {
        height: calc(100vh - 150px);
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        width: 100%;
    }
    .menu-bar::-webkit-scrollbar {
        display: none;
    }
    #sidebar .menu-links li {
        position: relative;
    }
    /* Estilos para o menu recolhido */
    #sidebar.hide-sidebar {
        width: 70px !important;
    }
    #content.hide-sidebar {
        margin-left: 70px !important;
    }
    #sidebar.hide-sidebar .text, 
    #sidebar.hide-sidebar .title, 
    #sidebar.hide-sidebar .search-box,
    #sidebar.hide-sidebar #newlog .title1 {
        display: none;
    }
    #sidebar.hide-sidebar .menu-links li a {
        text-align: center;
        padding: 10px 0 !important;
        display: flex !important;
        justify-content: center;
        align-items: center;
    }
    #sidebar.hide-sidebar .menu-links li a i {
        margin: 0 !important;
        font-size: 20px;
    }
    /* Tooltip Flutuante no modo recolhido */
    .title-tooltip {
        display: none;
        position: fixed;
        left: 70px;
        background-color: #2E363F;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 1001;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .toggle-menu {
        position: fixed;
        top: 15px;
        left: 202px;
        z-index: 10002;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background-color: #2E363F;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        color: #fff;
        transition: left 0.3s ease;
        text-decoration: none;
    }
    .mode { cursor: pointer; }

    .menu-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
    }

    @media (max-width: 767px) {
        #sidebar { 
            display: block !important; 
            position: fixed !important;
            left: -220px !important;
            top: 0 !important;
            bottom: 0 !important;
            z-index: 10001 !important;
            background-color: #2E363F !important; /* Garante fundo escuro no mobile */
            width: 220px !important;
            transition: left 0.3s ease !important;
            transform: none !important;
        }
        #sidebar.visible-on-mobile { 
            left: 0 !important;
        }
        #content { margin-left: 0 !important; }
        .toggle-menu {
            left: 10px;
            top: 100px;
            background-color: #2E363F;
            color: #fff;
            border-radius: 50%;
            padding: 0;
        }
        /* Forçar exibição dos itens no mobile quando o menu estiver aberto */
        #sidebar.visible-on-mobile .text,
        #sidebar.visible-on-mobile .title {
            display: inline-block !important;
        }
        #sidebar.visible-on-mobile .search-box,
        #sidebar.visible-on-mobile #newlog .title1 {
            display: block !important;
        }
    }
    /* Fix para Tablet/Zoom: Forçar recolhimento visualmente via CSS para evitar delay do JS */
    @media (min-width: 768px) and (max-width: 1280px) {
        #sidebar.hide-sidebar { width: 70px !important; }
        #content.hide-sidebar { margin-left: 70px !important; }
        #sidebar.hide-sidebar .text, 
        #sidebar.hide-sidebar .title, 
        #sidebar.hide-sidebar .search-box, 
        #sidebar.hide-sidebar #newlog .title1 {
            display: none !important;
        }
        #sidebar.hide-sidebar .menu-links li a {
            padding: 10px 0 !important;
            display: flex !important;
            justify-content: center;
            align-items: center;
        }
        #sidebar.hide-sidebar .menu-links li a i {
            margin: 0 !important;
        }
        
        /* Forçar exibição quando expandido neste modo */
        #sidebar:not(.hide-sidebar) {
            width: 220px !important;
        }
        #content:not(.hide-sidebar) {
            margin-left: 220px !important;
        }
        #sidebar:not(.hide-sidebar) .text,
        #sidebar:not(.hide-sidebar) .title {
            display: inline-block !important;
        }
        #sidebar:not(.hide-sidebar) .search-box,
        #sidebar:not(.hide-sidebar) #newlog .title1 {
            display: block !important;
        }
        #sidebar:not(.hide-sidebar) .menu-links li a {
            text-align: left !important;
            padding: 10px 15px !important;
        }
        #sidebar:not(.hide-sidebar) .menu-links li a i {
            margin-right: 10px !important;
        }
    }
</style>
<a href="#" class="toggle-menu">
    <div class="mode">
        <div class="moon-menu">
            <i class='bx bx-chevron-right iconX open-2'></i>
            <i class='bx bx-chevron-left iconX close-2'></i>
        </div>
    </div>
</a>
<div class="menu-overlay"></div>
<nav id="sidebar">
    <div id="newlog">
        <div class="icon2">
            <img src="<?php echo base_url() ?>assets/img/logo-two.png">
        </div>
        <div class="title1">
            <?= $configuration['app_theme'] == 'white' || $configuration['app_theme'] == 'whitegreen' ? '<img src="' . base_url() . 'assets/img/logo-mapos.png">' : '<img src="' . base_url() . 'assets/img/logo-mapos-branco.png">'; ?>
        </div>
    </div>

    <div class="menu-bar">
        <div class="menu">

            <ul class="menu-links" style="position: relative;">
                <!-- Start Pesquisar-->
                <li class="search-box">
                    <form style="display: flex" action="<?= site_url('mapos/pesquisar') ?>">
                        <button style="background:transparent;border:transparent" type="submit" class="tip-bottom" title="">
                            <i class='bx bx-search iconX'></i></button>
                        <input
                            style="background:transparent;<?= $configuration['app_theme'] == 'white' ? 'color:#313030;' : 'color:#fff;' ?>border:transparent"
                            type="search" name="termo" placeholder="Pesquise aqui...">
                        <span class="title-tooltip">Pesquisar</span>
                    </form>
                </li>
                <!-- End Pesquisar-->

                <li class="<?php if (isset($menuPainel)) {
                    echo 'active';
                }
                ; ?>">
                    <a class="tip-bottom" title="" href="<?= base_url() ?>"><i class='bx bx-home-alt iconX'></i>
                        <span class="title nav-title">Home</span>
                        <span class="title-tooltip">Início</span>
                    </a>
                </li>

                <li class="<?php if (isset($menuAtividades)) {
                    echo 'active';
                }
                ; ?>">
                    <a class="tip-bottom" title="" href="<?= site_url('atividades') ?>"><i
                            class='fas fa-calendar-check iconX'></i>
                        <span class="title">Minhas Atividades</span>
                        <span class="title-tooltip">Atividades</span>
                    </a>
                </li>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) { ?>
                    <li class="<?php if (isset($menuClientes)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('clientes') ?>"><i class='bx bx-user iconX'></i>
                            <span class="title">Cliente / Fornecedor</span>
                            <span class="title-tooltip">Clientes</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) { ?>
                    <li class="<?php if (isset($menuProdutos)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('produtos') ?>"><i class='bx bx-basket iconX'></i>
                            <span class="title">Produtos</span>
                            <span class="title-tooltip">Produtos</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vServico')) { ?>
                    <li class="<?php if (isset($menuServicos)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('servicos') ?>"><i class='bx bx-wrench iconX'></i>
                            <span class="title">Serviços</span>
                            <span class="title-tooltip">Serviços</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCurso')) { ?>
                    <li class="<?php if (isset($menuCursos)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('cursos') ?>"><i
                                class='fas fa-graduation-cap iconX'></i>
                            <span class="title">Cursos</span>
                            <span class="title-tooltip">Cursos</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vViagem')) { ?>
                    <li class="<?php if (isset($menuViagens)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('viagens') ?>"><i class='fas fa-route iconX'></i>
                            <span class="title">Viagens</span>
                            <span class="title-tooltip">Viagens</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vTreino')) { ?>
                    <li class="<?php if (isset($menuTreinos)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="Treinos" href="<?= site_url('treinos') ?>"><i
                                class='fas fa-dumbbell iconX'></i>
                            <span class="title">Treinos</span>
                            <span class="title-tooltip">Treinos</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) { ?>
                    <li class="<?php if (isset($menuVendas)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('vendas') ?>"><i
                                class='bx bx-cart-alt iconX'></i></span>
                            <span class="title">Vendas</span>
                            <span class="title-tooltip">Vendas</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vOs')) { ?>
                    <li class="<?php if (isset($menuOs)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('os') ?>"><i class='bx bx-file iconX'></i>
                            <span class="title">Ordens de Serviço</span>
                            <span class="title-tooltip">Ordens</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vGarantia')) { ?>
                    <li class="<?php if (isset($menuGarantia)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('garantias') ?>"><i
                                class='bx bx-receipt iconX'></i>
                            <span class="title">Termos de Garantias</span>
                            <span class="title-tooltip">Garantias</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vArquivo')) { ?>
                    <li class="<?php if (isset($menuArquivos)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('arquivos') ?>"><i class='bx bx-box iconX'></i>
                            <span class="title">Arquivos</span>
                            <span class="title-tooltip">Arquivos</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vLancamento')) { ?>
                    <li class="<?php if (isset($menuLancamentos)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('financeiro/lancamentos') ?>"><i
                                class="bx bx-bar-chart-alt-2 iconX"></i>
                            <span class="title">Lançamentos</span>
                            <span class="title-tooltip">Lançamentos</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCobranca')) { ?>
                    <li class="<?php if (isset($menuCobrancas)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('cobrancas/cobrancas') ?>"><i
                                class='bx bx-dollar-circle iconX'></i>
                            <span class="title">Cobranças</span>
                            <span class="title-tooltip">Cobranças</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'cPermissao')) { ?>
                    <li class="<?php if (isset($menuIntegracoes)) {
                        echo 'active';
                    }
                    ; ?>">
                        <a class="tip-bottom" title="" href="<?= site_url('evolution') ?>"><i
                                class='fas fa-rocket iconX'></i>
                            <span class="title">Integrações</span>
                            <span class="title-tooltip">Integrações</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="botton-content">
            <li class="">
                <a class="tip-bottom" title="" href="<?= site_url('login/sair'); ?>">
                    <i class='bx bx-log-out-circle iconX'></i>
                    <span class="title">Sair</span>
                    <span class="title-tooltip">Sair</span>
                </a>
            </li>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function() {
        function updateVisualState() {
            let currentIsMobile = window.matchMedia("(max-width: 767px)").matches;
            if (currentIsMobile) {
                $('#sidebar, #content').removeClass('hide-sidebar');
                if (!$('#sidebar').hasClass('visible-on-mobile')) {
                    $('.toggle-menu').css('left', '10px');
                    $('.open-2').show();
                    $('.close-2').hide();
                } else {
                    $('.toggle-menu').css('left', '202px');
                    $('.open-2').hide();
                    $('.close-2').show();
                }
            } else {
                $('.menu-overlay').hide();
                // Lógica para Desktop e Tablet
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    $('#sidebar, #content').addClass('hide-sidebar');
                    $('.toggle-menu').css('left', '52px');
                    $('.open-2').show();
                    $('.close-2').hide();
                } else {
                    $('#sidebar, #content').removeClass('hide-sidebar');
                    $('.toggle-menu').css('left', '202px');
                    $('.open-2').hide();
                    $('.close-2').show();
                }
            }
        }

        // Inicialização
        updateVisualState();

        // Tooltip handling
        $('#sidebar .menu-links li').hover(function() {
            if ($('#sidebar').hasClass('hide-sidebar')) {
                var $tooltip = $(this).find('.title-tooltip');
                var rect = this.getBoundingClientRect();
                $tooltip.css({
                    top: rect.top + (rect.height / 2) - ($tooltip.outerHeight() / 2),
                    left: rect.right + 5,
                    display: 'block'
                });
            }
        }, function() {
            $(this).find('.title-tooltip').hide();
        });
        
        $('.menu-bar').scroll(function() { $('.title-tooltip').hide(); });

        // Toggle Click Handler
        $('.toggle-menu').click(function(e) {
            e.preventDefault();
            let currentIsMobile = window.matchMedia("(max-width: 767px)").matches;
            
            if (!currentIsMobile) {
                let isCollapsed = $('#sidebar').hasClass('hide-sidebar');
                localStorage.setItem('sidebar-collapsed', !isCollapsed);
                updateVisualState();
            } else {
                // Remove conflitos de classes de desktop
                $('#sidebar, #content').removeClass('hide-sidebar');

                // Mobile: alterna a visibilidade e atualiza o botão manualmente
                $('#sidebar').toggleClass('visible-on-mobile');

                if ($('#sidebar').hasClass('visible-on-mobile')) {
                    // Menu is now open
                    $('.menu-overlay').fadeIn();
                    $('.toggle-menu').css('left', '202px');
                    $('.open-2').hide();
                    $('.close-2').show();
                } else {
                    // Menu is now closed
                    $('.menu-overlay').fadeOut();
                    $('.toggle-menu').css('left', '10px');
                    $('.open-2').show();
                    $('.close-2').hide();
                }
            }
        });

        // Fecha o menu ao clicar no overlay
        $('.menu-overlay').click(function() {
            $('#sidebar').removeClass('visible-on-mobile');
            $('.menu-overlay').fadeOut();
            $('.toggle-menu').css('left', '10px');
            $('.open-2').show();
            $('.close-2').hide();
        });

        // RESIZE LISTENER COM CONTROLE DE TRANSIÇÃO
        let resizeTimer;
        window.addEventListener('resize', function () {
            document.body.classList.add('is-resizing');
            updateVisualState();
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                document.body.classList.remove('is-resizing');
            }, 250);
        });

        // Fecha o menu ao clicar em um link no mobile
        $('#sidebar a').click(function() {
            if (window.matchMedia("(max-width: 767px)").matches) {
                if ($(this).closest('.submenu').length === 0) {
                    $('#sidebar').removeClass('visible-on-mobile');
                    $('.menu-overlay').fadeOut();
                    $('.toggle-menu').css('left', '10px');
                    $('.open-2').show();
                    $('.close-2').hide();
                    updateVisualState();
                }
            }
        });
    });
</script>
<!--End sidebar-menu-->