<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();
$protector->must_be_out_of_any_kind_of_combat();

if (!$userDetails->navio["cod_canhao"]) {
    $protector->exit_error("Você precisa de um canhão no navio");
}

$alvo_id = $protector->get_number_or_exit("id");

$balas = $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?",
    "ii", array($userDetails->tripulacao["id"], TIPO_ITEM_BALA_CANHAO));

if (!$balas->count()) {
    $protector->exit_error("Você precisa de balas de canhão para acertar seu inimigo");
}

$result = get_player_data_for_combat_check($alvo_id);
if (!$result->count()) {
    $protector->exit_error("Alvo não encontrado");
}
$alvo = $result->fetch_array();

if (!can_dispair_cannon($alvo)) {
    $protector->exit_error("Você não pode atirar nesse jogador");
}

$userDetails->reduz_item(1, TIPO_ITEM_BALA_CANHAO, 1);

$canhao = $connection->run("SELECT * FROM tb_item_navio_canhao WHERE cod_canhao = ?",
    "i", array($userDetails->navio["cod_canhao"]))->fetch_array();


$chance = $canhao["bonus"];

if (rand(0, 100) > $chance) {
    echo "Você errou o disparo...";
    exit();
}

$navio_alvo = $connection->run("SELECT * FROM tb_usuario_navio WHERE id = ?", "i", $alvo_id)->fetch_array();

$dano = 10;

if ($aumento = $userDetails->buffs->get_efeito("aumento_dano_canhao")) {
    $dano += (int)($aumento * $dano);
}

$nhp = max(1, $navio_alvo["hp"] - $dano);

$reducao_rota = 1 - ($nhp / $navio_alvo["hp_max"]);

$connection->run("UPDATE tb_rotas SET momento = momento + CAST(((momento - unix_timestamp()) * $reducao_rota) as UNSIGNED) WHERE id = ?",
    "i", array($alvo_id));

$connection->run("UPDATE tb_usuario_navio SET hp = ? WHERE id = ?",
    "ii", array($nhp, $alvo_id));

echo "Você causou $dano pontos de dano no navio adversário";
