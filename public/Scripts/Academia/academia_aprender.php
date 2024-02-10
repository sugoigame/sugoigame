<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

$protector->must_be_out_of_missao();

$class = $protector->get_number_or_exit("class");
$cod = $protector->get_number_or_exit("cod");

$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$cont = $result->count();

if ($cont == 0) {
    echo ("#Personagem não encontrado.");
    exit();
}
$personagem = $result->fetch_array();

if ($personagem["classe"] != 0) {
    echo ("#Esse personagem já possuiu uma classe.");
    exit();
}

$bonus_sem_classe = get_bonus_excelencia(0, $personagem["excelencia_lvl"]);
$bonus_com_classe = get_bonus_excelencia($class, $personagem["excelencia_lvl"]);

$atributo = "";
if ($class == 1) {
    $atributo = " atk = atk + " . ($bonus_com_classe["atk"] - $bonus_sem_classe["atk"]) . ", ";
} else if ($class == 2) {
    $atributo = " def = def + " . ($bonus_com_classe["def"] - $bonus_sem_classe["def"]) . ", ";
} else if ($class == 3) {
    $atributo = " pre = pre + " . ($bonus_com_classe["pre"] - $bonus_sem_classe["pre"]) . ", ";
}

$query = "UPDATE tb_personagens 
	SET $atributo classe_treino='0', classe='$class', classe_score='1000' 
	WHERE cod='" . $personagem["cod"] . "'";
$connection->run($query);
$response->send_conquista_pers($personagem, $personagem["nome"] . " se tornou um " . nome_classe($class) . "!");
