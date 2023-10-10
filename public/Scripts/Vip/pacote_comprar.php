<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    echo ("#Você precisa estar logado!");
    exit();
}

function comprarPacoteVip() {
    global $userDetails, $connection, $protector;

    // Verifique apenas o preço do gol de cada vantagem
    $protector->need_gold(PRECO_GOLD_PACOTE);

    // Defina o tempo de duração
    $tempo_base = $userDetails->vip["pacote"] ? $userDetails->vip["pacote_duracao"] : atual_segundo();
    $tempo_pacote = $tempo_base + 30 * 24 * 60 * 60;

    // Verifique se o usuário já possui a vantagem "Táticas"
    if ($userDetails->vip["tatic"]) {
        // Se possui, adicione 30 dias de duração
        $tempo_pacote += 30 * 24 * 60 * 60;
    } else {
        // Se não possui, adicione a vantagem "Táticas" com 30 dias de duração
        $connection->run("UPDATE tb_vip SET tatic = 1, tatic_duracao = ? WHERE id = ?",
            "ii", array($tempo_pacote, $userDetails->tripulacao["id"]));
    }

    // Verifique se o usuário já possui a vantagem "Coup de Burst"
    if ($userDetails->vip["coup_de_burst"]) {
        // Se possui, adicione 30 dias de duração
        $tempo_pacote += 30 * 24 * 60 * 60;
    } else {
        // Se não possui, adicione a vantagem "Coup de Burst" com 30 dias de duração
        $connection->run("UPDATE tb_vip SET coup_de_burst = 1, coup_de_burst_duracao = ? WHERE id = ?",
            "ii", array($tempo_pacote, $userDetails->tripulacao["id"]));
    }

    // Verifique se o usuário já possui a vantagem "Luneta"
    if ($userDetails->vip["luneta"]) {
        // Se possui, adicione 30 dias de duração
        $tempo_pacote += 30 * 24 * 60 * 60;
    } else {
        // Se não possui, adicione a vantagem "Luneta" com 30 dias de duração
        $connection->run("UPDATE tb_vip SET luneta = 1, luneta_duracao = ? WHERE id = ?",
            "ii", array($tempo_pacote, $userDetails->tripulacao["id"]));
    }
    
    /*Comprar coup de burst
    $connection->run("UPDATE tb_vip SET coup_de_burst = 5, coup_de_burst_duracao = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

    // Comprar luneta
    $connection->run("UPDATE tb_vip SET luneta= '1', luneta_duracao = ? WHERE id = ?", 'ii', [
        $tempo,
        $usuario['id']
    ]);

    // Comprar taticas 
    $query = "UPDATE tb_vip SET tatic='1', tatic_duracao='$tempo' WHERE id='" . $usuario["id"] . "'";
    mysql_query($query) or die("Não foi possível cadastrar o item");*/

    // Reduzir o valor do pacote vip
    $userDetails->reduz_gold(PRECO_GOLD_PACOTE, "pacote_vip");

    echo ("- Parabébns!<br>Você acabou de comprar o Pacote De Vantagens!");

}

comprarPacoteVip();
?> 