<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = mysql_real_escape_string($_GET["cod"]);

if (!ereg("([0-9])", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Personagem não encotrado.");
    exit();
}

$personagem = mysql_fetch_array($result);

$query = "DELETE FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod' LIMIT 1";
mysql_query($query) or die("Nao foi possivel demitir o persoangem1");

$query = "DELETE FROM tb_personagens_skil WHERE cod='$cod'";
mysql_query($query) or die("Nao foi possivel demitir o persoangem");

mysql_close();
echo("Tripulante expulso!");
exit();
?>