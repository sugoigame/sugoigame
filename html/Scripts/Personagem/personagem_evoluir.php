<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers_cod   = $protector->get_number_or_exit("cod");
$personagem = $userDetails->get_pers_by_cod($pers_cod);

if (!$personagem) {
    $protector->exit_error("Personagem não encontrado");
}

if ($personagem["xp"] < $personagem["xp_max"]) {
    $protector->exit_error("Esse personagem nao tem experiência suficiente para evoluir.");
}

if ($personagem["lvl"] >= 50) {
    $protector->exit_error("Esse personagem já atingiu o nível máximo.");
}

$lvl    = $personagem["lvl"] + 1;
$xp_max = formulaExp($lvl);
$xp     = $personagem["xp"] - $personagem["xp_max"];

$connection->run("UPDATE tb_personagens 
	SET xp          = ?,
        xp_max      = ?,
        lvl         = ?,
        pts         = pts + ?, 
        hp_max      = hp_max + 100,
        hp          = hp_max,
        mp_max      = mp_max + 7,
        mp          = mp_max,
        fama_ameaca = fama_ameaca + 20000
	WHERE id = ? AND cod = ?", 'iiiiii', [
        $xp,
        $xp_max,
        $lvl,
        PONTOS_POR_NIVEL,
        $userDetails->tripulacao["id"],
        $personagem["cod"]
    ]);

$max_lvls_for_reputacao = 50 * 15;
$lvls_tripulacao        = $connection->run("SELECT SUM(lvl) AS lvls FROM tb_personagens WHERE id = ?", "i", $userDetails->tripulacao["id"])->fetch_array()["lvls"];
if ($lvls_tripulacao <= $max_lvls_for_reputacao) {
    $connection->run("UPDATE tb_usuarios SET reputacao = reputacao + 5, reputacao_mensal = reputacao_mensal + 5 WHERE id = ?", "i", $userDetails->tripulacao["id"]);
}

if ($personagem["lvl"] == 1 AND $userDetails->tripulacao["cod_personagem"] == $personagem["cod"]) {
    $assunto    = "Nível 2 Alcançado!";
    $texto      = "Parabéns!
		Este é o serviço de mensagens Den Den Mushi, use-o para enviar mensagens aos seus amigos!
		Agora que você alcançou o nível 2, continue juntando dinheiro fazendo missões para comprar um barco no estaleiro,
		e recrutar mais tripulantes para sua tripulação. O primeiro barco só cabe 3 tripulantes, nas próximas ilhas você
		encontrará barcos maiores a venda. Visite a escola de profissões para aprender a profissão de Cartógrafo e ter 
		acesso aos mapas do jogo. Acesse o menu Ajuda para encontrar guias e tutoriais. Boa sorte!";
    $hora       = "às " . date("H:i", time()) . " do dia " . date("d/m/Y", time());

    $connection->run("INSERT INTO tb_mensagens (remetente,destinatario,assunto,texto,hora) VALUES (?,?,?,?,?)", 'iisss', [
        $userDetails->tripulacao["id"],
        $userDetails->tripulacao["id"],
        $assunto,
        $texto,
        $hora
    ]);
}

$response->send_conquista_pers($personagem, $personagem["nome"] . " alcançou o nível $lvl!");
