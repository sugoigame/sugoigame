<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_USAR_FORMACOES);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_USAR_FORMACOES);
}

$tempo_base = $userDetails->vip["formacoes"] ? $userDetails->vip["formacoes_duracao"] : atual_segundo();
$tempo = $tempo_base + 30 * 24 * 60 * 60;
$connection->run("UPDATE tb_vip SET formacoes = 1, formacoes_duracao = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_GOLD_USAR_FORMACOES, "formacoes");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_USAR_FORMACOES, "formacoes");
}

echo("-Parabens!<br>Você acabou de adquirir o acesso às Formações de Tripulação!");