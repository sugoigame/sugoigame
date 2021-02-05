<?php
require "../../Includes/conectdb.php";

function get_random_reagent() {
    global $connection;

    return $connection->run("SELECT * FROM tb_item_reagents WHERE mining > 0 OR madeira > 0 ORDER BY RAND() LIMIT 1")->fetch_array();
}

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod = $protector->get_number_or_exit("cod");

$item = $userDetails->get_item($cod, TIPO_ITEM_EQUIPAMENTO);

if (!$item) {
    $protector->exit_error("Item não encontrado");
}

$equipamento = $connection->run("SELECT * FROM tb_item_equipamentos WHERE cod_equipamento = ?",
    "i", array($cod))->fetch_array();

if (!$userDetails->can_add_item($equipamento["upgrade"] ? 2 : 1)) {
    $protector->exit_error("Você precisa de um espaço disponível no inventário para receber a recompensa");
}

$msg = "";
if ($equipamento["lvl"] < 46 || $equipamento["categoria"] < 2) {
    $item = get_random_reagent();
    $quant = rand(1, 5);
    $userDetails->add_item($item["cod_reagent"], TIPO_ITEM_REAGENT, $quant);
    $msg = "Você recebeu " . $item["nome"] . " x$quant";
} else if ($equipamento["categoria"] == 2) {
    $userDetails->add_item(153, TIPO_ITEM_REAGENT, 1);
    $msg = "Você recebeu uma Essência Branca";
} else if ($equipamento["categoria"] == 3) {
    $userDetails->add_item(156, TIPO_ITEM_REAGENT, 1);
    $msg = "Você recebeu um Fragmento de Essência Azul";
} else {
    $userDetails->add_item(157, TIPO_ITEM_REAGENT, 1);
    $msg = "Você recebeu uma Essência Azul";
}

if ($equipamento["upgrade"]) {
    $userDetails->add_item(158, TIPO_ITEM_REAGENT, $equipamento["upgrade"]);
    $msg .= " e " . $equipamento["upgrade"] . " Estilhaços de Essência";
}

$userDetails->reduz_item($cod, TIPO_ITEM_EQUIPAMENTO, 1, true);
$connection->run("DELETE FROM tb_item_equipamentos WHERE cod_equipamento = ?",
    "i", array($cod));

echo $msg;
