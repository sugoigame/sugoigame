<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();

$personagens = array();

if (isset($_GET["cod"])) {
    $cod = $protector->get_number_or_exit("cod");

    $pers = $userDetails->get_pers_by_cod($cod);
    $personagens[] = $pers;
} else {
    foreach ($userDetails->personagens as $pers) {
        if ($pers["hp"] <= $pers["hp_max"] && $pers["respawn_tipo"] == 0) {
            $personagens[] = $pers;
        }
    }
}

foreach ($personagens as $personagem) {
    $tempo = 6 * max(0, $personagem["lvl"]);
    $tempo = $tempo + atual_segundo();

    $connection->run("UPDATE tb_personagens SET respawn = ?, respawn_tipo = ? WHERE cod = ?",
        "iii", array($tempo, RECUPERACAO_TIPO_HOSPITAL, $personagem["cod"]));
}

if (count($personagens)) {
    echo("Tratamento iniciado!");
} else {
    $protector->exit_error("Não é possível iniciar o tratamento do tripulante");
}
