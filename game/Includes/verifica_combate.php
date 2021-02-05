<?php
$incombate = !!$userDetails->combate_pvp || !!$userDetails->combate_pve;
if ($userDetails->combate_pvp) {
    $usuario["pvp"] = $userDetails->combate_pvp;
    if ($usuario["pvp"]["id_1"] == $usuario["id"]) {
        if ($usuario["pvp"]["saiu_1"] == 1) {
            $incombate = FALSE;
        } else {
            $usuario["pvp"]["id_user"] = $usuario["pvp"]["id_1"];
            $usuario["pvp"]["id_user_"] = "id_1";
            $usuario["pvp"]["move_user_"] = "move_1";
            $usuario["pvp"]["move_user"] = $usuario["pvp"]["move_1"];
            $usuario["pvp"]["id_ini"] = $usuario["pvp"]["id_2"];
            $usuario["pvp"]["id_ini_"] = "id_2";
            $usuario["pvp"]["move_ini_"] = "move_2";
            $usuario["pvp"]["move_ini"] = $usuario["pvp"]["move_2"];
            if ($usuario["pvp"]["vez"] == 1) {
                $vez = "user";
            } else {
                $vez = "ini";
            }
        }
        $saiu_eu = "saiu_1";
    } else if ($usuario["pvp"]["id_2"] == $usuario["id"]) {
        if ($usuario["pvp"]["saiu_2"] == 1) {
            $incombate = FALSE;
        } else {
            $usuario["pvp"]["id_user"] = $usuario["pvp"]["id_2"];
            $usuario["pvp"]["id_user_"] = "id_2";
            $usuario["pvp"]["move_user_"] = "move_2";
            $usuario["pvp"]["move_user"] = $usuario["pvp"]["move_2"];
            $usuario["pvp"]["id_ini"] = $usuario["pvp"]["id_1"];
            $usuario["pvp"]["id_ini_"] = "id_1";
            $usuario["pvp"]["move_ini_"] = "move_1";
            $usuario["pvp"]["move_ini"] = $usuario["pvp"]["move_1"];
            if ($usuario["pvp"]["vez"] == 2) {
                $vez = "user";
            } else {
                $vez = "ini";
            }
        }
        $saiu_eu = "saiu_2";
    }
}

$usuario["npc"] = $userDetails->combate_pve;