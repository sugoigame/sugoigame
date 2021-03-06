<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();

$pers = $protector->get_number_or_exit("pers");
$tipo = $protector->get_alphanumeric_or_exit("tipo");

$cartografo = $userDetails->get_pers_by_cod($pers);

if (!$cartografo || $cartografo["profissao"] != PROFISSAO_CARTOGRAFO) {
    $protector->exit_error("Personagem inválido");
}

$result = $connection->run(
    "SELECT 
    mapa.cod_mapa AS cod_mapa, 
    mapa.desenho AS desenho
    FROM tb_usuario_itens itn
    INNER JOIN tb_item_mapa mapa ON itn.cod_item = mapa.cod_mapa AND itn.tipo_item = " . TIPO_ITEM_MAPA .
    " WHERE itn.id = ? LIMIT 1",
    "i", $userDetails->tripulacao["id"]
);

if (!$result->count()) {
    $protector->exit_error("Você precisa de um mapa");
}

$mapa = $result->fetch_array();

$tempo = 120 - 10 * ($cartografo["profissao_lvl"] - 1);
if ($userDetails->tripulacao["desenho"] > atual_segundo()) {
    $preco = ceil(($userDetails->tripulacao["desenho"] - atual_segundo()) / $tempo) * 2 - 1;

    if ($tipo == "gold") {
        if (!$userDetails->reduz_gold($preco, "desenho_novamente")) {
            $protector->exit_error("Você não tem moedas de ouro suficientes");
        }
    } else if ($tipo == "dobroes") {
        $preco = ceil($preco * 1.2);
        if (!$userDetails->reduz_dobrao($preco, "desenho_novamente")) {
            $protector->exit_error("Você não tem dobrões o suficientes");
        }
    }
}

$cod_mapa = $mapa["cod_mapa"];
$desenho = $mapa["desenho"] ? json_decode($mapa["desenho"], true) : [];

$coord_x_navio = $userDetails->tripulacao["x"];
$coord_y_navio = $userDetails->tripulacao["y"];
$distancia_visao = 5;

for ($coord_y = $coord_y_navio - 5; $coord_y <= $coord_y_navio + 5; $coord_y++) {
    for ($coord_x = $coord_x_navio - 5; $coord_x <= $coord_x_navio + 5; $coord_x++) {
        if (sqrt(pow($coord_x_navio - $coord_x, 2) + pow($coord_y_navio - $coord_y, 2)) <= $distancia_visao) {
            $x = calc_map_limit_x($coord_x);
            $y = calc_map_limit_y($coord_y);

            $desenho[$x][$y] = true;
        }
    }
}

$connection->run("UPDATE tb_item_mapa SET desenho = ? WHERE cod_mapa = ?",
    "si", array(json_encode($desenho), $cod_mapa));

if ($userDetails->tripulacao["desenho"] > atual_segundo()) {
    $tempo += $userDetails->tripulacao["desenho"];
} else {
    $tempo += atual_segundo();
}

$connection->run("UPDATE tb_usuarios SET desenho= $tempo WHERE id = ?", "i", $userDetails->tripulacao["id"]);

$xp = $cartografo["profissao_xp"] + 1;
if ($xp > $cartografo["profissao_xp_max"]) {
    $xp = $cartografo["profissao_xp_max"];
}

$connection->run("UPDATE tb_personagens SET profissao_xp='$xp' WHERE cod = ?", "i", $cartografo["cod"]);

echo("-Desenho concluido");
