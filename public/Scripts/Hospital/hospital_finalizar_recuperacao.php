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
        if ($pers["respawn_tipo"] == RECUPERACAO_TIPO_HOSPITAL && $pers["respawn"] < atual_segundo()) {
            $personagens[] = $pers;
        }
    }
}

foreach ($personagens as $personagem) {
    $connection->run(
        "UPDATE tb_personagens SET respawn = 0, respawn_tipo = 0, mp = mp_max, hp = hp_max
        WHERE cod = ?",
        "i", array($personagem["cod"])
    );
}

if (count($personagens)) {
    echo("Tratamento concluído!");
} else {
    $protector->exit_error("Não é possível iniciar o descanso do tripulante");
}
