<?php
function attack_torneio()
{
    global $userDetails;
    global $connection;


    return false;
}

function inicia_torneio($torneio)
{
    global $connection;

    $inscritos = get_inscritos_torneio_poneglyph($torneio);
    $count_inscritos = count($inscritos);

    if (! $count_inscritos) {
        $connection->run("UPDATE tb_torneio SET `status` = ? WHERE id = ?",
            "ii", [TORNEIO_STATUS_FINALIZADO, $torneio["id"]]);
    } elseif ($count_inscritos == 1) {
        set_vencedor_torneio($torneio, $inscritos[0]);
    } else {
        $connection->run("UPDATE tb_torneio SET `status` = ? WHERE id = ?",
            "ii", [TORNEIO_STATUS_ANDAMENTO, $torneio["id"]]);

        $id_final = $torneio["id"] * 10;
        $final = [
            "id" => $id_final,
            "torneio_id" => $torneio["id"],
            "proxima_chave" => null,
            "tripulacao_1_id" => null,
            "tripulacao_2_id" => null,
        ];
        $semi_finais = [
            [
                "id" => $id_final + 1,
                "torneio_id" => $torneio["id"],
                "proxima_chave" => $final["id"],
                "tripulacao_1_id" => null,
                "tripulacao_2_id" => null,
            ], [
                "id" => $id_final + 2,
                "torneio_id" => $torneio["id"],
                "proxima_chave" => $final["id"],
                "tripulacao_1_id" => null,
                "tripulacao_2_id" => null,
            ]
        ];
        $quartas = [
            [
                "id" => $id_final + 3,
                "torneio_id" => $torneio["id"],
                "proxima_chave" => $semi_finais[0]["id"],
                "tripulacao_1_id" => null,
                "tripulacao_2_id" => null,
            ], [
                "id" => $id_final + 4,
                "torneio_id" => $torneio["id"],
                "proxima_chave" => $semi_finais[0]["id"],
                "tripulacao_1_id" => null,
                "tripulacao_2_id" => null,
            ], [
                "id" => $id_final + 5,
                "torneio_id" => $torneio["id"],
                "proxima_chave" => $semi_finais[1]["id"],
                "tripulacao_1_id" => null,
                "tripulacao_2_id" => null,
            ], [
                "id" => $id_final + 6,
                "torneio_id" => $torneio["id"],
                "proxima_chave" => $semi_finais[1]["id"],
                "tripulacao_1_id" => null,
                "tripulacao_2_id" => null,
            ]
        ];

        $limite_inicio_1_luta = '+5 minutes';
        $limite_fim_1_luta = '+15 minutes';

        $limite_inicio_2_luta = '+20 minutes';
        $limite_fim_2_luta = '+30 minutes';

        $limite_inicio_3_luta = '+35 minutes';
        $limite_fim_3_luta = '+45 minutes';

        if ($count_inscritos == 2) {
            $final["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $final["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $final["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);
        } elseif ($count_inscritos == 3) {
            $semi_finais[0]["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $semi_finais[0]["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $semi_finais[0]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $semi_finais[0]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $final["tripulacao_2_id"] = $inscritos[2]["tripulacao_id"];
            $final["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);
        } elseif ($count_inscritos == 4) {
            $semi_finais[0]["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $semi_finais[0]["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $semi_finais[0]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $semi_finais[0]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $semi_finais[1]["tripulacao_1_id"] = $inscritos[2]["tripulacao_id"];
            $semi_finais[1]["tripulacao_2_id"] = $inscritos[3]["tripulacao_id"];
            $semi_finais[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $semi_finais[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $final["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);
        } elseif ($count_inscritos == 5) {
            $quartas[0]["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $quartas[0]["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $quartas[0]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[0]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $semi_finais[0]["tripulacao_2_id"] = $inscritos[4]["tripulacao_id"];
            $semi_finais[0]["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $semi_finais[0]["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);

            $semi_finais[1]["tripulacao_1_id"] = $inscritos[2]["tripulacao_id"];
            $semi_finais[1]["tripulacao_2_id"] = $inscritos[3]["tripulacao_id"];
            $semi_finais[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $semi_finais[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $final["limite_inicio"] = strtotime($limite_inicio_3_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_3_luta, $torneio["limite_inscricao"]);
        } elseif ($count_inscritos == 6) {
            $quartas[0]["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $quartas[0]["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $quartas[0]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[0]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $quartas[1]["tripulacao_1_id"] = $inscritos[4]["tripulacao_id"];
            $quartas[1]["tripulacao_2_id"] = $inscritos[5]["tripulacao_id"];
            $quartas[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $semi_finais[0]["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $semi_finais[0]["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);

            $semi_finais[1]["tripulacao_1_id"] = $inscritos[2]["tripulacao_id"];
            $semi_finais[1]["tripulacao_2_id"] = $inscritos[3]["tripulacao_id"];
            $semi_finais[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $semi_finais[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $final["limite_inicio"] = strtotime($limite_inicio_3_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_3_luta, $torneio["limite_inscricao"]);
        } elseif ($count_inscritos == 7) {
            $quartas[0]["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $quartas[0]["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $quartas[0]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[0]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $quartas[1]["tripulacao_1_id"] = $inscritos[4]["tripulacao_id"];
            $quartas[1]["tripulacao_2_id"] = $inscritos[5]["tripulacao_id"];
            $quartas[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $quartas[2]["tripulacao_1_id"] = $inscritos[2]["tripulacao_id"];
            $quartas[2]["tripulacao_2_id"] = $inscritos[3]["tripulacao_id"];
            $quartas[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $semi_finais[0]["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $semi_finais[0]["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);

            $semi_finais[1]["tripulacao_2_id"] = $inscritos[6]["tripulacao_id"];
            $semi_finais[1]["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $semi_finais[1]["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);

            $final["limite_inicio"] = strtotime($limite_inicio_3_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_3_luta, $torneio["limite_inscricao"]);
        } elseif ($count_inscritos == 8) {
            $quartas[0]["tripulacao_1_id"] = $inscritos[0]["tripulacao_id"];
            $quartas[0]["tripulacao_2_id"] = $inscritos[1]["tripulacao_id"];
            $quartas[0]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[0]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $quartas[1]["tripulacao_1_id"] = $inscritos[4]["tripulacao_id"];
            $quartas[1]["tripulacao_2_id"] = $inscritos[5]["tripulacao_id"];
            $quartas[1]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[1]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $quartas[2]["tripulacao_1_id"] = $inscritos[2]["tripulacao_id"];
            $quartas[2]["tripulacao_2_id"] = $inscritos[3]["tripulacao_id"];
            $quartas[2]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[2]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $quartas[3]["tripulacao_1_id"] = $inscritos[6]["tripulacao_id"];
            $quartas[3]["tripulacao_2_id"] = $inscritos[7]["tripulacao_id"];
            $quartas[3]["limite_inicio"] = strtotime($limite_inicio_1_luta, $torneio["limite_inscricao"]);
            $quartas[3]["limite_fim"] = strtotime($limite_fim_1_luta, $torneio["limite_inscricao"]);

            $semi_finais[0]["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $semi_finais[0]["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);

            $semi_finais[1]["limite_inicio"] = strtotime($limite_inicio_2_luta, $torneio["limite_inscricao"]);
            $semi_finais[1]["limite_fim"] = strtotime($limite_fim_2_luta, $torneio["limite_inscricao"]);

            $final["limite_inicio"] = strtotime($limite_inicio_3_luta, $torneio["limite_inscricao"]);
            $final["limite_fim"] = strtotime($limite_fim_3_luta, $torneio["limite_inscricao"]);
        }

        $chaves = array_merge([$final], $semi_finais, $quartas);

        $query = "INSERT INTO tb_torneio_chave
        (id, tripulacao_1_id, tripulacao_2_id, limite_inicio, limite_fim, proxima_chave, torneio_id)
        VALUES ";

        $query_parts = [];
        foreach ($chaves as $chave) {
            $query_parts[] = "(" .
                $chave["id"] . "," .
                ($chave["tripulacao_1_id"] ? $chave["tripulacao_1_id"] : "null") . "," .
                ($chave["tripulacao_2_id"] ? $chave["tripulacao_2_id"] : "null") . "," .
                "from_unixtime(" . $chave["limite_inicio"] . ")," .
                "from_unixtime(" . $chave["limite_fim"] . ")," .
                ($chave["proxima_chave"] ? $chave["proxima_chave"] : "null") . "," .
                $chave["torneio_id"] .
                ")";
        }

        $connection->run($query . implode($query_parts, ","));
    }
}

function set_vencedor_torneio($torneio, $inscrito)
{
    global $connection;

    $connection->run(
        "UPDATE tb_torneio SET `status` = ?, vencedor = ? WHERE id = ?",
        "iii", [TORNEIO_STATUS_FINALIZADO, $inscrito["tripulacao_id"], $torneio["id"]]
    );

    $connection->run(
        "UPDATE tb_usuarios SET reputao_mensal = reputacao_mensal + 1 WHERE id = ?",
        "i", $inscrito["tripulacao_id"]
    );
}

function get_current_torneio_poneglyph_completo()
{
    global $connection;
    $torneio = get_current_torneio_poneglyph();
    $result = $connection->run("SELECT * FROM tb_torneio WHERE id = ?", "i", [$torneio["id"]]);
    return array_merge($result->count() ? $result->fetch_array() : [], $torneio);
}

function get_inscritos_torneio_poneglyph($torneio)
{
    global $connection;
    return $connection
        ->run(
            "SELECT * FROM tb_torneio_inscricao ti
            INNER JOIN tb_usuarios u ON ti.tripulacao_id = u.id
            WHERE ti.torneio_id = ?
            ORDER BY data_inscricao",
            "i", [$torneio["id"]]
        )
        ->fetch_all_array();
}
