<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();

$ilha = \Regras\Ilhas::get_ilha($userDetails->ilha['ilha']);

$faccao_ilha = $connection
    ->run('SELECT faccao FROM tb_mapa WHERE ilha = ?', 'i', [$userDetails->ilha['ilha']])
    ->fetch_array()['faccao'];
$faccao = \Utils\Data::find_inside('mundo', 'faccoes', ['cod' => $faccao_ilha]);

mt_srand($userDetails->ilha['ilha']);
$viajante_img = rand(1, PERSONAGENS_MAX);
mt_srand();

// todo coletar producao

$connection->run("UPDATE tb_usuarios SET viajante_faccao = ?, viajante_ilha_origem = ?, viajante_img = ?, viajante_ultima_coleta = NOW() WHERE id = ?",
    "iiii", [$faccao_ilha, $userDetails->ilha["ilha"], $viajante_img, $userDetails->tripulacao["id"]]);

echo "|O viajante se juntou à você!";
