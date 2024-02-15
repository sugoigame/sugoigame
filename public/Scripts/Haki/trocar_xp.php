<?php
/**
 * Created by PhpStorm.
 * User: Luiz Eduardo
 * Date: 01/07/2017
 * Time: 12:32
 */

require('../../Includes/conectdb.php');

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();
$protector->must_be_in_ilha();
$quant = $protector->post_number_or_exit('quant');
$_POST['cod'];
// Executa a consulta
$result = $connection->run("SELECT COUNT(*) AS total FROM tb_personagens WHERE xp > 0 AND cod =?",
    "i", array($_POST['cod']));

// Verifica se a consulta foi bem-sucedida
if (!$result) {
    echo "Erro ao executar a consulta.";
} else {
    // Obtém o valor do total diretamente do resultado da consulta
    $row = $result->fetch_array();
    $xp_positiva = $row['total'];
    
    // Use a variável $xp_positiva conforme necessário
}



//$protector->exit_error($_POST['cod']);
if ($xp_positiva > 0 && $quant > $pers['xp']) {
    $userDetails->remove_xp_personagem($quant,$_POST['cod']);
    $userDetails->haki_for_all($quant);
} else {
    $protector->exit_error("Sua tripulação não tem XP suficiente");
}





echo "%haki";