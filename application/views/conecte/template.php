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
        #sidebar {
            position: fixed !important;
            top: 0;
            bottom: 0;
            left: 0;
            width: 220px !important;
            overflow-y: auto !important;
            padding-top: 60px !important;
            padding-bottom: 150px !important;
            z-index: 99;
            box-sizing: border-box !important;
        }
        #sidebar::-webkit-scrollbar {
            width: 5px;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <!--top-Header-menu-->
    <div id="header">
        <h1><a href="dashboard.html"><?php echo $this->config->item('app_name'); ?></a></h1>
    </div>
    <div class="navebarn" style="margin-top: -60px; height: 25px; margin-bottom: 15px;">
        <div id="user-nav" class="navbar navbar-inverse"
            style="display: flex; justify-content: flex-end; align-items: center; padding-right: 20px;">
            <ul class="nav" style="width: auto; position: static;">
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
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='bx bx-user-circle iconN1'></i>
                        <?= $this->session->userdata('nome') ?> </a>
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

    <nav id="sidebar">
        <div id="newlog">
            <div class="icon2">
                <img src="<?php echo base_url() ?>assets/img/logo-two.png">
            </div>
            <div class="title1">
                <img src="<?= base_url() ?>assets/img/logo-mapos-branco.png">
            </div>
        </div>
        <a href="#" class="visible-phone">
            <div class="mode">
                <div class="moon-menu">
                    <i class='bx bx-chevron-right iconX open-2'></i>
                    <i class='bx bx-chevron-left iconX close-2'></i>
                </div>
            </div>
        </a>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links" style="position: relative;">
                    <li class="<?php if (isset($menuPainel)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title=""
                            href="<?php echo base_url() ?>index.php/mine/painel"><i class='bx bx-home-alt iconX'></i>
                            <span class="title">Painel</span></a></li>
                    <li class="<?php if (isset($menuConta)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title=""
                            href="<?php echo base_url() ?>index.php/mine/conta"><i class="bx bx-user-circle iconX"></i>
                            <span class="title">Minha Contas</span></a></li>
                    <li class="<?php if (isset($menuOs)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title=""
                            href="<?php echo base_url() ?>index.php/mine/os"><i class='bx bx-spreadsheet iconX'></i>
                            <span class="title">Ordens</span></a></li>
                    <li class="<?php if (isset($menuVendas)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title=""
                            href="<?php echo base_url() ?>index.php/mine/compras"><i class='bx bx-cart-alt iconX'></i>
                            <span class="title">Compras</span></a></li>
                    <li class="<?php if (isset($menuCobrancas)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title=""
                            href="<?php echo base_url() ?>index.php/mine/cobrancas"><i
                                class='bx bx-credit-card-front iconX'></i> <span class="title">Cobranças</span></a></li>
                    <li class="<?php if (isset($menuCursos)) {
                        echo 'active';
                    }
                    ; ?>"><a class="tip-bottom" title=""
                            href="<?php echo base_url() ?>index.php/mine/meusCursos"><i
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
</body>

</html>