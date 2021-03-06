<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_RESETAR_INFLUENCIA);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_RESETAR_INFLUENCIA);
}

$total_espadachim = $connection->run(
    "SELECT IFNULL(SUM(i.pontos) + SUM(ceil((pow(1.2,i.nivel) - 1)/0.2)),0) AS total
      FROM tb_incursao_personagem i 
      INNER JOIN tb_personagens p ON i.personagem_id = p.cod
      WHERE i.tripulacao_id = ? AND i.ilha = ? AND p.classe = 1",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
)->fetch_array()["total"];
$total_lutador = $connection->run(
    "SELECT IFNULL(SUM(i.pontos) + SUM(ceil((pow(1.2,i.nivel) - 1)/0.2)),0)  AS total
      FROM tb_incursao_personagem i 
      INNER JOIN tb_personagens p ON i.personagem_id = p.cod
      WHERE i.tripulacao_id = ? AND i.ilha = ? AND p.classe = 2",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
)->fetch_array()["total"];
$total_atirador = $connection->run(
    "SELECT IFNULL(SUM(i.pontos) + SUM(ceil((pow(1.2,i.nivel) - 1)/0.2)),0)  AS total
      FROM tb_incursao_personagem i 
      INNER JOIN tb_personagens p ON i.personagem_id = p.cod
      WHERE i.tripulacao_id = ? AND i.ilha = ? AND p.classe = 3",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
)->fetch_array()["total"];

$connection->run(
    "UPDATE tb_incursao_pontos SET
    pontos_espadachim = pontos_espadachim + ?, 
    pontos_lutador = pontos_lutador + ?,
    pontos_atirador = pontos_atirador + ?
    WHERE tripulacao_id = ? AND ilha = ?",
    "iiiii", array($total_espadachim, $total_lutador, $total_atirador, $userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
);

$connection->run("DELETE FROM tb_incursao_personagem WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

if ($tipo == "gold") {
    $userDetails->reduz_gold(PRECO_GOLD_RESETAR_INFLUENCIA, "reset_influencia");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_RESETAR_INFLUENCIA, "reset_influencia");
}
echo "-Influência redefinida";