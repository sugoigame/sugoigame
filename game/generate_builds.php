<?php
require "Includes/conectdb.php";

for ($x = 1, $id = 4; $x <= 50; $x++) {
    $personagens = $connection->run(
        "SELECT 
          p.lvl,
          p.haki_cri,
          p.haki_esq,
          p.classe,
          p.classe_score,
          p.hp_max,
          p.mp_max,
          p.atk 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 1),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 1),0)
            AS atk,
          p.def 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 2),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 2),0)
            AS def,
          p.agl 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 3),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 3),0)
            AS agl,
          p.res 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 4),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 4),0)
            AS res,
          p.pre 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 5),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 5),0)
            AS pre,
          p.dex 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 6),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 6),0)
            AS dex,
          p.con 
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND (ps.tipo = 3 OR ps.tipo = 6) AND pa.bonus_atr = 7),0)
           + IFNULL((SELECT SUM(pa.bonus_atr_qnt) FROM tb_personagens_skil ps INNER JOIN tb_akuma_skil_passiva pa ON ps.cod_skil = pa.cod_skil WHERE ps.cod = p.cod AND ps.tipo = 9 AND pa.bonus_atr = 7),0)
            AS con,
          p.vit
          FROM tb_personagens p WHERE p.lvl = ? AND p.pts = 0 ORDER BY RAND() LIMIT 30",
        "i", array($x)
    );

    while ($pers = $personagens->fetch_array()) {
        $pers[nome_atributo_tabela(rand(1, 7))] += rand(round($x / 5), 10);

        for ($e = 1; $e <= 7; $e++) {
            $equip = array(
                "slot" => 1,
                "categoria" => rand(1, 2),
                "lvl" => $x,
                "upgrade" => rand(1, 10)
            );
            $bonus_1 = calc_bonus_equip_atr_principal($equip);
            $bonus_2 = calc_bonus_equip_atr_secundario($equip);
            $pers[nome_atributo_tabela($e)] += $bonus_1;

            $atr_2 = $e + 1;
            if ($atr_2 == 8) {
                $atr_2 = 1;
            }
            $pers[nome_atributo_tabela($atr_2)] += $bonus_2;
        }
        for ($e = 1; $e <= 8; $e++) {
            $pers[nome_atributo_tabela($e)] = round($pers[nome_atributo_tabela($e)]);
        }

        echo
        "$id:<br/>
&nbsp;&nbsp;img: 297<br/>
&nbsp;&nbsp;skin: 0<br/>
&nbsp;&nbsp;nome: Bandido da montanha<br/>
&nbsp;&nbsp;titulo:<br/>
&nbsp;&nbsp;hp: {$pers["hp_max"]}<br/>
&nbsp;&nbsp;hp_max: {$pers["hp_max"]}<br/>
&nbsp;&nbsp;mp: {$pers["mp_max"]}<br/>
&nbsp;&nbsp;mp_max: {$pers["mp_max"]}<br/>
&nbsp;&nbsp;lvl: {$pers["lvl"]}<br/>
&nbsp;&nbsp;atk: {$pers["atk"]}<br/>
&nbsp;&nbsp;def: {$pers["def"]}<br/>
&nbsp;&nbsp;agl: {$pers["agl"]}<br/>
&nbsp;&nbsp;res: {$pers["res"]}<br/>
&nbsp;&nbsp;pre: {$pers["pre"]}<br/>
&nbsp;&nbsp;dex: {$pers["dex"]}<br/>
&nbsp;&nbsp;con: {$pers["con"]}<br/>
&nbsp;&nbsp;vit: {$pers["vit"]}<br/>
&nbsp;&nbsp;haki_esq: {$pers["haki_esq"]}<br/>
&nbsp;&nbsp;haki_cri: {$pers["haki_cri"]}<br/>
&nbsp;&nbsp;classe: {$pers["classe"]}<br/>
&nbsp;&nbsp;classe_score: {$pers["classe_score"]}<br/>
&nbsp;&nbsp;akuma: 0<br/>
&nbsp;&nbsp;tatic_d: 0<br/>
&nbsp;&nbsp;pack_habilidade_id: 1<br/>";
        $id++;
    }
}