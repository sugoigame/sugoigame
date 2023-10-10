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
    $tempo = time() + 30 * 24 * 60 * 60;

    // Comprar coup de burst
    $connection->run("UPDATE tb_vip SET coup_de_burst = 5, coup_de_burst_duracao = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

    // Comprar luneta
    $connection->run("UPDATE tb_vip SET luneta= '1', luneta_duracao = ? WHERE id = ?", 'ii', [
        $tempo,
        $usuario['id']
    ]);

    // Comprar taticas 
    $query = "UPDATE tb_vip SET tatic='1', tatic_duracao='$tempo' WHERE id='" . $usuario["id"] . "'";
    mysql_query($query) or die("Não foi possível cadastrar o item");

    // Reduzir o valor do pacote vip
    $userDetails->reduz_gold(PRECO_GOLD_PACOTE, "pacote_vip");

    echo ("- Parabébns!<br>Você acabou de comprar o Pacote De Vantagens!");

}

comprarPacoteVip();
?> 