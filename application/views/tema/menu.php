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
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        color: #fff;
        transition: left 0.3s ease;
        text-decoration: none;
    }

    .mode {
        cursor: pointer;
    }

    .menu-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }

    @media (max-width: 767px) {
        #sidebar {
            display: flex !important;
            flex-direction: column !important;
            position: fixed !important;
            transform: translateX(-100%) !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            margin-left: 0 !important;
            /* Neutraliza o hide-sidebar do margin-left desktop */
            z-index: 100000 !important;
            width: 230px !important;
            height: 100vh !important;
            transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1) !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        #sidebar.visible-on-mobile {
            transform: translateX(0) !important;
            z-index: 100000 !important;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5) !important;
        }

        #sidebar.visible-on-mobile>ul,
        #sidebar.visible-on-mobile .menu-links {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            height: 100% !important;
            z-index: 100001 !important;
            position: relative;
        }

        #sidebar.visible-on-mobile .menu-bar {
            flex: 1 !important;
            height: 0 !important; /* Ajuda o Flex a calcular a sobra de altura corretamente no iOS/Chrome */
            overflow-y: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            visibility: visible !important;
            z-index: 100001 !important;
            width: 100% !important;
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        
        #sidebar.visible-on-mobile .menu-bar .menu {
            flex: 1 !important;
            overflow-y: auto !important;
            padding-bottom: 80px !important;
        }

        #content {
            margin-left: 0 !important;
            overflow-x: hidden !important;
            width: 100vw !important;
            box-sizing: border-box !important;
        }

        /* Flex wrap nas páginas de Dashboard para não esmagar componentes em modo celular */
        .row-fluid[style*="display: flex"] {
            flex-direction: column !important;
            width: 100% !important;
        }

        .Sspan12 {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }

        .widget-box,
        .widget-box0,
        .widget-box2,
        .widget-box-new,
        .widget-box-statist {
            max-width: 100% !important;
            width: 100% !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }

        .toggle-menu {
            z-index: 100002 !important;
            position: fixed !important;
            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1) !important;
        }

        .toggle-menu.menu-closed-mobile {
            left: 0 !important;
            /* Sem posições negativas para não quebrar a responsividade da tela */
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 20px !important;
            height: 60px !important;
            border-radius: 0 30px 30px 0 !important;
            justify-content: center !important;
            padding-right: 0 !important;
            opacity: 0.8;
            background-color: rgba(0, 0, 0, 0.5) !important;
            color: #fff !important;
            box-shadow: 3px 0 6px rgba(0, 0, 0, 0.3) !important;
        }

        .toggle-menu.menu-closed-mobile:active,
        .toggle-menu.menu-closed-mobile:hover {
            opacity: 1;
            width: 25px !important;
        }

        .toggle-menu.menu-open-mobile {
            left: 220px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            justify-content: center !important;
            padding-right: 0 !important;
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.5) !important;
            color: #fff !important;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4) !important;
        }

        #sidebar.visible-on-mobile .text,
        #sidebar.visible-on-mobile .title {
            display: inline-block !important;
            opacity: 1 !important;
        }

        #sidebar.visible-on-mobile .menu-links li a {
            padding: 12px 15px !important;
            display: flex !important;
            align-items: center !important;
        }

        #sidebar.visible-on-mobile .search-box,
        #sidebar.visible-on-mobile #newlog .title1 {
            display: block !important;
        }

        #sidebar.visible-on-mobile #newlog {
            margin-left: 0 !important;
            opacity: 1 !important;
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            padding: 15px 15px 5px 15px !important;
            margin-top: 0 !important;
            background-color: inherit !important;
            z-index: 100002 !important;
        }

        #sidebar.visible-on-mobile .search-box {
            margin-left: 20px !important;
            opacity: 1 !important;
        }

        #sidebar.visible-on-mobile li {
            opacity: 1 !important;
        }

        .widget-content {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .widget-content canvas {
            max-width: 100% !important;
            height: auto !important;
        }

        .table {
            min-width: 100% !important;
            width: max-content !important;
            max-width: none !important;
            white-space: nowrap !important;
            /* Obriga o scroll horizontal na tabela grande */
        }

        .widget-title {
            height: auto !important;
        }

        .nav-tabs {
            display: flex !important;
            flex-direction: column !important;
            border-bottom: 0 !important;
        }

        .nav-tabs li {
            width: 100% !important;
            margin-bottom: 2px !important;
            float: none !important;
        }

        .progress {
            height: 10px !important;
            margin-bottom: 10px !important;
        }

        .progress .bar {
            height: 100% !important;
            line-height: 10px !important;
            font-size: 9px !important;
        }
    }

    /* Fix para Tablet/Zoom: Forçar recolhimento visualmente via CSS para evitar delay do JS */
    @media (min-width: 768px) and (max-width: 1280px) {
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
                        <button style="background:transparent;border:transparent" type="submit" class="tip-bottom"
                            title="">
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
    $(document).ready(function () {
        function updateVisualState() {
            let currentIsMobile = window.matchMedia("(max-width: 767px)").matches || $(window).width() <= 767;
            if (currentIsMobile) {
                $('#sidebar, #content').removeClass('hide-sidebar');
                if (!$('#sidebar').hasClass('visible-on-mobile')) {
                    $('.toggle-menu').removeClass('menu-open-mobile').addClass('menu-closed-mobile');
                    $('.open-2').show();
                    $('.close-2').hide();
                } else {
                    $('.toggle-menu').removeClass('menu-closed-mobile').addClass('menu-open-mobile');
                    $('.open-2').hide();
                    $('.close-2').show();
                }
            } else {
                $('.menu-overlay').hide();
                $('.toggle-menu').removeClass('menu-open-mobile menu-closed-mobile');
                // Lógica para Desktop e Tablet
                try {
                    if (localStorage.getItem('sidebar-collapsed') === 'true') {
                        $('#sidebar, #content').addClass('hide-sidebar');
                        $('.toggle-menu').css({ 'left': '52px', 'top': '15px', 'transform': 'none', 'width': '35px', 'height': '35px', 'border-radius': '50%', 'justify-content': 'center', 'padding-right': '0' });
                        $('.open-2').show();
                        $('.close-2').hide();
                    } else {
                        $('#sidebar, #content').removeClass('hide-sidebar');
                        $('.toggle-menu').css({ 'left': '202px', 'top': '15px', 'transform': 'none', 'width': '35px', 'height': '35px', 'border-radius': '50%', 'justify-content': 'center', 'padding-right': '0' });
                        $('.open-2').hide();
                        $('.close-2').show();
                    }
                } catch (e) { }
            }
        }

        // Inicialização
        updateVisualState();

        // Tooltip handling
        $('#sidebar .menu-links li').hover(function () {
            if ($('#sidebar').hasClass('hide-sidebar')) {
                var $tooltip = $(this).find('.title-tooltip');
                var rect = this.getBoundingClientRect();
                $tooltip.css({
                    top: rect.top + (rect.height / 2) - ($tooltip.outerHeight() / 2),
                    left: rect.right + 5,
                    display: 'block'
                });
            }
        }, function () {
            $(this).find('.title-tooltip').hide();
        });

        $('.menu-bar').scroll(function () { $('.title-tooltip').hide(); });

        // Toggle Click Handler
        $(document).off('click', '.toggle-menu').on('click', '.toggle-menu', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let currentIsMobile = window.matchMedia("(max-width: 767px)").matches || $(window).width() <= 767;

            if (!currentIsMobile) {
                let isCollapsed = $('#sidebar').hasClass('hide-sidebar');
                try { localStorage.setItem('sidebar-collapsed', !isCollapsed); } catch (e) { }
                updateVisualState();
            } else {
                // Remove conflitos de classes de desktop
                $('#sidebar, #content').removeClass('hide-sidebar');

                // Mobile: alterna a visibilidade e atualiza o botão manualmente
                $('#sidebar').toggleClass('visible-on-mobile');

                if ($('#sidebar').hasClass('visible-on-mobile')) {
                    // Menu is now open
                    $('.menu-overlay').fadeIn();
                    $('.toggle-menu').removeClass('menu-closed-mobile').addClass('menu-open-mobile');
                    $('.open-2').hide();
                    $('.close-2').show();
                } else {
                    // Menu is now closed
                    $('.menu-overlay').fadeOut();
                    $('.toggle-menu').removeClass('menu-open-mobile').addClass('menu-closed-mobile');
                    $('.open-2').show();
                    $('.close-2').hide();
                }
            }
        });

        // Fecha o menu ao clicar no overlay
        $(document).off('click', '.menu-overlay').on('click', '.menu-overlay', function () {
            let currentIsMobile = window.matchMedia("(max-width: 767px)").matches || $(window).width() <= 767;
            if (currentIsMobile) {
                $('#sidebar').removeClass('visible-on-mobile');
                $('.menu-overlay').fadeOut();
                $('.toggle-menu').removeClass('menu-open-mobile').addClass('menu-closed-mobile');
                $('.open-2').show();
                $('.close-2').hide();
            }
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
        $('#sidebar a').click(function () {
            let currentIsMobile = window.matchMedia("(max-width: 767px)").matches || $(window).width() <= 767;
            if (currentIsMobile) {
                if ($(this).closest('.submenu').length === 0) {
                    $('#sidebar').removeClass('visible-on-mobile');
                    $('.menu-overlay').fadeOut();
                    $('.toggle-menu').removeClass('menu-open-mobile').addClass('menu-closed-mobile');
                    $('.open-2').show();
                    $('.close-2').hide();
                }
            }
        });
    });
</script>
<!--End sidebar-menu-->