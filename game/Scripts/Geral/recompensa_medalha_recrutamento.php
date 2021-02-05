<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$afilhado_id = $protector->get_number_or_exit("id");

$afilhado = $connection->run(
    "SELECT 
          a.*,
         (SELECT count(p.id) FROM tb_vip_pagamentos p WHERE p.mensagem LIKE concat('%ouro para ', CAST(a.afilhado AS UNSIGNED))) AS ouro_comprado
         FROM tb_afilhados a 
         INNER JOIN tb_conta c ON a.afilhado = c.conta_id
         WHERE a.id = ? AND a.afilhado = ?",
    "ii", array($userDetails->conta["conta_id"], $afilhado_id)
);

if (!$afilhado->count()) {
    $protector->exit_error("Conta inválida");
}

$afilhado = $afilhado->fetch_array();

if (!$afilhado["ouro_comprado"]) {
    $protector->exit_error("Essa conta ainda não colocou moedas de ouro para que você consiga obter a recompensa");
}
if ($afilhado["medalha_ganha"]) {
    $protector->exit_error("Você já obteve a recompensa por esse jogador");
}

$connection->run("UPDATE tb_conta SET medalhas_recrutamento = medalhas_recrutamento + 1 WHERE conta_id = ?",
    "i", array($userDetails->conta["conta_id"]));

$connection->run("UPDATE tb_afilhados SET medalha_ganha = 1 WHERE id = ? AND afilhado = ?",
    "ii", array($userDetails->conta["conta_id"], $afilhado_id));

echo "-Parabéns! Você recebeu uma medalha de recrutamento!";