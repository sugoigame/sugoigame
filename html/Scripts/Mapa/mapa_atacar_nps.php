<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_alive();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();

$alvo = $protector->get_number_or_exit("id");

$contem = $connection->run("SELECT * FROM tb_mapa_contem WHERE increment_id = ?", "i", $alvo);

if (!$contem->count()) {
    $protector->exit_error("O alvo não foi encontrado.");
}

$contem = $contem->fetch_array();

if (!can_attack_nps($contem)) {
    $protector->exit_error("Você não pode atacar este alvo");
}

$npss = DataLoader::load("nps");

$nps = $npss[$contem["nps_id"]];

atacar_rdm($nps["rdm_id"]);

$connection->run("DELETE FROM tb_mapa_contem WHERE increment_id = ?", "i", $alvo);

echo "%combate";