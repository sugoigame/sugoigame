<?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_COLISEU)) : ?>
    <?php $participante = $connection->run(
        "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1",
        "i", array($userDetails->tripulacao["id"])
    ); ?>
    <?php if ($participante->count() && $participante->fetch_array()["na_fila"]) : ?>
        <li id="div_icon_torneio" data-toggle="tooltip" title="Você está a procura de um oponente no Torneio PvP"
            data-placement="bottom">
            <a href="./?ses=torneio" class="link_content">
                <i class="fa fa-bolt"></i>
                <span class="badge"><i class="fa fa-eye"></i></span>
            </a>
        </li>
    <?php endif; ?>
<?php endif; ?>

