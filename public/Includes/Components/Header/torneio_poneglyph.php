<?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_TORNEIO_PONEGLIPH)) : ?>
    <?php $torneio = get_current_torneio_poneglyph_completo(); ?>
    <?php $inscritos = get_inscritos_torneio_poneglyph($torneio); ?>
    <div id="torneio-poneglyph" style="display: flex; flex-direction: column; align-items: center;">
        <a href="./?ses=torneio" class="link_content text-center">
            <?php if ($torneio["status"] === TORNEIO_STATUS_AGUARDANDO) : ?>
                <?php $coord = json_decode($torneio["coordenadas"], true)[$userDetails->ilha["mar"]]; ?>
                <div class="<?= $coord["x"] == $userDetails->tripulacao["x"]
                    && $coord["y"] == $userDetails->tripulacao["y"]
                    ? "user-progress-finished"
                    : "" ?>">
                    <div><img src="./Imagens/Icones/poneglyph.png" width="35" alt=""></div>
                    <strong>
                        <div style="display: flex;"><?= get_human_location($coord["x"], $coord["y"]) ?></div>
                    </strong>
                    <small>
                        Até
                        <?= date("H:i", $torneio["limite_inscricao"]) ?>
                    </small>
                    <div>

                    </div>
                    <div>
                        <small>
                            <?= count($inscritos) ?> /
                            <?= TORNEIO_LIMITE_PARTICIPANTES ?>
                        </small>
                    </div>
                </div>
            <?php else : ?>
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <img src="./Imagens/Icones/poneglyph.png" width="35" alt="">
                    <div>
                        <?= date("H:i", $torneio["end"]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </a>
    </div>
<?php endif; ?>

