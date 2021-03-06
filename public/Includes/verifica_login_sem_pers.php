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