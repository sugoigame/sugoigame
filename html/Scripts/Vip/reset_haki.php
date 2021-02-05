<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem invalido");
}

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_RESET_HAKI);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_RESET_HAKI);
}

$haki = $pers["haki_lvl"];

$connection->run("UPDATE tb_personagens SET haki_pts = ?, haki_esq = 0, haki_blo = 0, haki_cri = 0, haki_hdr = 0 WHERE cod = ?",
    "ii", array($haki, $pers_cod));

$userDetails->remove_hdr($pers);

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_GOLD_RESET_HAKI, "resetar_haki");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_RESET_HAKI, "resetar_haki");
}

echo("|Haki Resetado");
