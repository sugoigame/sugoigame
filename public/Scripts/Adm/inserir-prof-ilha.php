<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id_ilha	= $protector->post_value_or_exit('ilha');
$cod_prof		= $protector->post_value_or_exit('cod_prof');
$porf_level_max	= $protector->post_value_or_exit('porf_level_max');



$connection->run("INSERT INTO tb_ilha_profissoes (`ilha`, `profissao`, `profissao_lvl_max`) VALUES (?, ?, ?)", 'iii', [
	$id_ilha,
    $cod_prof,
    $porf_level_max


]);
echo "%inserir-prof-ilha";
