<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_dobroes(PRECO_DOBRAO_RESET_AKUMA);

if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$personagem = mysql_real_escape_string($_GET["cod"]);

if (!preg_match("/^[\d]/", $personagem)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE cod='$personagem' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Persoangem nao encontrado");
    exit();
}
$pers = mysql_fetch_array($result);

$query = "SELECT * FROM tb_akuma WHERE cod='$personagem'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Esse personagem não comeu nenhuma Akuma no Mi");
    exit();
}

$userDetails->restaura_effects($pers, "(tipo IN (7,8,9))");

$akuma = mysql_fetch_array($result);

$query = "DELETE FROM tb_akuma_skil_atk WHERE cod_akuma='" . $akuma["cod_akuma"] . "'";
mysql_query($query) or die("Nao foi possivel remover as habilidades.");
$query = "DELETE FROM tb_akuma_skil_buff WHERE cod_akuma='" . $akuma["cod_akuma"] . "'";
mysql_query($query) or die("Nao foi possivel remover as habilidades.");
$query = "DELETE FROM tb_akuma_skil_passiva WHERE cod_akuma='" . $akuma["cod_akuma"] . "'";
mysql_query($query) or die("Nao foi possivel remover as habilidades.");

$query = "DELETE FROM tb_akuma WHERE cod='$personagem'";
mysql_query($query) or die("Nao foi possivel remover a akuma.");


$query = "DELETE FROM tb_personagens_skil WHERE cod='$personagem' AND 
	(tipo='7' OR tipo='8' OR tipo='9')";
mysql_query($query) or die("Nao foi possivel remover as habilidades.");

$query = "UPDATE tb_personagens 
	SET akuma=NULL, maestria=0
	WHERE cod='$personagem'";
mysql_query($query) or die("Nao foi possivel remover a akuma.");

$userDetails->reduz_dobrao(PRECO_DOBRAO_RESET_AKUMA, "resetar_akuma");

mysql_close();
echo("-Akuma no Mi Removida");
