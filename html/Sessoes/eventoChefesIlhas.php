<div class="panel-heading">
    Equilibrando os poderes do mundo
</div>

<div class="panel-body">
    <?= ajuda("Equilibrando os poderes do mundo", "Os chefes de algumas ilhas do jogo ficaram vulneráveis e estão cedendo seu poder para os jogadores. Durante o evento é possível derrotar novamente os chefes de algumas ilhas do jogo e colocar um de seus tripulantes para assumir o lugar do chefe na ilha para que outros jogadores o enfrentem. É preciso assumir o posto do chefe da ilha por um certo período para cumprir o evento."); ?>

    <?php $chefe = $connection->run("SELECT * FROM tb_evento_chefes ec INNER JOIN tb_personagens p ON ec.personagem_id = p.cod WHERE ec.ilha = ?",
        "i", array($userDetails->ilha["ilha"])); ?>
    <?php if ($chefe->count()): ?>
        <?php $chefe = $chefe->fetch_array(); ?>
        <h3><?= $chefe["nome"] ?> é o Chefe Especial dessa ilha</h3>
    <?php elseif ($userDetails->ilha["ilha"]): ?>
        <?php $chefes = DataLoader::load("chefes_ilha"); ?>
        <?php $chefe = $chefes[$userDetails->ilha["ilha"]]; ?>
        <?php $rdms = DataLoader::load("rdm"); ?>
        <?php $rdm = $rdms[$chefe["rdm"]]; ?>
        <h3><?= $rdm["nome"] ?> é o Chefe Especial dessa ilha</h3>
    <?php endif; ?>

    <h4>Derrote os Chefes Especiais para receber as recompensas</h4>

    <button class="btn btn-success link_send" href="link_Eventos/atacar_chefe_especial.php"
        <?= $userDetails->ilha["ilha"] ? "" : "disabled" ?>>
        Enfrentar o Chefe Especial da ilha
    </button>

    <p>De hora em hora o chefe original da ilha recupera seu lugar do jogador que o tomou</p>

    <?php $derrotados = $connection->run("SELECT sum(quant) AS total FROM tb_pve WHERE id= ? AND zona = 9998",
        "i", $userDetails->tripulacao["id"])->fetch_array()["total"]; ?>

    <h3>Até agora você já derrotou <?= $derrotados ?> Chefes Especiais</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_chefes_ilhas"), $derrotados, "Derrote", "Chefes Especiais", "Eventos/chefes_ilhas.php", "tb_evento_recompensa"); ?>

</div>