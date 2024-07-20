<?php
$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);
$personagens_combate = get_pers_in_combate($userDetails->tripulacao["id"]);

$perdeu = true;

$tabuleiro = [];
foreach ($personagens_combate as $pers) {
    if ($pers["hp"]) {
        $perdeu = false;
        $tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
    }
}
?>

<?php render_battle_heading(); ?>
<div id="navio_batalha">
    <?php if (! $userDetails->combate_pve["hp_npc"]) : ?>
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
        <div id="batalha_background" <?php if ($userDetails->combate_pve["battle_back"]) : ?>
                style="background: url(Imagens/Batalha/BattleBacks/<?= $userDetails->combate_pve["battle_back"] ?>.jpg) no-repeat top center; -webkit-background-size: cover; background-size: cover;"
            <?php endif; ?>>
            <?php $style = $userDetails->combate_pve["skin_npc"] !== null
                ? "background: url('Imagens/Personagens/Big/" . get_img(array("img" => $userDetails->combate_pve["img_npc"], "skin_c" => $userDetails->combate_pve["skin_npc"]), "c") . ".jpg') no-repeat center bottom;background-size: 160px;"
                : "background: url(Imagens/Batalha/Npc/" . $userDetails->combate_pve["img_npc"] . ".png) no-repeat center bottom;background-size: contain;" ?>
            <div class="fight-zone">
                <div class="row" style="margin-top: 25px;">
                    <div class="navio navio-npc selecionavel" data-cod="npc"
                        style="<?= $style ?>display: flex; flex-direction: column; padding-top: 10px;">
                        <div class="progress" style="min-height: 1vw;">
                            O adversário está mirando na
                            <?= $userDetails->combate_pve["mira"] + 1 ?>º linha.
                            <small>Seus tripulantes nesta linha ou nas adjacentes tem mais chances de serem atacados</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger"
                                style="width: <?= $userDetails->combate_pve["hp_npc"] / $userDetails->combate_pve["hp_max_npc"] * 100.0 ?>%;">
                                <?= $userDetails->combate_pve["nome_npc"] . ":" .
                                    $userDetails->combate_pve["hp_npc"] . "/" . $userDetails->combate_pve["hp_max_npc"] ?>
                            </div>
                        </div>
                        <div id="npc">
                        </div>
                    </div>
                </div>
                <div class="navio navio-player" <?php if (! $userDetails->combate_pve["battle_back"]) : ?>
                        style="background: url(Imagens/Bandeiras/Navios/<?= $userDetails->tripulacao["faccao"]; ?>/<?= $userDetails->tripulacao["skin_tabuleiro_navio"] ?>/batalha.png) no-repeat center"
                    <?php endif; ?>>
                    <?php render_tabuleiro($tabuleiro, 0, 5, null, $userDetails->combate_pve["mira"]); ?>
                </div>
            </div>

            <div class="personagens-info">
                <?php render_personagens_info($personagens_combate) ?>
            </div>
        </div>
        <?= \Componentes::render("Combate.Menu", ["combate" => $combate]); ?>
        <button id="botao_relatorio" onclick="relatorioCombate()" class="btn btn-sm btn-primary">
            <i class="fa fa-file-text"></i><br />
            Relatório
        </button>

        <div id="relatorio-combate-content">
            <?php $combate_logger = new CombateLogger($connection, $userDetails); ?>
            <?= Componentes::render("Combate.Relatorio", [
                "relatorio" => $combate_logger->get_relatorio_combate_pve(),
                "id_azul" => $userDetails->tripulacao["id"]
            ]); ?>
        </div>
    <?php endif; ?>
</div>
