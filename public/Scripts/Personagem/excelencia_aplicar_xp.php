<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers_cod = $protector->post_number_or_exit("pers");
$quant = $protector->post_number_or_exit("quant");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}

$xp_necessaria_up = $pers["excelencia_xp_max"] - $pers["excelencia_xp"];

if (!$pers["xp"] || !$xp_necessaria_up || $quant > $pers["xp"] || $quant > $xp_necessaria_up) {
    $protector->exit_error("Você não pode aplicar experiência a este personagem");
}

$connection->run("UPDATE tb_personagens SET xp = xp - ?, excelencia_xp = excelencia_xp + ? WHERE cod = ?",
    "iii", array($quant, $quant, $pers_cod));

echo "-Experiência aplicada.";
