<?php
$contaOk = !!$userDetails->conta;
$conta = $userDetails->conta;
$conect = !!$userDetails->tripulacao;
$inrota = !!$userDetails->rotas;
$usuario = $userDetails->tripulacao;
$usuario["conta_id"] = $conta["conta_id"];
$usuario["email"] = $conta["email"];
$id = $userDetails->tripulacao ? $userDetails->tripulacao["id"] : NULL;
$inilha = $userDetails->in_ilha;
$usuario["mar"] = $userDetails->ilha ? $userDetails->ilha["mar"] : NULL;
$usuario["ilha"] = $userDetails->ilha ? $userDetails->ilha["ilha"] : NULL;
$usuario["capacidade_iventario"] = $userDetails->navio ? $userDetails->navio["capacidade_inventario"] : 0;
$inrota = !!$userDetails->rotas;
$innavio = !!$userDetails->navio;
$usuario_vip = $userDetails->vip;
$personagem = $userDetails->personagens;

if ($innavio) {
    $navio = $userDetails->navio;
    $usuario["navio"] = $userDetails->navio["cod_navio"];
    $usuario["casco"] = $userDetails->navio["cod_casco"];
    $usuario["leme"] = $userDetails->navio["cod_leme"];
    $usuario["velas"] = $userDetails->navio["cod_velas"];
    $usuario["canhao"] = $userDetails->navio["cod_canhao"];
    $usuario["navio_hp"] = $userDetails->navio["hp"];
    $usuario["navio_hp_max"] = $userDetails->navio["hp_max"];
    $usuario["navio_lvl"] = $userDetails->navio["lvl"];
    $usuario["navio_reparo"] = $userDetails->navio["reparo"];
    $usuario["navio_reparo_tipo"] = $userDetails->navio["reparo_tipo"];
    $usuario["navio_reparo_quant"] = $userDetails->navio["reparo_quant"];
    $usuario["navio_xp"] = $userDetails->navio["xp"];
    $usuario["navio_xp_max"] = $userDetails->navio["xp_max"];
}

$usuario_vip = $userDetails->vip;
$inally = !!$userDetails->ally;
$usuario["alianca"] = $userDetails->ally;