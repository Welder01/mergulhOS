<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Área do Cliente - <?php echo $this->config->item('app_name') ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo $this->config->item('app_name') . ' - ' . $this->config->item('app_subname') ?>">
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
        /* Estilos para a notificação moderna */
        .notification-bell .dropdown-toggle {
            position: relative;
            font-size: 1.2em;
        }
        .notification-bell .label-important {
            position: absolute;
            top: 2px;
            right: 105px;
            height: 18px;
            width: 18px;
            line-height: 17px;
            border-radius: 50%;
            font-size: 10px;
            text-align: center;
            background-color: #dc3545;
            border: 2px solid #2e363f;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .notification-bell.has-notification .fa-bell {
            animation: bell-ring 1.5s ease-in-out infinite;
            transform-origin: top center;
        }
        @keyframes bell-ring {
            0%, 100% { transform: rotate(0); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(10deg); }
            20%, 40%, 60%, 80% { transform: rotate(-10deg); }
        }
        .notification-bell .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.1);
        }
        .notification-bell .dropdown-menu a {
            padding: 10px 15px;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .notification-bell .dropdown-menu .badge {
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <!--top-Header-menu-->
    <div id="header">
        <h1><a href="dashboard.html"><?php echo $this->config->item('app_name'); ?></a></h1>
    </div>
    <div class="navebarn" style="margin-top: -60px; height: 25px; margin-bottom: 15px;">
        <div id="user-nav" class="navbar navbar-inverse">
            <ul class="nav">
                <?php
                    $has_notification = isset($alerta_perfil_incompleto) && $alerta_perfil_incompleto;
                ?>
                <li class="dropdown notification-bell <?= $has_notification ? 'has-notification' : '' ?>" id="menu-messages">
                    <?php if ($has_notification) : ?>
                        <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                            <i class="fas fa-bell"></i>
                            <span class="text">Notificações</span>
                            <span class="label label-important">1</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="sAdd" title="Perfil Incompleto" href="<?= site_url('mine/conta?tab=saude') ?>"><i class="fas fa-exclamation-triangle" style="color: #f89406;"></i> Perfil Incompleto! Clique para atualizar.</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                        <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
                            <i class="fas fa-bell"></i> <span class="text">Notificações</span>
                            <span class="label label-important">0</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="sAdd" title="" href="#">Nenhuma notificação nova</a></li>
                        </ul>
                <?php endif; ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class='bx bx-user-circle iconN1'></i> <?= $this->session->userdata('nome') ?> </a>
                    <ul class="dropdown-menu">
                        <li class=""><a title="Meu Perfil" href="<?php echo base_url() ?>index.php/mine/conta"><i class="fas fa-user"></i> <span class="text">Meu Perfil</span></a></li>
                        <li class="divider"></li>
                        <li class=""><a title="Sair" href="<?php echo base_url() ?>index.php/mine/sair"><i class="fas fa-sign-out-alt"></i> <span class="text">Sair</span></a></li>
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
                                }; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/painel"><i class='bx bx-home-alt iconX'></i> <span class="title">Painel</span></a></li>
                    <li class="<?php if (isset($menuConta)) {
                                    echo 'active';
                                }; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/conta"><i class="bx bx-user-circle iconX"></i> <span class="title">Minha Contas</span></a></li>
                    <li class="<?php if (isset($menuOs)) {
                                    echo 'active';
                                }; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/os"><i class='bx bx-spreadsheet iconX'></i> <span class="title">Ordens</span></a></li>
                    <li class="<?php if (isset($menuVendas)) {
                                    echo 'active';
                                }; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/compras"><i class='bx bx-cart-alt iconX'></i> <span class="title">Compras</span></a></li>
                    <li class="<?php if (isset($menuCobrancas)) {
                                    echo 'active';
                                }; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/cobrancas"><i class='bx bx-credit-card-front iconX'></i> <span class="title">Cobranças</span></a></li>
                    <li class="<?php if (isset($menuCursos)) {
                                    echo 'active';
                                }; ?>"><a class="tip-bottom" title="" href="<?php echo base_url() ?>index.php/mine/meusCursos"><i class='bx bxs-book-bookmark iconX'></i> <span class="title">Cursos</span></a></li>
                    <li class="<?php if (isset($menuTreinos)) {
                                    echo 'active';
                                }; ?>"><a class="tip-bottom" title="Agendar Treinos" href="<?php echo base_url() ?>index.php/mine/treinos"><i class='bx bx-dumbbell iconX'></i> <span class="title">Treinos</span></a></li>
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
            <div id="breadcrumb"><a href="<?php echo base_url(); ?>index.php/mine/painel" title="Painel" class="tip-bottom"><i class="fas fa-home"></i> Painel</a></div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">

                <div class="span12">
                    <?php if ($var = $this->session->flashdata('success')) : ?><script>
                            swal("Sucesso!", "<?php echo str_replace('"', '', $var); ?>", "success");
                        </script><?php endif; ?>
                    <?php if ($var = $this->session->flashdata('error')) : ?><script>
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
            <a class="pecolor" href="<?= $_ENV['APP_URL_FOOTER'] ?? 'https://github.com/RamonSilva20/mapos' ?>" target="_blank">
                <?= $configuration['app_footer'] ?? date('Y') . ' &copy; ' . $this->config->item('app_name') ?> - Versão: <?= $this->config->item('app_version'); ?>
            </a>
        </div>
    </div>

    <!-- javascript
================================================== -->

    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/matrix.js"></script>
</body>

</html>
