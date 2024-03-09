<?php

function get_current_torneio_poneglyph()
{
    $duracao = "+220 minutes";

    $inicio = mktime(0, 0, 0, 3, 1, 2024);
    $end = strtotime($duracao, $inicio);
    $now = time();
    $id = 1;
    while ($end < $now) {
        $inicio = strtotime($duracao, $inicio);
        $end = strtotime($duracao, $inicio);
        $id++;
    }

    // todo fix to 20 min
    $limite_inscricao = strtotime('+220 minutes', $inicio);

    return [
        "start" => $inicio,
        "end" => $end,
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
            inicia_torneio(array_merge($torneio_db, $torneio));
        }
    }
}

cron_atualiza_torneio_poneglyph();
