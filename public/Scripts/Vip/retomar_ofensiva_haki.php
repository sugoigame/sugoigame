<?php
/**
 * Created by PhpStorm.
 * User: Luiz Eduardo
 * Date: 01/07/2017
 * Time: 15:06
 */

require('../../Includes/conectdb.php');

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod = $protector->get_number_or_exit('cod');
$tipo = $protector->get_enum_or_exit('tipo', array('gold', 'dobrao'));

if (!$pers = $userDetails->get_pers_by_cod($cod)) {
    $protector->exit_error('Personagem inválido');
}

$hoje = $connection->run('SELECT CURRENT_DATE as hoje')->fetch_array()['hoje'];
$dias = ((strtotime($hoje) - strtotime($pers['haki_ultimo_dia_treino'])) / (24 * 60 * 60)) - 1;

if ($tipo == 'gold') {
    $protector->need_gold(PRECO_GOLD_RECUPERAR_OFENSIVA_MESTRE_HAKI * $dias);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_RECUPERAR_OFENSIVA_MESTRE_HAKI * $dias);
}

$connection->run('UPDATE tb_personagens SET haki_ultimo_dia_treino = SUBDATE(CURRENT_DATE, INTERVAL 1 DAY) WHERE cod = ?',
    'i', $cod);

if ($tipo == 'gold') {
    $userDetails->reduz_gold(PRECO_GOLD_RECUPERAR_OFENSIVA_MESTRE_HAKI * $dias, 'retoma_ofensiva_haki');
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_RECUPERAR_OFENSIVA_MESTRE_HAKI * $dias, 'retoma_ofensiva_haki');

}

echo '-A ofensiva foi retomada!';