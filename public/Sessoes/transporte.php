<div class="panel-heading">
    Serviço de Transporte
    <?= ajuda("Serviço de Transporte", "Levamos você para onde quiser bem rapidinho!") ?>
</div>

<div class="panel-body">

    <div class="row" style="margin: 0;">
        <div class="list-group-item col-xs-12 col-md-4">
            <div class="row" style="margin: 0;">
                <?php $ilha_retorno = \Regras\Ilhas::get_ilha_by_coord($userDetails->tripulacao["res_x"], $userDetails->tripulacao["res_y"]); ?>
                <div class="col-xs-6 col-md-6">
                    <h4>Viajar para ilha de retorno:</h4>
                    <p>Seu retorno está salvo em
                        <?= nome_ilha($ilha_retorno["ilha"]) ?>
                        (
                        <?= nome_mar($ilha_retorno["mar"]) ?>)
                    </p>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button href="Vip/transporte_retorno.php" class="link_confirm btn btn-info"
                        data-question="Deseja retornar para sua ilha de retorno salva?"
                        <?= $userDetails->tripulacao["berries"] >= PRECO_BERRIES_TRANSPORTE_ILHA_RETORNO && $userDetails->has_ilha_envolta_me ? "" : "disabled" ?>>
                        <img src="Imagens/Icones/Berries.png" />
                        <?= mascara_berries(PRECO_BERRIES_TRANSPORTE_ILHA_RETORNO) ?>
                        Viajar
                    </button>
                    <button href="Vip/transporte_retorno_gold.php" class="link_confirm btn btn-success"
                        data-question="Deseja retornar para sua ilha de retorno salva?" <?= $userDetails->conta["gold"] >= PRECO_GOLD_VIAJA_RETORNO ? "" : "disabled" ?>>
                        <?= PRECO_GOLD_VIAJA_RETORNO ?> <img src="Imagens/Icones/Gold.png" /> Viajar
                    </button>

                </div>
            </div>
        </div>
        <?php $result = $userDetails->capitao["lvl"] >= 45
            ? array_filter(\Utils\Data::load("mundo")["ilhas"], function ($ilha) {
                        return ! isset($ilha["restrita"]);
                    })
            : array_filter(\Utils\Data::load("mundo")["ilhas"], function ($ilha) use ($userDetails) {
                        return $userDetails->ilha["mar"] == $ilha["mar"] && ! isset($ilha["restrita"]);
                    }); ?>

        <?php foreach ($result as $ilha) : ?>
            <?php if ($userDetails->ilha["ilha"] != $ilha["ilha"]) : ?>
                <div class="list-group-item col-xs-12 col-md-4">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <h4>
                                <?= nome_ilha($ilha["ilha"]); ?><br />
                                (
                                <?= nome_mar($ilha["mar"]) ?>)
                            </h4>
                        </div>

                        <div class="col-xs-6 col-md-6">
                            <?php if ($ilha["mar"] == $userDetails->ilha["mar"]
                                && ($ilha["ilha"] == 1
                                    || $ilha["ilha"] == 8
                                    || $ilha["ilha"] == 15
                                    || $ilha["ilha"] == 22
                                    || $ilha["ilha"] == 29
                                    || $ilha["ilha"] == 44)
                            ) : ?>
                                <button href="Vip/transporte_berries.php?destino=<?= $ilha["ilha"]; ?>"
                                    class="link_confirm btn btn-info" data-question="Deseja viajar para <?= $ilha["nome"]; ?>?"
                                    <?= $userDetails->tripulacao["berries"] >= 1000000 && $userDetails->has_ilha_envolta_me ? "" : "disabled" ?>>
                                    1.000.000 <img src="Imagens/Icones/Berries.png" /> Viajar
                                </button>
                                </p>
                            <?php endif; ?>
                            <?php if ($ilha["ilha_dono"] == $userDetails->tripulacao["id"]) : ?>
                                <button href="Vip/transporte_minha_ilha.php?destino=<?= $ilha["ilha"]; ?>"
                                    class="link_confirm btn btn-info" data-question="Deseja viajar para <?= $ilha["nome"]; ?>?"
                                    <?= $userDetails->tripulacao["berries"] >= 3000000 && $userDetails->has_ilha_envolta_me ? "" : "disabled" ?>>
                                    3.000.000 <img src="Imagens/Icones/Berries.png" /> Viajar
                                </button>
                                </p>
                            <?php endif; ?>
                            <?php $distancia = sqrt(pow($ilha["x"] - $userDetails->tripulacao["x"], 2) + pow($ilha["y"] - $userDetails->tripulacao["y"], 2)); ?>
                            <?php $preco = round(($distancia * 10) / 20); ?>
                            <?php if ($preco < 20)
                                $preco = 20; ?>
                            <button href="Vip/transporte_gold.php?tipo=gold&destino=<?= $ilha["ilha"]; ?>"
                                class="link_confirm btn btn-success" data-question="Deseja viajar para <?= $ilha["nome"]; ?>?"
                                <?= $userDetails->conta["gold"] >= $preco ? "" : "disabled" ?>>
                                <?= $preco ?> <img src="Imagens/Icones/Gold.png" /> Viajar
                            </button>
                            <?php $preco = ceil($preco * 1.5); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <ul class="list-group" style="display: none;">
        <li class="list-group-item">
            <div class="row">
                <?php $ilha_retorno = \Regras\Ilhas::get_ilha_by_coord($userDetails->tripulacao["res_x"], $userDetails->tripulacao["res_y"]); ?>
                <div class="col-md-6">
                    <h4>Viajar para ilha de retorno:</h4>
                    <p>Seu retorno está salvo em
                        <?= $ilha_retorno["nome"] ?>
                        (
                        <?= nome_mar($ilha_retorno["mar"]) ?>)
                    </p>
                </div>
                <div class="col-md-6">
                    <button href="Vip/transporte_retorno.php" class="link_confirm btn btn-info"
                        data-question="Deseja retornar para sua ilha de retorno salva?"
                        <?= $userDetails->tripulacao["berries"] >= PRECO_BERRIES_TRANSPORTE_ILHA_RETORNO && $userDetails->has_ilha_envolta_me ? "" : "disabled" ?>>
                        <img src="Imagens/Icones/Berries.png" />
                        <?= mascara_berries(PRECO_BERRIES_TRANSPORTE_ILHA_RETORNO) ?>
                        Viajar
                    </button>
                    <button href="Vip/transporte_retorno_gold.php" class="link_confirm btn btn-success"
                        data-question="Deseja retornar para sua ilha de retorno salva?" <?= $userDetails->conta["gold"] >= PRECO_GOLD_VIAJA_RETORNO ? "" : "disabled" ?>>
                        <?= PRECO_GOLD_VIAJA_RETORNO ?> <img src="Imagens/Icones/Gold.png" /> Viajar
                    </button>

                </div>
            </div>
        </li>
        <?php foreach ($result as $ilha) : ?>
            <?php if ($userDetails->ilha["ilha"] != $ilha["ilha"] && $ilha["ilha"] != 102) : ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <? print_r($ilha); ?>
                            <h4>
                                <?= nome_ilha($ilha["ilha"]); ?> (
                                <?= nome_mar($ilha["mar"]) ?>)
                            </h4>
                        </div>

                        <div class="col-md-6">
                            <?php if ($ilha["mar"] == $userDetails->ilha["mar"]
                                && ($ilha["ilha"] == 1
                                    || $ilha["ilha"] == 8
                                    || $ilha["ilha"] == 15
                                    || $ilha["ilha"] == 22
                                    || $ilha["ilha"] == 29
                                    || $ilha["ilha"] == 44)
                            ) : ?>
                                <button href="Vip/transporte_berries.php?destino=<?= $ilha["ilha"]; ?>"
                                    class="link_confirm btn btn-info" data-question="Deseja viajar para <?= $ilha["nome"]; ?>?"
                                    <?= $userDetails->tripulacao["berries"] >= 1000000 && $userDetails->has_ilha_envolta_me ? "" : "disabled" ?>>
                                    1.000.000 <img src="Imagens/Icones/Berries.png" /> Viajar
                                </button>
                                </p>
                            <?php endif; ?>
                            <?php if ($ilha["ilha_dono"] == $userDetails->tripulacao["id"]) : ?>
                                <button href="Vip/transporte_minha_ilha.php?destino=<?= $ilha["ilha"]; ?>"
                                    class="link_confirm btn btn-info" data-question="Deseja viajar para <?= $ilha["nome"]; ?>?"
                                    <?= $userDetails->tripulacao["berries"] >= 3000000 && $userDetails->has_ilha_envolta_me ? "" : "disabled" ?>>
                                    3.000.000 <img src="Imagens/Icones/Berries.png" /> Viajar
                                </button>
                                </p>
                            <?php endif; ?>
                            <?php $distancia = sqrt(pow($ilha["x"] - $userDetails->tripulacao["x"], 2) + pow($ilha["y"] - $userDetails->tripulacao["y"], 2)); ?>
                            <?php $preco = round(($distancia * 10) / 20); ?>
                            <?php if ($preco < 20)
                                $preco = 20; ?>
                            <button href="Vip/transporte_gold.php?tipo=gold&destino=<?= $ilha["ilha"]; ?>"
                                class="link_confirm btn btn-success" data-question="Deseja viajar para <?= $ilha["nome"]; ?>?"
                                <?= $userDetails->conta["gold"] >= $preco ? "" : "disabled" ?>>
                                <?= $preco ?> <img src="Imagens/Icones/Gold.png" /> Viajar
                            </button>
                            <?php $preco = ceil($preco * 1.5); ?>

                        </div>
                    </div>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
