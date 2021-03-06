<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$afilhado_id = $protector->get_number_or_exit("id");

$afilhado = $connection->run(
    "SELECT 
          a.*,
         (SELECT max(p.lvl) FROM tb_personagens p INNER JOIN tb_usuarios u ON p.id = u.id WHERE u.conta_id = c.conta_id) AS lvl_mais_forte
         FROM tb_afilhados a 
         INNER JOIN tb_conta c ON a.afilhado = c.conta_id
         WHERE a.id = ? AND a.afilhado = ?",
    "ii", array($userDetails->conta["conta_id"], $afilhado_id)
);

if (!$afilhado->count()) {
    $protector->exit_error("Conta inválida");
}

$afilhado = $afilhado->fetch_array();

if ($afilhado["lvl_mais_forte"] < 50) {
    $protector->exit_error("Essa conta ainda não alcançou o nível 50 para que você consiga obter a recompensa");
}
if ($afilhado["berries_ganhos"]) {
    $protector->exit_error("Você já obteve a recompensa por esse jogador");
}

$connection->run("UPDATE tb_usuarios SET berries = berries + 10000000 WHERE conta_id = ?",
    "i", array($userDetails->conta["conta_id"]));

$connection->run("UPDATE tb_afilhados SET berries_ganhos = 1 WHERE id = ? AND afilhado = ?",
    "ii", array($userDetails->conta["conta_id"], $afilhado_id));

echo "-Parabéns! Você recebeu 10 milhões de Berries em todas as tripulações da sua conta!";