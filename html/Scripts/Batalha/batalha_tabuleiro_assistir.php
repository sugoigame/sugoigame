<?php
$valida = "EquipeSugoiGame2012";
require_once "../../Includes/conectdb.php";

if (!validate_number($_GET["combate"])) {
    echo "Batalha não encontrada ou finalizada<br/><a href='ses?=home' class='link_content'>Voltar a página inicial</a>";
    exit();
}
$combate_id = $_GET["combate"];

$result = $connection->run("SELECT * FROM tb_combate WHERE combate = ?", "i", $combate_id);

if (!$result->count()) {
    echo "Batalha não encontrada ou finalizada<br/><a href='ses?=home' class='link_content'>Voltar a página inicial</a>";
    exit();
}

$combate = $result->fetch_array();

$tripulacao["1"] = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $combate["id_1"])->fetch_array();
$tripulacao["2"] = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $combate["id_2"])->fetch_array();

$id_blue = $tripulacao["1"]["id"];

$pode_assistir = $userDetails->tripulacao["adm"] || ($combate["permite_apostas_1"] && $combate["permite_apostas_2"]);

if ($pode_assistir) {
    include "batalha_tabuleiro_assistir_content.php";
} else {
    echo "<i class=\"fa fa-thumbs-down\"></i>Os jogadores não permitiram que essa batalha seja assistida<br/><a href='ses?=home' class='link_content'>Voltar a página inicial</a>";
}