<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!isset($_GET["atributo"]) OR !isset($_GET["cod"]) OR !isset($_GET["quant"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = mysql_real_escape_string($_GET["cod"]);
$atr = mysql_real_escape_string($_GET["atributo"]);
$quant = mysql_real_escape_string($_GET["quant"]);

if (!preg_match("/^[\w]+$/", $atr) OR !preg_match("/^[\d]+$/", $cod) OR !preg_match("/^[\d]+$/", $quant)) {
    mysql_close();
    echo("#Você informou algo inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$pers = mysql_fetch_array($result);

if ($quant > 0 AND $pers["pts"] >= $quant AND $conect) {
    $pers[$atr] += $quant;
    $pers["pts"] -= $quant;
    $query = "UPDATE tb_personagens SET " . $atr . "='" . $pers[$atr] . "', pts='" . $pers["pts"] . "'";
    if ($atr == "vit") {
        $pers["hp"] += (30 * $quant);
        $pers["hp_max"] += (30 * $quant);
        $pers["mp"] += (7 * $quant);
        $pers["mp_max"] += (7 * $quant);
        $query .= ", vit='" . $pers[$atr] . "', hp_max='" . $pers["hp_max"] . "' ,hp='" . $pers["hp"] . "', mp_max='"
            . $pers["mp_max"] . "', mp = '" . $pers["mp"] . "' ";
    }
    $query .= "WHERE cod='" . $cod . "'";
    $result = mysql_query($query) or die ($query);
} else {
    echo "!";
}
mysql_close();