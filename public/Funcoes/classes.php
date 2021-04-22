<?php
function nome_classe($cod) {
    switch ($cod) {
        case 0:
            $classe = "Nenhuma";
            break;
        case 1:
            $classe = "Espadachim";
            break;
        case 2:
            $classe = "Lutador";
            break;
        case 3:
            $classe = "Atirador";
            break;
    }
    return $classe;
}

function get_bonus_excelencia($classe, $lvl) {
    return array(
        "atk" => floor(($lvl + 2) / 10) + ($classe == 1 ? floor(($lvl) / 10) : 0),
        "def" => floor(($lvl + 3) / 10) + ($classe == 2 ? floor(($lvl) / 10) : 0),
        "agl" => floor(($lvl + 4) / 10),
        "res" => floor(($lvl + 7) / 10),
        "pre" => floor(($lvl + 5) / 10) + ($classe == 3 ? floor(($lvl) / 10) : 0),
        "dex" => floor(($lvl + 6) / 10),
        "con" => floor(($lvl + 8) / 10),
        "vit" => 0,
        "hp_max" => floor(($lvl + 1) / 10) * 100,
        "mp_max" => floor(($lvl + 9) / 10) * 6
    );
}

function get_next_bonus_excelencia($classe, $lvl) {
    $atual = get_bonus_excelencia($classe, $lvl);
    $proximo = get_bonus_excelencia($classe, $lvl + 1);

    return array(
        "atk" => $proximo["atk"] - $atual["atk"],
        "def" => $proximo["def"] - $atual["def"],
        "agl" => $proximo["agl"] - $atual["agl"],
        "res" => $proximo["res"] - $atual["res"],
        "pre" => $proximo["pre"] - $atual["pre"],
        "dex" => $proximo["dex"] - $atual["dex"],
        "con" => $proximo["con"] - $atual["con"],
        "vit" => $proximo["vit"] - $atual["vit"],
        "hp_max" => $proximo["hp_max"] - $atual["hp_max"],
        "mp_max" => $proximo["mp_max"] - $atual["mp_max"]
    );
}