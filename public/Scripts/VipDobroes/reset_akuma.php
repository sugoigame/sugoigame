<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_dobroes(PRECO_DOBRAO_RESET_AKUMA);
$personagem = $protector->get_number_or_exit('cod');

$result = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?", 'ii', [
    $personagem,
    $userDetails->tripulacao['id']
]);
if ($result->count() == 0) {
    $protector->exit_error("Persoangem nao encontrado");
}
$pers = $result->fetch_array();

$result = $connection->run("SELECT * FROM tb_akuma WHERE cod = ?", 'i', $personagem);
if ($result->count() == 0) {
    $protector->exit_error("Esse personagem não comeu nenhuma Akuma no Mi");
}

$userDetails->restaura_effects($pers, "(tipo IN (7, 8, 9))");

$akuma = $result->fetch_array();

$connection->run("DELETE FROM tb_akuma_skil_atk WHERE cod_akuma = ?", 'i', $akuma["cod_akuma"]);
$connection->run("DELETE FROM tb_akuma_skil_buff WHERE cod_akuma = ?", 'i', $akuma["cod_akuma"]);
$connection->run("DELETE FROM tb_akuma_skil_passiva WHERE cod_akuma = ?", 'i', $akuma["cod_akuma"]);
$connection->run("DELETE FROM tb_akuma WHERE cod = ?", 'i', $personagem);
$connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND tipo IN (7, 8, 9)", 'i', $personagem);
$connection->run("UPDATE tb_personagens SET akuma = NULL, maestria = 0 WHERE cod = ?", 'i', $personagem);

$userDetails->reduz_dobrao(PRECO_DOBRAO_RESET_AKUMA, "resetar_akuma");

echo("-Akuma no Mi Removida");
