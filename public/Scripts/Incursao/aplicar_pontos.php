<?php
require "../../Includes/conectdb.php";

function calc_evolucao($pontos, $nivel, $aumento) {
    while ($aumento > 0) {
        $requisito = ceil(pow(1.2, $nivel));

        if ($pontos + $aumento >= $requisito) {
            $pontos = 0;
            $aumento -= ($requisito - $pontos);
            $nivel++;
        } else {
            $pontos += $aumento;
            $aumento = 0;
        }
    }

    return array(
        "pontos" => $pontos,
        "nivel" => $nivel
    );
}

$protector->need_tripulacao();
$protector->must_be_in_ilha();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();

$pers = $protector->post_number_or_exit("pers");
$quant = $protector->post_number_or_exit("quant");

$personagem = $userDetails->get_pers_by_cod($pers);

if (!$personagem || !$personagem["classe"]) {
    $protector->exit_error("Personagem inválido");
}

$coluna_ponto = "pontos_" . strtolower(nome_classe($personagem["classe"]));

$pontos = $connection->run("SELECT * FROM tb_incursao_pontos WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

if (!$pontos->count()) {
    $protector->exit_error("Você não tem pontos suficientes");
}

$pontos = $pontos->fetch_array()[$coluna_ponto];

if (!$pontos || $pontos < $quant) {
    $protector->exit_error("Você não tem pontos suficientes");
}

$pontos_aplicados = $connection->run("SELECT * FROM tb_incursao_personagem WHERE personagem_id = ? AND ilha = ?",
    "ii", array($pers, $userDetails->ilha["ilha"]));

if (!$pontos_aplicados->count()) {
    $pontos = calc_evolucao(0, 0, $quant);
    $connection->run("INSERT INTO tb_incursao_personagem (tripulacao_id, personagem_id, ilha, pontos, nivel)  VALUE (?, ?, ?, ?, ?)",
        "iiiii", array($userDetails->tripulacao["id"], $pers, $userDetails->ilha["ilha"], $pontos["pontos"], $pontos["nivel"]));
} else {
    $pontos_aplicados = $pontos_aplicados->fetch_array();
    $pontos = calc_evolucao($pontos_aplicados["pontos"], $pontos_aplicados["nivel"], $quant);

    $connection->run("UPDATE tb_incursao_personagem SET pontos = ?, nivel = ? WHERE personagem_id = ? AND ilha = ?",
        "iiii", array($pontos["pontos"], $pontos["nivel"], $pers, $userDetails->ilha["ilha"]));
}

$connection->run("UPDATE tb_incursao_pontos SET $coluna_ponto = $coluna_ponto - ? WHERE tripulacao_id = ? AND ilha = ?",
    "iii", array($quant, $userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

echo "-Pontos aplicados";