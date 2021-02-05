<?php
require "../../Includes/conectdb.php";
?>

<?php function render_profissao_time_panel($label, $pers, $time, $duracao, $min_sec_id, $script_id, $cod_running, $action) { ?>
    <?php global $userDetails; ?>
    <div class="list-group-item col-md-4">
        <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg">
        <h4><?= $label ?>: <?= $pers["nome"] ?></h4>
        <?php if ($time && $time > atual_segundo()) : ?>
            <p>
                Tempo de espera até poder <?= strtolower($action) ?> novamente:
                <span id="<?= $min_sec_id ?>_min">
                    <?= transforma_tempo_min($time - atual_segundo()) ?>
                </span>
                <span id="<?= $min_sec_id ?>_sec" style="display: none;"><?= $time - atual_segundo() ?></span>
            </p>
            <?php $preco = ceil(($time - atual_segundo()) / $duracao) * 2 - 1; ?>
            <p>
                <button href="Mapa/<?= $script_id ?>_finalizar.php?pers=<?= $pers["cod"] ?>&tipo=gold"
                        class="link_confirm btn btn-info"
                        data-dismiss="modal"
                        data-question="Deseja <?= $action ?> novamente?"
                    <?= $userDetails->conta["gold"] >= $preco ? "" : "disabled" ?>>
                    <?= $preco ?> <img src="Imagens/Icones/Gold.png">
                    <?= $action ?> novamente
                </button>
            </p>
            <?php $preco = ceil($preco * 1.2); ?>
            <p>
                <button href="Mapa/<?= $script_id ?>_finalizar.php?pers=<?= $pers["cod"] ?>&tipo=dobroes"
                        class="link_confirm btn btn-info"
                        data-dismiss="modal"
                        data-question="Deseja <?= $action ?> novamente?"
                    <?= $userDetails->conta["dobroes"] >= $preco ? "" : "disabled" ?>>
                    <?= $preco ?> <img src="Imagens/Icones/Dobrao.png">
                    <?= $action ?> novamente
                </button>
            </p>
        <?php else: ?>
            <p>
                Espera:
                <?= transforma_tempo_min($duracao) ?>
            </p>
            <button href="link_Mapa/<?= $script_id ?>_finalizar.php?pers=<?= $pers["cod"] ?>&tipo=normal"
                    data-dismiss="modal"
                    class="link_send btn btn-primary">
                <?= $action ?>
            </button>
        <?php endif; ?>
    </div>
<?php } ?>
<div class="row">
    <?php if ($userDetails->mergulhadores) : ?>
        <?php foreach ($userDetails->mergulhadores as $mergulhador) : ?>
            <?php render_profissao_time_panel(
                "Mergulhador",
                $mergulhador,
                $userDetails->tripulacao["mergulho"],
                3600 - (($mergulhador["profissao_lvl"] - 1) * 180),
                "mergulhador",
                "mergulho",
                $userDetails->tripulacao["mergulho_cod"],
                "Mergulhar"
            ); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($userDetails->arqueologos) : ?>
        <?php foreach ($userDetails->arqueologos as $arqueologo) : ?>
            <?php render_profissao_time_panel(
                "Arqueólogo",
                $arqueologo,
                $userDetails->tripulacao["expedicao"],
                3600 - (($arqueologo["profissao_lvl"] - 1) * 180),
                "expedicao",
                "expedicao",
                $userDetails->tripulacao["expedicao_cod"],
                "Explorar"
            ); ?>
        <?php endforeach ?>
    <?php endif; ?>
    <?php if ($userDetails->ferreiros) : ?>
        <?php foreach ($userDetails->ferreiros as $ferreiro) : ?>
            <?php render_profissao_time_panel(
                "Ferreiro",
                $ferreiro,
                $userDetails->tripulacao["mining"],
                10 * 60,
                "mining",
                "mining",
                $userDetails->tripulacao["mining_cod"],
                "Mineirar"
            ); ?>
        <?php endforeach ?>
    <?php endif; ?>
    <?php if ($userDetails->carpinteiros) : ?>
        <?php foreach ($userDetails->carpinteiros as $carpinteiro) : ?>
            <?php render_profissao_time_panel(
                "Carpinteiro",
                $carpinteiro,
                $userDetails->tripulacao["madeira"],
                10 * 60,
                "madeira",
                "madeira",
                $userDetails->tripulacao["madeira_cod"],
                "Madeirar"
            ); ?>
        <?php endforeach ?>
    <?php endif; ?>
    <?php if ($userDetails->cartografos) : ?>
        <?php foreach ($userDetails->cartografos as $cartografo) : ?>
            <?php render_profissao_time_panel(
                "Cartógrafo",
                $cartografo,
                $userDetails->tripulacao["desenho"],
                120 - 10 * ($cartografo["profissao_lvl"] - 1),
                "desenho",
                "desenho",
                $userDetails->tripulacao["desenho_cod"],
                "Desenhar"
            ); ?>
        <?php endforeach ?>
    <?php endif; ?>
</div>