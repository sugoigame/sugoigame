<?php
function nome_prof($cod) {
    switch ($cod) {
        case PROFISSAO_CARTOGRAFO:
            return "Cartógrafo";
        case PROFISSAO_NAVEGADOR:
            return "Navegador";
        case PROFISSAO_MEDICO:
            return "Médico";
        case PROFISSAO_CARPINTEIRO:
            return "Carpinteiro";
        case PROFISSAO_ARQUEOLOGO:
            return "Arqueólogo";
        case PROFISSAO_MERGULHADOR:
            return "Mergulhador";
        case PROFISSAO_COZINHEIRO:
            return "Cozinheiro";
        case PROFISSAO_MUSICO:
            return "Músico";
        case PROFISSAO_COMBATENTE:
            return "Combatente";
        case PROFISSAO_ENGENHEIRO:
            return "Engenheiro";
        case PROFISSAO_FERREIRO:
            return "Ferreiro";
        case PROFISSAO_ARTESAO:
            return "Artesão";
        default:
            return "Nenhuma";
    }
}

function desc_prof($cod) {
    switch ($cod) {
        case PROFISSAO_CARTOGRAFO:
            return "Possui a habilidade de desenhar mapas enquanto você navega para poder traçar rotas maiores.";
        case PROFISSAO_NAVEGADOR:
            return "Consegue enxergar ventos e correntes ajudando na navegação e na armação de rotas estratégicas.";
        case PROFISSAO_MEDICO:
            return "Possui a habilidade de produzir medicamentos e consegue recuperar companheiros que caíram em combate.";
        case PROFISSAO_CARPINTEIRO:
            return "Capaz de reparar estragos no navio e tambem aprimorá-lo.";
        case PROFISSAO_ARQUEOLOGO:
            return "Capaz de sair em terra atrás de artefatos históricos preciosos.";
        case PROFISSAO_MERGULHADOR:
            return "Consegue ir ao fundo do oceano atrás de tesouros.";
        case PROFISSAO_COZINHEIRO:
            return "Prepara refeições que energizam seus companheiros e os prepara para o combate.";
        case PROFISSAO_MUSICO:
            return "Alimenta a tensão dos combates com músicas que fortalecem seus companheiros.";
        case PROFISSAO_COMBATENTE:
            return "A força de combate da tripulação, aprende diversas técnicas de combate que ajudam na hora da batalha.";
        case PROFISSAO_ENGENHEIRO:
            return "Faça combinações diversas com seus itens podendo juntar vários itens fracos para criar um item forte, ou simplesmente criar itens diversos.";
        case PROFISSAO_FERREIRO:
            return "Trabalhe com metais para produzir itens e equipamentos para sua tripulação";
        case PROFISSAO_ARTESAO:
            return "Trabalhe com madeira, tecidos e diversos materiais para produzir itens e equipamentos para sua tripulação";
        default:
            return "Nenhuma";
    }
}

function calc_preco_tratamento_quartos($personagens) {
    $preco = 0;

    foreach ($personagens as $pers) {
        $preco += $pers["lvl"] * 1000;
    }
    return $preco;
}

function calc_tempo_tratament_quartos($pers) {
    global $userDetails;
    return (10 * max(0, $pers["lvl"] - 20)) * (1 - ($userDetails->lvl_medico * 0.05));
}

function render_painel_profissao($pers) { ?>
    <?php global $userDetails; ?>
    <?php if (!$pers["profissao"]): ?>
        <p>
            Este personagem ainda não tem função no navio, visite a escola de profissões de alguma ilha para aprender
            uma profissão.
        </p>
        <?php if ($userDetails->in_ilha): ?>
            <p>
                <a class="link_content" href="./?ses=profissoesAprender&cod=<?= $pers["cod"] ?>">
                    Ir para a Escola de Profissões
                </a>
            </p>
        <?php endif; ?>
    <?php else: ?>
        <h3><?= nome_prof($pers["profissao"]) ?></h3>
        <h4>Nível: <?= $pers["profissao_lvl"]; ?></h4>
        <div class="progress">
            <div class="progress-bar"
                 style="width: <?= $pers["profissao_xp"] / $pers["profissao_xp_max"] * 100 ?>%;">
                <span>EXP:<?= $pers["profissao_xp"] . "/" . $pers["profissao_xp_max"] ?></span>
            </div>
        </div>
        <?php if ($pers["profissao"] == PROFISSAO_CARTOGRAFO): ?>
            <p>
                O cartógrafo ganha experiência desenhando o campo de visão do jogador em um mapa.
            </p>
            <p>
                Tempo gasto para desenhar o campo de visão:
                <?= transforma_tempo_min(130 - ($pers["profissao_lvl"] * 10)); ?>
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_NAVEGADOR) : ?>
            <p>
                O navegador ganha experiência enquanto você navega pelo oceano
            </p>
            <p>
                Correntes Identificadas:
                <?php
                if ($pers["profissao_lvl"] <= 3) echo "Rank A";
                else if ($pers["profissao_lvl"] <= 8) echo "Rank B";
                else echo "Rank A";
                ?>
            </p>
            <p>
                Ventos Identificados:
                <?php
                if ($pers["profissao_lvl"] <= 2) echo "Rank A";
                else if ($pers["profissao_lvl"] <= 9) echo "Rank B";
                else echo "Rank A";
                ?>
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_CARPINTEIRO) : ?>
            <p>
                O carpinteiro ganha experiência consertando seu navio e madeirando para obter recursos
            </p>
            <p>
                Bônus na velocidade de reparos:
                <?= $pers["profissao_lvl"] * 5; ?>%
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_ARQUEOLOGO) : ?>
            <p>
                O arqueólogo ganha experiência realizando explorações em ilhas em busca de tesouros
            </p>
            <p>
                Duração da expedição:
                <?= transforma_tempo_min(3600 - (($pers["profissao_lvl"] - 1) * 300)); ?>
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_MERGULHADOR) : ?>
            <p>
                O mergulhador ganha experiência realizando mergulhos em busca de tesouros
            </p>
            <p>
                Duração do mergulho:
                <?= transforma_tempo_min(3600 - (($pers["profissao_lvl"] - 1) * 300)); ?>
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_MEDICO) : ?>
            <p>
                O médico ganha experiência criando remédios de MESMO NÍVEL dele e também tratando a
                tripulação nos quartos do navio
            </p>
            <p>
                Bônus na velocidade do tratamento:
                <?= $pers["profissao_lvl"] * 5; ?>%
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_COZINHEIRO) : ?>
            <p>
                O cozinheiro ganha experiência criando comidas de MESMO NÍVEL dele
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_COMBATENTE) : ?>
            <p>
                O combatente ganha experiência usando habilidades em combate
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_MUSICO) : ?>
            <p>
                O músico ganha experiência usando habilidades em combate
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_FERREIRO) : ?>
            <p>
                O ferreiro ganha experiência mineirando na ilha em busca de recursos e também construindo
                itens na forja do navio
            </p>
        <?php elseif ($pers["profissao"] == PROFISSAO_ARTESAO) : ?>
            <p>
                O artesão ganha experiência construindo itens na oficina do navio
            </p>
        <?php endif; ?>

        <p>
            <button class="link_confirm btn btn-info" <?= $userDetails->conta["gold"] >= PRECO_GOLD_RESET_PROFISSAO ? "" : "disabled" ?>
                    data-question="Resetar a profissão desse personagem permitirá que ele aprenda uma nova. Porém toda a experiência e níveis adquiridos serão perdidos. Deseja continuar?"
                    href="Vip/reset_profissao.php?cod=<?= $pers["cod"] ?>&tipo=gold">
                <?= PRECO_GOLD_RESET_PROFISSAO ?> <img src="Imagens/Icones/Gold.png"/>
                Resetar Profissão
            </button>
            <button class="link_confirm btn btn-info" <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_PROFISSAO ? "" : "disabled" ?>
                    data-question="Resetar a profissão desse personagem permitirá que ele aprenda uma nova. Porém toda a experiência e níveis adquiridos serão perdidos. Deseja continuar?"
                    href="Vip/reset_profissao.php?cod=<?= $pers["cod"] ?>&tipo=dobrao">
                <?= PRECO_DOBRAO_RESET_PROFISSAO ?> <img src="Imagens/Icones/Dobrao.png"/>
                Resetar Profissão
            </button>
        </p>
    <?php endif; ?>
<?php } ?>