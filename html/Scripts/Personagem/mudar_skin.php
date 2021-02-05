<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$skin = $protector->get_number_or_exit("skin");
$pers_cod = $protector->get_number_or_exit("pers");
$tipo = $protector->get_enum_or_exit("tipo", array("c", "r"));

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}

if ($skin != 0) {
    $has_skin = $connection->run("SELECT count(id) AS total FROM tb_tripulacao_skins WHERE tripulacao_id = ? AND img = ? AND skin = ?",
        "iii", array($userDetails->tripulacao["id"], $pers["img"], $skin))->fetch_array()["total"];

    if (!$has_skin) {
        $protector->exit_error("Aparência inválida");
    }
}

$connection->run("UPDATE tb_personagens SET skin_$tipo = ? WHERE cod = ?",
    "ii", array($skin, $pers_cod));

echo "-Aparência habilitada!";