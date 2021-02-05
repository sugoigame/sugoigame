<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod = $protector->get_number_or_exit("cod");
$tipo = $protector->get_number_or_exit("tipo");


if ($tipo == TIPO_ITEM_REAGENT) {
    $itens = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_reagents", "cod_reagent", TIPO_ITEM_REAGENT,
        "WHERE origem.id = ? AND origem.cod_item = ?", "ii", array($userDetails->tripulacao["id"], $cod));
} else {
    $itens = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_missao", "id", TIPO_ITEM_MISSAO,
        "WHERE origem.id = ? AND origem.cod_item = ?", "ii", array($userDetails->tripulacao["id"], $cod));
}

if (!count($itens)) {
    $protector->exit_error("Item inválido");
}

$item = $itens[0];

if (!$item["method"]) {
    $protector->exit_error("Este item não pode ser usado");
}

$usaveis = new ItemUsavel($userDetails, $connection, $protector, $response);

$method_params = explode(",", $item["method"]);

$method = $method_params[0];

$res = $usaveis->$method($item, $method_params);

if (!is_array($res) || !isset($res["prevent_remove"])) {
    $userDetails->reduz_item($cod, $tipo, 1);
}

$msg = is_array($res) ? $res["message"] : $res;
if (substr($msg, 0, 1) == "%") {
    echo $msg;
} else {
    $response->send_share_msg($msg);
}