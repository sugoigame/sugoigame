<?php

include_once(dirname(__FILE__) . "/mini_eventos_cron.php");
include_once(dirname(__FILE__) . "/era_dos_piratas_cron.php");
include_once(dirname(__FILE__) . "/batalha_poneglyphs_cron.php");
include_once(dirname(__FILE__) . "/evento_periodico_cron.php");

$eventos_ativos = [
    "eraDosPiratas" => get_current_era(),
    "batalhaPoneglyphs" => get_current_batalha_poneglyphs(),
    "eventoPeriodico" => get_current_evento_periodico()
];
