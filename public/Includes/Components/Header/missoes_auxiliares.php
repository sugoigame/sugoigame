<div id="missao-auxiliar">
    <?php $progress_info = $userDetails->get_progress_info(); ?>
    <?php if ($progress_info) : ?>
        <?php $progress_reward = $progress_info["rewards"]; ?>
        <?php $finished = $userDetails->is_progress_finished(); ?>
        <a href="<?= $finished ? 'link_Missoes/finaliza_user_progress.php' : './?ses=' . $progress_info["link"] ?>"
            class="<?= $finished ? 'link_send user-progress-finished' : 'link_content' ?> user-progress-<?= $userDetails->tripulacao["progress"] ?>">
            <div class="media-body">
                <div>
                    <strong>Objetivo:</strong>
                    <img height="15rem" class="mr" src="Imagens/Icones/missao-<?= $finished ? "2" : "0" ?>.png">
                </div>
                <div>
                    <?= $progress_info["goal"]; ?>
                </div>
                <div>
                    <?php if ($progress_reward["xp"]) : ?>
                        <div>
                        </div>
                    <?php endif; ?>
                    <?php if ($progress_reward["berries"]) : ?>
                        <div>
                            <img height="15px" src="Imagens/Icones/Berries.png" />
                            <span id="span_berries">
                                <?= mascara_numeros_grandes($progress_reward["berries"]); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php if ($progress_reward["dobroes"]) : ?>
                        <div>
                            <img height="15px" src="Imagens/Icones/Dobrao.png" />
                            <span id="span_gold">
                                <?= mascara_numeros_grandes($progress_reward["dobroes"]); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    <?php endif; ?>
</div>
