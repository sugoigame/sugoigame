<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_dono_ilha();

$bonus_id = $protector->get_number_or_exit("bonus");

$bonus_disponiveis = DataLoader::load("bonus_ilha");

if (!isset($bonus_disponiveis[$bonus_id])) {
    $protector->exit_error("Bônus inválido");
}

$bonus = $bonus_disponiveis[$bonus_id];

$buff_ativo = $connection->run("SELECT * FROM tb_ilha_bonus_ativo WHERE ilha = ? AND buff_id = ?",
    "ii", array($userDetails->ilha["ilha"], $bonus["buff_id"]));

if ($buff_ativo->count()) {
    $protector->exit_error("Este bônus já está ativo");
}

$recursos = $connection->run("SELECT * FROM tb_ilha_recurso WHERE ilha = ?",
    "i", array($userDetails->ilha["ilha"]))->fetch_array();

if ($recursos["recurso_0"] < $bonus["preco_0"]
    || $recursos["recurso_1"] < $bonus["preco_1"]
    || $recursos["recurso_2"] < $bonus["preco_2"]
) {
    $protector->exit_error("Você não possui recursos suficientes para ativar este bonus");
}

$buffs = DataLoader::load("buffs_tripulacao");
$buff = $buffs[$bonus["buff_id"]];

$connection->run("INSERT INTO tb_ilha_bonus_ativo (ilha, x, y, buff_id, expiracao) VALUE (?,?,?,?,?)",
    "iiiii", array(
        $userDetails->ilha["ilha"],
        $userDetails->ilha["x"],
        $userDetails->ilha["y"],
        $bonus["buff_id"],
        atual_segundo() + DURACAO_BONUS_ILHA
    ));

$connection->run("UPDATE tb_personagens SET fama_ameaca = fama_ameaca + ? WHERE cod = ?",
    "ii", array($bonus["fa"], $userDetails->capitao["cod"]));

$connection->run("UPDATE tb_ilha_recurso SET recurso_0 = recurso_0 - ?, recurso_1 = recurso_1 - ?, recurso_2 = recurso_2 - ? WHERE ilha = ?",
    "iiii", array($bonus["preco_0"], $bonus["preco_1"], $bonus["preco_2"], $userDetails->ilha["ilha"]));

$connection->run("INSERT INTO tb_news_coo (msg) VALUE (?)",
    "s", array($userDetails->tripulacao["tripulacao"] . " ativou um bônus em " . nome_ilha($userDetails->ilha["ilha"])));

echo "-O Bônus foi ativado!";