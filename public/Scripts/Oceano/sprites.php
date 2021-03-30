<?php
require "../../Includes/conectdb.php";

$sprites        = [];
$tripulacoes    = $connection->run("SELECT id, bandeira, faccao, skin_navio FROM tb_usuarios")->fetch_all_array();
foreach ($tripulacoes as $tripulacao) {
    $sprites[]  = [
        'key'       => 'ship_' . $tripulacao['id'],
        'faccao'    => $tripulacao['faccao'],
        'bandeira'  => $tripulacao['bandeira'],
        'skin'      => $tripulacao['skin_navio'],
    ];
}
echo json_encode($sprites, JSON_PRETTY_PRINT);