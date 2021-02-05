<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id = $protector->get_number_or_exit("id");
$rate = $protector->get_number_or_exit("rate");

$my_rate = $connection->run(
    "SELECT * FROM tb_kanban_rate WHERE conta_id = ? AND kanban_item_id = ?",
    "ii", array($userDetails->conta["conta_id"], $id)
);

if ($my_rate->count()) {
    $connection->run(
        "UPDATE tb_kanban_rate SET rate = ? WHERE conta_id = ? AND kanban_item_id = ?",
        "iii", array($rate, $userDetails->conta["conta_id"], $id)
    );
} else {
    $connection->run(
        "INSERT INTO tb_kanban_rate (kanban_item_id, conta_id, rate) VALUES (?, ?, ?)",
        "iii", array($id, $userDetails->conta["conta_id"], $rate)
    );
}

echo "-Seu voto foi enviado com sucesso.";