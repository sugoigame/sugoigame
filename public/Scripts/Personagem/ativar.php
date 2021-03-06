<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_in_ilha();

$cod = $protector->get_number_or_exit("cod");

$personagem = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ? AND ativo = 0",
    "ii", array($cod, $userDetails->tripulacao["id"]));

if (!$personagem->count()) {
    $protector->exit_error("Personagem Invalido");
}
$personagem = $personagem->fetch_array();

if ($personagem["preso"]) {
    $protector->exit_error("Este personagem está preso e não pode entrar para a tripulação");
}

$navio = $connection->run("SELECT * FROM tb_navio WHERE cod_navio = ?", "i", $userDetails->navio["cod_navio"])->fetch_array();

if (count($userDetails->personagens) >= $navio["limite"]) {
    $protector->exit_error("Não há espaço para mais tripulantes no seu navio.");
}

$tatic_ocupada = $connection->run(
    "SELECT * FROM tb_personagens WHERE 
      ((tatic_a = ? AND tatic_a <> '0') 
      OR (tatic_d = ? AND tatic_d <> '0') 
      OR (tatic_p = ? AND tatic_p <> '0'))
      AND ativo = 1 AND id = ?",
    "sssi", array($personagem["tatic_a"], $personagem["tatic_d"], $personagem["tatic_p"], $userDetails->tripulacao["id"])
);

if ($tatic_ocupada->count()) {
    $personagem_tatic = $tatic_ocupada->fetch_array();
    $protector->exit_error("As táticas definidas para este personagem são as mesmas de " . $personagem_tatic["nome"] . ". Modifique as táticas deste personagem para voltar com esse tripulante para o navio");
}

$connection->run("UPDATE tb_personagens SET ativo = 1 WHERE cod = ?", "i", array($cod));

echo "%tripulantesInativos";