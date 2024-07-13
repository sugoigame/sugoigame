<style type="text/css">
    <?php include "CSS/combate.css"; ?>
</style>
<script type="text/javascript">
    <?php include "JS/easystar-0.4.4.min.js"; ?>
    <?php include "JS/combate.js"; ?>
    <?php if ($userDetails->combate_pve)
        include "JS/combate_npc.js"; ?>
    <?php if ($userDetails->combate_pvp)
        include "JS/combate_pvp.js"; ?>
    <?php if ($userDetails->combate_bot)
        include "JS/combate_bot.js"; ?>
</script>
<div id="batalha-content">
    <?php
    if ($userDetails->combate_pve)
        include "Scripts/Batalha/batalha_tabuleiro_content.php";
    if ($userDetails->combate_pvp)
        include "Scripts/Batalha/batalha_tabuleiro_pvp_content.php";
    if ($userDetails->combate_bot)
        include "Scripts/Batalha/batalha_tabuleiro_bot_content.php";
    ?>
</div>

<div id="skills-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xlg" role="document">
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
