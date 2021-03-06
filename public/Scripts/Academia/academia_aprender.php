<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado.");
    exit();
}
if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!isset($_GET["class"]) OR !isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$class = mysql_real_escape_string($_GET["class"]);
$cod = mysql_real_escape_string($_GET["cod"]);

if (!preg_match("/^[\d]+$/", $class) OR !preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);

if ($cont == 0) {
    mysql_close();
    echo("#Personagem não encontrado.");
    exit();
}
$personagem = mysql_fetch_array($result);

if ($personagem["classe"] != 0) {
    mysql_close();
    echo("#Esse personagem já possuiu uma classe.");
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
mysql_query($query) or die("nao foi possivel finalizar o treinamento");
mysql_close();
$response->send_conquista_pers($personagem, $personagem["nome"] . " se tornou um " . nome_classe($class) . "!");