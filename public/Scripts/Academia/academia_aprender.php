<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

$protector->must_be_out_of_missao();

$class = $protector->get_enum_or_exit("class", [CLASSE_ITORYU, CLASSE_PUNHO, CLASSE_CHUTE]);
$cod = $protector->get_number_or_exit("cod");
$personagem = $protector->get_tripulante_or_exit("cod");

$habilidades = DataLoader::load("habilidades")["classes"][$class]["habilidades"];

$query = "INSERT IGNORE INTO tb_personagens_skil (cod_pers, cod_skil, nome, descricao, icone, animacao) VALUES ";

$queries = [];
$values = [];
$formats = "";

foreach ($habilidades as $habilidade) {
    $habilidade = habilidade_default_values($habilidade);
    $queries[] = "(?,?,?,?,?,?)";
    $values[] = $personagem["cod"];
    $values[] = $habilidade["cod"];
    $values[] = $habilidade["nome"];
    $values[] = $habilidade["descricao"];
    $values[] = $habilidade["icone"];
    $values[] = $habilidade["animacao"];

    $formats .= "iissis";
}

$connection->run($query . implode(",", $queries), $formats, $values);

$query = "UPDATE tb_personagens SET classe='$class' WHERE cod='" . $personagem["cod"] . "'";
$connection->run($query);
$response->send_conquista_pers($personagem, $personagem["nome"] . " se tornou um " . nome_classe($class) . "!");
