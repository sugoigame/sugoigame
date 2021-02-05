<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_in_ilha();
$protector->need_tripulacao_alive();
$protector->must_be_out_of_rota();

$missoes_disponiveis = $connection->run(
    "SELECT  count(ilhamis.cod_missao) AS total
    FROM tb_ilha_missoes ilhamis
    LEFT JOIN tb_missoes_concluidas conc ON ilhamis.cod_missao = conc.cod_missao AND conc.id = ?
    INNER JOIN tb_missoes mis ON ilhamis.cod_missao = mis.cod_missao 
    LEFT JOIN tb_missoes_concluidas misreccon ON mis.requisito_missao = misreccon.cod_missao
    WHERE ilhamis.ilha = ? AND (mis.faccao = '3' OR mis.faccao = ?) AND conc.cod_missao IS NULL 
    GROUP BY mis.cod_missao
    ORDER BY mis.requisito_lvl",
    "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $userDetails->tripulacao["faccao"]))->fetch_array()["total"];

if ($missoes_disponiveis) {
    $protector->exit_error("Você ainda não completou todas as missões dessa ilha");
}

$chefe_derrotado = $connection->run(
    "SELECT count(id) AS total FROM tb_missoes_chefe_ilha WHERE tripulacao_id = ? AND ilha_derrotado = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
)->fetch_array()["total"];

if ($chefe_derrotado) {
    $protector->exit_error("Você já derrotou o chefe dessa ilha.");
}

$chefes_ilha = DataLoader::load("chefes_ilha");

$rdm_id = $chefes_ilha[$userDetails->ilha["ilha"]]["rdm"];

$rdms = DataLoader::load("rdm");

$rdm = $rdms[$rdm_id];

$connection->run(
    "INSERT INTO tb_combate_npc 
        (id, 
        img_npc,
        nome_npc, 
        hp_npc, hp_max_npc, 
        mp_npc, mp_max_npc, 
		atk_npc, def_npc, agl_npc, res_npc, pre_npc, dex_npc, con_npc, 
		dano, armadura, 
		zona, battle_back, chefe_ilha)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
    "iisiiiiiiiiiiiiiiii", array(
        $userDetails->tripulacao["id"],
        isset($rdm["img"]) ? $rdm["img"] : rand($rdm["img_rand_min"], $rdm["img_rand_max"]),
        $rdm["nome"],
        $rdm["hp"], $rdm["hp"],
        0, 0,
        $rdm["atk"], $rdm["def"], $rdm["agl"], $rdm["res"], $rdm["pre"], $rdm["dex"], $rdm["con"],
        0, 0,
        $rdm["id"], $rdm["battle_back"], 1
    )
);

insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_p", 0, 4);

echo "%combate";