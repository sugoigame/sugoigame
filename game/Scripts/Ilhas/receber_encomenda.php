<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_dono_ilha();

$mercador_id = $protector->get_number_or_exit("id");

$mercador = $connection->run("SELECT * FROM tb_ilha_mercador WHERE id = ?",
    "i", array($mercador_id));

if (!$mercador->count()) {
    $protector->exit_error("Mercador inválido");
}

$mercador = $mercador->fetch_array();

if ($mercador["ilha_destino"] != $userDetails->ilha["ilha"]) {
    $protector->exit_error("Mercador inválido");
}

if (!$mercador["finalizou"]) {
    $protector->exit_error("Este mercador ainda não finalizou o trajeto");
}

$recurso_column = "recurso_" . $mercador["recurso"];

$connection->run("UPDATE tb_ilha_recurso SET $recurso_column = $recurso_column + ? WHERE ilha = ?",
    "ii", array($mercador["quant"], $userDetails->ilha["ilha"]));

$connection->run("DELETE FROM tb_ilha_mercador WHERE id = ?",
    "i", array($mercador_id));

echo "-Sua ilha recebeu " . $mercador["quant"] . " " . nome_recurso($mercador["recurso"]);
