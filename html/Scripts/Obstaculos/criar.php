<?php
require "../../Includes/conectdb.php";

function isset_row($tabuleiro, $x, $y) {
    return (isset($tabuleiro[$x]) && isset($tabuleiro[$x][$y])) ? array($x, $y, $tabuleiro[$x][$y]["id"]) : NULL;
}

function isset_near($tabuleiro, $x, $y, $safe_id) {
    $row = isset_row($tabuleiro, $x - 1, $y - 1);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x - 1, $y);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x - 1, $y + 1);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x, $y - 1);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x, $y + 1);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x + 1, $y - 1);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x + 1, $y);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }
    $row = isset_row($tabuleiro, $x + 1, $y + 1);
    if ($row && !in_array($row[2], $safe_id)) {
        return $row;
    }

    return null;
}

$protector->need_tripulacao();
$protector->need_navio();

$x = $protector->get_number_or_exit("x");
$y = $protector->get_number_or_exit("y");
$hp = $protector->get_number_or_exit("hp");

$preco = $hp * PRECO_BERRIES_VIDA_OBSTACULO;

$protector->need_berries($preco);

$tipo = $x < 5 ? 2 : 1;

if ($hp > OBSTACULOS_HP_INDIVIDUAL_MAX) {
    $protector->exit_error("O máximo de pontos de vida por obstáculo é de " . mascara_berries(OBSTACULOS_HP_INDIVIDUAL_MAX));
}

$obstaculos = $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = ?",
    "ii", array($userDetails->tripulacao["id"], $tipo))->fetch_all_array();

$hp_atual = 0;
$editar = FALSE;
$tabuleiro = array();
foreach ($obstaculos as $obstaculo) {
    if ($x == $obstaculo["x"] && $y == $obstaculo["y"]) {
        $editar = TRUE;
        $hp_atual += $hp;
    } else {
        $hp_atual += $obstaculo["hp"];
    }
    $tabuleiro[$obstaculo["x"]][$obstaculo["y"]] = $obstaculo;
}

$tabuleiro[$x][$y] = TRUE;

if (!$editar) {
    foreach ($obstaculos as $obstaculo) {
        $safe_id = array($obstaculo["id"]);
        $near = isset_near($tabuleiro, $obstaculo["x"], $obstaculo["y"], $safe_id);
        if ($near) {
            $safe_id[] = $near[2];
            $near = isset_near($tabuleiro, $near[0], $near[1], $safe_id);
            if ($near) {
                $safe_id[] = $near[2];
                $near = isset_near($tabuleiro, $near[0], $near[1], $safe_id);
                if ($near) {
                    $protector->exit_error("Você não pode colocar mais que 3 obstáculos próximos uns aos outros.");
                }
            }
        }
    }
    $hp_atual += $hp;
}

if ($hp_atual > OBSTACULOS_HP_MAX) {
    $protector->exit_error("O máximo de pontos de vida em todos os obstáculos é de " . mascara_berries(OBSTACULOS_HP_MAX));
}

if (!$editar && count($obstaculos) >= OBSTACULOS_MAX) {
    $protector->exit_error("Você já criou o numero máximo de obstáculos possíveis");
}

if ($hp <= 0) {
    $connection->run("DELETE FROM tb_obstaculos WHERE tripulacao_id = ? AND x = ? AND y = ?",
        "iii", array($userDetails->tripulacao["id"], $x, $y));
} else if ($editar) {
    $connection->run("UPDATE tb_obstaculos SET hp = ? WHERE tripulacao_id = ? AND x = ? AND y = ?",
        "iiii", array($hp, $userDetails->tripulacao["id"], $x, $y));
} else {
    $connection->run("INSERT INTO tb_obstaculos (tripulacao_id, x, y, tipo, hp) VALUE (?,?,?,?,?)",
        "iiiii", array($userDetails->tripulacao["id"], $x, $y, $tipo, $hp));
}

$userDetails->reduz_berries($preco);

echo "-Obstáculo criado!";