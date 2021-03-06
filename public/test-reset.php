<?php
require_once 'Includes/conectdb.php';

$result = $connection->run("SELECT * FROM tb_personagens");
while ($row = $result->fetch_array()) {
    $att    = (($row['lvl'] - 1) * PONTOS_POR_NIVEL) + 69;
    $hp     = (($row['lvl'] - 1) * 100) + 2500;
    $mp     = (($row['lvl'] - 1) * 7) + 100;

    $bonus = get_bonus_excelencia($row['classe'], $row['excelencia_lvl']);

    $hp += $bonus['hp_max'];
    $mp += $bonus['mp_max'];

    $connection->run("UPDATE tb_personagens 
        SET atk     = ?,
            def     = ?,
            agl     = ?,
            res     = ?,
            pre     = ?,
            dex     = ?,
            con     = ?,
            vit     = ?,
            pts     = ?,
            hp      = ?,
            hp_max  = ?,
            mp_max  = ?,
            mp      = ?,
            xp_max  = ?
        WHERE cod = ?", "iiiiiiiiiiiiiii", [
            1 + $bonus['atk'],
            1 + $bonus['def'],
            1 + $bonus['agl'],
            1 + $bonus['res'],
            1 + $bonus['pre'],
            1 + $bonus['dex'],
            1 + $bonus['con'],
            1 + $bonus['vit'],
            $att,
            $hp,
            $hp,
            $mp,
            $mp,
            formulaExp($row['lvl']),
            $row['cod']
        ]
    );
    $userDetails->remove_skills_classe($row);
}