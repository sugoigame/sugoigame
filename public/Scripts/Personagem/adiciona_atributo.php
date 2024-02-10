<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! isset($_GET["atributo"]) or ! isset($_GET["cod"]) or ! isset($_GET["quant"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$cod = $protector->get_number_or_exit("cod");
$atr = $protector->get_alphanumeric_or_exit("atributo");
$quant = $protector->get_number_or_exit("quant");

if (! preg_match("/^[\w]+$/", $atr) or ! preg_match("/^[\d]+$/", $cod) or ! preg_match("/^[\d]+$/", $quant)) {

    echo ("#Você informou algo inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$pers = $result->fetch_array();

if ($quant > 0 and $pers["pts"] >= $quant and $conect) {
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
    $result = $connection->run($query) or die($query);
} else {
    echo "!";
}

