<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers_cod = $protector->post_number_or_exit("pers");
$quant = $protector->post_number_or_exit("quant");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}

if ($pers["haki_lvl"] >= HAKI_LVL_MAX) {
    $protector->exit_error("Esse personagem já alcançou o nível máximo de Haki");
}
if ($pers["hp"] <= 0) {
    $protector->exit_error("Esse personagem foi derrotado e nao pode treinar Haki enquanto não estiver curado.");
}

$quant = min($quant, $pers["haki_xp_max"] - $pers["haki_xp"]);

if ($userDetails->tripulacao["haki_xp"] < $quant) {
    $protector->exit_error("Sua tripulação não tem Haki para aplicar");
}

$userDetails->add_haki($pers, $quant);

$connection->run("UPDATE tb_usuarios SET haki_xp = haki_xp - ? WHERE id = ?",
    "ii", array($quant, $userDetails->tripulacao["id"]));

echo ":haki&outro=" . $pers_cod;