<?php function render_ganhos($combate) { ?>
    <ul>
        <li>Aumento na recompensa: <?= mascara_berries(calc_recompensa($combate["fa_ganha"])); ?></li>
    </ul>
<?php } ?>
<?php function render_perdas($combate) { ?>
    <ul>
        <li>Redução na recompensa: <?= mascara_berries(calc_recompensa($combate["fa_perdida"])); ?></li>
    </ul>
<?php } ?>

<div class="panel-heading">
    Histórico de recompensas
</div>

<style>
    .personagem-aliado {
        border: 3px solid blue;
    }
</style>

<div class="panel-body">
    <?php $result = $connection->run(
        "SELECT 
          log.data AS horario,
          log.vencedor_cod AS vencedor_cod,
          log.perdedor_cod AS perdedor_cod,
          log.fa_ganha AS fa_ganha,
          log.fa_perdida AS fa_perdida,
          persg.nome AS vencedor_nome,
          persg.img AS vencedor_img,
          persg.skin_r AS vencedor_skin_r,
          persg.id AS vencedor_id,
          persp.nome AS perdedor_nome,
          persp.img AS perdedor_img,
          persp.skin_r AS perdedor_skin_r,
          persp.id AS perdedor_id
        FROM tb_wanted_log log
        INNER JOIN tb_personagens persg ON log.vencedor_cod = persg.cod
        INNER JOIN tb_personagens persp ON log.perdedor_cod = persp.cod
        WHERE persg.id = ? OR persp.id = ?
        ORDER BY log.data DESC LIMIT 20",
        "ii", array($userDetails->tripulacao["id"], $userDetails->tripulacao["id"])
    ); ?>
    <ul class="list-group">
        <?php if (!$result->count()): ?>
            <p>Você ainda não participou de nenhum combate</p>
        <?php endif; ?>
        <?php while ($combate = $result->fetch_array()) : ?>
            <li class="list-group-item">
                <p><?= date("d/m/Y - H:i", strtotime($combate["horario"])) ?></p>
                <div class="row">
                    <div class="col-md-5 text-right">
                        <?= $combate["vencedor_nome"] ?><br/>
                        <span class='text-warning'>Vencedor</span>
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-5 text-left">
                        <?= $combate["perdedor_nome"] ?><br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 text-right">
                        <img <?= $combate["vencedor_id"] == $userDetails->tripulacao["id"] ? 'class="personagem-aliado"' : '' ?>
                                src="Imagens/Personagens/Icons/<?= get_img(array("img" => $combate["vencedor_img"], "skin_r" => $combate["vencedor_skin_r"]), "r") ?>.jpg">
                    </div>
                    <div class="col-md-2">
                        <img src="Imagens/Batalha/vs.png">
                    </div>
                    <div class="col-md-5 text-left">
                        <img <?= $combate["vencedor_id"] == $userDetails->tripulacao["id"] ? 'class="personagem-aliado"' : '' ?>
                                src="Imagens/Personagens/Icons/<?= get_img(array("img" => $combate["perdedor_img"], "skin_r" => $combate["perdedor_skin_r"]), "r") ?>.jpg">
                    </div>
                </div>
                <div class="row text-left">
                    <div class="col-md-5">
                        <?php render_ganhos($combate); ?>
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-5">
                        <?php render_perdas($combate); ?>
                    </div>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>