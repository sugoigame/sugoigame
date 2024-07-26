<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();

$faccao_id = $protector->get_number_or_exit("faccao");

$faccoes = \Utils\Data::load('mundo')['faccoes'];
$faccao = array_find($faccoes, ["cod" => $faccao_id]);
if (! $faccao) {
    $protector->exit_error("Facção inválida");
}

$relacao = $connection
    ->run('SELECT * FROM tb_tripulacao_faccao WHERE tripulacao_id = ? AND faccao_id = ?',
        'ii', [$userDetails->tripulacao['id'], $faccao["cod"]]);

if (! $relacao->count()) {
    $protector->exit_error("Reputação insuficiente");
}
$relacao = $relacao->fetch_array();

if (! isset($faccao["evolui_outros"]) || ! $faccao["evolui_outros"]) {
    $relacao_base = $connection
        ->run('SELECT * FROM tb_tripulacao_faccao WHERE tripulacao_id = ? AND faccao_id = ?',
            'ii', [$userDetails->tripulacao['id'], $faccoes[0]["cod"]]);
    $nivel_base = $relacao_base->count() ? $relacao_base->fetch_array()['nivel'] : 0;
} else {
    $nivel_base = 0;
}

$reputacao_necessaria = \Regras\Influencia::get_reputacao_necessaria($relacao['nivel'] ?: 0);
$reputacao =
    ($relacao['reputacao'] ?: 0) +
    \Regras\Influencia::get_reputacao_produzida(json_decode($relacao['producao'], true) ?: []);
$nivel = ($relacao['nivel'] ?: 0) + ($nivel_base ?: 0);

if ($reputacao < $reputacao_necessaria || $nivel >= $userDetails->tripulacao['influencia']) {
    $protector->exit_error("Reputação isuficiente");
}

// resgata a reputacao
$relacao["reputacao"] = $reputacao;
$producao = json_decode($relacao["producao"], true);
$total = 0;
foreach ($producao as $produzido) {
    $total += $produzido["quantidade"];
}
$relacao["producao"] = json_encode([[
    "inicio" => atual_segundo(),
    "quantidade" => $total
]]);

// consome a reputacao
$relacao["reputacao"] -= $reputacao_necessaria;

// sobe de nivel
$relacao["nivel"]++;

$connection
    ->run('UPDATE tb_tripulacao_faccao SET reputacao = ?, producao = ?, nivel = ? WHERE tripulacao_id = ? AND faccao_id = ?',
        'isiii', [$relacao["reputacao"], $relacao["producao"], $relacao["nivel"], $userDetails->tripulacao['id'], $faccao["cod"]]);

echo ":";
