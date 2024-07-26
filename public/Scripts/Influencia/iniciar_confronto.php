<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();

//todo validar se pode mesmo fazer o confronto

$confronto = \Regras\Influencia::generate_confronto($userDetails->tripulacao['nivel_confronto']);

$tripualacao_bot = $confronto["tripulacao"];

$result = $connection->run(
    "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga, battle_back, confronto) VALUE(?, ?, ?, ?, ?, 1)",
    "isisi", array($userDetails->tripulacao["id"], $tripualacao_bot["tripulacao"], $tripualacao_bot["faccao"], $tripualacao_bot["bandeira"], $tripualacao_bot["battle_back"])
);

$id = $result->last_id();

$personagens_bot = $confronto["personagens"];
$tabuleiro = [];
$bots = [];
sorteia_posicoes($personagens_bot, array("tatic" => 1), "tatic_d", 0, 4, $bots, $tabuleiro);

foreach ($bots as $pers) {
    $connection->run(
        "INSERT INTO tb_combate_personagens_bot
          (combate_bot_id, nome, lvl, img, skin_r, skin_c, hp, hp_max, atk, def, agl, res, pre, dex, con, vit, quadro_x, quadro_y, haki_esq, haki_cri, titulo, classe, efeitos) VALUE
          (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "isiiiiiiiiiiiiiiiiiisis", array(
            $id, $pers["nome"], $pers["lvl"], $pers["img"], $pers["skin_r"], $pers["skin_c"], $pers["hp"], $pers["hp_max"],
            $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"], $pers["con"], $pers["vit"],
            $pers["quadro_x"], $pers["quadro_y"], $pers["haki_esq"], $pers["haki_cri"], $pers["titulo"], $pers["classe"], json_encode($pers["efeitos"])
        )
    );
}

$personagens_jogador = $userDetails->personagens;
$bonus = \Regras\Influencia::get_bonus_todas_faccoes();
foreach ($personagens_jogador as $key => $pers) {
    foreach ($bonus as $atr => $valor) {
        $pers[$atr] += round($pers[$atr] * ($valor / 100));
        if ($atr == "hp") {
            $pers["hp_max"] += round($pers["hp_max"] * ($valor / 100));
        }
        if ($atr == "atk") {
            $pers["efeitos"] = [[
                "duracao" => 1000000,
                "explicacao" => "Dano de habilidade aumentado em " . abrevia_numero_grande(round($valor)) . "%",
                "bonus" => [
                    "atr" => "dano_habilidade",
                    "valor" => $valor / 100
                ]
            ]];
        }
    }

    $personagens_jogador[$key] = $pers;
}

insert_personagens_combate($userDetails->tripulacao["id"], $personagens_jogador, $userDetails->vip, "tatic_a", 5, 9);

echo "%combate";
