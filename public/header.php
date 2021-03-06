<?php
function get_berries() {
    global $userDetails;

    return mascara_berries($userDetails->tripulacao["berries"]);
}
function get_current_mar() {
    global $userDetails;

    return nome_mar($userDetails->ilha["mar"]);
}
function get_current_ilha() {
    global $userDetails;

    return nome_ilha($userDetails->ilha["ilha"]);
}
function has_mapa() {
    global $userDetails, $connection;

    return $connection->run("SELECT * FROM tb_usuario_itens WHERE tipo_item = 2 AND id= ?", "i", $userDetails->tripulacao["id"])->count();
}
?>
<nav class="header-navbar navbar navbar-default">
    <div class="container-fluid">
        <div>
            <ul class="nav navbar-nav">
                <li class="hidden-sm hidden-xs" data-toggle="tooltip" title="Estamos em fase BETA!" data-placement="bottom">
                    <a href="./?ses=beta" class="link_content">
                        BETA
                    </a>
                </li>
                <li data-toggle="tooltip" title="A bandeira da sua tripulação" data-placement="bottom">
                    <a class="link_content" href="./?ses=bandeira">
                        <img height="21px" src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>"/>
                    </a>
                </li>
                <li id="div_icon_coordenada" data-toggle="tooltip" title="Localização atual" data-placement="bottom">
                    <a>
                        <img src="Imagens/Icones/Pose.png" height="21px" />
                        <span id="location"><?=get_current_location();?></span>,
                        <span id="destino_mar"><?=get_current_mar();?></span> -
                        <span id="destino_ilha"><?=get_current_ilha();?></span>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php $participante = $connection->run(
                    "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1",
                    "i", array($userDetails->tripulacao["id"])
                ); ?>
                <?php if ($participante->count() && $participante->fetch_array()["na_fila"]): ?>
                    <li id="div_icon_torneio" data-toggle="tooltip"
                        title="Você está a procura de um oponente no Torneio PvP"
                        data-placement="bottom">
                        <a href="./?ses=torneio" class="link_content">
                            <i class="fa fa-bolt"></i>
                            <span class="badge"><i class="fa fa-eye"></i></span>
                        </a>
                    </li>
                <?php endif; ?>
                <li id="div_icon_coliseu" data-toggle="tooltip"
                    title="Localizador PvP<?= $userDetails->fila_coliseu
                        ? ($userDetails->fila_coliseu["pausado"]
                            ? " - O Localizador foi pausado enquanto você executa uma ação importante."
                            : ($userDetails->fila_coliseu["desafio"]
                                ? " - Adversário encontrado!"
                                : " - Você está procurando um adversário"))
                        : (is_coliseu_aberto()
                            ? " - O Coliseu está aberto!"
                            : ""); ?>" data-placement="bottom">
                    <a href="./?ses=<?= is_coliseu_aberto() ? "coliseu" : "localizadorCasual"; ?>" class="link_content">
                        <i class="glyphicon glyphicon-fire fa-fw"></i>
                        <?php if ($userDetails->fila_coliseu) : ?>
                            <?php if ($userDetails->fila_coliseu["pausado"]) : ?>
                                <span class="badge badge-alert"><i class="fa fa-pause"></i></span>
                            <?php elseif ($userDetails->fila_coliseu["desafio"]) : ?>
                                <span class="badge badge-alert"><i class="fa fa-fire"></i></span>
                            <?php else : ?>
                                <span class="badge"><i class="fa fa-eye"></i></span>
                            <?php endif; ?>
                        <?php elseif (is_coliseu_aberto()) : ?>
                            <span class="badge"><i class="fa fa-bolt"></i></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li id="div_icon_daily_gift" data-toggle="tooltip" title="Presentes e Eventos"
                    data-placement="bottom">
                    <a href="#" class="noHref" data-toggle="modal" data-target="#modal-daily-gift">
                        <i class="fa fa-calendar-check-o"></i>
                        <?php $novos_mini_eventos = $connection->run("SELECT count(*) AS total FROM tb_mini_eventos WHERE inicio > DATE_SUB(NOW(), INTERVAL 5 MINUTE) ")->fetch_array()["total"]; ?>
                        <?php if (!$userDetails->tripulacao["presente_diario_obtido"]) : ?>
                            <span class="badge">1</span>
                        <?php elseif ($novos_mini_eventos) : ?>
                            <span class="badge"><?= $novos_mini_eventos ?></span>
                        <?php endif; ?>

                    </a>
                </li>
                <?php if (count($userDetails->buffs->buffs_ativos)): ?>
                    <li data-toggle="tooltip" title="Bonus ativos"
                        data-placement="bottom">
                        <a href="#" class="noHref" data-content="-" data-container="#tudo" data-toggle="popover"
                           data-placement="bottom"
                           data-trigger="focus"
                           data-html="true"
                           data-template='
                        <div class="container info-buff-tripulacao">
                            <?php foreach ($userDetails->buffs->buffs_ativos as $buff): ?>
                            <?php $expiracao = ($buff["expiracao"] - atual_segundo()) - 1; ?>
                            <?php $horas = floor($expiracao / (60 * 60)); ?>
                            <div class="row">
                                <div class="col-xs-2">
                                    <img src="Imagens/Icones/<?= $buff["icon"] ?>" />
                                </div>
                                <div class="col-xs-10">
                                    <p><small><?= $buff["descricao"] ?></small></p>
                                </div>
                                <div class="col-xs-12">
                                    <p>Expira em <?= $horas == "00" ? "24" : $horas ?> horas e <?= date("i", $expiracao) ?> minutos</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>'>
                            <img height="21px" src="Imagens/Icones/bonus.jpg"/>
                        </a>
                    </li>
                <?php endif; ?>
                <li id="div_icon_progress" data-toggle="tooltip" title="Missões auxiliares"
                    data-placement="bottom">
                    <?php $progress_info = $userDetails->get_progress_info(); ?>
                    <?php if ($progress_info) : ?>
                        <?php $progress_reward = $userDetails->get_progress_reward(); ?>
                        <?php $finished = $userDetails->is_progress_finished(); ?>
                        <a href="#"
                           class="noHref <?= $finished ? 'user-progress-finished' : '' ?> user-progress-<?= $userDetails->tripulacao["progress"] ?>"
                           data-title="<?= $progress_info["title"] ?>"
                           data-description="<?= $progress_info["description"] ?><?= $finished ? "<br/><br/>" . $progress_info["finished"] : "" ?>"
                           data-xp="<?= $progress_reward["xp"] ?>"
                           data-berries="<?= $progress_reward["berries"] ?>"
                           data-finished="<?= $finished ? "true" : "false" ?>">
                            <img height="21px" src="Imagens/Icones/missao-<?= $finished ? "2" : "0" ?>.png">
                        </a>
                    <?php endif; ?>
                </li>
                <?php if (has_mapa()) : ?>
                    <li id="div_icon_cartografo" data-toggle="tooltip" title="Mapa Mundi" data-placement="bottom">
                        <a href="#" class="noHref" data-toggle="modal" data-target="#modal-cartografo">
                            <img height="21px" src="Imagens/Icones/Mapa.png"/>
                        </a>
                    </li>
                <?php else: ?>
                    <li data-toggle="tooltip" title="Mapa Mundi" data-placement="bottom">
                        <a href="#" class="noHref" data-toggle="modal" data-target="#modal-no-cartografo">
                            <img height="21px" src="Imagens/Icones/Mapa.png"/>
                        </a>
                    </li>
                <?php endif; ?>
                <li id="div_icon_denden" class="div_icon" data-toggle="tooltip" title="Mensagens"
                    data-placement="bottom">
                    <?php $lido = has_mensagem(); ?>
                    <a href="#" class="noHref" data-toggle="modal" data-target="#modal-mensagens">
                        <img height="21px" id="denden_mushi" src="Imagens/Icones/Denden_<?= $lido; ?>.png"
                             alt="Den Den Mushi"/>
                        <?php if ($lido == 0) : ?>
                            <script type="text/javascript">
                                n_puru(<?= $userDetails->in_combate ? 'true' : 'false' ?>);
                            </script>
                        <?php endif; ?>
                    </a>
                </li>
                <?php $novos = $connection->run("SELECT SUM(novo) AS total FROM tb_usuario_itens WHERE id = ?",
                    "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"]; ?>
                <li id="div_icon_inventario" class="div_icon" data-toggle="tooltip" title="Inventário"
                    data-placement="bottom">
                    <a href="#" class="noHref" data-toggle="modal" data-target="#modal-inventario">
                        <img height="21px" id="icon_iventario" src="Imagens/Icones/Bau.png" alt="Inventário"/>
                        <?php if ($novos) : ?>
                            <span class="badge"><?= $novos ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li id="div_icon_berries" class="div_icon" data-toggle="tooltip" title="Berries" data-placement="bottom">
                    <a>
                        <img height="21px" src="Imagens/Icones/Berries.png"/>
                        <span id="span_berries"> <?= mascara_numeros_grandes($userDetails->tripulacao["berries"]); ?> </span>
                    </a>
                </li>
                <li id="div_icon_gold" data-toggle="tooltip" title="Moedas de Ouro" data-placement="bottom">
                    <a href="link_vipComprar" class="link_content2">
                        <img height="21px" src="Imagens/Icones/Gold.png"/>
                        <span id="span_gold"><?= mascara_numeros_grandes($userDetails->conta["gold"]); ?></span>
                    </a>
                </li>
                <li id="div_icon_gold" data-toggle="tooltip" title="Dobrões de Ouro" data-placement="bottom">
                    <!-- <a href="link_leiloes" class="link_content2"> -->
                    <a>
                        <img height="21px" src="Imagens/Icones/Dobrao.png"/>
                        <span id="span_gold"><?= mascara_numeros_grandes($userDetails->conta["dobroes"]); ?></span>
                    </a>
                    <!-- </a> -->
                </li>
            </ul>
        </div>
    </div>
</nav>


