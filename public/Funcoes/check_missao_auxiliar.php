<?php

function check_progress_missoes_realizadas($ilha, $amount)
{
    global $userDetails;
    global $connection;
    $missoes_concluidas = $connection->run(
        "SELECT count(missaoc.cod_missao) AS total
        FROM tb_missoes_concluidas missaoc
        INNER JOIN tb_ilha_missoes imissao ON missaoc.cod_missao = imissao.cod_missao AND imissao.ilha = ?
        WHERE missaoc.id = ?",
        "ii", array($ilha, $userDetails->tripulacao["id"])
    )->fetch_array()["total"];

    if ($amount == -1) {
        $amount = $connection->run(
            "SELECT count(missao.cod_missao) AS total
            FROM tb_ilha_missoes missao
            WHERE missao.ilha = ?",
            "i", array($ilha)
        )->fetch_array()["total"];
    }

    return $missoes_concluidas >= $amount;
}

function check_progress_personagens_recuperados()
{
    global $userDetails;
    foreach ($userDetails->personagens as $pers) {
        if ($pers["hp"] < $pers["hp_max"]) {
            return false;
        }
    }

    return true;
}

function check_progress_personagem_com_classe($pers)
{
    return $pers["classe"] != 0;
}

function check_progress_personagem_com_atributos($pers)
{
    for ($i = 1; $i <= 8; $i++) {
        if ($pers[nome_atributo_tabela($i)] > 1) {
            return true;
        }
    }
    return false;
}
function check_progress_personagem_com_habilidade($pers_cod)
{
    global $connection;
    $missoes_concluidas = $connection->run(
        "SELECT count(ps.cod_skil) AS total
        FROM tb_personagens_skil ps
        WHERE ps.cod = ?",
        "i", array($pers_cod)
    )->fetch_array()["total"];

    return $missoes_concluidas > 1;
}

function check_progress_barco_comprado()
{
    global $userDetails;
    return ! ! $userDetails->navio;
}

function check_progress_bandeira_trocada()
{
    global $userDetails;
    return $userDetails->tripulacao["bandeira"] != '010113046758010128123542010115204020';
}
function check_progress_tripulantes_recrutados($amount)
{
    global $userDetails;
    return count($userDetails->personagens) >= $amount;
}

function check_progress_chefe_ilha_derrotado($ilha)
{
    global $connection;
    global $userDetails;
    return $connection->run(
        "SELECT * FROM tb_missoes_chefe_ilha WHERE tripulacao_id = ? AND ilha_derrotado = ?",
        "ii", array($userDetails->tripulacao["id"], $ilha)
    )->count() > 0;
}

function check_progress_incursao_realizada($ilha)
{
    global $connection;
    global $userDetails;
    return $connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"]))->count() > 0;
}
function check_progress_pesquisa_iniciada()
{
    global $connection;
    global $userDetails;
    $count = $connection->run("SELECT * FROM tb_missoes_r mr WHERE mr.id = ?",
        "i", array($userDetails->tripulacao["id"]))->count() +
        $connection->run("SELECT * FROM tb_missoes_r_dia mr WHERE mr.id = ?",
            "i", array($userDetails->tripulacao["id"]))->count();
    return $count > 0;
}

function check_progress_personagem_com_profissao($pers)
{
    return $pers["profissao"] != 0;
}

function check_progress_comida_comprada()
{
    global $connection;
    global $userDetails;
    return $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?",
        "ii", array($userDetails->tripulacao["id"], TIPO_ITEM_COMIDA))->count() > 0;
}

function check_progress_criatura_derrotada()
{
    global $connection;
    global $userDetails;
    $defeated = $connection->run("SELECT count(*) AS total FROM tb_pve WHERE id = ?", "i", $userDetails->tripulacao["id"])
        ->fetch_array()["total"];

    return $defeated > 0;
}

function check_progress_in_ilha($ilha)
{
    global $userDetails;
    return $userDetails->ilha["ilha"] == $ilha;
}
