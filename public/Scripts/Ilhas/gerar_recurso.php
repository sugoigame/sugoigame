<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_dono_ilha();

$quant = $protector->post_number_or_exit("quant");

$preco = PRECO_GERAR_RECURSO_ILHA * $quant;

$protector->need_berries($preco);

$recursos = $connection->run("SELECT * FROM tb_ilha_recurso WHERE ilha = ?",
    "i", array($userDetails->ilha["ilha"]))->fetch_array();

$recurso_column = "recurso_" . $recursos["recurso_gerado"];

$connection->run("UPDATE tb_ilha_recurso SET $recurso_column = $recurso_column + ? WHERE ilha = ?",
    "ii", array($quant, $userDetails->ilha["ilha"]));

$connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
    "ii", array($preco, $userDetails->tripulacao["id"]));

echo "-Recurso gerado!";
