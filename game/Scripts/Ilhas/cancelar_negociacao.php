<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_dono_ilha();

$negociacao_id = $protector->get_number_or_exit("id");

$negociacao = $connection->run("SELECT * FROM tb_ilha_recurso_venda WHERE id = ?",
    "i", array($negociacao_id));

if (!$negociacao->count()) {
    $protector->exit_error("Essa negociação não existe");
}

$negociacao = $negociacao->fetch_array();

if ($negociacao["ilha"] != $userDetails->ilha["ilha"]) {
    $protector->exit_error("Você não é o criador dessa negociação");
}

$recurso_column = "recurso_" . $negociacao["recurso_oferecido"];

$connection->run("UPDATE tb_ilha_recurso SET $recurso_column = $recurso_column + ? WHERE ilha = ?",
    "ii", array($negociacao["quant"], $userDetails->ilha["ilha"]));


$connection->run("DELETE FROM tb_ilha_recurso_venda WHERE id = ?",
    "i", array($negociacao_id));

echo "-Negociação cancelada!";
