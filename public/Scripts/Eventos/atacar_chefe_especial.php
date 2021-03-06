<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_in_ilha();
$protector->must_be_out_of_rota();

$chefes = DataLoader::load("chefes_ilha");
$chefe = $chefes[$userDetails->ilha["ilha"]];
$rdms = DataLoader::load("rdm");
$rdm = $rdms[$chefe["rdm"]];

$chefe = $connection->run("SELECT * FROM tb_evento_chefes ec INNER JOIN tb_personagens p ON ec.personagem_id = p.cod WHERE ec.ilha = ?",
    "i", array($userDetails->ilha["ilha"]));
if ($chefe->count()) {
    $player = $chefe->fetch_array();
    if ($player["tripulacao_id"] == $userDetails->tripulacao["id"]) {
        $protector->exit_error("Você não pode enfrentar a si mesmo na ilha. Vá até outra ilha ou espere outro jogador tomar o seu lugar nessa ilha.");
    }

    $player["battle_back"] = $rdm["battle_back"];
    $player["hp"] = $player["hp_max"] * 10;
    $rdm = $player;
}
if (!isset($rdm["skin_c"])) {
    $rdm["skin_c"] = NULL;
}

$connection->run(
    "INSERT INTO tb_combate_npc 
        (id, 
        img_npc,
        nome_npc, 
        hp_npc, hp_max_npc, 
        mp_npc, mp_max_npc, 
		atk_npc, def_npc, agl_npc, res_npc, pre_npc, dex_npc, con_npc, 
		dano, armadura, 
		zona, battle_back, skin_npc, chefe_especial)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
    "iisiiiiiiiiiiiiiiiii", array(
        $userDetails->tripulacao["id"],
        isset($rdm["img"]) ? $rdm["img"] : rand($rdm["img_rand_min"], $rdm["img_rand_max"]),
        $rdm["nome"],
        $rdm["hp"], $rdm["hp"],
        0, 0,
        $rdm["atk"], $rdm["def"], $rdm["agl"], $rdm["res"], $rdm["pre"], $rdm["dex"], $rdm["con"],
        0, 0,
        9998, $rdm["battle_back"], $rdm["skin_c"], 1
    )
);

insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_p", 0, 4);

$connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
$connection->run("DELETE FROM tb_mapa_contem WHERE id = ?", "i", array($userDetails->tripulacao["id"]));

echo "%combate";