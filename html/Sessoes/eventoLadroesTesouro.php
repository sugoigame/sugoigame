<div class="panel-heading">
    Derrote os Ladrões de Tesouro
</div>

<div class="panel-body">
    <?= ajuda("Derrote os Ladrões de Tesouro", "Algumas tripulações piratas roubaram tesouros preciosos e existem belas 
    recompensas para quem os trouxer de volta. É preciso encontra-las e derrota-las para cumprir o evento."); ?>

    <h4>Derrote os Ladrões de Tesouros para receber as recompensas</h4>

    <?php $recompensas = DataLoader::load("recompensas_ladroes_tesouro"); ?>
    <?php $derrotados = $connection->run("SELECT sum(quant) AS total FROM tb_pve WHERE id= ? AND zona = 73",
        "i", $userDetails->tripulacao["id"])->fetch_array()["total"]; ?>

    <h3>Até agora você já derrotou <?= $derrotados ?> Ladrões de Tesouros</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_ladroes_tesouro"), $derrotados, "Derrote", "Ladrões de Tesouro", "Eventos/ladroes_tesouro.php", "tb_evento_recompensa"); ?>
</div>