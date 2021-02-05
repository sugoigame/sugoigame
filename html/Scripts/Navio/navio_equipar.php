<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$carpok = FALSE;
for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4) {
        $carpok = TRUE;
    }
}

if (!$carpok) {
    mysql_close();
    echo("#Você precisa de um carpinteiro");
    exit();
}
if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_GET["tipo"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$item = mysql_real_escape_string($_GET["cod"]);
$tipo = mysql_real_escape_string($_GET["tipo"]);

if (!preg_match("/^[\d]+$/", $item)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $tipo)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

switch ($tipo) {
    case 3:
        $tb = "tb_item_navio_casco";
        $cod = "cod_casco";
        break;
    case 4:
        $tb = "tb_item_navio_leme";
        $cod = "cod_leme";
        break;
    case 5:
        $tb = "tb_item_navio_velas";
        $cod = "cod_velas";
        break;
    case 12:
        $tb = "tb_item_navio_canhao";
        $cod = "cod_canhao";
        break;
    default:
        mysql_close();
        echo("#Você informou algum caracter inválido.");
        exit();
        break;
}

$query = "SELECT * FROM $tb WHERE $cod='$item'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont != 1) {
    mysql_close();
    echo("#Item nao encontrado");
    exit();
}
$item_info = mysql_fetch_array($result);

$possivel = FALSE;
for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4 AND $personagem[$x]["profissao_lvl"] >= $item_info["requisito_lvl"]) {
        $possivel = TRUE;
    }
}
if (!$possivel) {
    mysql_close();
    echo("#Seu carpinteiro nao cumpre os requisitos para instalar este item.");
    exit();
}
if ($tipo == 3) {
    $hpmax = 100 + (($userDetails->navio["lvl"] - 1) * 10) + $item_info["bonus"];
} else {
    $hpmax = $usuario["navio_hp_max"];
}
$hp = $hpmax;

$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
mysql_query($query) or die("Nao foi possivel remover o item do iventario");

$query = "UPDATE tb_usuario_navio SET $cod='$item', hp='$hp', hp_max='$hpmax' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel instalar o item");

for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4
        AND $personagem[$x]["profissao_lvl"] == $item_info["requisito_lvl"]
        AND $personagem[$x]["profissao_xp"] < $personagem[$x]["profissao_xp_max"]
    ) {
        $xp = $personagem[$x]["profissao_xp"] + 1;
        $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem[$x]["cod"] . "'";
        mysql_query($query) or die("Nao foi possivel evoluir profissao");
    }
}
mysql_close();
echo("-Iten instalado!");

?>