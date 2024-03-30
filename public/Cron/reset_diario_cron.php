<?php
function cron_reset_diario()
{
    global $connection;
    global $userDetails;

    $ultimo_reset = $userDetails->tripulacao["ultimo_reset"]
        ? strtotime($userDetails->tripulacao["ultimo_reset"])
        : strtotime("-1 day", time());
    $reset_day_of_month = date("j", $ultimo_reset);
    $reset_month = date("n", $ultimo_reset);
    $reset_year = date("Y", $ultimo_reset);

    $now = time();
    $current_day_of_month = date("j", $now);
    $current_month = date("n", $now);
    $current_year = date("Y", $now);
    if (! $userDetails->tripulacao["ultimo_reset"]
        || $reset_day_of_month != $current_day_of_month
        || $reset_month != $current_month
        || $reset_year != $current_year) {

        $connection->run(
            "UPDATE tb_usuarios
                SET presente_diario_obtido = 0,
                iscas_usadas = 0,
                ultimo_reset = CURDATE()
                WHERE id = ?",
            "i", [$userDetails->tripulacao["id"]]);
    }

}
// essa rotina pode rodar em qualquer requisicao de qualquer jogador
cron_reset_diario();
