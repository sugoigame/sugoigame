<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo       = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));
$pers_cod   = $protector->get_number_or_exit("cod");
$pers       = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem invalido");
}

if ($pers["lvl"] >= $userDetails->capitao["lvl"] || $pers["xp"] >= $pers["xp_max"]) {
    $protector->exit_error("Este personagem não está apto a adquirir experiência");
}

if ($tipo == "gold") {
    $protector->need_gold(PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO);
} else {
    $protector->need_dobroes(PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO);
}

$connection->run("UPDATE tb_personagens SET xp = xp + xp_max WHERE cod = ?", "i", $pers_cod);

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO, "comprar_lvl");
} else {
    $userDetails->reduz_dobrao(PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO, "comprar_lvl");
}

echo("|Experiência adquirida!");
