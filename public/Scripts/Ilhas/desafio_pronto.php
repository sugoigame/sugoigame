<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();
$protector->need_tripulacao_alive();
$protector->must_be_in_ilha();

function teleporta_adversario_ilha($id) {
    global $connection;
    global $userDetails;

    $connection->run("DELETE FROM tb_combate WHERE id_1 =? OR id_2 = ?",
        "ii", array($id, $id));

    $connection->run("DELETE FROM tb_combate_bot WHERE tripulacao_id = ?",
        "i", array($id));

    $connection->run("DELETE FROM tb_combate_npc WHERE id = ?",
        "i", array($id));

    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?",
        "i", array($id));

    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?",
        "i", array($id));

    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?",
        "i", array($id));

    $connection->run("UPDATE tb_usuarios SET x = ?, y = ? WHERE id = ?",
        "iii", array($userDetails->ilha["x"], $userDetails->ilha["y"], $id));
}

$disputa = $connection->run("SELECT * FROM tb_ilha_disputa d LEFT JOIN tb_usuarios u ON d.vencedor_id = u.id WHERE d.ilha = ?",
    "i", array($userDetails->ilha["ilha"]));

if (!$disputa->count()) {
    $protector->exit_error("Essa ilha não está sob disputa");
}

$disputa = $disputa->fetch_array();

if (!$disputa["vencedor_id"]) {
    $protector->exit_error("O vencedor ainda não foi declarado");
}


if ($disputa["vencedor_id"] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_ilha_disputa SET vencedor_pronto = 1 WHERE ilha = ?",
        "i", array($userDetails->ilha["ilha"]));

    if ($disputa["dono_pronto"]) {
        teleporta_adversario_ilha($userDetails->ilha["ilha_dono"]);

        header("location:../../Scripts/Mapa/mapa_atacar.php?id=" . $userDetails->ilha["ilha_dono"] . "&tipo=5");
    }
} else if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_ilha_disputa SET dono_pronto = 1 WHERE ilha = ?",
        "i", array($userDetails->ilha["ilha"]));

    if ($disputa["vencedor_pronto"]) {
        teleporta_adversario_ilha($disputa["vencedor_id"]);

        header("location:../../Scripts/Mapa/mapa_atacar.php?id=" . $disputa["vencedor_id"] . "&tipo=5");
    }
} else {
    $protector->exit_error("Requisicao invalida");
}