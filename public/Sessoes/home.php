<?php
function render_maior_do_mundo($tipo)
{
    global $connection;

    switch ($tipo) {
        case "espadachim":
            $class = 1;
            break;
        case "lutador":
            $class = 2;
            break;
        default:
            $class = 3;
            break;
    }

    $result = $connection->run(
        "SELECT p.*, u.* FROM tb_personagens p
            INNER JOIN tb_usuarios u ON p.id = u.id
            WHERE u.adm = 0 AND p.classe = ? ORDER BY fama_ameaca DESC LIMIT 1",
        "i", [$class]
    );
    ?>
    <div class="col-sm-4 col-md-4">
        <?php if ($result->count()) : ?>
            <div class="panel panel-default">
                <div class="panel-heading" style="font-size: 1.2rem;">
                    Melhor
                    <?= ucfirst($tipo); ?>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <?php $famoso = $result->fetch_array(); ?>
                    <div class="div-top">
                        <?php render_top_player($famoso, "O maior $tipo do mundo"); ?>
                    </div>
                    <img src="Imagens/Bandeiras/img.php?cod=<?= $famoso["bandeira"]; ?>&f=<?= $famoso["faccao"]; ?>" width="65"
                        height="45" />
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php } ?>
<?php function render_procurados($faccao)
{ ?>
    <?php global $connection; ?>
    <?php $result = $connection->run(
        "SELECT *
              FROM tb_personagens pers
              INNER JOIN tb_usuarios usr ON pers.cod = usr.cod_personagem
              WHERE usr.adm='0' AND usr.faccao = '$faccao' ORDER BY pers.fama_ameaca DESC LIMIT 24"
    ); ?>

    <?php while ($famoso = $result->fetch_array()) : ?>
        <?php render_cartaz_procurado($famoso, $faccao); ?>
    <?php endwhile; ?>
<?php } ?>
<?php if ($userDetails->tripulacao && $userDetails->tripulacao["progress"] == 0) : ?>
    <script type="text/javascript">
        $(function () {
            bootbox.alert({
                title: 'Bem vindo marujo!',
                message: 'Fique de olho na espada amarela no topo da tela. Alí você encontrará a ajuda que precisa para começar a jogar!'
            });
        });
    </script>
<?php endif; ?>

<style type="text/css">
    .text-vitoria {
        color: green;
    }

    .text-derrota {
        color: #9f0000;
    }

    .div-top {
        margin: 0;
        padding: 0;
        position: relative;
    }

    .texto-top {
        position: absolute;
        z-index: 2;
        left: 0;
        bottom: 0;
        width: 100%;
        text-shadow: #000 1px -1px 2px, #000 -1px 1px 2px, #000 1px 1px 2px, #000 -1px -1px 2px;
        color: #fff;
    }

    .texto-top p {
        margin-bottom: 0;
    }

    .texto-top-cargo {
        font-size: 1em;
    }

    .texto-top-nome {
        font-size: 1.5em;
    }

    .texto-top-alcunha {
        font-size: 1em;
    }
</style>
<div class="panel-body">
    <style type="text/css">
        <?php include "CSS/home.css"; ?>
    </style>
    <?php $rdp = explode(",", get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_PIRATA)); ?>
    <?php $adf = explode(",", get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_MARINHA)); ?>
    <?php $yonkou = explode(",", get_value_varchar_variavel_global(VARIAVEL_YONKOUS)); ?>
    <?php $almirante = explode(",", get_value_varchar_variavel_global(VARIAVEL_ALMIRANTES)); ?>
    <?php $incursao = explode(",", get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_INCURSAO)); ?>
    <?php if ($userDetails->tripulacao) : ?>
        <!-- <div class="row">
            <?php if (count($userDetails->personagens) < 15) : ?>
                <?php render_progress(
                    "progress-tripulantes",
                    count($userDetails->personagens) / 15,
                    count($userDetails->personagens),
                    count($userDetails->personagens) . " de 15 tripulantes recrutados",
                    "#45859a",
                    "recrutar"
                ); ?>
            <?php endif; ?>

            <?php if ($userDetails->lvl_mais_forte < 50) : ?>
                <?php render_progress(
                    "progress-mais-forte",
                    $userDetails->lvl_mais_forte / 50,
                    $userDetails->lvl_mais_forte,
                    "Personagem mais forte no nível " . $userDetails->lvl_mais_forte . " de 50",
                    "#c04000",
                    "status"
                ); ?>
            <?php endif; ?>

            <?php $rep_mais_forte = $connection->run(
                "SELECT max(reputacao) AS total FROM tb_usuarios"
            )->fetch_array()["total"]; ?>
            <?php if (count($userDetails->personagens) >= 15) : ?>
                <?php render_progress(
                    "progress-reputacao",
                    $userDetails->tripulacao["reputacao"] / $rep_mais_forte,
                    mascara_numeros_grandes($userDetails->tripulacao["reputacao"]),
                    mascara_numeros_grandes($userDetails->tripulacao["reputacao"]) . " pontos de reputação",
                    "#62c462",
                    "ranking"
                ); ?>
            <?php endif; ?>

            <?php if ($userDetails->lvl_mais_forte >= 50) : ?>
                <?php $fa_mais_forte = $connection->run(
                    "SELECT max(fama_ameaca) AS total FROM tb_personagens"
                )->fetch_array()["total"]; ?>
                <?php render_progress(
                    "progress-wanted",
                    $userDetails->fa_mais_alta / $fa_mais_forte,
                    abrevia_numero_grande(calc_recompensa($userDetails->fa_mais_alta)),
                    "Recompensa mais alta de " . abrevia_numero_grande(calc_recompensa($userDetails->fa_mais_alta)),
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
                "$missoes_concluidas missões concluídas na ilha",
                "#45859a",
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
                "#c04000",
                "missoes"
            ); ?>
        </div> -->

        <?php $is_dbl = $connection->run("SELECT `id`,`data_inicio`,`data_fim` FROM tb_vip_dobro WHERE NOW() BETWEEN data_inicio AND data_fim LIMIT 1"); ?>
        <?php if ($is_dbl->count()) { ?>
            <?php $is_dbl = $is_dbl->fetch_array(); ?>
            <div class="alert alert-info">
                <b>!!! PROMOÇÃO !!!</b><br />
                Estamos com uma promoção de Gold em DOBRO ativa de <b>
                    <?= date('d/m/Y à\s H:i:s', strtotime($is_dbl['data_inicio'])); ?>
                </b> até <b>
                    <?= date('d/m/Y à\s H:i:s', strtotime($is_dbl['data_fim'])); ?>
                </b>.<br />
                Clique <a href="./?ses=vipComprar" class="link_content">aqui</a> e aproveite essa oportunidade.
            </div>
        <?php } ?>

        <?php if (in_array($userDetails->tripulacao["id"], $rdp) || in_array($userDetails->tripulacao["id"], $adf)) : ?>
            <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_era WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
            <?php if (! $recompensa) : ?>
                <div class="alert alert-info" style="margin-bottom: 10px;;">
                    <b>Parabéns!</b> Você foi um dos melhores na Grande Era dos Piratas! Clique <a
                        href="link_Eventos/recompensa_era.php" class="alert-link link_send">aqui</a> para receber
                    sua recompensa.
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (in_array($userDetails->tripulacao["id"], $yonkou) || in_array($userDetails->tripulacao["id"], $almirante)) : ?>
            <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_grandes_poderes WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
            <?php if (! $recompensa) : ?>
                <div class="alert alert-info" style="margin-bottom: 10px;;">
                    <b>Parabéns!</b> Você foi um dos melhores na Batalha dos Grandes Poderes! Clique <a
                        href="link_Eventos/recompensa_grandes_poderes.php" class="alert-link link_send">aqui</a>
                    para receber sua recompensa.
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (in_array($userDetails->tripulacao["id"], $incursao)) : ?>
            <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_incursao WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
            <?php if (! $recompensa) : ?>
                <div class="alert alert-info" style="margin-bottom: 10px;;">
                    <b>Parabéns!</b> Você foi um dos ganhadores da Incursão Especial! Clique <a
                        href="link_Eventos/recompensa_incursao.php" class="alert-link link_send">aqui</a> para
                    receber sua recompensa.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-3">
            <?php render_painel_yonkou($yonkou[0], $yonkou[1], $yonkou[2], $yonkou[3]); ?>
        </div>
        <div class="col-xs-3">
            <?php render_painel_rdp($rdp[0]); ?>
        </div>
        <div class="col-xs-3">
            <?php render_painel_adf($adf[0]); ?>
        </div>
        <div class="col-xs-3">
            <?php render_painel_almirante($almirante[0], $almirante[1], $almirante[2], $almirante[3]); ?>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <h3 class="text-center">Noticías</h3>
            </div>
            <?php
            $pagina = 1;
            $topicos_por_pagina = 30;

            if (isset($_GET["pagina"]) && validate_number($_GET["pagina"])) {
                $pagina = $_GET["pagina"];
            }
            ?>
            <?php $topicos = $connection->run(
                "SELECT *, (SELECT count(*) FROM tb_noticia_comment c WHERE n.cod_noticia = c.noticia_id) AS comentarios
             FROM tb_noticias n
             LEFT JOIN tb_noticia_lida l ON l.noticia_id = n.cod_noticia AND l.tripulacao_id = ?
             ORDER BY n.criacao DESC
             LIMIT ?, ?",
                "iii", array($userDetails->tripulacao["id"], ($pagina - 1) * $topicos_por_pagina, $topicos_por_pagina)
            ); ?>
            <div class="list-group">
                <?php while ($noticia = $topicos->fetch_array()) : ?>
                    <a class="list-group-item link_content" href="./?ses=noticia&cod=<//?= $noticia["cod_noticia"] ?>">
                        <div class="media">
                            <div class="media-left hidden-xs">
                                <?php if ($noticia["banner"]) : ?>
                                    <img src="<?= $noticia["banner"] ?>"
                                        style="display: block;object-fit: cover;height: 70px; width: 200px;">
                                <?php endif; ?>
                            </div>
                            <p class="visible-xs">
                                <?php if ($noticia["banner"]) : ?>
                                    <img src="<?= $noticia["banner"] ?>"
                                        style="display: block;object-fit: cover;height: 70px; width: 200px; margin: auto">
                                <?php endif; ?>
                            </p>
                            <div class="media-body" style="vertical-align: middle;">
                                <h5 class="list-group-item-heading">
                                    <div class="<?= ! $noticia["data_leitura"] ? "text-warning" : "" ?>">
                                        <?= $noticia["nome"] ?>
                                        <?php if (! $noticia["data_leitura"]) : ?>
                                            <span class="label label-warning">Novo!</span>
                                        <?php endif; ?>
                                    </div>
                                </h5>
                                <p class="pull-right">
                                    <i class="fa fa-comment-o"></i>
                                    <?= $noticia["comentarios"] ?>
                                </p>
                                <p class="list-group-item-text">
                                    <?= date("d/m/Y H:m", strtotime($noticia["criacao"])) ?>
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
                <?php if ($pagina > 1) : ?>
                    <button class="btn btn-info link_content" href="./?ses=noticias&pagina=<?= $pagina - 1 ?>">
                        <i class="fa fa-chevron-left"></i> Anterior
                    </button>
                <?php endif; ?>

                Página
                <?= $pagina ?>

                <button class="btn btn-info link_content" href="./?ses=noticias&pagina=<?= $pagina + 1 ?>">
                    Próxima <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Os Mais Procurados</div>
                <?php render_procurados(FACCAO_PIRATA); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">As Maiores Recompensas da Cross Guild</div>
                <?php render_procurados(FACCAO_MARINHA); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading" style="display: flex; align-items: center; justify-content: center;">
                    <img src="Imagens/Icones/road-poneglyph.png" width="15vw" />
                    Road Poneglyphs
                </div>
                <?php render_top_ranking_reputacao("reputacao", array(FACCAO_PIRATA, FACCAO_MARINHA), 8); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading" style="display: flex; align-items: center; justify-content: center;">
                    <img src="Imagens/Icones/poneglyph.png" width="15vw" />
                    Poneglyphs
                </div>
                <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_PIRATA, FACCAO_MARINHA), 8); ?>
            </div>
        </div>
    </div>

    <!-- <h3>As Maiores Alianças & Frotas</h3>
    <div class="row">
        <div class="col-xs-12">
            <div class="list-group">
                <?php $result = $connection->run(
                    "SELECT
                    alianca.nome AS nome,
                    alianca.score AS score,
                    alianca.vitorias AS vitorias,
                    usr.faccao AS faccao
                    FROM tb_alianca alianca
                    INNER JOIN tb_alianca_membros allymemb ON alianca.cod_alianca = allymemb.cod_alianca AND allymemb.autoridade='0'
                    INNER JOIN tb_usuarios usr ON allymemb.id = usr.id AND usr.adm='0'
                    ORDER BY alianca.score DESC LIMIT 6"
                ); ?>

                <?php while ($alianca = $result->fetch_array()) : ?>
                    <div class="list-group-item col-xs-6 col-sm-4 col-md-3">
                        <h4>
                            <img src="Imagens/Icones/Bandeira_<?= $alianca["faccao"] ?>.jpg"
                                style="margin-right: 5px; vertical-align: -2px;" class="hidden-xs" />
                            <?= $alianca["nome"] ?>
                        </h4>
                        <p>Reputação:
                            <?= $alianca["score"] ?><br />
                            Vitórias:
                            <?= $alianca["vitorias"] ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div> -->

    <?php $result = $connection->run(
        "SELECT
              log.combate AS combate_id,
              log.vencedor AS vencedor,
              log.id_1 AS id_1,
              log.id_2 AS id_2,
              log.tipo AS tipo,
              usr_1.tripulacao AS tripulacao_1,
              usr_1.faccao AS faccao_1,
              usr_1.bandeira AS bandeira_1,
              usr_2.tripulacao AS tripulacao_2,
              usr_2.faccao AS faccao_2,
              usr_2.bandeira AS bandeira_2,
              combate.combate AS andamento
             FROM tb_combate_log log
             LEFT JOIN tb_combate combate ON log.combate=combate.combate
             INNER JOIN tb_usuarios usr_1 ON log.id_1 = usr_1.id
             INNER JOIN tb_usuarios usr_2 ON log.id_2 = usr_2.id
             ORDER BY log.horario DESC LIMIT 6"
    );

    if ($result->count() > 0) :

        ?>

        <div>
            <div class="panel panel-heading">
                <h3><i class="fa fa-file-text" aria-hidden="true"></i> Últimos Combates</h3>
            </div>
            <div class="list-group" style="margin-top: 5px">
                <?php while ($combate = $result->fetch_array()) : ?>
                    <div class="list-group-item col-sm-6 col-md-4" style="display: flex; flex-direction: column; padding: 0;">
                        <div class="row" style="display: flex; justify-content: center; align-items: center; padding: 4px;">
                            <div class="col-xs-3 col-md-3"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <span style="font-size: 0.9rem;"><?= $combate["tripulacao_1"] ?></span>
                                <?php if ($combate["vencedor"]) : ?>
                                    <?= $combate["vencedor"] == $combate["id_1"] ? "<span class='text-vitoria'>Vitória</span>" : "<span class='text-derrota'>Derrota</span>" ?>
                                <?php endif; ?>
                                <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_1"] ?>&f=<?= $combate["faccao_1"] ?>"
                                    style="width:100%; max-width: 75px;" />
                            </div>
                            <div class="col-xs-4 col-md-3"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 0;">
                                <?php if ($userDetails->tripulacao["adm"] || $combate["andamento"]) : ?>
                                    <a style="font-size: 1.2em; "
                                        href="./?ses=combateAssistir&combate=<?= $combate["combate_id"]; ?>" class="link_content"
                                        title="Assista essa partida">
                                        Assistir
                                    </a>
                                <?php endif; ?>
                                <span
                                    style="font-size: 1.2rem; margin-top: 10px"><?= ucwords(nome_tipo_combate($combate["tipo"])) ?></span>
                                <img src="./Imagens/batalha/vs.png" alt="" width="30%">
                            </div>
                            <div class="col-xs-3 col-md-3"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column; padding: 0;">
                                <span style="font-size: 0.9rem;"><?= $combate["tripulacao_2"] ?></span>
                                <?php if ($combate["vencedor"]) : ?>
                                    <?= $combate["vencedor"] == $combate["id_2"] ? "<span class='text-vitoria'>Vitória</span>" : "<span class='text-derrota'>Derrota</span>" ?>
                                <?php endif; ?>
                                <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_2"] ?>&f=<?= $combate["faccao_2"] ?>"
                                    style="width:100%; max-width: 75px;" />
                            </div>
                        </div>

                    </div>
                <?php endwhile; ?>
            </div>
            <div class="col-md-12">

                <?php while ($combate = $result->fetch_array()) : ?>
                    <div class="list-group-item col-sm-6 col-md-6">
                        <div class="row">
                            <?php if ($userDetails->tripulacao["adm"] || $combate["andamento"]) : ?>
                                <a href="./?ses=combateAssistir&combate=<?= $combate["combate_id"]; ?>" class="link_content"
                                    title="Assista essa partida">
                                    <div>
                                        Assistir
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-xs-4"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <?= $combate["tripulacao_1"] ?><br />
                                <?= $combate["vencedor"] == $combate["id_1"] ? "<span class='text-warning'>Vencedor</span>" : "--" ?>
                            </div>
                            <div class="col-xs-4">
                                VS
                            </div>
                            <div class="col-xs-4"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <?= $combate["tripulacao_2"] ?><br />
                                <?= $combate["vencedor"] == $combate["id_2"] ? "<span class='text-warning'>Vencedor</span>" : "--" ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_1"] ?>&f=<?= $combate["faccao_1"] ?>"
                                    style="width:100%; max-width: 95px;" />
                            </div>
                            <div class="col-xs-4">
                            </div>
                            <div class="col-xs-4">
                                <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_2"] ?>&f=<?= $combate["faccao_2"] ?>"
                                    style="width:100%; max-width: 95px;" />
                            </div>
                        </div>
                        <div>
                            <p><?= ucwords(nome_tipo_combate($combate["tipo"])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    <?php endif; ?>
</div>
