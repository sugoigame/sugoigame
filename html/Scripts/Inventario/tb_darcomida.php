<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod_comida = $protector->get_number_or_exit("comida");
$tipo = $protector->get_enum_or_exit("tipo", array(1, 7));

if ($tipo == TIPO_ITEM_COMIDA) {
    $comida = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_comida", "cod_comida", 1,
        "WHERE origem.id = ? AND origem.cod_item = ?", "ii", array($userDetails->tripulacao["id"], $cod_comida));
} else {
    $comida = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_remedio", "cod_remedio", 7,
        "WHERE origem.id = ? AND origem.cod_item = ?", "ii", array($userDetails->tripulacao["id"], $cod_comida));
}

if (!count($comida)) {
    $protector->exit_error("O item acabou");
}

$comida = $comida[0];
?>
<div class="modal-body">
    <?= get_img_item($comida) ?> x<?= $comida["quant"] ?>
    <div class="row">
        <?php foreach ($userDetails->personagens as $pers): ?>
            <div class="dar-comida-pers col-md-3">
                <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                     height="55px" id="&pers=<?= $pers["cod"]; ?>"
                     class="<?= $pers["hp"] > 0 ? "com_fome" : "" ?>"
                     style="opacity: <?= $pers["hp"] / $pers["hp_max"]; ?>"/>
                <?php render_personagem_hp_bar($pers); ?>
                <?php render_personagem_mp_bar($pers); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>