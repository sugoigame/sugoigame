<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao();
$protector->must_be_out_of_rota();

$connection->run("DELETE FROM tb_marcenaria_reparos WHERE id=?", "i", array($userDetails->tripulacao["id"]));

$destino = array(
    "x" => $protector->get_number_or_exit("x"),
    "y" => $protector->get_number_or_exit("y")
);

$origem = array(
    "x" => $userDetails->tripulacao["coord_x_navio"],
    "y" => $userDetails->tripulacao["coord_y_navio"]
);

$rota = distancia($origem, $destino) > 1.5 ? path_finding($origem, $destino) : array();

if ($userDetails->navio["cod_velas"]) {
    $vela = $connection->run("SELECT * FROM tb_item_navio_velas WHERE cod_velas = ?",
        "i", array($userDetails->navio["cod_velas"]))->fetch_array();
    $mod_vela = 1 - ($vela["bonus"] / 100);
} else {
    $mod_vela = 1;
}

if ($userDetails->navio["cod_leme"]) {
    $leme = $connection->run("SELECT * FROM tb_item_navio_leme WHERE cod_leme = ?",
        "i", array($userDetails->navio["cod_leme"]))->fetch_array();
    $mod_leme = 1 - ($leme["bonus"] / 100);
} else {
    $mod_leme = 1;
}

$mod_navio = 1 - (($userDetails->navio["lvl"] - 1) * 0.05);

array_unshift($rota, $origem);
array_push($rota, $destino);

$tempo = [];
foreach ($rota as $index => $coordenada) {
    if ($coordenada["x"] == $userDetails->tripulacao["coord_x_navio"] && $coordenada["y"] == $userDetails->tripulacao["coord_y_navio"]) {
        continue;
    }

    $nav_data = calc_nav_data($rota[$index - 1], $coordenada);

    $coordenada_info = $connection->run("SELECT * FROM tb_mapa WHERE x = ? AND y = ?",
        "ii", array($coordenada["x"], $coordenada["y"]))->fetch_array();

    if (!$coordenada_info["navegavel"]) {
        break;
    }

    if ($coordenada_info["dir_vento"] == $nav_data["direcao"]) {
        $mod_vento = 1 + $coordenada_info["tipo_vento"] * 0.1;
    } else if ($coordenada_info["dir_vento"] == $nav_data["direcao_inversa"]) {
        $mod_vento = 1 - $coordenada_info["tipo_vento"] * 0.1;
    } else {
        $mod_vento = 1;
    }

    if ($coordenada_info["dir_corrente"] == $nav_data["direcao"]) {
        $mod_corr = 1 + $coordenada_info["tipo_corrente"] * 0.1;
    } else if ($coordenada_info["dir_corrente"] == $nav_data["direcao_inversa"]) {
        $mod_corr = 1 - $coordenada_info["tipo_corrente"] * 0.1;
    } else {
        $mod_corr = 1;
    }

    $tempo[$index] = $nav_data["tempo"] * $mod_corr * $mod_vento * $mod_navio * $mod_leme * $mod_vela;
    if ($tempo[$index] < 5) {
        $tempo[$index] = 5;
    }

    $reducao = (1 - ($userDetails->navio["hp"] / $userDetails->navio["hp_max"])) * $tempo[$index];

    if ($aumento = $userDetails->buffs->get_efeito("aumento_velocidade_barco")) {
        $tempo[$index] -= ceil($aumento * $tempo[$index]);
    }
    $tempo[$index] += $reducao;

    if (isset($tempo[($index - 1)])) {
        $tempo[$index] += $tempo[($index - 1)];
    }

    $tempo_final = $tempo[$index] + atual_segundo();
    $tempo_final = round($tempo_final);

    $connection->run("INSERT INTO tb_rotas (id, x, y, indice, momento) VALUE (?,?,?,?,?)",
        "iiiii", array($userDetails->tripulacao["id"], $coordenada["x"], $coordenada["y"], $index, $tempo_final));

}

