<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

$item = $protector->get_number_or_exit("item");

$item = $userDetails->get_item($item, TIPO_ITEM_EQUIPAMENTO);

if (!$item) {
    $protector->exit_error("Você não possui esse item");
}

$equipamento = $connection->run("SELECT * FROM tb_item_equipamentos WHERE cod_equipamento = ?",
    "i", array($item["cod_item"]))->fetch_array();


$preco = preco_venda_equipamento($equipamento);

if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
    $preco += round($aumento * $preco);
    if ($preco >= preco_compra_equipamento($equipamento)) {
        $preco = preco_compra_equipamento($equipamento) - 1;
    }
}

$connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
    "ii", array($preco, $userDetails->tripulacao["id"]));

$userDetails->reduz_item($item["cod_item"], $item["tipo_item"], 1);

$connection->run("DELETE FROM tb_item_equipamentos WHERE cod_equipamento = ?",
    "i", array($item["cod_item"]));

echo "-Item vendido";