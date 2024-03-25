<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$personagens = array();

foreach ($userDetails->personagens as $pers) {
    if ($pers["hp"] < $pers["hp_max"]) {
        $personagens[] = $pers;
    }
}

$preco = calc_preco_recuperar_tripulantes($personagens);

if (! $userDetails->reduz_berries($preco)) {
    $protector->exit_error("Sem Berries suficientes");
}

foreach ($personagens as $personagem) {
    $connection->run(
        "UPDATE tb_personagens SET respawn = 0, respawn_tipo = 0, hp = hp_max
        WHERE cod = ?",
        "i", array($personagem["cod"])
    );
}

if (count($personagens)) {
    echo ("Tratamento concluído!");
} else {
    $protector->exit_error("Não é possível iniciar o tratamento do tripulante");
}
