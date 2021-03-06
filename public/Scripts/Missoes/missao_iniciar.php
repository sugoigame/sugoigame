<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_in_ilha();
$protector->need_tripulacao_alive();

$cod = $protector->get_number_or_exit("cod");
$tipo = $protector->get_alphanumeric_or_exit("tipo");

function nome_faccao($item) {
    global $userDetails;
    global $tipo;
    $faccao = $userDetails->tripulacao["faccao"];
    return isset($item["nome_" . $tipo . "_" . $faccao]) ? $item["nome_" . $tipo . "_" . $faccao] : $item["nome"];
}

function img_faccao($item) {
    global $userDetails;
    global $tipo;
    $faccao = $userDetails->tripulacao["faccao"];
    return isset($item["img_" . $tipo . "_" . $faccao]) ? $item["img_" . $tipo . "_" . $faccao] : $item["nome"];
}

function skin_faccao($item) {
    global $userDetails;
    global $tipo;
    $faccao = $userDetails->tripulacao["faccao"];
    return isset($item["skin_" . $tipo . "_" . $faccao]) ? $item["skin_" . $tipo . "_" . $faccao] : $item["nome"];
}

if ($tipo != TIPO_KARMA_BOM && $tipo != TIPO_KARMA_MAU) {
    $protector->exit_error("Um dado inválido foi informado.");
}

if ($userDetails->tripulacao["tempo_missao"] > atual_segundo()) {
    $protector->exit_error("Você ainda não pode iniciar a missão");
}

$result = $connection->run("SELECT * FROM tb_ilha_missoes WHERE ilha = ? AND cod_missao = ?",
    "ii", array($userDetails->ilha["ilha"], $cod));

if (!$result->count()) {
    $protector->exit_error("Missao nao encontrada");
}

$concluida = $connection->run("SELECT count(*) AS total FROM tb_missoes_concluidas WHERE id = ? AND cod_missao = ?",
    "ii", array($userDetails->tripulacao["id"], $cod))->fetch_array()["total"];
if ($concluida) {
    $result = $connection->run("SELECT quant FROM tb_missoes_concluidas_dia WHERE tripulacao_id = ? AND ilha = ?",
        "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

    $total_concluido_hoje = $result->count() ? $result->fetch_array()["quant"] : 0;

    if ($total_concluido_hoje >= MAX_MISSOES_ILHA_DIA) {
        $protector->exit_error("Você já atingiu o limite de missões repetidas nessa ilha por hoje.<br/> Viaje para uma nova ilha ou volte aqui amanhã.");
    }
}


$missao = DataLoader::load("missoes")[$cod];
$inimigos = DataLoader::load("inimigos_missao");

if ($userDetails->capitao['lvl'] < $missao["requisito_lvl"]) {
    $protector->exit_error("Seu capitão não tem o nível mínimo para iniciar essa missão.");
}

$connection->run(
    "INSERT INTO tb_missoes_iniciadas (id, cod_missao, fim, log, venceu, hp_final, mp_final, tipo_karma) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
    "iiisisss", array(
        $userDetails->tripulacao["id"],
        $cod,
        atual_segundo(),
        "",
        1,
        "",
        "",
        $tipo
    )
);

if (!$userDetails->tripulacao["missoes_automaticas"]) {
    $battle_back = isset($missao["battle_back"]) ? $missao["battle_back"] : 1;

    $result = $connection->run(
        "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga, battle_back) VALUE(?, ?, ?, ?, ?)",
        "isisi", array($userDetails->tripulacao["id"], "Inimigos da missão $cod", FACCAO_PIRATA, "010113046758010128123542010115204020", $battle_back)
    );

    $id = $result->last_id();

    $personagens_bot = array();
    $pos_x = 1;
    foreach ($missao["inimigos"] as $index => $inimigo_id) {
        $bot = $inimigos[$inimigo_id];
        $bot["nome"] = nome_faccao($bot);
        $bot["img"] = img_faccao($bot);
        $bot["skin"] = skin_faccao($bot);
        $bot["tatic_d"] = isset($bot["tatic_d"]) ? $bot["tatic_d"] : "0";

        // deixa os adversários próximos do jogador
        if ($userDetails->capitao["lvl"] < 5) {
            $bot["tatic_d"] = "4;" . (10 + $pos_x);
            $pos_x *= -1;
            if ($pos_x > 0) {
                $pos_x++;
            }
        }

        $bot["titulo"] = "";
        $bot["vit"] = 1;
        $bot["pack_habilidade_id"] = 1;
        $personagens_bot[] = $bot;
    }

    $tabuleiro = [];
    $bots = [];

    foreach ($personagens_bot as $bot) {
        if ($bot["tatic_d"] != "0") {
            $posicao = explode(";", $bot["tatic_d"]);
            $bot["quadro_x"] = $posicao[0];
            $bot["quadro_y"] = $posicao[1];
            $bots[] = $bot;
            $tabuleiro[$posicao[0]][$posicao[1]] = $bot;
        }
    }

    sorteia_posicoes($personagens_bot, array("tatic" => 1), "tatic_d", 0, 4, $bots, $tabuleiro);

    foreach ($bots as $pers) {
        $connection->run(
            "INSERT INTO tb_combate_personagens_bot 
          (combate_bot_id, nome, lvl, img, skin_r, skin_c, hp, hp_max, mp, mp_max, atk, def, agl, res, pre, dex, con, vit, quadro_x, quadro_y, haki_esq, haki_cri, titulo, classe, classe_score, pack_habilidade_id) VALUE 
          (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "isiiiiiiiiiiiiiiiiiiiisiii", array(
                $id, $pers["nome"], $pers["lvl"], $pers["img"], $pers["skin"], $pers["skin"], $pers["hp"], $pers["hp_max"],
                $pers["mp"], $pers["mp_max"], $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"], $pers["con"], $pers["vit"],
                $pers["quadro_x"], $pers["quadro_y"], $pers["haki_esq"], $pers["haki_cri"], $pers["titulo"], $pers["classe"], $pers["classe_score"], $pers["pack_habilidade_id"]
            )
        );
    }

    if ($userDetails->capitao["lvl"] < 5) {
        $pos_x = 1;
        foreach ($userDetails->personagens as $index => $pers) {
            if ($pers["tatic_a"] == "0") {
                $userDetails->personagens[$index]["tatic_a"] = "5;" . (10 + $pos_x);
                $pos_x *= -1;
                if ($pos_x > 0) {
                    $pos_x++;
                }
            }
        }
        $userDetails->vip["tatic"] = 1;
    }

    insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_a", 5, 9);
}
$fim = atual_segundo() + $missao["duracao"];
$connection->run("UPDATE tb_usuarios SET tempo_missao = ? WHERE id = ?",
    "ii", array($fim, $userDetails->tripulacao["id"]));

echo($userDetails->tripulacao["missoes_automaticas"] ? "%missoes" : "%combate");