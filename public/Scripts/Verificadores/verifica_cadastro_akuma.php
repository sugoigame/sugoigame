<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (! isset($_GET["akuma"])) {
    echo 0;
    exit();
}
$akuma = $protector->get_alphanumeric_or_exit("akuma");
if (! ereg("[0-9a-zA-Z]", $akuma)) {
    echo 0;
    exit();
}
$sql = "SELECT nome FROM tb_akuma WHERE nome='$akuma'";
$result = $connection->run($sql);
$cont = $result->count();
if ($cont == 0)
    echo 1;
else
    echo 0;

