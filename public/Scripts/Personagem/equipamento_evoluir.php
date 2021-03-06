<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

$cod_item = $protector->get_number_or_exit("item");

$item = $userDetails->get_item($cod_item, TIPO_ITEM_EQUIPAMENTO);

if (!$item) {
    $protector->exit_error("item invalido");
}

$equipamento = $connection->run("SELECT * FROM tb_item_equipamentos WHERE cod_equipamento= ?",
    "i", array($cod_item))->fetch_array();

if ($equipamento["upgrade"] == 10) {
    $protector->exit_error("Esse item nao pode ser mais aprimorado.");
}

if ($equipamento["upgrade"] < 1 && $equipamento["categoria"] < 3) {
    $joia_id = 7;
} else if ($equipamento["upgrade"] < 1) {
    $joia_id = 8;
} else if ($equipamento["upgrade"] == 1 && $equipamento["categoria"] < 3) {
    $joia_id = 11;
} else if ($equipamento["upgrade"] == 1) {
    $joia_id = 12;
} else if ($equipamento["upgrade"] == 2 && $equipamento["categoria"] < 3) {
    $joia_id = 9;
} else if ($equipamento["upgrade"] == 2) {
    $joia_id = 10;
} else if ($equipamento["upgrade"] == 3 && $equipamento["categoria"] < 3) {
    $joia_id = 3;
} else if ($equipamento["upgrade"] == 3) {
    $joia_id = 4;
} else if ($equipamento["upgrade"] <= 5 && $equipamento["categoria"] < 3) {
    $joia_id = 13;
} else if ($equipamento["upgrade"] <= 5) {
    $joia_id = 14;
} else if ($equipamento["upgrade"] <= 7 && $equipamento["categoria"] < 3) {
    $joia_id = 5;
} else if ($equipamento["upgrade"] <= 7) {
    $joia_id = 6;
} else if ($equipamento["upgrade"] <= 9 && $equipamento["categoria"] < 3) {
    $joia_id = 1;
} else {
    $joia_id = 2;
}

$joia = $userDetails->get_item($joia_id, TIPO_ITEM_REAGENT);

if (!$joia) {
    $protector->exit_error("Você não possui a joia necessária");
}

$precoB = (300000 * $equipamento["categoria"] + 200000) * ($equipamento["upgrade"] + 1);

$protector->need_berries($precoB);

$connection->run("UPDATE tb_item_equipamentos SET upgrade = upgrade + 1 WHERE cod_equipamento = ?",
    "i", array($cod_item));

$userDetails->reduz_item($joia_id, TIPO_ITEM_REAGENT, 1);

$connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
    "ii", array($precoB, $userDetails->tripulacao["id"]));

echo "Aprimoramento realizado!";