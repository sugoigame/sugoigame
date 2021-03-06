<?php
$maxNivel = 50;
function formulaExp($nivel = 1) {
    return (480 + (($nivel / 5) * 100)) * $nivel;
}

echo '<table width="500px" border="1">
    <tr>
        <th align="center">Nível</th>
        <th align="center">Exp</th>
        <th align="center">Exp Acumulada</th>
    </tr>';

$total      = 0;
for ($i = 1; $i <= $maxNivel; $i++) {
    $xp     = formulaExp($i);
    $total  += $xp;

    echo "<tr>
        <td align=\"center\">{$i}</td>
        <td align=\"center\">" . number_format($xp, 0, ',', '.') . "</td>
        <td align=\"center\">" . number_format($total, 0, ',', '.') . "</td>
    </tr>";
}
echo '</table>';
echo 'Total: ' . $total;