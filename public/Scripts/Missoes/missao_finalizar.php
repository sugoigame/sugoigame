<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_in_ilha();
$protector->must_be_in_missao();


if ($userDetails->missao["fim"] > atual_segundo()) {
    $protector->exit_error("Você ainda não concluiu a missão");
}

$connection->run("DELETE FROM tb_missoes_iniciadas WHERE id = ? AND cod_missao = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]));

if ($userDetails->missao["venceu"]) {
    $concluida = $connection->run("SELECT count(*) AS total FROM tb_missoes_concluidas WHERE id = ? AND cod_missao = ?",
        "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]))->fetch_array()["total"];
    if (! $concluida) {
        $connection->run("INSERT INTO tb_missoes_concluidas (id, cod_missao) VALUES (?, ?)",
            "ii", array($userDetails->tripulacao["id"], $userDetails->missao["cod_missao"]));

        $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
            "ii", array($userDetails->missao["recompensa_berries"], $userDetails->tripulacao["id"]));

        $userDetails->xp_for_all($userDetails->missao["recompensa_xp"]);
    } else {

        $result = $connection->run("SELECT quant FROM tb_missoes_concluidas_dia WHERE tripulacao_id = ? AND ilha = ? AND cast(dia as date) = CURDATE()",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

        $total_concluido_hoje = $result->count() ? $result->fetch_array()["quant"] : 0;

        if ($total_concluido_hoje) {
            $connection->run("UPDATE tb_missoes_concluidas_dia SET quant = quant + 1 WHERE tripulacao_id = ? AND ilha = ? AND cast(dia as date) = CURDATE()",
                "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
        } else {
            $connection->run("INSERT INTO tb_missoes_concluidas_dia (tripulacao_id, ilha, quant) VALUE (?,?,1)",
                "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));
        }
    }
}

echo ":";
