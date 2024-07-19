<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();

$pers = $protector->get_number_or_exit("pers");
$tipo = $protector->get_alphanumeric_or_exit("tipo");

$personagem = $userDetails->get_pers_by_cod($pers);

if (! $personagem || $personagem["profissao"] != PROFISSAO_ARQUEOLOGO) {
    $protector->exit_error("Personagem inválido");
}

$mergulhadores = $userDetails->arqueologos;

$quant = min(count($mergulhadores), 3);

if (! $userDetails->can_add_item($quant)) {
    $protector->exit_error("Seu inventário está cheio. Libere $quant espaços para pegar sua recompensa");
}

$tempo = 3600 - (($userDetails->lvl_arqueologo - 1) * 180);
$preco = ceil(($userDetails->tripulacao["expedicao"] - atual_segundo()) / $tempo) * 2 - 1;
if ($userDetails->tripulacao["expedicao"] > atual_segundo()) {
    if ($tipo == "gold") {
        $protector->need_gold($preco);
    } elseif ($tipo == "dobroes") {
        $preco = ceil($preco * 1.2);
        $protector->need_dobroes($preco);
    } else {
        $protector->exit_error("Você ainda não pode fazer isso");
    }
}

if ($userDetails->ilha["mar"] <= 4) {
    $mar = 1;
} elseif ($userDetails->ilha["mar"] == 5) {
    $mar = "2' OR mergulho='1";
} elseif ($userDetails->ilha["mar"] == 6) {
    $mar = "3' OR mergulho='2' OR mergulho='1";
} elseif ($userDetails->ilha["mar"] == 7) {
    $mar = "4' OR mergulho='3' OR mergulho='2' OR mergulho='1";
}

$recompensas = [];
for ($x = 1; $x <= $quant; $x++) {
    $rand = rand(1, 100);

    if ($rand <= 24) {
        $remedios = MapLoader::filter("comidas", function ($item) use ($mar) {
            return $item["mergulho"] == $mar;
        });
        $item = $remedios[array_rand($remedios)];
        $userDetails->add_item($item["cod_comida"], TIPO_ITEM_COMIDA, 1);

        $recompensas[] = $item["nome"];
    } elseif ($rand <= 27) {
        $item = $connection->run("SELECT nome, cod_casco FROM tb_item_navio_casco WHERE mergulho='$mar' ORDER BY RAND() LIMIT 1")->fetch_array();

        $userDetails->add_item($item["cod_casco"], TIPO_ITEM_CASCO, 1);

        $recompensas[] = $item["nome"];
    } elseif ($rand <= 40) {
        $remedios = MapLoader::filter("remedios", function ($remedio) use ($mar) {
            return $remedio["mergulho"] == $mar;
        });
        $item = $remedios[array_rand($remedios)];

        $userDetails->add_item($item["cod_remedio"], TIPO_ITEM_REMEDIO, 1);

        $recompensas[] = $item["nome"];
    } elseif ($rand <= 57) {
        $berries = round(($userDetails->lvl_arqueologo * 300000) * (rand(50, 100) / 100));
        $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
            "ii", array($berries, $userDetails->tripulacao["id"]));
        $recompensas[] = mascara_berries($berries) . " Berries";
    } elseif ($rand <= 99) {
        $item = $connection->run("SELECT nome, cod_reagent FROM tb_item_reagents WHERE mergulho='$mar' ORDER BY RAND() LIMIT 1")->fetch_array();

        $userDetails->add_item($item["cod_reagent"], TIPO_ITEM_REAGENT, 1);

        $recompensas[] = $item["nome"];
    } else {
        $userDetails->add_item(get_random_akuma()["cod_akuma"], TIPO_ITEM_AKUMA, 1, true);

        $recompensas[] = "Akuma no Mi";
    }
}


$connection->run("UPDATE tb_personagens SET profissao_xp = LEAST(profissao_xp_max, profissao_xp + 5) WHERE profissao = ? AND id = ? AND ativo = 1",
    "ii", array(PROFISSAO_ARQUEOLOGO, $userDetails->tripulacao["id"]));

if ($userDetails->tripulacao["expedicao"] > atual_segundo()) {
    if ($tipo == "gold") {
        $userDetails->reduz_gold($preco, "expedicao_novamente, ");
    }
}

if ($reducao = $userDetails->buffs->get_efeito("reducao_tempo_expedicao")) {
    $tempo -= $reducao;
}

if ($userDetails->tripulacao["expedicao"] > atual_segundo()) {
    $tempo += $userDetails->tripulacao["expedicao"];
} else {
    $tempo += atual_segundo();
}

$connection->run("UPDATE tb_usuarios SET expedicao = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

$response->send_loot($recompensas, "Você Recebeu " . implode(", ", $recompensas));
