<div class="panel-heading">
    <?php if ($userDetails->combate_pvp) : ?>
        <?php render_combate_pvp_header($userDetails->combate_pvp, $userDetails->tripulacoes_pvp); ?>
    <?php elseif ($userDetails->combate_pve) : ?>
        <?= $userDetails->tripulacao["tripulacao"] ?>
        <img src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"] ?>&f=<?= $userDetails->tripulacao["faccao"] ?>">
        <img src="Imagens/Batalha/vs.png"/>
        <img src="Imagens/Batalha/npc.jpg"/>
        <?= $userDetails->combate_pve["nome_npc"] ?>
    <?php elseif ($userDetails->combate_bot) : ?>
        <?= $userDetails->tripulacao["tripulacao"] ?>
        <img src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"] ?>&f=<?= $userDetails->tripulacao["faccao"] ?>">
        <img src="Imagens/Batalha/vs.png"/>
        <img src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->combate_bot["bandeira_inimiga"] ?>&f=<?= $userDetails->combate_bot["faccao_inimiga"] ?>">
        <?= $userDetails->combate_bot["tripulacao_inimiga"] ?>
    <?php endif; ?>
</div>
<style type="text/css">
    <?php include "CSS/combate.css"; ?>
</style>
<script type="text/javascript">
    <?php include "JS/combate.js"; ?>
    <?php if ($userDetails->combate_pve) include "JS/combate_npc.js"; ?>
    <?php if ($userDetails->combate_pvp) include "JS/combate_pvp.js"; ?>
    <?php if ($userDetails->combate_bot) include "JS/combate_bot.js"; ?>
</script>
<div class="panel-body">
    <div id="navio_batalha">
        <?php
        if ($userDetails->combate_pve) include "Scripts/Batalha/batalha_tabuleiro_content.php";
        if ($userDetails->combate_pvp) include "Scripts/Batalha/batalha_tabuleiro_pvp_content.php";
        if ($userDetails->combate_bot) include "Scripts/Batalha/batalha_tabuleiro_bot_content.php";
        ?>
    </div>
</div>

<div id="skills-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cancelaskil()">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Escolha uma habilidade</h4>
            </div>
            <div class="modal-body" id="skills-personagem">
            </div>
        </div>
    </div>
</div>