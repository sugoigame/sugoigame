<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (!isset($_GET["akuma"])) {
    echo 0;
    exit();
}
$akuma = mysql_real_escape_string($_GET["akuma"]);
if (!ereg("[0-9a-zA-Z]", $akuma)) {
    echo 0;
    exit();
}
$sql = "SELECT nome FROM tb_akuma WHERE nome='$akuma'";
$result = mysql_query($sql);
$cont = mysql_num_rows($result);
if ($cont == 0) echo 1;
else echo 0;
mysql_close();