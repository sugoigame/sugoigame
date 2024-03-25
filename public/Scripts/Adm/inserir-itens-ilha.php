<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id_trip	= $protector->post_value_or_exit('ilha');
$cod_item		= $protector->post_value_or_exit('cod_item');
$tipo_itemo	= $protector->post_value_or_exit('tipo_item');



$connection->run("INSERT INTO tb_ilha_itens (`ilha`, `cod_item`, `tipo_item`) VALUES (?, ?, ?)", 'iii', [
	$id_trip,
    $cod_item,
    $tipo_itemo


]);
echo "%inserir-itens-ilha";
