<?php
function render_maior_do_mundo($tipo) {
    global $connection;

    switch ($tipo) {
        case "espadachim":  $class = 1; break;
        case "lutador":     $class = 2; break;
        default:            $class = 3; break;
    }

    $result = $connection->run(
        "SELECT p.*, u.*, IF(p.sexo =0, t.nome, t.nome_f) AS titulo_nome FROM tb_personagens p
            LEFT JOIN tb_titulos t ON p.titulo = t.cod_titulo
            INNER JOIN tb_usuarios u ON p.id = u.id
            WHERE u.adm = 0 AND p.classe = ? ORDER BY classe_score DESC LIMIT 1",
        "i", $class
    );
?>
    <div class="col-sm-4 col-md-4">
        <?php if ($result->count()): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Melhor <?= ucfirst($tipo); ?>
                </div>
                <div class="pane-body">
                    <?php $famoso = $result->fetch_array(); ?>
                    <div class="div-top">
                        <?php render_top_player($famoso, "O maior $tipo do mundo"); ?>
                    </div>
                    <img src="Imagens/Bandeiras/img.php?cod=<?= $famoso["bandeira"]; ?>&f=<?= $famoso["faccao"]; ?>"/>
                </div>
            </div>
        <?php endif; ?>
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
    <style type="text/css">
        <?php include "CSS/home.css"; ?>
    </style>
    <div class="row">
        <div class="col-md-8">
            <div class="text-left">
                <h3 class="text-center">Mural de Noticías</h3>
            </div>
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
                       href="./?ses=noticia&cod=<?= $noticia["cod_noticia"] ?>">
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
                            <div class="media-body" style="vertical-align: middle;">
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
            </div>
        </div>

        <div class="col-md-4">
            <h3>Os Melhores</h3>
            <?php render_top_ranking_reputacao("reputacao", array(FACCAO_PIRATA, FACCAO_MARINHA)); ?>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <?php render_painel_rdp($rdp[0]); ?>
        </div>
        <div class="col-sm-6 col-md-3">
            <?php render_painel_adf($adf[0]); ?>
        </div>
        <div class="col-md-6">
            <div class="row">
                <?php render_maior_do_mundo("espadachim"); ?>
                <?php render_maior_do_mundo("lutador"); ?>
                <?php render_maior_do_mundo("atirador"); ?>
            </div>
            <!-- <p>Os primeiros tripulantes a conseguir 15.000 de Score em uma Era são eleitos os melhores do mundo.</p> -->
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?php render_painel_yonkou($yonkou[0], $yonkou[1], $yonkou[2], $yonkou[3]); ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?php render_painel_almirante($almirante[0], $almirante[1], $almirante[2], $almirante[3]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h3>Grande Era dos Piratas</h3>
            <div class="row">
                <div class="col-xs-12 col-md-6" style="margin-bottom: 10px;">
                    <?php render_top_ranking_reputacao("reputacao", array(FACCAO_PIRATA), 4); ?>
                </div>
                <div class="col-xs-12 col-md-6" style="margin-bottom: 10px;">
                    <?php render_top_ranking_reputacao("reputacao", array(FACCAO_MARINHA), 4); ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Grandes Poderes</h3>
            <div class="row">
                <div class="col-xs-12 col-md-6" style="margin-bottom: 10px;">
                    <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_PIRATA), 4); ?>
                </div>
                <div class="col-xs-12 col-md-6" style="margin-bottom: 10px;">
                    <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_MARINHA), 4); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>Os Mais Procurados</h3>
            <?php render_procurados(FACCAO_PIRATA); ?>
        </div>
        <div class="col-md-6">
            <h3>As Maiores Gratificações</h3>
            <?php render_procurados(FACCAO_MARINHA); ?>
        </div>
    </div>

    <h3>As Maiores Alianças & Frotas</h3>
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

                <?php while ($alianca = $result->fetch_array()): ?>
                    <div class="list-group-item col-xs-6 col-sm-4 col-md-3">
                        <h4>
                            <img src="Imagens/Icones/Bandeira_<?= $alianca["faccao"] ?>.jpg" style="margin-right: 5px; vertical-align: -2px;" class="hidden-xs" />
                            <?= $alianca["nome"] ?>
                        </h4>
                        <p>Reputação: <?= $alianca["score"] ?><br />
                           Vitórias: <?= $alianca["vitorias"] ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
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

        if($result->count() > 0):
        
        ?>
    <h3>Últimos Combates</h3>
    <div class="list-group">

        <?php while ($combate = $result->fetch_array()): ?>
            <div class="list-group-item col-sm-6 col-md-4">
                <p>
                    <?php if ($userDetails->tripulacao["adm"] || $combate["andamento"]): ?>
                        <a href="./?ses=combateAssistir&combate=<?= $combate["combate_id"]; ?>" class="link_content" title="Assista essa partida">
                            Assistir
                        </a>
                    <?php endif; ?>
                </p>
                <div class="row">
                    <div class="col-xs-5">
                        <?= $combate["tripulacao_1"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_1"] ? "<span class='text-warning'>Vencedor</span>" : "--" ?>
                    </div>
                    <div class="col-xs-2">
                        VS
                    </div>
                    <div class="col-xs-5">
                        <?= $combate["tripulacao_2"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_2"] ? "<span class='text-warning'>Vencedor</span>" : "--" ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_1"] ?>&f=<?= $combate["faccao_1"] ?>" style="width:100%; max-width: 95px;" />
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-5">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_2"] ?>&f=<?= $combate["faccao_2"] ?>" style="width:100%; max-width: 95px;" />
                    </div>
                </div>
                <div><?= ucwords(nome_tipo_combate($combate["tipo"])) ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php endif; ?>
</div>
