<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Área do Cliente - <?php echo $this->config->item('app_name') ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="<?php echo $this->config->item('app_name') . ' - ' . $this->config->item('app_subname') ?>">
    <meta name="csrf-token-name" content="<?= config_item("csrf_token_name") ?>">
    <meta name="csrf-cookie-name" content="<?= config_item("csrf_cookie_name") ?>">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-style.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-media.css" />
    <?php if (isset($configuration['app_theme'])): ?>
        <?php if ($configuration['app_theme'] == 'white') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tema-white.css" />
        <?php } ?>
        <?php if ($configuration['app_theme'] == 'puredark') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tema-pure-dark.css" />
        <?php } ?>
        <?php if ($configuration['app_theme'] == 'darkviolet') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tema-dark-violet.css" />
        <?php } ?>
        <?php if ($configuration['app_theme'] == 'darkorange') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tema-dark-orange.css" />
        <?php } ?>
        <?php if ($configuration['app_theme'] == 'whitegreen') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tema-white-green.css" />
        <?php } ?>
        <?php if ($configuration['app_theme'] == 'whiteblack') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tema-white-black.css" />
        <?php } ?>
    <?php endif; ?>
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" />
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/funcoesGlobal.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>assets/js/csrf.js"></script>
    <style>
        .nav.nav-tabs a {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Modern Notification Styles */
        .notification-container {
            position: relative;
            display: inline-block;
            margin-top: 15px;
            /* Aligner with user menu */
        }

        .notification-trigger {
            background: transparent;
            border: none;
            cursor: pointer;
            position: relative;
            padding: 8px;
            color: #999;
            transition: color 0.3s ease;
        }

        .notification-trigger:hover {
            color: #fff;
        }

        .notification-trigger i {
            font-size: 1.4rem;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #e74c3c;
            color: #fff;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 15px;
            text-align: center;
            border: 2px solid #2e363f;
            /* Match navbar background */
            animation: bounceIn 0.5s;
        }

        .notification-menu {
            position: absolute;
            top: 100%;
            right: -10px;
            width: 320px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: slideDown 0.3s ease-out forwards;
            border: 1px solid #eee;
        }

        .notification-menu.show {
            display: flex;
        }

        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .notification-header h3 {
            margin: 0;
            font-size: 1rem;
            color: #333;
            font-weight: 600;
        }

        .notification-header .mark-read {
            font-size: 0.8rem;
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: start;
            gap: 12px;
            transition: background 0.2s;
            text-decoration: none !important;
            color: inherit;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e3f2fd;
            color: #1976d2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.warning {
            background: #fff3e0;
            color: #f57c00;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
            display: block;
        }

        .notification-desc {
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
            display: block;
        }

        .notification-empty {
            padding: 30px;
            text-align: center;
            color: #999;
        }

        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 10px;
            display: block;
            color: #e0e0e0;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .notification-menu {
                position: fixed;
                top: 60px;
                left: 10px;
                right: 10px;
                width: auto;
            }
        }

        /* Correção tamanho icone perfil */
        .iconN1 {
            font-size: 1.6rem;
        }

        /* Correção para o menu lateral permitir rolagem em telas menores */

        #sidebar::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
    </style>

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
                height: 0 !important;
                /* Ajuda o Flex a calcular a sobra de altura corretamente no iOS/Chrome */
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
            display: flex !important;
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
            display: flex !important;
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
                padding: 0 15px 5px 15px !important;
                margin-top: 0 !important;
                background-color: inherit !important;
                z-index: 100002 !important;
            }

            #sidebar.visible-on-mobile #newlog img {
                margin-top: 0 !important;
            }

            #sidebar.visible-on-mobile #newlog .icon2,
            #sidebar.visible-on-mobile #newlog .title1 {
                margin-top: 0 !important;
                padding-top: 0 !important;
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
        
        /* Oculta o header do topo (tarja vazia) e reseta barra limpa */
        #header { display: none !important; }
        .navebarn { margin-top: 10px !important; margin-bottom: 5px !important; position: absolute; right: 15px; top: 0; z-index: 10000; display: flex; justify-content: flex-end; width: auto !important; }
        
        #user-nav {
            position: static !important;
            width: auto !important;
            margin: 0 !important;
            padding-right: 15px !important;
        }
        
        /* Cancela o offset global do CSS admin que corta menus a direita */
        #user-nav > ul {
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
            display: flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
        }
        #user-nav > ul > li {
            left: 0 !important;
            position: relative !important;
            margin: 0 !important;
        }

        @media (max-width: 767px) {
            body { margin-top: 0 !important; }
            .navebarn { position: fixed !important; right: 5px !important; top: 0 !important; padding: 0 !important; width: auto !important; }
            
            /* Impede q o nome vaze da margem container da navebarn no mobile */
            #user-nav .dropdown-toggle .text {
                max-width: 90px !important;
            }

            #sidebar.visible-on-mobile #newlog {
                flex: 0 0 auto !important;
                position: relative !important;
                margin-top: 20px !important;
                margin-bottom: 20px !important;
            }
        }
    </style>
</head>

<body>
    <!--top-Header-menu-->
    <div id="header">
        <h1><a href="dashboard.html"><?php echo $this->config->item('app_name'); ?></a></h1>
    </div>
    <div class="navebarn">
        <div id="user-nav">
            <ul class="nav">
                <!-- Notification Component -->
                <?php
                $has_notification = isset($alerta_perfil_incompleto) && $alerta_perfil_incompleto;
                $notif_count = $has_notification ? 1 : 0;
                ?>
                <li class="dropdown" style="margin-right: 15px;">
                    <button class="notification-trigger" id="notifDropdownBtn">
                        <i class="fas fa-bell"></i>
                        <?php if ($notif_count > 0): ?>
                            <span class="notification-badge"><?= $notif_count ?></span>
                        <?php endif; ?>
                    </button>

                    <div class="notification-menu" id="notifMenu">
                        <div class="notification-header">
                            <h3>Notificações</h3>
                        </div>
                        <ul class="notification-list">
                            <?php if ($has_notification): ?>
                                <li>
                                    <a href="<?= site_url('mine/conta?tab=saude') ?>" class="notification-item">
                                        <div class="notification-icon warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="notification-content">
                                            <span class="notification-title">Perfil Incompleto</span>
                                            <span class="notification-desc">Alguns dados de saúde estão faltando. Clique
                                                para atualizar.</span>
                                        </div>
                                    </a>
                                </li>
                            <?php else: ?>
                                <div class="notification-empty">
                                    <i class="fas fa-check-circle"></i>
                                    <p>Tudo limpo por aqui!</p>
                                    <small>Nenhuma nova notificação.</small>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const btn = document.getElementById('notifDropdownBtn');
                        const menu = document.getElementById('notifMenu');

                        // Toggle menu
                        btn.addEventListener('click', function (e) {
                            e.stopPropagation();
                            menu.classList.toggle('show');
                        });

                        // Close when clicking outside
                        document.addEventListener('click', function (e) {
                            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                                menu.classList.remove('show');
                            }
                        });
                    });
                </script>
                <li class="dropdown" style="display: flex; align-items: center;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                        style="display:flex; align-items:center; gap:5px; padding: 5px 10px; text-decoration: none;">
                        <i class='bx bx-user-circle iconN1' style="font-size: 24px;"></i>
                        <span class="text" style="max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block;">
                            <?= $this->session->userdata('nome') ?> 
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class=""><a title="Meu Perfil" href="<?php echo base_url() ?>index.php/mine/conta"><i
                                    class="fas fa-user"></i> <span class="text">Meu Perfil</span></a></li>
                        <li class="divider"></li>
                        <li class=""><a title="Sair" href="<?php echo base_url() ?>index.php/mine/sair"><i
                                    class="fas fa-sign-out-alt"></i> <span class="text">Sair</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

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
        <div id="newlog" style="margin-top: 15px; margin-bottom: 30px; display: flex; flex-direction: column; align-items: center;">
            <div class="icon2">
                <img src="<?php echo base_url() ?>assets/img/logo-two.png">
            </div>
            <div class="title1">
                <img src="<?= base_url() ?>assets/img/logo-mapos-branco.png">
            </div>
        </div>


        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links" style="position: relative;">
                    <li class="<?php if (isset($menuPainel)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/painel"><i
                                class='bx bx-home-alt iconX'></i>
                            <span class="title">Painel</span></a></li>
                    <li class="<?php if (isset($menuConta)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/conta"><i
                                class="bx bx-user-circle iconX"></i>
                            <span class="title">Minha Contas</span></a></li>
                    <li class="<?php if (isset($menuOs)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/os"><i
                                class='bx bx-spreadsheet iconX'></i>
                            <span class="title">Ordens</span></a></li>
                    <li class="<?php if (isset($menuVendas)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/compras"><i
                                class='bx bx-cart-alt iconX'></i>
                            <span class="title">Compras</span></a></li>
                    <li class="<?php if (isset($menuCobrancas)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/cobrancas"><i
                                class='bx bx-credit-card-front iconX'></i> <span class="title">Cobranças</span></a></li>
                    <li class="<?php if (isset($menuCursos)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/meusCursos"><i
                                class='bx bxs-book-bookmark iconX'></i> <span class="title">Cursos</span></a></li>
                    <li class="<?php if (isset($menuTreinos)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title="Agendar Treinos"
                            href="<?php echo base_url() ?>index.php/mine/treinos"><i class='bx bx-dumbbell iconX'></i>
                            <span class="title">Treinos</span></a></li>
                </ul>
            </div>

            <div class="botton-content">
                <li class="">
                    <a class="tip-bottom" title="" href="<?= site_url('login/sair'); ?>">
                        <i class='bx bx-log-out-circle iconX'></i>
                        <span class="title">Sair</span></a>
                </li>
            </div>

        </div>
    </nav>

    <div style="background: #f3f4f6" id="content">
        <div class="content-header" id="content-header">
            <div id="breadcrumb"><a href="<?php echo base_url(); ?>index.php/mine/painel" title="Painel"
                    class="tip-bottom"><i class="fas fa-home"></i> Painel</a></div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">

                <div class="span12">
                    <?php if ($var = $this->session->flashdata('success')): ?>
                        <script>
                            swal("Sucesso!", "<?php echo str_replace('"', '', $var); ?>", "success");
                        </script><?php endif; ?>
                    <?php if ($var = $this->session->flashdata('error')): ?>
                        <script>
                            swal("Falha!", "<?php echo str_replace('"', '', $var); ?>", "error");
                        </script><?php endif; ?>
                    <?php if (isset($output)) {
                        $this->load->view($output);
                    } ?>

                </div>
            </div>

        </div>
    </div>
    <!--Footer-part-->
    <div class="row-fluid">
        <div id="footer" class="span12">
            <a class="pecolor" href="<?= $_ENV['APP_URL_FOOTER'] ?? 'https://github.com/RamonSilva20/mapos' ?>"
                target="_blank">
                <?= $configuration['app_footer'] ?? date('Y') . ' &copy; ' . $this->config->item('app_name') ?> -
                Versão: <?= $this->config->item('app_version'); ?>
            </a>
        </div>
    </div>

    <!-- javascript
================================================== -->

    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/matrix.js"></script>

    <script>
        $(document).ready(function () {
            function updateVisualState() {
                let currentIsMobile = window.innerWidth <= 767;
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
                let currentIsMobile = window.innerWidth <= 767;

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
                let currentIsMobile = window.innerWidth <= 767;
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
                let currentIsMobile = window.innerWidth <= 767;
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
</body>

</html>