<?php
function get_current_batalha_poneglyphs()
{
    $now = time();
    $current_month = date("n", $now);
    $current_year = date("Y", $now);
    $start = mktime(0, 0, 0, $current_month, 1, $current_year);
    $end = strtotime("+1 months", $start);

    return [
        "start" => $start,
        "end" => $end,
        "id" => $current_year . "-" . $current_month,
    ];
}

function cron_atualiza_batalha_poneglyphs()
{
    global $connection;

    $batalha_atual = get_current_batalha_poneglyphs();
    $batalha_registrada = get_value_varchar_variavel_global(VARIAVEL_BATALHA_PONEGLYPH_ATUAL);

    if ($batalha_registrada != $batalha_atual["id"]) {
        set_value_varchar_variavel_global(VARIAVEL_BATALHA_PONEGLYPH_ATUAL, $batalha_atual["id"]);

        // TODO entrega premios de fim de era
    }
}

cron_atualiza_batalha_poneglyphs();
