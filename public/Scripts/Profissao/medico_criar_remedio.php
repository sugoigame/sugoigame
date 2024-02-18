<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";


$protector->need_tripulacao();

$pers = $protector->post_number_or_exit("pers");
$item = $protector->post_number_or_exit("item");
$quant_faz = $protector->post_number_or_exit("quant");

if ($quant_faz < 0) {
    $protector->exit_error("Você informou algum caracter inválido.");
}
$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() >= $usuario["capacidade_iventario"]) {
    $protector->exit_error("Seu iventário está lotado.");
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$pers'";
$result = $connection->run($query);
$personagem = $result->fetch_array();

if ($personagem["profissao"] != 3) {
    $protector->exit_error("Este personagem não é um médico.");
}

$item = MapLoader::find("remedios", ["cod_remedio" => $item]);
$item["preco"] = ($item["hp_recuperado"] + $item["mp_recuperado"]) * 60;
$item["preco"] *= (((1 - $personagem["profissao_lvl"] * 0.05)) * $quant_faz);

if ($usuario["berries"] < $item["preco"]) {
    $protector->exit_error("Você não tem dinheiro para essa quantidade de itens.");
}

if ($personagem["profissao_lvl"] < $item["requisito_lvl"]) {
    $protector->exit_error("Você não cumpre os requisitos para fazer este item.");
}
$berries = $usuario["berries"] - $item["preco"];
$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel pagar o item");

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='" . $item["cod_remedio"] . "' AND tipo_item='7'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont != 0) {
    $quant = $result->fetch_array();
    $quant = $quant["quant"] + $quant_faz;
    $query = "UPDATE tb_usuario_itens SET quant='$quant', novo = 1 WHERE id='" . $usuario["id"] . "' AND cod_item='" . $item["cod_remedio"] . "' AND tipo_item='7' LIMIT 1";
    $connection->run($query) or die("Nao foi posssivel adicionar o item");
} else {
    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["id"] . "', '" . $item["cod_remedio"] . "', '7', '$quant_faz')";
    $connection->run($query) or die("Nao foi possivel criar o item");
}
if ($personagem["profissao_xp"] < $personagem["profissao_xp_max"] and $personagem["profissao_lvl"] == $item["requisito_lvl"]) {
    $xp = $personagem["profissao_xp"] + $quant_faz;
    if ($xp > $personagem["profissao_xp_max"])
        $xp = $personagem["profissao_xp_max"];
    $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem["cod"] . "'";
    $connection->run($query) or die("Nao foi possivel evoluir profisssao");
}

