<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();
$protector->need_tripulacao_alive();

if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]) {
    $protector->exit_error("Você não pode participar da sua incursão");
}

if ($userDetails->ilha["ilha_dono"]) {
    $tripulacao = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?",
        "i", array($userDetails->ilha["ilha_dono"]))->fetch_array();

    if ($tripulacao["conta_id"] == $userDetails->conta["conta_id"] || $tripulacao["ip"] == $userDetails->tripulacao["ip"]) {
        $protector->exit_error("Você não pode participar da sua incursão");
    }
} else {
    $tripulacao = array(
        "tripulacao" => "Disputa por ilha sem dono",
        "bandeira" => "010113046758010128123542010115204020",
        "faccao" => FACCAO_PIRATA
    );
}

$disputa = $connection->run("SELECT * FROM tb_ilha_disputa d LEFT JOIN tb_usuarios u ON d.vencedor_id = u.id WHERE d.ilha = ?",
    "i", array($userDetails->ilha["ilha"]));

if (!$disputa->count()) {
    $protector->exit_error("Essa ilha não está sob disputa");
}

$disputa = $disputa->fetch_array();

if ($disputa["vencedor_id"]) {
    $protector->exit_error("Essa ilha não está sob disputa");
}

$result = $connection->run("SELECT * FROM tb_ilha_disputa_progresso WHERE ilha = ? AND tripulacao_id = ?",
    "ii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"]));

$progresso = $result->count() ? $result->fetch_array() : array("progresso" => 0);


$result = $connection->run(
    "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga, battle_back, disputa_ilha) VALUE(?, ?, ?, ?, 54, 1)",
    "isis", array($userDetails->tripulacao["id"], $tripulacao["tripulacao"], $tripulacao["faccao"], $tripulacao["bandeira"])
);

$id = $result->last_id();

$personagens = DataLoader::load("personagens");
$personagens_incursao = array();
foreach ($personagens as $personagem) {
    if (count($personagens_incursao) >= 15) {
        break;
    }
    if ($personagem["lvl"] == 50) {
        $personagem["skin_r"] = $personagem["skin"];
        $personagem["skin_c"] = $personagem["skin"];
        $personagens_incursao[] = $personagem;
    }
}

$tabuleiro = [];
$bots = [];
sorteia_posicoes($personagens_incursao, array("tatic" => 1), "tatic_d", 0, 4, $bots, $tabuleiro);

foreach ($bots as $pers) {
    $connection->run(
        "INSERT INTO tb_combate_personagens_bot 
          (combate_bot_id, nome, lvl, img, skin_r, skin_c, hp, hp_max, mp, mp_max, atk, def, agl, res, pre, dex, con, vit, quadro_x, quadro_y, haki_esq, haki_cri, titulo, classe, classe_score) VALUE 
          (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "isiiiiiiiiiiiiiiiiiiiisii", array(
            $id, $pers["nome"], $pers["lvl"], $pers["img"], $pers["skin_r"], $pers["skin_c"], $pers["hp_max"], $pers["hp_max"],
            $pers["mp_max"], $pers["mp_max"], $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"], $pers["con"], $pers["vit"],
            $pers["quadro_x"], $pers["quadro_y"], $pers["haki_esq"], $pers["haki_cri"], $pers["titulo"], $pers["classe"], $pers["classe_score"]
        )
    );
}

insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_a", 5, 9);

echo "%combate";