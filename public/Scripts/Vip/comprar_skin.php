<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$skin = $protector->get_number_or_exit("skin");
$img = $protector->get_number_or_exit("img");
$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao", "credito"));

$skins = DataLoader::load("skins");

if (!isset($skins[$img]) || !isset($skins[$img][$skin]) || ($skins[$img][$skin] == -1 && $tipo != "credito")) {
    $protector->exit_error("Skin inválida");
}

if ($tipo == "gold") {
    $protector->need_gold($skins[$img][$skin]);
} else if ($tipo == "dobrao") {
    $protector->need_dobroes(ceil($skins[$img][$skin] * 1.2));
} else {
    if (!$userDetails->tripulacao["credito_skin"]) {
        $protector->exit_error("Você não tem direito à aparências gratuitas");
    }
}

$connection->run("INSERT INTO tb_tripulacao_skins (conta_id, tripulacao_id, img, skin) VALUE (?, ?, ?, ?)",
    "iiii", array($userDetails->conta["conta_id"], $userDetails->tripulacao["id"], $img, $skin));

if ($tipo == "gold") {
    $userDetails->reduz_gold($skins[$img][$skin], "skin_personagem");
} else if ($tipo == "dobrao") {
    $userDetails->reduz_dobrao(ceil($skins[$img][$skin] * 1.2), "skin_personagem");
} else {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin -1 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
}
echo "-Parabéns! Você comprou uma nova aparência para seu tripulante!";