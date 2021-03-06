<?php

if (!$userDetails->tripulacao) {
    echo '{"error":"Você precisa estar logado."}';
    exit;
}
if ($userDetails->missao || $userDetails->tripulacao["recrutando"]) {
    echo '{"error":"Você está ocupado em uma missão neste momento."}';
    exit;
}
if ($userDetails->combate_pve || $userDetails->combate_pvp) {
    $connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
    echo '{"redirect":"combate"}';
    exit;
}
if (!$userDetails->navio) {
    echo '{"error":"Você precisa de um navio."}';
    exit;
}
if (!$userDetails->rotas) {
    echo '{"error":"Você não traçou uma rota."}';
    exit;
}
$rota = $userDetails->rotas[0];
if ($rota["momento"] > atual_segundo()) {
    echo '{"error":"Você não concluiu a navegação."}';
    exit;
}

$destino = $connection->run("SELECT * FROM tb_mapa WHERE x = ? AND y = ? LIMIT  1", "ii", array($rota["x"], $rota["y"]))->fetch_array();

$zona_rdm = DataLoader::load("zona_rdm");

$battle_num = $destino["zona_especial"] ? 100 : $zona_rdm[$destino["zona"]]["chance"];

$battle_num += $userDetails->tripulacao["isca"];

$connection->run("UPDATE tb_usuarios SET isca='0', coup_de_burst_usado = 0 WHERE id = ?", "i", $userDetails->tripulacao["id"]);

$battle_start = rand(1, 100) <= $battle_num;

if ($userDetails->tripulacao["kai"]) {
    $battle_start = FALSE;
}
$connection->run("DELETE FROM tb_rotas WHERE id = ? AND x = ? AND y = ? AND indice = 1 LIMIT 1",
    "iii", array($userDetails->tripulacao["id"], $rota["x"], $rota["y"]));

$xp = $userDetails->navio["xp"] + 1;
if ($xp >= $userDetails->navio["xp_max"]) {
    $xp = 0;
    $lvl = $userDetails->navio["lvl"] + 1;
    if ($lvl < 10) {
        $xp_max = $navio["xp_max"] + 250;
        $connection->run(
            "UPDATE tb_usuario_navio 
            SET xp = ?, xp_max = ?, lvl = ? 
            WHERE id = ?",
            "iiii", array($xp, $xp_max, $lvl, $userDetails->tripulacao["id"])
        );
    }
} else {
    $connection->run(
        "UPDATE tb_usuario_navio 
         SET xp = ? WHERE id = ?",
        "ii", array($xp, $userDetails->tripulacao["id"])
    );
}

if ($userDetails->navegadores) {
    $connection->run(
        "UPDATE tb_personagens SET profissao_xp = profissao_xp + 1 
        WHERE id = ? AND profissao_xp < profissao_xp_max AND profissao = " . PROFISSAO_NAVEGADOR,
        "i", $userDetails->tripulacao["id"]
    );
}

$connection->run("DELETE FROM tb_mapa_contem WHERE id = ?", "i", $userDetails->tripulacao["id"]);

if (rand(1, 100) <= 5) {
    $userDetails->add_item(133, TIPO_ITEM_REAGENT, 1);
}

if (!$battle_start) {
    // entra na GL
    if (
        $userDetails->capitao["lvl"] >= 15
        && (($rota["x"] == 3 && $rota["y"] == 35) ||
            ($rota["x"] == 3 && $rota["y"] == 65) ||
            ($rota["x"] == 198 && $rota["y"] == 66) ||
            ($rota["x"] == 198 && $rota["y"] == 35))
    ) {
        $rota["x"] = 7;
        $rota["y"] = 50;

        $connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", $userDetails->tripulacao["id"]);

        if ($userDetails->capitao["lvl"] < 45) {
            $connection->run("UPDATE tb_usuarios SET res_x = 7, res_y = 49 WHERE id = ?", "i", $userDetails->tripulacao["id"]);
        }
    }

    $dmg = $destino["damage"] * 10;
    $hp_navio = $userDetails->navio["hp"] - $dmg;
    if ($hp_navio < 0) {
        $hp_navio = 0;
    }

    $connection->run("UPDATE tb_usuario_navio SET hp = ? WHERE id = ?",
        "ii", array($hp_navio, $userDetails->tripulacao["id"]));

    if ($hp_navio <= 0) {
        $connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", $userDetails->tripulacao["id"]);

        $rota["x"] = $userDetails->tripulacao["res_x"];
        $rota["y"] = $userDetails->tripulacao["res_y"];
    }

    $direcao = 0;
    if ($rota["x"] < $userDetails->tripulacao["coord_x_navio"] && $rota["y"] < $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 7;
    } else if ($rota["x"] < $userDetails->tripulacao["coord_x_navio"] && $rota["y"] == $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 6;
    } else if ($rota["x"] < $userDetails->tripulacao["coord_x_navio"] && $rota["y"] > $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 5;
    } else if ($rota["x"] == $userDetails->tripulacao["coord_x_navio"] && $rota["y"] < $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 0;
    } else if ($rota["x"] == $userDetails->tripulacao["coord_x_navio"] && $rota["y"] > $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 4;
    } else if ($rota["x"] > $userDetails->tripulacao["coord_x_navio"] && $rota["y"] < $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 1;
    } else if ($rota["x"] > $userDetails->tripulacao["coord_x_navio"] && $rota["y"] == $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 2;
    } else if ($rota["x"] > $userDetails->tripulacao["coord_x_navio"] && $rota["y"] > $userDetails->tripulacao["coord_y_navio"]) {
        $direcao = 3;
    }

    $connection->run("UPDATE tb_usuarios SET coord_x_navio = ?, coord_y_navio = ?, direcao_navio = ? WHERE id = ?",
        "iiii", array($rota["x"], $rota["y"], $direcao, $userDetails->tripulacao["id"]));

    $connection->run("INSERT INTO tb_mapa_contem (x, y, id) VALUES (? , ?, ?)",
        "iii", array($rota["x"], $rota["y"], $userDetails->tripulacao["id"]));

    $connection->run("UPDATE tb_usuarios SET coord_x_navio = ?, coord_y_navio = ? WHERE id = ?",
        "iii", array($rota["x"], $rota["y"], $userDetails->tripulacao["id"]));

    $connection->run("UPDATE tb_rotas SET indice = indice - 1 WHERE id = ?", "i", $userDetails->tripulacao["id"]);

    $rotas = $connection->run("SELECT * FROM tb_rotas WHERE id = ? ORDER BY indice", "i", $userDetails->tripulacao["id"])->fetch_all_array();
    $rota_anterior = $userDetails->rotas[0];
    $tempo_inc = atual_segundo() - 2;// compensa delay de rede
    foreach ($rotas as $rota) {
        $diferenca = $rota["momento"] - $rota_anterior["momento"];

        $tempo_inc += $diferenca;

        $connection->run("UPDATE tb_rotas SET momento = ? WHERE id = ? AND indice = ?",
            "iii", array($tempo_inc, $userDetails->tripulacao["id"], $rota["indice"]));

        $rota_anterior = $rota;
    }

    $userDetails->rotas = count($rotas) ? $rotas : FALSE;
    $userDetails->ilha = $destino;
} else {
    $connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", $userDetails->tripulacao["id"]);

    $rdms = DataLoader::load("rdm");

    $zone_rdms = [];
    $zona = $destino["zona_especial"] ? $destino["zona_especial"] : $destino["zona"];
    foreach ($rdms as $rdm) {
        if (isset($rdm["zona"]) && $rdm["zona"] == $zona) {
            $zone_rdms [] = $rdm;
        }
    }

    $rdm = $zone_rdms[rand(0, count($zone_rdms) - 1)];

    if (isset($rdm["boss"]) && $rdm["boss"]) {
        $boss = $connection->run("SELECT id FROM tb_boss WHERE real_boss_id = ?", "i", $rdm["boss"]);
        if ($boss->count()) {
            $rdm["boss"] = $boss->fetch_array()["id"];
        } else {
            $insert = $connection->run("INSERT INTO tb_boss (real_boss_id, hp) VALUE (?, ?)", "ii", array($rdm["boss"], $rdm["hp"]));
            $rdm["boss"] = $insert->last_id();
        }
    } else {
        $rdm["boss"] = null;
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
		zona, boss_id)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "iisiiiiiiiiiiiiiii", array(
            $userDetails->tripulacao["id"],
            isset($rdm["img"]) ? $rdm["img"] : rand($rdm["img_rand_min"], $rdm["img_rand_max"]),
            $rdm["nome"],
            $rdm["hp"], $rdm["hp"],
            0, 0,
            $rdm["atk"], $rdm["def"], $rdm["agl"], $rdm["res"], $rdm["pre"], $rdm["dex"], $rdm["con"],
            0, 0,
            $rdm["id"], $rdm["boss"]
        )
    );

    insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_p", 0, 4);

    echo '{"redirect":"combate"}';
    exit();
}


