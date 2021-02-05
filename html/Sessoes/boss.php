<div class="panel-heading">
    Caça aos grandes chefões dos mares
</div>

<div class="panel-body">

    <?= ajuda("Caça aos grandes chefões dos mares", "O governo mundial detectou uma ameaça de escala global!<br/>
    Trata-se de um chefão dos mares muito poderoso que pode destruir ilhas sozinho! E para combater essa ameaça, 
    um chamado global foi feito, ótimas recompensas estão sendo pagas aos corajosos que ajudarem a combater essas criaturas."); ?>

    <?php $boss = "Girafales"; ?>
    <h3>Chefão identificado: <?= $boss ?></h3>

    <h4>Essa criatura pode ser encontrada nas seguintes coordenadas:</h4>
    <?php $zonas = $connection->run("SELECT x, y FROM tb_mapa_rdm WHERE rdm_id = 91"); ?>
    <?php while ($quadro = $zonas->fetch_array()) : ?>
        <p>
            <?= get_human_location($quadro["x"], $quadro["y"]) ?> - <?= nome_mar(get_mar($quadro["x"], $quadro["y"])) ?>
        </p>
    <?php endwhile; ?>

    <?php $dano = $connection->run("SELECT damage FROM tb_boss_damage WHERE real_boss_id = 10 AND tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"]))->fetch_array(); ?>
    <?php $dano = $dano["damage"] ? $dano["damage"] : 0; ?>
    <h3>Dano causado a <?= $boss ?>: <?= mascara_berries($dano) ?></h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php render_recompensas(DataLoader::load("recompensas_boss"), $dano, "Cause ", " pontos de dano a $boss", "Eventos/chefao.php", "tb_evento_recompensa"); ?>
</div>