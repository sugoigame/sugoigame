<?php
$personagens_combate = get_pers_in_combate($userDetails->tripulacao["id"]);
$personagens_combate_bot = get_pers_bot_in_combate($userDetails->combate_bot["id"]);

$perdeu = true;
$venceu = true;

$tabuleiro = [];
foreach ($personagens_combate as $pers) {
    if ($pers["hp"]) {
        $perdeu = false;
        $tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
    }
}
foreach ($personagens_combate_bot as $pers) {
    if ($pers["hp"]) {
        $venceu = false;
        $tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
    }
}
$special_effects = get_special_effects_bot($userDetails->tripulacao["id"], $userDetails->combate_bot["id"]);
?>
<?php render_battle_heading(); ?>
<div id="navio_batalha">
    <?php if ($venceu) : ?>
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
            <div>
                <button href="link_Batalha/batalha_fim.php" class="link_send btn btn-success">
                    Finalizar
                </button>
            </div>
        </div>
    <?php elseif ($perdeu) : ?>
        <div id="fim_batalha">
            <h2>Você foi derrotado.</h2>
            <div>
                <button href="link_Batalha/batalha_fim.php" class="link_send btn btn-info">
                    Finalizar
                </button>
            </div>
        </div>
    <?php else : ?>
        <div id="batalha_background" <?php if ($userDetails->combate_bot["battle_back"]) : ?>
                style="background: url(Imagens/Batalha/BattleBacks/<?= $userDetails->combate_bot["battle_back"] ?>.jpg) no-repeat top center; -webkit-background-size: cover; background-size: cover;"
            <?php endif; ?>>
            <div class="fight-zone">
                <div class="navio navio-player" <?php if (! $userDetails->combate_bot["battle_back"]) : ?>
                        style="background: url(Imagens/Bandeiras/Navios/<?= $userDetails->combate_bot["faccao_inimiga"]; ?>/0/batalha.png) no-repeat center"
                    <?php endif; ?>>
                    <?php render_tabuleiro($tabuleiro, 0, 5, null, null, $special_effects); ?>
                </div>
                <div class="navio navio-player" <?php if (! $userDetails->combate_bot["battle_back"]) : ?>
                        style="background: url(Imagens/Bandeiras/Navios/<?= $userDetails->tripulacao["faccao"]; ?>/<?= $userDetails->tripulacao["skin_tabuleiro_navio"] ?>/batalha.png) no-repeat center"
                    <?php endif; ?>>
                    <?php render_tabuleiro($tabuleiro, 5, 10, null, null, $special_effects); ?>
                </div>
            </div>
            <div class="personagens-info">
                <?php render_personagens_info(
                    array_merge($personagens_combate, $personagens_combate_bot),
                    get_buffs_combate_bot($userDetails->tripulacao["id"], $userDetails->combate_bot["id"]),
                    null,
                    $special_effects
                ); ?>
            </div>
        </div>
        <div id="menu_batalha">
            <input type="hidden" id="turno-vez" value="<?= $userDetails->combate_bot["vez"] ?>" />
            <?php if ($userDetails->combate_bot["vez"] == 1) : ?>
                <div>
                    <button id="botao_atacar" onclick="atacar()" class="btn btn-sm btn-danger">
                        <i class="fa fa-dot-circle-o"></i><br />
                        Atacar
                    </button>
                </div>
                <?php if ($userDetails->combate_bot["move"]) : ?>
                    <div>
                        <button id="botao_mover" onclick="mover()" class="btn btn-sm btn-info">
                            <i class="fa fa-arrows-alt"></i>
                            <?= $userDetails->combate_bot["move"] ?><br />
                            Mover
                        </button>
                    </div>
                <?php endif; ?>
                <div>
                    <button id="botao_passar" onclick="passar_vez()" class="btn btn-sm btn-primary">
                        <i class="fa fa-step-forward"></i><br />
                        Passar
                    </button>
                </div>
                <div>
                    <input type="hidden" id="moves_remain" value="<?= $userDetails->combate_bot["move"] ?>">
                </div>
                <div>
                    <button id="botao_desistir" onclick="desistir()" class="btn btn-sm btn-primary">
                        <i class="fa fa-flag"></i><br />
                        Desistir
                    </button>
                </div>
            <?php else : ?>
                <img src="Imagens/Batalha/Menu/Tempo.png" class="mr2" />
            <?php endif; ?>
        </div>

        <button id="botao_relatorio" onclick="relatorioCombate()" class="btn btn-sm btn-primary">
            <i class="fa fa-file-text"></i><br />
            Relatório
        </button>

        <div id="relatorio-combate-content">
            <?php $combate_logger = new CombateLogger($connection, $userDetails); ?>
            <?php render_relatorio_data($combate_logger->get_relatorio_combate_bot(), $userDetails->tripulacao["id"]); ?>
        </div>
    <?php endif; ?>
</div>
