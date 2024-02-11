<?php $novos = $connection->run("SELECT SUM(novo) AS total FROM tb_usuario_itens WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"]; ?>
<li id="div_icon_inventario" class="div_icon" data-toggle="tooltip" title="Inventário" data-placement="bottom">
    <a href="#" class="noHref" data-toggle="modal" data-target="#modal-inventario">
        <img height="21px" id="icon_iventario" src="Imagens/Icones/Bau.png" alt="Inventário" />
        <?php if ($novos) : ?>
            <span class="badge">
                <?= $novos ?>
            </span>
        <?php endif; ?>
    </a>
</li>
