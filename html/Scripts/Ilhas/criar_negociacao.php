<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_dono_ilha();

$quant = $protector->post_number_or_exit("quant");
$recurso_oferecido = $protector->post_enum_or_exit("recurso_oferecido", array(0, 1, 2));
$recurso_procurado = $protector->post_enum_or_exit("recurso_procurado", array(0, 1, 2));

if ($recurso_oferecido == $recurso_procurado) {
    $protector->exit_error("Você não pode procurar o mesmo recurso que oferecer");
}

$recursos = $connection->run("SELECT * FROM tb_ilha_recurso WHERE ilha = ?",
    "i", array($userDetails->ilha["ilha"]))->fetch_array();

$recurso_column = "recurso_$recurso_oferecido";
if ($recursos[$recurso_column] < $quant) {
    $protector->exit_error("Você não tem esses recursos para oferecer");
}

$connection->run("INSERT INTO tb_ilha_recurso_venda (ilha, recurso_oferecido, recurso_desejado, quant) VALUE (?,?,?,?)",
    "iiii", array($userDetails->ilha["ilha"], $recurso_oferecido, $recurso_procurado, $quant));


$connection->run("UPDATE tb_ilha_recurso SET $recurso_column = $recurso_column - ? WHERE ilha = ?",
    "ii", array($quant, $userDetails->ilha["ilha"]));

echo "-Negociação criada!";
