<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers_cod = $protector->get_number_or_exit("pers");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}

$xp_necessaria_up = $pers["excelencia_xp_max"] - $pers["excelencia_xp"];

if ($pers["excelencia_xp_max"] > $pers["excelencia_xp"] || $pers["excelencia_lvl"] >= EXCELENCIA_LVL_MAX) {
    $protector->exit_error("Você não pode evoluir este personagem");
}

$protector->need_berries(PRECO_BERRIES_EVOLUIR_EXCELENCIA);

$xp_proximo_lvl = $pers["excelencia_xp_max"] + 45000;

$bonus = get_next_bonus_excelencia($pers["classe"], $pers["excelencia_lvl"]);

$connection->run(
    "UPDATE tb_personagens 
     SET excelencia_xp = 0, 
     excelencia_xp_max = ?, 
     excelencia_lvl = excelencia_lvl + 1,
     atk = atk + ?,
     def = def + ?,
     agl = agl + ?,
     res = res + ?,
     pre = pre + ?,
     dex = dex + ?,
     con = con + ?,
     vit = vit + ?,
     hp_max = hp_max + ?,
     hp = hp_max,
     mp_max = mp_max + ?,
     mp = mp_max
     WHERE cod = ?",
    "iiiiiiiiiiii", array(
        $xp_proximo_lvl,
        $bonus["atk"],
        $bonus["def"],
        $bonus["agl"],
        $bonus["res"],
        $bonus["pre"],
        $bonus["dex"],
        $bonus["con"],
        $bonus["vit"],
        $bonus["hp_max"],
        $bonus["mp_max"],
        $pers_cod
    )
);

$userDetails->reduz_berries(PRECO_BERRIES_EVOLUIR_EXCELENCIA);

$response->send_conquista_pers($pers, $pers["nome"] . " alcançou o nível " . ($pers["excelencia_lvl"] + 1) . " de Excelência!");
