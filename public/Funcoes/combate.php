<?php

function get_player_data_for_combat_check($alvo_id)
{
    global $connection;
    return $connection->run(
        "SELECT
        usr.id AS id,
        usr.x AS x,
        usr.y AS y,
        usr.adm AS adm,
        IF(usr.reputacao_mensal > 0, 0, usr.protecao_pvp) as protecao_pvp,
        usr.cod_personagem AS cod_personagem,
        usr.faccao AS faccao,
        usr.ip AS ip,
        usr.tripulacao AS tripulacao,
        usr.mar_visivel as mar_visivel,
        ally.cod_alianca AS cod_alianca,
        guerra.cod_inimigo AS cod_inimigo,
        (SELECT max(allpers.lvl) AS lvl FROM tb_personagens allpers WHERE allpers.id = usr.id AND allpers.ativo = 1) AS nivel_mais_forte
        FROM tb_usuarios usr
        LEFT JOIN tb_alianca_membros allymember ON usr.id = allymember.id
        LEFT JOIN tb_alianca ally ON ally.cod_alianca = allymember.cod_alianca
        LEFT JOIN tb_alianca_guerra guerra ON ally.cod_alianca = guerra.cod_alianca
        WHERE usr.id = ?", "i", $alvo_id
    );
}

function get_attack_restriction($content)
{
    global $userDetails;
    if (! $content["reputacao_mensal"] && $content["protecao_pvp"]) {
        return "O alvo está com o PVP desativado.";
    }

    if (! $userDetails->tripulacao["reputacao_mensal"] && $userDetails->tripulacao["protecao_pvp"]) {
        return "Você está com o PVP desativado.";
    }

    if (same_id($content)) {
        return "Você não pode atacar a si mesmo";
    }

    if (in_guerra($content)) {
        return null;
    }

    if (get_tempo_desde_ultimo_ataque($content) <= (10 * 60)) {
        return "Você precisa aguardar 10 minutos para atacar este alvo novamente.";
    }

    if (both_marine($content)) {
        return "Você não pode atacar outro marinheiro.";
    }

    if (! $userDetails->is_visivel) {
        return "Você não pode atacar enquanto estiver invisível.";
    }

    if (\Regras\Ilhas::has_ilha_envolta($content["x"], $content["y"])) {
        return "O alvo está em uma área segura.";
    }

    if ($userDetails->has_ilha_envolta_me) {
        return "Você está em uma área segura.";
    }

    if (same_ally($content)) {
        return "Você não pode atacar outro membro da sua aliança.";
    }

    if (tem_protecao_contra_mim($content)) {
        return "O alvo tem uma proteção contra você.";
    }

    return null;
}

function get_tempo_desde_ultimo_ataque($target)
{
    global $connection;
    global $userDetails;

    $result = $connection->run("SELECT unix_timestamp(current_timestamp) - unix_timestamp(fim) AS duracao
    FROM tb_combate_log
    WHERE ((id_1 = ? AND id_2 = ?) OR (id_1 = ? AND id_2 = ?)) AND tipo = ?
    ORDER BY horario DESC
    LIMIT 1",
        "iiiii",
        [
            $userDetails->tripulacao["id"],
            $target["id"],
            $target["id"],
            $userDetails->tripulacao["id"],
            TIPO_ATAQUE
        ]
    );
    if (! $result->count()) {
        return PHP_INT_MAX;
    }

    return $result->fetch_array()["duracao"];
}

function in_guerra($content)
{
    global $userDetails;
    return $userDetails->ally && $userDetails->ally["cod_alianca"] == $content["cod_inimigo"];
}

function tem_protecao_contra_mim($content)
{
    global $userDetails;
    global $connection;
    return $connection->run("SELECT * FROM tb_pvp_imune WHERE tripulacao_id = ? AND adversario_id = ? AND TIMEDIFF(current_timestamp, horario) < '00:30:00'",
        "ii", array($content["id"], $userDetails->tripulacao["id"]))->count() ? TRUE : FALSE;
}

function same_ally($content)
{
    global $userDetails;
    return $userDetails->ally && $userDetails->ally["cod_alianca"] == $content["cod_alianca"];
}

function same_id($content)
{
    global $userDetails;
    return $userDetails->tripulacao["id"] == $content["id"];
}

function both_marine($content)
{
    global $userDetails;
    return $userDetails->tripulacao["faccao"] == 0 && $content["faccao"] == 0;
}

function get_pers_in_combate($id)
{
    global $connection;
    $personagens = $connection->run(
        "SELECT
        pers.cod as cod,
        pers.cod as cod_pers,
        pers.id AS id,
        pers.id AS tripulacao_id,
        pers.nome AS nome,
        pers.lvl AS lvl,
        pers.classe AS classe,
        pers.classe_score AS classe_score,
        pers.haki_esq AS haki_esq,
        pers.haki_cri AS haki_cri,
        pers.haki_hdr as haki_hdr,
        pers.fama_ameaca AS fama_ameaca,
        pers.akuma AS akuma,
        pers.xp AS xp,
        akuma.categoria AS categoria_akuma,
        pers.profissao AS profissao,
        pers.profissao_lvl AS profissao_lvl,
        pers.profissao_xp AS profissao_xp,
        pers.profissao_xp_max AS profissao_xp_max,
        cbtpers.quadro_x AS quadro_x,
        cbtpers.quadro_y AS quadro_y,
        cbtpers.hp AS hp,
        cbtpers.hp_max AS hp_max,
        IFnull(cbtpers.img, pers.img) AS img,
        IFnull(cbtpers.skin_c, pers.skin_c) AS skin_c,
        IFnull(cbtpers.skin_r, pers.skin_r) AS skin_r,
        pers.borda AS borda,
        IF (pers.sexo = 0, titulo.nome, titulo.nome_f) AS titulo,
        cbtpers.atk AS atk,
        cbtpers.def AS def,
        cbtpers.agl AS agl,
        cbtpers.res AS res,
        cbtpers.pre AS pre,
        cbtpers.dex AS dex,
        cbtpers.con AS con,
        cbtpers.vit AS vit,
        cbtpers.fa_ganha AS fa_ganha,
        usr.cod_personagem AS cod_capitao,
        cbtpers.efeitos as efeitos
        FROM tb_combate_personagens cbtpers
        INNER JOIN tb_personagens pers ON cbtpers.cod = pers.cod
        INNER JOIN tb_usuarios usr ON usr.id = pers.id
        LEFT JOIN tb_titulos titulo ON pers.titulo = titulo.cod_titulo
        LEFT JOIN tb_akuma akuma ON pers.akuma = akuma.cod_akuma
        WHERE cbtpers.id = ? AND cbtpers.hp > 0",
        "i", [$id]
    )->fetch_all_array();

    foreach ($personagens as $key => $pers) {
        $personagens[$key]["efeitos"] = $pers["efeitos"] ? json_decode($pers["efeitos"], true) : [];
    }

    return $personagens;
}

function get_pers_bot_in_combate($id)
{
    global $connection;
    $personagens = $connection->run(
        "SELECT
        concat('bot_', cbtpers.id) AS cod,
        concat('bot_', cbtpers.id) as cod_pers,
        cbtpers.id AS bot_id,
        'bot' AS id,
        'bot' AS tripulacao_id,
        cbtpers.*
        FROM tb_combate_personagens_bot cbtpers
        WHERE cbtpers.combate_bot_id = ? AND hp > 0",
        "i", [$id]
    )->fetch_all_array();

    foreach ($personagens as $key => $pers) {
        $personagens[$key]["efeitos"] = $pers["efeitos"] ? json_decode($pers["efeitos"], true) : [];
    }

    return $personagens;
}

function nome_tipo_combate($tipo)
{
    switch ($tipo) {
        case TIPO_ATAQUE:
            return "Ataque";
        case TIPO_SAQUE:
            return "Saque";
        case TIPO_AMIGAVEL:
            return "Disputa amigável";
        case TIPO_COLISEU:
            return "Batalha no Coliseu";
        case TIPO_CONTROLE_ILHA:
            return "Disputa por Ilha";
        case TIPO_LOCALIZADOR_CASUAL:
            return "Batalha Casual";
        case TIPO_LOCALIZADOR_COMPETITIVO:
            return "Batalha Competitiva";
        case TIPO_TORNEIO:
            return "Batalha pelo Poneglyph";
        default:
            return "";
    }
}

function calc_recompensa($fa)
{
    $milhao = 1000000;
    if ($fa < $milhao) {
        return floor($fa / 1000) * 1000;
    } else if ($fa < 5 * $milhao) {
        return floor($fa / 500000) * 500000;
    } else if ($fa < 500 * $milhao) {
        return floor($fa / $milhao) * $milhao;
    } else if ($fa < 1000 * $milhao) {
        return floor($fa / (5 * $milhao)) * 5 * $milhao;
    } else if ($fa < 2000 * $milhao) {
        return floor($fa / (50 * $milhao)) * 50 * $milhao;
    } else {
        return floor($fa / (100 * $milhao)) * 100 * $milhao;
    }
}

function get_cross_guild_stars($reward)
{
    $ret = "";
    $bilion = 1000000000;
    $milion = 100000000;
    $cont = 0;
    if ($reward > $bilion) {
        while ($reward > $bilion && $cont < 5) {
            $ret .= "♛";
            $reward -= $bilion;
            $cont++;
        }
    } else {
        while ($cont <= 0 || ($reward > 0 && $cont < 5)) {
            $ret .= "★";
            $reward -= $milion;
            $cont++;
        }
    }

    return $ret;
}

function atacar_rdm($rdm_id, $details = null, $conn = null)
{
    if (! $conn) {
        global $connection;
    } else {
        $connection = $conn;
    }

    if (! $details) {
        global $userDetails;
    } else {
        $userDetails = $details;
    }

    $rdms = DataLoader::load("rdm");

    $rdm = $rdms[$rdm_id];

    if (! isset($rdm["boss"])) {
        $rdm["boss"] = null;
    }

    $connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
    $connection->run("DELETE FROM tb_mapa_contem WHERE id = ?", "i", $userDetails->tripulacao["id"]);

    $connection->run(
        "INSERT INTO tb_combate_npc
        (id,
        img_npc,
        nome_npc,
        hp_npc, hp_max_npc,
        mp_npc, mp_max_npc,
		atk_npc, def_npc, agl_npc, res_npc, pre_npc, dex_npc, con_npc,
		dano, armadura,
		zona, boss_id, battle_back)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "iisiiiiiiiiiiiiiiii", array(
            $userDetails->tripulacao["id"],
            isset($rdm["img"]) ? $rdm["img"] : rand($rdm["img_rand_min"], $rdm["img_rand_max"]),
            $rdm["nome"],
            $rdm["hp"], $rdm["hp"],
            1, 0,
            $rdm["atk"], $rdm["def"], $rdm["agl"], $rdm["res"], $rdm["pre"], $rdm["dex"], $rdm["con"],
            0, 0,
            $rdm["id"], $rdm["boss"], isset($rdm["battle_back"]) ? $rdm["battle_back"] : null
        )
    );

    $connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = null, navegacao_inicio = null, navegacao_fim = null WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));

    // força o update caso invocada do map server
    $userDetails->reload_personagems();

    insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_p", 0, 4, array(), false, $userDetails, $connection);
}

function cria_crianca($id)
{
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

    return $connection->run("SELECT * FROM tb_personagens WHERE cod = ?",
        "i", array($cod))->fetch_array();
}

function inicia_combate($alvo, $tipo, $chave = null)
{
    global $protector;
    global $connection;
    global $userDetails;

    // valida ataque e saque
    if ($tipo == TIPO_ATAQUE || $tipo == TIPO_SAQUE) {
        $protector->must_be_out_of_ilha();
    }

    // remove desafio
    if ($tipo == TIPO_AMIGAVEL) {
        $result = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ?", "i", $userDetails->tripulacao["id"]);
        if (! $result->count()) {
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

        if (! $disputa->count()) {
            $protector->exit_error("Essa ilha não está sob disputa");
        }

        $disputa = $disputa->fetch_array();

        if ($disputa["vencedor_id"] != $userDetails->tripulacao["id"] && $disputa["vencedor_id"] != $alvo) {
            $protector->exit_error("Nenhum de voces foi vencedor da disputa pela ilha");
        }
    }

    // valida o coliseu
    if ($tipo == TIPO_COLISEU || $tipo == TIPO_LOCALIZADOR_CASUAL || $tipo == TIPO_LOCALIZADOR_COMPETITIVO) {
        if (! $userDetails->fila_coliseu
            || ! $userDetails->fila_coliseu["desafio"]
            || $alvo != $userDetails->fila_coliseu["desafio"]
            || ! $userDetails->fila_coliseu["desafio_aceito"]
            || $userDetails->fila_coliseu["desafio_tipo"] != $tipo
        ) {
            $protector->exit_error("Você não foi desafiado.");
        }

        $adversario_fila = $connection->run("SELECT * FROM tb_coliseu_fila WHERE desafio = ?",
            "i", array($userDetails->tripulacao["id"]));

        if (! $adversario_fila->count()) {
            $protector->exit_error("Seu adversário não recebeu o desafio");
        }
        $adversario_fila = $adversario_fila->fetch_array();

        if (! $adversario_fila["desafio_aceito"]) {
            $protector->exit_error("Seu adversário ainda não aceitou o desafio");
        }

        $connection->run("DELETE FROM tb_coliseu_fila WHERE id = ? OR id = ?",
            "ii", array($userDetails->tripulacao["id"], $adversario_fila["id"]));
    }

    // carrega usuario alvo
    $result = get_player_data_for_combat_check($alvo);
    if (! $result->count()) {
        $protector->exit_error("Alvo não encontrado");
    }
    $usuario_alvo = $result->fetch_array();

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

    // if ($usuario_alvo["adm"]) {
    //     $protector->exit_error("Um dos requisitos para atacar esse alvo não está cumprido.");
    // }

    // valida requisitos de ataque
    if ($tipo == TIPO_ATAQUE || $tipo == TIPO_SAQUE) {
        $protector->must_be_visivel();

        if (! $usuario_alvo["mar_visivel"]) {
            $protector->exit_error("O seu alvo precisa estar visível");
        }

        if ($restriction = get_attack_restriction($usuario_alvo)) {
            $protector->exit_error($restriction);
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
    $connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = null, navegacao_inicio = null, navegacao_fim = null  WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
    $connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = null, navegacao_inicio = null, navegacao_fim = null  WHERE id = ?", "i", array($alvo));

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
    $battle_back = $tipo == TIPO_COLISEU ? 42 : ($tipo == TIPO_CONTROLE_ILHA ? 54 : null);
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
            $mar = "em uma batalha por um Poneglyph";
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

    if ($tipo == TIPO_TORNEIO) {
        $posicao = $chave["tripulacao_1_id"] == $userDetails->tripulacao["id"] ? "1" : "2";
        $placar_1 = $posicao == "1" ? count($meus_personagens) : count($personagens_alvo);
        $placar_2 = $posicao == "2" ? count($meus_personagens) : count($personagens_alvo);
        $connection->run(
            "UPDATE tb_torneio_chave SET em_andamento = 1, placar_1 = ?, placar_2 = ?, combate_id = ? WHERE id = ?",
            "iiii", [$placar_1, $placar_2, $combate_id, $chave["id"]]
        );
    }

    // fim
    $connection->link()->commit();

    return $combate_id;
}

?>
<?php function render_tabuleiro($tabuleiro, $x1, $x2, $id_blue = null, $mira = null)
{ ?>
    <?php
    global $userDetails;
    if ($id_blue === null) {
        $id_blue = $userDetails->tripulacao["id"];
    }

    $classes = [];
    $backgrounds = [];
    for ($x = $x1; $x < $x2; $x++) {
        $classes[$x] = [];
        $backgrounds[$x] = [];
        for ($y = 0; $y < 20; $y++) {
            $classes[$x][$y] = [
                "base" => [$x . "_" . $y],
                "selecao" => ["selecionavel", "td-selecao"]
            ];
            $backgrounds[$x][$y] = [];
            if (isset($tabuleiro[$x][$y])) {
                $pers = $tabuleiro[$x][$y];
                $classes[$x][$y]["base"][] = $pers["tripulacao_id"] == $id_blue ? "personagem-aliado" : "personagem-inimigo";
                $classes[$x][$y]["selecao"][] = "personagem";
                $classes[$x][$y]["selecao"][] = $pers["tripulacao_id"] == $id_blue ? "aliado" : "inimigo";

                if (isset($pers["efeitos"]) && count($pers["efeitos"])) {
                    $classes[$x][$y]["base"][] = "efeito-estetico";
                    foreach ($pers["efeitos"] as $index => $efeito) {
                        $classes[$x][$y]["base"][] = "efeito-estetico-" . $efeito["bonus"]["atr"];
                        $classes[$x][$y]["selecao"][] = "efeito-" . $efeito["bonus"]["atr"];
                        $backgrounds[$x][$y][] = $efeito["bonus"]["cor"] . " " . (($index / count($pers["efeitos"])) * 100) . "%";
                        $backgrounds[$x][$y][] = $efeito["bonus"]["cor"] . " " . ((($index + 1) / count($pers["efeitos"])) * 100) . "%";
                    }
                }
            }
        }
    }
    ?>

    <table class="table_batalha">
        <?php for ($x = $x1; $x < $x2; $x++) : ?>
            <tr>
                <td class="td-mira">
                    <?php if ($mira !== null && $mira == $x) : ?>
                        <div class="tabuleiro-mira" data-toggle="tooltip" title="O adversário está mirando aqui.">
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <?php for ($y = 0; $y < 20; $y++) : ?>
                    <td id="<?= isset($tabuleiro[$x][$y]) ? $tabuleiro[$x][$y]["cod"] : "" ?>"
                        class="<?= implode(' ', $classes[$x][$y]["base"]); ?>"
                        style="<?= count($backgrounds[$x][$y]) ? "background: linear-gradient(90deg, " . implode(", ", $backgrounds[$x][$y]) . ");" : ""; ?>">
                        <?php if (isset($tabuleiro[$x][$y])) : ?>
                            <div class="progress progress-red">
                                <div class="progress-bar progress-bar-success"
                                    style="width: <?= $tabuleiro[$x][$y]["hp"] / $tabuleiro[$x][$y]["hp_max"] * 100 ?>%">
                                </div>
                            </div>
                            <img src="Imagens/Personagens/Icons/<?= getImg($tabuleiro[$x][$y], "r") ?>.jpg">
                        <?php endif; ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
    <table class="table_batalha_selecao">
        <?php for ($x = $x1; $x < $x2; $x++) : ?>
            <tr>
                <td class="td-mira">
                </td>
                <?php for ($y = 0; $y < 20; $y++) : ?>
                    <td id="<?= $x . "_" . $y; ?>" class="<?= implode(' ', $classes[$x][$y]["selecao"]); ?>" data-x="<?= $x ?>"
                        data-y="<?= $y ?>" data-cod="<?= isset($tabuleiro[$x][$y]) ? $tabuleiro[$x][$y]["cod"] : "" ?>">
                        <img src="Imagens/Icones/selecao.png" />
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
<?php } ?>
<?php function render_personagens_info($personagens_combate, $id_blue = null)
{ ?>
    <?php global $userDetails; ?>
    <?php if ($id_blue === null) {
        $id_blue = $userDetails->tripulacao["id"];
    } ?>
    <?php foreach ($personagens_combate as $pers) : ?>
        <?php $info_avancado = ($userDetails->vip["conhecimento_duracao"] && $pers["tripulacao_id"] == $userDetails->tripulacao["id"]) || $userDetails->tripulacao["adm"]; ?>
        <?php if ($pers["hp"]) : ?>
            <?php $pers["id"] = $pers["tripulacao_id"]; ?>
            <div class="personagem-info hidden" id="personagem-info-<?= $pers["cod"] ?>">
                <div class="panel panel-<?= $pers["tripulacao_id"] == $id_blue ? "info" : "danger" ?>">
                    <div class="panel-heading">
                        <?= $pers["nome"]; ?>,
                        <?= $pers["titulo"] ?>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6" style="width: 100%">
                                <?= big_pers_skin($pers["img"], $pers["skin_c"], isset($pers["borda"]) ? $pers["borda"] : 0, "tripulante_big_img", 'width="100%"') ?>
                                <br />
                                <?php if ($info_avancado) : ?>
                                    <?php if ($pers["akuma"]) : ?>
                                        <div>
                                            Akuma no Mi:
                                            <?= nome_categoria_akuma($pers["categoria_akuma"]); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($pers["profissao"] == PROFISSAO_MEDICO
                                        || $pers["profissao"] == PROFISSAO_COMBATENTE
                                        || $pers["profissao"] == PROFISSAO_MUSICO
                                    ) : ?>
                                        <div class="clearfix">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-primary" role="progressbar"
                                                    style="width: <?= $pers["profissao_xp"] / $pers["profissao_xp_max"] * 100 ?>%;">
                                                    <?= nome_prof($pers["profissao"]) . ':' . $pers["profissao_xp"] . "/" . $pers["profissao_xp_max"]; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($pers["tripulacao_id"] == $userDetails->tripulacao["id"] || $userDetails->tripulacao["adm"]) : ?>
                                    Nível
                                    <?= $pers["lvl"] ?>
                                <?php endif; ?>

                                <?php render_personagem_hp_bar($pers); ?>
                            </div>
                            <div class="col-xs-6" style="width: 100%">
                                <?php if (isset($pers["efeitos"]) && count($pers["efeitos"])) : ?>
                                    <h4>Buffs</h4>
                                    <?php foreach ($pers["efeitos"] as $efeito) : ?>
                                        <div class="text-center">
                                            <?= Componentes::render("Habilidades.Explicacao", ["explicacao" => $efeito["explicacao"]]); ?>
                                            (<?= $efeito["duracao"] + 1; ?>)
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if ($info_avancado) : ?>
                                    <?php render_personagem_haki_bars($pers); ?>
                                    <div style="display: flex; justify-content: center;">
                                        <?php render_row_atributo("atk", "Ataque", $pers); ?>
                                        <?php render_row_atributo("def", "Defesa", $pers); ?>
                                        <?php render_row_atributo("pre", "Precisao", $pers); ?>
                                        <?php render_row_atributo("agl", "Agilidade", $pers); ?>
                                        <?php render_row_atributo("res", "Resistencia", $pers); ?>
                                        <?php render_row_atributo("con", "Conviccao", $pers); ?>
                                        <?php render_row_atributo("dex", "Dextreza", $pers); ?>
                                        <?php render_row_atributo("vit", "Vitalidade", $pers); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php } ?>
<?php function render_row_atributo($abr, $img, $pers)
{ ?>
    <div
        style="display: flex; justify-content: center; align-items: center;flex-wrap: wrap; margin: 3px 2px; padding: 2px; background-color: #000; opacity: 0.8;">
        <img src="Imagens/Icones/<?= $img ?>.png" width="25px">
        <div class="text-left" style="display: inline-block; color: white; margin: 0 2px;">
            <?= $pers[$abr]; ?>
        </div>
    </div>
<?php } ?>
<?php function get_img_combate($log)
{
    return ($log["skin_r"] === "npc")
        ? "Imagens/Batalha/Npc/" . $log["img"] . ".png"
        : "Imagens/Personagens/Icons/" . get_img($log, "r") . ".jpg";
} ?>
<?php function render_relatorio_data($relatorio, $id_azul, $avancado = false)
{ ?>
    <ul class="list-group">
        <?php foreach ($relatorio as $index => $log) : ?>
            <?php if ($index > 5) {
                break;
            } ?>
            <li class="list-group-item">
                <?php if ($index == 0) : ?>
                    <div class="relatorio-origem-meta-data"
                        data-log='<?= str_replace("'", "", json_encode($log, JSON_NUMERIC_CHECK)); ?>'></div>
                <?php endif; ?>
                <h4>
                    <img src="<?= get_img_combate($log) ?>" height="55px"
                        class="<?= $log["id"] == $id_azul ? "personagem-aliado" : "personagem-inimigo" ?>" />
                    <?= $log["nome"] ?>
                    <?php if ($log["tipo"] == 0) : ?>
                        se movimentou
                    <?php elseif ($log["tipo"] == 3) : ?>
                        passou a vez
                    <?php else : ?>
                        usou: <img src="Imagens/Skils/<?= $log["img_skil"] ?>.jpg" />
                        <?= $log["nome_skil"] ?>
                    <?php endif; ?>
                </h4>

                <?php if ($log["tipo"] != 0 && $log["tipo"] != 3) : ?>
                    <p>
                        <?= $log["descricao_skil"] ?>
                    </p>

                    <?php foreach ($log["afetados"] as $afetado) : ?>
                        <?php if ($index == 0) : ?>
                            <div class="relatorio-meta-data"
                                data-log='<?= str_replace("'", "", json_encode($afetado, JSON_NUMERIC_CHECK)); ?>'></div>
                        <?php endif; ?>
                        <p>
                            <?php if ($afetado["acerto"] == 0) : ?>
                                Acertou um quadrado vazio
                            <?php else : ?>
                                <img src="<?= get_img_combate($afetado) ?>" height="40px"
                                    class="<?= $afetado["id"] == $id_azul ? "personagem-aliado" : "personagem-inimigo" ?>" />
                                <?= $afetado["nome"] ?>
                                <?php if ($afetado["tipo"] == 0) : ?>
                                    <?php if ($afetado["esq"] == 1) : ?>
                                        <span class="esquiva">Se esquivou</span>
                                        <?php if ($avancado && isset($afetado["resultado"])) : ?>
                                            <span class="text-success">Rolou no dado
                                                <?= $afetado["resultado"]["dado_esquivou"] ?>/100 com chance de
                                                <?= $afetado["resultado"]["chance_esquiva"] ?>%
                                            </span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if ($avancado && isset($afetado["resultado"])) : ?>
                                            <span>Tentou esquivar, mas rolou no dado
                                                <?= $afetado["resultado"]["dado_esquivou"] ?>/100 com chance de
                                                <?= $afetado["resultado"]["chance_esquiva"] ?>%
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($afetado["bloq"] == 1) : ?>
                                            <span class="bloqueio">Bloqueou</span>
                                            <?php if ($avancado && isset($afetado["resultado"])) : ?>
                                                <span class="text-info">Rolou no dado
                                                    <?= $afetado["resultado"]["dado_bloqueou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_bloqueio"] ?>%
                                                </span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php if ($avancado && isset($afetado["resultado"])) : ?>
                                                <span>Tentou bloquear mas rolou no dado
                                                    <?= $afetado["resultado"]["dado_bloqueou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_bloqueio"] ?>%
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        perdeu
                                        <?= $afetado["efeito"] ?> pontos de vida
                                        <?php if ($afetado["cri"] == 1) : ?>
                                            <span class="critico">Ataque crítico</span>
                                            <?php if ($avancado && isset($afetado["resultado"])) : ?>
                                                <span class="text-danger">Rolou no dado
                                                    <?= $afetado["resultado"]["dado_critou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_critico"] ?>%
                                                </span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php if ($avancado && isset($afetado["resultado"])) : ?>
                                                <span>O ataque crítico falhou pois rolou no dado
                                                    <?= $afetado["resultado"]["dado_critou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_critico"] ?>%
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($afetado["derrotado"] == 1) : ?>
                                            <span class="derrotado">e foi derrotado</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($afetado["tipo"] == 1) : ?>
                                    recebeu
                                    <?= $afetado["efeito"] ?> pontos em
                                    <?= nome_atributo($afetado["atributo"]) ?>
                                <?php elseif ($afetado["tipo"] == 2) : ?>
                                    <?php if (! empty($afetado["cura_h"])) : ?>
                                        recebeu
                                        <?= $afetado["cura_h"] ?> pontos de vida
                                    <?php endif; ?>
                                    <?php if (! empty($afetado["cura_m"])) : ?>
                                        recebeu
                                        <?= $afetado["cura_m"] ?> pontos de energia
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php } ?>
<?php function render_vontade($amount)
{ ?>
    <span class="vontade" style="background-size: <?= min($amount / 50, 1) * 100; ?>%" data-toggle="tooltip"
        data-placement="bottom" data-container="body"
        title="Vontade: A cada turno essa tripulação pode utilizar habilidades mais poderosas. Você ganha um ponto de Vontade adicional sempre que um tripulante for derrotado.">
        <?= $amount ?>
    </span>
<?php } ?>
<?php function render_combate_pvp_header($combate, $tripulacao, $id_blue = null)
{ ?>
    <?php global $userDetails, $personagens_combate; ?>
    <?php $esconder_vontade = true; ?>
    <?php if ($id_blue === null) {
        $id_blue = $userDetails->tripulacao["id"];
        $esconder_vontade = false;
    } ?>
    <div class="battle-heading-details">
        <span class="battle-player text-right">
            <?= $tripulacao["1"]["tripulacao"] ?>
            <img class="personagem-<?= $tripulacao["1"]["id"] == $id_blue ? "aliado" : "inimigo" ?>"
                src="Imagens/Bandeiras/img.php?cod=<?= $tripulacao["1"]["bandeira"] ?>&f=<?= $tripulacao["1"]["faccao"] ?>">
        </span>
        <?php if ($personagens_combate["1"]) : ?>
            <?php if (! $esconder_vontade) : ?>
                <?php render_vontade($combate["vontade_1"]) ?>
            <?php endif; ?>
            <span class="placar text-<?= $tripulacao["1"]["id"] == $id_blue ? "info" : "danger" ?>">
                <?= count($personagens_combate["1"]) ?>
            </span>
        <?php endif; ?>
        <img src="Imagens/Batalha/vs.png" />
        <?php if ($personagens_combate["2"]) : ?>
            <span class="placar text-<?= $tripulacao["2"]["id"] == $id_blue ? "info" : "danger" ?>">
                <?= count($personagens_combate["2"]) ?>
            </span>
            <?php if (! $esconder_vontade) : ?>
                <?php render_vontade($combate["vontade_2"]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <span class="battle-player text-left">
            <img class="personagem-<?= $tripulacao["2"]["id"] == $id_blue ? "aliado" : "inimigo" ?>"
                src="Imagens/Bandeiras/img.php?cod=<?= $tripulacao["2"]["bandeira"] ?>&f=<?= $tripulacao["2"]["faccao"] ?>">
            <?= $tripulacao["2"]["tripulacao"] ?>
        </span>
    </div>
    <div>
        <?= nome_tipo_combate($combate["tipo"]); ?>
    </div>
<?php } ?>

<?php function render_battle_heading($combate = null, $tripulacoes = null, $id_blue = null)
{
    global $userDetails, $personagens_combate, $personagens_combate_bot; ?>
    <div class="battle-heading">
        <?php if ($userDetails->combate_pvp || $combate) : ?>
            <?php render_combate_pvp_header(
                $combate != null ? $combate : $userDetails->combate_pvp,
                $tripulacoes != null ? $tripulacoes : $userDetails->tripulacoes_pvp,
                $id_blue
            ); ?>
        <?php elseif ($userDetails->combate_pve) : ?>
            <div class="battle-heading-details">
                <span class="battle-player text-right">
                    <?= $userDetails->tripulacao["tripulacao"] ?>
                    <img
                        src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"] ?>&f=<?= $userDetails->tripulacao["faccao"] ?>">
                </span>
                <?php render_vontade($userDetails->combate_pve["vontade_1"]) ?>
                <span class="placar">
                    <?= count($personagens_combate) ?>
                </span>
                <img src="Imagens/Batalha/vs.png" />
                <span class="placar">
                    <?= $userDetails->combate_pve["hp_npc"] ? "1" : "0" ?>
                </span>
                <?php render_vontade($userDetails->combate_pve["vontade_npc"]) ?>
                <span class="battle-player text-left">
                    <img src="Imagens/Batalha/npc.jpg" />
                    <?= $userDetails->combate_pve["nome_npc"] ?>
                </span>
            </div>
        <?php elseif ($userDetails->combate_bot) : ?>
            <div class="battle-heading-details">
                <span class="battle-player text-right">
                    <?= $userDetails->tripulacao["tripulacao"] ?>
                    <img
                        src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"] ?>&f=<?= $userDetails->tripulacao["faccao"] ?>">
                </span>
                <?php render_vontade($userDetails->combate_bot["vontade_1"]) ?>
                <span class="placar text-info">
                    <?= count($personagens_combate) ?>
                </span>
                <img src="Imagens/Batalha/vs.png" />
                <span class="placar text-danger">
                    <?= count($personagens_combate_bot) ?>
                </span>
                <?php render_vontade($userDetails->combate_bot["vontade_2"]) ?>
                <span class="battle-player text-left">
                    <img
                        src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->combate_bot["bandeira_inimiga"] ?>&f=<?= $userDetails->combate_bot["faccao_inimiga"] ?>">
                    <?= $userDetails->combate_bot["tripulacao_inimiga"] ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
<?php } ?>

