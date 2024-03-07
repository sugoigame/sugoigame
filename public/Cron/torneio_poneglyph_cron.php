<?php

function get_current_torneio_poneglyph()
{
    $inicio = mktime(0, 0, 0, 3, 1, 2024);
    $now = time();
    $id = 1;
    while ($inicio > $now) {
        $inicio = strtotime('+220 minutes', $inicio);
        $id++;
    }

    $fim = strtotime('+220 minutes', $inicio);

    $limite_inscricao = strtotime('+20 minutes', $inicio);

    return [
        "start" => $inicio,
        "end" => $fim,
        "limite_inscricao" => $limite_inscricao,
        "id" => $id,
    ];
}

function cron_atualiza_torneio_poneglyph()
{
    global $connection;

    $torneio = get_current_torneio_poneglyph();

    $result = $connection->run("SELECT * FROM tb_torneio WHERE id = ?", "i", [$torneio["id"]]);

    if (! $result->count()) {
        $connection->run("UPDATE tb_torneio SET `status` = ?", "i", [TORNEIO_STATUS_FINALIZADO]);

        $connection->run("INSERT INTO tb_torneio (id, `status`, inicio, limite_inscricao, limite_conclusao, coordenadas)
        VALUES (?,?,from_unixtime(?),from_unixtime(?),from_unixtime(?),?)",
            "iiiiis", [
                $torneio["id"],
                TORNEIO_STATUS_AGUARDANDO,
                $torneio["start"],
                $torneio["limite_inscricao"],
                $torneio["end"],
                json_encode([
                    1 => get_random_coord_navegavel(1),
                    2 => get_random_coord_navegavel(2),
                    3 => get_random_coord_navegavel(3),
                    4 => get_random_coord_navegavel(4),
                    5 => get_random_coord_navegavel(5),
                    6 => get_random_coord_navegavel(6),
                ])
            ]);
    } else {
        $torneio_db = $result->fetch_array();

        if ($torneio_db["status"] == TORNEIO_STATUS_AGUARDANDO && time() > $torneio["limite_inscricao"]) {
            inicia_torneio($torneio, $torneio_db);
        }
    }
}

cron_atualiza_torneio_poneglyph();
