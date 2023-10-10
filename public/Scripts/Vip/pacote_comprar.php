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
    
        // Obtenha a duração atual de cada vantagem
        $tempo_taticas = $userDetails->vip["tatic_duracao"];
        $tempo_coup = $userDetails->vip["coup_de_burst_duracao"];
        $tempo_luneta = $userDetails->vip["luneta_duracao"];
    
        // Defina o tempo base para o "PACOTE VIP"
        $tempo_base = max($tempo_taticas, $tempo_coup, $tempo_luneta);
        $tempo_pacote = $tempo_base + 30 * 24 * 60 * 60;
    
        // Atualize a vantagem "Táticas" com a nova duração
        $tempo_base = $userDetails->vip["tatic"] ? $userDetails->vip["tatic_duracao"] : atual_segundo();
        $tempo = $tempo_base + 2592000;
        $connection->run("UPDATE tb_vip SET tatic = 1, tatic_duracao = ? WHERE id = ?",
            "ii", array($tempo, $userDetails->tripulacao["id"]));
    
        // Atualize a vantagem "Coup de Burst" com a nova duração
        $tempo_base = $userDetails->vip["coup_de_burst_duracao"] > atual_segundo() ? $userDetails->vip["coup_de_burst_duracao"] : atual_segundo();
        $tempo = $tempo_base + 30 * 24 * 60 * 60;
        $connection->run("UPDATE tb_vip SET coup_de_burst = 5, coup_de_burst_duracao = ? WHERE id = ?",
            "ii", array($tempo, $userDetails->tripulacao["id"]));
    
        // Atualize a vantagem "Luneta" com a nova duração
        $tempoBase  = $userDetails->vip["luneta"] ? $userDetails->vip["luneta_duracao"] : atual_segundo();
        $tempo      = $tempoBase + 30 * 24 * 60 * 60; //(30 * 86400); // 86400 = 1 dia
        $connection->run("UPDATE tb_vip SET luneta = '1', luneta_duracao = ? WHERE id = ?", 'ii', [
            $tempo,
            $usuario['id']
        ]);
    
        // Reduzir o valor do pacote vip
        $userDetails->reduz_gold(PRECO_GOLD_PACOTE, "pacote_vip");
    
        echo ("- Parabébns!<br>Você acabou de comprar o Pacote De Vantagens!");
    }
    

comprarPacoteVip();
?> 