<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

$pers_cod = $protector->get_number_or_exit("pers");
$quadro = $protector->get_test_pass_or_exit("quadro", "/^[0-9a-zA-Z;_]+$/");

$personagem = $userDetails->get_pers_by_cod($pers_cod, true);

if (!$personagem) {
    $protector->exit_error("Personagem invalido");
}

$machucado_joelho = $connection->run("SELECT * FROM tb_combate_special_effect WHERE personagem_id = ? AND special_effect = ?",
    "ii", array($pers_cod, SPECIAL_EFFECT_MACHUCADO_JOELHO));

if ($machucado_joelho->count()) {
    $protector->exit_error("Esse personagem etá impossibilitado de se mover devido a um efeito especial");
}

$result = $connection->run("SELECT * FROM tb_combate_personagens WHERE cod = ?",
    "i", array($pers_cod));

if (!$result->count()) {
    $protector->exit_error("Personagem fora de combate");
}

$personagem_combate = $result->fetch_array();

if (!$personagem_combate["hp"]) {
    $protector->exit_error("Este personagem foi derrotado e não pode se mover");
}

$quadro = explode("_", $quadro);
$quadro_x = (int)$quadro[0];
$quadro_y = (int)$quadro[1];

$custo_movimento = max(abs($quadro_x - $personagem_combate["quadro_x"]), abs($quadro_y - $personagem_combate["quadro_y"]));

if ($userDetails->combate_pve) {
    if ($userDetails->combate_pve["move"] < $custo_movimento) {
        $protector->exit_error("Nessa batalha contra NPC seus movimentos acabaram");
    }
} else if ($userDetails->combate_pvp) {
    if ($userDetails->combate_pvp["id_1"] == $userDetails->tripulacao["id"]) {
        if ($userDetails->combate_pvp["move_1"] < $custo_movimento) {
            $protector->exit_error("Você não pode se movimentar");
        }
    } else if ($userDetails->combate_pvp["id_2"] == $userDetails->tripulacao["id"]) {
        if ($userDetails->combate_pvp["move_2"] < $custo_movimento) {
            $protector->exit_error("Seus movimentos acabaram, você não pode se movimentar");
        }
    }
} else if ($userDetails->combate_bot) {
    if ($userDetails->combate_bot["vez"] != 1 || $userDetails->combate_bot["move"] < $custo_movimento) {
        $protector->exit_error("Nessa batalha contra Bot seus movimentos acabaram");
    }
}

if ($userDetails->combate_pvp) {
    $result = $connection->run("SELECT * FROM tb_combate_personagens WHERE quadro_x = ? AND quadro_y = ? AND (id = ? OR id = ?)",
        "iiii", array($quadro_x, $quadro_y, $userDetails->combate_pvp["id_1"], $userDetails->combate_pvp["id_2"]));
} else {
    $result = $connection->run("SELECT * FROM tb_combate_personagens WHERE quadro_x = ? AND quadro_y = ? AND id = ?",
        "iii", array($quadro_x, $quadro_y, $userDetails->tripulacao["id"]));
}

if ($result->count()) {
    $incoord = $result->fetch_array();
    if ($incoord["hp"] > 0) {
        $protector->exit_error("Coordenada ocupada");
    }
}

if ($userDetails->combate_bot) {
    $result = $connection->run("SELECT * FROM tb_combate_personagens_bot WHERE quadro_x = ? AND quadro_y = ? AND id = ?",
        "iii", array($quadro_x, $quadro_y, $userDetails->combate_bot["id"]));

    if ($result->count()) {
        $incoord = $result->fetch_array();
        if ($incoord["hp"] > 0) {
            $protector->exit_error("Coordenada ocupada");
        }
    }
}

if ($userDetails->combate_pve) {
    if ($quadro_x < 5 AND $quadro_x >= 0 AND $quadro_y < 20 AND $quadro_y >= 0) {
        $connection->run("UPDATE tb_combate_personagens SET quadro_x = ?, quadro_y = ? WHERE cod =?",
            "iii", array($quadro_x, $quadro_y, $pers_cod));

        $connection->run("UPDATE tb_combate_npc SET move = move - $custo_movimento WHERE id = ?",
            "i", array($userDetails->tripulacao["id"]));
    }
} else if ($userDetails->combate_pvp) {
    if ($quadro_x < 10 AND $quadro_x >= 0 AND $quadro_y < 20 AND $quadro_y >= 0) {
        $connection->run("UPDATE tb_combate_personagens SET quadro_x = ?, quadro_y = ? WHERE cod =?",
            "iii", array($quadro_x, $quadro_y, $pers_cod));

        if ($userDetails->combate_pvp["id_1"] == $userDetails->tripulacao["id"]) {
            $connection->run("UPDATE tb_combate SET move_1 = move_1 - $custo_movimento WHERE combate = ?",
                "i", array($userDetails->combate_pvp["combate"]));
        } else if ($userDetails->combate_pvp["id_2"] == $userDetails->tripulacao["id"]) {
            $connection->run("UPDATE tb_combate SET move_2 = move_2 - $custo_movimento WHERE combate = ?",
                "i", array($userDetails->combate_pvp["combate"]));
        }

        $relatorio["combate"] = $userDetails->combate_pvp["combate"];
        $relatorio["relatorio"] = atual_segundo();
        $relatorio["id"] = $userDetails->tripulacao["id"];
        $relatorio["nome"] = $personagem["nome"];
        $relatorio["cod"] = $personagem["cod"];
        $relatorio["img"] = $personagem["img"];
        $relatorio["tipo"] = 0;
        $relatorio["nome_skil"] = 0;
        $relatorio["descricao_skil"] = 0;
        $relatorio["img_skil"] = 0;

        $query = "INSERT INTO tb_relatorio (combate, relatorio, id, cod, img, nome, tipo, nome_skil, img_skil, descricao_skil)
				VALUES
				('" . $relatorio["combate"] . "', '" . $relatorio["relatorio"] . "', '" . $relatorio["id"] . "', '" . $relatorio["cod"] . "',
				'" . $relatorio["img"] . "', '" . $relatorio["nome"] . "', '" . $relatorio["tipo"] . "', '" . $relatorio["nome_skil"] . "',
				'" . $relatorio["img_skil"] . "', '" . $relatorio["descricao_skil"] . "')";
        mysql_query($query) or die("nao foi possivel inserir o relatorio");
    }
} else if ($userDetails->combate_bot) {
    if ($quadro_x < 10 AND $quadro_x >= 0 AND $quadro_y < 20 AND $quadro_y >= 0) {
        $connection->run("UPDATE tb_combate_personagens SET quadro_x = ?, quadro_y = ? WHERE cod =?",
            "iii", array($quadro_x, $quadro_y, $pers_cod));

        $connection->run("UPDATE tb_combate_bot SET move = move - $custo_movimento WHERE tripulacao_id = ?",
            "i", array($userDetails->tripulacao["id"]));
    }
}