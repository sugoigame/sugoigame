<?php
$inmissaor = !!$userDetails->missao_r;
$inrecrute = !!$userDetails->tripulacao["recrutando"];
$inmissao = !!$userDetails->missao || $inrecrute;
$usuario["missao"] = $userDetails->missao;