<?php

function cron_atualiza_hp_boss()
{
    global $connection;

    $ultimo_reset = get_value_int_variavel_global(VARIAVEL_ULTIMO_RESET_HP_BOSS);

    if ($ultimo_reset < atual_segundo() - 60 * 60) {
        set_value_int_variavel_global(VARIAVEL_ULTIMO_RESET_HP_BOSS, atual_segundo());
        $connection->run("UPDATE tb_boss SET hp = 1000000");
    }
}

cron_atualiza_hp_boss();
