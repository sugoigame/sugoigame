<div class="panel-body">
    <table>
        <thead>
        <tr>
            <th> Vencedor antes</th>
            <th> Vencedor depois</th>
            <th> Perdedor antes</th>
            <th> Perdedor depois</th>
            <th> Novo perdedor</th>
        </tr>
        </thead>
        <tbody>
        <?php

        for ($rep_vencedor = 0; $rep_vencedor <= 50000; $rep_vencedor += 500) {
            for ($rep_perdedor = 0; $rep_perdedor <= 50000; $rep_perdedor += 500) {
                $reputacao = calc_reputacao($rep_vencedor, $rep_perdedor, 50, 50);
                $nova_rep = calc_reputacao($rep_vencedor, max(0, $rep_perdedor - 5000), 50, 50);

                echo "<tr><td>$rep_vencedor</td><td>" . $reputacao["vencedor_rep"] . "</td><td>$rep_perdedor</td><td>" . $reputacao["perdedor_rep"] . "</td><td>" . $nova_rep["perdedor_rep"] . "</td></tr>";
            }
        }
        ?>
        </tbody>
    </table>
</div>