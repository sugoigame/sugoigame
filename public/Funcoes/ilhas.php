<?php
if (! function_exists('nome_ilha')) {
    function nome_ilha($cod)
    {
        return \Utils\Data::load("mundo")["ilhas"][$cod]["nome"];
    }
}
function nome_mar($mar)
{
    return \Utils\Data::load("mundo")["mares"][$mar]["nome"];
}

function get_current_location()
{
    global $userDetails;
    return get_human_location($userDetails->tripulacao["x"], $userDetails->tripulacao["y"]);
}

function get_mar($x, $y)
{
    if ($x <= 230 && $y <= 95) {
        return 2; // NORTH
    } elseif ($x > 230 && $y <= 95) {
        return 1; // EAST
    } elseif ($x <= 230 && $y >= 265) {
        return 3; // WEST
    } elseif ($x > 230 && $y >= 265) {
        return 4; // SOUTH
    } elseif ($x <= 230 && $y >= 105 && $y <= 255) {
        return 6; // NEW WORLD
    } elseif ($x > 230 && $y >= 105 && $y <= 255) {
        return 5; // PARADISE
    } else {
        return 7; // CALM BELT
    }
}

function get_human_location($x, $y)
{
    return $x . "º L, " . (359 - $y) . "º N";
}

function get_coord_ilha_from_cache($ilha, $cache)
{
    foreach ($cache as $ilha_data) {
        if ($ilha == $ilha_data["ilha"]) {
            return $ilha_data;
        }
    }
    return null;
}

function get_coord_tele_gl($mar)
{
    if ($mar == 1) {
        return array("x" => 3, "y" => 35);
    } elseif ($mar == 2) {
        return array("x" => 198, "y" => 35);
    } elseif ($mar == 3) {
        return array("x" => 3, "y" => 65);
    } elseif ($mar == 4) {
        return array("x" => 198, "y" => 66);
    } else {
        return array("x" => 0, "y" => 0);
    }
}

function nome_recurso($recurso_id)
{
    switch ($recurso_id) {
        case 0:
            return "Madeira Especial";
        case 1:
            return "Ferro Especial";
        case 2:
            return "Trigo Especial";
        default:
            return "";
    }
}

function icon_recurso($recurso_id)
{
    switch ($recurso_id) {
        case 0:
            return "206";
        case 1:
            return "205";
        case 2:
            return "155";
        default:
            return "";
    }
}

function get_random_coord_navegavel($mar)
{
    $no_navigable = MapLoader::load("mapa_nao_navegavel");

    do {
        if ($mar == 1) {
            $x = rand(290, 430);
            $y = rand(20, 95);
        } elseif ($mar == 2) {
            $x = rand(30, 165);
            $y = rand(20, 95);
        } elseif ($mar == 3) {
            $x = rand(288, 430);
            $y = rand(266, 345);
        } elseif ($mar == 4) {
            $x = rand(30, 165);
            $y = rand(266, 345);
        } elseif ($mar == 5) {
            $x = rand(291, 430);
            $y = rand(106, 254);
        } else {
            $x = rand(30, 165);
            $y = rand(106, 254);
        }
    } while (isset($no_navigable[$x]) && isset($no_navigable[$x][$y]));

    return array("x" => $x, "y" => $y);
}

function spawn_rdm_in_random_coord($mar, $rdm_id)
{
    global $connection;
    $coord = get_random_coord_navegavel($mar);

    $connection->run("INSERT INTO tb_mapa_rdm (x, y, rdm_id) VALUE (?,?,?)",
        "iii", array($coord["x"], $coord["y"], $rdm_id));
}
