<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

	header("location:../../index.php");
	exit();
}

$query = "UPDATE tb_conta SET tripulacao_id=NULL WHERE conta_id='" . $usuario["conta_id"] . "'";
$connection->run($query) or die("nao foi possivel deslogar");


header("location:../../?ses=seltrip");
