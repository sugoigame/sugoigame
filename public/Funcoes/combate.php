<?php
function has_ilha_envolta_target($content) {
    global $connection;

    $x = $content["x"];
    $y = $content["y"];
    $ilha_envolta = $connection->run(
        "SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND ilha <> 0 AND ilha <> 47",
        "iiii", array($x - 2, $x + 2, $y - 2, $y + 2)
    )->count();

    return !!$ilha_envolta;
}

function get_player_in_coord($x, $y) {
    global $connection;
    return $connection->run(
        "SELECT 
          contem.increment_id AS increment_id,
          contem.nps_id AS nps_id,
          contem.mercador_id AS mercador_id,
          contem.x AS x,
          contem.y AS y,
          usr.id AS id,
          usr.tripulacao AS tripulacao,
          usr.bandeira AS bandeira,
          usr.faccao AS faccao,
          usr.coord_x_navio AS coord_x_navio,
          usr.coord_y_navio AS coord_y_navio,
          usr.skin_navio AS skin_navio,
          usr.reputacao AS reputacao,
          usr.adm,
          pers.nome AS capitao,
          pers.lvl AS capitao_lvl,
          IF (pers.sexo = 0, titulo.nome, titulo.nome_f) AS titulo,
          ally.nome AS alianca,
          ally.cod_alianca AS cod_alianca,
          guerra.cod_inimigo AS cod_inimigo,
          (SELECT max(allpers.lvl) FROM tb_personagens allpers WHERE allpers.id = usr.id  AND allpers.ativo = 1) AS nivel_mais_forte
        FROM tb_mapa_contem contem
        LEFT JOIN tb_usuarios usr ON usr.id = contem.id
        LEFT JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
        LEFT JOIN tb_titulos titulo ON pers.titulo = titulo.cod_titulo
        LEFT JOIN tb_alianca_membros allymember ON usr.id = allymember.id
        LEFT JOIN tb_alianca ally ON ally.cod_alianca = allymember.cod_alianca
        LEFT JOIN tb_alianca_guerra guerra ON ally.cod_alianca = guerra.cod_alianca
        WHERE contem.x = ? AND contem.y = ?", "ii", array($x, $y)
    );
}

function get_player_data_for_combat_check($alvo_id) {
    global $connection;
    return $connection->run(
        "SELECT
        usr.id AS id,
        usr.x AS x,
        usr.y AS y,
        usr.adm AS adm,
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

function can_attack($content) {
    global $userDetails;

    if (same_id($content)) {
        return false;
    }

    if (in_guerra($content)) {
        return true;
    }

    return !both_marine($content)
        && diff_mais_forte_equilibrada($content)
        && $userDetails->is_visivel
        && !has_ilha_envolta_target($content)
        && !$userDetails->has_ilha_envolta_me
        && !same_ally($content)
        && ao_lado($content)
        && !tem_protecao_contra_mim($content);
}

function can_attack_mercador($content) {
    global $connection;
    global $userDetails;

    if (!$content["mercador_id"]) {
        return false;
    }

    $ilha_dono = $connection->run("SELECT mapa.ilha_dono FROM tb_ilha_mercador m INNER JOIN tb_mapa mapa ON m.ilha_origem = mapa.ilha WHERE m.id = ?",
        "i", $content["mercador_id"])->fetch_array()["ilha_dono"];

    if (!$ilha_dono) {
        return false;
    }

    return $userDetails->is_visivel
        && !$userDetails->has_ilha_envolta_me
        && !has_ilha_envolta_target(array("x" => $content["x"], "y" => $content["y"]))
        && ao_lado(array("x" => $content["x"], "y" => $content["y"]));
}

function can_attack_nps($content) {
    return ao_lado(array("x" => $content["x"], "y" => $content["y"]));
}

function diff_mais_forte_equilibrada($alvo) {
    global $userDetails;
    $diff = abs($userDetails->lvl_mais_forte - $alvo["nivel_mais_forte"]);

    if ($userDetails->ilha["mar"] <= 5) { // blue e GL
        return $diff <= 5;
    } else { // calm belt e novo mundo
        return true;
    }
}

function can_dispair_cannon($content) {
    global $userDetails;

    return !same_id($content)
        && diff_mais_forte_equilibrada($content)
        && !both_marine($content)
        && $userDetails->is_visivel
        && !has_ilha_envolta_target($content)
        && !$userDetails->has_ilha_envolta_me
        && !same_ally($content);
}

function in_guerra($content) {
    global $userDetails;
    return $userDetails->ally && $userDetails->ally["cod_alianca"] == $content["cod_inimigo"];
}

function tem_protecao_contra_mim($content) {
    global $userDetails;
    global $connection;
    return $connection->run("SELECT * FROM tb_pvp_imune WHERE tripulacao_id = ? AND adversario_id = ? AND TIMEDIFF(current_timestamp, horario) < '00:30:00'",
        "ii", array($content["id"], $userDetails->tripulacao["id"]))->count() ? TRUE : FALSE;
}

function same_ally($content) {
    global $userDetails;
    return $userDetails->ally && $userDetails->ally["cod_alianca"] == $content["cod_alianca"];
}

function same_id($content) {
    global $userDetails;
    return $userDetails->tripulacao["id"] == $content["id"];
}

function both_marine($content) {
    global $userDetails;
    return $userDetails->tripulacao["faccao"] == 0 && $content["faccao"] == 0;
}

function ao_lado($content) {
    global $userDetails;
    return sqrt(pow($content["x"] - $userDetails->tripulacao["x"], 2) + pow($content["y"] - $userDetails->tripulacao["y"], 2)) <= 2;
}

function get_pers_in_combate($id) {
    global $connection;
    return $connection->run(
        "SELECT 
        pers.id AS id,
        pers.id AS tripulacao_id,
        pers.nome AS nome,
        pers.lvl AS lvl,
        pers.classe AS classe, 
        pers.classe_score AS classe_score, 
        pers.id AS tripulacao_id,
        pers.haki_esq AS haki_esq,
        pers.haki_cri AS haki_cri,
        pers.fama_ameaca AS fama_ameaca,
        pers.akuma AS akuma,
        akuma.categoria AS categoria_akuma,
        pers.profissao AS profissao,
        pers.profissao_xp AS profissao_xp,
        pers.profissao_xp_max AS profissao_xp_max,
        cbtpers.cod AS cod, 
        cbtpers.quadro_x AS quadro_x,
        cbtpers.quadro_y AS quadro_y,
        cbtpers.hp AS hp,
        cbtpers.hp_max AS hp_max,
        cbtpers.mp AS mp,
        cbtpers.mp_max AS mp_max,
        IFNULL(cbtpers.img, pers.img) AS img,
        IFNULL(cbtpers.skin_c, pers.skin_c) AS skin_c,
        IFNULL(cbtpers.skin_r, pers.skin_r) AS skin_r,
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
        usr.cod_personagem AS cod_capitao
        FROM tb_combate_personagens cbtpers
        INNER JOIN tb_personagens pers ON cbtpers.cod = pers.cod
        INNER JOIN tb_usuarios usr ON usr.id = pers.id
        LEFT JOIN tb_titulos titulo ON pers.titulo = titulo.cod_titulo
        LEFT JOIN tb_akuma akuma ON pers.akuma = akuma.cod_akuma
        WHERE cbtpers.id = ? AND cbtpers.hp > 0",
        "i", $id
    )->fetch_all_array();
}

function get_pers_bot_in_combate($id) {
    global $connection;
    return $connection->run(
        "SELECT 
        concat('bot_', cbtpers.id) AS cod, 
        cbtpers.id AS bot_id,
        'bot' AS id,
        'bot' AS tripulacao_id,
        cbtpers.*
        FROM tb_combate_personagens_bot cbtpers
        WHERE cbtpers.combate_bot_id = ? AND hp > 0",
        "i", array($id)
    )->fetch_all_array();
}

function get_buffs_combate($id_1, $id_2 = null) {
    global $connection;
    $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_buff WHERE id = ?", "i", array($id_1))->fetch_all_array();
    $buffs = [];
    foreach ($buffs_desordenados as $buff) {
        $buffs[$buff["cod"]][] = $buff;
    }

    if ($id_2) {
        $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_buff WHERE id = ?", "i", array($id_2))->fetch_all_array();
        foreach ($buffs_desordenados as $buff) {
            $buffs[$buff["cod"]][] = $buff;
        }
    }

    return $buffs;
}

function get_special_effects($id_1, $id_2 = null) {
    global $connection;
    $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_special_effect WHERE tripulacao_id = ?", "i", array($id_1))->fetch_all_array();
    $buffs = [];
    foreach ($buffs_desordenados as $buff) {
        $buffs[$buff["personagem_id"]][] = $buff;
    }

    if ($id_2) {
        $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_special_effect WHERE tripulacao_id = ?", "i", array($id_2))->fetch_all_array();
        foreach ($buffs_desordenados as $buff) {
            $buffs[$buff["personagem_id"]][] = $buff;
        }
    }

    return $buffs;
}

function get_special_effects_bot($id_1, $id) {
    global $connection;
    $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_special_effect WHERE tripulacao_id = ?", "i", array($id_1))->fetch_all_array();
    $buffs = [];
    foreach ($buffs_desordenados as $buff) {
        $buffs[$buff["personagem_id"]][] = $buff;
    }

    $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_special_effect WHERE bot_id = ?", "i", array($id))->fetch_all_array();
    foreach ($buffs_desordenados as $buff) {
        $buffs['bot_' . $buff["personagem_bot_id"]][] = $buff;
    }

    return $buffs;
}

function get_buffs_combate_bot($user_id, $id) {
    global $connection;
    $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_buff WHERE id = ?", "i", array($user_id))->fetch_all_array();
    $buffs = [];
    foreach ($buffs_desordenados as $buff) {
        $buffs[$buff["cod"]][] = $buff;
    }

    $buffs_desordenados = $connection->run("SELECT * FROM tb_combate_buff_bot WHERE id = ?", "i", array($id))->fetch_all_array();
    foreach ($buffs_desordenados as $buff) {
        $buffs['bot_' . $buff["cod"]][] = $buff;
    }

    return $buffs;
}

function nome_tipo_combate($tipo) {
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
            return "Torneio";
        default:
            return "";
    }
}

function calc_redutor_rep_vencedor($rep) {
    return 1 - (pow((0.001 * $rep + 1), (1 / 10)) - 1.0);
}

function calc_redutor_rep_perdedor($rep) {
    return pow((0.001 * $rep + 1), (1 / 20)) - 1.0;
}

function calc_rep_base_no_lvl($lvl) {
    return pow($lvl, 2);
}

function calc_modificador_reputacao($vencedor, $perdedor) {
    if ($vencedor <= 0) {
        return 1;
    }
    $dif = max(0, $perdedor / $vencedor - 0.3);
    return $dif < 1.5 ? $dif : 1.5;
}

function calc_modificador_lvl($vencedor_lvl, $perdedor_lvl) {
    $dif = abs($vencedor_lvl - $perdedor_lvl);
    if ($dif <= 0) {
        return 1;
    } else if ($dif >= 10) {
        return 0;
    } else {
        return 1 - ($dif * 10) / 100;
    }
}

function calc_reputacao($vencedor_rep, $perdedor_rep, $lvl_mais_forte_vencedor, $lvl_mais_forte_perdedor) {
    $rep_base           = calc_rep_base_no_lvl($lvl_mais_forte_perdedor);
    $dif_rep            = calc_modificador_reputacao($vencedor_rep, $perdedor_rep);
    $perdedor_rep       = max(0, $perdedor_rep - 5000);
    $dif_lvl            = $lvl_mais_forte_vencedor >= $lvl_mais_forte_perdedor ? calc_modificador_lvl($lvl_mais_forte_vencedor, $lvl_mais_forte_perdedor) : 1;
    $redutor_vencedor   = calc_redutor_rep_vencedor($vencedor_rep);
    $redutor_perdedor   = calc_redutor_rep_perdedor($perdedor_rep);

    $vencedor_rep = round($rep_base * $dif_rep * $dif_lvl * $redutor_vencedor + 50);
    $perdedor_rep = round($rep_base * $dif_rep * $dif_lvl * $redutor_perdedor);

    return [
        "vencedor_rep"      => $vencedor_rep,
        "perdedor_rep"      => $perdedor_rep,

        "vencedor_rep.new"  => max(0, $perdedor_rep),
        "perdedor_rep.new"  => max(0, $perdedor_rep)
    ];
}

function reduz_score($pers) {
    // np
}

function aumenta_score($pers) {
    global $connection;

    if (!$pers["classe"]) {
        return;
    }
    if ($pers["classe_score"] < 500) {
        $pers["classe_score"] = 500;
    }

    $score = round(pow(1 / ($pers["classe_score"] + 1000), 2) * 1000000000);
    if ($score < 1) {
        $score = 1;
    }
    $score = $pers["classe_score"] + $score;
    $connection->run("UPDATE tb_personagens SET classe_score = '$score' WHERE cod = ?", "i", $pers["cod"]);
}

function calc_recompensa($fa) {
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

function calc_score_mod($classe_score) {
    return ($classe_score / 5000) * 0.01;
}

function min_max($value, $min, $max) {
    if ($value < $min) {
        $value = $min;
    }
    if ($value > $max) {
        $value = $max;
    }
    return $value;
}

function chance_esquiva($pers, $alvo) {
    $pre = $pers["pre"];
    $agl = $alvo["agl"];

    if ($pers["classe"] == 3) {
        $pre += $pers["pre"] * calc_score_mod($pers["classe_score"]);
    }

    $esquiva_haki = max(0, $alvo["haki_esq"] - $pers["haki_esq"]);

    $esquiva = min_max($agl - $pre, 0, 50) + $esquiva_haki;

    return round($esquiva);
}

function chance_crit($pers, $alvo) {
    $dex = $pers["dex"];
    $con = $alvo["con"];

    if ($pers["classe"] == 3) {
        $dex += $pers["dex"] * calc_score_mod($pers["classe_score"]);
    }

    $crit_haki = max(0, $pers["haki_cri"] - $alvo["haki_cri"]);

    $chance_crit = min_max($dex - $con, 0, 50) + $crit_haki;

    return round($chance_crit);
}

function dano_crit($pers, $alvo) {
    $dex = $pers["dex"];
    $con = $alvo["con"];

    if ($pers["classe"] == 3) {
        $dex += $pers["dex"] * calc_score_mod($pers["classe_score"]);
    }

    return (float)min_max($dex - $con, 25, 90) / 100;
}

function chance_bloq($pers, $alvo) {
    $res = $alvo["res"];
    $pre = $pers["pre"];

    $bloq_haki = max(0, $alvo["haki_cri"] - $pers["haki_cri"]);

    $chance_bloq = min_max($res - $pre, 0, 50) + $bloq_haki;

    return round($chance_bloq);
}

function dano_bloq($pers, $alvo) {
    $res = $alvo["res"];
    $pre = $pers["pre"];

    return (float)min_max($res - $pre, 50, 90) / 100;
}

function dano_por_atributo($pers, $alvo) {
    $atk = $pers["atk"];
    $def = $alvo["def"];

    if ($pers["classe"] == 1) {
        $atk += $pers["atk"] * calc_score_mod($pers["classe_score"]);
    }
    if ($alvo["classe"] == 2) {
        $def += $alvo["def"] * calc_score_mod($alvo["classe_score"]);
    }

    return max(0, ($atk - $def) * 10);
}

function calc_dano($pers, $alvo, $dano_hab = 0) {
    $retorno = [
        'esquivou'      => false,
        'dado_esquivou' => 0,
        'critou'        => false,
        'dado_critou'   => 0,
        'critico'       => 0,
        'bloqueou'      => false,
        'dado_bloqueou' => 0,
        'bloqueio'      => 0,
        'dano'          => 0
    ];

    $esquiva    = chance_esquiva($pers, $alvo);

    $retorno["chance_esquiva"] = $esquiva;
    $retorno["dado_esquivou"] = rand(1, 1000) / 10;

    if ($retorno["dado_esquivou"] <= $esquiva) {
        $retorno["esquivou"] = true;
    } else {

        $dano = dano_por_atributo($pers, $alvo) + $dano_hab;

        $chance_crit    = chance_crit($pers, $alvo);

        $retorno["chance_critico"]  = $chance_crit;
        $retorno["dado_critou"]     = rand(1, 1000) / 10;
        if ($retorno["dado_critou"] <= $chance_crit) {
            $retorno["critou"] = true;

            $retorno["critico"] = dano_crit($pers, $alvo);
        }

        $chance_bloq = chance_bloq($pers, $alvo);

        $retorno["chance_bloqueio"] = $chance_bloq;
        $retorno["dado_bloqueou"]   = rand(1, 1000) / 10;
        if ($retorno["dado_bloqueou"] <= $chance_bloq) {
            $retorno["bloqueou"] = true;

            $retorno["bloqueio"] = dano_bloq($pers, $alvo);
        }

        $dano_crit = $retorno["critico"] * $dano;

        $dano += $dano_crit;

        // dano bloqueado é calculado em cima do dano já critado
        $dano_bloq = $retorno["bloqueio"] * $dano;

        $retorno["dano"] = max(1, (int)($dano - $dano_bloq));
    }

    return $retorno;
}

function get_categoria_akuma($cod_akuma) {
    global $connection;

    return $connection->run("SELECT categoria FROM tb_akuma WHERE cod_akuma=?", "i", $cod_akuma)->fetch_array()["categoria"];
}

function calc_mod_akuma_for_cbt($pers, $alvo) {
    if ($pers["akuma"] && $alvo["akuma"]) {
        $categoria_personagem = get_categoria_akuma($pers["akuma"]);
        $categoria_alvo = get_categoria_akuma($alvo["akuma"]);
        return categoria_akuma($categoria_personagem, $categoria_alvo);
    } else {
        return 1;
    }
}

function get_special_effect_classes($special_effects) {
    $effects = [];

    foreach ($special_effects as $effect) {
        $effects[] = "special-effect-" . $effect["special_effect"];
    }

    return implode(" ", $effects);
}

function atacar_rdm($rdm_id, $details = null, $conn = null) {
    if (!$conn) {
        global $connection;
    } else {
        $connection = $conn;
    }

    if (!$details) {
        global $userDetails;
    } else {
        $userDetails = $details;
    }

    $rdms = DataLoader::load("rdm");

    $rdm = $rdms[$rdm_id];

    if (!isset($rdm["boss"])) {
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
            0, 0,
            $rdm["atk"], $rdm["def"], $rdm["agl"], $rdm["res"], $rdm["pre"], $rdm["dex"], $rdm["con"],
            0, 0,
            $rdm["id"], $rdm["boss"], isset($rdm["battle_back"]) ? $rdm["battle_back"] : NULL
        )
    );

    $connection->run("UPDATE tb_usuarios SET mar_visivel = 0, navegacao_destino = NULL, navegacao_inicio = NULL, navegacao_fim = NULL WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));

    insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_p", 0, 4, array(), false, $userDetails, $connection);
}

function is_tanque($pers) {
    return $pers["def"] > 100;
}

function fadiga_batalha_ativa($personagens) {
    $count_tanque = 0;
    $count_ataque = 0;
    foreach ($personagens as $pers) {
        if (is_tanque($pers)) {
            $count_tanque++;
        } else {
            $count_ataque++;
        }
    }

    return $count_tanque > $count_ataque;
}

?>
<?php function render_tabuleiro($tabuleiro, $x1, $x2, $id_blue = NULL, $mira = NULL, $special_effects = array()) { ?>
    <?php global $userDetails; ?>
    <?php if ($id_blue === NULL) {
        $id_blue = $userDetails->tripulacao["id"];
    } ?>
    <table class="table_batalha">
        <?php for ($x = $x1; $x < $x2; $x++): ?>
            <tr>
                <td class="td-mira">
                    <?php if ($mira !== NULL && $mira == $x): ?>
                        <div class="tabuleiro-mira" data-toggle="tooltip"
                             title="O adversário está mirando aqui.">
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <?php for ($y = 0; $y < 20; $y++): ?>
                    <td id="<?= isset($tabuleiro[$x][$y]) ? $tabuleiro[$x][$y]["cod"] : "" ?>"
                        class="<?= $x . "_" . $y; ?> <?= isset($tabuleiro[$x][$y]) ? ($tabuleiro[$x][$y]["tripulacao_id"] == $id_blue ? "personagem-aliado" : "personagem-inimigo") : "" ?>"
                        width="40px" height="40px">
                        <?php if (isset($tabuleiro[$x][$y])): ?>
                            <div class="progress progress-red">
                                <div class="progress-bar progress-bar-success"
                                     style="width: <?= $tabuleiro[$x][$y]["hp"] / $tabuleiro[$x][$y]["hp_max"] * 100 ?>%">
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning"
                                     style="width: <?= $tabuleiro[$x][$y]["mp"] / $tabuleiro[$x][$y]["mp_max"] * 100 ?>%">
                                </div>
                            </div>
                            <img class="<?= (isset($special_effects[$tabuleiro[$x][$y]["cod"]])) ? get_special_effect_classes($special_effects[$tabuleiro[$x][$y]["cod"]]) : "" ?>"
                                 src="Imagens/Personagens/Icons/<?= getImg($tabuleiro[$x][$y], "r") ?>.jpg"
                                 width="36px">
                        <?php endif; ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
    <table class="table_batalha_selecao">
        <?php for ($x = $x1; $x < $x2; $x++): ?>
            <tr>
                <td class="td-mira">
                </td>
                <?php for ($y = 0; $y < 20; $y++): ?>
                    <td id="<?= $x . "_" . $y; ?>"
                        class="selecionavel td-selecao <?= isset($tabuleiro[$x][$y]) ? "personagem" : "" ?> <?= isset($tabuleiro[$x][$y]) ? ($tabuleiro[$x][$y]["tripulacao_id"] == $id_blue ? "aliado" : "inimigo") : "" ?>"
                        width="40px" height="40px" data-x="<?= $x ?>" data-y="<?= $y ?>"
                        data-cod="<?= isset($tabuleiro[$x][$y]) ? $tabuleiro[$x][$y]["cod"] : "" ?>">
                        <img src="Imagens/Icones/selecao.png" whidth="39px" height="39px"/>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
<?php } ?>
<?php function render_personagens_info($personagens_combate, $buffs, $id_blue = NULL, $special_effects = array()) { ?>
    <?php global $userDetails; ?>
    <?php if ($id_blue === NULL) {
        $id_blue = $userDetails->tripulacao["id"];
    } ?>
    <?php foreach ($personagens_combate as $pers): ?>
        <?php if ($pers["hp"]): ?>
            <?php $pers["id"] = $pers["tripulacao_id"]; ?>
            <div class="personagem-info container hidden" id="personagem-info-<?= $pers["cod"] ?>">
                <div class="panel panel-<?= $pers["tripulacao_id"] == $id_blue ? "info" : "danger" ?>">
                    <div class="panel-heading"><?= $pers["nome"]; ?>, <?= $pers["titulo"] ?></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <?= big_pers_skin($pers["img"], $pers["skin_c"], isset($pers["borda"]) ? $pers["borda"] : 0, "tripulante_big_img", 'width="100%"') ?>
                                <br/>
                                <?php if (($userDetails->vip["conhecimento_duracao"] && $pers["tripulacao_id"] == $userDetails->tripulacao["id"]) || $userDetails->tripulacao["adm"]) : ?>
                                    <div>
                                        Score: <?= $pers["classe_score"]; ?>
                                    </div>
                                    <?php if ($pers["akuma"]): ?>
                                        <div>
                                            Akuma no Mi: <?= nome_categoria_akuma($pers["categoria_akuma"]); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($pers["profissao"] == PROFISSAO_MEDICO
                                        || $pers["profissao"] == PROFISSAO_COMBATENTE
                                        || $pers["profissao"] == PROFISSAO_MUSICO
                                    ): ?>
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
                                <?php if (isset($buffs[$pers["cod"]])): ?>
                                    <h4>Buffs</h4>
                                    <?php foreach ($buffs[$pers["cod"]] as $buff): ?>
                                        <div class="text-center">
                                            <img src="Imagens/Icones/<?= nome_atributo_img($buff["atr"]) ?>.png"
                                                 width="25px">
                                            <?= $buff["efeito"] > 0 ? "+" : "" ?><?= $buff["efeito"]; ?>
                                            (<?= $buff["espera"] ?>)
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (isset($special_effects[$pers["cod"]])): ?>
                                    <h4>Estado</h4>
                                    <?php foreach ($special_effects[$pers["cod"]] as $effect): ?>
                                        <div class="text-center">
                                            <?= nome_special_effect($effect["special_effect"]) ?>
                                            (<?= $effect["duracao"] ?>)
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-6">
                                <?php if ($pers["tripulacao_id"] == $userDetails->tripulacao["id"] || $userDetails->tripulacao["adm"]) : ?>
                                    Nível <?= $pers["lvl"] ?>
                                <?php endif; ?>

                                <?php render_personagem_hp_bar($pers); ?>
                                <?php render_personagem_mp_bar($pers); ?>

                                <?php if (($userDetails->vip["conhecimento_duracao"] && $pers["tripulacao_id"] == $userDetails->tripulacao["id"]) || $userDetails->tripulacao["adm"]) : ?>
                                    <?php render_personagem_haki_bars($pers); ?>
                                    <?php render_row_atributo("atk", "Ataque", $pers); ?>
                                    <?php render_row_atributo("def", "Defesa", $pers); ?>
                                    <?php render_row_atributo("pre", "Precisao", $pers); ?>
                                    <?php render_row_atributo("agl", "Agilidade", $pers); ?>
                                    <?php render_row_atributo("res", "Resistencia", $pers); ?>
                                    <?php render_row_atributo("con", "Conviccao", $pers); ?>
                                    <?php render_row_atributo("dex", "Dextreza", $pers); ?>
                                    <?php render_row_atributo("vit", "Vitalidade", $pers); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php } ?>
<?php function render_row_atributo($abr, $img, $pers) { ?>
    <div>
        <img src="Imagens/Icones/<?= $img ?>.png" width="25px">
        <div class="text-center" style="display: inline-block; width:30px">
            <?= $pers[$abr]; ?>
        </div>
    </div>
<?php } ?>
<?php function get_img_combate($log) {
    return ($log["skin_r"] === "npc")
        ? "Imagens/Batalha/Npc/" . $log["img"] . ".png"
        : "Imagens/Personagens/Icons/" . get_img($log, "r") . ".jpg";
} ?>
<?php function render_relatorio_data($relatorio, $id_azul, $avancado = false) { ?>
    <ul class="list-group">
        <?php foreach ($relatorio as $index => $log): ?>
            <?php if ($index > 5) {
                break;
            } ?>
            <li class="list-group-item">
                <?php if ($index == 0): ?>
                    <div class="relatorio-origem-meta-data"
                         data-log='<?= str_replace("'", "", json_encode($log, JSON_NUMERIC_CHECK)); ?>'></div>
                <?php endif; ?>
                <h4>
                    <img src="<?= get_img_combate($log) ?>" height="55px"
                         class="<?= $log["id"] == $id_azul ? "personagem-aliado" : "personagem-inimigo" ?>"/>
                    <?= $log["nome"] ?>
                    <?php if ($log["tipo"] == 0) : ?>
                        se movimentou
                    <?php elseif ($log["tipo"] == 3) : ?>
                        passou a vez
                    <?php else: ?>
                        usou: <img src="Imagens/Skils/<?= $log["img_skil"] ?>.jpg"/> <?= $log["nome_skil"] ?>
                    <?php endif; ?>
                </h4>

                <?php if ($log["tipo"] != 0 && $log["tipo"] != 3) : ?>
                    <p><?= $log["descricao_skil"] ?></p>

                    <?php foreach ($log["afetados"] as $afetado) : ?>
                        <?php if ($index == 0): ?>
                            <div class="relatorio-meta-data"
                                 data-log='<?= str_replace("'", "", json_encode($afetado, JSON_NUMERIC_CHECK)); ?>'></div>
                        <?php endif; ?>
                        <p>
                            <?php if ($afetado["acerto"] == 0): ?>
                                Acertou um quadrado vazio
                            <?php else: ?>
                                <img src="<?= get_img_combate($afetado) ?>" height="40px"
                                     class="<?= $afetado["id"] == $id_azul ? "personagem-aliado" : "personagem-inimigo" ?>"/>
                                <?= $afetado["nome"] ?>
                                <?php if ($afetado["tipo"] == 0): ?>
                                    <?php if ($afetado["esq"] == 1): ?>
                                        <span class="esquiva">Se esquivou</span>
                                        <?php if ($avancado && isset($afetado["resultado"])): ?>
                                            <span class="text-success">Rolou no dado
                                                <?= $afetado["resultado"]["dado_esquivou"] ?>/100 com chance de
                                                <?= $afetado["resultado"]["chance_esquiva"] ?>%</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($avancado && isset($afetado["resultado"])): ?>
                                            <span>Tentou esquivar, mas rolou no dado
                                                <?= $afetado["resultado"]["dado_esquivou"] ?>/100 com chance de
                                                <?= $afetado["resultado"]["chance_esquiva"] ?>%</span>
                                        <?php endif; ?>
                                        <?php if ($afetado["bloq"] == 1): ?>
                                            <span class="bloqueio">Bloqueou</span>
                                            <?php if ($avancado && isset($afetado["resultado"])): ?>
                                                <span class="text-info">Rolou no dado
                                                    <?= $afetado["resultado"]["dado_bloqueou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_bloqueio"] ?>%</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($avancado && isset($afetado["resultado"])): ?>
                                                <span>Tentou bloquear mas rolou no dado
                                                    <?= $afetado["resultado"]["dado_bloqueou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_bloqueio"] ?>%</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        perdeu <?= $afetado["efeito"] ?> pontos de vida
                                        <?php if ($afetado["cri"] == 1): ?>
                                            <span class="critico">Ataque crítico</span>
                                            <?php if ($avancado && isset($afetado["resultado"])): ?>
                                                <span class="text-danger">Rolou no dado
                                                    <?= $afetado["resultado"]["dado_critou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_critico"] ?>%</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($avancado && isset($afetado["resultado"])): ?>
                                                <span>O ataque crítico falhou pois rolou no dado
                                                    <?= $afetado["resultado"]["dado_critou"] ?>/100 com chance de
                                                    <?= $afetado["resultado"]["chance_critico"] ?>%</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($afetado["derrotado"] == 1): ?>
                                            <span class="derrotado">e foi derrotado</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($afetado["tipo"] == 1): ?>
                                    recebeu <?= $afetado["efeito"] ?> pontos em
                                    <?= nome_atributo($afetado["atributo"]) ?>
                                <?php elseif ($afetado["tipo"] == 2): ?>
                                    <?php if (!empty($afetado["cura_h"])): ?>
                                        recebeu <?= $afetado["cura_h"] ?> pontos de vida
                                    <?php endif; ?>
                                    <?php if (!empty($afetado["cura_m"])): ?>
                                        recebeu <?= $afetado["cura_m"] ?> pontos de energia
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
<?php function render_combate_pvp_header($combate, $tripulacao, $id_blue = NULL) { ?>
    <?php global $userDetails; ?>
    <?php if ($id_blue === NULL) {
        $id_blue = $userDetails->tripulacao["id"];
    } ?>
    <div class="row">
        <div class="col-md-5 text-right" style="padding-top: 50px">
            <?= $tripulacao["1"]["tripulacao"] ?>
            <img class="personagem-<?= $tripulacao["1"]["id"] == $id_blue ? "aliado" : "inimigo" ?>"
                 src="Imagens/Bandeiras/img.php?cod=<?= $tripulacao["1"]["bandeira"] ?>&f=<?= $tripulacao["1"]["faccao"] ?>">
        </div>
        <div class="col-md-2">
            <img src="Imagens/Batalha/vs.png"/>
        </div>
        <div class="col-md-5 text-left" style="padding-top: 50px">
            <img class="personagem-<?= $tripulacao["2"]["id"] == $id_blue ? "aliado" : "inimigo" ?>"
                 src="Imagens/Bandeiras/img.php?cod=<?= $tripulacao["2"]["bandeira"] ?>&f=<?= $tripulacao["2"]["faccao"] ?>">
            <?= $tripulacao["2"]["tripulacao"] ?>
        </div>
    </div>
    <h3><?= nome_tipo_combate($combate["tipo"]); ?></h3>
<?php } ?>
<?php function render_combate_pvp_placar($tripulacao, $id_blue = NULL) { ?>
    <?php global $userDetails; ?>
    <?php if ($id_blue === NULL) {
        $id_blue = $userDetails->tripulacao["id"];
    } ?>
    <div class="row">
        <div class="col-md-5 text-right text-<?= $tripulacao["1"]["id"] == $id_blue ? "info" : "danger" ?>">
            <h1><?= $tripulacao["1"]["quant"] ?></h1>
            <?php if (fadiga_batalha_ativa($tripulacao["1"]["personagens"])): ?>
                <img src="Imagens/Batalha/fadiga.jpg"
                     style="border-radius: 5px; border: 2px solid #000;margin-bottom:10px">
                <p>Fadiga de Batalha Ativa!</p>
                <p>Essa tripulação tem muitos tripulantes defensivos em combate. Todos eles receberão 200 pontos de dano
                    no início de cada turno desse jogador.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-5 text-left text-<?= $tripulacao["2"]["id"] == $id_blue ? "info" : "danger" ?>">
            <h1><?= $tripulacao["2"]["quant"] ?></h1>
            <?php if (fadiga_batalha_ativa($tripulacao["2"]["personagens"])): ?>
                <img src="Imagens/Batalha/fadiga.jpg"
                     style="border-radius: 5px; border: 2px solid #000;margin-bottom:10px">
                <p>Fadiga de Batalha Ativa!</p>
                <p>Essa tripulação tem muitos tripulantes defensivos em combate. Todos eles receberão 200 pontos de dano
                    no início de cada turno desse jogador.</p>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
