<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();
$protector->need_tripulacao_alive();
$protector->must_be_out_of_any_kind_of_combat();

$destino = $protector->get_number_or_exit("destino");
$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

$result = $connection->run("SELECT * FROM tb_mapa WHERE ilha=?", "i", array($destino));

if (!$result->count()) {
    $protector->exit_error("Ilha invalida");
}

$mapa = $result->fetch_array();

if ($userDetails->capitao["lvl"] < 45 && $mapa["mar"] != $userDetails->ilha["mar"]) {
    $protector->exit_error("O Serviço de transporte não pode te levar para essa ilha.");
}

$distancia = sqrt(pow($mapa["x"] - $userDetails->tripulacao["x"], 2) + pow($mapa["y"] - $userDetails->tripulacao["y"], 2));
$preco = round(($distancia * 10) / 20);
if ($preco < 20) {
    $preco = 20;
}

if ($tipo == "gold") {
    $protector->need_gold($preco);
} else {
    $protector->need_dobroes(ceil($preco * 1.5));
}

$connection->run("UPDATE tb_usuarios SET x = ?, y = ?, mar_visivel = 0 WHERE id = ?",
    "iii", array($mapa["x"], $mapa["y"], $userDetails->tripulacao["id"]));

if ($tipo == "gold") {
    $userDetails->reduz_gold($preco, "transporte_gold");
} else {
    $userDetails->reduz_dobrao(ceil($preco * 1.2), "transporte_gold");
}

echo("%oceano");
