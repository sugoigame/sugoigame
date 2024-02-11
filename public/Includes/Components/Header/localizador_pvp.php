<li id="div_icon_coliseu" data-toggle="tooltip" title="Localizador PvP<?= $userDetails->fila_coliseu
    ? ($userDetails->fila_coliseu["pausado"]
        ? " - O Localizador foi pausado enquanto você executa uma ação importante."
        : ($userDetails->fila_coliseu["desafio"]
            ? " - Adversário encontrado!"
            : " - Você está procurando um adversário"))
    : (is_coliseu_aberto()
        ? " - O Coliseu está aberto!"
        : ""); ?>" data-placement="bottom">
    <a href="./?ses=<?= is_coliseu_aberto() ? "coliseu" : "localizadorCasual"; ?>" class="link_content">
        <i class="glyphicon glyphicon-fire fa-fw"></i>
        <?php if ($userDetails->fila_coliseu) : ?>
            <?php if ($userDetails->fila_coliseu["pausado"]) : ?>
                <span class="badge badge-alert"><i class="fa fa-pause"></i></span>
            <?php elseif ($userDetails->fila_coliseu["desafio"]) : ?>
                <span class="badge badge-alert"><i class="fa fa-fire"></i></span>
            <?php else : ?>
                <span class="badge"><i class="fa fa-eye"></i></span>
            <?php endif; ?>
        <?php elseif (is_coliseu_aberto()) : ?>
            <span class="badge"><i class="fa fa-bolt"></i></span>
        <?php endif; ?>
    </a>
</li>
