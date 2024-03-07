<?php
function attack_torneio()
{
    global $userDetails;
    global $connection;

    $participante = $connection->run(
        "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1 AND na_fila = 1",
        "i", array($userDetails->tripulacao["id"])
    );

    if (! $participante->count()) {
        return false;
    }

    $participante = $participante->fetch_array();

    $total_participantes = $connection->run("SELECT count(*) AS total FROM tb_torneio_inscricao WHERE confirmacao = 1")->fetch_array()["total"];
    $total_participantes_rodada = $connection->run("SELECT count(*) AS total FROM tb_torneio_inscricao WHERE confirmacao = 1 AND rodada >= ?",
        "i", array($participante["rodada"]))->fetch_array()["total"];

    $oponentes = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE confirmacao = 1 AND rodada = ? AND tripulacao_id <> ?",
        "ii", array($participante["rodada"], $userDetails->tripulacao["id"]))->fetch_all_array();

    $oponentes_pontos = [];
    foreach ($oponentes as $oponente) {
        if (! isset($oponentes_pontos[$oponente["pontos"]])) {
            $oponentes_pontos[$oponente["pontos"]] = [];
        }
        $oponentes_pontos[$oponente["pontos"]][] = $oponente;
    }

    $tolerance = 0;

    do {
        if (isset($oponentes_pontos[$participante["pontos"] + $tolerance])) {
            foreach ($oponentes_pontos[$participante["pontos"] + $tolerance] as $oponente) {
                if ($oponente["na_fila"]) {
                    return $oponente["tripulacao_id"];
                }
            }
        }
        if (isset($oponentes_pontos[$participante["pontos"] - $tolerance])) {
            foreach ($oponentes_pontos[$participante["pontos"] - $tolerance] as $oponente) {
                if ($oponente["na_fila"]) {
                    return $oponente["tripulacao_id"];
                }
            }
        }
        if (isset($oponentes_pontos[$participante["pontos"] + $tolerance])
            || isset($oponentes_pontos[$participante["pontos"] - $tolerance])) {
            return false;
        }
        $tolerance++;
    } while ($total_participantes_rodada == $total_participantes && $tolerance < 5);

    return false;
}

function inicia_torneio($torneio, $torneio_db)
{
    global $connection;

    $result = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE torneio_id = ?", "i", [$torneio["id"]]);

    if (! $result->count()) {
        $connection->run("UPDATE tb_torneio SET `status` = ? WHERE id = ?",
            "ii", [TORNEIO_STATUS_FINALIZADO, $torneio["id"]]);
    } else {
        $connection->run("UPDATE tb_torneio SET `status` = ? WHERE id = ?",
            "ii", [TORNEIO_STATUS_ANDAMENTO, $torneio["id"]]);
        // TODO cria as chaves
    }
}
