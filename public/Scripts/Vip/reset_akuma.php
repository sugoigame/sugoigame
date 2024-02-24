<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_gold(PRECO_GOLD_RESET_AKUMA);
$personagem = $protector->get_number_or_exit('cod');

$result = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?", 'ii', [
    $personagem,
    $userDetails->tripulacao['id']
]);
if ($result->count() == 0) {
    $protector->exit_error("Personagem nao encontrado");
}
$pers = $result->fetch_array();

if (! $pers["akuma"]) {
    $protector->exit_error("Esse personagem não comeu nenhuma Akuma no Mi");
}

$connection->run("UPDATE tb_personagens SET akuma = NULL WHERE cod = ?", 'i', $personagem);

$userDetails->reduz_gold(PRECO_GOLD_RESET_AKUMA, "resetar_akuma");

echo ("-Akuma no Mi Removida");
