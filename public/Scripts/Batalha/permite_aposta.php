<?php
require "../../Includes/conectdb.php";


$protector->need_tripulacao();
$protector->must_be_in_combat_pvp();

$my_id = $userDetails->combate_pvp["id_1"] == $userDetails->tripulacao["id"] ? "1" : "2";
$ini_id = $userDetails->combate_pvp["id_1"] == $userDetails->tripulacao["id"] ? "2" : "1";

$connection->run("UPDATE tb_combate SET permite_apostas_$my_id = 1 WHERE combate = ?",
    "i", $userDetails->combate_pvp["combate"]);

if ($userDetails->combate_pvp["permite_apostas_$ini_id"]) {
    $mais_forte_adversario = $connection->run("SELECT max(lvl) AS lvl FROM tb_personagens WHERE id = ? AND ativo = 1",
        "i", array($userDetails->combate_pvp["id_$ini_id"]))->fetch_array()["lvl"];

    $mais_baixo = min($userDetails->lvl_mais_forte, $mais_forte_adversario);

    $preco = $mais_baixo * 10000;
    $connection->run("UPDATE tb_combate SET premio_apostas = ?, preco_apostas= ? WHERE combate = ?",
        "iii", array($preco * 2, $preco, $userDetails->combate_pvp["combate"]));

    $tripulacao_adversaria = $connection->run("SELECT tripulacao FROM tb_usuarios WHERE id = ?",
        "i", array($userDetails->combate_pvp["id_$ini_id"]))->fetch_array()["tripulacao"];

    $connection->run(
        "INSERT INTO tb_news_coo (msg) VALUE (?)",
        "s", array(
            '<a class="link_content" href="./?ses=combateAssistir&combate=' . $userDetails->combate_pvp["combate"] . '" >As apostas pela batalha de ' . $userDetails->tripulacao["tripulacao"] . " contra " . $tripulacao_adversaria . " estão abertas!</a>"
        )
    );
}

echo "Você permitiu apostas na sua batalha";