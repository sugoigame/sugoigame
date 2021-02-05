<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont >= $usuario["capacidade_iventario"]) {
    mysql_close();
    echo("#Seu iventário está lotado.");
    exit();
}
$item = array();
$i = 0;
$mapa = false;
while ($sql = mysql_fetch_array($result)) {
    $item[$i]["cod"] = $sql["cod_item"];
    $item[$i]["tipo"] = $sql["tipo_item"];
    $item[$i]["quant"] = $sql["quant"];
    $i += 1;
}
for ($x = 0; $x < sizeof($item); $x++) {
    if ($item[$x]["tipo"] == 2) {
        $mapa = TRUE;
    }
}
if (!$mapa AND $cont < $usuario["capacidade_iventario"]) {
    $berries = $usuario["berries"] - 2000;

    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
    mysql_query($query) or die("#001 - " . mysql_error());

    $query = "INSERT INTO tb_item_mapa (id) VALUES ('" . $usuario["id"] . "')";
    mysql_query($query) or die("#003 - " . mysql_error());

    $query = "SELECT * FROM tb_item_mapa WHERE id='" . $usuario["id"] . "'";
    $result = mysql_query($query);
    $sql = mysql_fetch_array($result);
    $cod_mapa = $sql["cod_mapa"];

    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) 
        VALUES ('" . $usuario["id"] . "', '$cod_mapa', '2', '1')";
    mysql_query($query) or die("#003 - " . mysql_error());

    mysql_close();
    echo("Mapa comprado!");
} else {
    mysql_close();
    echo("#Você já tem um mapa.");
}
?>