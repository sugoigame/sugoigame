<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();

$requisitos = \Regras\Influencia::get_requisitos($userDetails->tripulacao['influencia']);
$relacoes = \Regras\Influencia::get_relacoes();

foreach ($requisitos as $requisito) {
    $faccao = \Utils\Data::find_inside('mundo', 'faccoes', ['cod' => $requisito['faccao']]);
    $relacao = array_find($relacoes, ['faccao_id' => $requisito['faccao']]);
    $concluido = $relacao["nivel"] >= $requisito['nivel'];
    if (! $concluido) {
        $protector->exit_error("Você não atende todos os requisitos para evoluir a influência");
    }
}

$connection->run("UPDATE tb_usuarios SET influencia = influencia + 1 WHERE id = ?",
    "i", [$userDetails->tripulacao["id"]]);

echo ":";
