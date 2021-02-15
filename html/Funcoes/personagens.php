<?php function calc_bonus($pers, $nivelamento = false) {
    global $connection;

    $equipamentos = load_equipamentos($pers);

    $bonus = cal_bonus_equip_atributo($equipamentos["equips_info"], $equipamentos["treinos"], $nivelamento);

    if ($pers["cod_acessorio"] != 0) {
        $result = $connection->run("SELECT * FROM tb_item_acessorio WHERE cod_acessorio= ? ", "i", $pers["cod_acessorio"]);
        if ($result->count()) {
            $acessorio = $result->fetch_array();
            $bonus[nome_atributo_tabela($acessorio["bonus_atr"])] += $acessorio["bonus_atr_qnt"];
        }
    }

    $result = $connection->run(
        "SELECT * FROM tb_skil_passiva 
			INNER JOIN tb_personagens_skil ON tb_personagens_skil.cod_skil=tb_skil_passiva.cod_skil 
			WHERE tb_personagens_skil.cod= ?
			AND (tb_personagens_skil.tipo='3' OR tb_personagens_skil.tipo='6')", "i", $pers["cod"]
    );

    while ($passiva = $result->fetch_array()) {
        $bonus[nome_atributo_tabela($passiva["bonus_atr"])] += $passiva["bonus_atr_qnt"];
    }

    $result = $connection->run(
        "SELECT * FROM tb_akuma_skil_passiva 
			INNER JOIN tb_personagens_skil ON tb_personagens_skil.cod_skil=tb_akuma_skil_passiva.cod_skil 
			WHERE tb_personagens_skil.cod=?
			AND tb_personagens_skil.tipo='9'", "i", $pers["cod"]
    );

    while ($passiva = $result->fetch_array()) {
        $bonus[nome_atributo_tabela($passiva["bonus_atr"])] += $passiva["bonus_atr_qnt"];
    }

    $result = $connection->run(
        "SELECT * FROM tb_personagem_titulo pertit 
			INNER JOIN tb_titulos titulos ON pertit.titulo = titulos.cod_titulo
			WHERE pertit.cod = ?", "i", $pers["cod"]
    );

    while ($titulo = $result->fetch_array()) {
        if ($titulo["bonus_atr_quant"]) {
            if ($titulo["bonus_atr"] == 0) {
                for ($i = 1; $i <= 7; $i++) {
                    $bonus[nome_atributo_tabela($i)] += $titulo["bonus_atr_quant"];
                }
            } else if (isset($bonus[nome_atributo_tabela($titulo["bonus_atr"])])) {
                $bonus[nome_atributo_tabela($titulo["bonus_atr"])] += $titulo["bonus_atr_quant"];
            }
        }
    }

    if ($nivelamento && $pers["lvl"] < 50) {
        for ($i = 1; $i <= 7; $i++) {
            $bonus[nome_atributo_tabela($i)] += (50 - $pers["lvl"]) * ($i == 2 ? 0.3 : 0.5);
        }
    }

    return $bonus;
}

function ajusta_hp($pers, $bonus) {
    if ($bonus["vit"]) {
        $proporcao_hp = $pers["hp"] / $pers["hp_max"];
        $pers["hp_max"] += $bonus["vit"] * 30;
        $pers["hp"] = round($pers["hp_max"] * $proporcao_hp);

        $proporcao_mp = $pers["mp"] / $pers["mp_max"];
        $pers["mp_max"] += $bonus["vit"] * 7;
        $pers["mp"] = round($pers["mp_max"] * $proporcao_mp);
    }
    return $pers;
}

function aplica_bonus($pers, $nivelamento = false) {
    $bonus = calc_bonus($pers, $nivelamento);
    $pers = ajusta_hp($pers, $bonus);

    foreach ($bonus as $atributo => $value) {
        $pers[$atributo] += $value;
    }

    return $pers;
}

function sorteia_posicoes($all_pers, $vip, $tatic_type, $x1, $x2, &$personagens, &$tabuleiro) {
    foreach ($all_pers as $pers) {
        if (!$vip["tatic"] || !$pers[$tatic_type] || $pers[$tatic_type] == "0") {
            do {
                $x = rand($x1, $x2);
                $y = rand(0, 19);
            } while (isset($tabuleiro[$x][$y]));
            $pers["quadro_x"] = $x;
            $pers["quadro_y"] = $y;
            $personagens[] = $pers;
            $tabuleiro[$x][$y] = $pers;
        }
    }
}

function insert_personagens_combate($id, $all_pers, $vip, $tatic_type, $x1, $x2, $obstaculos = array(), $nivelamento = false, $details = null, $conn = null) {
    if (!$conn) {
        global $connection;
    } else {
        $connection = $conn;
    }

    if (!$details) {
        global $userDetails;
    } else {
        $userDetails = $details;
    }

    $tabuleiro = [];
    foreach ($obstaculos as $obstaculo) {
        $tabuleiro[$obstaculo["x"]][$obstaculo["y"]] = $obstaculo;
    }

    $personagens = [];
    if ($vip["tatic"]) {
        foreach ($all_pers as $pers) {
            if ($pers[$tatic_type] && $pers[$tatic_type] != "0") {
                $posicao = explode(";", $pers[$tatic_type]);
                $pers["quadro_x"] = $posicao[0];
                $pers["quadro_y"] = $posicao[1];
                $personagens[] = $pers;
                $tabuleiro[$posicao[0]][$posicao[1]] = $pers;
            }
        }
    }
    sorteia_posicoes($all_pers, $vip, $tatic_type, $x1, $x2, $personagens, $tabuleiro);

    if ($fantasias = $userDetails->buffs->get_efeito_from_tripulacao("fantasia_imgs", $id)) {
        $fantasias = explode(";", $fantasias);

        $tripulantes = $userDetails->buffs->get_efeito_from_tripulacao("fantasia_quant_trips", $id);

        for ($x = 0; $x < $tripulantes; $x++) {
            $pers_index = array_rand($personagens);
            $fantasia_index = array_rand($fantasias);
            $fantasia = explode(",", $fantasias[$fantasia_index]);
            $personagens[$pers_index]["img"] = $fantasia[0];
            $personagens[$pers_index]["skin_r"] = $personagens[$pers_index]["skin_c"] = $fantasia[1];
        }
    }

    $connection->run("DELETE FROM tb_combate_personagens WHERE id = ?", "i", array($id));
    foreach ($personagens as $pers) {
        $pers = aplica_bonus($pers, $nivelamento);

        $connection->run(
            "INSERT INTO tb_combate_personagens 
             (id, 
             cod, 
             hp, hp_max, 
             mp, mp_max, 
             atk, def, agl, res, pre, dex, con, vit, 
             quadro_x, quadro_y, 
             haki_esq, haki_cri, img, skin_r, skin_c) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "iiiiiiiiiiiiiiiiiiiii", array(
                $id,
                $pers["cod"],
                $pers["hp"], $pers["hp_max"],
                $pers["mp"], $pers["mp_max"],
                $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"], $pers["con"], $pers["vit"],
                $pers["quadro_x"], $pers["quadro_y"],
                $pers["haki_esq"], $pers["haki_cri"],
                $pers["img"], $pers["skin_r"], $pers["skin_c"]
            )
        );
    }

    $fila_coliseu = $connection->run("SELECT * FROM tb_coliseu_fila WHERE id = ?", "i", array($id));
    if ($fila_coliseu->count()) {
        $fila_coliseu = $fila_coliseu->fetch_array();
        if ($fila_coliseu["desafio"]) {
            $connection->run("UPDATE tb_coliseu_fila SET desafio = NULL, desafio_momento = NULL, desafio_aceito = 0 WHERE id = ? OR id = ?",
                "ii", array($id, $fila_coliseu["desafio"]));
        }
    }

    $connection->run("UPDATE tb_coliseu_fila SET pausado = 1 WHERE id = ?", "i", array($id));

    $connection->run("UPDATE tb_torneio_inscricao 
        SET tempo_na_fila = IFNULL((unix_timestamp() - unix_timestamp(fila_entrada)) + IFNULL(tempo_na_fila, 0), tempo_na_fila), na_fila = 0, fila_entrada = NULL 
        WHERE tripulacao_id = ?",
        "i", array($id));
}

function preco_selo_exp($pers) {
    return round($pers["lvl"] < 50 ? max($pers["xp_max"] / 2, $pers["xp"] / 4) : $pers["xp_max"] / 2 * 10);
}
?>
<?php function render_personagens_pills($personagens = NULL, $on_click = null, $alert_func = '0') { ?>
    <?php global $userDetails ?>
    <?php $cod = isset($_GET["cod"]) && !empty($_GET["cod"]) ? $_GET["cod"] : NULL; ?>
    <?php $personagens = $personagens ? $personagens : $userDetails->personagens; ?>
    <ul class="nav nav-pills nav-justified">
        <?php foreach ($personagens as $index => $pers): ?>
            <li class="personagem-pill <?= $cod ? ($pers["cod"] == $cod ? "active" : "") : ($index == 0 ? "active" : "") ?>"
                onclick="setQueryParam('cod','<?= $pers["cod"]; ?>');" data-cod="<?= $pers["cod"]; ?>">
                <a href="#personagem-<?= $pers["cod"] ?>" data-toggle="tab">
                    <?php if ($alert_func): ?>
                        <div style="position: absolute; right: 0;top: 0;">
                            <?php $alert_func($pers); ?>
                        </div>
                    <?php endif; ?>
                    <?= icon_pers_skin($pers["img"], $pers["skin_r"], "", 'style="max-width: 75px; width: 100%"') ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php } ?>
<?php function render_personagem_panel_top($pers, $index) { ?>
    <?php $cod = isset($_GET["cod"]) && !empty($_GET["cod"]) ? $_GET["cod"] : NULL; ?>
    <div id="personagem-<?= $pers["cod"] ?>"
    class="tab-pane <?= $cod ? ($pers["cod"] == $cod ? "active" : "") : ($index == 0 ? "active" : "") ?>">
<?php } ?>
<?php function render_personagem_panel_bottom() { ?>
    </div>
<?php } ?>
<?php function render_personagem_sub_panel_with_img_top($pers) { ?>
    <div class="row">
    <div class="col-md-3 hidden-sm hidden-xs">
        <img src="Imagens/Personagens/Big/<?= getImg($pers, "c") ?>.jpg">
    </div>
    <div class="col-md-9">
    <div class="panel panel-default">
<?php } ?>
<?php function render_personagem_sub_panel_with_img_bottom() { ?>
    </div>
    </div>
    </div>
<?php } ?>
<?php function render_personagem_status_bars($pers, $text = true) { ?>
    <div class="clearfix">
        <?php render_personagem_hp_bar($pers, $text); ?>
        <?php render_personagem_mp_bar($pers, $text); ?>
        <?php render_personagem_xp_bar($pers, $text); ?>
    </div>
<?php } ?>
<?php function render_personagem_hp_bar($pers, $text = true) { ?>
    <?php global $userDetails; ?>
    <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar"
             style="width: <?= $pers["hp"] / $pers["hp_max"] * 100 ?>%;">
            <?php if ($pers["id"] == $userDetails->tripulacao["id"] && $text) : ?>
                <span>HP:<?= $pers["hp"] . "/" . $pers["hp_max"] ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_personagem_mp_bar($pers, $text = true) { ?>
    <?php global $userDetails; ?>
    <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar"
             style="width: <?= $pers["mp"] / $pers["mp_max"] * 100 ?>%;">
            <?php if ($pers["id"] == $userDetails->tripulacao["id"] && $text) : ?>
                <span>Energia:<?= $pers["mp"] . "/" . $pers["mp_max"] ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_personagem_xp_bar($pers, $text = true) { ?>
    <div class="progress">
        <div class="progress-bar progress-bar-default" role="progressbar"
             style="width: <?= $pers["xp"] / ($pers["lvl"] >= 50 ? $pers["excelencia_xp_max"] : $pers["xp_max"]) * 100 ?>%;">
            <?php if ($text) : ?>
                <span>EXP:<?= $pers["xp"] . "/" . ($pers["lvl"] >= 50 ? $pers["excelencia_xp_max"] : $pers["xp_max"]) ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_personagem_haki_bars($pers) { ?>
    <div class="clearfix">
        <?php render_personagem_mantra_bar($pers); ?>
        <?php render_personagem_armamento_bar($pers); ?>
        <?php if (isset($pers["haki_hdr"])): ?>
            <?php render_personagem_hdr_bar($pers); ?>
        <?php endif; ?>
    </div>
<?php } ?>
<?php function render_personagem_mantra_bar($pers, $text = true) { ?>
    <div class="progress">
        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
             style="width: <?= $pers["haki_esq"] / MAX_POINTS_MANTRA * 100 ?>%;">
            <?php if ($text): ?>
                Mantra:<?= $pers["haki_esq"] . "/" . MAX_POINTS_MANTRA; ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_personagem_armamento_bar($pers, $text = true) { ?>
    <div class="progress">
        <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar"
             style="width: <?= $pers["haki_cri"] / MAX_POINTS_ARMAMENTO * 100 ?>%;">
            <?php if ($text): ?>
                Armamento:<?= $pers["haki_cri"] . "/" . MAX_POINTS_ARMAMENTO; ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_personagem_hdr_bar($pers, $text = true) { ?>
    <div class="progress">
        <div class="progress-bar progress-bar-default progress-bar-striped" role="progressbar"
             style="width: <?= $pers["haki_hdr"] / MAX_POINTS_HDR * 100 ?>%;">
            <?php if ($text): ?>
                Haoshoku:<?= $pers["haki_hdr"] . "/" . MAX_POINTS_HDR; ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_cartaz_procurado($famoso, $faccao) { ?>
    <div class="tripulante_quadro <?= $faccao == FACCAO_PIRATA ? "pirate" : "marine" ?>" style="margin: 2px;;">
        <img class="tripulante_quadro_img  <?= $faccao == FACCAO_PIRATA ? "pirate" : "marine" ?>"
             src="Imagens/Personagens/Icons/<?= getImg($famoso, "r"); ?>.jpg">
        <div class="recompensa_text  <?= $faccao == FACCAO_PIRATA ? "pirate" : "marine" ?>">
            <?= $famoso["nome"] . "<br>" . mascara_berries(calc_recompensa($famoso["fama_ameaca"])); ?>
        </div>
    </div>
<?php } ?>
<?php function render_progress($id, $progress, $inner_text, $text, $color, $link) { ?>
    <div class="col-xs-6 col-md-3" style="margin-bottom: 10px">
        <div class="list-group-item" style="height: 100%;">

            <div id="<?= $id ?>"></div>
            <div>
                <a href="./?ses=<?= $link ?>" class="link_content">
                    <?= $text ?>
                </a>
            </div>

            <script>
                $(function () {
                    var container = document.getElementById('<?= $id ?>');
                    var bar = new ProgressBar.Circle(container, {
                        strokeWidth: 10,
                        easing: 'easeInOut',
                        duration: 1400,
                        color: '<?= $color ?>',
                        trailColor: '#3b3b3b',
                        trailWidth: 1,
                        svgStyle: {
                            width: '4em'
                        },
                        text: {
                            value: '<?= $inner_text ?>'
                        }
                    });

                    bar.animate(<?= $progress ?>);  // Number from 0.0 to 1.0
                });
            </script>
        </div>
    </div>
<?php } ?>