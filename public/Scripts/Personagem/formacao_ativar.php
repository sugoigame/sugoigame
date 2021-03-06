<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

if (!$userDetails->vip["formacoes"]) {
    $protector->exit_error("Você precisa adquirir uma vantagem VIP para usar esse recurso.");
}

$id = $protector->get_number_or_exit("id");

$formacao = $connection->run("SELECT * FROM tb_tripulacao_formacao WHERE id = ? AND tripulacao_id = ?",
    "ii", array($id, $userDetails->tripulacao["id"]));

if (!$formacao->count()) {
    $protector->exit_error("Essa formação não existe");
}

$formacao_id = $formacao->fetch_array()["formacao_id"];

$personagens = $connection->run(
    "SELECT * FROM tb_tripulacao_formacao 
    INNER JOIN tb_personagens ON tb_tripulacao_formacao.personagem_id = tb_personagens.cod 
    WHERE formacao_id = ? AND tripulacao_id = ?",
    "si", array($formacao_id, $userDetails->tripulacao["id"])
)->fetch_all_array();

$personagens = array_merge($personagens, array($userDetails->capitao));

$cods = array($userDetails->capitao["cod"]);
foreach ($personagens as $pers) {
    $cods[] = $pers["cod"];
    foreach ($personagens as $pers_comp) {
        if ($pers["cod"] != $pers_comp["cod"]
            && (
                ($pers["tatic_a"] != '0' && $pers["tatic_a"] == $pers_comp["tatic_a"])
                || ($pers["tatic_d"] != '0' && $pers["tatic_d"] == $pers_comp["tatic_d"])
                || ($pers["tatic_p"] != '0' && $pers["tatic_p"] == $pers_comp["tatic_p"])
            )
        ) {
            $protector->exit_error("O tripulante " . $pers["nome"] . " tem a mesma tática de " . $pers_comp["nome"] . " por isso não será possível ativar essa formação.");
        }
    }
}

$cods = implode(",", $cods);

$connection->run("UPDATE tb_personagens SET ativo = 0 WHERE cod NOT IN (" . $cods . ") AND id = ?",
    "i", array($userDetails->tripulacao["id"]));
$connection->run("UPDATE tb_personagens SET ativo = 1 WHERE cod IN (" . $cods . ") AND id = ?",
    "i", array($userDetails->tripulacao["id"]));

echo "Formação ativada!";