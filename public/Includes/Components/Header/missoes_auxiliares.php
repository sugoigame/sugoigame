<li id="div_icon_progress" data-toggle="tooltip" title="Missões auxiliares" data-placement="bottom">
    <?php $progress_info = $userDetails->get_progress_info(); ?>
    <?php if ($progress_info) : ?>
        <?php $progress_reward = $userDetails->get_progress_reward(); ?>
        <?php $finished = $userDetails->is_progress_finished(); ?>
        <a href="#"
            class="noHref <?= $finished ? 'user-progress-finished' : '' ?> user-progress-<?= $userDetails->tripulacao["progress"] ?>"
            data-title="<?= $progress_info["title"] ?>"
            data-description="<?= $progress_info["description"] ?><?= $finished ? "<br/><br/>" . $progress_info["finished"] : "" ?>"
            data-xp="<?= $progress_reward["xp"] ?>" data-berries="<?= $progress_reward["berries"] ?>"
            data-finished="<?= $finished ? "true" : "false" ?>">
            <img height="21px" src="Imagens/Icones/missao-<?= $finished ? "2" : "0" ?>.png">
        </a>
    <?php endif; ?>
</li>
