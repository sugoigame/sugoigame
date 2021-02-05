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

$dono_outra_ilha = $connection->run("SELECT * FROM tb_mapa WHERE ilha = ?",
    "i", array($negociacao["ilha"]))->fetch_array();

$recursos = $connection->run("SELECT * FROM tb_ilha_recurso WHERE ilha = ?",
    "i", array($userDetails->ilha["ilha"]))->fetch_array();

$recurso_column = "recurso_" . $negociacao["recurso_desejado"];

if ($recursos[$recurso_column] < $negociacao["quant"]) {
    $protector->exit_error("Você não tem esses recursos para oferecer");
}

$origem = $userDetails->ilha;

$result = $connection->run("INSERT INTO tb_ilha_mercador (ilha_origem, ilha_destino, recurso, quant) VALUE (?,?,?,?)",
    "iiii", array($userDetails->ilha["ilha"], $negociacao["ilha"], $negociacao["recurso_desejado"], $negociacao["quant"]));

$mercador_id = $result->last_id();

$connection->run("INSERT INTO tb_mapa_contem (x, y, mercador_id) VALUE (?,?,?)",
    "iii", array($origem["x"] + 1, $origem["y"] + 1, $mercador_id));


$destino = $connection->run("SELECT * FROM tb_mapa WHERE  ilha = ?", "i", array($negociacao["ilha"]))->fetch_array();

$result = $connection->run("INSERT INTO tb_ilha_mercador (ilha_origem, ilha_destino, recurso, quant) VALUE (?,?,?,?)",
    "iiii", array($negociacao["ilha"], $userDetails->ilha["ilha"], $negociacao["recurso_oferecido"], $negociacao["quant"]));

$mercador_id = $result->last_id();

$connection->run("INSERT INTO tb_mapa_contem (x, y, mercador_id) VALUE (?,?,?)",
    "iii", array($destino["x"], $destino["y"], $mercador_id));


$connection->run("UPDATE tb_ilha_recurso SET $recurso_column = $recurso_column - ? WHERE ilha = ?",
    "ii", array($negociacao["quant"], $userDetails->ilha["ilha"]));

$connection->run("DELETE FROM tb_ilha_recurso_venda WHERE id = ?",
    "i", array($negociacao_id));

$connection->run("INSERT INTO tb_news_coo (msg) VALUE (?)",
    "s", array("Navios mercadores sairam de " . nome_ilha($userDetails->ilha["ilha"]) . " e " . nome_ilha($negociacao["ilha"])));

echo "-Negociação aprovada! Os mercadores sairão em breve com destino as respectivas ilhas. Isso ainda pode demorar alguns minutos.";