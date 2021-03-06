<?php function render_karma_bars() { ?>
    <?php global $userDetails; ?>
    <?php $porc_karma_bom = $userDetails->tripulacao["karma_bom"] / 1000 * 0.5; ?>
    <?php $porc_karma_mau = $userDetails->tripulacao["karma_mau"] / 1000 * 0.5; ?>
    <div class="progress">
        <div class="progress-bar progress-bar-info progress-bar-striped"
             style="width: <?= (0.5 + $porc_karma_bom - $porc_karma_mau) * 100 ?>%">
            <span>Karma Bom: <?= $userDetails->tripulacao["karma_bom"] ?></span>
        </div>
        <div class="progress-bar progress-bar-danger progress-bar-striped"
             style="width: <?= (0.5 + $porc_karma_mau - $porc_karma_bom) * 100 ?>%">
            <span>Karma Mau: <?= $userDetails->tripulacao["karma_mau"]; ?></span>
        </div>
    </div>
<?php } ?>