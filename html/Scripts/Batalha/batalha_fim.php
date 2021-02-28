<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_combate.php";

$protector->need_tripulacao();

if (!$userDetails->combate_pve && !$userDetails->combate_pvp && !$userDetails->combate_bot) {
    echo("%oceano");
    exit();
}

$combate_logger = new CombateLogger($connection, $userDetails);

if ($userDetails->combate_pve) {
    $venceu = FALSE;
    $perdeu = FALSE;

    if ($userDetails->combate_pve["hp_npc"] == 0) {
        $venceu = TRUE;
    }

    $alive = $connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ? AND hp > 0", "i", $userDetails->tripulacao["id"])->fetch_array();

    if (!$alive["total"]) {
        $perdeu = TRUE;
    }

    if (!$perdeu && !$venceu) {
        $protector->exit_error("Sua batalha ainda não acabou");
    }

    $personagens_in_combate = $connection->run(
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
        "i", $userDetails->tripulacao["id"]
    )->fetch_all_array();

    if ($perdeu) {
        foreach ($personagens_in_combate as $pers) {
            $nmp = $pers["mp"];
            if ($nmp > $pers["real_mp_max"]) {
                $nmp = $pers["real_mp_max"];
            }
            $connection->run("UPDATE tb_personagens SET hp = 0, mp = '$nmp' WHERE cod = ?", "i", $pers["cod"]);
        }
    } else if ($venceu) {
        $rdms = DataLoader::load("rdm");

        $rdm = $rdms[$userDetails->combate_pve["zona"]];

        $xp = $rdm["xp"];

        if (!$userDetails->combate_pve["boss_id"]) {
            if (isset($rdm["loot"])) {
                $drop = $rdm["loot"][rand(0, count($rdm["loot"]) - 1)];

                $userDetails->add_item($drop["cod"], $drop["tipo"], 1, $drop["tipo"] == TIPO_ITEM_ACESSORIO);
            }

            $registro_pve = $connection->run("SELECT * FROM tb_pve WHERE id = ? AND zona = ?",
                "ii", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"]));

            if ($registro_pve->count()) {
                $connection->run("UPDATE tb_pve SET quant = quant + 1 WHERE id = ?  AND zona = ?",
                    "ii", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"]));
            } else {
                $connection->run("INSERT INTO tb_pve (id, zona, quant) VALUE (?, ?, ?)",
                    "iii", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"], 1));
            }

            if ($userDetails->ally) {
                $missao_ally_result = $connection->run("SELECT * FROM tb_alianca_missoes WHERE cod_alianca = ? AND boss_id IS NULL",
                    "i", $userDetails->ally["cod_alianca"]);

                if ($missao_ally_result->count()) {
                    $missao_ally = $missao_ally_result->fetch_array();

                    if ($missao_ally["quant"] < $missao_ally["fim"]) {
                        $connection->run("UPDATE tb_alianca_missoes SET quant = quant + 1 WHERE cod_alianca = ?",
                            "i", $userDetails->ally["cod_alianca"]);
                        $xp *= 1.2;
                    }
                }
            }

            if ($userDetails->tripulacao["missao_caca"]) {
                $missoes = DataLoader::load("missoes_caca");
                $missao = $missoes[$userDetails->tripulacao["missao_caca"]];
                if ($missao["objetivo"] == $userDetails->combate_pve["zona"]) {
                    $connection->run("UPDATE tb_usuarios SET missao_caca_progress = missao_caca_progress + 1 WHERE id = ?",
                        "i", array($userDetails->tripulacao["id"]));
                }
            }

            if ($userDetails->combate_pve["chefe_ilha"]) {
                $connection->run("INSERT INTO tb_missoes_chefe_ilha (tripulacao_id, ilha_derrotado) VALUE (?, ?)",
                    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
            }
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

            if (isset($rdm['haki'])) {
                foreach ($personagens_in_combate as $pers) {
                    if ($pers['haki_ultimo_dia_treino'] != $pers['hoje']) {
                        $haki_pts = calc_pts_mestre_treino_haki_today($pers, $pers['ontem']);
                        $userDetails->add_haki($pers, $haki_pts);
                        $connection->run("UPDATE tb_personagens SET haki_count_dias_treino = ?, haki_ultimo_dia_treino = CURRENT_DATE WHERE cod = ?",
                            "ii", array(calc_ofensiva_mestre_treino_haki($pers, $pers['ontem']) + 1, $pers['cod']));
                    }
                }
            }

            if (isset($rdm["mini_evento"]) && $rdm["mini_evento"]) {
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

                            $connection->run("INSERT INTO tb_mini_eventos_concluidos (mini_evento_id, tripulacao_id) VALUE (?,?)",
                                "ii", array($id, $userDetails->tripulacao["id"]));

                            foreach ($recompensas as $recompensa) {
                                recebe_recompensa($recompensa, NULL, false);
                            }
                        }
                        break;
                    }
                }
            }
        }

        foreach ($personagens_in_combate as $pers) {
            $nmp = $pers["mp"];
            if ($nmp > $pers["real_mp_max"]) {
                $nmp = $pers["real_mp_max"];
            }
            $nhp = $pers["hp"];
            if ($nhp > $pers["real_hp_max"]) {
                $nhp = $pers["real_hp_max"];
            }
            $connection->run(
                "UPDATE tb_personagens SET 
                  hp = '$nhp', 
                  mp = '$nmp'
                WHERE cod = ?",
                "i", $pers["cod"]
            );
        }

        $userDetails->xp_for_all($xp);
    }

    $connection->run(
        "INSERT INTO tb_combate_log_npc (tripulacao_id, rdm_id, relatorio) VALUE (?, ?, ?)",
        "iis", array($userDetails->tripulacao["id"], $userDetails->combate_pve["zona"], json_encode($combate_logger->get_relatorio_combate_pve()))
    );

    $connection->run("DELETE FROM tb_combate_npc WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", "i", $userDetails->tripulacao["id"]);
    $connection->run("DELETE FROM tb_combate_buff_npc WHERE tripulacao_id = ?", "i", $userDetails->tripulacao["id"]);

    if ($userDetails->ally) {
        $in_guerra = $connection->run("SELECT * FROM tb_alianca_guerra WHERE cod_alianca = ? OR cod_inimigo = ?",
            "ii", array($userDetails->ally["cod_alianca"], $userDetails->ally["cod_alianca"]));

        if ($in_guerra->count()) {
            $connection->run("UPDATE tb_usuarios SET mar_visivel = 1 WHERE id = ?",
                "i", array($userDetails->tripulacao["id"]));
        }
    }
} else if ($userDetails->combate_pvp) {
    $result = $connection->run("SELECT * FROM tb_combate_personagens WHERE id = ? AND hp > 0", 'i', $usuario['id']);
    if ($result->count() == 0) {
        $perdeu = TRUE;
        $venceu = FALSE;
    } else {
        $perdeu = FALSE;
        $venceu = TRUE;
    }

    $result = $connection->run("SELECT * FROM tb_combate_personagens WHERE id = ? AND hp > 0", 'i', $usuario["pvp"]["id_ini"]);
    if ($result->count() == 0) {
        $ini_perdeu = TRUE;
        $ini_venceu = FALSE;
    } else {
        $ini_perdeu = FALSE;
        $ini_venceu = TRUE;
    }

    if (!$perdeu AND !$ini_perdeu) {
        echo "%combate";
        exit();
    }

    $inimigo = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", 'i', $usuario["pvp"]["id_ini"])->fetch_array();

    $result = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 1", 'i', $usuario["pvp"]["id_ini"]);
    for ($x = 0; $sql = $result->fetch_array(); $x++)
        $personagem_ini[$x] = $sql;

    for ($x = 0; $x < sizeof($personagem); $x++) {
        $result = $connection->run("SELECT * FROM tb_combate_personagens WHERE cod = ?", 'i', $personagem[$x]["cod"]);
        $personagem_info[$x] = $result->fetch_array();
    }
    for ($x = 0; $x < sizeof($personagem_ini); $x++) {
        $result = $connection->run("SELECT * FROM tb_combate_personagens WHERE cod = ?", 'i', $personagem_ini[$x]["cod"]);
        $personagem_ini_info[$x] = $result->fetch_array();
    }

    if ($perdeu) {
        $vencedor = $inimigo;
        $vencedor_pers = $personagem_ini_info;
        $vencedor_pers_info = $personagem_ini;

        $perdedor = $usuario;
        $perdedor_pers = $personagem_info;
        $perdedor_pers_info = $personagem;
    } else {
        $vencedor = $usuario;
        $vencedor_pers = $personagem_info;
        $vencedor_pers_info = $personagem;

        $perdedor = $inimigo;
        $perdedor_pers = $personagem_ini_info;
        $perdedor_pers_info = $personagem_ini;
    }
    $toda_query = array();

    $reputacao = array("vencedor_rep" => 0, "perdedor_rep" => 0);
    $reputacao_mensal = array("vencedor_rep" => 0, "perdedor_rep" => 0);

    $vencedor_berries = $vencedor["berries"];
    $perdedor_berries = $perdedor["berries"];

    if ($usuario["pvp"]["tipo"] != TIPO_AMIGAVEL && ($_SERVER["HTTP_HOST"] == "localhost" || ($vencedor["conta_id"] != $perdedor["conta_id"] && $vencedor["ip"] != $perdedor["ip"]))) {

        if ($userDetails->combate_pvp["tipo"] != TIPO_LOCALIZADOR_CASUAL) {
            //atualiza hp e mp dos venc
            for ($x = 0; $x < sizeof($vencedor_pers); $x++) {
                if ($vencedor_pers_info[$x]["hp"] > $vencedor_pers[$x]["hp"]) {
                    $nhp = $vencedor_pers[$x]["hp"];
                } else {
                    $nhp = $vencedor_pers_info[$x]["hp"];
                }
                if ($vencedor_pers_info[$x]["mp"] > $vencedor_pers[$x]["mp"]) {
                    $nmp = $vencedor_pers[$x]["mp"];
                } else {
                    $nmp = $vencedor_pers_info[$x]["mp"];
                }

                $query = "UPDATE tb_personagens SET xp = xp + 800 ";

                if ($userDetails->combate_pvp["tipo"] != TIPO_COLISEU
                    && $userDetails->combate_pvp["tipo"] != TIPO_TORNEIO) {
                    $query .= ", hp='$nhp', mp='$nmp' ";
                }

                $query .= "WHERE cod='" . $vencedor_pers[$x]["cod"] . "'";

                $toda_query[sizeof($toda_query)] = $query;
            }

            //atualiza hp e mp dos perd
            for ($x = 0; $x < sizeof($perdedor_pers); $x++) {
                if ($perdedor_pers_info[$x]["mp"] > $perdedor_pers[$x]["mp"]) {
                    $nmp = $perdedor_pers[$x]["mp"];
                } else {
                    $nmp = $perdedor_pers_info[$x]["mp"];
                }
                $query = "UPDATE tb_personagens SET  xp = xp + 400 ";


                $fa_perdida = 1000000;
                if ($userDetails->combate_pvp["tipo"] != TIPO_COLISEU
                    && $userDetails->combate_pvp["tipo"] != TIPO_TORNEIO) {
                    $query .= ", hp='0', mp='$nmp' ";
                    $fa_perdida /= 5;
                }

                if ($perdedor_pers_info[$x]["fama_ameaca"] > 10000000) {
                    $query .= ", fama_ameaca = fama_ameaca - " . ($fa_perdida - (750 * ($perdedor_pers[$x]["vit"] - 1))) . " ";
                }

                $query .= " WHERE cod='" . $perdedor_pers[$x]["cod"] . "'";
                $toda_query[sizeof($toda_query)] = $query;
            }
        }

        if ($usuario["pvp"]["id_1"] == $vencedor["id"])
            $recop = "recop_1";
        else
            $recop = "recop_2";

        $vencedor_berries = $usuario["pvp"][$recop] + $vencedor["berries"];
        $perdedor_berries = $perdedor["berries"];

        $lvl_mais_forte_perdedor = 0;
        foreach ($perdedor_pers_info as $pers) {
            if ($pers["lvl"] > $lvl_mais_forte_perdedor) {
                $lvl_mais_forte_perdedor = $pers["lvl"];
            }
        }

        $lvl_mais_forte_vencedor = 0;
        foreach ($vencedor_pers_info as $pers) {
            if ($pers["lvl"] > $lvl_mais_forte_vencedor) {
                $lvl_mais_forte_vencedor = $pers["lvl"];
            }
        }

        $reputacao = calc_reputacao($vencedor["reputacao"], $perdedor["reputacao"], $lvl_mais_forte_vencedor, $lvl_mais_forte_perdedor);
        $reputacao_mensal = calc_reputacao($vencedor["reputacao_mensal"], $perdedor["reputacao_mensal"], $lvl_mais_forte_vencedor, $lvl_mais_forte_perdedor);

        if ($usuario["pvp"]["tipo"] == 2) {
            $reputacao["vencedor_rep"] /= 2;
            $reputacao["perdedor_rep"] /= 2;

            $reputacao_mensal["vencedor_rep"] /= 2;
            $reputacao_mensal["perdedor_rep"] /= 2;

            $porcentagem = 0.1;
            if ($aumento = $userDetails->buffs->get_efeito_from_tripulacao("aumento_berries_saque", $vencedor["id"])) {
                $porcentagem += $aumento;
            }

            $saque = (int)($perdedor["berries"] * $porcentagem);
            $vencedor_berries += $saque;
            $perdedor_berries -= $saque;
        } else if ($userDetails->combate_pvp["tipo"] == TIPO_LOCALIZADOR_COMPETITIVO) {
            $reputacao["vencedor_rep"] /= 3;
            $reputacao["perdedor_rep"] /= 3;

            $reputacao_mensal["vencedor_rep"] /= 3;
            $reputacao_mensal["perdedor_rep"] /= 3;
        }

        $vencedor_rep = $reputacao["vencedor_rep"];
        $perdedor_rep = $perdedor["reputacao"] - $reputacao["perdedor_rep"];

        $vencedor_rep_mensal = $reputacao_mensal["vencedor_rep"];
        $perdedor_rep_mensal = $perdedor["reputacao_mensal"] - $reputacao_mensal["perdedor_rep"];

        if ($perdedor_rep < 0) {
            $perdedor_rep = 0;
        }

        if ($perdedor_rep_mensal < 0) {
            $perdedor_rep_mensal = 0;
        }

        $vencedor_vit = $vencedor["vitorias"] + 1;

        if ($userDetails->combate_pvp["tipo"] != TIPO_COLISEU
            && $userDetails->combate_pvp["tipo"] != TIPO_TORNEIO
            && $userDetails->combate_pvp["tipo"] != TIPO_LOCALIZADOR_CASUAL) {
            $toda_query[sizeof($toda_query)] = "UPDATE tb_usuarios " .
                "SET reputacao='$perdedor_rep', reputacao_mensal = '$perdedor_rep_mensal', berries='$perdedor_berries' " .
                "WHERE id='" . $perdedor["id"] . "'";

            //add reputacao ao vencedor
            $toda_query[sizeof($toda_query)] = "UPDATE tb_usuarios SET " .
                "reputacao= reputacao +'$vencedor_rep', vitorias = '$vencedor_vit', reputacao_mensal = reputacao_mensal + '$vencedor_rep_mensal', berries='$vencedor_berries'  " .
                "WHERE id='" . $vencedor["id"] . "'";
        }

        //guerra
        $result = $connection->run("SELECT * FROM tb_alianca_membros WHERE id = ?", 'i', $vencedor["id"]);
        if ($result->count() != 0) {
            $alianca_vencedor = $result->fetch_array();

            $result = $connection->run("SELECT * FROM tb_alianca_membros WHERE id = ?", 'i', $perdedor["id"]);
            if ($result->count() != 0) {
                $alianca_perdedor = $result->fetch_array();

                $result = $connection->run("SELECT * FROM tb_alianca_guerra  WHERE cod_alianca = ? AND cod_inimigo = ?", 'ii', [
                    $alianca_vencedor["cod_alianca"],
                    $alianca_perdedor["cod_alianca"]
                ]);
                $guerra = $result->fetch_array();
                $cont   = $result->count();

                $result = $connection->run("SELECT * FROM tb_alianca_guerra  WHERE cod_inimigo = ? AND cod_alianca = ?", 'ii', [
                    $alianca_vencedor["cod_alianca"],
                    $alianca_perdedor["cod_alianca"]
                ]);
                $guerra_inimigo = $result->fetch_array();
                $cont           += $result->count();

                if ($cont > 0) {
                    if ($guerra["pts"] < $guerra["vitoria"] AND $guerra_inimigo["pts"] < $guerra["vitoria"]) {
                        $pts = $guerra["pts"] + 1;
                        $toda_query[sizeof($toda_query)] = "UPDATE tb_alianca_guerra SET pts='$pts' 
							WHERE cod_alianca='" . $alianca_vencedor["cod_alianca"] . "'";

                        $result = $connection->run("SELECT * FROM tb_alianca_guerra_ajuda  WHERE id = ? AND cod_alianca = ?", 'ii', [
                            $vencedor['id'],
                            $alianca_vencedor['cod_alianca']
                        ]);
                        if ($result->count() == 0) {
                            $toda_query[sizeof($toda_query)] = "INSERT INTO tb_alianca_guerra_ajuda (cod_alianca, id, quant) 
								VALUES ('" . $alianca_vencedor["cod_alianca"] . "', '" . $vencedor["id"] . "', '1')";
                        } else {
                            $quant = $result->fetch_array();
                            $quant = $quant["quant"] + 1;
                            $toda_query[sizeof($toda_query)] = "UPDATE tb_alianca_guerra_ajuda SET quant='$quant' 
								WHERE id='" . $vencedor["id"] . "' AND cod_alianca='" . $alianca_vencedor["cod_alianca"] . "'";
                        }
                    }
                }
            }
        }

        $perdedor_pers_count = $connection->run("SELECT count(*) AS total FROM tb_combate_personagens WHERE id = ?",
            "i", array($perdedor["id"]))->fetch_array()["total"];
        $vencedor_pers_count = $connection->run("SELECT count(*) AS total FROM tb_combate_personagens WHERE id = ?",
            "i", array($vencedor["id"]))->fetch_array()["total"];
        $perdedor_derrotados = $connection->run("SELECT count(*) AS total FROM tb_combate_personagens WHERE hp <= 0 AND desistencia = 0 AND id = ?",
            "i", array($perdedor["id"]))->fetch_array()["total"];
        $vencedor_derrotados = $connection->run("SELECT count(*) AS total FROM tb_combate_personagens WHERE hp <= 0 AND desistencia = 0 AND id = ?",
            "i", array($vencedor["id"]))->fetch_array()["total"];
        $duracao = $connection->run("SELECT unix_timestamp(TIMEDIFF(current_timestamp, horario)) - unix_timestamp(current_date) AS duracao FROM tb_combate_log WHERE combate = ?",
            "i", array($userDetails->combate_pvp["combate"]))->fetch_array()["duracao"];

        $premio_participacao = $perdedor_derrotados >= $perdedor_pers_count / 2
            || $vencedor_derrotados >= $vencedor_pers_count / 2
            || $duracao > 10 * 60;

        if ($userDetails->combate_pvp["tipo"] == TIPO_COLISEU) {
            $vencedor_CP = 200;
            $connection->run("UPDATE tb_usuarios SET coliseu_points = coliseu_points + ?, coliseu_points_edicao = coliseu_points_edicao + ? WHERE id =?",
                "iii", array($vencedor_CP, $vencedor_CP, $vencedor["id"]));

            if ($premio_participacao) {
                $perdedor_CP = 120;
                $connection->run("UPDATE tb_usuarios SET coliseu_points = coliseu_points + ?, coliseu_points_edicao = coliseu_points_edicao + ? WHERE id =?",
                    "iii", array($perdedor_CP, $perdedor_CP, $perdedor["id"]));
            }
        }
        if (($userDetails->combate_pvp["tipo"] == TIPO_ATAQUE || $userDetails->combate_pvp["tipo"] == TIPO_SAQUE)
            && $perdedor_derrotados <= 4) {
            $connection->run("UPDATE tb_usuarios SET battle_points = battle_points + ? WHERE id = ?",
                "ii", array(100 - ($perdedor_derrotados * 20), $userDetails->tripulacao["id"]));
        }
        if ($userDetails->combate_pvp["tipo"] == TIPO_LOCALIZADOR_CASUAL) {
            $userDetails->add_item(193, TIPO_ITEM_REAGENT, 1, false, $vencedor["id"]);

            if ($premio_participacao) {
                $userDetails->add_item(194, TIPO_ITEM_REAGENT, 1, false, $perdedor["id"]);
            }
        }

        $userDetails->add_item(134, TIPO_ITEM_REAGENT, 1, false, $vencedor["id"]);
        $userDetails->add_item(134, TIPO_ITEM_REAGENT, 1, false, $perdedor["id"]);

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

        if ($userDetails->combate_pvp["tipo"] == TIPO_TORNEIO) {
            $vencedor_torneio = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ?",
                "i", array($vencedor["id"]))->fetch_array();
            $perdedor_torneio = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ?",
                "i", array($perdedor["id"]))->fetch_array();

            $connection->run("INSERT INTO tb_torneio_rodadas (tripulacao_id, rodada, status, adversario_id) VALUE (?,?,?,?)",
                "iiii", array($vencedor["id"], $vencedor_torneio["rodada"] + 1, 1, $perdedor["id"]));
            $connection->run("INSERT INTO tb_torneio_rodadas (tripulacao_id, rodada, status, adversario_id) VALUE (?,?,?,?)",
                "iiii", array($perdedor["id"], $perdedor_torneio["rodada"] + 1, 0, $vencedor["id"]));

            $connection->run("UPDATE tb_torneio_inscricao SET pontos = pontos + 1 WHERE tripulacao_id = ?", "i", array($vencedor["id"]));
            $connection->run("UPDATE tb_torneio_inscricao SET rodada = rodada + 1 WHERE tripulacao_id = ? OR tripulacao_id = ?", "ii", array($vencedor["id"], $perdedor["id"]));
        }

        // envia a noticia para todos
        if ($userDetails->ilha["mar"] > 4) {
            if ($userDetails->combate_pvp["tipo"] == TIPO_COLISEU) {
                $mar = "no Coliseu";
            } else if ($userDetails->combate_pvp["tipo"] == TIPO_CONTROLE_ILHA) {
                $mar = "pelo controle de " . nome_ilha($userDetails->ilha["ilha"]);
            } else if ($userDetails->combate_pvp["tipo"] == TIPO_LOCALIZADOR_CASUAL) {
                $mar = "pelo Localizador Casual";
            } else if ($userDetails->combate_pvp["tipo"] == TIPO_LOCALIZADOR_COMPETITIVO) {
                $mar = "pelo Localizador Competitivo";
            } else if ($userDetails->combate_pvp["tipo"] == TIPO_TORNEIO) {
                $mar = "no Torneio PvP";
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

    $dif_berries_vencedor = $vencedor_berries - $vencedor["berries"];
    $dif_berries_perdedor = $perdedor["berries"] - $perdedor_berries;

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
            $reputacao_mensal["vencedor_rep"],
            $reputacao_mensal["perdedor_rep"],
            $dif_berries_vencedor,
            $dif_berries_perdedor,
            $vencedor["reputacao"],
            $perdedor["reputacao"],
            $vencedor["reputacao_mensal"],
            $perdedor["reputacao_mensal"],
            $userDetails->combate_pvp["combate"]
        )
    );

    for ($x = 0; $x < sizeof($toda_query); $x++) {
        $connection->query($toda_query[$x]);
    }

    // apostas
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

    $connection->run("DELETE FROM tb_personagens WHERE temporario = 1 AND id IN (?, ?)",
        "ii", array($vencedor["id"], $perdedor["id"]));

    $connection->run("DELETE FROM tb_combate WHERE id_1 = ?", 'i', $usuario["pvp"]["id_1"]);
    if ($connection->affected_rows() == 0) {
        echo "";
        exit();
    }

    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", 'i', $vencedor["id"]);
    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", 'i', $perdedor["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", 'i', $vencedor["id"]);
    $connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ?", 'i', $perdedor["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", 'i', $vencedor["id"]);
    $connection->run("DELETE FROM tb_combate_buff WHERE id = ?", 'i', $perdedor["id"]);
    $connection->run("DELETE FROM tb_relatorio WHERE combate = ?", 'i', $usuario["pvp"]["combate"]);
    $connection->run("DELETE FROM tb_relatorio_afetados WHERE combate = ?", 'i', $usuario["pvp"]["combate"]);
    $connection->run("DELETE FROM tb_combate_apostas WHERE combate_id = ?", 'i', $usuario["pvp"]["combate"]);
} else if ($userDetails->combate_bot) {
    $venceu = FALSE;
    $perdeu = FALSE;

    $alive = $connection->run("SELECT count(id) AS total FROM tb_combate_personagens_bot WHERE combate_bot_id = ? AND hp > 0",
        "i", array($userDetails->combate_bot["id"]))->fetch_array();

    if (!$alive["total"]) {
        $venceu = TRUE;
    }

    $alive = $connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ? AND hp > 0",
        "i", array($userDetails->tripulacao["id"]))->fetch_array();

    if (!$alive["total"]) {
        $perdeu = TRUE;
    }

    if (!$perdeu && !$venceu) {
        $protector->exit_error("Sua batalha ainda não acabou");
    }

    $personagens_in_combate = $connection->run(
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
        "i", $userDetails->tripulacao["id"]
    )->fetch_all_array();

    if ($perdeu) {
        foreach ($personagens_in_combate as $pers) {
            $nmp = $pers["mp"];
            if ($nmp > $pers["real_mp_max"]) {
                $nmp = $pers["real_mp_max"];
            }
            $connection->run("UPDATE tb_personagens SET hp = 0, mp = '$nmp' WHERE cod = ?", "i", $pers["cod"]);
        }
    } else if ($venceu) {
        foreach ($personagens_in_combate as $pers) {
            $nmp = $pers["mp"];
            if ($nmp > $pers["real_mp_max"]) {
                $nmp = $pers["real_mp_max"];
            }
            $nhp = $pers["hp"];
            if ($nhp > $pers["real_hp_max"]) {
                $nhp = $pers["real_hp_max"];
            }
            $nxp = $pers["real_xp"] + 150;
            $connection->run(
                "UPDATE tb_personagens SET 
                  hp = '$nhp', 
                  mp = '$nmp',
                  xp = '$nxp'
                WHERE cod = ?",
                "i", $pers["cod"]
            );
        }

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

        if ($userDetails->combate_bot["disputa_ilha"] && $userDetails->ilha["ilha"]) {
            $result = $connection->run("SELECT * FROM tb_ilha_disputa WHERE ilha = ?",
                "i", array($userDetails->ilha["ilha"]));

            if ($result->count()) {
                $disputa = $result->fetch_array();
                $result = $connection->run("SELECT * FROM tb_ilha_disputa_progresso WHERE ilha = ? AND tripulacao_id = ?",
                    "ii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"]));

                if ($result->count()) {
                    $progresso = $result->fetch_array();
                    if ($progresso["progresso"] == 2 && !$disputa["vencedor_id"]) {
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

        if ($userDetails->combate_bot["haki"]) {
            $userDetails->haki_for_all($userDetails->combate_bot["haki"] * 21000);
            for ($x = 0; $x < $userDetails->combate_bot["haki"]; $x++) {
                $connection->run("INSERT INTO tb_haki_treino (tripulacao_id) VALUE (?)", "i", array($userDetails->tripulacao["id"]));
            }
        }

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

            $adversario = get_adversario_incursao(isset($incursao["especial"]) ? 1 : $progresso - 1, $incursao);

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
    if ($userDetails->missao) {
        $connection->run("DELETE FROM tb_missoes_iniciadas WHERE id = ? AND cod_missao = ?",
            "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]));

        if ($venceu) {
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
            if (!$concluida) {
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

if ($venceu) {
    if ($userDetails->combate_pve && isset($rdm['haki'])) {
        echo("%haki");
    } else if ($userDetails->combate_bot && $userDetails->combate_bot["incursao"]) {
        echo("%incursao");
    } else if ($userDetails->combate_bot && $userDetails->combate_bot["disputa_ilha"]) {
        echo("%politicaIlha");
    } else if ($userDetails->combate_pve && $userDetails->combate_pve["chefe_especial"]) {
        echo("%eventoChefesIlhas");
    } else if ($userDetails->missao) {
        echo "%missoes";
    } else {
        echo("%oceano");
    }
} else if ($perdeu) {
    echo("%respawn");
} else
    echo "A luta ainda não acabou!";