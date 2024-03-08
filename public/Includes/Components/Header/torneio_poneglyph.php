<?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_TORNEIO_PONEGLIPH)) : ?>
    <?php $torneio = get_current_torneio_poneglyph_completo(); ?>
    <?php $inscritos = get_inscritos_torneio_poneglyph($torneio); ?>
    <div id="torneio-poneglyph">
        <a href="./?ses=torneio" class="link_content text-center">
            <?php if ($torneio["status"] === TORNEIO_STATUS_AGUARDANDO) : ?>
                <?php $coord = json_decode($torneio["coordenadas"], true)[$userDetails->ilha["mar"]]; ?>
                <div class="<?= $coord["x"] == $userDetails->tripulacao["x"]
                    && $coord["y"] == $userDetails->tripulacao["y"]
                    ? "user-progress-finished"
                    : "" ?>">
                    <div>Poneglyph localizado:</div>
                    <strong>
                        <?= get_human_location($coord["x"], $coord["y"]) ?>
                    </strong>
                    <small>
                        Até
                        <?= date("H:i", $torneio["limite_inscricao"]) ?>
                    </small>
                    <div>

                    </div>
                    <div>
                        <small>
                            Tripulações no local:
                            <?= count($inscritos) ?>/8
                        </small>
                    </div>
                </div>
            <?php else : ?>
                <div>
                    O próximo Poneglyph aparecerá às
                    <?= date("H:i", $torneio["end"]) ?>
                </div>
            <?php endif; ?>
        </a>
    </div>
<?php endif; ?>

