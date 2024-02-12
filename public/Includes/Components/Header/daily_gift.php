<?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_CALENDARIO)) : ?>
    <li id="div_icon_daily_gift" data-toggle="tooltip" title="Presentes e Eventos" data-placement="bottom">
        <a href="#" class="noHref" data-toggle="modal" data-target="#modal-daily-gift">
            <i class="fa fa-calendar-check-o"></i>
            <?php $novos_mini_eventos = $connection->run("SELECT count(*) AS total FROM tb_mini_eventos WHERE inicio > DATE_SUB(NOW(), INTERVAL 5 MINUTE) ")->fetch_array()["total"]; ?>
            <?php if (! $userDetails->tripulacao["presente_diario_obtido"]) : ?>
                <span class="badge">1</span>
            <?php elseif ($novos_mini_eventos) : ?>
                <span class="badge">
                    <?= $novos_mini_eventos ?>
                </span>
            <?php endif; ?>
        </a>
    </li>
<?php endif; ?>

