<?php

include_once (dirname(__FILE__) . "/mini_eventos_cron.php");
include_once (dirname(__FILE__) . "/era_dos_piratas_cron.php");
include_once (dirname(__FILE__) . "/batalha_poneglyphs_cron.php");
include_once (dirname(__FILE__) . "/evento_periodico_cron.php");
include_once (dirname(__FILE__) . "/disputa_ilha_cron.php");
include_once (dirname(__FILE__) . "/torneio_poneglyph_cron.php");
include_once (dirname(__FILE__) . "/reset_diario_cron.php");
include_once (dirname(__FILE__) . "/boss_cron.php");

$eventos_ativos = [
    "eraDosPiratas" => get_current_era(),
    "batalhaPoneglyphs" => get_current_batalha_poneglyphs(),
    "eventoPeriodico" => get_current_evento_periodico(),
    "disputaIlha" => get_current_disputa_ilha()
];
