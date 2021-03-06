<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers = $protector->get_number_or_exit("pers");
$img = $protector->get_number_or_exit("img");
$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_TROCAR_PERSONAGEM);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_TROCAR_PERSONAGEM);
}

$personagem = $userDetails->get_pers_by_cod($pers);

if (!$personagem) {
    $protector->exit_error("Personagem invalido");
}

$result = $connection->run(
    "SELECT * FROM tb_personagens WHERE img = ? AND id = ?",
    "ii", array($img, $userDetails->tripulacao["id"])
);

if ($result->count()) {
    $protector->exit_error("Você já possui esse personagem na tripulação e não pode ter personagens repetidos");
}

$connection->run("UPDATE tb_personagens SET img = ?, skin_r = 0, skin_c = 0 WHERE cod = ?",
    "ii", array($img, $pers));

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_GOLD_TROCAR_PERSONAGEM, "trocar_personagem");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_TROCAR_PERSONAGEM, "trocar_personagem");
}

echo "-Personagem alterado";
