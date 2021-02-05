<?php
require "../../Includes/conectdb.php";

$response->send_loot(array(), $userDetails->capitao["nome"] . " alcançou o nível de excelência 50!");
