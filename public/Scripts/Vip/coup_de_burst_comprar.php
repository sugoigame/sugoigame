<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_COUP_DE_BURST);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_COUP_DE_BURST);
}

$tempo_base = $userDetails->vip["coup_de_burst_duracao"] > atual_segundo() ? $userDetails->vip["coup_de_burst_duracao"] : atual_segundo();
$tempo = $tempo_base + 30 * 24 * 60 * 60;
$connection->run("UPDATE tb_vip SET coup_de_burst = 5, coup_de_burst_duracao = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_GOLD_COUP_DE_BURST, "coup_de_burst");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_COUP_DE_BURST, "coup_de_burst");
}

echo("-Parabens!<br>Você acabou de comprar o Coup De Burst!");