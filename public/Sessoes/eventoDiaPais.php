<div class="panel-heading">
    Dia dos Pais
</div>

<div class="panel-body">
    <?= ajuda("Evento do dia dos Pais", "Nesse dia dos pais, o Barba Branca está premiando os guerreiros capazes de 
    derrotar seu navio Moby Dick com mapas do tesouro. Mas não se empolgue tão facilmente, esses tesouros são protegidos 
    por criaturas marítimas. Derrote os protetores dos tesouros para ganhar mais recompensas!"); ?>

    <h4>Derrote os protetores do tesouro do Moby Dick para ganhar recompensas</h4>

    <?php $derrotados = $connection->run("SELECT sum(quant) AS total FROM tb_pve WHERE id= ? AND zona = 76",
        "i", $userDetails->tripulacao["id"])->fetch_array()["total"]; ?>

    <h3>Até agora você já derrotou <?= $derrotados ?> Protetores do tesouro</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_pais"), $derrotados, "Derrote", "Protetores do Tesouro", "Eventos/pais.php", "tb_evento_amizade_recompensa"); ?>
</div>