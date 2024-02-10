<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}

$query = "UPDATE tb_usuarios SET " .
    "recrutando='0' " .
    "WHERE id='" . $usuario["id"] . "'";
$connection->run($query);

$query = "UPDATE tb_personagens SET respawn='0', respawn_tipo='0', classe_aprender='0' WHERE id='" . $usuario["id"] . "'";

$query = "DELETE FROM tb_missoes_iniciadas WHERE id='" . $usuario["id"] . "'";
$connection->run($query);

$query = "DELETE FROM tb_missoes_r WHERE id='" . $usuario["id"] . "'";
$connection->run($query);

$query = "DELETE FROM tb_rotas WHERE id='" . $usuario["id"] . "'";
$connection->run($query);

$query = "DELETE FROM tb_marcenaria_reparos WHERE id='" . $usuario["id"] . "'";
$connection->run($query);


echo "Todos as tarefas por tempo foram resetadas!";
