<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (! $pers) {
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
<?php if (! $pers["classe"]) : ?>
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
                    Mestre em espadas. Atacar é a única lei. <br />
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
            && $pers[nome_atributo_tabela($skill["requisito_atr_1"])] >= $skill["requisito_atr_1_qnt"]
            && $userDetails->tripulacao["berries"] >= $skill["requisito_berries"]
            && $pers["classe"] == $skill["requisito_classe"];
    }; ?>
    <?php render_habilidades_classe_tab($skills_classe[$pers["classe"]], $pers, "Academia/aprender_skil.php", $pode_aprender_func) ?>

    <h3>
        <b>Score:</b>
        <?= $pers["classe_score"]; ?>
        <?= ajuda_tooltip("O Score aumenta quando o tripulante atacar em combate e diminui quando ele for atacado.
    Espadachins ganham 1% de bônus de Ataque para cada 10 mil pontos de Score.
    Lutadores ganham 1% de bônus de Defesa para cada 10 mil pontos de Score.
    Atiradores ganham 1% de bônus de Precisão e 1% de bônus de Destreza para cada 10 mil pontos de Score.") ?>
    </h3>
    <p>
        <button class="link_confirm btn btn-info" <?= ($userDetails->conta["gold"] >= PRECO_GOLD_RESET_CLASSE ? "" : "disabled") ?>
            data-question="Resetar a classe desse personagem permitirá que ele aprenda uma nova. Deseja continuar?"
            href="Vip/reset_classe.php?cod=<?= $pers["cod"] ?>&tipo=gold">
            <?= PRECO_GOLD_RESET_CLASSE ?> <img src="Imagens/Icones/Gold.png" />
            Trocar de Classe
        </button>
        <button class="link_confirm btn btn-info" <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_CLASSE ? "" : "disabled" ?>
            data-question="Resetar a classe desse personagem permitirá que ele aprenda uma nova. Deseja continuar?"
            href="Vip/reset_classe.php?cod=<?= $pers["cod"] ?>&tipo=dobrao">
            <?= PRECO_DOBRAO_RESET_CLASSE ?> <img src="Imagens/Icones/Dobrao.png" />
            Trocar de Classe
        </button>
    </p>
<?php endif; ?>
<?php render_personagem_panel_bottom() ?>

