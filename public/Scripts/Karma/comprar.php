<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$buff = $protector->get_number_or_exit("buff");
$tipo = $protector->get_alphanumeric_or_exit("tipo");

if ($tipo != TIPO_KARMA_BOM && $tipo != TIPO_KARMA_MAU) {
    $protector->exit_error("Tipo inválido");
}

switch ($buff) {
    case 1:
    case 6:
        $preco = PRECO_KARMA_BONUS_1;
        break;
    case 2:
    case 7:
        $preco = PRECO_KARMA_BONUS_2;
        break;
    case 3:
    case 8:
        $preco = PRECO_KARMA_BONUS_3;
        break;
    case 4:
    case 9:
        $preco = PRECO_KARMA_BONUS_4;
        break;
    case 5:
    case 10:
        $preco = PRECO_KARMA_BONUS_5;
        break;
    default:
        $protector->exit_error("Bonus inválido");
        return;
}

$karma = "karma_$tipo";

if ($userDetails->tripulacao[$karma] < $preco) {
    $protector->exit_error("Você não tem pontos de Karma suficientes");
}

if ($userDetails->buffs->has_buff($buff)) {
    $protector->exit_error("Você já possui esse bonus ativo.");
}

$userDetails->buffs->add_buff($buff, 24 * 60 * 60);

$connection->run("UPDATE tb_usuarios SET $karma = $karma - ? WHERE id = ?",
    "ii", array($preco, $userDetails->tripulacao["id"]));

echo "O efeito foi ativado e irá expirar em 24 horas";