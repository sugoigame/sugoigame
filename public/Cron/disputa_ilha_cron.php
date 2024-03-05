<?php

$disputas_ilhas = [
    [
        "id" => nome_ilha(33), // rain base
        "ilha_id" => 33,
        "day_of_week" => 0,
        "start_hour" => "02:00:00"
    ],
    [
        "id" => nome_ilha(35), // alubarna
        "ilha_id" => 35,
        "day_of_week" => 0,
        "start_hour" => "23:00:00"
    ],
    [
        "id" => nome_ilha(44), // punk hazard
        "ilha_id" => 44,
        "day_of_week" => 1,
        "start_hour" => "01:00:00"
    ],
    [
        "id" => nome_ilha(41), // triller bark
        "ilha_id" => 41,
        "day_of_week" => 1,
        "start_hour" => "18:00:00"
    ],
    [
        "id" => nome_ilha(40), // water 7
        "ilha_id" => 40,
        "day_of_week" => 1,
        "start_hour" => "20:00:00"
    ],
    [
        "id" => nome_ilha(45), // yukiryu
        "ilha_id" => 45,
        "day_of_week" => 1,
        "start_hour" => "23:00:00"
    ],
    [
        "id" => nome_ilha(34), // yuba
        "ilha_id" => 34,
        "day_of_week" => 2,
        "start_hour" => "00:00:00"
    ],
    [
        "id" => nome_ilha(37), // mocktown
        "ilha_id" => 37,
        "day_of_week" => 2,
        "start_hour" => "13:00:00"
    ],
    [
        "id" => nome_ilha(30), // whiskey peaks
        "ilha_id" => 30,
        "day_of_week" => 3,
        "start_hour" => "02:00:00"
    ],
    [
        "id" => nome_ilha(38), // south grave
        "ilha_id" => 38,
        "day_of_week" => 3,
        "start_hour" => "11:00:00"
    ],
    [
        "id" => nome_ilha(31),// little garden
        "ilha_id" => 31,
        "day_of_week" => 3,
        "start_hour" => "22:00:00"
    ],
    [
        "id" => nome_ilha(32), // drum
        "ilha_id" => 32,
        "day_of_week" => 4,
        "start_hour" => "19:00:00"
    ],
    [
        "id" => nome_ilha(36), // nanohana
        "ilha_id" => 36,
        "day_of_week" => 5,
        "start_hour" => "03:00:00"
    ],
    [
        "id" => nome_ilha(47), // laugh tale
        "ilha_id" => 47,
        "day_of_week" => 2,
        "start_hour" => "15:00:00"
    ],
    [
        "id" => nome_ilha(39), // long ring
        "ilha_id" => 39,
        "day_of_week" => 6,
        "start_hour" => "17:00:00"
    ],
    [
        "id" => nome_ilha(42), // sabaody
        "ilha_id" => 42,
        "day_of_week" => 6,
        "start_hour" => "19:00:00"
    ],
    [
        "id" => nome_ilha(29), // farol
        "ilha_id" => 29,
        "day_of_week" => 6,
        "start_hour" => "21:00:00"
    ],
];

function get_current_disputa_ilha()
{
    global $disputas_ilhas;

    $disputas_hoje = array_filter($disputas_ilhas, function ($disputa) {
        return $disputa["day_of_week"] == date("w", time());
    });

    $now = time();
    $current_day_of_month = date("j", $now);
    $current_month = date("n", $now);
    $current_year = date("Y", $now);

    foreach ($disputas_hoje as $disputa_ativa) {
        $start_hour = explode(":", $disputa_ativa["start_hour"]);

        $start = mktime(
            intval($start_hour[0]),
            intval($start_hour[1]),
            intval($start_hour[2]),
            $current_month,
            $current_day_of_month,
            $current_year
        );
        $end = strtotime("+2 hours", $start);

        if ($start <= $now && $end >= $now) {
            return array_merge(
                [
                    "start" => $start,
                    "end" => $end,
                ],
                $disputa_ativa
            );
        }
    }
    return null;
}


function cron_atualiza_disputa_ilha()
{
    global $connection;

    $disputa = get_current_disputa_ilha();


    if ($disputa) {
        // atualiza status da disputa
        $incursao_end = strtotime("+1 hours", $disputa["start"]);
        $disputa_db = $connection->run(
            "SELECT * FROM tb_ilha_disputa WHERE ilha = ?",
            "i", [$disputa["ilha_id"]]
        );

        if (time() < $incursao_end) {
            // inicia disputa
            if (! $disputa_db->count()) {
                $connection->run(
                    "DELETE FROM tb_ilha_disputa_progresso WHERE ilha = ?",
                    "i", [$disputa["ilha_id"]]
                );
                $connection->run(
                    "INSERT INTO tb_ilha_disputa (ilha, fim) VALUE (?, ?)",
                    "ii", [$disputa["ilha_id"], $disputa["end"]]
                );
            }
        } else {
            // finaliza incursao
            if ($disputa_db->count()) {
                $disputa_db = $disputa_db->fetch_array();
                if (! $disputa_db["vencedor_id"]) {
                    $connection->run("DELETE FROM tb_ilha_disputa WHERE vencedor_id IS NULL");
                }
            }
        }

    } else {
        // precisa finalizar a disputa ativa se houver
        $result = $connection->run(
            "SELECT * FROM tb_ilha_disputa WHERE vencedor_pronto = 1 AND dono_pronto = 0"
        );

        while ($disputa_db = $result->fetch_array()) {
            $connection->run("UPDATE tb_mapa SET ilha_dono = ? WHERE ilha = ?",
                "ii", [$disputa_db["vencedor_id"], $disputa_db["ilha"]]);
        }

        $connection->run("DELETE FROM tb_ilha_disputa WHERE vencedor_pronto <> 1 OR dono_pronto <> 1");
    }
}

cron_atualiza_disputa_ilha();
