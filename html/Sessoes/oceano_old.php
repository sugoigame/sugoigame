<?php
function pode_navegar($x, $y, $navegaveis) {
    global $userDetails;
    return isset($navegaveis[$x]) && isset($navegaveis[$x][$y]) && $navegaveis[$x][$y] && !$userDetails->rotas;
}

?>

<?php function render_profissao_time_panel($label, $pers, $time, $duracao, $min_sec_id, $script_id, $cod_running, $action) { ?>
    <?php global $userDetails; ?>
    <div class="list-group-item col-md-4">
        <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg">
        <h4><?= $label ?>: <?= $pers["nome"] ?></h4>
        <?php if ($time && $time > atual_segundo()) : ?>
            <p>
                Tempo de espera até poder <?= strtolower($action) ?> novamente:
                <span id="<?= $min_sec_id ?>_min">
                    <?= transforma_tempo_min($time - atual_segundo()) ?>
                </span>
                <span id="<?= $min_sec_id ?>_sec" style="display: none;"><?= $time - atual_segundo() ?></span>
            </p>
            <?php $preco = ceil(($time - atual_segundo()) / $duracao) * 2 - 1; ?>
            <p>
                <button href="Mapa/<?= $script_id ?>_finalizar.php?pers=<?= $pers["cod"] ?>&tipo=gold"
                        class="link_confirm btn btn-info"
                        data-question="Deseja <?= $action ?> novamente?"
                    <?= $userDetails->conta["gold"] >= $preco ? "" : "disabled" ?>>
                    <?= $preco ?> <img src="Imagens/Icones/Gold.png">
                    <?= $action ?> novamente
                </button>
            </p>
            <?php $preco = ceil($preco * 1.2); ?>
            <p>
                <button href="Mapa/<?= $script_id ?>_finalizar.php?pers=<?= $pers["cod"] ?>&tipo=dobroes"
                        class="link_confirm btn btn-info"
                        data-question="Deseja <?= $action ?> novamente?"
                    <?= $userDetails->conta["dobroes"] >= $preco ? "" : "disabled" ?>>
                    <?= $preco ?> <img src="Imagens/Icones/Dobrao.png">
                    <?= $action ?> novamente
                </button>
            </p>
        <?php else: ?>
            <p>
                Espera:
                <?= transforma_tempo_min($duracao) ?>
            </p>
            <button href="link_Mapa/<?= $script_id ?>_finalizar.php?pers=<?= $pers["cod"] ?>&tipo=normal"
                    class="link_send btn btn-primary">
                <?= $action ?>
            </button>
        <?php endif; ?>
    </div>
<?php } ?>

<div class="panel-heading">
    Oceano
</div>

<style type="text/css">
    <?php include 'CSS/oceano.css'; ?>
</style>

<?php include "JS/oceano.php"; ?>

<div class="panel-body">
    <?php $distancia_visao = $userDetails->ilha["nevoa"] ? 2.5 : 3.5; ?>
    <?php if ($userDetails->vip["luneta"]) {
        $distancia_visao++;
    } ?>

    <?php
    function in_rota($x, $y) {
        global $userDetails;
        $rotas = array();
        if (!$userDetails->rotas) {
            return $rotas;
        }
        foreach ($userDetails->rotas as $rota) {
            if ($rota["x"] == $x && $rota["y"] == $y) {
                $rotas[] = $rota;
            }
        }

        return $rotas;
    }

    ?>

    <?php
    $coord_x_navio = $userDetails->tripulacao["coord_x_navio"];
    $coord_y_navio = $userDetails->tripulacao["coord_y_navio"];
    ?>
    <div class="row">
        <div class="col-md-7">
            <div id="info_mapa">
                <?php if (!$userDetails->rotas) : ?>
                    <div id="tracar_rota">
                        <form id="formulario_tracar_rota">
                            <button type="button" class="btn btn-primary" id="add_rota" disabled>
                                <i class="fa fa-plus"></i>
                                Adicionar Rota
                            </button>
                            <button class="btn btn-primary" onclick="limpa_rota();" type="button">
                                <i class="glyphicon glyphicon-erase"></i>
                                Limpar Rota
                            </button>
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-check"></i>
                                Navegar
                            </button>
                            <div class="hidden">
                                <input id="rota_1" readonly="true"><br>
                                <input id="rota_2" readonly="true"><br>
                                <input id="rota_3" readonly="true"><br>
                                <input id="rota_4" readonly="true"><br>
                                <input id="rota_5" readonly="true"><br>
                                <input id="rota_r_1" name="r_1" type="hidden" readonly="true">
                                <input id="rota_r_2" name="r_2" type="hidden" readonly="true">
                                <input id="rota_r_3" name="r_3" type="hidden" readonly="true">
                                <input id="rota_r_4" name="r_4" type="hidden" readonly="true">
                                <input id="rota_r_5" name="r_5" type="hidden" readonly="true">
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <button href="Mapa/mapa_cancelarnav.php" id="cancelar_navegacao" class="btn btn-primary">
                        <i class="fa fa-times"></i>
                        Cancelar navegação
                    </button>
                <?php endif; ?>
            </div>
            <div id="oceano_tudo" class="embed-responsive">
                <div id="oceano_selecoes_borda">
                    <table class="table_sem_borda" id="oceano_selecoes">
                        <?php
                        $result = $connection->run(
                            "SELECT navegavel, x, y FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ?",
                            "iiii", array($coord_x_navio - 4, $coord_x_navio + 4, $coord_y_navio - 4, $coord_y_navio + 4)
                        );

                        $navegaveis = array();
                        while ($navegavel = $result->fetch_array()) {
                            $navegaveis[$navegavel["x"]][$navegavel["y"]] = $navegavel["navegavel"];
                        }

                        ?>

                        <?php for ($coord_y = $coord_y_navio - 4; $coord_y <= $coord_y_navio + 4; $coord_y++): ?>
                            <tr>
                                <?php for ($coord_x = $coord_x_navio - 4; $coord_x <= $coord_x_navio + 4; $coord_x++): ?>
                                    <td id="<?= $coord_x; ?>_<?= $coord_y; ?>">
                                        <?php if (sqrt(pow($coord_x_navio - $coord_x, 2) + pow($coord_y_navio - $coord_y, 2)) <= $distancia_visao): ?>

                                            <?php
                                            $x = calc_map_limit_x($coord_x);
                                            $y = calc_map_limit_y($coord_y);
                                            ?>

                                            <div style="width: 40px; height: 40px;">
                                                <?php $rotas = in_rota($x, $y); ?>
                                                <?php if (count($rotas)): ?>
                                                    <div class="mapa_vento text-center"
                                                         style="width: 40px; height: 40px;background: rgba(135, 206, 250,0.3);">
                                                        <?php foreach ($rotas as $rota): ?>
                                                            <div class="label"
                                                                 style="background: rgba(0, 0, 0, <?= 1.1 - $rota["indice"] / 5 ?>);font-size: 8px;position: absolute;right: 0;top:0;
                                                                         z-index: <?= 100 - $rota["indice"] ?>">
                                                                <span id="rota_andamento_tempo_<?= $rota["indice"] ?>"><?= $rota["momento"] - atual_segundo() ?></span>
                                                                <span id="rota_andamento_tempo_sec_<?= $rota["indice"] ?>"
                                                                      style="display: none"><?= $rota["momento"] - atual_segundo() ?></span>
                                                            </div><br/>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="mapa_vento" id="sel_<?= $x; ?>_<?= $y; ?>"
                                                     style="width: 40px; height: 40px;">
                                                    <img id="dbl-click-<?= $x; ?>-<?= $y; ?>"
                                                         onclick="mostraAlvo('<?= $x; ?>','<?= $y; ?>', '<?= $userDetails->ilha["navegavel"]; ?>');
                                                         <?php if (pode_navegar($x, $y, $navegaveis)) { ?>
                                                                 verAddRota('<?= $x; ?>','<?= $y; ?>', '<?= $userDetails->ilha["navegavel"]; ?>');
                                                         <?php } ?>" src="Imagens/Icones/selecao.png"/>

                                                    <?php if (pode_navegar($x, $y, $navegaveis)): ?>
                                                        <?php if ($userDetails->tripulacao["navegacao_automatica"]): ?>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    $('#dbl-click-<?= $x; ?>-<?= $y; ?>')
                                                                        .addClass('seletor-oceano')
                                                                        .on('dblclick', function (e) {
                                                                            e.preventDefault();
                                                                            addRota('<?= $x; ?>', '<?= $y; ?>', '<?= $userDetails->ilha["navegavel"]; ?>');
                                                                            sendGet('Mapa/navegar.php?x=<?= $x ?>&y=<?= $y ?>')
                                                                        });
                                                                });
                                                            </script>
                                                        <?php else: ?>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    $('#dbl-click-<?= $x; ?>-<?= $y; ?>')
                                                                        .addClass('seletor-oceano')
                                                                        .on('dblclick', function (e) {
                                                                            e.preventDefault();
                                                                            addRota('<?= $x; ?>', '<?= $y; ?>', '<?= $userDetails->ilha["navegavel"]; ?>');
                                                                        });
                                                                });
                                                            </script>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </div>
                <div id="oceano_borda">
                    <?php include "Scripts/Mapa/mapa_oceano_conteudo.php"; ?>
                </div>
            </div>
            <div>
                <?php
                $visivel = $connection->run("SELECT count(id) AS total FROM tb_mapa_contem WHERE id = ?",
                    "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];
                ?>
                <?php if ($userDetails->vip["coup_de_burst"]): ?>
                    <button class="btn btn-info link_send" href="link_Mapa/coup_de_burst.php"
                        <?= $visivel && $userDetails->rotas ? "" : "disabled" ?>>
                        <i class="fa fa-bolt"></i>
                        Coup De Burst (<?= $userDetails->vip["coup_de_burst"] ?>)
                    </button>
                <?php else: ?>
                    <button class="btn btn-info link_confirm" href="Mapa/coup_de_burst.php?tipo=gold"
                            data-question="O Coup De Burst irá reduzir em 10 segundos o tempo de navegação para a próxima coordenada. Você não poderá usa-lo mais de uma vez para navegar a mesma coordenada. Deseja investir <?= PRECO_GOLD_COUP_DE_BURST_INSTANTANEO ?> Moedas de Ouro em um Coup De Burst?"
                        <?= !$userDetails->tripulacao["coup_de_burst_usado"] && $visivel && $userDetails->rotas && $userDetails->conta["gold"] >= PRECO_GOLD_COUP_DE_BURST_INSTANTANEO ? "" : "disabled" ?>>
                        <?= PRECO_GOLD_COUP_DE_BURST_INSTANTANEO ?> <img src="Imagens/Icones/Gold.png"/>
                        Coup De Burst (<?= $userDetails->vip["coup_de_burst"] ?>)
                    </button>
                    <button class="btn btn-info link_confirm" href="Mapa/coup_de_burst.php?tipo=dobrao"
                            data-question="O Coup De Burst irá reduzir em 10 segundos o tempo de navegação para a próxima coordenada. Você não poderá usa-lo mais de uma vez para navegar a mesma coordenada. Deseja investir <?= PRECO_DOBRAO_COUP_DE_BURST_INSTANTANEO ?> Dobrões de Ouro em um Coup De Burst?"
                        <?= !$userDetails->tripulacao["coup_de_burst_usado"] && $visivel && $userDetails->rotas && $userDetails->conta["dobroes"] >= PRECO_DOBRAO_COUP_DE_BURST_INSTANTANEO ? "" : "disabled" ?>>
                        <?= PRECO_DOBRAO_COUP_DE_BURST_INSTANTANEO ?> <img src="Imagens/Icones/Dobrao.png"/>
                        Coup De Burst (<?= $userDetails->vip["coup_de_burst"] ?>)
                    </button>
                <?php endif; ?>
            </div>
            <br/>
            <div>
                <button class="btn btn-primary link_confirm"
                        href="Geral/navegacao_automatica.php"
                        data-question="A Navegação automática é um recurso experimental que traça rotas automaticamente quando você der dois cliques no mapa.">
                    <?= $userDetails->tripulacao["navegacao_automatica"] ? "Desabilitar" : "Habilitar" ?> navegação
                    automática
                </button>
            </div>
            <!--
            <div>
                <button class="btn btn-info link_confirm" href="Vip/ocultar.php"
                        data-question="Você só estará invisível enquanto estiver parado, quando navegar voltará a ser visível. Deseja investir <?= PRECO_GOLD_CAMUFLAGEM ?> Moedas de Ouro para se camuflar?"
                    <?= $visivel && $userDetails->conta["gold"] >= PRECO_GOLD_CAMUFLAGEM ? "" : "disabled" ?>>
                    <?= PRECO_GOLD_CAMUFLAGEM ?> <img src="Imagens/Icones/Gold.png"/>
                    Camuflagem
                </button>
                <button class="btn btn-info link_confirm" href="VipDobroes/ocultar.php"
                        data-question="Você só estará invisível enquanto estiver parado, quando navegar voltará a ser visível. Deseja investir <?= PRECO_DOBRAO_CAMUFLAGEM ?> Dobrões de Ouro para se camuflar?"
                    <?= $visivel && $userDetails->conta["dobroes"] >= PRECO_DOBRAO_CAMUFLAGEM ? "" : "disabled" ?>>
                    <?= PRECO_DOBRAO_CAMUFLAGEM ?> <img src="Imagens/Icones/Dobrao.png"/>
                    Camuflagem
                </button>
            </div>
            -->
        </div>
        <div class="col-md-5">
            <?php if ($userDetails->navio["cod_casco"] == COD_CASCO_KAIROUSEKI) : ?>
                <select class="form-control" style="width: 180px; display: inline-block"
                        onchange="sendGet('Mapa/kairouseki_mudar.php?op='+this.value)">
                    <?php if (!$userDetails->tripulacao["kai"]) : ?>
                        <option value='0'>Kairouseki Desativado</option>
                        <option value='1'>Kairouseki Ativado</option>
                    <?php else : ?>
                        <option value='1'>Kairouseki Ativado</option>
                        <option value='0'>Kairouseki Desativado</option>
                    <?php endif; ?>
                </select>
            <?php endif; ?>
            <?php $count_isca_normal = $connection->run("SELECT SUM(quant) AS total FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?",
                "ii", array($userDetails->tripulacao["id"], TIPO_ITEM_ISCA_NORMAL))->fetch_array()["total"]; ?>
            <?php $count_isca_dourada = $connection->run("SELECT SUM(quant) AS total FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?",
                "ii", array($userDetails->tripulacao["id"], TIPO_ITEM_ISCA_DOURADA))->fetch_array()["total"]; ?>
            <div style="display: inline-block;">
                <button href="<?= $count_isca_normal ? "Vip/isca.php?tipo=" . TIPO_ITEM_ISCA_NORMAL : "./?ses=servicoDenDen" ?>"
                    <?= $count_isca_normal ? 'data-question="Deseja mesmo usar uma isca?"' : "" ?>
                        class="btn btn-info <?= $count_isca_normal ? "link_confirm" : "link_content" ?>"
                        data-toggle="tooltip" title="Usar Isca" data-placement="bottom">
                    <img height="23px" src="Imagens/Itens/370.png">
                    (<?= $count_isca_normal ? $count_isca_normal : "0" ?>)
                </button>
                <button href="<?= $count_isca_dourada ? "Vip/isca.php?tipo=" . TIPO_ITEM_ISCA_DOURADA : "./?ses=servicoDenDen" ?>"
                    <?= $count_isca_dourada ? 'data-question="Deseja mesmo usar uma isca dourada?"' : "" ?>
                        class="btn btn-warning <?= $count_isca_dourada ? "link_confirm" : "link_content" ?>"
                        data-toggle="tooltip" title="Usar Isca Dourada" data-placement="bottom">
                    <img height="23px" src="Imagens/Itens/371.png">
                    (<?= $count_isca_dourada ? $count_isca_dourada : "0" ?>)
                </button>
            </div>
            <div>
                <div class="info_mapa_title">
                    <h4 id="coord_alvo"><?= get_current_location() ?></h4>
                </div>

                <div id="coord_selec">

                </div>
            </div>
        </div>
    </div>

    <?= ajuda("Oceano", "Agora começa sua aventura pelo mundo de One Piece!<br>
			Para traçar um rota basta dar 2 cliques em cada quadro que você deseja navegar.", true) ?>

    <?php if (!$userDetails->rotas) : ?>
        <div class="row">
            <?php if ($userDetails->mergulhadores) : ?>
                <?php foreach ($userDetails->mergulhadores as $mergulhador) : ?>
                    <?php render_profissao_time_panel(
                        "Mergulhador",
                        $mergulhador,
                        $userDetails->tripulacao["mergulho"],
                        3600 - (($mergulhador["profissao_lvl"] - 1) * 180),
                        "mergulhador",
                        "mergulho",
                        $userDetails->tripulacao["mergulho_cod"],
                        "Mergulhar"
                    ); ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($userDetails->arqueologos) : ?>
                <?php foreach ($userDetails->arqueologos as $arqueologo) : ?>
                    <?php render_profissao_time_panel(
                        "Arqueólogo",
                        $arqueologo,
                        $userDetails->tripulacao["expedicao"],
                        3600 - (($arqueologo["profissao_lvl"] - 1) * 180),
                        "expedicao",
                        "expedicao",
                        $userDetails->tripulacao["expedicao_cod"],
                        "Explorar"
                    ); ?>
                <?php endforeach ?>
            <?php endif; ?>
            <?php if ($userDetails->ferreiros) : ?>
                <?php foreach ($userDetails->ferreiros as $ferreiro) : ?>
                    <?php render_profissao_time_panel(
                        "Ferreiro",
                        $ferreiro,
                        $userDetails->tripulacao["mining"],
                        10 * 60,
                        "mining",
                        "mining",
                        $userDetails->tripulacao["mining_cod"],
                        "Mineirar"
                    ); ?>
                <?php endforeach ?>
            <?php endif; ?>
            <?php if ($userDetails->carpinteiros) : ?>
                <?php foreach ($userDetails->carpinteiros as $carpinteiro) : ?>
                    <?php render_profissao_time_panel(
                        "Carpinteiro",
                        $carpinteiro,
                        $userDetails->tripulacao["madeira"],
                        10 * 60,
                        "madeira",
                        "madeira",
                        $userDetails->tripulacao["madeira_cod"],
                        "Madeirar"
                    ); ?>
                <?php endforeach ?>
            <?php endif; ?>
            <?php if ($userDetails->cartografos) : ?>
                <?php foreach ($userDetails->cartografos as $cartografo) : ?>
                    <?php render_profissao_time_panel(
                        "Cartógrafo",
                        $cartografo,
                        $userDetails->tripulacao["desenho"],
                        120 - 10 * ($cartografo["profissao_lvl"] - 1),
                        "desenho",
                        "desenho",
                        $userDetails->tripulacao["desenho_cod"],
                        "Desenhar"
                    ); ?>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>