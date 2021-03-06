<?php
function recebe_recompensa($recompensa, $pers = null, $exit_error = true) {
    global $connection;
    global $userDetails;
    global $protector;

    switch ($recompensa["tipo"]) {
        case "berries":
            $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
                "ii", array($recompensa["quant"], $userDetails->tripulacao["id"]));
            return "Você recebeu " . mascara_berries($recompensa["quant"]) . " Berries";
        case "xp":
            $userDetails->xp_for_all($recompensa["quant"]);
            return "Você recebeu " . mascara_berries($recompensa["quant"]) . " Pontos de experiência";
        case "dobrao":
            $connection->run("UPDATE tb_conta SET dobroes = dobroes + ? WHERE conta_id = ?",
                "ii", array($recompensa["quant"], $userDetails->conta["conta_id"]));
            return "Você recebeu " . $recompensa["quant"] . " Dobrões de Ouro";
        case "haki":
            $userDetails->haki_for_all($recompensa["quant"]);
            return "Sua tripulação recebeu " . $recompensa["quant"] . " pontos de Haki";
        case "akuma":
            if (!$userDetails->add_item(rand(100, 110), rand(8, 10), 1, true) && $exit_error) {
                $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
            }
            return "Você recebeu uma Akuma no Mi";
        case "alcunha":
            $alcunha = $connection->run("SELECT * FROM tb_personagem_titulo WHERE cod = ? AND titulo = ?",
                "ii", array($pers ? $pers["cod"] : $userDetails->capitao["cod"], $recompensa["cod_titulo"]));
            if (!$alcunha->count()) {
                $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
                    "ii", array($pers ? $pers["cod"] : $userDetails->capitao["cod"], $recompensa["cod_titulo"]));
            }
            return "Você recebeu uma nova Alcunha";
        case "skin":
            $connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin) VALUE (?, ?, ?)",
                "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
            return "Você recebeu uma nova recompensa";
        case "skin_navio":
            $connection->run("INSERT INTO tb_tripulacao_skin_navio (tripulacao_id, skin_id) VALUE (?, ?)",
                "ii", array($userDetails->tripulacao["id"], $recompensa["skin"]));
            return "Você recebeu uma nova recompensa";
        case "effect":
            $userDetails->add_effect($recompensa["effect"]);
            return "Você recebeu a animação de habilidade " . $recompensa["effect"];
        case "moeda_evento":
            $connection->run("UPDATE tb_usuarios SET moedas_evento = moedas_evento + ? WHERE id = ?",
                "ii", array($recompensa["quant"], $userDetails->tripulacao["id"]));
            return "Você recebeu uma moeda de evento!";
        case "reagent":
            if (!$userDetails->add_item($recompensa["cod_item"], TIPO_ITEM_REAGENT, $recompensa["quant"]) && $exit_error) {
                $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
            }
            return "Você recebeu uma nova recompensa";
        case "equipamento":
            if (!$userDetails->can_add_item()) {
                if ($exit_error) {
                    $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
                } else {
                    return "";
                }
            }

            $userDetails->add_equipamento_by_cod($recompensa["cod_item"]);
            return "Você recebeu uma nova recompensa";
        case "wanted":
            $connection->run("UPDATE tb_personagens SET fama_ameaca = fama_ameaca + ? WHERE id = ?",
                "ii", array($recompensa["quant"], $userDetails->tripulacao["id"]));
            return "A " . ($userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "gratificação" : "recompensa") . " pela sua tripulação aumentou!";
    }
    return "";
}

function render_recompensa($recompensa, $reagents, $equipamentos) {
    global $userDetails, $connection; ?>
    <?php if ($recompensa["tipo"] == "berries"): ?>
        <p>
            <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($recompensa["quant"]) ?>
        </p>
    <?php elseif ($recompensa["tipo"] == "xp"): ?>
        <p>
            <img style="border-radius: 5px" src="Imagens/NPC/xp.jpg" data-toggle="tooltip"
                 title="Pontos de Experiência para toda tripulação"/>
            <?= mascara_numeros_grandes($recompensa["quant"]) ?>
        </p>
    <?php elseif ($recompensa["tipo"] == "dobrao"): ?>
        <p>
            <img src="Imagens/Icones/Dobrao.png"/> <?= $recompensa["quant"] ?>
        </p>
    <?php elseif ($recompensa["tipo"] == "haki"): ?>
        <p>
            <?= mascara_numeros_grandes($recompensa["quant"]) ?> pontos de Haki para distribuir entre os tripulantes
        </p>
    <?php elseif ($recompensa["tipo"] == "akuma"): ?>
        <p>
            <img src="Imagens/Itens/100.png"> Akuma no Mi Aleatória
        </p>
    <?php elseif ($recompensa["tipo"] == "alcunha"): ?>
        <?php $alcunha = $connection->run("SELECT * FROM tb_titulos WHERE cod_titulo = ?", "i", array($recompensa["cod_titulo"]))->fetch_array(); ?>
        <p>
            Alcunha: <strong><?= $alcunha["nome"] ?></strong>
        </p>
    <?php elseif ($recompensa["tipo"] == "skin"): ?>
        <p>Aparência exclusiva</p>
        <p>
            <?= icon_pers_skin($recompensa["img"], $recompensa["skin"]) ?>
        </p>
        <p>
            <?= big_pers_skin($recompensa["img"], $recompensa["skin"]) ?>
        </p>
    <?php elseif ($recompensa["tipo"] == "skin_navio"): ?>
        <p>Aparência de navio exclusiva</p>
        <p>
            <?php render_navio_skin($userDetails->tripulacao["bandeira"], $userDetails->tripulacao["faccao"], $recompensa["skin"]) ?>
        </p>
    <?php elseif ($recompensa["tipo"] == "moeda_evento"): ?>
        <p>
            <?= $recompensa["quant"] ?>
            <img src="Imagens/Icones/MoedaEvento.png" data-toggle="tooltip"
                 title="Medalha de Evento. Pode ser usada para comprar recompensas especiais na Loja de Eventos">
            Medalha de Evento
        </p>
    <?php elseif ($recompensa["tipo"] == "reagent"): ?>
        <div class="clearfix">
            <div class="equipamentos_casse_1" style="display: inline-block; margin: auto">
                <img src="Imagens/Itens/<?= $reagents[$recompensa["cod_item"]]["img"] ?>.<?= $reagents[$recompensa["cod_item"]]["img_format"] ?>">
            </div>
            <p>
                <?= $reagents[$recompensa["cod_item"]]["nome"] ?>
                x <?= $recompensa["quant"] ?>
            </p>
        </div>
    <?php elseif ($recompensa["tipo"] == "equipamento"): ?>
        <div class="clearfix">
            <?= info_item_with_img($equipamentos[$recompensa["cod_item"]], $equipamentos[$recompensa["cod_item"]], FALSE, FALSE, FALSE) ?>
        </div>
    <?php elseif ($recompensa["tipo"] == "wanted"): ?>
        <div class="clearfix">
            Aumento na <?= $userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "gratificação" : "recompensa"; ?> da
            tripulação em
            <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($recompensa["quant"]) ?>
        </div>
    <?php endif; ?>
<? }

function get_reagents_for_recompensa() {
    global $connection;
    $reagents_db = $connection->run("SELECT * FROM tb_item_reagents")->fetch_all_array();
    $reagents = array();
    foreach ($reagents_db as $reagent) {
        $reagents[$reagent["cod_reagent"]] = $reagent;
    }
    return $reagents;
}

function get_equipamentos_for_recompensa() {
    global $connection;
    $equipamentos_db = $connection->run("SELECT * FROM tb_equipamentos")->fetch_all_array();
    $equipamentos = array();
    foreach ($equipamentos_db as $equip) {
        $equipamentos[$equip["item"]] = $equip;
    }
    return $equipamentos;
}

function render_recompensas($recompensas, $progresso_recompensa, $acao, $alvo, $script_recompensa, $tabela_recompensado) { ?>
    <?php global $connection; ?>
    <?php global $userDetails; ?>
    <?php $reagents = get_reagents_for_recompensa() ?>
    <?php $equipamentos = get_equipamentos_for_recompensa() ?>
    <div class="row">
    <?php foreach ($recompensas as $id => $recompensa) : ?>
        <div class="col-xs-4 col-md-4 list-group-item">
                <?php if (isset($recompensa["objetivo"])): ?>
                    <h4><?= $recompensa["objetivo"] ?></h4>
                <?php else: ?>
                    <h4><?= $acao ?> <?= $recompensa["minimo"] ?> <?= $alvo ?></h4>
                <?php endif; ?>
                <h5>Recompensa:</h5>
                <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                <p>
                    500 - Pontos de Experiência
                </p>
                <?php $progresso = is_array($progresso_recompensa) ? $progresso_recompensa[$id] : $progresso_recompensa; ?>
                <?php if ($progresso >= $recompensa["minimo"]): ?>
                    <?php $recompensado = $connection->run("SELECT count(*) AS total FROM $tabela_recompensado WHERE tripulacao_id = ? AND recompensa_id = ?",
                        "ii", array($userDetails->tripulacao["id"], $id))->fetch_array()["total"]; ?>

                    <?php if (!$recompensado): ?>
                        <p>
                            <button class="btn btn-success link_send"
                                    href="link_<?= $script_recompensa ?>?rec=<?= $id ?>">
                                Obter recompensa
                            </button>
                        </p>
                    <?php else: ?>
                        <p class="text-success">
                            <i class="fa fa-check"></i> Você já adquiriu essa recompensa!
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
    <?php endforeach; ?>
    </div>
<?php } ?>