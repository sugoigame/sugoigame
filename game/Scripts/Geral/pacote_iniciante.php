<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$padrinho = $connection->run(
    "SELECT * FROM tb_afilhados a INNER JOIN tb_conta c ON a.id = c.conta_id WHERE a.afilhado = ?",
    "i", array($userDetails->conta["conta_id"])
);

if (!$padrinho->count()) {
    $protector->exit_error("Você não foi recrutado");
}

$padrinho = $padrinho->fetch_array();

if ($padrinho["bau_ganho"]) {
    $protector->exit_error("Você já recebeu essa recompensa");
}

if (!$userDetails->can_add_item()) {
    $protector->exit_error("Seu inventário está cheio. Libere espaço para receber a recompensa");
}

$userDetails->add_item(123, TIPO_ITEM_REAGENT, 1);

$connection->run("UPDATE tb_afilhados SET bau_ganho = 1 WHERE afilhado = ?",
    "i", array($userDetails->conta["conta_id"]));

echo "-Parabéns! Você recebeu um pacote do iniciante!";