<?php
function get_current_era()
{
    $now = time();
    $current_month = date("n", $now);
    $current_year = date("Y", $now);
    $current_trimestre = floor($current_month / 3);
    $start = mktime(0, 0, 0, $current_trimestre * 3 + 1, 1, $current_year);
    $end = strtotime("+3 months", $start);

    return [
        "start" => $start,
        "end" => $end,
        "id" => $current_year . "-" . $current_trimestre,
    ];
}

function cron_atualiza_era()
{
    global $connection;

    $era_atual = get_current_era();
    $era_registrada = get_value_varchar_variavel_global(VARIAVEL_ERA_ATUAL);

    if ($era_registrada != $era_atual["id"]) {
        set_value_varchar_variavel_global(VARIAVEL_ERA_ATUAL, $era_atual["id"]);

        // TODO entrega premios de fim de era
    }
}

cron_atualiza_era();
