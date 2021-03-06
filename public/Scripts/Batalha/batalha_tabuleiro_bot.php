<?php
require_once "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_bot();

include "batalha_tabuleiro_bot_content.php";
