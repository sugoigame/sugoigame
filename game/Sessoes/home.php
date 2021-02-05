<?php
function get_banners() {
    $query = "SELECT * FROM tb_noticias_banner ORDER BY `data` DESC LIMIT 3";
    $result = mysql_query($query);
    $news = [];
    while ($banner = mysql_fetch_assoc($result)) {
        $news[] = $banner;
    }

    return $news;
}

?>

<?php function render_maior_do_mundo($tipo) { ?>
    <?php global $connection; ?>
    <?php
    switch ($tipo) {
        case "espadachim":
            $cod = 249;
            break;
        case "lutador":
            $cod = 245;
            break;
        default:
            $cod = 534;
            break;
    }
    ?>
    <?php $result = $connection->run(
        "SELECT p.*, u.*, IF(p.sexo =0, t.nome, t.nome_f) AS titulo_nome FROM tb_personagens p
            LEFT JOIN tb_titulos t ON p.titulo = t.cod_titulo
            INNER JOIN tb_usuarios u ON p.id = u.id
            WHERE p.cod= ? LIMIT 1",
        "i", array($cod)
    ); ?>
    <div class="panel panel-default col-md-4">
        <div class="pane-body">
            <?php if ($result->count()): ?>
                <h5>Maior <?= $tipo ?> do mundo</h5>
                <?php $famoso = $result->fetch_array(); ?>
                <div class="div-top">
                    <?php render_top_player($famoso, "O maior $tipo do mundo"); ?>
                </div>
                <img src="Imagens/Bandeiras/img.php?cod=<?= $famoso["bandeira"]; ?>&f=<?= $famoso["faccao"]; ?>"/>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>

<?php function render_procurados($faccao) { ?>
    <?php global $connection; ?>
    <?php $result = $connection->run(
        "SELECT * 
              FROM tb_personagens pers
              INNER JOIN tb_usuarios usr ON pers.cod = usr.cod_personagem
              WHERE usr.adm='0' AND usr.faccao = '$faccao' ORDER BY pers.fama_ameaca DESC LIMIT 24"
    ); ?>

    <?php while ($famoso = $result->fetch_array()): ?>
        <?php render_cartaz_procurado($famoso, $faccao); ?>
    <?php endwhile; ?>
<?php } ?>


<?php function render_progress($id, $progress, $inner_text, $text, $color, $link) { ?>
    <div class="col-xs-12 col-sm-3">
        <div class="list-group-item" style="height: 100%;">

            <div id="<?= $id ?>"></div>
            <div>
                <a href="?ses=<?= $link ?>" class="link_content">
                    <?= $text ?>
                </a>
            </div>

            <script>
                $(function () {
                    var container = document.getElementById('<?= $id ?>');
                    var bar = new ProgressBar.Circle(container, {
                        strokeWidth: 10,
                        easing: 'easeInOut',
                        duration: 1400,
                        color: '<?= $color ?>',
                        trailColor: '#3b3b3b',
                        trailWidth: 1,
                        svgStyle: {
                            width: '4em'
                        },
                        text: {
                            value: '<?= $inner_text ?>'
                        }
                    });

                    bar.animate(<?= $progress ?>);  // Number from 0.0 to 1.0
                });
            </script>
        </div>
    </div>
<?php } ?>

<?php if ($userDetails->tripulacao && $userDetails->tripulacao["progress"] == 0): ?>
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
        margin-bottom: 10px;
        text-shadow: #000 1px -1px 2px, #000 -1px 1px 2px, #000 1px 1px 2px, #000 -1px -1px 2px;
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
    <?php if (!$conect) return; ?>
    <style type="text/css">
        <? include "CSS/home.css"; ?>
    </style>

    <?php $rdp = explode(",", get_value_variavel_global(VARIAVEL_VENCEDORES_ERA_PIRATA)["valor_varchar"]); ?>
    <?php $adf = explode(",", get_value_variavel_global(VARIAVEL_VENCEDORES_ERA_MARINHA)["valor_varchar"]); ?>
    <?php $yonkou = explode(",", get_value_variavel_global(VARIAVEL_YONKOUS)["valor_varchar"]); ?>
    <?php $almirante = explode(",", get_value_variavel_global(VARIAVEL_ALMIRANTES)["valor_varchar"]); ?>
    <?php $incursao = explode(",", get_value_variavel_global(VARIAVEL_VENCEDORES_INCURSAO)["valor_varchar"]); ?>

    <?php if (in_array($userDetails->tripulacao["id"], $rdp) || in_array($userDetails->tripulacao["id"], $adf)): ?>
        <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_era WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
        <?php if (!$recompensa): ?>
            <button class="btn btn-success link_send" href="link_Eventos/recompensa_era.php">
                Receber recompensa pela Grande Era dos Piratas
            </button>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (in_array($userDetails->tripulacao["id"], $yonkou) || in_array($userDetails->tripulacao["id"], $almirante)): ?>
        <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_grandes_poderes WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
        <?php if (!$recompensa): ?>
            <button class="btn btn-success link_send" href="link_Eventos/recompensa_grandes_poderes.php">
                Receber recompensa pela Batalha dos Grandes Poderes
            </button>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (in_array($userDetails->tripulacao["id"], $incursao)): ?>
        <?php $recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_incursao WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count(); ?>
        <?php if (!$recompensa): ?>
            <button class="btn btn-success link_send" href="link_Eventos/recompensa_incursao.php">
                Receber recompensa pela Incursão especial
            </button>
        <?php endif; ?>
    <?php endif; ?>

    <?php $convocacao_torneio = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"])); ?>
    <?php if ($convocacao_torneio->count()): ?>
        <p>
            <a class="btn btn-success link_content" href="?ses=torneio">
                Você foi convocado para o Torneio dos Melhores do Sugoi Game!
            </a>
        </p>
    <?php endif; ?>

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
    <br/>
    <h3>O que há de novo?</h3>
    <div class="row">
        <div class="col-md-3">
            <a class="link_content" href="?ses=noticia&cod=39">
                <img src="Imagens/Banners/new-5.png" style="max-width:100%;"/>
            </a>
            <h4>Maestria de Classe</h4>
            <p>
                Crie personagens mestres em suas classes tão poderosos quanto usuários de Akuma no Mi
            </p>
        </div>
        <div class="col-md-3">
            <a class="link_content" href="?ses=noticia&cod=8">
                <img src="Imagens/Banners/new-1.png" style="max-width:100%;"/>
            </a>
            <h4>Novos Equipamentos</h4>
            <p>
                Participe de eventos para conseguir Essências Verdes e criar Equipamentos mais poderosos
            </p>
        </div>
        <div class="col-md-3">
            <a class="link_content" href="?ses=noticia&cod=5">
                <img src="Imagens/Banners/new-2.png" style="max-width:100%;"/>
            </a>
            <h4>Excelência de Classe</h4>
            <p>
                Continue evoluindo após o nível 50 para ganhar Bônus em sua Classe
            </p>
        </div>
        <div class="col-md-3">
            <a class="link_content" href="?ses=noticia&cod=22">
                <img src="Imagens/Banners/new-3.png" style="max-width:100%;"/>
            </a>
            <h4>Nível de Batalha PvP</h4>
            <p>
                Ganhe Recompensas e Equipamentos ao participar de Batalhas PvP
            </p>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-8">
            <div class="text-left"><img src="Imagens/news.png"></div>
            <?php $topicos = $connection->run(
                "SELECT *, (SELECT count(*) FROM tb_noticia_comment c WHERE n.cod_noticia = c.noticia_id) AS comentarios 
                 FROM tb_noticias n
                 LEFT JOIN tb_noticia_lida l ON l.noticia_id = n.cod_noticia AND l.tripulacao_id = ?
                 ORDER BY n.criacao DESC 
                 LIMIT 5",
                "i", array($userDetails->tripulacao["id"])
            ); ?>
            <div class="list-group">
                <?php while ($noticia = $topicos->fetch_array()): ?>
                    <a class="list-group-item link_content"
                       href="?ses=noticia&cod=<?= $noticia["cod_noticia"] ?>">
                        <div class="media">
                            <div class="media-left hidden-xs">
                                <?php if ($noticia["banner"]): ?>
                                    <img src="<?= $noticia["banner"] ?>"
                                         style="display: block;object-fit: cover;height: 70px; width: 200px;">
                                <?php endif; ?>
                            </div>
                            <p class="visible-xs">
                                <?php if ($noticia["banner"]): ?>
                                    <img src="<?= $noticia["banner"] ?>"
                                         style="display: block;object-fit: cover;height: 70px; width: 200px; margin: auto">
                                <?php endif; ?>
                            </p>
                            <div class="media-body">
                                <h5 class="list-group-item-heading">
                                    <div class="<?= !$noticia["data_leitura"] ? "text-warning" : "" ?>">
                                        <?= $noticia["nome"] ?>
                                        <?php if (!$noticia["data_leitura"]): ?>
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
                <a href="?ses=noticias" class="link_content">
                    Ver notícias mais antigas
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <h3>Os mais famosos:</h3>
            <?php render_top_ranking_reputacao("reputacao", array(FACCAO_PIRATA, FACCAO_MARINHA)); ?>
            <a class="link_content" href="?ses=ranking">Ver o ranking completo</a>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-3">
            <?php render_painel_rdp($rdp[0]); ?>
        </div>
        <div class="col-md-3">
            <?php render_painel_adf($adf[0]); ?>
        </div>
        <div class="col-md-6">
            <div class="row">
                <?php render_maior_do_mundo("espadachim"); ?>
                <?php render_maior_do_mundo("lutador"); ?>
                <?php render_maior_do_mundo("atirador"); ?>
            </div>
            <div class="row">
                <p>Os primeiros tripulantes a conseguir 15.000 de Score em uma Era são eleitos os melhores do mundo.</p>
            </div>
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">Incursão Especial à Alubarna</div>
                    <div class="panel-body">
                        <?php render_campeao_incursao($incursao[0], "Primeiro Colocado"); ?>
                        <?php render_campeao_incursao($incursao[1], "Segundo Colocado"); ?>
                        <?php render_campeao_incursao($incursao[2], "Terceiro Colocado"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php render_painel_yonkou($yonkou[0], $yonkou[1], $yonkou[2], $yonkou[3]); ?>
        </div>
        <div class="col-md-6">
            <?php render_painel_almirante($almirante[0], $almirante[1], $almirante[2], $almirante[3]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h3>Grande Era dos Piratas</h3>
            <div class="row">
                <div class="col-sm-6">
                    <?php render_top_ranking_reputacao("reputacao", array(FACCAO_PIRATA), 4); ?>
                    <a class="link_content" href="?ses=ranking&faccao=1">Ver o ranking completo</a>
                </div>
                <div class="col-sm-6">
                    <?php render_top_ranking_reputacao("reputacao", array(FACCAO_MARINHA), 4); ?>
                    <a class="link_content" href="?ses=ranking&faccao=0">Ver o ranking completo</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Batalha pelos Grandes Poderes</h3>
            <div class="row">
                <div class="col-sm-6">
                    <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_PIRATA), 4); ?>
                    <a class="link_content" href="?ses=ranking&rank=reputacao_mensal&faccao=1">Ver o ranking
                        completo</a>
                </div>
                <div class="col-sm-6">
                    <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_MARINHA), 4); ?>
                    <a class="link_content" href="?ses=ranking&rank=reputacao_mensal&faccao=0">Ver o ranking
                        completo</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>Os mais procurados:</h3>
            <?php render_procurados(FACCAO_PIRATA); ?>
        </div>
        <div class="col-md-6">
            <h3>As maiores gratificações:</h3>
            <?php render_procurados(FACCAO_MARINHA); ?>
        </div>
    </div>

    <h3>As maiores alianças e frotas</h3>
    <div class="row list-group">
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

        <?php while ($alianca = $result->fetch_array()): ?>
            <div class="list-group-item col-md-4">
                <h4>
                    <img src="Imagens/Icones/Bandeira_<?= $alianca["faccao"] ?>.jpg">
                    <?= $alianca["nome"] ?>
                </h4>
                <p>Reputação: <?= $alianca["score"] ?></p>
                <p>Vitórias: <?= $alianca["vitorias"] ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h3>Excelência de Classe</h3>
            <?php $rep_mais_forte = $connection->run(
                "SELECT max(excelencia_lvl) AS total FROM tb_personagens"
            )->fetch_array()["total"]; ?>
            <?php if (!$rep_mais_forte) {
                $rep_mais_forte = 1;
            } ?>
            <?php $result = $connection->run(
                "SELECT usr.id, pers.excelencia_lvl AS reputacao, usr.faccao, usr.bandeira, usr.tripulacao, pers.img, pers.nome, pers.skin_r 
                FROM tb_usuarios usr
                INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
                WHERE usr.adm='0' ORDER BY pers.excelencia_lvl DESC LIMIT 6"
            ); ?>

            <div class="list-group">
                <?php render_top_ranking($result, $rep_mais_forte, "reputacao", "níveis de excelência"); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Nível de Batalha</h3>
            <?php $rep_mais_forte = $connection->run(
                "SELECT max(battle_lvl) AS total FROM tb_usuarios"
            )->fetch_array()["total"]; ?>
            <?php if (!$rep_mais_forte) {
                $rep_mais_forte = 1;
            } ?>
            <?php $result = $connection->run(
                "SELECT usr.id, usr.battle_lvl AS reputacao, usr.faccao, usr.bandeira, usr.tripulacao, pers.img, pers.nome, pers.skin_r 
                FROM tb_usuarios usr
                INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
                WHERE usr.adm='0' ORDER BY  usr.battle_lvl DESC LIMIT 6"
            ); ?>

            <div class="list-group">
                <?php render_top_ranking($result, $rep_mais_forte, "reputacao", "níveis de batalha"); ?>
            </div>
        </div>
    </div>

    <h3>Últimos combates:</h3>
    <div class="row list-group">
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
             ORDER BY log.horario DESC LIMIT 3"
        ); ?>
        <?php while ($combate = $result->fetch_array()): ?>
            <div class="list-group-item col-md-4">
                <p>
                    <?php if ($combate["andamento"]): ?>
                        <a href="?ses=combateAssistir&combate=<?= $combate["combate_id"]; ?>" class="link_content"
                           title="Assista essa partida">
                            Assistir
                        </a>
                    <?php endif; ?>
                </p>
                <div class="row">
                    <div class="col-md-5">
                        <?= $combate["tripulacao_1"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_1"] ? "<span class='text-warning'>Vencedor</span>" : "" ?>
                    </div>
                    <div class="col-md-2">
                        VS
                    </div>
                    <div class="col-md-5">
                        <?= $combate["tripulacao_2"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_2"] ? "<span class='text-warning'>Vencedor</span>" : "" ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_1"] ?>&f=<?= $combate["faccao_1"] ?>">
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-5">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_2"] ?>&f=<?= $combate["faccao_2"] ?>">
                    </div>
                </div>
                <div><?= nome_tipo_combate($combate["tipo"]) ?></div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
