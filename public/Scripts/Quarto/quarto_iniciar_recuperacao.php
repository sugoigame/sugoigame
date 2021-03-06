<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if (!$userDetails->medicos) {
    $protector->exit_error("Você precisa de um médico na tripulação");
}

$personagens = array();

if (isset($_GET["cod"])) {
    $cod = $protector->get_number_or_exit("cod");

    $pers = $userDetails->get_pers_by_cod($cod);
    $personagens[] = $pers;
} else {
    foreach ($userDetails->personagens as $pers) {
        if ($pers["hp"] <= 0 && $pers["respawn_tipo"] == 0) {
            $personagens[] = $pers;
        }
    }
}

$preco = calc_preco_tratamento_quartos($personagens);

$protector->need_berries($preco);

foreach ($personagens as $personagem) {
    $tempo = calc_tempo_tratament_quartos($personagem);
    $tempo = $tempo + atual_segundo();

    $connection->run("UPDATE tb_personagens SET respawn = ?, respawn_tipo = ? WHERE cod = ?",
        "iii", array($tempo, RECUPERACAO_TIPO_QUARTOS, $personagem["cod"]));
}

$connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
    "ii", array($preco, $userDetails->tripulacao["id"]));

if (count($personagens)) {
    echo("Tratamento iniciado!");
} else {
    $protector->exit_error("Não é possível iniciar o tratamento do tripulante");
}
