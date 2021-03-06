<?php function render_tatics($tabuleiro, $sem_tatica, $tipo, $x1, $x2) { ?>
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
    <div>
        <?php foreach ($sem_tatica[$tipo] as $pers): ?>
            <a href="#" class="personagemRandom noHref" data-cod="<?= $pers["cod"] ?>">
                <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg">
            </a>
        <?php endforeach; ?>
    </div>
<?php } ?>

<div class="panel-heading">
    Taticas
</div>

<style type="text/css">
    <?php include "CSS/combate.css"; ?>
</style>

<script type="text/javascript">
    $(function () {
        $(".personagemRandom").click(function () {
            var cod = $(this).data("cod");
            $(".td-selecao:not(.personagem)")
                .css("background", "rgba(0,200,230,0.5)")
                .css('cursor', 'pointer')
                .click(function () {
                    var type = $('.tab-pane.active').attr('id');
                    var data = "Tatics/" + type + ".php?cod=" + cod + "&pos=" + $(this).attr('id');
                    sendGet(data);
                });
        });
        $(".personagem.aliado").click(function () {
            var type = $('.tab-pane.active').attr('id');
            var data = "Tatics/" + type + "_remove.php?cod=" + $(this).data("cod");
            sendGet(data);
        });
    });
</script>

<div class="panel-body">
    <?= ajuda("Taticas", "Defina sua tática de posicionamento dos personagens para as batalhas."); ?>

    <p>Defina a posição de personagens para batalhas, mas não se esqueça que para usar essas posições é necessário
        ativar a função VIP "Taticas"</p>
    <p><a href="./?ses=vipLoja" class="link_content">Clique Aqui</a> para acessar a loja VIP e ativar o recurso.</p>

    <?php
    $selected_tatics = "A";
    if (isset($_GET["tatics"]) && validate_alphanumeric($_GET["tatics"])) {
        $selected_tatics = $_GET["tatics"];
    }
    ?>
    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="<?= $selected_tatics == "A" ? "active" : "" ?>">
                <a href="./?ses=tatics&tatics=A" class="link_content">Atacando jogadores</a>
            </li>
            <li class="<?= $selected_tatics == "D" ? "active" : "" ?>">
                <a href="./?ses=tatics&tatics=D" class="link_content">Sendo atacado por jogadores</a>
            </li>
            <li class="<?= $selected_tatics == "P" ? "active" : "" ?>">
                <a href="./?ses=tatics&tatics=P" class="link_content">Batalha contra Reis dos Mares</a>
            </li>
        </ul>
    </div>

    <?php
    $tabuleiro = array("a" => [], "d" => [], "p" => []);
    $sem_tatica = array("a" => [], "d" => [], "p" => []);
    foreach ($userDetails->personagens as $pers) {
        if (!$pers["hp"]) {
            $pers["hp"] = 1;
        }
        $pers["tripulacao_id"] = $pers["id"];
        if ($pers["tatic_a"]) {
            $tatic = explode(";", $pers["tatic_a"]);
            $tabuleiro["a"][$tatic[0]][$tatic[1]] = $pers;
        } else {
            $sem_tatica["a"][] = $pers;
        }
        if ($pers["tatic_d"]) {
            $tatic = explode(";", $pers["tatic_d"]);
            $tabuleiro["d"][$tatic[0]][$tatic[1]] = $pers;
        } else {
            $sem_tatica["d"][] = $pers;
        }
        if ($pers["tatic_p"]) {
            $tatic = explode(";", $pers["tatic_p"]);
            $tabuleiro["p"][$tatic[0]][$tatic[1]] = $pers;
        } else {
            $sem_tatica["p"][] = $pers;
        }
    }
    $obstaculos = $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"]))->fetch_all_array();
    foreach ($obstaculos as $obstaculo) {
        $tabuleiro[$obstaculo["tipo"] == 1 ? "a" : "d"][$obstaculo["x"]][$obstaculo["y"]] = array(
            "cod" => "obstaculo-" . $obstaculo["id"],
            "tripulacao_id" => null,
            "hp" => $obstaculo["hp"],
            "hp_max" => OBSTACULOS_HP_INDIVIDUAL_MAX,
            "mp" => 0,
            "mp_max" => 1,
            "img" => "obstaculo",
            "skin_r" => 0
        );
    }

    ?>

    <div class="tab-content">
        <div id="taticsA" class="tab-pane <?= $selected_tatics == "A" ? "active" : "" ?>">
            <?php render_tatics($tabuleiro, $sem_tatica, "a", 5, 10); ?>
        </div>
        <div id="taticsD" class="tab-pane <?= $selected_tatics == "D" ? "active" : "" ?>">
            <?php render_tatics($tabuleiro, $sem_tatica, "d", 0, 5); ?>
        </div>
        <div id="taticsP" class="tab-pane <?= $selected_tatics == "P" ? "active" : "" ?>">
            <div class="navio_batalha">
                <div id="batalha_background">
                    <div class="fight-zone">
                        <div class="navio navio-npc"
                             style="background: url(Imagens/Batalha/Npc/1.png) no-repeat center">
                        </div>
                        <div class="navio navio-player"
                             style="background: url(Imagens/Batalha/bg-navio-<?= $userDetails->tripulacao["faccao"] ?>.png) no-repeat center">
                            <?php render_tabuleiro($tabuleiro["p"], 0, 5); ?>
                        </div>
                    </div>
                </div>
                <div>
                    <?php foreach ($sem_tatica["p"] as $pers): ?>
                        <a href="#" class="personagemRandom noHref" data-cod="<?= $pers["cod"] ?>">
                            <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>