<?php
require_once "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

if ($userDetails->combate_pvp && $userDetails->combate_pvp["tipo"] != 3 && $userDetails->combate_pvp["tipo"] != 4) {
    $tripulacoes = $userDetails->tripulacoes_pvp;

    $id_adversario = $userDetails->tripulacao["id"] == $tripulacoes["1"]["id"] ? "2" : "1";

    $trip_adversario = $connection->run(
        "SELECT 
          cbtpers.cod AS cod,
          pers.lvl AS lvl,
          pers.classe AS classe,
          pers.classe_score AS classe_score,
          pers.fama_ameaca AS fama_ameaca
          FROM tb_combate_personagens cbtpers
          INNER JOIN tb_personagens AS pers ON cbtpers.cod = pers.cod
          WHERE cbtpers.hp > 0 AND cbtpers.id = ?",
        "i", $tripulacoes[$id_adversario]["id"]
    )->fetch_all_array();

    $minha_trip_combate = $connection->run(
        "SELECT 
          cbtpers.cod AS cod,
          pers.lvl AS lvl,
          pers.classe AS classe,
          pers.classe_score AS classe_score,
          pers.fama_ameaca AS fama_ameaca
          FROM tb_combate_personagens cbtpers
          INNER JOIN tb_personagens AS pers ON cbtpers.cod = pers.cod
          WHERE cbtpers.hp > 0 AND cbtpers.id = ?",
        "i", $userDetails->tripulacao["id"]
    )->fetch_all_array();

    foreach ($minha_trip_combate as $pers) {
        reduz_score($pers);
    }

} else if ($userDetails->combate_pve && !$userDetails->combate_pve["boss_id"]) {
    $minha_trip_combate = $connection->run(
        "SELECT 
          cbtpers.cod AS cod,
          pers.lvl AS lvl,
          pers.classe AS classe,
          pers.classe_score AS classe_score
          FROM tb_combate_personagens cbtpers
          INNER JOIN tb_personagens AS pers ON cbtpers.cod = pers.cod
          WHERE cbtpers.hp > 0 AND cbtpers.id = ?",
        "i", $userDetails->tripulacao["id"]
    )->fetch_all_array();

    foreach ($minha_trip_combate as $pers) {
        reduz_score($pers);
    }
}

$connection->run("UPDATE tb_combate_personagens SET hp = 0, desistencia = 1 WHERE id = ? AND hp > 0", "i", $userDetails->tripulacao["id"]);


echo "-Você desistiu do combate";
