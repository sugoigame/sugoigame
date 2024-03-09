<div class="panel-heading">
    Disputa pelo Poneglyph
    <?= ajuda_tooltip("Lute contra outros jogadores para obter Poneglyphs") ?>
</div>

<div class="panel-body">
    <?php $torneio = get_current_torneio_poneglyph_completo(); ?>
    <?php $inscritos = get_inscritos_torneio_poneglyph($torneio); ?>
    <?php $estou_inscrito = array_find($inscritos, ["tripulacao_id" => $userDetails->tripulacao["id"]]) ?>

    <?php if ($torneio["status"] === TORNEIO_STATUS_AGUARDANDO) : ?>
        <?php $coord = json_decode($torneio["coordenadas"], true)[$userDetails->ilha["mar"]]; ?>
        <?php $no_local = $coord["x"] == $userDetails->tripulacao["x"]
            && $coord["y"] == $userDetails->tripulacao["y"]; ?>
        <div class="mb">
            <p>
                Poneglyph localizado:
                Viaje para
                <strong>
                    <?= get_human_location($coord["x"], $coord["y"]) ?>
                </strong>
                até às
                <?= date("H:i", $torneio["limite_inscricao"]) ?>
            </p>
            <p>
                <strong>Importante:</strong>
                É necessário estar com o PVP Ativado para lutar pelo Poneglyph.
            </p>

            <?php if ($estou_inscrito) : ?>
                <span class="text-success">
                    <i class="fa fa-check"></i>Você já está inscrito!
                </span>
            <?php else : ?>
                <button class="btn btn-success link_send" href="link_torneio/torneio_inscrever.php" <?= $no_local ? "" : "disabled" ?>>
                    Quero participar
                </button>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="mb">
            O próximo Poneglyph aparecerá às
            <?= date("H:i", $torneio["end"]) ?>
        </div>

        <div class="mb">
            Acompanhe a atual disputa:
        </div>
    <?php endif; ?>

    <p>
        Tripulações no local:
        <?= count($inscritos) ?> /
        <?= TORNEIO_LIMITE_PARTICIPANTES ?>
    </p>
    <div class="row mt4 justify-content-center">
        <?php foreach ($inscritos as $inscrito) : ?>
            <div class="col mx" style="width: 8%">
                <?= img_bandeira($inscrito); ?>
                <br />
                <?= $inscrito["tripulacao"]; ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
