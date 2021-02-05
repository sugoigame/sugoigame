<div class="panel-heading">
    Derrote as tripulações piratas
</div>

<div class="panel-body">
    <?= ajuda("Derrote as tripulações piratas", "Nos últimos dias a quantidade de pessoas que resolveram se tornar piratas aumentou
    drasticamente.<br/> Com o objetivo de manter as coisas sob controle o Governo Mundial está dando recompensas pelas cabeças desses
    recém chegados."); ?>

    <h4>Derrote as tripulações piratas para conquistar as recompensas</h4>

    <?php $derrotados = $connection->run("SELECT sum(quant) AS total FROM tb_pve WHERE id= ? AND zona >=15 AND zona <=21",
        "i", $userDetails->tripulacao["id"])->fetch_array()["total"]; ?>

    <h3>Até agora você já derrotou <?= $derrotados ?> Tripulações Piratas</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_piratas"), $derrotados, "Derrote", "Tripulações Piratas", "Eventos/piratas.php", "tb_evento_recompensa"); ?>
</div>