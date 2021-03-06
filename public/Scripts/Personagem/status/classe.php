<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>
<?php
$skills_classe = array(
    "1" => get_basic_skills("requisito_classe", 1),
    "2" => get_basic_skills("requisito_classe", 2),
    "3" => get_basic_skills("requisito_classe", 3)
);
for ($classe = 1; $classe <= 3; $classe++) {
    $skills_ordered = $skills_classe[$classe];
    $skills_1 = [];
    $skills_2 = [];
    $skills_3 = [];
    foreach ($skills_ordered as $skill) {
        $var_name = "skills_" . $skill["categoria"];
        $var = &$$var_name;
        $var[] = $skill;
    }

    $skills_classe[$classe] = array(1 => $skills_1, 2 => $skills_2, 3 => $skills_3);
}
?>
<?php render_personagem_panel_top($pers, 0) ?>
<?php if (!$pers["classe"]): ?>
    <?php render_personagem_sub_panel_with_img_top($pers); ?>
    <div class="panel-body">
        <h4>
            Este tripulante ainda não tem uma classe de combate.
            <?php $userDetails->render_alert("trip_sem_classe." . $pers["cod"]) ?>
        </h4>
        <div class="media">
            <div class="media-body">
                <h4 class="media-heading">Espadachim</h4>
                <p>
                    Mestre em espadas. Atacar é a única lei. <br/>
                    Não precisa de distância para atacar, vai à linha de frente e fatia
                    seus inimigos.
                </p>
            </div>
            <div class="media-right">
                <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=1'
                        class="btn btn-success link_confirm" data-question="Deseja se tornar um Espadachim?">
                    Se tornar um Espadachim
                </button>
            </div>
        </div>
        <div class="media">
            <div class="media-body">
                <h4 class="media-heading">Lutador</h4>
                <p>
                    Mestre do corpo. Barreira impenetrável.<br>
                    O corpo se torna capaz de dominar técnicas que aumentam sua defesa e
                    a de seus aliados.
                </p>
            </div>
            <div class="media-right">
                <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=2'
                        class="btn btn-success link_confirm" data-question="Deseja se tornar um Lutador?">
                    Se tornar um Lutador
                </button>
            </div>
        </div>
        <div class="media">
            <div class="media-body">
                <h4 class="media-heading">Atirador</h4>
                <p>
                    Mestre em armas de fogo. O aliado estratégico.<br>
                    Sua variedade de armas permite que ele possa optar entre atacar de
                    uma distância segura, fortalecer seus aliados e enfraquecer seus
                    inimigos ou até mesmo levar um canhão para batalha.
                </p>
            </div>
            <div class="media-right">
                <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=3'
                        class="btn btn-success link_confirm" data-question="Deseja se tornar um Atirador?">
                    Se tornar um Atirador
                </button>
            </div>
        </div>
    </div>
    <?php render_personagem_sub_panel_with_img_bottom(); ?>
<?php else : ?>
    <?php $pode_aprender_func = function ($pers, $skill) {
        global $userDetails;
        return $pers["lvl"] >= $skill["requisito_lvl"]
            AND $pers[nome_atributo_tabela($skill["requisito_atr_1"])] >= $skill["requisito_atr_1_qnt"]
            AND $userDetails->tripulacao["berries"] >= $skill["requisito_berries"]
            AND $pers["classe"] == $skill["requisito_classe"];
    }; ?>
    <h4>Atenção! Você só pode escolher uma habilidade por linha.</h4>
    <h4>Selos de experiência: <?= $pers["selos_xp"] ?> <img src="Imagens/Icones/seloexp.png"/></h4>
    <?php render_habilidades_classe_tab($skills_classe[$pers["classe"]], $pers, "Academia/aprender_skil.php", $pode_aprender_func) ?>
<?php endif; ?>
<?php render_personagem_panel_bottom() ?>
