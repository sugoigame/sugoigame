<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers_cod = $protector->get_number_or_exit("pers");
$tipo = $protector->get_enum_or_exit("tipo", array(1, 2, 3));

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}

if ($pers["haki_pts"] <= 0) {
    $protector->exit_error("Esse personagem nao tem pontos para distribuir.");
}

if ($tipo == 1) {
    if ($pers["haki_esq"] >= MAX_POINTS_MANTRA) {
        $protector->exit_error("Este personagem atingiu o limite de treino nesse Haki.");
    }

    $connection->run("UPDATE tb_personagens SET haki_esq = haki_esq + 1, haki_pts = haki_pts - 1 WHERE cod = ?",
        "i", array($pers_cod));
} else if ($tipo == 2) {
    if ($pers["haki_cri"] >= MAX_POINTS_ARMAMENTO) {
        $protector->exit_error("Este personagem atingiu o limite de treino nesse Haki.");
    }
    $connection->run("UPDATE tb_personagens SET haki_cri = haki_cri + 1, haki_pts = haki_pts - 1 WHERE cod = ?",
        "i", array($pers_cod));
} else if ($tipo == 3) {
    if ($pers["haki_hdr"] >= MAX_POINTS_HDR || $pers_cod != $userDetails->capitao["cod"]) {
        $protector->exit_error("Este personagem atingiu o limite de treino nesse Haki.");
    }
    $connection->run("UPDATE tb_personagens SET haki_hdr = haki_hdr + 1, haki_pts = haki_pts - 1 WHERE cod = ?",
        "i", array($pers_cod));

    if (!$pers["haki_hdr"]) {
        $connection->run(
            "INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon)
            VALUES (?, ?, '1', 'Haoshoku Haki',
            'Uma forma rara de Haki que não pode ser alcançado por meio de treinamento
             e apenas um em um milhão de pessoas a transportar.', '900')",
            "ii", array($pers_cod, $COD_HAOSHOKU_LVL[1])
        );
    } else {
        $connection->run(
            "UPDATE tb_personagens_skil SET cod_skil = ? WHERE cod = ? AND cod_skil = ? AND tipo = ?",
            "iiii", array($COD_HAOSHOKU_LVL[$pers["haki_hdr"] + 1], $pers_cod, $COD_HAOSHOKU_LVL[$pers["haki_hdr"]], TIPO_SKILL_ATAQUE_CLASSE)
        );
    }
}
echo ":haki&outro=" . $pers_cod;