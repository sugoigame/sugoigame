<?php
if (!function_exists('nome_ilha')) {
    function nome_ilha($cod) {
        switch ($cod) {
            //East
            case 1:     return "Ilha Dawn";
            case 2:     return "Shells Town";
            case 3:     return "Orange Town";
            case 4:     return "Vila Syrup";
            case 5:     return "Baratie";
            case 6:     return "Ilha Cocoyashi";
            case 7:     return "Loguetown";

            //North
            case 8:     return "Lvneel Kingdom";
            case 9:     return "Burlywood Town";
            case 10:    return "GoldenRod Town";
            case 11:    return "Oubliette Town";
            case 12:    return "Vila Whitewood";
            case 13:    return "Ilha North Coral";
            case 14:    return "Cartigen";

            //South
            case 15:    return "Baterilla";
            case 16:    return "Zozu Town";
            case 17:    return "Karatê island";
            case 18:    return "Avalien Town";
            case 19:    return "Torino";
            case 20:    return "Cinturão das luas";
            case 21:    return "Kimotsu Town";

            //West
            case 22:    return "Ilusia Kingdom";
            case 23:    return "Ohara";
            case 24:    return "Ilha Toroa";
            case 25:    return "Las Camp";
            case 26:    return "Kima Town";
            case 27:    return "Jumbo Town";
            case 28:    return "Ilha Kagero";

            //Grand Line
            case 29:    return "Cabos Gêmeos";
            case 30:    return "Whiskey Peak";
            case 31:    return "Little Garden";
            case 32:    return "Drum";
            case 33:    return "Rainbase";
            case 34:    return "Yuba";
            case 35:    return "Alubarna";
            case 36:    return "Nanohana";
            case 37:    return "Mock Town";
            case 38:    return "South Grave";
            case 39:    return "Long Ring Long Land";
            case 40:    return "Water 7";
            case 41:    return "Thriller Bark";
            case 42:    return "Sabaody";
            case 43:    return "Mariejois";

            //Novo Mundo
            case 44:    return "Punk Hazard";
            case 45:    return "Yukiryu";
            case 46:    return "Raijin";
            case 47:    return "Laftel";

            // outras
            case 101:   return "Impel Down";
            case 102:   return "Enies Lobby";
            default:    return "";
        }
    }
}
function nome_mar($mar) {
    switch ($mar) {
        case 1:     return "East Blue";
        case 2:     return "North Blue";
        case 3:     return "West Blue";
        case 4:     return "South Blue";
        case 5:     return "Grand Line";
        case 6:     return "Novo Mundo";
        case 7:     return "Calm Belt";
        default:    return "";
    }
}

function get_current_location() {
    global $userDetails;
    return get_human_location($userDetails->tripulacao["x"], $userDetails->tripulacao["y"]);
}

function get_mar($x, $y) {
    if ($x <= 230 && $y <= 95) {
        return 2; // NORTH
    } else if ($x > 230 && $y <= 95) {
        return 1; // EAST
    } else if ($x <= 230 && $y >= 265) {
        return 3; // WEST
    } else if ($x > 230 && $y >= 265) {
        return 4; // SOUTH
    } else if ($x <= 230 && $y >= 105 && $y <= 255) {
        return 6; // NEW WORLD
    } else if ($x > 230 && $y >= 105 && $y <= 255) {
        return 5; // PARADISE
    } else {
        return 7; // CALM BELT
    }
}

function get_human_location($x, $y) {
    return $x . "º L, " . (359 - $y) . "º N";
}

function get_coord_ilha($ilha) {
    global $connection;
    return $connection->run("SELECT x, y FROM tb_mapa WHERE ilha = ?", "i", $ilha)->fetch_array();
}

function get_ilha_by_coord($x, $y) {
    global $connection;
    return $connection->run("SELECT ilha FROM tb_mapa WHERE x = ? AND y = ?", "ii", array($x, $y))->fetch_array();
}

function get_coord_tele_gl($mar) {
    if ($mar == 1) {
        return array("x" => 3, "y" => 35);
    } else if ($mar == 2) {
        return array("x" => 198, "y" => 35);
    } else if ($mar == 3) {
        return array("x" => 3, "y" => 65);
    } else if ($mar == 4) {
        return array("x" => 198, "y" => 66);
    } else {
        return array("x" => 0, "y" => 0);
    }
}

function nome_recurso($recurso_id) {
    switch ($recurso_id) {
        case 0:     return "Madeira Especial";
        case 1:     return "Ferro Especial";
        case 2:     return "Trigo Especial";
        default:    return "";
    }
}

function icon_recurso($recurso_id) {
    switch ($recurso_id) {
        case 0:     return "206";
        case 1:     return "205";
        case 2:     return "155";
        default:    return "";
    }
}

function get_random_coord_navegavel($mar) {
    $no_navigable = MapLoader::load("mapa_nao_navegavel");

    do {
        if ($mar == 1) {
            $x = rand(290, 430);
            $y = rand(20, 95);
        } else if ($mar == 2) {
            $x = rand(30, 165);
            $y = rand(20, 95);
        } else if ($mar == 3) {
            $x = rand(288, 430);
            $y = rand(266, 345);
        } else if ($mar == 4) {
            $x = rand(30, 165);
            $y = rand(266, 345);
        } else if ($mar == 5) {
            $x = rand(291, 430);
            $y = rand(106, 254);
        } else {
            $x = rand(30, 165);
            $y = rand(106, 254);
        }
    } while (isset($no_navigable[$x]) && isset($no_navigable[$x][$y]));

    return array("x" => $x, "y" => $y);
}

function spawn_rdm_in_random_coord($mar, $rdm_id) {
    global $connection;
    $coord = get_random_coord_navegavel($mar);

    $connection->run("INSERT INTO tb_mapa_rdm (x, y, rdm_id) VALUE (?,?,?)",
        "iii", array($coord["x"], $coord["y"], $rdm_id));
}