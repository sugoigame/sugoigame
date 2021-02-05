<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_dono_ilha();

$pers_cod = $protector->get_number_or_exit("pers");
$sequencia = $protector->get_enum_or_exit("nivel", array(0, 1, 2));

$personagem = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?",
    "ii", array($pers_cod, $userDetails->tripulacao["id"]));

if (!$personagem->count()) {
    $protector->exit_error("Personagem inválido");
}

$personagem = $personagem->fetch_array();

$pers_in_nivel = $connection->run("SELECT * FROM tb_ilha_incursao_protecao WHERE sequencia = ? AND ilha = ?",
    "ii", array($sequencia, $userDetails->ilha["ilha"]));

if (!$pers_in_nivel->count()) {
    $inserts = [];
    foreach ($userDetails->personagens as $pers) {
        if ($pers["cod"] != $pers_cod) {
            $inserts[] = '(' . $pers["cod"] . "," . $sequencia . "," . $userDetails->ilha["ilha"] . ")";
        }
    }

    $connection->run("INSERT INTO tb_ilha_incursao_protecao (personagem_id, sequencia, ilha) VALUES " . implode(",", $inserts));
} else {
    $connection->run("DELETE FROM tb_ilha_incursao_protecao WHERE sequencia = ? AND ilha = ? AND personagem_id = ?",
        "iii", array($sequencia, $userDetails->ilha["ilha"], $pers_cod));
}

echo "@";