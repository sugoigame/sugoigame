<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));
$protector->need_gold_or_dobrao($tipo, PRECO_GOLD_RESET_PROFISSAO, PRECO_DOBRAO_RESET_PROFISSAO);

$pers = $protector->get_tripulante_or_exit("cod");

$connection->run("UPDATE tb_personagens 
	SET profissao='0', profissao_lvl='0', profissao_xp='0', profissao_xp_max='0'
	WHERE cod=?", "i", array($pers["cod"]));

$userDetails->remove_skills_profissao($pers);

$userDetails->reduz_gold_or_dobrao($tipo, PRECO_GOLD_RESET_PROFISSAO, PRECO_DOBRAO_RESET_PROFISSAO, "resetar_profissao");

echo("-Profissão resetada!");
