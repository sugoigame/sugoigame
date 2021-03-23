<?php
if (!validate_number($_GET["combate"])) {
    echo "Batalha não encontrada ou finalizada<br/><a href='ses?=home' class='link_content'>Voltar a página inicial</a>";
    exit();
}
$combate_id = $_GET["combate"];

$result = $connection->run("SELECT * FROM tb_combate_log WHERE combate = ?", "i", $combate_id);

if (!$result->count()) {
    echo "Batalha não encontrada ou já finalizada";
    exit();
}

$combate = $result->fetch_array();

$tripulacao["1"] = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $combate["id_1"])->fetch_array();
$tripulacao["2"] = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $combate["id_2"])->fetch_array();

$id_blue = $tripulacao["1"]["id"];

$pode_assistir = $userDetails->tripulacao["adm"];

if (!$pode_assistir) {
    echo "<i class=\"fa fa-thumbs-down\"></i> Os jogadores não permitiram que essa batalha seja assistida<br/><a href='ses?=home' class='link_content'>Voltar a página inicial</a>";
    exit();
}
?>

<style type="text/css">
    <?php include "CSS/combate.css"; ?>
</style>

<div class="panel-heading">
    <?php render_combate_pvp_header($combate, $tripulacao, $id_blue); ?>
</div>

<div class="panel-body">
    <a href="./?ses=home" class="link_content">Voltar para a página inicial</a>
    <div id="navio_batalha">
        <?php include "Scripts/Batalha/batalha_tabuleiro_assistir_content_adm.php"; ?>
    </div>
</div>