<?php
require_once "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_pvp();


include "batalha_tabuleiro_pvp_content.php";
