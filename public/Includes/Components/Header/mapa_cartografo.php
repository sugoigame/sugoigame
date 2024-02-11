<?php if (has_mapa()) : ?>
    <li id="div_icon_cartografo" data-toggle="tooltip" title="Mapa Mundi" data-placement="bottom">
        <a href="#" class="noHref" data-toggle="modal" data-target="#modal-cartografo">
            <img height="21px" src="Imagens/Icones/Mapa.png" />
        </a>
    </li>
<?php else : ?>
    <li data-toggle="tooltip" title="Mapa Mundi" data-placement="bottom">
        <a href="#" class="noHref" data-toggle="modal" data-target="#modal-no-cartografo">
            <img height="21px" src="Imagens/Icones/Mapa.png" />
        </a>
    </li>
<?php endif; ?>

