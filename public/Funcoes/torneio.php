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

    if (! count($inscritos)) {
        $connection->run("UPDATE tb_torneio SET `status` = ? WHERE id = ?",
            "ii", [TORNEIO_STATUS_FINALIZADO, $torneio["id"]]);
    } else {
        $connection->run("UPDATE tb_torneio SET `status` = ? WHERE id = ?",
            "ii", [TORNEIO_STATUS_ANDAMENTO, $torneio["id"]]);
        // TODO cria as chaves
    }
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
             WHERE ti.torneio_id = ?",
            "i", [$torneio["id"]]
        )
        ->fetch_all_array();
}
