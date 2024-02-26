<?php
function get_current_evento_periodico()
{
    $now = time();
    $current_week = date("W", $now);
    $current_day_of_week = date("w", $now);
    $current_day_of_month = date("j", $now);
    $current_month = date("n", $now);
    $current_year = date("Y", $now);
    $current_week_quarter = $current_week % 4;
    $start = strtotime('-' . $current_day_of_week . ' days',
        mktime(0, 0, 0, $current_month, $current_day_of_month, $current_year)
    );
    $end = strtotime("+1 weeks", $start);

    $eventos = [
        "eventoLadroesTesouro",
        "eventoChefesIlhas",
        "boss",
        "eventoPirata"
    ];

    return [
        "start" => $start,
        "end" => $end,
        "id" => $eventos[$current_week_quarter],
    ];
}

function cron_atualiza_evento_periodico()
{
    global $connection;

    $evento = get_current_evento_periodico();
    $evento_registrado = get_value_varchar_variavel_global(VARIAVEL_EVENTO_PERIODICO_ATIVO);

    if ($evento_registrado != $evento["id"]) {
        set_value_varchar_variavel_global(VARIAVEL_EVENTO_PERIODICO_ATIVO, $evento["id"]);

        // TODO entrega premios de fim de era
    }
}

cron_atualiza_evento_periodico();
