<?php
function is_coliseu_aberto() {
    $coliseu = get_value_varchar_variavel_global('VARIAVEL_COLISEU');

    return $coliseu == 'aberto';
    // return (date("D", time()) == "Sun" OR date("D", time()) == "Sat");
}

function calc_lvl_tolerancia_coliseu($momento_inicio_fila) {
    return floor((time() - strtotime($momento_inicio_fila)) / 10) * 5;
}

function check_timeout_desafio() {
    global $userDetails;
    global $connection;

    if ($userDetails->fila_coliseu["desafio"]) {
        $desafiador = $connection->run("SELECT * FROM tb_coliseu_fila WHERE id = ?",
            "i", array($userDetails->fila_coliseu["desafio"]));

        if (!$desafiador->count() || $desafiador->fetch_array()["desafio"] != $userDetails->tripulacao["id"]) {
            $connection->run("UPDATE tb_coliseu_fila SET desafio = NULL, desafio_momento = NULL, desafio_aceito = 0 WHERE id = ?",
                "i", array($userDetails->tripulacao["id"]));
        } else {
            if (strtotime($userDetails->fila_coliseu["desafio_momento"]) < time() - 120) {
                $connection->run("UPDATE tb_coliseu_fila SET desafio = NULL, desafio_momento = NULL, desafio_aceito = 0 WHERE id = ?",
                    "i", array($userDetails->tripulacao["id"]));

                $connection->run("DELETE FROM tb_coliseu_fila WHERE id = ?",
                    "i", array($userDetails->fila_coliseu["desafio"]));
            }
        }
    }
}

function attack_coliseu() {
    global $userDetails;
    global $connection;

    if (!$userDetails->fila_coliseu || $userDetails->fila_coliseu["pausado"] || $userDetails->fila_coliseu["desafio"]) {
        return false;
    }

    $tolerancia = calc_lvl_tolerancia_coliseu($userDetails->fila_coliseu["momento"]);

    $fila = $connection->run(
        "SELECT f.*
        FROM tb_coliseu_fila f 
        LEFT JOIN tb_combate_log l ON ((l.id_1 = ? AND l.id_2 = f.id) OR (l.id_1 = f.id AND l.id_2 = ?)) AND (l.tipo = ? OR l.tipo = ? OR l.tipo = ?) AND TIMEDIFF(CURRENT_TIMESTAMP(), l.fim) < '02:00:00'
        WHERE f.id <> ? AND f.pausado = 0 AND f.desafio IS NULL AND l.horario IS NULL ORDER BY f.momento",
        "iiiiii", array($userDetails->tripulacao["id"], $userDetails->tripulacao["id"], TIPO_COLISEU, TIPO_LOCALIZADOR_CASUAL, TIPO_LOCALIZADOR_COMPETITIVO, $userDetails->tripulacao["id"])
    )->fetch_all_array();

    foreach ($fila as $adversario) {
        $tolerancia_adversario = calc_lvl_tolerancia_coliseu($adversario["momento"]);
        if ($adversario["lvl"] <= $userDetails->fila_coliseu["lvl"] + $tolerancia
            && $userDetails->fila_coliseu["lvl"] <= $adversario["lvl"] + $tolerancia_adversario
            && !$adversario["pausado"]
            && !$adversario["desafio"]
        ) {
            if ($userDetails->fila_coliseu["busca_competitivo"] && $adversario["busca_competitivo"]) {
                $connection->run("UPDATE tb_coliseu_fila SET desafio = ?, desafio_aceito = 0, desafio_momento = current_timestamp, desafio_tipo = ? WHERE id = ?",
                    "iii", array($userDetails->tripulacao["id"], TIPO_LOCALIZADOR_COMPETITIVO, $adversario["id"]));
                $connection->run("UPDATE tb_coliseu_fila SET desafio = ?, desafio_aceito = 0, desafio_momento = current_timestamp, desafio_tipo = ? WHERE id = ?",
                    "iii", array($adversario["id"], TIPO_LOCALIZADOR_COMPETITIVO, $userDetails->tripulacao["id"]));
                return true;
            } else if ($userDetails->fila_coliseu["busca_coliseu"] && $adversario["busca_coliseu"]) {
                $connection->run("UPDATE tb_coliseu_fila SET desafio = ?, desafio_aceito = 0, desafio_momento = current_timestamp, desafio_tipo = ? WHERE id = ?",
                    "iii", array($userDetails->tripulacao["id"], TIPO_COLISEU, $adversario["id"]));
                $connection->run("UPDATE tb_coliseu_fila SET desafio = ?, desafio_aceito = 0, desafio_momento = current_timestamp, desafio_tipo = ? WHERE id = ?",
                    "iii", array($adversario["id"], TIPO_COLISEU, $userDetails->tripulacao["id"]));
                return true;
            } else if ($userDetails->fila_coliseu["busca_casual"] && $adversario["busca_casual"]) {
                $connection->run("UPDATE tb_coliseu_fila SET desafio = ?, desafio_aceito = 0, desafio_momento = current_timestamp, desafio_tipo = ? WHERE id = ?",
                    "iii", array($userDetails->tripulacao["id"], TIPO_LOCALIZADOR_CASUAL, $adversario["id"]));
                $connection->run("UPDATE tb_coliseu_fila SET desafio = ?, desafio_aceito = 0, desafio_momento = current_timestamp, desafio_tipo = ? WHERE id = ?",
                    "iii", array($adversario["id"], TIPO_LOCALIZADOR_CASUAL, $userDetails->tripulacao["id"]));
                return true;
            }
        }
    }

    return false;
}

function get_projecao_atributos_coliseu($pers) {
    $pts = 0;
    $ao_menos_um_diff_zero = false;
    for ($i = 1; $i <= 8; $i++) {
        $pts += ($pers[nome_atributo_tabela($i)] - 1);
        if ($pers[nome_atributo_tabela($i)] > 1) {
            $ao_menos_um_diff_zero = true;
        }
    }
    $adicoes = array();
    $pontos = $pers["akuma"] ? 265 : 355;
    $restante = $pontos - (($pers["lvl"] - 1) * 4 + 69) + $pers["pts"];
    if ($ao_menos_um_diff_zero) {
        for ($i = 1; $i <= 8; $i++) {
            $adicoes[nome_atributo_tabela($i)] = round($pers[nome_atributo_tabela($i)] / $pts * $restante);
        }
    } else {
        for ($i = 1; $i <= 8; $i++) {
            $adicoes[nome_atributo_tabela($i)] = round(1 / 8 * $restante);
        }
    }
    return $adicoes;
}

function nivela_personagens_coliseu($personagens) {
    foreach ($personagens as $index => $pers) {
        if ($pers["lvl"] < 50) {
            $adicoes = get_projecao_atributos_coliseu($pers);

            for ($i = 1; $i <= 8; $i++) {
                $personagens[$index][nome_atributo_tabela($i)] += $adicoes[nome_atributo_tabela($i)];
            }

            $personagens[$index]["hp_max"] = 7400 + $personagens[$index]["vit"] * 30;
            $personagens[$index]["mp_max"] = 443 + $personagens[$index]["vit"] * 7;
        }

        $personagens[$index]["hp"] = $personagens[$index]["hp_max"];
        $personagens[$index]["mp"] = $personagens[$index]["mp_max"];
    }

    return $personagens;
}