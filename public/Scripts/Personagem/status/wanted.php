<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>

<?php $fa_premios = DataLoader::load("fa_premios"); ?>
<?php $reagents = get_reagents_for_recompensa(); ?>
<?php $equipamentos = get_equipamentos_for_recompensa(); ?>

<div class="row">
    <div class="col-md-12">
        <h4>
            Valor exato da recompensa:
            <imgsrc
            ="Imagens/Icones/Berries.png"/> <?= mascara_berries($pers["fama_ameaca"]) ?>
        </h4>
        <h3>Prêmios disponíveis:</h3>
        <ul class="list-group">
            <?php foreach ($fa_premios as $premio_index => $premio): ?>
                <li class="list-group-item">
                    <h4>Prêmio por uma
                        <?= $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "Recompensa" : "Gratificação"; ?>
                        de <?= mascara_berries($premio["objetivo"]) ?> de Berries</h4>
                    <?php if ($pers["fa_premio"] > $premio_index): ?>
                        <p class="text-success">Você já recebeu essa recompensa</p>
                    <?php else: ?>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success"
                                 style="width: <?= $pers["fama_ameaca"] / $premio["objetivo"] * 100 ?>%;">
                                <?= mascara_berries($pers["fama_ameaca"]) . "/" . mascara_berries($premio["objetivo"]) ?>
                            </div>
                        </div>
                        <?php foreach ($premio["recompensas"] as $recompensa): ?>
                            <p>
                                <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                                <?php if (isset($recompensa["unico"])): ?>
                                    <span class="text-warning">Único para a tripulação</span>
                                <?php endif; ?>
                            </p>
                        <?php endforeach; ?>
                        <?php if ($pers["fama_ameaca"] >= $premio["objetivo"] && $premio_index == $pers["fa_premio"]): ?>
                            <p>
                                <button class="btn btn-success link_send"
                                        href="link_Personagem/premio_wanted.php?cod=<?= $pers["cod"] ?>">
                                    Receber o prêmio
                                </button>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>
            A sua
            <?= $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "Recompensa" : "Gratificação"; ?>
            aumenta apenas em batalhas PvP, sempre que:
        </p>
        <ul class="text-left">
            <li>Você causar dano em um adversário (100.000 Berries para cada 1.000 pontos de dano)
            </li>
            <li>Seus pontos de defesa absorverem dano (70.000 Berries para cada 1.000 pontos de dano
                absorvidos)
            </li>
            <li>Você se esquivar (1.000 Berries por nível do adversário)</li>
            <li> Você acertar um ataque com mais precisão que a agilidade do adversário (200 Berries
                por nível do adversário)
            </li>
            <li>Você bloquear (1.000 Berries por nível do adversário)</li>
            <li>
                Você acertar um ataque com mais precisão que a resistência do adversário (200
                Berries por nível do adversário)
            </li>
            <li>Você acertar um ataque crítico (1.000 Berries por nível do adversário)</li>
            <li>Você não receber um ataque crítico tendo mais percepção do que a destreza do
                adversário (200 Berries por nível do adversário)
            </li>
        </ul>
        <p>
            Por outro lado, a sua
            <?= $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "Recompensa" : "Gratificação"; ?>
            também pode ser reduzida quando você perde uma batalha PvP:
        </p>
        <ul class="text-left">
            <li>Tripulantes com menos de 10.000.000 Berries não terão sua
                <?= $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "Recompensa" : "Gratificação"; ?>
                reduzida.
            </li>
            <li>
                Caso você perca uma batalha, seus tripulantes perdem 1.000.000 de Berries menos 750
                Berries por cada ponto em vitalidade.
            </li>
        </ul>
        <p>Observações:</p>
        <ul class="text-left">
            <li>O capitão sempre recebe 20% mais Berries do que o resto da tripulação</li>
            <li>
                O limite de Berries que um tripulante pode receber em uma batalha é de 5.000.000
            </li>
            <li>
                O limite de Berries que o capitão pode receber em uma batalha é de 10.000.000
            </li>
        </ul>
    </div>
</div>
