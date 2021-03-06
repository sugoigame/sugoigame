<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();

$alvo = $protector->get_number_or_exit("id");
$tipo = $protector->get_enum_or_exit("tipo", array(TIPO_ATAQUE, TIPO_SAQUE, TIPO_AMIGAVEL, TIPO_COLISEU, TIPO_CONTROLE_ILHA, TIPO_LOCALIZADOR_CASUAL, TIPO_LOCALIZADOR_COMPETITIVO, TIPO_TORNEIO));


// valida ataque e saque
if ($tipo == TIPO_ATAQUE || $tipo == TIPO_SAQUE) {
    $protector->must_be_out_of_ilha();

    if ($userDetails->capitao["lvl"] < 10) {
        $protector->exit_error("É necessário ter o capitão no nível 10 para iniciar um combate PvP");
    }
}

// remove desafio
if ($tipo == TIPO_AMIGAVEL) {
    $result = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ?", "i", $userDetails->tripulacao["id"]);
    if (!$result->count()) {
        $protector->exit_error("Você não foi desafiado por esse jogador.");
    }

    $connection->run("DELETE FROM tb_combate_desafio WHERE desafiado = ?", "i", $userDetails->tripulacao["id"]);
}

// valida controle de ilha
if ($tipo == TIPO_CONTROLE_ILHA) {
    if ($userDetails->ilha["ilha_dono"] != $userDetails->tripulacao["id"] && $userDetails->ilha["ilha_dono"] != $alvo) {
        $protector->exit_error("Nenhum de voces e dono da ilha");
    }
    $disputa = $connection->run("SELECT * FROM tb_ilha_disputa d LEFT JOIN tb_usuarios u ON d.vencedor_id = u.id WHERE d.ilha = ?",
        "i", array($userDetails->ilha["ilha"]));

    if (!$disputa->count()) {
        $protector->exit_error("Essa ilha não está sob disputa");
    }

    $disputa = $disputa->fetch_array();

    if ($disputa["vencedor_id"] != $userDetails->tripulacao["id"] && $disputa["vencedor_id"] != $alvo) {
        $protector->exit_error("Nenhum de voces foi vencedor da disputa pela ilha");
    }
}

// valida o coliseu
if ($tipo == TIPO_COLISEU || $tipo == TIPO_LOCALIZADOR_CASUAL || $tipo == TIPO_LOCALIZADOR_COMPETITIVO) {
    if (!$userDetails->fila_coliseu
        || !$userDetails->fila_coliseu["desafio"]
        || $alvo != $userDetails->fila_coliseu["desafio"]
        || !$userDetails->fila_coliseu["desafio_aceito"]
        || $userDetails->fila_coliseu["desafio_tipo"] != $tipo
    ) {
        $protector->exit_error("Você não foi desafiado.");
    }

    $adversario_fila = $connection->run("SELECT * FROM tb_coliseu_fila WHERE desafio = ?",
        "i", array($userDetails->tripulacao["id"]));

    if (!$adversario_fila->count()) {
        $protector->exit_error("Seu adversário não recebeu o desafio");
    }
    $adversario_fila = $adversario_fila->fetch_array();

    if (!$adversario_fila["desafio_aceito"]) {
        $protector->exit_error("Seu adversário ainda não aceitou o desafio");
    }

    $connection->run("DELETE FROM tb_coliseu_fila WHERE id = ? OR id = ?",
        "ii", array($userDetails->tripulacao["id"], $adversario_fila["id"]));
}

// carrega usuario alvo
$result = get_player_data_for_combat_check($alvo);
if (!$result->count()) {
    $protector->exit_error("Alvo não encontrado");
}
$usuario_alvo = $result->fetch_array();

// valida o torneio
if ($tipo == TIPO_TORNEIO) {
    $participante = $connection->run(
        "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1",
        "i", array($userDetails->tripulacao["id"])
    );
    if (!$participante->count()) {
        $protector->exit_error("Você não está participando do torneio");
    }
    $participante = $participante->fetch_array();
    if (!$participante["na_fila"]) {
        $protector->exit_error("Você não está na fila.");
    }

    $participante = $connection->run(
        "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1",
        "i", array($alvo)
    );
    if (!$participante->count()) {
        $protector->exit_error("Seu alvo não está participando do torneio");
    }
    $participante = $participante->fetch_array();
    if (!$participante["na_fila"]) {
        $protector->exit_error("Seu alvo não está na fila.");
    }

    $connection->run("UPDATE tb_torneio_inscricao 
        SET tempo_na_fila = IFNULL((unix_timestamp() - unix_timestamp(fila_entrada)) + IFNULL(tempo_na_fila, 0), tempo_na_fila), na_fila = 0, fila_entrada = NULL 
        WHERE tripulacao_id = ? OR tripulacao_id = ?",
        "ii", array($userDetails->tripulacao["id"], $alvo));
}

// valida lvl do capitao alvo
$capitao_alvo = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? ", "i", $usuario_alvo["cod_personagem"])->fetch_array();
if (($tipo == TIPO_ATAQUE || $tipo == TIPO_SAQUE) && $capitao_alvo["lvl"] < 10) {
    $protector->exit_error("É necessário que seu alvo tenha o capitão no nível 10 para iniciar um combate PvP");
}

// valida alvo em combate
$result = $connection->run("SELECT * FROM tb_combate WHERE id_1 = ? OR id_2 = ?", "ii", array($alvo, $alvo));
if ($result->count()) {
    $protector->exit_error("Seu alvo já está em combate");
}
$result = $connection->run("SELECT * FROM tb_combate_npc WHERE id = ?", "i", $alvo);
if ($result->count()) {
    $protector->exit_error("Seu alvo já está em combate contra um rei dos mares");
}
$result = $connection->run("SELECT * FROM tb_combate_bot WHERE tripulacao_id = ?", "i", $alvo);
if ($result->count()) {
    $protector->exit_error("Seu alvo já está em combate contra bots");
}

if ($usuario_alvo["adm"]) {
    $protector->exit_error("Um dos requisitos para atacar esse alvo não está cumprido.");
}

// valida requisitos de ataque
if ($tipo == TIPO_ATAQUE || $tipo == TIPO_SAQUE) {
    $protector->must_be_visivel();

    if (!$usuario_alvo["mar_visivel"]) {
        $protector->exit_error("O seu alvo precisa estar visível");
    }

    if (!can_attack($usuario_alvo)) {
        $protector->exit_error("Um dos requisitos para atacar esse alvo não está cumprido.");
    }
}


// carega personagens do alvo
if ($tipo == TIPO_COLISEU) {
    $personagens_alvo = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND time_coliseu= 1", "i", array($alvo))->fetch_all_array();
    $personagens_alvo = nivela_personagens_coliseu($personagens_alvo);

    $meus_personagens = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND time_coliseu= 1", "i", array($userDetails->tripulacao["id"]))->fetch_all_array();
    $meus_personagens = nivela_personagens_coliseu($meus_personagens);
} else if ($tipo == TIPO_LOCALIZADOR_CASUAL) {
    $personagens_alvo = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND time_casual= 1", "i", array($alvo))->fetch_all_array();
    $personagens_alvo = nivela_personagens_coliseu($personagens_alvo);

    $meus_personagens = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND time_casual= 1", "i", array($userDetails->tripulacao["id"]))->fetch_all_array();
    $meus_personagens = nivela_personagens_coliseu($meus_personagens);
} else if ($tipo == TIPO_LOCALIZADOR_COMPETITIVO) {
    $personagens_alvo = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND time_competitivo= 1", "i", array($alvo))->fetch_all_array();

    $meus_personagens = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND time_competitivo= 1", "i", array($userDetails->tripulacao["id"]))->fetch_all_array();
} else {
    $personagens_alvo = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 1", "i", array($alvo))->fetch_all_array();
    $meus_personagens = $userDetails->personagens;
}


// carrega o vip do alvo
$alvo_vip = $connection->run("SELECT * FROM tb_vip WHERE id = ?", "i", $alvo)->fetch_array();

// remove do oceano
$connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = NULL, navegacao_inicio = NULL, navegacao_fim = NULL  WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
$connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = NULL, navegacao_inicio = NULL, navegacao_fim = NULL  WHERE id = ?", "i", array($alvo));

// comeca a transaction
$connection->link()->begin_transaction();

// deleta combate_personagem por seguranca
$connection->run("DELETE FROM tb_combate_personagens WHERE id = ? ", "i", $alvo)->fetch_all_array();
$connection->run("DELETE FROM tb_combate_personagens WHERE id = ? ", "i", $userDetails->tripulacao["id"])->fetch_all_array();

// insere os personagens no combate
$taticas_alvo = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? "tatic_a" : "tatic_d";
$limites_alvo = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? array(5, 9) : array(0, 4);
$taticas_user = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? "tatic_d" : "tatic_a";
$limites_user = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? array(0, 4) : array(5, 9);

$id_1 = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? $usuario_alvo["id"] : $userDetails->tripulacao["id"];
$id_2 = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? $userDetails->tripulacao["id"] : $usuario_alvo["id"];

$obstaculos = $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = 1",
    "i", array($id_1))->fetch_all_array();

$obstaculos = array_merge($obstaculos, $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = 2",
    "i", array($id_2))->fetch_all_array());


function cria_crianca($id) {
    global $connection;
    $imgs = array(
        array("img" => 2, "skin" => 9),
        array("img" => 3, "skin" => 4),
        array("img" => 4, "skin" => 3),
        array("img" => 5, "skin" => 1),
        array("img" => 6, "skin" => 4),
        array("img" => 9, "skin" => 4),
        array("img" => 13, "skin" => 10),
        array("img" => 34, "skin" => 1),
        array("img" => 85, "skin" => 11),
        array("img" => 179, "skin" => 2),
        array("img" => 3, "skin" => 4),
        array("img" => 186, "skin" => 14),
        array("img" => 191, "skin" => 4),
        array("img" => 199, "skin" => 2),
        array("img" => 224, "skin" => 1),
        array("img" => 228, "skin" => 1)
    );

    $img = $imgs[array_rand($imgs)];

    $result = $connection->run("INSERT INTO tb_personagens (id, img, nome, skin_r, skin_c, xp, hp, hp_max, ativo, temporario) VALUES (?, ?, ?, ?, ?, ?, 1, 1, 0, 1)",
        "iisiii", array($id, $img["img"], "Catarrento " . $id, $img["skin"], $img["skin"], 0));

    $cod = $result->last_id();

    $connection->run("INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon)
		VALUES ('$cod', '" . COD_SKILL_SOCO . "', '" . TIPO_SKILL_ATAQUE_CLASSE . "', 'Soco', 'Tenta acerta um soco no oponente.', '1')");

    return $connection->run("SELECT * FROM tb_personagens WHERE cod = ?",
        "i", array($cod))->fetch_array();
}

if ($userDetails->buffs->get_efeito("chamado_infantil")) {
    $connection->run("UPDATE tb_usuarios SET batalhas_criancas = batalhas_criancas + 1 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));

    $meus_personagens = array_merge($meus_personagens, array(cria_crianca($userDetails->tripulacao["id"])));
}

if ($userDetails->buffs->get_efeito_from_tripulacao("chamado_infantil", $alvo)) {
    $connection->run("UPDATE tb_usuarios SET batalhas_criancas = batalhas_criancas + 1 WHERE id = ?",
        "i", array($alvo));

    $personagens_alvo = array_merge($personagens_alvo, array(cria_crianca($alvo)));
}

$nivelamento = $tipo == TIPO_COLISEU || $tipo == TIPO_LOCALIZADOR_CASUAL;
insert_personagens_combate($usuario_alvo["id"], $personagens_alvo, $alvo_vip, $taticas_alvo, $limites_alvo[0], $limites_alvo[1], $obstaculos, $nivelamento);
insert_personagens_combate($userDetails->tripulacao["id"], $meus_personagens, $userDetails->vip, $taticas_user, $limites_user[0], $limites_user[1], $obstaculos, $nivelamento);

// dead lock validation
$result = $connection->run("SELECT * FROM tb_combate WHERE id_1 = ? OR id_2 = ? OR id_1 = ? OR id_2 = ?",
    "iiii", array($alvo, $alvo, $userDetails->tripulacao["id"], $userDetails->tripulacao["id"]));
if ($result->count()) {
    $connection->link()->rollback();
    $protector->exit_error("Seu alvo já está em combate");
}
$result = $connection->run("SELECT * FROM tb_combate_npc WHERE id = ?", "i", $alvo);
if ($result->count()) {
    $connection->link()->rollback();
    $protector->exit_error("Seu alvo já está em combate contra um rei dos mares");
}

// cria o registro de combate
$vez = 1;
$tempo = $tipo == TIPO_COLISEU || $tipo == TIPO_CONTROLE_ILHA ? (atual_segundo() + 120) : (atual_segundo() + 90);
$battle_back = $tipo == TIPO_COLISEU ? 42 : ($tipo == TIPO_CONTROLE_ILHA ? 54 : NULL);
$result = $connection->run(
    "INSERT INTO tb_combate (id_1, id_2, vez, vez_tempo, move_1, move_2, tipo, battle_back) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
    "iiiiiiii", array($id_1, $id_2, $vez, $tempo, '5', '5', $tipo, $battle_back)
);
$combate_id = $result->last_id();

// cria o log
$pos_1 = $userDetails->tripulacao["x"] . "_" . $userDetails->tripulacao["y"];
$pos_2 = $usuario_alvo["x"] . "_" . $usuario_alvo["y"];
$connection->run(
    "INSERT INTO tb_combate_log (combate, id_1, id_2, tipo, pos_1, pos_2, ip_1, ip_2) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
    "iiiissss", array($combate_id, $id_1, $id_2, $tipo, $pos_1, $pos_2, $userDetails->tripulacao["ip"], $usuario_alvo["ip"])
);

if ($tipo == TIPO_TORNEIO) {
    $connection->run("UPDATE tb_combate SET permite_apostas_1 = 1, permite_apostas_2 = 1, premio_apostas = 5000000, preco_apostas= 5000000 WHERE combate = ?",
        "i", array($combate_id));
}

// envia a noticia para todos
if ($tipo != TIPO_AMIGAVEL && $userDetails->ilha["mar"] > 4) {
    if ($tipo == TIPO_COLISEU) {
        $mar = "no Coliseu";
    } else if ($tipo == TIPO_CONTROLE_ILHA) {
        $mar = "pelo controle de " . nome_ilha($userDetails->ilha["ilha"]);
    } else if ($tipo == TIPO_LOCALIZADOR_CASUAL) {
        $mar = "pelo Localizador Casual";
    } else if ($tipo == TIPO_LOCALIZADOR_COMPETITIVO) {
        $mar = "pelo Localizador Competitivo";
    } else if ($tipo == TIPO_TORNEIO) {
        $mar = "no Torneio PvP";
    } else {
        $nome_mar = nome_mar($userDetails->ilha["mar"]);
        $mar = $userDetails->ilha["mar"] == 5 ? "na " . $nome_mar : "no " . $nome_mar;
    }
    $connection->run(
        "INSERT INTO tb_news_coo (msg) VALUE (?)",
        "s", array(
            $userDetails->tripulacao["tripulacao"] . " entrou em combate contra " . $usuario_alvo["tripulacao"] . " " . $mar
        )
    );
}

$connection->run("UPDATE tb_torneio_inscricao 
        SET tempo_na_fila = IFNULL((unix_timestamp() - unix_timestamp(fila_entrada)) + IFNULL(tempo_na_fila, 0), tempo_na_fila), na_fila = 0, fila_entrada = NULL 
        WHERE tripulacao_id = ?",
    "i", array($userDetails->tripulacao["id"]));

// fim
$connection->link()->commit();

echo("%combate");