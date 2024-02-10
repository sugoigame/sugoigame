<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! isset($_GET["cod"]) or ! isset($_GET["alc"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$cod = $protector->get_number_or_exit("cod");
$atr = $protector->get_number_or_exit("alc");

if (! preg_match("/^[\d]+$/", $atr) or ! preg_match("/^[\d]+$/", $cod)) {

    echo ("#Você informou algo inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Personagem não encontrado.");
    exit();
}

if ($atr != 0) {
    $titulos_compartilhados = $connection->run(
        "SELECT tit.cod_titulo, tit.nome, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
            INNER JOIN tb_personagens per ON pertit.cod = per.cod
            INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
            WHERE tit.compartilhavel = 1 AND per.id = ? AND tit.cod_titulo = ?",
        "ii", array($userDetails->tripulacao["id"], $atr)
    );

    if (! $titulos_compartilhados->count()) {
        $query = "SELECT * FROM tb_personagem_titulo WHERE cod='$cod' AND titulo='$atr'";
        $result = $connection->run($query);
        if ($result->count() == 0) {

            echo ("#Alcunha inválida.");
            exit();
        }
    }
}
if ($atr == 0) {
    $atr = "NULL";
} else {
    $atr = "'$atr'";
}

$query = "UPDATE tb_personagens SET titulo= $atr  WHERE cod='$cod'";
$connection->run($query) or die("não foi possivel alterar o titulo");

echo "Alcunha alterada";

?>

