<div class="panel-heading">
    Evento da Independência
</div>

<div class="panel-body">
    <?= ajuda("Evento da Independência", "Realize incursões para levar a independência aos reinos do mundo e ganhe recompensas."); ?>

    <h4>Derrote adversários de quaisquer incursões do jogo para ganhar recompensas.</h4>

    <?php $derrotados = $connection->run("SELECT sum(progresso - 1) AS total FROM tb_incursao_progresso WHERE tripulacao_id= ?",
        "i", $userDetails->tripulacao["id"])->fetch_array()["total"]; ?>

    <h3>Até agora você já derrotou <?= $derrotados ?> Adversários de incursões</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_independencia"), $derrotados, "Derrote", "Adversários de Incursões", "Eventos/independencia.php", "tb_evento_amizade_recompensa"); ?>
</div>