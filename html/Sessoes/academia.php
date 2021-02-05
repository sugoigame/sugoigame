<div class="panel-heading">
    Academia
</div>

<div class="panel-body clearfix">
    <?= ajuda("Academia", "Na academia você ensina as classes de batalha aos seus personagens, espadachim, atirador e lutador.<br>
            Cada classe tem 3 sequências de habilidades, sendo que não é possível aprender a última habilidade de
            mais de uma sequência, então escolha sua build de atributos a partir daqui.") ?>
    <script type="text/javascript">
        $(function () {
            $(".aprender_classe").click(function () {
                var locale = $(this).attr("href");
                bootbox.confirm({
                    message: "Aprender essa classe?",
                    buttons: {
                        confirm: {
                            label: 'Sim',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Não',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            sendGet(locale);
                        }
                    }
                });
            });
        });
    </script>

    <div>
        <?php render_personagens_pills() ?>
    </div>

    <div class="tab-content">
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
        <?php foreach ($userDetails->personagens as $index => $pers): ?>
            <?php render_personagem_panel_top($pers, $index) ?>
            <?php render_personagem_sub_panel_with_img_top($pers); ?>
            <div class="panel-body">
                <?php if (!$pers["classe"]): ?>
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
                                    class="btn btn-success aprender_classe">
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
                                    class="btn btn-success aprender_classe">
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
                                    class="btn btn-success aprender_classe">
                                Se tornar um Atirador
                            </button>
                        </div>
                    </div>
                <?php else : ?>
                    <h4><?= nome_classe($pers["classe"]) ?></h4>
                    <p>
                        <b>Score:</b> <?= $pers["classe_score"]; ?>
                    </p>
                    <p>
                        <button class="link_confirm btn btn-info" <?= $userDetails->conta["gold"] >= PRECO_GOLD_RESET_CLASSE ? "" : "disabled" ?>
                                data-question="Resetar a classe desse personagem permitirá que ele aprenda uma nova. Deseja continuar?"
                                href="Vip/reset_classe.php?cod=<?= $pers["cod"] ?>&tipo=gold">
                            <?= PRECO_GOLD_RESET_CLASSE ?> <img src="Imagens/Icones/Gold.png"/>
                            Resetar a classe
                        </button>
                        <button class="link_confirm btn btn-info" <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_CLASSE ? "" : "disabled" ?>
                                data-question="Resetar a classe desse personagem permitirá que ele aprenda uma nova. Deseja continuar?"
                                href="Vip/reset_classe.php?cod=<?= $pers["cod"] ?>&tipo=dobrao">
                            <?= PRECO_DOBRAO_RESET_CLASSE ?> <img src="Imagens/Icones/Dobrao.png"/>
                            Resetar a classe
                        </button>
                    </p>
                <?php endif; ?>
            </div>
            <?php render_personagem_sub_panel_with_img_bottom(); ?>

            <?php if ($pers["classe"]): ?>
                <?php $pode_aprender_func = function ($pers, $skill) {
                    global $userDetails;
                    return $pers["lvl"] >= $skill["requisito_lvl"]
                        AND $pers[nome_atributo_tabela($skill["requisito_atr_1"])] >= $skill["requisito_atr_1_qnt"]
                        AND $userDetails->tripulacao["berries"] >= $skill["requisito_berries"]
                        AND $pers["classe"] == $skill["requisito_classe"];
                }; ?>
                <h4>Atenção! Você só pode escolher uma habilidade por linha.</h4>
                <?php render_habilidades_classe_tab($skills_classe[$pers["classe"]], $pers, "Academia/aprender_skil.php", $pode_aprender_func) ?>
            <?php endif ?>
            <?php render_personagem_panel_bottom() ?>
        <?php endforeach; ?>
    </div>
</div>