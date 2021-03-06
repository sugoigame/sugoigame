<?php
function load_equipamentos($pers) {
    global $connection;
    $result = $connection->run("SELECT * FROM tb_personagem_equipamentos WHERE cod = ?", "i", $pers["cod"]);
    if (!$result->count()) {
        $connection->run("INSERT INTO tb_personagem_equipamentos (cod) VALUES (?)", "i", $pers["cod"]);
        $result = $connection->run("SELECT * FROM tb_personagem_equipamentos WHERE cod = ?", "i", $pers["cod"]);
    }
    $equips = $result->fetch_array();

    $equips_info = [];
    $treinos = [];
    for ($slot = 1; $slot <= 8; $slot++) {
        if (isset($equips[$slot])) {
            $result = $connection->run("SELECT * FROM tb_item_equipamentos WHERE cod_equipamento = ?", "i", $equips[$slot]);
            $equips_info[$slot] = $result->count() ? $result->fetch_array() : [];

            $result = $connection->run("SELECT * FROM tb_personagem_equip_treino WHERE cod = ? AND item = ?",
                "ii", array($pers["cod"], $equips_info[$slot]["item"]));

            $treinos[$slot] = $result->count() ? $result->fetch_array() : array("xp" => 0);
            $treinos[$slot]["max"] = 1;
            $treinos[$slot]["porcent"] = 1;
        }
    }

    return array("equips" => $equips, "equips_info" => $equips_info, "treinos" => $treinos);
}

function calc_bonus_equip_atr_principal($equip_info, $nivelamento = false) {
    $lvl = $nivelamento ? 50 : $equip_info["lvl"];
    $categoria = $nivelamento ? max(3, $equip_info["categoria"]) : $equip_info["categoria"];
    $mod_slot = $equip_info["slot"] == 10 ? 2 : 1;
    $upgrade = isset($equip_info["upgrade"]) ? $equip_info["upgrade"] : 0;
    $mod = 10 - $categoria;
    $efeito = ($lvl + $upgrade) / $mod;
    return round($efeito * $mod_slot, 2);
}

function calc_bonus_equip_atr_secundario($equip_info, $nivelamento = false) {
    $lvl = $nivelamento ? 50 : $equip_info["lvl"];
    $categoria = $nivelamento ? max(3, $equip_info["categoria"]) : $equip_info["categoria"];
    $mod_slot = $equip_info["slot"] == 10 ? 2 : 1;
    $upgrade = isset($equip_info["upgrade"]) ? $equip_info["upgrade"] : 0;
    $mod = 11 - $categoria;
    $efeito = ($lvl + $upgrade) / $mod;
    return round($efeito * $mod_slot, 2);
}

function cal_bonus_equip_atributo($equips_info, $treinos, $nivelamento = false) {
    $bonus_total = array("atk" => 0, "def" => 0, "agl" => 0, "pre" => 0, "res" => 0, "dex" => 0, "con" => 0, "vit" => 0);
    for ($slot = 0; $slot <= 8; $slot++) {
        if (isset($equips_info[$slot])) {
            if ($slot != 8 || $equips_info[$slot]["slot"] != 10) {
                if (!empty($equips_info[$slot]["b_1"])) {
                    $efeito = calc_bonus_equip_atr_principal($equips_info[$slot], $nivelamento);
                    $bonus = round($efeito * $treinos[$slot]["porcent"], 2);
                    $bonus_total[nome_atributo_tabela($equips_info[$slot]["b_1"])] += $bonus;
                }
                if (!empty($equips_info[$slot]["b_2"])) {
                    $efeito = calc_bonus_equip_atr_secundario($equips_info[$slot], $nivelamento);
                    $bonus = round($efeito * $treinos[$slot]["porcent"], 2);
                    $bonus_total[nome_atributo_tabela($equips_info[$slot]["b_2"])] += $bonus;
                }
            }
        }
    }

    return $bonus_total;
}

function nome_slot($slot) {
    switch ($slot) {
        case 1:
            return "Cabeça";
        case 2:
            return "Colete";
        case 3:
            return "Calças";
        case 4:
            return "Botas";
        case 5:
            return "Luvas";
        case 6:
            return "Capa";
        case 7:
            return "Primeira mão";
        case 8:
            return "Segunda mão";
        case 9:
            return "Uma mão";
        case 10:
            return "Duas mâos";
        default:
            return "";
    }
}