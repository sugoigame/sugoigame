<?php function render_tatics($tabuleiro, $tipo, $x1, $x2) { ?>
    <?php global $userDetails; ?>
    <div class="navio_batalha">
        <div class="batalha_background">
            <div class="fight-zone">
                <?php if ($x2 <= 5): ?>
                    <div class="navio navio-player"
                         style="background: url(Imagens/Batalha/bg-navio-<?= $userDetails->tripulacao["faccao"] ?>.png) no-repeat center">
                        <?php render_tabuleiro($tabuleiro[$tipo], $x1, $x2); ?>
                    </div>
                    <div class="navio navio-player"
                         style="background: url(Imagens/Batalha/bg-navio-<?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? FACCAO_PIRATA : FACCAO_MARINHA ?>.png) no-repeat center">
                    </div>
                <?php else: ?>
                    <div class="navio navio-player"
                         style="background: url(Imagens/Batalha/bg-navio-<?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? FACCAO_PIRATA : FACCAO_MARINHA ?>.png) no-repeat center">
                    </div>
                    <div class="navio navio-player"
                         style="background: url(Imagens/Batalha/bg-navio-<?= $userDetails->tripulacao["faccao"] ?>.png) no-repeat center">
                        <?php render_tabuleiro($tabuleiro[$tipo], $x1, $x2); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php } ?>

<div class="panel-heading">
    Obstáculos do Navio
</div>

<style type="text/css">
    <?php include "CSS/combate.css"; ?>
</style>

<script type="text/javascript">
    $(function () {
        $(".td-selecao:not(.personagem.aliado)")
            .css('cursor', 'pointer')
            .click(function () {
                var x = $(this).data('x');
                var y = $(this).data('y');
                bootbox.prompt({
                    title: 'Quantos pontos de vida gostaria que esse obstáculo tivesse?',
                    inputType: 'number',
                    callback: function (result) {
                        if (result) {
                            sendGet('Obstaculos/criar.php?x=' + x + '&y=' + y + '&hp=' + result);
                        }
                    }
                });
            });
    });
</script>

<div class="panel-body">
    <?= ajuda("Obstáculos", "Obstáculos podem ser adicionados ao seu navio para impedir que os tripulantes ocupem certas posições em combate"); ?>

    <p>Clique no quadro que desejar posicionar um obstáculo</p>
    <p>Para alterar os pontos de vidas de um obstáculo basta clicar nele e escolher a nova quantidade</p>
    <p>Para remover um obstáculo basta clicar nele e definir zero pontos de vida</p>
    <p>Cada ponto de vida custa <img
                src="Imagens/Icones/Berries.png"/> <?= mascara_berries(PRECO_BERRIES_VIDA_OBSTACULO) ?></p>

    <?php
    $selected_tatics = "A";
    if (isset($_GET["tatics"]) && validate_alphanumeric($_GET["tatics"])) {
        $selected_tatics = $_GET["tatics"];
    }
    ?>
    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="<?= $selected_tatics == "A" ? "active" : "" ?>">
                <a href="./?ses=obstaculos&tatics=A" class="link_content">Atacando jogadores</a>
            </li>
            <li class="<?= $selected_tatics == "D" ? "active" : "" ?>">
                <a href="./?ses=obstaculos&tatics=D" class="link_content">Sendo atacado por jogadores</a>
            </li>
        </ul>
    </div>

    <?php
    $tabuleiro = array(1 => [], 2 => []);
    $count = array(1 => 0, 2 => 0);
    $hp = array(1 => 0, 2 => 0);

    foreach ($userDetails->personagens as $pers) {
        if (!$pers["hp"]) {
            $pers["hp"] = 1;
        }
        $pers["tripulacao_id"] = $pers["id"];
        if ($pers["tatic_a"]) {
            $tatic = explode(";", $pers["tatic_a"]);
            $tabuleiro[1][$tatic[0]][$tatic[1]] = $pers;
        }
        if ($pers["tatic_d"]) {
            $tatic = explode(";", $pers["tatic_d"]);
            $tabuleiro[2][$tatic[0]][$tatic[1]] = $pers;
        }
    }

    $obstaculos = $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"]))->fetch_all_array();
    foreach ($obstaculos as $obstaculo) {
        $tabuleiro[$obstaculo["tipo"]][$obstaculo["x"]][$obstaculo["y"]] = obstaculo_para_tabuleiro($obstaculo);
        $hp[$obstaculo["tipo"]] += $obstaculo["hp"];
        $count[$obstaculo["tipo"]]++;
    }
    ?>

    <div class="tab-content">
        <div id="taticsA" class="tab-pane <?= $selected_tatics == "A" ? "active" : "" ?>">
            <div>
                <p>
                    Você já criou <?= $count[1] ?> / <?= OBSTACULOS_MAX ?> obstáculos
                </p>
                <p>
                    Você já distribuiu <?= mascara_berries($hp[1]) ?> dos <?= mascara_berries(OBSTACULOS_HP_MAX) ?>
                    pontos de vida disponíveis para seus obstáculos
                </p>
            </div>
            <?php render_tatics($tabuleiro, 1, 5, 10); ?>
        </div>
        <div id="taticsD" class="tab-pane <?= $selected_tatics == "D" ? "active" : "" ?>">
            <div>
                <p>
                    Você já criou <?= $count[2] ?> / <?= OBSTACULOS_MAX ?> obstáculos
                </p>
                <p>
                    Você já distribuiu <?= mascara_berries($hp[2]) ?> dos <?= mascara_berries(OBSTACULOS_HP_MAX) ?>
                    pontos de vida disponíveis para seus obstáculos
                </p>
            </div>
            <?php render_tatics($tabuleiro, 2, 0, 5); ?>
        </div>
    </div>
</div>