<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

$protector->need_tripulacao();
$protector->need_navio();
$protector->need_tripulacao_alive();
$protector->must_be_out_of_any_kind_of_combat();

$protector->need_gold(PRECO_GOLD_VIAJA_RETORNO);

$query = "UPDATE tb_usuarios SET x='" . $usuario["res_x"] . "', y='" . $usuario["res_y"] . "', mar_visivel=0
	WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel iniciar navegação");

$userDetails->reduz_gold(PRECO_GOLD_VIAJA_RETORNO, "transporte_gold");


echo ("%oceano");
