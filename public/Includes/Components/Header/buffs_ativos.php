<?php if (count($userDetails->buffs->buffs_ativos)) : ?>
    <li data-toggle="tooltip" title="Bonus ativos" data-placement="bottom">
        <a href="#" class="noHref" data-content="-" data-container="#tudo" data-toggle="popover" data-placement="bottom"
            data-trigger="focus" data-html="true" data-template='
                        <div class="container info-buff-tripulacao">
                            <?php foreach ($userDetails->buffs->buffs_ativos as $buff) : ?>
                            <?php $expiracao = ($buff["expiracao"] - atual_segundo()) - 1; ?>
                            <?php $horas = floor($expiracao / (60 * 60)); ?>
                            <div class="row">
                                <div class="col-xs-2">
                                    <img src="Imagens/Icones/<?= $buff["icon"] ?>" />
                                </div>
                                <div class="col-xs-10">
                                    <p><small><?= $buff["descricao"] ?></small></p>
                                </div>
                                <div class="col-xs-12">
                                    <p>Expira em <?= $horas == "00" ? "24" : $horas ?> horas e <?= date("i", $expiracao) ?> minutos</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>'>
            <img height="21px" src="Imagens/Icones/bonus.jpg" />
        </a>
    </li>
<?php endif; ?>

