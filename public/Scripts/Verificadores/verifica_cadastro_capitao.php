<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (! isset($_GET["capitao"])) {
    echo 0;
    exit();
}
$capitao = $_GET["capitao"];
if (! preg_match("/^[\w ]+$/", $capitao)) {
    echo 0;
    exit();
}
$sql = "SELECT * FROM tb_personagens WHERE nome='$capitao'";
$result = $connection->run($sql);
$cont = $result->count();
if ($cont == 0) {
    echo 1;
} else {
    echo 0;
}


