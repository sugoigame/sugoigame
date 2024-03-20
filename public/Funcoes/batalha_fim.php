<?php

function get_personagens_combate($id = null)
{
    global $connection;
    global $userDetails;

    if (! $id) {
        $id = $userDetails->tripulacao["id"];
    }

    return $connection->run(
        "SELECT
          cbtpers.*,
          pers.mp_max AS real_mp_max,
          pers.hp_max AS real_hp_max,
          pers.xp AS real_xp,
          pers.haki_lvl AS haki_lvl,
          pers.lvl AS lvl,
          pers.haki_xp AS haki_xp,
          pers.haki_xp_max AS haki_xp_max,
          pers.haki_pts AS haki_pts,
          pers.haki_count_dias_treino AS haki_count_dias_treino,
          pers.haki_ultimo_dia_treino AS haki_ultimo_dia_treino,
          CURRENT_DATE AS hoje,
          SUBDATE(CURRENT_DATE, INTERVAL 1 DAY) AS ontem
        FROM tb_combate_personagens cbtpers
        INNER JOIN tb_personagens pers ON cbtpers.cod = pers.cod
        WHERE cbtpers.id = ?",
        "i", [$id]
    )->fetch_all_array();
}

function filter_personagens_vivos($pers)
{
    $alive = [];
    foreach ($pers as $per) {
        if ($per["hp"] > 0) {
            $alive[] = $per;
        }
    }
    return $alive;
}

function zera_hp_tripulantes($personagens_in_combate)
{
    global $connection;
    $cods = [];
    foreach ($personagens_in_combate as $pers) {
        $cods[] = $pers["cod"];
    }
    $connection->run("UPDATE tb_personagens SET hp = 0 WHERE cod IN (" . (implode(",", $cods)) . ")");
}

function atualiza_hp_tripulantes($personagens_in_combate)
{
    global $connection;
    foreach ($personagens_in_combate as $pers) {
        $nhp = $pers["hp"];
        if ($nhp > $pers["real_hp_max"]) {
            $nhp = $pers["real_hp_max"];
        }
        $connection->run(
            "UPDATE tb_personagens SET
              hp = '$nhp'
            WHERE cod = ?",
            "i", [$pers["cod"]]
        );
    }
}

function add_rdm_loot($rdm)
{
    global $userDetails;

    if (isset ($rdm["loot"])) {
        $drop = $rdm["loot"][rand(0, count($rdm["loot"]) - 1)];

        $userDetails->add_item($drop["cod"], $drop["tipo"], 1, $drop["tipo"] == TIPO_ITEM_ACESSORIO);
    }
}

function increment_registro_pve()
{
    global $userDetails;
    global $connection;

    $registro_pve = $connection->run("SELECT * FROM tb_pve WHERE id = ? AND zona = ?",
        "ii", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"]));

    if ($registro_pve->count()) {
        $connection->run("UPDATE tb_pve SET quant = quant + 1 WHERE id = ?  AND zona = ?",
            "ii", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"]));
    } else {
        $connection->run("INSERT INTO tb_pve (id, zona, quant) VALUE (?, ?, ?)",
            "iii", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"], 1));
    }
}

function atualiza_missao_alianca_and_calc_xp_bonus()
{
    global $userDetails;
    global $connection;

    if ($userDetails->ally) {
        $missao_ally_result = $connection->run(
            "SELECT * FROM tb_alianca_missoes WHERE cod_alianca = ? AND boss_id IS NULL",
            "i", $userDetails->ally["cod_alianca"]
        );

        if ($missao_ally_result->count()) {
            $missao_ally = $missao_ally_result->fetch_array();

            if ($missao_ally["quant"] < $missao_ally["fim"]) {
                $connection->run("UPDATE tb_alianca_missoes SET quant = quant + 1 WHERE cod_alianca = ?",
                    "i", $userDetails->ally["cod_alianca"]);
                return 1.2;
            }
        }
    }

    return 1;
}

function atualiza_missao_caca()
{
    global $userDetails;
    global $connection;

    if ($userDetails->tripulacao["missao_caca"]) {
        $missoes = DataLoader::load("missoes_caca");
        $missao = $missoes[$userDetails->tripulacao["missao_caca"]];
        if ($missao["objetivo"] == $userDetails->combate_pve["zona"]) {
            $connection->run(
                "UPDATE tb_usuarios SET missao_caca_progress = missao_caca_progress + 1 WHERE id = ?",
                "i", array($userDetails->tripulacao["id"])
            );
        }
    }
}

function registra_chefe_ilha()
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pve["chefe_ilha"]) {
        $connection->run("INSERT INTO tb_missoes_chefe_ilha (tripulacao_id, ilha_derrotado) VALUE (?, ?)",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
    }
}

function registra_chefe_especial()
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pve["chefe_especial"]) {
        $chefe = $connection->run("SELECT * FROM tb_evento_chefes WHERE ilha = ?",
            "i", array($userDetails->ilha["ilha"]));
        if ($chefe->count()) {
            $connection->run("UPDATE tb_evento_chefes SET tripulacao_id = ?, personagem_id = ? WHERE ilha = ?",
                "iii", array($userDetails->tripulacao["id"], $userDetails->capitao["cod"], $userDetails->ilha["ilha"]));
        } else {
            $connection->run("INSERT INTO tb_evento_chefes (ilha, tripulacao_id, personagem_id) VALUE (?, ?, ?)",
                "iii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"], $userDetails->capitao["cod"]));
        }
    }
}

function atualiza_mini_eventos($rdm)
{
    global $userDetails;
    global $connection;

    if (isset ($rdm["mini_evento"]) && $rdm["mini_evento"]) {
        $eventos = DataLoader::load("mini_eventos");

        foreach ($eventos as $id => $evento) {
            if (in_array($rdm["zona"], $evento["zonas"])) {
                $pode_receber = $connection->run(
                    "SELECT * FROM tb_mini_eventos m
                    LEFT JOIN tb_mini_eventos_concluidos c ON m.id = c.mini_evento_id AND c.tripulacao_id = ?
                    WHERE m.id = ? AND c.momento IS NULL",
                    "ii", array($userDetails->tripulacao["id"], $id)
                );

                if ($pode_receber->count()) {
                    $receber = $pode_receber->fetch_array();
                    $recompensas = $evento["recompensas"][$receber["pack_recompensa"]];

                    $connection->run(
                        "INSERT INTO tb_mini_eventos_concluidos (mini_evento_id, tripulacao_id) VALUE (?,?)",
                        "ii", array($id, $userDetails->tripulacao["id"])
                    );

                    foreach ($recompensas as $recompensa) {
                        recebe_recompensa($recompensa, null, false);
                    }
                }
                break;
            }
        }
    }
}

function registra_log_npc()
{
    global $userDetails;
    global $connection;
    global $combate_logger;

    $connection->run(
        "INSERT INTO tb_combate_log_npc (tripulacao_id, rdm_id, relatorio) VALUE (?, ?, ?)",
        "iis", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"], json_encode($combate_logger->get_relatorio_combate_pve()))
    );
}

function remove_batalha_npc()
{
    global $userDetails;
    global $connection;

    $connection->run("DELETE FROM tb_combate_npc WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_buff_npc WHERE tripulacao_id = ?", "i", $userDetails->tripulacao["id"]);
}
function remove_batalha_bot()
{
    global $userDetails;
    global $connection;

    $connection->run("DELETE FROM tb_combate_bot WHERE tripulacao_id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_buff_npc WHERE tripulacao_id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_personagens_bot WHERE combate_bot_id = ?", "i", $userDetails->combate_bot["id"]);
    $connection->run("DELETE FROM tb_combate_buff_bot WHERE id = ?", "i", $userDetails->combate_bot["id"]);
    $connection->run("DELETE FROM tb_combate_special_effect WHERE combate_id = ?", "i", $userDetails->combate_bot["id"]);
    $connection->run("DELETE FROM tb_combate_special_effect WHERE combate_id = ?", "i", $userDetails->combate_bot["id"]);
}

function rouba_carga_mercador()
{
    global $userDetails;
    global $connection;

    if ($userDetails->combate_bot["mercador"]) {
        $carga = $connection->run("SELECT * FROM tb_ilha_mercador WHERE id = ?",
            "i", array($userDetails->combate_bot["mercador"]))->fetch_array();

        if ($carga["quant"]) {
            $max = min($carga["quant"], 3);
            $quant = rand(1, $max);

            $connection->run("UPDATE tb_ilha_mercador SET quant = quant - ? WHERE id = ?",
                "ii", array($quant, $userDetails->combate_bot["mercador"]));

            $userDetails->add_item(CARGA_ROUBADA_ID, TIPO_ITEM_REAGENT, $quant);
        }
    }
}

function atualiza_disputa_ilha()
{
    global $userDetails;
    global $connection;

    if ($userDetails->combate_bot["disputa_ilha"] && $userDetails->ilha["ilha"]) {
        $result = $connection->run("SELECT * FROM tb_ilha_disputa WHERE ilha = ?",
            "i", array($userDetails->ilha["ilha"]));

        if ($result->count()) {
            $disputa = $result->fetch_array();
            $result = $connection->run("SELECT * FROM tb_ilha_disputa_progresso WHERE ilha = ? AND tripulacao_id = ?",
                "ii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"]));

            if ($result->count()) {
                $progresso = $result->fetch_array();
                if ($progresso["progresso"] == 2 && ! $disputa["vencedor_id"]) {
                    if ($userDetails->ilha["ilha_dono"]) {
                        $connection->run("UPDATE tb_ilha_disputa SET vencedor_id = ? WHERE ilha = ?",
                            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
                    } else {
                        $connection->run("UPDATE tb_mapa SET ilha_dono = ? WHERE ilha = ?",
                            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

                        $connection->run("DELETE FROM tb_ilha_disputa WHERE  ilha = ?",
                            "i", array($userDetails->ilha["ilha"]));

                        $connection->run("DELETE FROM tb_ilha_incursao_protecao WHERE  ilha = ?",
                            "i", array($userDetails->ilha["ilha"]));

                        $connection->run("DELETE FROM tb_ilha_disputa_progresso WHERE  ilha = ?",
                            "i", array($userDetails->ilha["ilha"]));
                    }
                    $msg = $userDetails->tripulacao["tripulacao"] . " concluiu a incursão pelo controle de " . nome_ilha($userDetails->ilha["ilha"]);
                    $connection->run(
                        "INSERT INTO tb_news_coo (msg) VALUE (?)",
                        "s", array($msg)
                    );
                    $hora = "às ";
                    $hora .= date("H:i", time());
                    $hora .= " do dia ";
                    $hora .= date("d/m/Y", time());
                    $connection->run("INSERT INTO tb_mensagens (remetente, destinatario, assunto, texto, hora) VALUE (?, ?, ?, ?, ?)",
                        "iisss", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha_dono"], "Estão atacando sua ilha!", $msg, $hora));
                }

                $connection->run("UPDATE tb_ilha_disputa_progresso SET progresso = progresso + 1 WHERE ilha = ? AND tripulacao_id = ?",
                    "ii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"]));
            } else {
                $connection->run("INSERT INTO tb_ilha_disputa_progresso (ilha, tripulacao_id, progresso) VALUE (?,?,1)",
                    "ii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"]));
            }
        }
    }
}

function atualiza_incursao()
{
    global $userDetails;
    global $connection;

    if ($userDetails->combate_bot["incursao"] && $userDetails->ilha["ilha"]) {
        $incursoes = DataLoader::load("incursoes");
        $incursao = $incursoes[$userDetails->ilha["ilha"]];
        $progresso = $userDetails->combate_bot["incursao"] + 1;

        $incursao_progresso = $connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ? AND ilha = ?",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

        if ($incursao_progresso->count()) {
            $incursao_progresso = $incursao_progresso->fetch_array();
            $connection->run("UPDATE tb_incursao_progresso SET progresso = ? WHERE tripulacao_id = ? AND ilha = ?",
                "iii", array($progresso, $userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
        } else {
            $connection->run("INSERT INTO tb_incursao_progresso (tripulacao_id, ilha, progresso) VALUE (?,?,?)",
                "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $progresso));
        }

        $adversario = get_adversario_incursao(isset ($incursao["especial"]) ? 1 : $progresso - 1, $incursao);

        $userDetails->xp_for_all($adversario["xp"]);
        $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
            "ii", array($adversario["berries"], $userDetails->tripulacao["id"]));

        $incursao_nivel = $connection->run("SELECT * FROM tb_incursao_nivel WHERE tripulacao_id = ? AND ilha = ?",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

        $incursao_nivel = $incursao_nivel->count() ? $incursao_nivel->fetch_array() : array("nivel" => 1);

        if ($incursao_nivel["nivel"] == 1) {
            $connection->run("INSERT INTO tb_incursao_nivel (tripulacao_id, ilha, nivel) VALUE (?,?,?)",
                "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $progresso));
        } else if ($progresso > $incursao_nivel["nivel"]) {
            $connection->run("UPDATE tb_incursao_nivel SET nivel = ? WHERE tripulacao_id = ? AND ilha = ?",
                "iii", array($progresso, $userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
        }
    }
}

function atualiza_missao()
{
    global $userDetails;
    global $connection;
    if ($userDetails->missao) {
        $karma = "karma_" . $userDetails->missao["tipo_karma"];

        $karma_reverso = ($karma == "karma_bom") ? "karma_mau" : "karma_bom";

        if ($userDetails->tripulacao[$karma_reverso]) {
            $userDetails->tripulacao[$karma_reverso] -= $userDetails->missao["karma"];

            if ($userDetails->tripulacao[$karma_reverso] < 0) {
                $userDetails->tripulacao[$karma] = abs($userDetails->tripulacao[$karma_reverso]);
                $userDetails->tripulacao[$karma_reverso] = 0;
            }
        } else {
            $userDetails->tripulacao[$karma] += $userDetails->missao["karma"];
        }

        $connection->run("UPDATE tb_usuarios SET karma_bom = ?, karma_mau = ? WHERE id = ?",
            "iii", array($userDetails->tripulacao["karma_bom"], $userDetails->tripulacao["karma_mau"], $userDetails->tripulacao["id"]));

        $concluida = $connection->run("SELECT count(*) AS total FROM tb_missoes_concluidas WHERE id = ? AND cod_missao = ?",
            "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]))->fetch_array()["total"];
        if (! $concluida) {
            $connection->run("INSERT INTO tb_missoes_concluidas (id, cod_missao) VALUES (?, ?)",
                "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]));

            $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
                "ii", array($userDetails->missao["recompensa_berries"], $userDetails->tripulacao["id"]));

            $userDetails->xp_for_all($userDetails->missao["recompensa_xp"]);
        } else {
            $result = $connection->run("SELECT quant FROM tb_missoes_concluidas_dia WHERE tripulacao_id = ? AND ilha = ?",
                "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

            $total_concluido_hoje = $result->count() ? $result->fetch_array()["quant"] : 0;

            if ($total_concluido_hoje) {
                $connection->run("UPDATE tb_missoes_concluidas_dia SET quant = quant + 1 WHERE tripulacao_id = ? AND ilha = ?",
                    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
            } else {
                $connection->run("INSERT INTO tb_missoes_concluidas_dia (tripulacao_id, ilha, quant) VALUE (?,?,1)",
                    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
            }
        }
    }
}

function remove_missao()
{
    global $userDetails;
    global $connection;
    if ($userDetails->missao) {
        $connection->run("DELETE FROM tb_missoes_iniciadas WHERE id = ? AND cod_missao = ?",
            "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]));
    }
}

function check_vitoria_npc($personagens_in_combate)
{
    global $userDetails;
    global $protector;

    if ($userDetails->combate_pve["hp_npc"] == 0) {
        return true;
    }

    $alive = filter_personagens_vivos($personagens_in_combate);

    if (! count($alive)) {
        return false;
    }

    $protector->exit_error("Sua batalha ainda não acabou");
}

function check_vitoria_bot($personagens_in_combate)
{
    global $userDetails;
    global $protector;
    global $connection;

    $enemy_alive_count = $connection->run(
        "SELECT count(id) AS total FROM tb_combate_personagens_bot WHERE combate_bot_id = ? AND hp > 0",
        "i", array($userDetails->combate_bot["id"])
    )->fetch_array()["total"];

    if (! $enemy_alive_count) {
        return true;
    }

    $alive = filter_personagens_vivos($personagens_in_combate);

    if (! count($alive)) {
        return false;
    }

    $protector->exit_error("Sua batalha ainda não acabou");
}

function check_vitoria_pvp($personagens_combate_1, $personagens_combate_2)
{
    global $userDetails;
    global $protector;
    global $connection;

    $alive_1 = filter_personagens_vivos($personagens_combate_1);

    if (count($alive_1)) {
        return "1";
    }

    $alive_2 = filter_personagens_vivos($personagens_combate_2);

    if (count($alive_2)) {
        return "2";
    }

    $protector->exit_error("Sua batalha ainda não acabou");
}

function pvp_tipo_deve_atualizar_hp($tipo)
{
    return false;
}

function is_pvp_competitivo_valido($tipo, $vencedor, $perdedor)
{
    if ($tipo == TIPO_AMIGAVEL) {
        return false;
    }
    if ($_SERVER["HTTP_HOST"] == "localhost") {
        return true;
    }

    return $vencedor["conta_id"] != $perdedor["conta_id"] && $vencedor["ip"] != $perdedor["ip"];
}

function get_batalha_duracao($combate_id)
{
    global $connection;
    return $connection->run(
        "SELECT unix_timestamp(TIMEDIFF(current_timestamp, horario)) - unix_timestamp(current_date) AS duracao FROM tb_combate_log WHERE combate = ?",
        "i", array($combate_id)
    )->fetch_array()["duracao"];
}

function pvp_vale_premio_participacao($duracao, $personagens_vencedor, $personagens_perdedor)
{
    $vivos_vencedor = filter_personagens_vivos($personagens_vencedor);
    $vivos_perdedor = filter_personagens_vivos($personagens_perdedor);

    return $duracao > 10 * 60
        || count($vivos_vencedor) < count($personagens_vencedor) / 2
        || count($vivos_perdedor) < count($personagens_perdedor) / 2;
}

function atualiza_reputacao($vencedor, $perdedor)
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pvp["tipo"] == TIPO_ATAQUE && $perdedor["reputacao"] > 0) {
        $vencedor["reputacao_mensal"] += 1;
        $perdedor["reputacao_mensal"] -= 1;

        $connection->run(
            "UPDATE tb_usuarios SET reputacao_mensal = ? WHERE id = ?",
            "ii", $vencedor["reputacao_mensal"], $vencedor["id"]
        );
        $connection->run(
            "UPDATE tb_usuarios SET reputacao_mensal = ? WHERE id = ?",
            "ii", $perdedor["reputacao_mensal"], $perdedor["id"]
        );

        return [
            "vencedor_rep" => 0,
            "vencedor_rep_mensal" => 1,
            "perdedor_rep" => 0,
            "perdedor_rep_mensal" => -1
        ];
    }

    return [
        "vencedor_rep" => 0,
        "vencedor_rep_mensal" => 0,
        "perdedor_rep" => 0,
        "perdedor_rep_mensal" => 0
    ];
}
function atualiza_battle_points($vencedor, $perdedor, $personagens_vencedor, $personagens_perdedor)
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pvp["tipo"] == TIPO_ATAQUE) {
        $vivos_vencedor = filter_personagens_vivos($personagens_vencedor);

        $connection->run(
            "UPDATE tb_usuarios SET battle_points = battle_points + ?, vitorias = vitorias + 1 WHERE id = ?",
            "ii", array(count($personagens_perdedor) * 20, $vencedor["id"]));

        $connection->run(
            "UPDATE tb_usuarios SET battle_points = battle_points + ?, derrotas = derrotas + 1 WHERE id = ?",
            "ii", array((count($personagens_vencedor) - count($vivos_vencedor)) * 20, $perdedor["id"]));

    }
}

function atualiza_xp_pvp($vencedor, $perdedor, $personagens_vencedor, $personagens_perdedor)
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pvp["tipo"] == TIPO_ATAQUE) {
        $vivos_vencedor = filter_personagens_vivos($personagens_vencedor);

        $connection->run(
            "UPDATE tb_personagens SET xp = xp + ? WHERE id = ?",
            "ii", array(count($personagens_perdedor) * 50, $vencedor["id"]));

        $connection->run(
            "UPDATE tb_personagens SET xp = xp + ? WHERE id = ?",
            "ii", array((count($personagens_vencedor) - count($vivos_vencedor)) * 50, $perdedor["id"]));

    }
}
function atualiza_pontos_coliseu($vencedor, $perdedor, $vale_premio_participacao)
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pvp["tipo"] == TIPO_COLISEU) {
        $vencedor_CP = 200;
        $connection->run("UPDATE tb_usuarios SET coliseu_points = coliseu_points + ?, coliseu_points_edicao = coliseu_points_edicao + ? WHERE id =?",
            "iii", array($vencedor_CP, $vencedor_CP, $vencedor["id"]));

        if ($vale_premio_participacao) {
            $perdedor_CP = 120;
            $connection->run("UPDATE tb_usuarios SET coliseu_points = coliseu_points + ?, coliseu_points_edicao = coliseu_points_edicao + ? WHERE id =?",
                "iii", array($perdedor_CP, $perdedor_CP, $perdedor["id"]));
        }
    }
}

function atualiza_controle_ilha_pvp($vencedor)
{
    global $userDetails;
    global $connection;

    if ($userDetails->combate_pvp["tipo"] == TIPO_CONTROLE_ILHA) {
        $connection->run("UPDATE tb_mapa SET ilha_dono = ? WHERE ilha = ?",
            "ii", array($vencedor["id"], $userDetails->ilha["ilha"]));

        $connection->run("DELETE FROM tb_ilha_disputa WHERE  ilha = ?",
            "i", array($userDetails->ilha["ilha"]));

        $connection->run("DELETE FROM tb_ilha_incursao_protecao WHERE  ilha = ?",
            "i", array($userDetails->ilha["ilha"]));

        $connection->run("DELETE FROM tb_ilha_disputa_progresso WHERE  ilha = ?",
            "i", array($userDetails->ilha["ilha"]));
    }
}

function envia_noticia_pvp($vencedor, $perdedor)
{
    global $userDetails;
    global $connection;
    if ($userDetails->ilha["mar"] > 4) {
        if ($userDetails->combate_pvp["tipo"] == TIPO_COLISEU) {
            $mar = "no Coliseu";
        } elseif ($userDetails->combate_pvp["tipo"] == TIPO_CONTROLE_ILHA) {
            $mar = "pelo controle de " . nome_ilha($userDetails->ilha["ilha"]);
        } elseif ($userDetails->combate_pvp["tipo"] == TIPO_LOCALIZADOR_CASUAL) {
            $mar = "pelo Localizador Casual";
        } elseif ($userDetails->combate_pvp["tipo"] == TIPO_LOCALIZADOR_COMPETITIVO) {
            $mar = "pelo Localizador Competitivo";
        } elseif ($userDetails->combate_pvp["tipo"] == TIPO_TORNEIO) {
            $mar = "na batalha pelo Poneglyph";
        } else {
            $nome_mar = nome_mar($userDetails->ilha["mar"]);
            $mar = $userDetails->ilha["mar"] == 5 ? "na " . $nome_mar : "no " . $nome_mar;
        }
        $connection->run(
            "INSERT INTO tb_news_coo (msg) VALUE (?)",
            "s", array(
                $vencedor["tripulacao"] . " derrotou " . $perdedor["tripulacao"] . " " . $mar
            )
        );
    }
}


function registra_log_pvp($vencedor, $perdedor, $reputacao)
{
    global $userDetails;
    global $connection;
    $connection->run(
        "UPDATE tb_combate_log SET
        vencedor = ?,
        reputacao_ganha = ?,
        reputacao_perdida = ?,
        reputacao_mensal_ganha = ?,
        reputacao_mensal_perdida = ?,
        berries_ganhos = ?,
        berries_perdidos = ?,
        reputacao_anterior_vencedor = ?,
        reputacao_anterior_perdedor = ?,
        reputacao_mensal_anterior_vencedor = ?,
        reputacao_mensal_anterior_perdedor = ?,
        fim = current_timestamp
        WHERE combate = ?",
        "iiiiiiiiiiii", array(
            $vencedor["id"],
            $reputacao["vencedor_rep"],
            $reputacao["perdedor_rep"],
            $reputacao["vencedor_rep_mensal"],
            $reputacao["perdedor_rep_mensal"],
            0,
            0,
            $vencedor["reputacao"],
            $perdedor["reputacao"],
            $vencedor["reputacao_mensal"],
            $perdedor["reputacao_mensal"],
            $userDetails->combate_pvp["combate"]
        )
    );
}

function finaliza_apostas($vencedor)
{
    global $userDetails;
    global $connection;
    if ($userDetails->combate_pvp["premio_apostas"]) {
        $premiados = $connection->run("SELECT * FROM tb_combate_apostas WHERE combate_id = ? AND aposta = ?",
            "ii", array($userDetails->combate_pvp["combate"], $vencedor["id"]))->fetch_all_array();
        $premio = round($userDetails->combate_pvp["premio_apostas"] / (count($premiados) + 1));

        $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
            "ii", array($premio, $vencedor["id"]));

        foreach ($premiados as $premiado) {
            $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
                "ii", array($premio, $premiado["tripulacao_id"]));
        }

    }
}

function remove_batalha_pvp($vencedor, $perdedor)
{
    global $userDetails;
    global $connection;
    $connection->run("DELETE FROM tb_personagens WHERE temporario = 1 AND id IN (?, ?)",
        "ii", array($vencedor["id"], $perdedor["id"]));

    $connection->run("DELETE FROM tb_combate WHERE combate = ?", 'i', $userDetails->combate_pvp["combate"]);

    if ($connection->affected_rows() == 0) {
        return;
    }

    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", 'i', $vencedor["id"]);
    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", 'i', $perdedor["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", 'i', $vencedor["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", 'i', $perdedor["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", 'i', $vencedor["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", 'i', $perdedor["id"]);
    $connection->run("DELETE FROM tb_relatorio WHERE combate = ?", 'i', $userDetails->combate_pvp["combate"]);
    $connection->run("DELETE FROM tb_relatorio_afetados WHERE combate = ?", 'i', $userDetails->combate_pvp["combate"]);
    $connection->run("DELETE FROM tb_combate_apostas WHERE combate_id = ?", 'i', $userDetails->combate_pvp["combate"]);
}
