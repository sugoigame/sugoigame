<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers_cod = $protector->get_number_or_exit("pers");
$cod_skill = $protector->get_number_or_exit("skill");
$tipo_skill = $protector->get_number_or_exit("tipo");


if (!$pers = $userDetails->get_pers_by_cod($pers_cod)) {
    $protector->exit_error("Personagem invalido");
}

$protector->need_berries(PRECO_BERRIES_REMOVER_SPECIAL_EFFECT);

$connection->run(
    "UPDATE tb_personagens_skil SET special_effect = NULL, special_apply_type =NULL, special_target =NULL WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "iii", array($pers_cod, $cod_skill, $tipo_skill)
);

$userDetails->reduz_berries(PRECO_BERRIES_REMOVER_SPECIAL_EFFECT);

echo "-Efeito especial removido!";