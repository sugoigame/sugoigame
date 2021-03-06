<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

if (!validate_number($_GET["cod"])) {
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = $_GET["cod"];

$result = $connection->run(
    "SELECT * 
    FROM tb_combate_personagens cbtpers
    INNER JOIN tb_personagens pers ON cbtpers.cod = pers.cod
    WHERE cbtpers.id = ? AND cbtpers.cod = ?",
    "ii", array($userDetails->tripulacao["id"], $cod)
);

if (!$result->count()) {
    echo("#Personagem inválido.");
    exit();
}
$pers = $result->fetch_array();

$skills = get_many_results_joined_mapped_by_type("tb_personagens_skil", "cod_skil", "tipo", array(
    array("nome" => "tb_skil_atk", "coluna" => "cod_skil", "tipo" => 1),
    array("nome" => "tb_skil_buff", "coluna" => "cod_skil", "tipo" => 2),
    array("nome" => "tb_skil_atk", "coluna" => "cod_skil", "tipo" => 4),
    array("nome" => "tb_skil_buff", "coluna" => "cod_skil", "tipo" => 5),
    array("nome" => "tb_akuma_skil_atk", "coluna" => "cod_skil", "tipo" => 7),
    array("nome" => "tb_akuma_skil_buff", "coluna" => "cod_skil", "tipo" => 8)
), "WHERE origem.cod = ? ORDER BY origem.tipo", "i", $pers["cod"]);


?>
<div>
    <?php render_personagem_mp_bar($pers); ?>
</div>
<div class="clearfix">
    <?php $last_tipo = NULL; ?>
    <?php foreach ($skills as $skill): ?>
        <?php
        $result = $connection->run(
            "SELECT * 
             FROM tb_combate_skil_espera 
			 WHERE cod = ? AND cod_skil = ? AND tipo = ?",
            "iii", array($cod, $skill["cod_skil"], $skill["tipo"])
        );
        $espera = $result->count() ? $result->fetch_array() : array("espera" => 0);
        ?>
        <?php if ($skill["tipo"] != $last_tipo): ?>
            <?php $last_tipo = $skill["tipo"]; ?>
            <h5><?= nome_tipo_skill($skill) ?> de <?= nome_origem_skill($skill) ?></h5>
        <?php endif; ?>
        <div class="icon-skill-cbt">
            <div>
                <a href="#" class="noHref" data-toggle="popover" data-content="-" data-placement="bottom"
                   data-html="true"
                   data-trigger="focus"
                   data-template='<div class="popover-skill">
                            <h4>
                                <?= str_replace("'", "&rsquo;", $skill["nome"]) ?>
                                <img src="Imagens/Icones/tipo_<?= nome_tipo_skill($skill) ?>.png" data-toggle="tooltip" data-placement="bottom"
                                     title="<?= nome_tipo_skill($skill) ?>"/>
                            </h4>
                            <h5><?= nome_tipo_skill($skill) ?> de <?= nome_origem_skill($skill) ?></h5>
                            <div class="text-left clearfix">
                                <?php render_skill_efeitos($skill) ?>
                                <?php if ($skill["special_effect"]): ?>
                                    <p>
                                        <?= nome_special_effect_apply_type($skill["special_apply_type"]) ?>
                                        <?= nome_special_effect($skill["special_effect"]) ?>
                                        no <?= nome_special_effect_target($skill["special_target"]) ?> da Habilidade
                                    </p>
                                    <p>
                                        <?= nome_special_effect($skill["special_effect"]) ?>
                                        : <?= descricao_special_effect($skill["special_effect"]) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                    </div>'>
                    <img src="Imagens/Skils/<?= $skill["icon"] ?>.jpg">
                </a>
            </div>
            <div>
                <?php if ($espera["espera"]): ?>
                    <p><?= $espera["espera"] ?> turno(s)</p>
                <?php elseif ($pers["mp"] < $skill["consumo"]): ?>
                    <p>Energia insuficiente</p>
                <?php elseif (!$espera["espera"] && $pers["mp"] >= $skill["consumo"]): ?>
                    <button class="btn btn-success" data-dismiss="modal"
                            onclick="usaSkil('<?= $skill["cod_skil"]; ?>','<?= $pers["cod"]; ?>','<?= $skill["alcance"]; ?>', '<?= $skill["tipo"]; ?>','<?= $skill["area"]; ?>')">
                        Usar
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ($pers["profissao"] == PROFISSAO_MEDICO) : ?>

        <?php $items = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_remedio", "cod_remedio", TIPO_ITEM_REMEDIO,
            "WHERE origem.id=?", "i", $userDetails->tripulacao["id"]); ?>

        <h5>Remédios</h5>
        <?php foreach ($items as $item) : ?>
            <?php
            $result = $connection->run(
                "SELECT * 
                 FROM tb_combate_skil_espera 
                 WHERE id = ? AND tipo = ?",
                "ii", array($userDetails->tripulacao["id"], 10)
            );
            $espera = $result->count() ? $result->fetch_array() : array("espera" => 0);
            $consumo = ($item["hp_recuperado"] + $item["mp_recuperado"])
            ?>
            <div class="icon-skill-cbt">
                <div>
                    <a href="#" class="noHref" data-toggle="popover" data-content="-" data-placement="bottom"
                       data-html="true"
                       data-trigger="focus"
                       data-template='<div class="popover-skill">
                            <?= info_item($item, $item, FALSE, FALSE, FALSE) ?>
                            <p>Espera: 2 turnos</p>
                            <p>Consumo: <?= $consumo ?></p>
                        </div>'>
                        <img src="Imagens/Itens/<?= $item["img"] ?>.png">
                    </a>
                </div>
                <div>
                    <?php if ($espera["espera"]): ?>
                        <p><?= $espera["espera"] ?> turno(s)</p>
                    <?php elseif ($pers["profissao_lvl"] < $item["requisito_lvl"]): ?>
                        <p>Nível Baixo</p>
                    <?php elseif ($pers["mp"] < $consumo): ?>
                        <p>Energia insuficiente</p>
                    <?php elseif (!$espera["espera"] && $pers["mp"] >= $consumo && $pers["profissao_lvl"] >= $item["requisito_lvl"]): ?>
                        <button class="btn btn-success" data-dismiss="modal"
                                onclick="usaSkil('<?= $item["cod_remedio"]; ?>','<?= $pers["cod"]; ?>','1', '10','1')">
                            Usar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
