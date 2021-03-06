<?php $termo_correto = ($usuario["faccao"] == 0) ? "Frota" : "Aliança"; ?>
<div class="panel-heading">
    Missões de <?= $termo_correto ?>
</div>

<div class="panel-body">
    <?= ajuda("Missões de Frotas / Alianças", "Aqui você vê os status das missões que sua Aliança/Frota está fazendo.") ?>

    <?php $result = $connection->run("SELECT * FROM tb_alianca_missoes WHERE cod_alianca = ?", "i", $userDetails->ally["cod_alianca"]); ?>

    <?php if (!$result->count()) : ?>
        <ul class="list-group">
            <li class="list-group-item">
                <h4>Caça 1</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>,
                    vocês devem derrotar 50 criaturas marítimas.
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 1
                                <?php if ($userDetails->ally["lvl"] >= 1): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>Bonus de 20% de experiência para cada criaturas marítima derrotada.</li>
                            <li>100 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>10 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 1 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_iniciar.php?quant=1' class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Caça 2</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>,
                    vocês devem derrotar 100 criaturas marítimas.
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 3
                                <?php if ($userDetails->ally["lvl"] >= 3): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>Bonus de 20% de experiência para cada criaturas marítima derrotada.</li>
                            <li>220 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>22 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 3 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_iniciar.php?quant=2' class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Caça 3</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>,
                    vocês devem derrotar 200 criaturas marítimas.
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 5
                                <?php if ($userDetails->ally["lvl"] >= 5): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>Bonus de 20% de experiência para cada criaturas marítima derrotada.</li>
                            <li>450 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>45 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 5 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_iniciar.php?quant=3' class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Ataque Fígaro</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>, vocês devem causar 1.000.000 de pontos de dano a Fígaro
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 1
                                <?php if ($userDetails->ally["lvl"] >= 1): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>
                                <img src="Imagens/Icones/Berries.png"/> 30.000.000 depositados no banco
                                da <?= $termo_correto ?>
                            </li>
                            <li>450 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>45 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 1 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_boss_iniciar.php?boss=1'
                            class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Ataque a Raposa Celeste</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>, vocês devem causar 1.000.000 de pontos de dano a Raposa Celeste
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 1
                                <?php if ($userDetails->ally["lvl"] >= 1): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>
                                <img src="Imagens/Icones/Berries.png"/> 30.000.000 depositados no banco
                                da <?= $termo_correto ?>
                            </li>
                            <li>450 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>45 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 1 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_boss_iniciar.php?boss=2'
                            class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Ataque Popóta</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>, vocês devem causar 1.000.000 de pontos de dano a Popóta
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 1
                                <?php if ($userDetails->ally["lvl"] >= 1): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>
                                <img src="Imagens/Icones/Berries.png"/> 30.000.000 depositados no banco
                                da <?= $termo_correto ?>
                            </li>
                            <li>450 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>45 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 1 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_boss_iniciar.php?boss=3'
                            class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Ataque Espetinho</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>, vocês devem causar 1.000.000 de pontos de dano a Espetinho
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 1
                                <?php if ($userDetails->ally["lvl"] >= 1): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>
                                <img src="Imagens/Icones/Berries.png"/> 30.000.000 depositados no banco
                                da <?= $termo_correto ?>
                            </li>
                            <li>450 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>45 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 1 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_boss_iniciar.php?boss=4'
                            class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
            <li class="list-group-item">
                <h4>Ataque Berroso</h4>
                <p>
                    Junto com sua <?= $termo_correto ?>, vocês devem causar 1.000.000 de pontos de dano a Berroso
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Requisitos:</h5>
                        <ul class="text-left">
                            <li>
                                <?= $termo_correto ?> nível 1
                                <?php if ($userDetails->ally["lvl"] >= 1): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Recompensas:</h5>
                        <ul class="text-left">
                            <li>
                                <img src="Imagens/Icones/Berries.png"/> 30.000.000 depositados no banco
                                da <?= $termo_correto ?>
                            </li>
                            <li>450 pontos de experiência para <?= $termo_correto ?>.</li>
                            <li>45 pontos de Reputação para <?= $termo_correto ?>.</li>
                        </ul>
                    </div>
                </div>
                <?php if ($userDetails->ally["lvl"] >= 1 AND can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
                    <button href='link_Alianca/alianca_missao_boss_iniciar.php?boss=5'
                            class="link_send btn btn-success">
                        Iniciar
                    </button>
                <?php endif; ?>
            </li>
        </ul>

    <?php else: ?>
        <?php $missao = $result->fetch_array(); ?>
        <h4>Sua <?= $termo_correto ?> está sob missão</h4>

        <?php if ($missao["boss_id"]): ?>
            <?php function get_boss($boss_id) {
                $rdms = DataLoader::load("rdm");
                foreach ($rdms as $rdm) {
                    if (isset($rdm["boss"]) && $rdm["boss"] == $boss_id) {
                        return $rdm;
                    }
                }
                return null;
            } ?>
            <?php $boss = get_boss($missao["boss_id"]); ?>
            <?php $locais = $connection->run("SELECT x, y FROM tb_mapa_rdm WHERE rdm_id = ?", "i", array($boss["id"]))->fetch_all_array(); ?>
            <h4>A <?= $boss["nome"] ?> pode ser encontrada nas seguintes coordenadas:</h4>
            <ul>
                <?php foreach ($locais as $local): ?>
                    <li><?= get_human_location($local["x"], $local["y"]) ?>
                        , <?= nome_mar(get_mar($local["x"], $local["y"])) ?></li>
                <?php endforeach; ?>
            </ul>
            <p>A vida de <?= $boss["nome"] ?> é restaurada uma vez por dia em um horário específico, descubra o horário
                para ser mais efetivo em sua investida</p>
            <h4>Dano causado a <?= $boss["nome"] ?>:</h4>
        <?php else: ?>
            <h5>Reis dos mares derrotados:</h5>
        <?php endif; ?>
        <div class="progress">
            <div class="progress-bar progress-bar-info" role="progressbar"
                 style="width: <?= $missao["quant"] / $missao["fim"] * 100 ?>%;">
                <span><?= $missao["quant"] . "/" . $missao["fim"] ?></span>
            </div>
        </div>

        <?php if (can_fin_missao($userDetails->ally, $userDetails->ally["autoridade"]) AND $missao["quant"] >= $missao["fim"]) : ?>
            <button href='link_Alianca/alianca_missao_finalizar.php' class="btn btn-success link_send">
                Finalizar
            </button>
        <?php elseif (can_fin_missao($userDetails->ally, $userDetails->ally["autoridade"])) : ?>
            <button href='link_Alianca/alianca_missao_cancelar.php' class="link_send btn btn-danger">
                Cancelar
            </button>
        <?php endif; ?>
    <?php endif; ?>
</div>