<?php
$personagens_combate = get_pers_in_combate($userDetails->tripulacao["id"]);

$perdeu = TRUE;

$tabuleiro = [];
foreach ($personagens_combate as $pers) {
    if ($pers["hp"]) {
        $perdeu = FALSE;
        $tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
    }
}
?>

<?php if (!$userDetails->combate_pve["hp_npc"]) : ?>
    <div id="fim_batalha">
        <h2>Você Venceu.</h2>
        <p>
            Você derrotou seu inimigo. Agora você nao poderá ser atacado enquanto não mudar de coordenada,
            aproveite para recuperar sua tripulação
        </p>
        <p>
            OBS: Se você fizer parte de uma aliança/frota que estiver em guerra contra outra aliança/frota você poderá
            ser atacado normalmente.
        </p>
        <button href="link_Batalha/batalha_fim.php" class="link_send btn btn-success">
            Finalizar
        </button>
    </div>
<?php elseif ($perdeu) : ?>
    <div id="fim_batalha">
        <h2>Você foi derrotado.</h2>
        <button href="link_Batalha/batalha_fim.php" class="link_send btn btn-info">
            Finalizar
        </button>
        <br>
    </div>
<?php else: ?>
    <div id="batalha_background"
        <?php if ($userDetails->combate_pve["battle_back"]): ?>
            style="background: url(Imagens/Batalha/BattleBacks/<?= $userDetails->combate_pve["battle_back"] ?>.jpg) no-repeat top center; -webkit-background-size: cover; background-size: cover;"
        <?php endif; ?>>
        <?php $style = $userDetails->combate_pve["skin_npc"] !== null
            ? "background: url('Imagens/Personagens/Big/" . get_img(array("img" => $userDetails->combate_pve["img_npc"], "skin_c" => $userDetails->combate_pve["skin_npc"]), "c") . ".jpg') no-repeat center bottom;background-size: 160px;"
            : "background: url(Imagens/Batalha/Npc/" . $userDetails->combate_pve["img_npc"] . ".png) no-repeat center bottom" ?>
        <div class="fight-zone">
            <div class="navio navio-npc selecionavel" data-cod="npc"
                 style="<?= $style ?>">
                <div class="progress">
                    O adversário está mirando na <?= $userDetails->combate_pve["mira"] + 1 ?>º linha.
                    <small>Seus tripulantes nesta linha ou nas adjacentes tem mais chances de serem atacados</small>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-danger"
                         style="width: <?= $userDetails->combate_pve["hp_npc"] / $userDetails->combate_pve["hp_max_npc"] * 100 ?>%;">
                        <?= $userDetails->combate_pve["nome_npc"] . ":" .
                        $userDetails->combate_pve["hp_npc"] . "/" . $userDetails->combate_pve["hp_max_npc"] ?>
                    </div>
                </div>
                <div id="npc">
                </div>
            </div>
            <div class="navio navio-player"
                <?php if (!$userDetails->combate_pve["battle_back"]): ?>
                    style="background: url(Imagens/Bandeiras/Navios/<?= $userDetails->tripulacao["faccao"]; ?>/<?= $userDetails->tripulacao["skin_tabuleiro_navio"] ?>/batalha.png) no-repeat center"
                <?php endif; ?>>
                <?php render_tabuleiro($tabuleiro, 0, 5, NULL, $userDetails->combate_pve["mira"]); ?>
            </div>
        </div>

        <div class="personagens-info">
            <?php render_personagens_info($personagens_combate, get_buffs_combate($userDetails->tripulacao["id"])) ?>
        </div>
    </div>
    <div id="menu_batalha">
        <p>
            <button id="botao_atacar" onclick="atacar()" class="btn btn-danger">
                Atacar
            </button>
            <button id="botao_passar" onclick="passar_vez()" class="btn btn-primary">
                Passar a vez
            </button>
            <input type="hidden" id="moves_remain" value="<?= $userDetails->combate_pve["move"] ?>">
            <?php if ($userDetails->combate_pve["move"]) : ?>
                <button id="botao_mover" data-move="<?= $userDetails->combate_pve["move"] ?>" onclick="mover()"
                        class="btn btn-success">
                    Mover (<?= $userDetails->combate_pve["move"] ?>)
                </button>
            <?php endif; ?>
        </p>
        <div class="clearfix">
            <button id="botao_desistir" onclick="desistir()" class="btn btn-primary pull-right">
                Desistir do combate
            </button>
        </div>
    </div>
    <div id="relatorio_combate" class="panel panel-info">
        <div class="panel-heading">Relatório</div>
        <div id="relatorio-combate-content" class="panel-body">
            <?php $combate_logger = new CombateLogger($connection, $userDetails); ?>
            <?php render_relatorio_data($combate_logger->get_relatorio_combate_pve(), $userDetails->tripulacao["id"]); ?>
        </div>
    </div>

<?php endif; ?>