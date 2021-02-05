<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";

$protector->must_be_out_of_any_kind_of_combat();
$protector->need_navio();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao();
$protector->must_be_out_of_rota();

if (!isset($_POST["vem"])) {
    mysql_close();
    echo("#Você não traçou uma rota.");
    exit;
}
if (!preg_match("/^[\w]+$/", $_POST["vem"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido1.");
    exit();
}
$query = "SELECT * FROM tb_rotas WHERE id='" . $usuario["id"] . "' ORDER BY indice";
$result = mysql_query($query);
for ($x = 0; $sql = mysql_fetch_array($result); $x++) {
    $rota[$x] = $sql;
}
if (isset($rota)) {
    mysql_close();
    echo("#Você já está navegando");
    exit;
}

//pega a rota
$tipo = $_POST["vem"];
$cont = 0;
if ($tipo == "oceano") {
    for ($i = 1; $i < 6; $i++) {
        $id = "r_" . $i;
        $rota[$i] = $_POST[$id];
        if (empty($rota[$i])) {
            $cont += 1;
        }
    }
    if ($cont == 5) {
        mysql_close();
        echo("#Você não traçou uma rota.");
        exit;
    }
} else if ($tipo = "cart") {
    $j = 0;
    for ($i = 1; $i < 26; $i++) {
        $id = "r_" . $j;
        $j++;
        $rota[$i] = $_POST[$id];
        if (empty($rota[$i])) {
            $cont += 1;
        }
    }
    if ($cont == 25) {
        mysql_close();
        echo("#Você não traçou uma rota.");
        exit;
    }
} else {
    mysql_close();
    echo("#Impossivel identificar onde você fez essa rota.");
    exit;
}

//cria os arrays com as coordenadas
for ($j = 1; $j <= sizeof($rota); $j++) {
    $ct = 0;
    $inpx[$j] = "";
    $inpy[$j] = "";
    for ($i = 0; $i < strlen($rota[$j]); $i++) {
        if (substr($rota[$j], $i, 1) == "_") {
            $ct++;
        } else {
            if ($ct == 0) {
                $inpx[$j] .= substr($rota[$j], $i, 1);
            } else {
                $inpy[$j] .= substr($rota[$j], $i, 1);
            }
        }
    }

    $query = "SELECT * FROM tb_mapa WHERE x='" . $inpx[$j] . "' AND y='" . $inpy[$j] . "'";
    $result = mysql_query($query);
    $mapa[$j] = mysql_fetch_array($result);
    if ($mapa[$j]["navegavel"] == 0 AND !empty($inpx[$j]) AND !empty($inpy[$j])) {
        mysql_close();
        echo("#Uma coordenada inválida foi informada.");
        exit;
    }
}
$query = "DELETE FROM tb_marcenaria_reparos WHERE id=" . $usuario["id"] . "";
mysql_query($query);


$query = "SELECT * FROM tb_mapa WHERE x='" . $usuario["coord_x_navio"] . "' AND y='" . $usuario["coord_y_navio"] . "'";
$result = mysql_query($query);
$mapa[0] = mysql_fetch_array($result);
$inpx[0] = $usuario["coord_x_navio"];
$inpy[0] = $usuario["coord_y_navio"];


if ($inpx[1] == ($usuario["coord_x_navio"]) AND $inpy[1] == ($usuario["coord_y_navio"])) {
    echo("#Uma coordenada inválida foi informada1.");
    exit;
}

if ($navio["cod_velas"] != 0) {
    $query = "SELECT bonus FROM tb_item_navio_velas WHERE cod_velas='" . $navio["cod_velas"] . "'";
    $result = mysql_query($query);
    $vela = mysql_fetch_array($result);
    $mod_vela = 1 - ($vela["bonus"] / 100);
} else {
    $mod_vela = 1;
}
if ($navio["cod_leme"] != 0) {
    $query = "SELECT bonus FROM tb_item_navio_leme WHERE cod_leme='" . $navio["cod_leme"] . "'";
    $result = mysql_query($query);
    $vela = mysql_fetch_array($result);
    $mod_leme = 1 - ($vela["bonus"] / 100);
} else {
    $mod_leme = 1;
}
$mod_navio = 1 - (($navio["lvl"] - 1) * 0.05);
for ($x = 1; $x <= sizeof($rota); $x++) {
    if (!empty($inpx[$x]) AND !empty($inpy[$x])) {
        //norte
        if ($inpx[$x] == $inpx[($x - 1)] AND $inpy[$x] == ($inpy[($x - 1)]) - 1) {
            $dir_pos[$x] = 5;
            $dir_neg[$x] = 1;
            $tmp[$x] = 30;
        } //sul
        else if ($inpx[$x] == $inpx[($x - 1)] AND $inpy[$x] == ($inpy[($x - 1)]) + 1) {
            $dir_pos[$x] = 1;
            $dir_neg[$x] = 5;
            $tmp[$x] = 30;
        } //leste
        else if ($inpx[$x] == ($inpx[($x - 1)] + 1) AND $inpy[$x] == $inpy[($x - 1)]) {
            $dir_pos[$x] = 7;
            $dir_neg[$x] = 3;
            $tmp[$x] = 30;
        } //oeste
        else if ($inpx[$x] == ($inpx[($x - 1)] - 1) AND $inpy[$x] == $inpy[($x - 1)]) {
            $dir_pos[$x] = 3;
            $dir_neg[$x] = 7;
            $tmp[$x] = 30;
        } //sudeste
        else if ($inpx[$x] == ($inpx[($x - 1)] + 1) AND $inpy[$x] == ($inpy[($x - 1)] + 1)) {
            $dir_pos[$x] = 8;
            $dir_neg[$x] = 4;
            $tmp[$x] = 45;
        } //nordeste
        else if ($inpx[$x] == ($inpx[($x - 1)] + 1) AND $inpy[$x] == ($inpy[($x - 1)] - 1)) {
            $dir_pos[$x] = 6;
            $dir_neg[$x] = 2;
            $tmp[$x] = 45;
        } //sudoeste
        else if ($inpx[$x] == ($inpx[($x - 1)] - 1) AND $inpy[$x] == ($inpy[($x - 1)] + 1)) {
            $dir_pos[$x] = 2;
            $dir_neg[$x] = 6;
            $tmp[$x] = 45;
        } //nororeste
        else if ($inpx[$x] == ($inpx[($x - 1)] - 1) AND $inpy[$x] == ($inpy[($x - 1)] - 1)) {
            $dir_pos[$x] = 4;
            $dir_neg[$x] = 8;
            $tmp[$x] = 45;
        } else {
            echo("#Uma coordenada inválida foi informada.");
            exit;
        }
        if ($mapa[($x - 1)]["dir_vento"] == $dir_pos[$x]) {
            $mod_vento[$x] = 1 + ($mapa[($x - 1)]["tipo_vento"] * 0.1);
        } else if ($mapa[($x - 1)]["dir_vento"] == $dir_neg[$x]) {
            $mod_vento[$x] = 1 - ($mapa[($x - 1)]["tipo_vento"] * 0.1);
        } else $mod_vento[$x] = 1;
        if ($mapa[($x - 1)]["dir_corrente"] == $dir_pos[$x]) {
            $mod_corr[$x] = 1 + ($mapa[($x - 1)]["tipo_corrente"] * 0.1);
        } else if ($mapa[($x - 1)]["dir_corrente"] == $dir_neg[$x]) {
            $mod_corr[$x] = 1 - ($mapa[($x - 1)]["tipo_corrente"] * 0.1);
        } else $mod_corr[$x] = 1;

        $tempo[$x] = $tmp[$x] * $mod_corr[$x] * $mod_vento[$x] * $mod_navio * $mod_leme * $mod_vela;
        if ($tempo[$x] < 5) {
            $tempo[$x] = 5;
        }

        $reducao = (1 - ($userDetails->navio["hp"] / $userDetails->navio["hp_max"])) * $tempo[$x];

        if ($aumento = $userDetails->buffs->get_efeito("aumento_velocidade_barco")) {
            $tempo[$x] -= ceil($aumento * $tempo[$x]);
        }
        $tempo[$x] += $reducao;

        if (isset($tempo[($x - 1)])) {
            $tempo[$x] += $tempo[($x - 1)];
        }

        $tempo_fin[$x] = $tempo[$x] + atual_segundo();
        $tempo_fin[$x] = round($tempo_fin[$x]);

        $query = "INSERT INTO tb_rotas (id, x, y, indice, momento) 
			VALUES ('" . $usuario["id"] . "', '" . $inpx[$x] . "', '" . $inpy[$x] . "', '" . $x . "', '" . $tempo_fin[$x] . "')";
        mysql_query($query) or die("nao foi possivel tracar a rota $x");
    } else {
        $x = sizeof($rota) + 5;
    }
}

mysql_close();