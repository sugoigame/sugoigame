<table>
    <tr>
        <?php foreach ($userDetails->personagens as $pers) : ?>
        <td class="tripulante_quadro_td">
            <div id="tripulante_quadro_<? echo $pers["cod"] ?>"
                class="tripulante_quadro <?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "marine" : "pirate" ?>"
                data-content="-" data-container="#tudo" <?= $sessao != "mar" ? 'data-toggle="popover"' : "" ?>
                data-placement="bottom"
                data-html="true" data-trigger="focus" tabindex="0"
                data-template='
                    <div class="container tripulante_quadro_info">
                        <div class="row">
                            <div class="col-xs-6">
                                <?= big_pers_skin($pers["img"], $pers["skin_c"], $pers["borda"], "tripulante_big_img", 'width="100%"') ?>
                            </div>
                            <div class="col-xs-6">
                                <div>
                                    <strong><?= $pers["nome"]; ?></strong>
                                </div>
                                <div>
                                    Nível <?= $pers["lvl"]; ?>
                                </div>

                                <?php render_personagem_status_bars($pers); ?>

                                <div class="tripulante_score">
                                    Score: <?php echo((int)$pers["classe_score"]) ?>
                                </div>

                                <?php render_personagem_haki_bars($pers); ?>
                                <?php if ($userDetails->vip["conhecimento_duracao"]) : ?>
                                    <?php render_row_atributo("atk", "Ataque", $pers); ?>
                                    <?php render_row_atributo("def", "Defesa", $pers); ?>
                                    <?php render_row_atributo("pre", "Precisao", $pers); ?>
                                    <?php render_row_atributo("agl", "Agilidade", $pers); ?>
                                    <?php render_row_atributo("res", "Resistencia", $pers); ?>
                                    <?php render_row_atributo("con", "Conviccao", $pers); ?>
                                    <?php render_row_atributo("dex", "Dextreza", $pers); ?>
                                    <?php render_row_atributo("vit", "Vitalidade", $pers); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>'>
                    <img class="tripulante_quadro_img <?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "marine" : "pirate" ?>" src="Imagens/Personagens/Icons/<?= getImg($pers, "r"); ?>.jpg">
                    <div class="recompensa_text <?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "marine" : "pirate" ?>">
                        <?php echo $pers["nome"] . "<br>";
                        $rec = calc_recompensa($pers["fama_ameaca"]);
                        echo mascara_berries($rec);
                        ?>
                    </div>
                    <?php if ($pers["xp"] >= $pers["xp_max"] && $pers["lvl"] < 50): ?>
                        <div class="tripulante-lvl-up" data-toggle="tooltip"
                                data-placement="bottom" data-container="#tudo"
                                title="Este tripulante já pode evoluir. Acesse a visão geral da tripulação!">
                            <a href="./?ses=status&cod=<?= $pers["cod"] ?>" class="link_content">
                                <img src="Imagens/Icones/quest-1.png">
                            </a>
                        </div>
                    <?php elseif ($pers["xp"] >= $pers["excelencia_xp_max"] && $pers["lvl"] <= 50): ?>
                        <div class="tripulante-lvl-up" data-toggle="tooltip"
                                data-placement="bottom" data-container="#tudo"
                                title="Este tripulante já pode evoluir um nível de Excelência. Acesse a visão geral da tripulação!">
                            <a href="./?ses=status&cod=<?= $pers["cod"] ?>" class="link_content">
                                <img src="Imagens/Icones/quest-1.png">
                            </a>
                        </div>
                    <?php endif; ?>
            </div>
            <div class="tripulante_quadro_td_status">
                <?php render_personagem_status_bars($pers, false); ?>
            </div>
        </td>
        <?php endforeach; ?>
    </tr>
</table>
