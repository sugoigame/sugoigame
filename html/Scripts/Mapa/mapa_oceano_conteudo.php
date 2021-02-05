<?php $distancia_visao = $userDetails->ilha["nevoa"] ? 2.5 : 3.5; ?>
<?php if ($userDetails->vip["luneta"]) {
    $distancia_visao++;
} ?>

<?php $npss = DataLoader::load("nps"); ?>

<?php
$coord_x_navio = $userDetails->tripulacao["coord_x_navio"];
$coord_y_navio = $userDetails->tripulacao["coord_y_navio"];
?>

<?php
$coordenadas_db = $connection->run("SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ?",
    "iiii", array($coord_x_navio - 4, $coord_x_navio + 4, $coord_y_navio - 4, $coord_y_navio + 4));

$coordenadas = [];
while ($coordenada = $coordenadas_db->fetch_array()) {
    $coordenadas[$coordenada["x"]][$coordenada["y"]] = $coordenada;
}

$contem_db = $connection->run(
    "SELECT contem.*, usr.*
    FROM tb_mapa_contem contem 
    LEFT JOIN tb_usuarios usr ON contem.id = usr.id 
    LEFT JOIN tb_ilha_mercador mer ON contem.mercador_id = mer.id 
    WHERE contem.x >= ? AND contem.x <= ? AND contem.y >= ? AND contem.y <= ?",
    "iiii", array($coord_x_navio - 4, $coord_x_navio + 4, $coord_y_navio - 4, $coord_y_navio + 4)
);

$all_contem = [];
while ($coordenada = $contem_db->fetch_array()) {
    $all_contem[$coordenada["x"]][$coordenada["y"]][] = $coordenada;
}

$escala_meu_navio = $userDetails->buffs->get_efeito("escala_navio");
if (!$escala_meu_navio) {
    $escala_meu_navio = 1;
}
?>

<div id="oceano">
    <table id="agua" class="table_sem_borda">
        <?php for ($coord_y = $coord_y_navio - 4; $coord_y <= $coord_y_navio + 4; $coord_y++): ?>
            <tr>
                <?php for ($coord_x = $coord_x_navio - 4; $coord_x <= $coord_x_navio + 4; $coord_x++): ?>
                    <td>
                        <?php if (sqrt(pow($coord_x_navio - $coord_x, 2) + pow($coord_y_navio - $coord_y, 2)) <= $distancia_visao): ?>

                            <?php
                            $x = calc_map_limit_x($coord_x);
                            $y = calc_map_limit_y($coord_y);
                            ?>

                            <?php $coord_info = isset($coordenadas[$x]) && isset($coordenadas[$x][$y]) ? $coordenadas[$x][$y] : array("tipo_vento" => 0, "tipo_corrente" => 0); ?>

                            <!-- Icone do navio do jogador -->
                            <?php if ($coord_x_navio == $x AND $coord_y_navio == $y): ?>
                                <img class="mapa_vento mapa_hp" src="Imagens/Personagens/Barra/hp_max.jpg"
                                     width="40px"
                                     height="3px">
                                <img class="mapa_vento mapa_hp" src="Imagens/Personagens/Barra/hp.jpg"
                                     width="<?= ($userDetails->navio["hp"] / $userDetails->navio["hp_max"]) * 40; ?>px"
                                     height="3px">
                                <?php $skin_offset = $SKIN_NAVIO_OFFSET[$userDetails->tripulacao["faccao"]][$userDetails->tripulacao["skin_navio"]]; ?>
                                <img class="mapa_vento mapa_navio"
                                     height="<?= $skin_offset["height"] * $escala_meu_navio ?>px"
                                     style="margin-left: <?= $skin_offset["left"] + ((1 - $escala_meu_navio) * ($skin_offset["height"] / 2)) ?>px; margin-top: <?= $skin_offset["top"] + ((1 - $escala_meu_navio) * ($skin_offset["height"])) ?>px"
                                     src="Imagens/Bandeiras/navio_skin.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>&s=<?= $userDetails->tripulacao["skin_navio"] ?>&d=<?= $userDetails->tripulacao["direcao_navio"] ?>"/>

                            <?php endif; ?>

                            <!-- Icone dos outros navios -->
                            <?php $contem = isset($all_contem[$x]) && isset($all_contem[$x][$y]) ? $all_contem[$x][$y] : array(); ?>
                            <?php if (count($contem)): ?>
                                <?php
                                $jogador_in = NULL;
                                $bandeira_in = NULL;
                                $faccao_in = NULL;
                                $direcao_in = NULL;
                                $skin_in = NULL;
                                $nps = NULL;
                                $mercador = NULL;
                                foreach ($contem as $jogador) {
                                    if ($jogador["id"]) {
                                        if ($jogador["id"] != $userDetails->tripulacao["id"]) {
                                            $jogador_in = $jogador["id"];
                                            $faccao_in = $jogador["faccao"];
                                            $bandeira_in = $jogador["bandeira"];
                                            $direcao_in = $jogador["direcao_navio"];
                                            $skin_in = $jogador["skin_navio"];

                                            if ($faccao_in != $userDetails->tripulacao["faccao"]) {
                                                break;
                                            }
                                        }
                                    } elseif ($jogador["nps_id"]) {
                                        $nps = $npss[$jogador["nps_id"]];
                                    } else {
                                        $mercador = $jogador;
                                    }
                                }
                                ?>
                                <?php if ($bandeira_in): ?>
                                    <?php $escala_navio = $userDetails->buffs->get_efeito_from_tripulacao("escala_navio", $jogador_in); ?>
                                    <?php if (!$escala_navio) {
                                        $escala_navio = 1;
                                    } ?>
                                    <?php $skin_offset = $SKIN_NAVIO_OFFSET[$faccao_in][$skin_in]; ?>
                                    <img class="mapa_vento mapa_navio"
                                         height="<?= $skin_offset["height"] * $escala_navio ?>px"
                                         style="margin-left: <?= $skin_offset["left"] + ((1 - $escala_navio) * ($skin_offset["height"] / 2)) ?>px; margin-top: <?= $skin_offset["top"] + ((1 - $escala_navio) * ($skin_offset["height"])) ?>px"
                                         src="Imagens/Bandeiras/navio_skin.php?cod=<?= $bandeira_in ?>&f=<?= $faccao_in ?>&s=<?= $skin_in ?>&d=<?= $direcao_in ?>"/>
                                <?php endif; ?>

                                <?php if ($nps): ?>
                                    <img src="Imagens/Batalha/Npc/Navios/<?= $nps["icon"]; ?>.png"
                                         class="mapa_vento mapa_navio"/>
                                <?php endif; ?>

                                <?php if ($mercador): ?>
                                    <img src="Imagens/Batalha/Npc/Navios/4.png"
                                         class="mapa_vento mapa_navio"/>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- icones dos ventos -->
                            <?php
                            if ($coord_info["tipo_vento"] AND $userDetails->navegadores) :
                                if (($coord_info["tipo_vento"] == 1)
                                    OR ($userDetails->lvl_navegador > 2 AND $coord_info["tipo_vento"] == 2)
                                    OR ($userDetails->lvl_navegador > 9 AND $coord_info["tipo_vento"] == 3)
                                ) :
                                    ?>
                                    <img class="mapa_vento"
                                         src="Imagens/Oceano/Ventos/<?= get_tipo_vento($coord_info["tipo_vento"]) ?>.png"
                                         style="-webkit-transform: rotate(<?= ($coord_info["dir_vento"] - 1) * 45 ?>deg);
                                                 -moz-transform: rotate(<?= ($coord_info["dir_vento"] - 1) * 45 ?>deg);
                                                 -o-transform: rotate(<?= ($coord_info["dir_vento"] - 1) * 45 ?>deg);"/>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- icones das correntes -->
                            <?php
                            if ($coord_info["tipo_corrente"] AND $userDetails->navegadores) :
                                if (($coord_info["tipo_corrente"] == 1)
                                    OR ($userDetails->lvl_navegador > 3 AND $coord_info["tipo_corrente"] == 2)
                                    OR ($userDetails->lvl_navegador > 8 AND $coord_info["tipo_corrente"] == 3)
                                ) :
                                    ?>
                                    <img class="mapa_vento"
                                         src="Imagens/Oceano/Correntes/<?= get_tipo_corrente($coord_info["tipo_corrente"]) ?>.png"
                                         style="-webkit-transform: rotate(<?= ($coord_info["dir_corrente"] - 1) * 45 ?>deg);
                                                 -moz-transform: rotate(<?= ($coord_info["dir_corrente"] - 1) * 45 ?>deg);
                                                 -o-transform: rotate(<?= ($coord_info["dir_corrente"] - 1) * 45 ?>deg);"/>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- icones do mapa de fundo -->
                            <img src="Imagens/Mapa/Mapa_Oceano/Mapa_<?= sprintf("%02d", $x) ?>_<?= sprintf("%02d", $y) ?>.jpg">

                        <?php endif; ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
</div>