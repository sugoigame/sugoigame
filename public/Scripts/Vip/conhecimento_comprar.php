<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_CONHECIMENTO);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_CONHECIMENTO);
}

$tempo_base = $userDetails->vip["conhecimento"] ? $userDetails->vip["conhecimento_duracao"] : atual_segundo();
$tempo = $tempo_base + 30 * 24 * 60 * 60;
$connection->run("UPDATE tb_vip SET conhecimento = 1, conhecimento_duracao = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_GOLD_CONHECIMENTO, "conhecimento_estrategico");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_CONHECIMENTO, "conhecimento_estrategico");
}

echo("-Parabens!<br>Você acabou de comprar o Conhecimento Estratégico!");