<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$cod = $protector->get_alphanumeric_or_exit("cod");

if (strlen($cod) != 36) {
    $protector->exit_error("Bandeira invalida");
}

if ($userDetails->tripulacao["bandeira"] == "010113046758010128123542010115204020") {
    $connection->run("UPDATE tb_usuarios SET bandeira= ? WHERE id = ?",
        "si", array($cod, $userDetails->tripulacao["id"]));
} else {
    $tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

    if ($tipo == "gold") {
        $protector->need_gold(PRECO_GOLD_TROCAR_BANDEIRA);
    } else {
        $protector->need_dobroes(PRECO_DOBRAO_TROCAR_BANDEIRA);
    }

    $connection->run("UPDATE tb_usuarios SET bandeira= ? WHERE id = ?",
        "si", array($cod, $userDetails->tripulacao["id"]));

    if ($tipo == "gold") {
        $userDetails->reduz_gold(PRECO_GOLD_TROCAR_BANDEIRA, "bandeira_mudar");
    } else {
        $userDetails->reduz_dobrao(PRECO_DOBRAO_TROCAR_BANDEIRA, "bandeira_mudar");
    }

}
echo("Bandeira alterada!");