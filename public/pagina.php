<?php
require_once "Includes/conectdb.php";
include_once "Includes/verifica_login.php";
include_once "Includes/verifica_combate.php";
include_once "Includes/verifica_missao.php";

if (! isset($_GET["sessao"]) || ! validate_alphanumeric($_GET["sessao"])) {
    echo "#Sessão inválida";
    exit();
}

$sessao = $_GET["sessao"];

if (! file_exists("Sessoes/" . $sessao . ".php")) {
    $sessao = "home";
}

$protector->protect($sessao);

$facebook_url = "https://www.facebook.com/dialog/oauth?client_id=444646756906612&redirect_uri=https://sugoigame.com.br/Scripts/Geral/login_facebook.php&scope=email";
?>
<div id="header">
    <div id="header_barra">
        <?php if (! $userDetails->conta) : ?>
            <nav class="header-navbar navbar navbar-default w-100">
                <div class="container-fluid">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="login.php">Início</a>
                        </li>
                        <li>
                            <a href="./?ses=cadastro" class="link_content">Cadastrar</a>
                        </li>
                        <li>
                            <a class="bt-facebook" href="<?= $facebook_url ?>">
                                <i class="fa fa-facebook-square fa-fw"></i>
                                Entrar com Facebook
                            </a>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-right" name="login" action="Scripts/Geral/logar.php" method="POST">
                        <div class="row">
                            <div class="col-md-5 form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></div>
                                    <input type="email" name="login" class="form-control" placeholder="E-mail" required />
                                </div>
                            </div>
                            <div class="col-md-5 form-group">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-key fa-fw"></i></div>
                                    <input type="password" name="senha" class="form-control" placeholder="Senha" required />
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                <button class="btn btn-block btn-info" type="submit">Acessar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </nav>
        <?php elseif ($userDetails->tripulacao) :
            include "header.php";
        else : ?>
            <nav class="header-navbar navbar navbar-default">
                <div class="container-fluid">
                    <div>
                        <ul class="nav navbar-nav">
                        </ul>
                    </div>
                </div>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php if (! $protector->is_ful_wide_session($sessao)) : ?>
    <div id="menu-wrapper">
        <?php include "menu.php"; ?>
    </div>
    <?php include "Includes/Components/Header/missoes_auxiliares.php"; ?>
    <?php include "Includes/Components/Header/torneio_poneglyph.php"; ?>
    <?php include "Includes/Components/Header/bandeira.php"; ?>
<?php endif; ?>
<?php if ($userDetails->tripulacao && ! $protector->is_ful_wide_session($sessao)) : ?>
    <div id="tripulantes-bar">
        <?php include "tripulantes.php"; ?>
    </div>
<?php endif; ?>

<?php if ($sessao != "oceano") : ?>
    <div id="fundo" class="<?= $protector->is_ful_wide_session($sessao) ? "fundo-full-wide" : "fundo-window" ?>">
        <button type="button" class="close close-session link_content" href="./?ses=oceano">
            <span>&times;</span>
        </button>
        <div class="container-fluid fundo-container">
            <div class="main-container">
                <div class="main-panel">
                    <div class="panel panel-default m0 panel-<?= $sessao ?>">
                        <?php include "Sessoes/$sessao.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($userDetails->tripulacao) : ?>
    <input type="hidden" id="ilha_atual" value="<?= $userDetails->ilha["ilha"]; ?>">
    <input type="hidden" id="should_show_world_map" value="<?= $protector->should_show_world_map($sessao); ?>">
    <input type="hidden" id="coord_x_navio" value="<?= $userDetails->tripulacao["x"]; ?>">
    <input type="hidden" id="coord_y_navio" value="<?= $userDetails->tripulacao["y"]; ?>">
    <input type="hidden" id="sg_c" value="<?= $_SESSION["sg_c"]; ?>">
    <input type="hidden" id="sg_k" value="<?= $_SESSION["sg_k"]; ?>">


    <?php if ($userDetails->tripulacao["faccao"] == 0) : ?>
        <style type="text/css">
            <?php include "CSS/estrutura-marine.css"; ?>
        </style>
    <?php endif; ?>
<?php endif; ?>

