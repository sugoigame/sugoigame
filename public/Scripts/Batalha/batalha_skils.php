<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

$cod = $protector->get_number_or_exit("cod");

$result = $connection->run(
    "SELECT *
    FROM tb_combate_personagens cbtpers
    INNER JOIN tb_personagens pers ON cbtpers.cod = pers.cod
    WHERE cbtpers.id = ? AND cbtpers.cod = ?",
    "ii", array($userDetails->tripulacao["id"], $cod)
);

if (! $result->count()) {
    echo ("#Personagem inválido.");
    exit();
}
$pers = $result->fetch_array();

$skills = get_all_skills($pers);

usort($skills, function ($a, $b) {
    return $a["consumo"] - $b["consumo"];
});

?>
<div class="row">
    <?php foreach ($skills as $skill) : ?>
        <?php
        $result = $connection->run(
            "SELECT *
             FROM tb_combate_skil_espera
			 WHERE cod = ? AND cod_skil = ? AND tipo = ?",
            "iii", array($cod, $skill["cod_skil"], $skill["tipo"])
        );
        $espera = $result->count() ? $result->fetch_array() : array("espera" => 0);
        ?>
        <div class="col-md-2 col-sm-3 col-xs-4 p0 h-100">
            <div class="panel panel-default m0 h-100">
                <div class="panel-body">
                    <div>
                        <img src="Imagens/Skils/<?= $skill["icon"] ?>.jpg" width="15vw">
                        <?= str_replace("'", "&rsquo;", $skill["nome"]) ?>
                        <img src="Imagens/Skils/Tipo/<?= nome_tipo_skill($skill) ?>.png" width="15vw" />
                    </div>
                    <div class="text-left clearfix">
                        <?php render_skill_efeitos($skill, $pers) ?>
                    </div>
                </div>
                <div class="panel-footer">
                    <?php if ($espera["espera"]) : ?>
                        <p>
                            <?= $espera["espera"] ?> turno(s)
                        </p>
                    <?php elseif ($pers["mp"] < $skill["consumo"]) : ?>
                        <p>Vontade insuficiente</p>
                    <?php elseif (! $espera["espera"] && $pers["mp"] >= $skill["consumo"]) : ?>
                        <button class="btn btn-success" data-dismiss="modal"
                            onclick="usaSkil('<?= $skill["cod_skil"]; ?>','<?= $pers["cod"]; ?>','<?= $skill["alcance"]; ?>', '<?= $skill["tipo"]; ?>','<?= $skill["area"]; ?>')">
                            Usar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ($pers["profissao"] == PROFISSAO_MEDICO) : ?>

        <?php $items = $connection->run(
            "SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item=?",
            "ii", [$userDetails->tripulacao["id"], TIPO_ITEM_REMEDIO])
            ->fetch_all_array(); ?>
        <?php foreach ($items as $key => $item) {
            $items[$key] = array_merge($item, MapLoader::find("remedios", ["cod_remedio" => $item["cod_item"]]));
        } ?>
        <?php foreach ($items as $item) : ?>
            <?php
            $result = $connection->run(
                "SELECT *
                 FROM tb_combate_skil_espera
                 WHERE id = ? AND tipo = ?",
                "ii", array($userDetails->tripulacao["id"], 10)
            );
            $espera = $result->count() ? $result->fetch_array() : array("espera" => 0);
            $consumo = ($item["requisito_lvl"] * 4)
                ?>

            <div class="col-md-2 col-sm-3 col-xs-4 p0 h-100">
                <div class="panel panel-default m0 h-100">
                    <div class="panel-body">
                        <?= info_item($item, $item, false, false, false) ?>
                    </div>
                    <div class="panel-footer">
                        <div>
                            <?php if ($espera["espera"]) : ?>
                                <div>
                                    <?= $espera["espera"] ?> turno(s)
                                </div>
                            <?php elseif ($pers["profissao_lvl"] < $item["requisito_lvl"]) : ?>
                                <div>Nível Baixo</div>
                            <?php elseif ($pers["mp"] < $consumo) : ?>
                                <div>Vontade insuficiente</div>
                            <?php elseif (! $espera["espera"] && $pers["mp"] >= $consumo && $pers["profissao_lvl"] >= $item["requisito_lvl"]) : ?>
                                <button class="btn btn-success" data-dismiss="modal"
                                    onclick="usaSkil('<?= $item["cod_remedio"]; ?>','<?= $pers["cod"]; ?>','1', '10','1')">
                                    Usar
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
