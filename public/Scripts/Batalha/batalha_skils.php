<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);

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

$habilidades = \Regras\Habilidades::get_todas_habilidades_pers($pers);

$skill_espera = $connection->run(
    "SELECT *
     FROM tb_combate_skil_espera
     WHERE cod = ?",
    "i", array($cod)
)->fetch_all_array();

?>
<div class="row">
    <?php foreach ($habilidades as $habilidade) : ?>
        <?php if (\Regras\Habilidades::is_usavel_batalha($habilidade)) : ?>
            <?php $espera = array_find($skill_espera, ["cod_skil" => $habilidade["cod"]]) ?: []; ?>
            <div class="col-md-2 col-sm-2 col-xs-2 p0 h-100">
                <div class="panel panel-default m0 h-100">
                    <div class="panel-body">
                        <div>
                            <?= Componentes::render('Habilidades.Icone', ["habilidade" => $habilidade]) ?>
                        </div>
                        <div>
                            <?php if (isset($espera["espera"])) : ?>
                                <p>
                                    <?= $espera["espera"] ?> turno(s)
                                </p>
                            <?php elseif ($combate->minhaTripulacao->get_vontade() < $habilidade["vontade"]) : ?>
                                <p>Vontade insuficiente</p>
                            <?php elseif (! $espera["espera"] && $combate->minhaTripulacao->get_vontade() >= $habilidade["vontade"]) : ?>
                                <button class="btn btn-success" data-dismiss="modal"
                                    onclick="usaSkil('<?= $habilidade["cod"]; ?>','<?= $pers["cod"]; ?>','<?= $habilidade["alcance"]; ?>', '1','<?= $habilidade["area"]; ?>')">
                                    Usar
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
