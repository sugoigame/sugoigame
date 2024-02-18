<table>
    <tr>
        <?php foreach ($userDetails->personagens as $count => $pers) : ?>
            <?php if ($count % 2 == 0) : ?>
            </tr>
            <tr>
            <?php endif; ?>
            <td class="tripulante_quadro_td">
                <div id="tripulante_quadro_<? echo $pers["cod"] ?>" onclick="setQueryParam('cod','<?= $pers["cod"]; ?>');"
                    data-cod="<?= $pers["cod"]; ?>" href="./?ses=status&cod=<?= $pers["cod"]; ?>"
                    class="tripulante_quadro <?= $sessao == "status" ? "" : "link_content" ?> <?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "marine" : "pirate" ?>">
                    <?php $userDetails->render_alert("status." . $pers["cod"], "pull-right"); ?>
                    <img class="tripulante_quadro_img <?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "marine" : "pirate" ?>"
                        src="Imagens/Personagens/Icons/<?= getImg($pers, "r"); ?>.jpg">

                    <?php if ($pers["xp"] >= $pers["xp_max"] && $pers["lvl"] < 50) : ?>
                        <div class="tripulante-lvl-up" data-toggle="tooltip" data-placement="bottom" data-container="#tudo"
                            title="Este tripulante já pode evoluir. Acesse a visão geral da tripulação!">
                            <a href="./?ses=status&cod=<?= $pers["cod"] ?>" class="link_content">
                                <img src="Imagens/Icones/quest-1.png">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="tripulante_level">
                        <?= $pers["lvl"] ?>
                    </div>
                    <div class="tripulante_quadro_td_status">
                        <?php render_personagem_status_bars($pers, false); ?>
                    </div>
                </div>
            </td>
        <?php endforeach; ?>
    </tr>
</table>
