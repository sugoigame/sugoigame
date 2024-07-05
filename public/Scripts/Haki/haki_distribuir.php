<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_tripulante_or_exit("cod");
$tipo = $protector->get_enum_or_exit("tipo", array("haki_esq", "haki_cri", "haki_blo", "haki_hdr"));
$quant = $protector->get_number_or_exit("quant");

$max = array(
    "haki_esq" => MAX_POINTS_MANTRA, "haki_cri" => MAX_POINTS_ARMAMENTO, "haki_blo" => MAX_POINTS_HAKI_AVANCADO, "haki_hdr" => MAX_POINTS_HDR
);

if ($quant < 0 || ($quant - $pers[$tipo]) > $pers["haki_pts"] || $quant > $max[$tipo]) {
    $protector->exit_error("Quantidade inválida");
}

$old_hdr_lvl = $pers["haki_hdr"];

$dif = $quant - $pers[$tipo];
$pers[$tipo] = $quant;
$pers["haki_pts"] -= $dif;

if ($tipo == "haki_hdr") {
    if ($pers["cod"] != $userDetails->capitao["cod"]) {
        $protector->exit_error("Este personagem atingiu o limite de treino nesse Haki.");
    }

    if (! $old_hdr_lvl) {
        $connection->run(
            "INSERT INTO tb_personagens_skil (cod_pers, cod_skil, nome, descricao, icone)
            VALUES (?, ?, 'Haoshoku Haki',
            'Uma forma rara de Haki que não pode ser alcançado por meio de treinamento
             e apenas um em um milhão de pessoas a transportar.', '900')",
            "ii", array($pers["cod"], $COD_HAOSHOKU_LVL[$pers["haki_hdr"]])
        );
    } elseif (! $pers["haki_hdr"]) {
        $connection->run(
            "DELETE FROM tb_personagens_skil WHERE cod_pers = ? AND cod_skil = ?",
            "ii", array($pers["cod"], $COD_HAOSHOKU_LVL[$old_hdr_lvl])
        );
    } else {
        $connection->run(
            "UPDATE tb_personagens_skil SET cod_skil = ? WHERE cod_pers = ? AND cod_skil = ?",
            "iii", array($COD_HAOSHOKU_LVL[$pers["haki_hdr"]], $pers["cod"], $COD_HAOSHOKU_LVL[$old_hdr_lvl])
        );
    }
}

$connection->run("UPDATE tb_personagens SET haki_esq=?, haki_cri=?, haki_blo=?, haki_hdr=?, haki_pts=? WHERE cod = ?",
    "iiiiii", array($pers["haki_esq"], $pers["haki_cri"], $pers["haki_blo"], $pers["haki_hdr"], $pers["haki_pts"], $pers["cod"]));

echo ":";
