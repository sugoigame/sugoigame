<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_number_or_exit("pers");
$cod_comida = $protector->get_number_or_exit("comida");
$tipo = $protector->get_number_or_exit("tipo");

$quant = 1;

if (isset($_GET["quant"])) {
    $quant = $protector->get_number_or_exit("quant");
}

$personagem = $userDetails->get_pers_by_cod($pers);

if (! $personagem) {
    $protector->exit_error("Personagem invalido");
}

if ($tipo == TIPO_ITEM_COMIDA) {
    $comida = $connection->run(
        "SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item=? AND cod_item=?",
        "iii", [$userDetails->tripulacao["id"], TIPO_ITEM_COMIDA, $cod_comida]);
    if (! $comida->count()) {
        $protector->exit_error("O item acabou");
    }

    $comida = array_merge($comida->fetch_array(), MapLoader::find("comidas", ["cod_comida" => $cod_comida]));
} else {
    $comida = $connection->run(
        "SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item=? AND cod_item=?",
        "iii", [$userDetails->tripulacao["id"], TIPO_ITEM_REMEDIO, $cod_comida]);
    if (! $comida->count()) {
        $protector->exit_error("O item acabou");
    }

    $comida = array_merge($comida->fetch_array(), MapLoader::find("remedios", ["cod_remedio" => $cod_comida]));
}


if ($comida["quant"] < $quant) {
    $protector->exit_error("Você não tem todos estes item disponíveis");
}

$hp_recuperado = $comida["hp_recuperado"] * $quant * 10;
$mp_recuperado = $comida["mp_recuperado"] * $quant;

$personagem["hp"] = min($personagem["hp_max"], $personagem["hp"] + $hp_recuperado);
$personagem["mp"] = min($personagem["mp_max"], $personagem["mp"] + $mp_recuperado);

$connection->run("UPDATE tb_personagens SET hp = ?, mp = ? WHERE cod = ?",
    "iii", array($personagem["hp"], $personagem["mp"], $pers));

$userDetails->reduz_item($cod_comida, $tipo, $quant);

echo ("@");
