<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));
$protector->need_gold_or_dobrao($tipo, PRECO_GOLD_RESET_CLASSE, PRECO_DOBRAO_RESET_CLASSE);

$pers = $protector->get_tripulante_or_exit("cod");

$bonus_atual = get_bonus_excelencia($pers["classe"], $pers["excelencia_lvl"]);
$bonus_sem_classe = get_bonus_excelencia(0, $pers["excelencia_lvl"]);

$atributo = "";
if ($pers["classe"] == 1) {
    $atributo = " atk = atk - " . ($bonus_atual["atk"] - $bonus_sem_classe["atk"]) . ", ";
} else if ($pers["classe"] == 2) {
    $atributo = " def = def - " . ($bonus_atual["def"] - $bonus_sem_classe["def"]) . ", ";
} else if ($pers["classe"] == 3) {
    $atributo = " pre = pre - " . ($bonus_atual["pre"] - $bonus_sem_classe["pre"]) . ", ";
}

$connection->run(
    "UPDATE tb_personagens 
	SET $atributo classe='0', classe_score='0'
	WHERE cod= ?",
    "i", array($pers["cod"]));

$userDetails->remove_skills_classe($pers);

$userDetails->reduz_gold_or_dobrao($tipo, PRECO_GOLD_RESET_CLASSE, PRECO_DOBRAO_RESET_CLASSE, "resetar_classe");

echo("-Classe resetada!");
