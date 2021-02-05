<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_alive();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();

$alvo = $protector->get_number_or_exit("id");

$contem = $connection->run("SELECT * FROM tb_mapa_contem WHERE increment_id = ?", "i", $alvo);

if (!$contem->count()) {
    $protector->exit_error("O alvo não foi encontrado.");
}

$contem = $contem->fetch_array();

if ($contem["mercador_id"] && !can_attack_mercador($contem)) {
    $protector->exit_error("Você não pode atacar este alvo");
}

$mercador = $connection->run(
    "SELECT 
    u.* 
    FROM tb_ilha_mercador me
     INNER JOIN tb_mapa mapa ON me.ilha_origem = mapa.ilha
     INNER JOIN tb_usuarios u ON mapa.ilha_dono = u.id
    WHERE me.id = ?",
    "i", array($contem["mercador_id"])
)->fetch_array();

$result = $connection->run(
    "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga, mercador) VALUE(?, ?, ?, ?, ?)",
    "isisi", array($userDetails->tripulacao["id"], $mercador["tripulacao"], $mercador["faccao"], $mercador["bandeira"], $contem["mercador_id"])
);

$id = $result->last_id();

$personagens = DataLoader::load("personagens");
$personagens_bot = array();
foreach ($personagens as $personagem) {
    if (count($personagens_bot) >= 15) {
        break;
    }
    if ($personagem["lvl"] == 50) {
        $personagem["skin_r"] = $personagem["skin"];
        $personagem["skin_c"] = $personagem["skin"];
        $personagens_bot[] = $personagem;
    }
}

$tabuleiro = [];
$bots = [];
sorteia_posicoes($personagens_bot, array("tatic" => 0), "tatic_d", 0, 4, $bots, $tabuleiro);

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

$connection->run("DELETE FROM tb_mapa_contem WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
$connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", array($userDetails->tripulacao["id"]));

echo "%combate";