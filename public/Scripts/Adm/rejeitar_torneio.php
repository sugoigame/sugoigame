<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

$protector->must_be_gm();

$id = $protector->get_number_or_exit("id");

$query = "UPDATE tb_torneio_inscricao SET status='1' WHERE id='$id'";
$connection->run($query);

echo "inscrição rejeitada";
