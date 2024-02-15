<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_tripulante_or_exit("cod");
$atr1 = $protector->get_number_or_exit("atr1");
$atr2 = $protector->get_number_or_exit("atr2");
$atr3 = $protector->get_number_or_exit("atr3");

if ($atr1 < 1 || $atr1 > 8 || $atr2 < 1 || $atr2 > 8 || $atr3 < 1 || $atr3 > 8) {
    $protector->exit_error("Atributo inválido");
}
$atr1 = nome_atributo_tabela($atr1);
$atr2 = nome_atributo_tabela($atr2);
$atr3 = nome_atributo_tabela($atr3);

$hp_razao = $pers["hp"] / $pers["hp_max"];

$total_para_distribuir = (($pers["lvl"] - 1) * PONTOS_POR_NIVEL) + PONTOS_INICIAIS;
$resto = 0;
for ($i = 1; $i <= 8; $i++) {
    $atri = nome_atributo_tabela($i);
    $pers[$atri] = 1;
    if ($atri === $atr1) {
        $quant = $total_para_distribuir * 0.5;
        $resto += $quant - floor($quant);
        $pers[$atri] += floor($quant);
    }
    if ($atri === $atr2) {
        $quant = $total_para_distribuir * 0.3;
        $resto += $quant - floor($quant);
        $pers[$atri] += floor($quant);
    }
    if ($atri === $atr3) {
        $quant = $total_para_distribuir * 0.2;
        $resto += $quant - floor($quant);
        $pers[$atri] += floor($quant);
    }
}
$pers[$atr1] += $resto;

$hp_max = calc_pers_hp_max($pers);

$hp = floor($hp_max * $hp_razao);

$connection->run("UPDATE tb_personagens SET hp=?, hp_max=?, atk=?, def=?, agl=?, res=?, pre=?, dex=?, con=?, vit=?, pts=? WHERE cod=?",
    "iiiiiiiiiiii", array($hp, $hp_max,
        $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"],
        $pers["con"], $pers["vit"], 0, $pers["cod"]));

echo ":";
