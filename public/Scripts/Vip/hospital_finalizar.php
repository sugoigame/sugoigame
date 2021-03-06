<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    echo("#Você está em combate!");
    exit();
}

$protector->need_gold(PRECO_GOLD_FINALIZAR_TRATAMENTO_HOSPITAL);

if (!isset($_GET["cod"])) {
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = $_GET["cod"];
if (!preg_match("/^[\d]+$/", $cod)) {
    echo("#Você informou algum caracter inválido.");
    exit();
}
$result = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?", 'ii', [
    $cod,
    $usuario['id']
]);

if ($result->count() == 0) {
    echo("#Personagem não encontrado.");
    exit();
}
$personagem = $result->fetch_array();

if ($personagem["respawn_tipo"] == 1) {
    $connection->run("UPDATE tb_personagens SET profissao_xp = profissao_xp + 1 WHERE profissao = ? AND profissao_xp < profissao_xp_max AND id = ? AND ativo = 1", "ii", [
        PROFISSAO_MEDICO,
        $userDetails->tripulacao["id"]
    ]);
}

$connection->run("UPDATE tb_personagens SET respawn_tipo = '0', respawn = '0', mp = ?,  hp = ? WHERE cod = ?", 'iii', [
    $personagem['mp_max'],
    $personagem['hp_max'],
    $cod
]);

$userDetails->reduz_gold(PRECO_GOLD_FINALIZAR_TRATAMENTO_HOSPITAL, "finalizar_hospital");

echo "-Tratamento Concluido!";
