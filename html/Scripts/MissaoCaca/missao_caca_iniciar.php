<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$missao_id = $protector->get_number_or_exit("cod");

$missoes = DataLoader::load("missoes_caca");

if (!isset($missoes[$missao_id])) {
    $protector->exit_error("Missão não encontrada");
}
$missao = $missoes[$missao_id];
if (isset($missao["diario"]) && $missao["diario"]) {
    $completa = $connection->run("SELECT * FROM tb_missoes_caca_diario WHERE tripulacao_id = ? AND missao_caca_id = ?",
        "ii", array($userDetails->tripulacao["id"], $missao_id))->count();
    if ($completa) {
        $protector->exit_error("Você já concluiu essa missão hoje");
    }
}

$connection->run("UPDATE tb_usuarios SET missao_caca = ?, missao_caca_progress = 0 WHERE id = ?",
    "ii", array($missao_id, $userDetails->tripulacao["id"]));

echo "-Missão iniciada!";