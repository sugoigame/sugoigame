<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>

<?php $skills_classe = []; ?>

<?php render_personagem_panel_top($pers, 0) ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>
                A Maestria de classe é um recurso exclusivo para tripulantes que não comeram nenhuma Akuma no Mi.
            </h4>
            <p>
                Apenas os tripulantes capazes de se dedicar em 100% a sua classe de combate podem evoluir sua Maestria e
                conseguir poder equivalente aos usuários de Akuma no Mi.
            </p>
            <?php if (!$pers["akuma"] && $pers["classe"]) : ?>
                <?php $skills_classe = get_basic_skills("requisito_classe", $pers["classe"], 0, 1); ?>
                <h4>Pontos de Maestria:</h4>
                <p>Seu tripulante ganha Pontos de Maestria toda vez que atacar em combate.</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-warning"
                         style="width: <?= $pers["maestria"] / 5000 * 100 ?>%">
                    </div>
                    <a>
                        <?= $pers["maestria"] . " / 5000" ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php if (!$pers["akuma"] && $pers["classe"]) : ?>
    <h4> Habilidades de Maestria</h4>
    <?php $pode_aprender_func = function ($pers, $skill) {
        global $userDetails;
        return $pers["lvl"] >= $skill["requisito_lvl"]
            AND $userDetails->tripulacao["berries"] >= $skill["requisito_berries"]
            AND $pers["classe"] == $skill["requisito_classe"]
            AND $pers["maestria"] >= $skill["requisito_maestria"];
    }; ?>
<div class="row">
    <?php foreach ($skills_classe as $skill): ?>
        <?php $aprendida = $connection->run("SELECT cod FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
            "iii", array($pers["cod"], $skill["cod_skil"], $skill["tiponum"]))->count(); ?>

        <?php if ($aprendida) continue; ?>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= $skill["tipo"] ?> <img src="Imagens/Skils/Tipo/<?= $skill["tipo"] ?>.png">
                        <?php if ($skill["requisito_lvl"] <= $pers["lvl"] && $skill["requisito_maestria"] <= $pers["maestria"]): ?>
                            <?= get_alert() ?>
                        <?php endif; ?>
                    </div>
                    <div class="panel-body">
                        <div>
                            <h5>Requisitos:</h5>
                            <?php render_skill_requisitos($skill, $pers) ?>
                        </div>
                        <div class="visible-xs visible-sm">
                            <button class="btn btn-info"
                                    data-toggle="popover" data-html="true" data-placement="bottom" data-trigger="focus"
                                    data-content='<div style="min-width: 250px"><?php render_skill_efeitos($skill) ?></div>'>
                                Efeitos
                            </button>
                        </div>
                        <div class="hidden-xs hidden-sm text-left">
                            <h5>Efeitos:</h5>
                            <?php render_skill_efeitos($skill) ?>
                        </div>
                        <div>
                            <?php render_new_skill_form($skill, $pers, "Academia/aprender_skil_maestria.php", $pode_aprender_func, "Aprender", true) ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php render_personagem_panel_bottom(); ?>