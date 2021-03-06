<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_next_to_land();
$protector->must_be_out_of_rota();

$pers = $protector->get_number_or_exit("pers");
$tipo = $protector->get_alphanumeric_or_exit("tipo");

$personagem = $userDetails->get_pers_by_cod($pers);

if (!$personagem || $personagem["profissao"] != PROFISSAO_CARPINTEIRO) {
    $protector->exit_error("Personagem inválido");
}

$carpinteiros = $userDetails->carpinteiros;

$quant = min(count($carpinteiros), 3);

if (!$userDetails->can_add_item($quant)) {
    $protector->exit_error("Seu inventário está cheio. Libere $quant espaços para receber sua recompensa");
}

$tempo = 10 * 60;
$preco = ceil(($userDetails->tripulacao["madeira"] - atual_segundo()) / $tempo) * 2 - 1;
if ($userDetails->tripulacao["madeira"] > atual_segundo()) {
    if ($tipo == "gold") {
        $protector->need_gold($preco);
    } else if ($tipo == "dobroes") {
        $preco = ceil($preco * 1.2);
        $protector->need_dobroes($preco);
    } else {
        $protector->exit_error("Você ainda não pode fazer isso");
    }
}

if ($userDetails->ilha["mar"] <= 4) {
    $mar = 1;
} else if ($userDetails->ilha["mar"] == 5) {
    $mar = "2' OR madeira='1";
} else if ($userDetails->ilha["mar"] == 6) {
    $mar = "3' OR madeira='2' OR madeira='1";
} else if ($userDetails->ilha["mar"] == 7) {
    $mar = "4' OR madeira='3' OR madeira='2' OR madeira='1";
}

$recompensas = [];
for ($x = 1; $x <= $quant; $x++) {
    $item = $connection->run("SELECT nome, cod_reagent FROM tb_item_reagents WHERE madeira='$mar' ORDER BY RAND() LIMIT 1")->fetch_array();

    $userDetails->add_item($item["cod_reagent"], TIPO_ITEM_REAGENT, 1);

    $recompensas[] = $item["nome"];
}

$connection->run("UPDATE tb_personagens SET profissao_xp = LEAST(profissao_xp_max, profissao_xp + 5) WHERE profissao = ? AND id = ? AND ativo = 1",
    "ii", array(PROFISSAO_CARPINTEIRO, $userDetails->tripulacao["id"]));

if ($userDetails->tripulacao["madeira"] > atual_segundo()) {
    if ($tipo == "gold") {
        $userDetails->reduz_gold($preco, "madeira_novamente");
    } else if ($tipo == "dobroes") {
        $userDetails->reduz_dobrao($preco, "madeira_novamente");
    }
    $tempo += $userDetails->tripulacao["madeira"];
} else {
    $tempo += atual_segundo();
}

$connection->run("UPDATE tb_usuarios SET madeira = ? WHERE id = ?",
    "ii", array($tempo, $userDetails->tripulacao["id"]));

$response->send_loot($recompensas, "Você Recebeu " . implode(", ", $recompensas));