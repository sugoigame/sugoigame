<?php
require_once "Includes/conectdb.php";

$token = array("sg_c" => $_SESSION["sg_c"], "sg_k" => $_SESSION["sg_k"]);

echo json_encode($token);