<div class="panel-heading">
    Enfermaria do navio
    <?= ajuda_tooltip("Aqui você pode curar seus tripulantes que sofreram no combate.") ?>
</div>
<script type="text/javascript">
    $(function () {
        timeOuts["atualiza_tempo_hospital"] = setTimeout("atualiza_tempo_hospital()", 1000);
    });
    var conttmp = 0;

    function atualiza_tempo_hospital() {
        timeOuts["atualiza_tempo_hospital"] = setTimeout("atualiza_tempo_hospital()", 1000);
        for (var x = 0; x < 20; x++) {
            var sec_rest = "tempo_sec_rest_" + x;
            var min_rest = "tempo_min_rest_" + x;
            if (document.getElementById(sec_rest) != null) {
                var tmp = document.getElementById(sec_rest).value - conttmp;
                document.getElementById(min_rest).innerHTML = transforma_tempo(tmp);
                if (tmp < 0) {
                    reloadPagina();
                    return;
                }
            }
        }
        conttmp += 1;
    }
</script>
<div class="panel-body">
    <?php $personagens_recuperando = array_filter($userDetails->personagens, function ($pers) {
        return $pers["respawn_tipo"] == RECUPERACAO_TIPO_HOSPITAL;
    }); ?>
    <?php $personagens_machucados = array_filter($userDetails->personagens, function ($pers) {
        return $pers["hp"] < $pers["hp_max"] && $pers["respawn_tipo"] == 0;
    }); ?>
    <?php $personagens = array_merge($personagens_machucados, $personagens_recuperando); ?>
    <div class="row">
        <div class="col-xs-12">
            <?php if (! count($personagens_machucados) && ! count($personagens_recuperando)) : ?>
                <p>Sua tripulação não precisa de tratamento!</p>
            <?php endif; ?>
            <?php if (count($personagens_recuperando)) : ?>
                <p>Tripulantes em tratamento:</p>
                <div class="row justify-content-center">
                    <?php foreach ($personagens_recuperando as $index => $pers) : ?>
                        <?php $tempo = $pers["respawn"] - atual_segundo(); ?>
                        <div class="col col-xs-2 mb3">
                            <img src="Imagens/Personagens/Icons/<?= getImg($pers, "r"); ?>.jpg">
                            <?php render_personagem_hp_bar($pers); ?>
                            <div>
                                <strong>Tempo Restante: </strong>
                                <span id="tempo_min_rest_<?= $index ?>">
                                    <?= transforma_tempo_min($tempo); ?>
                                </span>
                                <input id="tempo_sec_rest_<?= $index ?>" type="hidden" value="<?= $tempo ?>" />
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (count($personagens_machucados)) : ?>
                <p>Tripulantes precisando de tratamento:</p>
                <div class="row justify-content-center">
                    <?php foreach ($personagens_machucados as $index => $pers) : ?>
                        <div class="col col-xs-2">
                            <img src="Imagens/Personagens/Icons/<?= getImg($pers, "r"); ?>.jpg">
                            <?php render_personagem_hp_bar($pers); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <p>
                <?php if (count($personagens_machucados) || count($personagens_recuperando)) : ?>
                    <?php $preco = calc_preco_recuperar_tripulantes($personagens); ?>
                    <button class="btn btn-success link_confirm" href='Hospital/hospital_iniciar_recuperacao_berries.php'
                        data-question="Tem certeza que deseja pagar para recuperar todos os tripulantes imediatamente?"
                        <?= $userDetails->tripulacao["berries"] < $preco ? "disabled" : ""; ?>>
                        Recuperar tripulação imediatamente<br />
                        <img src="Imagens/Icones/Berries.png" alt=" Berries">
                        <?= mascara_berries($preco); ?>
                    </button>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
