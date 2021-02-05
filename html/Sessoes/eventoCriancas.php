<div class="panel-heading">
    Semana das Crianças
</div>

<div class="panel-body">
    <?= ajuda("Ensine a arte do combate às crianças que são o futuro da nação.", "Durante essa semana as crianças do 
    mundo todo estão de folga, e alguns orfanatos deixam que forasteiros as levem para se divertir e as ensinem algumas 
    coisas importantes. Essa é a sua chance de ensinar suas técnias para a nova geração!"); ?>

    <h4>Leve as crianças para participar de partidas PvP para ganhar recompensas</h4>

    <p>
        Utilize o Chamado Infantil para que uma criança participe das suas batalhas PvP.
    </p>
    <button class="btn btn-success link_send" href="link_Eventos/criancas_chamado.php">
        Ativar o Chamado Infantil
    </button>

    <?php $derrotados = $userDetails->tripulacao["batalhas_criancas"]; ?>

    <h3>Até agora você já participou de <?= $derrotados ?> Batalhas PvP com Crianças</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_criancas"), $derrotados, "Participe de", "Batalhas PvP com crianças", "Eventos/criancas.php", "tb_evento_amizade_recompensa"); ?>
</div>