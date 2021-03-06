<?php
function calc_map_limit_x($x) {
    if ($x <= 0) {
        return 0;
    }
    if ($x > 459) {
        return 459;
    }

    return $x;
}

function calc_map_limit_y($y) {
    if ($y <= 0) {
        $y = 0;
    }
    if ($y > 359) {
        $y = 359;
    }

    return $y;
}

function get_tipo_vento($vento) {
    switch ($vento) {
        case 1:
            return "C";
        case 2:
            return "B";
        case 3:
            return "A";
        default:
            return "";
    }
}

function get_tipo_corrente($corrente) {
    switch ($corrente) {
        case 1:
            return "C";
        case 2:
            return "B";
        case 3:
            return "A";
        default:
            return "";
    }
}

function preco_ficar_coordenada_derrotado_capitao() {
    global $userDetails;

    return $userDetails->lvl_mais_forte * 1000;
}

function preco_ficar_coordenada_derrotado_tripulacao() {
    global $userDetails;

    return $userDetails->lvl_mais_forte * 2000;
}

function preco_ficar_coordenada_derrotado_imune() {
    global $userDetails;

    return $userDetails->lvl_mais_forte * 3000;
}

function load_mapa_for_path_finding($origem, $destino) {
    global $connection;

    $mapa_bd = $connection->run("SELECT x, y, navegavel, ilha FROM tb_mapa");

    $mapa = array();
    while ($coordenada = $mapa_bd->fetch_array()) {
        $mapa[$coordenada["x"] - 1][$coordenada["y"] - 1] = $coordenada["navegavel"] ? 0 : PathFinder::TYPE_WALL;
    }

    $mapa[$origem["x"] - 1][$origem["y"] - 1] = 1;
    $mapa[$destino["x"] - 1][$destino["y"] - 1] = 2;

    $blockX = count($mapa[0]);
    $blockY = count($mapa);

    for ($x = 0; $x < $blockX; $x++) {
        for ($y = 0; $y < $blockY; $y++) {
            if (!isset($mapa[$y]) || !isset($mapa[$y][$x])) {
                $mapa[$y][$x] = PathFinder::TYPE_WALL;
            }
        }
    }
    return $mapa;
}

function path_finding_with_mapa($mapa) {

    $path_finder = new PathFinder($mapa, true);

    $rota = $path_finder->mapPathSteps;

    foreach ($rota as $index => $coordenada) {
        $rota[$index]["x"] = $coordenada[1] + 1;
        $rota[$index]["y"] = $coordenada[0] + 1;
    }

    return $rota;
}

function path_finding($origem, $destino) {
    return path_finding_with_mapa(load_mapa_for_path_finding($origem, $destino));
}

function calc_nav_data($origem, $destino) {
    //norte
    if ($destino["x"] == $origem["x"] AND $destino["y"] == $origem["y"] - 1) {
        return array(
            "direcao" => 5,
            "direcao_inversa" => 1,
            "tempo" => 30
        );
    } //sul
    else if ($destino["x"] == $origem["x"] AND $destino["y"] == $origem["y"] + 1) {
        return array(
            "direcao" => 1,
            "direcao_inversa" => 5,
            "tempo" => 30
        );
    } //leste
    else if ($destino["x"] == $origem["x"] + 1 AND $destino["y"] == $origem["y"]) {
        return array(
            "direcao" => 7,
            "direcao_inversa" => 3,
            "tempo" => 30
        );
    } //oeste
    else if ($destino["x"] == $origem["x"] - 1 AND $destino["y"] == $origem["y"]) {
        return array(
            "direcao" => 3,
            "direcao_inversa" => 7,
            "tempo" => 30
        );
    } //sudeste
    else if ($destino["x"] == $origem["x"] + 1 AND $destino["y"] == $origem["y"] + 1) {
        return array(
            "direcao" => 8,
            "direcao_inversa" => 4,
            "tempo" => 45
        );
    } //nordeste
    else if ($destino["x"] == $origem["x"] + 1 AND $destino["y"] == $origem["y"] - 1) {
        return array(
            "direcao" => 6,
            "direcao_inversa" => 2,
            "tempo" => 45
        );
    } //sudoeste
    else if ($destino["x"] == $origem["x"] - 1 AND $destino["y"] == $origem["y"] + 1) {
        return array(
            "direcao" => 2,
            "direcao_inversa" => 6,
            "tempo" => 45
        );
    } //nororeste
    else if ($destino["x"] == $origem["x"] - 1 AND $destino["y"] == $origem["y"] - 1) {
        return array(
            "direcao" => 4,
            "direcao_inversa" => 8,
            "tempo" => 45
        );
    } else {
        return null;
    }
}

function distancia($origem, $destino) {
    return sqrt(pow($origem["x"] - $destino["x"], 2) + pow($origem["y"] - $destino["y"], 2));
}