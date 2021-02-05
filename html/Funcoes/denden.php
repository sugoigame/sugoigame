<?php
function has_mensagem() {
    global $userDetails;
    global $connection;
    $has_msg = !$connection->run(
        "SELECT * FROM tb_mensagens WHERE destinatario = ? AND lido='0'",
        "i", $userDetails->tripulacao["id"]
    )->count();

    if ($has_msg) {
        $msgs = $connection->run(
            "SELECT lidas.data_leitura AS lido, glob.data AS data FROM tb_mensagens_globais glob 
            LEFT JOIN tb_mensagens_globais_lidas lidas ON glob.id = lidas.mensagem_id AND lidas.tripulacao_id = ?
            ORDER BY glob.id DESC LIMIT 5",
            "i", $userDetails->tripulacao["id"]
        )->fetch_all_array();
        foreach ($msgs as $msg) {
            if (!$msg["lido"] && $msg["data"] > $userDetails->tripulacao["cadastro"]) {
                $has_msg = 0;
                break;
            }
        }
    }

    return $has_msg ? 1 : 0;
}