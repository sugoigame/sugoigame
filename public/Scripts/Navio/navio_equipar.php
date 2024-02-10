<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}

$carpok = FALSE;
for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4) {
        $carpok = TRUE;
    }
}

if (! $carpok) {

    echo ("#Você precisa de um carpinteiro");
    exit();
}
if (! isset($_GET["cod"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! isset($_GET["tipo"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$item = $protector->get_number_or_exit("cod");
$tipo = $protector->get_number_or_exit("tipo");

if (! preg_match("/^[\d]+$/", $item)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $tipo)) {

    echo ("#Você informou algum caracter inválido.");
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

        echo ("#Você informou algum caracter inválido.");
        exit();
        break;
}

$query = "SELECT * FROM $tb WHERE $cod='$item'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont != 1) {

    echo ("#Item nao encontrado");
    exit();
}
$item_info = $result->fetch_array();

$possivel = FALSE;
for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4 and $personagem[$x]["profissao_lvl"] >= $item_info["requisito_lvl"]) {
        $possivel = TRUE;
    }
}
if (! $possivel) {

    echo ("#Seu carpinteiro nao cumpre os requisitos para instalar este item.");
    exit();
}
if ($tipo == 3) {
    $hpmax = 100 + (($userDetails->navio["lvl"] - 1) * 10) + $item_info["bonus"];
} else {
    $hpmax = $usuario["navio_hp_max"];
}
$hp = $hpmax;

$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
$connection->run($query) or die("Nao foi possivel remover o item do iventario");

$query = "UPDATE tb_usuario_navio SET $cod='$item', hp='$hp', hp_max='$hpmax' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel instalar o item");

for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4
        and $personagem[$x]["profissao_lvl"] == $item_info["requisito_lvl"]
        and $personagem[$x]["profissao_xp"] < $personagem[$x]["profissao_xp_max"]
    ) {
        $xp = $personagem[$x]["profissao_xp"] + 1;
        $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem[$x]["cod"] . "'";
        $connection->run($query) or die("Nao foi possivel evoluir profissao");
    }
}

echo ("-Iten instalado!");

?>

