<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$result = $connection->run(
    "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga) VALUE(?, ?, ?, ?)",
    "isis", array($userDetails->tripulacao["id"], "Tripulação Bot", FACCAO_PIRATA, "010113046758010128123542020122074820")
);

$id = $result->last_id();

$x = 1;
$y = 2;
foreach ($userDetails->personagens as $pers) {
    if ($pers["hp"] > 0) {
        $connection->run(
            "INSERT INTO tb_combate_personagens_bot 
              (combate_bot_id, nome, lvl, img, skin_r, skin_c, hp, hp_max, mp, mp_max, atk, def, agl, res, pre, dex, con, vit, quadro_x, quadro_y, haki_esq, haki_cri, haki_blo, titulo, classe) VALUE 
              (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "isiiiiiiiiiiiiiiiiiiiiisi", array(
                $id, $pers["nome"], $pers["lvl"], $pers["img"], $pers["skin_r"], $pers["skin_c"], $pers["hp"], $pers["hp_max"],
                $pers["mp"], $pers["mp_max"], $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"], $pers["con"], $pers["vit"],
                $x, $y, $pers["haki_esq"], $pers["haki_cri"], $pers["haki_blo"], "O Fake", $pers["classe"]
            )
        );
    }
    $y++;
}

insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_p", 5, 9);

echo "%combate";