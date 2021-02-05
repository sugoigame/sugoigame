<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->post_alphanumeric_or_exit("pers");
$quant = $protector->post_number_or_exit("quant");

$contzero = 0;
$slot = array();
$cod = array();
for ($x = 1; $x <= 8; $x++) {

    $slot[$x] = explode("_", $_POST["slot_$x"]);

    if (isset($slot[$x][0]) AND isset($slot[$x][1])) {
        $protector->protect_number($slot[$x][0]);
        $protector->protect_number($slot[$x][1]);
        $protector->protect_number($slot[$x][2]);

        $quant_necessaria = $slot[$x][2] * $quant;

        $item = $userDetails->get_item($slot[$x][0], $slot[$x][1]);

        if (!$item || $item["quant"] < $quant_necessaria) {
            $protector->exit_error("Você não possui todos os itens iformados.");
        }

        if ($slot[$x][1] == TIPO_ITEM_EQUIPAMENTO) {
            if ($quant > 1) {
                $protector->exit_error("Receitas com equipamentos não podem ser repetidas automaticamente.");
            }

            $cod[$x] = $connection->run("SELECT item FROM tb_item_equipamentos WHERE cod_equipamento = ?",
                "i", array($slot[$x][0]))->fetch_array()["item"];
        } else {
            $cod[$x] = $slot[$x][0];
        }
    } else {
        $slot[$x][0] = "0";
        $cod[$x] = "0";
        $slot[$x][1] = "0";
        $slot[$x][2] = "0";
        $contzero++;
    }
}
if ($contzero >= 8) {
    echo("Nada aconteceu...");
    exit();
}
if (validate_number($pers)) {
    $personagem = $userDetails->get_pers_by_cod($pers);

    if (!$personagem) {
        $protector->exit_error("Personagem invalido");
    }

    if ($personagem["profissao"] != PROFISSAO_FERREIRO
        && $personagem["profissao"] != PROFISSAO_ARTESAO
        && $personagem["profissao"] != PROFISSAO_CARPINTEIRO
    ) {
        $protector->exit_error("Este personagem não tem a profissão adequada");
    }
    if ($personagem["profissao"] == PROFISSAO_ARTESAO) {
        $tb = "tb_combinacoes_artesao";
        $tb2 = "tb_combinacoes_artesao_aleatorio";
    } else if ($personagem["profissao"] == PROFISSAO_CARPINTEIRO) {
        $tb = "tb_combinacoes_carpinteiro";
        $tb2 = "tb_combinacoes_carpinteiro_aleatorio";
    } else {
        $tb = "tb_combinacoes_forja";
        $tb2 = "tb_combinacoes_forja_aleatorio";
    }
} else if ($pers == "artesao" || $pers == "artesao_dobrao") {
    $tb = "tb_combinacoes_artesao";
    $tb2 = "tb_combinacoes_artesao_aleatorio";
} else if ($pers == "carpinteiro" || $pers == "carpinteiro_dobrao") {
    $tb = "tb_combinacoes_carpinteiro";
    $tb2 = "tb_combinacoes_carpinteiro_aleatorio";
} else if ($pers == "ferreiro" || $pers == "ferreiro_dobrao") {
    $tb = "tb_combinacoes_forja";
    $tb2 = "tb_combinacoes_forja_aleatorio";
} else {
    $protector->exit_error("Personagem invalido");
}

$result = $connection->run(
    "SELECT * FROM $tb WHERE
	`1` = ? AND `1_t` = ? AND `1_q` = ? AND
	`2` = ? AND `2_t` = ? AND `2_q` = ? AND
	`3` = ? AND `3_t` = ? AND `3_q` = ? AND
	`4` = ? AND `4_t` = ? AND `4_q` = ? AND
	`5` = ? AND `5_t` = ? AND `5_q` = ? AND
	`6` = ? AND `6_t` = ? AND `6_q` = ? AND
	`7` = ? AND `7_t` = ? AND `7_q` = ? AND
	`8` = ? AND `8_t` = ? AND `8_q` = ?",
    "iiiiiiiiiiiiiiiiiiiiiiii",
    array(
        $cod[1], $slot[1][1], $slot[1][2],
        $cod[2], $slot[2][1], $slot[2][2],
        $cod[3], $slot[3][1], $slot[3][2],
        $cod[4], $slot[4][1], $slot[4][2],
        $cod[5], $slot[5][1], $slot[5][2],
        $cod[6], $slot[6][1], $slot[6][2],
        $cod[7], $slot[7][1], $slot[7][2],
        $cod[8], $slot[8][1], $slot[8][2]
    )
);

if (!$result->count()) {
    echo "Nada aconteceu...";
    exit();
}

$receita = $result->fetch_array();

if (!$userDetails->can_add_item($quant)) {
    $protector->exit_error("Você precisa de $quant espaços vazios no seu inventário para fazer essa receita");
}

if (!validate_number($pers)) {
    if ($pers == "artesao_dobrao" || $pers == "carpinteiro_dobrao" || $pers == "ferreiro_dobrao") {
        $protector->need_dobroes(PRECO_DOBRAO_FERREIRO_VIP);
    } else {
        $protector->need_gold(PRECO_GOLD_FERREIRO_VIP);
    }
} else {
    if ($receita["lvl"] > $personagem["profissao_lvl"]) {
        $protector->exit_error("Este personagem ainda não tem o nível de profissão adequado para criar essa receita");
    }

    $xp = $receita["lvl"] == $personagem["profissao_lvl"] ? 10 : 5;

    $new_xp = min($personagem["profissao_xp_max"], $personagem["profissao_xp"] + ($xp * $quant));

    $connection->run("UPDATE tb_personagens SET profissao_xp = ? WHERE cod = ?",
        "ii", array($new_xp, $pers));
}

for ($x = 1; $x < 9; $x++) {
    if ($slot[$x][2] > 0) {
        $userDetails->reduz_item($cod[$x], $slot[$x][1], $slot[$x][2] * $quant);
    }
}

if (!$receita["aleatorio"]) {
    $tipo_insert = $receita["tipo"];
    $cod_insert = $receita["cod"];
    $quant_insert = $receita["quant"];
} else {
    $receita_rand = $connection->run("SELECT * FROM $tb2 WHERE receita = ? ORDER BY RAND() LIMIT 1",
        "i", array($receita["cod_receita"]))->fetch_array();

    $tipo_insert = $receita_rand["tipo"];
    $cod_insert = $receita_rand["cod"];
    $quant_insert = $receita_rand["quant"];
}

if ($tipo_insert == TIPO_ITEM_EQUIPAMENTO) {
    for ($x = 0; $x < $quant; $x++) {
        if (!$receita["aleatorio"]) {
            $userDetails->add_equipamento_by_cod($receita["cod"]);
        } else {
            $receita_rand = $connection->run("SELECT * FROM $tb2 WHERE receita = ? ORDER BY RAND() LIMIT 1",
                "i", array($receita["cod_receita"]))->fetch_array();
            $userDetails->add_equipamento_by_cod($receita_rand["cod"]);
        }
    }
} else {
    if (!$receita["aleatorio"]) {
        $userDetails->add_item($receita["cod"], $receita["tipo"], $receita["quant"] * $quant);
    } else {
        for ($x = 0; $x < $quant; $x++) {
            $receita_rand = $connection->run("SELECT * FROM $tb2 WHERE receita = ? ORDER BY RAND() LIMIT 1",
                "i", array($receita["cod_receita"]))->fetch_array();
            $userDetails->add_item($receita_rand["cod"], $receita_rand["tipo"], $receita_rand["quant"]);
        }
    }
}

if (!validate_number($pers)) {
    if ($pers == "artesao_dobrao" || $pers == "carpinteiro_dobrao" || $pers == "ferreiro_dobrao") {
        $userDetails->reduz_dobrao(PRECO_DOBRAO_FERREIRO_VIP, "ferreiro_forjar");
    } else {
        $userDetails->reduz_gold(PRECO_GOLD_FERREIRO_VIP, "ferreiro_forjar");
    }
}

echo("-Novo item criado!");
