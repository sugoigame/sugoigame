<?php function render_torneio_button($chave, $posicao)
{
    global $userDetails;

    $is_position = $userDetails->tripulacao["id"] == $chave["tripulacao_" . $posicao . "_id"];
    ?>
    <?php if ($chave["tripulacao_" . $posicao . "_pronto"]) : ?>
        <button class="btn btn-info" disabled title="Pronto!" data-toggle="tooltip" data-placement="top" data-trigger="hover">
            <i class="fa fa-check"></i>
        </button>
    <?php else : ?>
        <button class="btn btn-<?= $is_position ? "success link_confirm" : "default" ?>" <?= ! $is_position ? "disabled" : ""; ?>
            title="<?= $is_position ? "Estou pronto!" : "Aguardando..." ?>" data-toggle="tooltip" data-placement="top"
            data-question="Quando ambas as tripulações estiverem prontas a batalha começará automaticamente. Você está pronto para começar essa batalha?"
            data-trigger="hover" <?= $is_position ? 'href="Torneio/torneio_pronto.php"' : "" ?>>
            <i class="fa fa-<?= $is_position ? "check" : "spinner"; ?>"></i>
        </button>
    <?php endif; ?>
<?php
}
?>
<?php function render_chave_torneio($chave)
{
    ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row mb">
                <div class="col-xs-12 small">
                    Deve começar antes de
                    <?= date("H:i", strtotime($chave["limite_inicio"])); ?>
                    e terminar antes de
                    <?= date("H:i", strtotime($chave["limite_fim"])); ?>.
                </div>
            </div>
            <div class="row">
                <div class="col-xs-5">
                    <div
                        class="<?= $chave["finalizada"] && $chave["tripulacao_1_id"] == $chave["vencedor"] ? "equipamentos_classe_3" : "" ?>">
                        <?php if ($chave["tripulacao_1"]) : ?>
                            <?= img_bandeira($chave["tripulacao_1"]) ?>
                        <?php else : ?>
                            <img src="Imagens/Transparent-white.png" alt="bandeira" />
                        <?php endif; ?>
                    </div>
                    <div>
                        <?= $chave["tripulacao_1"]["tripulacao"]; ?>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="d-flex flex-column h-100 align-items-center">
                        <img src="Imagens/Batalha/vs.png" alt="vs" width="100%" />
                    </div>
                </div>
                <div class="col-xs-5">
                    <div
                        class="<?= $chave["finalizada"] && $chave["tripulacao_2_id"] == $chave["vencedor"] ? "equipamentos_classe_3" : "" ?>">
                        <?php if ($chave["tripulacao_2"]) : ?>
                            <?= img_bandeira($chave["tripulacao_2"]) ?>
                        <?php else : ?>
                            <img src="Imagens/Transparent-white.png" alt="bandeira" />
                        <?php endif; ?>
                    </div>
                    <div>
                        <?= $chave["tripulacao_2"]["tripulacao"]; ?>
                    </div>
                </div>
            </div>
            <?php if (! $chave["em_andamento"] && ! $chave["finalizada"]) : ?>
                <div class="row">
                    <div class="col-xs-5">
                        <?php render_torneio_button($chave, "1"); ?>
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-5">
                        <?php render_torneio_button($chave, "2"); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($chave["em_andamento"]) : ?>
                <div class="row">
                    <div class="col-xs-5">
                        <?= $chave["placar_1"]; ?>
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-5">
                        <?= $chave["placar_2"]; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <a href="./?ses=combateAssistir&combate=<?= $chave["combate_id"]; ?>" class="link_content"
                            title="Assista essa partida">
                            Assistir
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (! $chave["em_andamento"] && $chave["finalizada"]) : ?>
                <div class="row">
                    <div class="col-xs-5">
                        <?= $chave["placar_1"]; ?>
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-5">
                        <?= $chave["placar_2"]; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="label label-success">
                            Vencedor:
                            <?= $chave["tripulacao_1_id"] == $chave["vencedor"]
                                ? $chave["tripulacao_1"]["tripulacao"]
                                : $chave["tripulacao_2"]["tripulacao"]; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>

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
                <button class="btn btn-success link_send" href="link_Torneio/torneio_inscrever.php" <?= $no_local ? "" : "disabled" ?>>
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
            Acompanhe a disputa atual:
        </div>
    <?php endif; ?>

    <p>
        Tripulações no local:
        <?= count($inscritos) ?> /
        <?= TORNEIO_LIMITE_PARTICIPANTES ?>
    </p>

    <?php if ($torneio["status"] === TORNEIO_STATUS_AGUARDANDO) : ?>
        <div class="row mt4 justify-content-center">
            <?php foreach ($inscritos as $inscrito) : ?>
                <div class="col mx" style="width: 8%">
                    <?= img_bandeira($inscrito); ?>
                    <br />
                    <?= $inscrito["tripulacao"]; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <?php
        $chaves = $connection->run(
            "SELECT * FROM tb_torneio_chave WHERE torneio_id = ?",
            "i", [$torneio["id"]]
        )->fetch_all_array();
        ?>

        <?php if (count($chaves)) : ?>
            <?php
            $tripulacoes = [];
            foreach ($chaves as $chave) {
                if ($chave["tripulacao_1_id"]) {
                    $tripulacoes[] = $chave["tripulacao_1_id"];
                }
                if ($chave["tripulacao_2_id"]) {
                    $tripulacoes[] = $chave["tripulacao_2_id"];
                }
            }
            $tripulacoes = $connection->run(
                "SELECT * FROM tb_usuarios WHERE id IN (" . implode($tripulacoes, ",") . ")"
            )->fetch_all_array();

            foreach ($chaves as $key => $chave) {
                $chaves[$key]["tripulacao_1"] = array_find($tripulacoes, ["id" => $chave["tripulacao_1_id"]]);
                $chaves[$key]["tripulacao_2"] = array_find($tripulacoes, ["id" => $chave["tripulacao_2_id"]]);
            }
            ?>

            <div class="row">
                <?php foreach ($chaves as $chave) : ?>
                    <div class="col-xs-4">
                        <?php render_chave_torneio($chave); ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    <?php endif; ?>
</div>
