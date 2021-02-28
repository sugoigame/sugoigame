<?php
require "../../Includes/conectdb.php";
// valida_alvo_valido($alvo_id, $incursao_progresso["progresso"], $incursao_nivel["nivel"])
function valida_alvo_valido($alvo_id, $progresso, $nivel) {
    if ($progresso > $alvo_id/* || $nivel > $alvo_id*/) {
        // echo $progresso . ' > ' . $alvo_id . ' || ' . $nivel . ' > ' . $alvo_id;
        // exit ;
        return false;
    } elseif ($alvo_id <= $progresso) {
        return true;
    } elseif ($alvo_id <= $nivel) {
        //return (($alvo_id % 10) - 1 == 0 && $alvo_id < $nivel * 10 + 1) || (($alvo_id % 3) - 1 == 0 && $alvo_id < $nivel * 3 + 1);
        return true;
    } else {
        return false;
    }
}

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_out_of_rota();

$incursoes = DataLoader::load("incursoes");
$incursao = $incursoes[$userDetails->ilha["ilha"]];

$incursao_nivel = $connection->run("SELECT * FROM tb_incursao_nivel WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

$incursao_nivel = $incursao_nivel->count() ? $incursao_nivel->fetch_array() : array("nivel" => 1);

$alvo_id = isset($incursao["especial"]) ? $incursao_nivel["nivel"] : $protector->get_number_or_exit("alvo");

$incursao_progresso = $connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

$incursao_progresso = $incursao_progresso->count() ? $incursao_progresso->fetch_array() : array("progresso" => 1);

$alvo = isset($incursao["especial"]) ? $incursao["niveis"][1][1] : get_adversario_incursao($alvo_id, $incursao);

if (!$alvo || !valida_alvo_valido($alvo_id, $incursao_progresso["progresso"], $incursao_nivel["nivel"])) {
    $protector->exit_error("Adversário inválido");
}

$tripulacoes = DataLoader::load("tripulacoes");

$tripulacao = $tripulacoes[$alvo["tripulacao"]];

$result = $connection->run(
    "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga, battle_back, incursao) VALUE(?, ?, ?, ?, ?, ?)",
    "isisii", array($userDetails->tripulacao["id"], $tripulacao["nome"], $tripulacao["faccao"], $tripulacao["bandeira"], $alvo["battle_back"], $alvo_id)
);

$id = $result->last_id();

$bots = DataLoader::load("personagens");
$personagens_bot = array();
foreach ($tripulacao["tripulantes"] as $bot_id) {
    $bot = $bots[$bot_id];
    if (isset($incursao["especial"])) {
        for ($x = 1; $x <= 7; $x++) {
            $bot[nome_atributo_tabela($x)] += round($alvo_id * 2);
        }
    }
    $personagens_bot[] = $bot;
}

$tabuleiro = [];
$bots = [];
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

$incursao_personagens_db = $connection->run("SELECT * FROM tb_incursao_personagem WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

$incursao_personagens = array();
while ($incursao_personagem = $incursao_personagens_db->fetch_array()) {
    $incursao_personagens[$incursao_personagem["personagem_id"]] = $incursao_personagem;
}

insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_a", 5, 9);