<?php

require_once "Includes/conectdb.php";
include_once "Includes/verifica_login.php";
include_once "Includes/verifica_combate.php";
include_once "Includes/verifica_missao.php";

if (!isset($_GET["sessao"]) || !validate_alphanumeric($_GET["sessao"])) {
    echo "#Sessão inválida";
    exit();
}

$sessao = $_GET["sessao"];

if (!file_exists("Sessoes/" . $sessao . ".php")) {
    $sessao = "home";
}

$protector->protect($sessao);

$facebook_url = "https://www.facebook.com/dialog/oauth?client_id=444646756906612&redirect_uri=https://sugoigame.com.br/Scripts/Geral/login_facebook.php&scope=email";
?>
    <div id="header">
        <div id="header_barra">
            <?php if (!$userDetails->conta): ?>
                <nav class="header-navbar navbar navbar-default">
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
            else: ?>
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
    <div id="fundo">
        <div class="container-fluid fundo-container">
            <div class="row main-container-row">
                <?php if (!$protector->is_ful_wide_session($sessao)): ?>
                    <div class="col-sm-3 menu-col">
                        <?php if ($sessao == "oceano"): ?>
                            <div class="text-left" style="margin-left: 5px; margin-top: 38px;">
                                <button class="btn btn-default" data-toggle="collapse" href="#menu-cover">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <div id="menu-cover" class="<?=($sessao == 'oceano' ? 'collapse' : 'collapse in')?>">
                            <div class="menu-content">
                                <?php include "menu.php"; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-sm-<?= $protector->is_ful_wide_session($sessao) ? "12" : "9" ?> main-col">
                    <div class="main-panel">
                        <?php $rdp          = explode(",", get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_PIRATA)); ?>
                        <?php $adf          = explode(",", get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_MARINHA)); ?>
                        <?php $yonkou       = explode(",", get_value_varchar_variavel_global(VARIAVEL_YONKOUS)); ?>
                        <?php $almirante    = explode(",", get_value_varchar_variavel_global(VARIAVEL_ALMIRANTES)); ?>
                        <?php $incursao     = explode(",", get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_INCURSAO)); ?>
                        <?php if ($userDetails->tripulacao) : ?>
                            <div id="tripulantes-bar" style="margin-bottom: 10px;;">
                                <?php include "tripulantes.php"; ?>
                            </div>
                            <div class="row">
                                <?php if (count($userDetails->personagens) < 15): ?>
                                    <?php render_progress(
                                        "progress-tripulantes",
                                        count($userDetails->personagens) / 15,
                                        count($userDetails->personagens),
                                        count($userDetails->personagens) . " de 15 tripulantes recrutados",
                                        "#5bc0de",
                                        "recrutar"
                                    ); ?>
                                <?php endif; ?>

                                <?php if ($userDetails->lvl_mais_forte < 50): ?>
                                    <?php render_progress(
                                        "progress-mais-forte",
                                        $userDetails->lvl_mais_forte / 50,
                                        $userDetails->lvl_mais_forte,
                                        "Personagem mais forte no nível " . $userDetails->lvl_mais_forte . " de 50",
                                        "#f89406",
                                        "status"
                                    ); ?>
                                <?php endif; ?>

                                <?php $rep_mais_forte = $connection->run(
                                    "SELECT max(reputacao) AS total FROM tb_usuarios"
                                )->fetch_array()["total"]; ?>
                                <?php if (count($userDetails->personagens) >= 15): ?>
                                    <?php render_progress(
                                        "progress-reputacao",
                                        $userDetails->tripulacao["reputacao"] / $rep_mais_forte,
                                        mascara_numeros_grandes($userDetails->tripulacao["reputacao"]),
                                        mascara_numeros_grandes($userDetails->tripulacao["reputacao"]) . " pontos de reputação",
                                        "#62c462",
                                        "ranking"
                                    ); ?>
                                <?php endif; ?>

                                <?php if ($userDetails->lvl_mais_forte >= 50): ?>
                                    <?php $fa_mais_forte = $connection->run(
                                        "SELECT max(fama_ameaca) AS total FROM tb_personagens"
                                    )->fetch_array()["total"]; ?>
                                    <?php render_progress(
                                        "progress-wanted",
                                        $userDetails->fa_mais_alta / $fa_mais_forte,
                                        abrevia_numero_grande(calc_recompensa($userDetails->fa_mais_alta)),
                                        ($userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "Recompensa" : "Gratificação") . " mais alta de " . abrevia_numero_grande(calc_recompensa($userDetails->fa_mais_alta)),
                                        "#ee5f5b",
                                        "ranking&rank=fa"
                                    ); ?>
                                <?php endif; ?>

                                <?php $missoes_concluidas = $connection->run(
                                    "SELECT count(conc.cod_missao) AS total
                                        FROM tb_missoes_concluidas conc
                                        WHERE conc.id = ?",
                                    "i", array($userDetails->tripulacao["id"])
                                )->fetch_array()["total"]; ?>
                                <?php $missoes_total = 176 ?>
                                <?php render_progress(
                                    "progress-missoes",
                                    $missoes_concluidas / $missoes_total,
                                    $missoes_concluidas,
                                    "$missoes_concluidas missões concluídas " . ($userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "no Subúrbio" : "na Base da Marinha"),
                                    "#5bc0de",
                                    "missoes"
                                ); ?>

                                <?php $chefes_derrotados = $connection->run(
                                    "SELECT count(conc.tripulacao_id) AS total
                                        FROM tb_missoes_chefe_ilha conc
                                        WHERE conc.tripulacao_id = ?",
                                    "i", array($userDetails->tripulacao["id"])
                                )->fetch_array()["total"]; ?>
                                <?php $chefes_total = 47 ?>
                                <?php render_progress(
                                    "progress-chefes",
                                    $chefes_derrotados / $chefes_total,
                                    $chefes_derrotados,
                                    "$chefes_derrotados de $chefes_total Chefes das Ilhas derrotados",
                                    "#f89406",
                                    "missoes"
                                ); ?>
                            </div>

                            <?php $is_dbl = $connection->run("SELECT `id`,`data_inicio`,`data_fim` FROM tb_vip_dobro WHERE NOW() BETWEEN data_inicio AND data_fim LIMIT 1"); ?>
                            <?php if ($is_dbl->count()) { ?>
                                <?php $is_dbl = $is_dbl->fetch_array(); ?>
                                <div class="alert alert-info">
                                    <b>!!! PROMOÇÃO !!!</b><br />
                                    Estamos com uma promoção de Gold em DOBRO ativa de <b><?=date('d/m/Y à\s H:i:s', strtotime($is_dbl['data_inicio']));?></b> até <b><?=date('d/m/Y à\s H:i:s', strtotime($is_dbl['data_fim']));?></b>.<br />
                                    Clique <a href="./?ses=vipComprar" class="link_content">aqui</a> e aproveite essa oportunidade.
                                </div>
                            <?php } ?>

                            <?php if (in_array($userDetails->tripulacao["id"], $rdp) || in_array($userDetails->tripulacao["id"], $adf)): ?>
                                <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_era WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
                                <?php if (!$recompensa): ?>
                                    <div class="alert alert-info" style="margin-bottom: 10px;;">
                                        <b>Parabéns!</b> Você foi um dos melhores na Grande Era dos Piratas! Clique <a href="link_Eventos/recompensa_era.php" class="alert-link link_send">aqui</a> para receber sua recompensa.
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($userDetails->tripulacao["id"], $yonkou) || in_array($userDetails->tripulacao["id"], $almirante)): ?>
                                <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_grandes_poderes WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
                                <?php if (!$recompensa): ?>
                                    <div class="alert alert-info" style="margin-bottom: 10px;;">
                                        <b>Parabéns!</b> Você foi um dos melhores na Batalha dos Grandes Poderes! Clique <a href="link_Eventos/recompensa_grandes_poderes.php" class="alert-link link_send">aqui</a> para receber sua recompensa.
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($userDetails->tripulacao["id"], $incursao)): ?>
                                <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_incursao WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
                                <?php if (!$recompensa): ?>
                                    <div class="alert alert-info" style="margin-bottom: 10px;;">
                                        <b>Parabéns!</b> Você foi um dos ganhadores da Incursão Especial! Clique <a href="link_Eventos/recompensa_incursao.php" class="alert-link link_send">aqui</a> para receber sua recompensa.
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php $convocacao_torneio = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"])); ?>
                            <?php if ($convocacao_torneio->count()): ?>
                                <div class="alert alert-warning" style="margin-bottom: 10px;;">
                                    <b>Atenção!</b> Você está sendo convocado para o Torneio dos Melhores Sugoi Game! Clique <a href="./?ses=torneio" class="alert-link link_content">aqui</a> para apresentar-se.
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="panel panel-default">
                            <?php include "Sessoes/$sessao.php"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer" class="-hidden-xs -visible-xs-block">
        <div id="footer_cima">
            <div id="footer_cr">
                Sugoi Game <?=date('Y');?>
                - <a href="./?ses=politica" class="link_content">Política de Privacidade</a>
                - <a href="./?ses=regras" class="link_content">Regras e Punições</a><br/>
                Personagens e desenhos © CopyRight 1997 by Eiichiro Oda. Todos os direitos reservados
            </div>
        </div>
    </div>

<?php if ($userDetails->tripulacao): ?>
    <a id="chat-button" href="https://discord.com/invite/evMNazcMN9" class="btn btn-default" target="_blank">
        <i class="fa fa-comments fa-fw"></i> CHAT
    </a>
    <input type="hidden" id="ilha_atual" value="<?= $userDetails->ilha["ilha"]; ?>">
    <input type="hidden" id="coord_x_navio" value="<?= $userDetails->tripulacao["x"]; ?>">
    <input type="hidden" id="coord_y_navio" value="<?= $userDetails->tripulacao["y"]; ?>">
    <input type="hidden" id="sg_c" value="<?= $_SESSION["sg_c"]; ?>">
    <input type="hidden" id="sg_k" value="<?= $_SESSION["sg_k"]; ?>">
<?php endif; ?>
