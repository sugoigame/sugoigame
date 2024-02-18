<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod_comida = $protector->get_number_or_exit("comida");
$tipo = $protector->get_enum_or_exit("tipo", array(1, 7));

if ($tipo == TIPO_ITEM_COMIDA) {
    $comida = $connection->run(
        "SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item=? AND cod_item=?",
        "iii", [$userDetails->tripulacao["id"], TIPO_ITEM_COMIDA, $cod_comida]);
    if (! $comida->count()) {
        $protector->exit_error("O item acabou");
    }

    $comida = array_merge($comida->fetch_array(), MapLoader::find("comidas", ["cod_comida" => $cod_comida]));
} else {
    $comida = $connection->run(
        "SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item=? AND cod_item=?",
        "iii", [$userDetails->tripulacao["id"], TIPO_ITEM_REMEDIO, $cod_comida]);
    if (! $comida->count()) {
        $protector->exit_error("O item acabou");
    }

    $comida = array_merge($comida->fetch_array(), MapLoader::find("remedios", ["cod_remedio" => $cod_comida]));
}

?>
<div class="modal-body">
    <?= get_img_item($comida) ?> x
    <?= $comida["quant"] ?>
    <div class="row">
        <?php foreach ($userDetails->personagens as $pers) : ?>
            <div class="dar-comida-pers col-md-3">
                <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg" height="55px"
                    id="&pers=<?= $pers["cod"]; ?>" class="<?= $pers["hp"] > 0 ? "com_fome" : "" ?>"
                    style="opacity: <?= $pers["hp"] / $pers["hp_max"]; ?>" />
                <?php render_personagem_hp_bar($pers); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
