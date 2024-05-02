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

    $limite_inscricao = strtotime('+20 minutes', $inicio);

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
function cron_finaliza_chave_tripulacao_nao_pronta()
{
    global $connection;

    $torneio = get_current_torneio_poneglyph();

    $result = $connection->run(
        "SELECT * FROM tb_torneio_chave
        WHERE torneio_id = ?
        AND limite_inicio < CURRENT_TIMESTAMP()
        AND (em_andamento = 0 OR em_andamento is NULL)",
        "i", [$torneio["id"]]
    );

    while ($chave = $result->fetch_array()) {
        $vencedor = null;
        $perdedor = null;
        if (($chave["tripulacao_1_pronto"] && ! $chave["tripulacao_2_pronto"])
            || (! $chave["tripulacao_1_pronto"] && ! $chave["tripulacao_2_pronto"])) {
            $vencedor = $chave["tripulacao_1_id"];
            $perdedor = $chave["tripulacao_2_id"];
        } elseif (! $chave["tripulacao_1_pronto"] && $chave["tripulacao_2_pronto"]) {
            $vencedor = $chave["tripulacao_2_id"];
            $perdedor = $chave["tripulacao_1_id"];
        }

        if (! $chave["tripulacao_1_pronto"] || ! $chave["tripulacao_2_pronto"]) {
            finaliza_chave_torneio(
                ["vencedor_rep_mensal" => 0],
                TIPO_TORNEIO,
                ["id" => $vencedor],
                ["id" => $perdedor],
                [],
                []
            );
        }
    }
}
function cron_finaliza_chave_tempo_limite()
{
    global $connection;

    $torneio = get_current_torneio_poneglyph();

    $result = $connection->run(
        "SELECT * FROM tb_torneio_chave
        WHERE torneio_id = ?
        AND limite_fim < CURRENT_TIMESTAMP()
        AND (finalizada = 0 OR finalizada is NULL)",
        "i", [$torneio["id"]]
    );

    while ($chave = $result->fetch_array()) {
        if ($chave["tripulacao_1_id"] || $chave["tripulacao_2_id"]) {
            $personagens_combate_1 = get_personagens_combate($chave["tripulacao_1_id"]);
            $personagens_combate_2 = get_personagens_combate($chave["tripulacao_2_id"]);

            $personagens_1_vivos = filter_personagens_vivos($personagens_combate_1);
            $personagens_2_vivos = filter_personagens_vivos($personagens_combate_2);

            $perdedor_posicao = count($personagens_1_vivos) >= count($personagens_2_vivos) ? "2" : "1";

            $connection->run(
                "UPDATE tb_combate_personagens SET hp = 0, desistencia = 1 WHERE id = ? AND hp > 0",
                "i", [$chave["tripulacao_" . $perdedor_posicao . "_id"]]
            );
        }
    }
}

cron_atualiza_torneio_poneglyph();
cron_finaliza_chave_tripulacao_nao_pronta();
cron_finaliza_chave_tempo_limite();
