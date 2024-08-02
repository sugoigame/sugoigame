<?php if ($userDetails->tripulacao) : ?>
    <a id="mastro-bandeira" class="link_content" href="./?ses=bandeira">
        <img class="mastro" src="Imagens/<?= $userDetails->tripulacao["faccao"] ?>/mastro.png" alt="mastro" />
        <img class="bandeira"
            src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>"
            alt="bandeira">
    </a>
<?php endif; ?>

