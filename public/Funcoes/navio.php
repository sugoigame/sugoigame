<?php function render_navio_hp_bar() { ?>
    <?php global $userDetails; ?>
    <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar"
             style="width: <?= $userDetails->navio["hp_teste"] / $userDetails->navio["hp_max"] * 100 ?>%;">
            <span>Navio:<?= $userDetails->navio["hp_teste"] . "/" . $userDetails->navio["hp_max"] ?></span>
        </div>
    </div>
<?php } ?>
<?php function render_navio_xp_bar() { ?>
    <?php global $userDetails; ?>
    <div class="progress">
        <div class="progress-bar progress-bar-default" role="progressbar"
             style="width: <?= $userDetails->navio["xp"] / $userDetails->navio["xp_max"] * 100 ?>%;">
            <span>EXP:<?= $userDetails->navio["xp"] . "/" . $userDetails->navio["xp_max"] ?></span>
        </div>
    </div>
<?php } ?>
<?php function render_navio_icon() { ?>
    <?php global $userDetails; ?>
    <?php for ($d = 0; $d < 8; $d++): ?>
        <img src="Imagens/Bandeiras/navio_skin.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>&s=<?= $userDetails->tripulacao["skin_navio"] ?>&d=<?= $d ?>"/>
    <?php endfor; ?>
<?php } ?>
<?php function render_navio_skin($bandeira, $faccao, $skin) { ?>
    <?php for ($d = 0; $d < 8; $d++): ?>
        <img src="Imagens/Bandeiras/navio_skin.php?cod=<?= $bandeira ?>&f=<?= $faccao ?>&s=<?= $skin ?>&d=<?= $d ?>"/>
    <?php endfor; ?>
    <img src="Imagens/Bandeiras/Navios/<?= $faccao ?>/<?= $skin ?>/batalha.png" width="100px"/>
<?php } ?>