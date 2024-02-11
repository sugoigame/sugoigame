<li id="div_icon_denden" class="div_icon" data-toggle="tooltip" title="Mensagens" data-placement="bottom">
    <?php $lido = has_mensagem(); ?>
    <a href="#" class="noHref" data-toggle="modal" data-target="#modal-mensagens">
        <img height="21px" id="denden_mushi" src="Imagens/Icones/Denden_<?= $lido; ?>.png" alt="Den Den Mushi" />
        <?php if ($lido == 0) : ?>
            <script type="text/javascript">
                n_puru(<?= $userDetails->in_combate ? 'true' : 'false' ?>);
            </script>
        <?php endif; ?>
    </a>
</li>
