<div class="panel-heading">
    Escola de profissões
</div>

<div class="panel-body">
    <?= ajuda("Escola de profissões", " Aqui sua tripulação aprende profissões para ajudarem no seu navio.<br>
                Cada personagem pode ter apenas 1 profissão, então escolha com cuidado!<br>
                Cada profissão evoliu de uma maneira diferente, combatentes usando habilidades em combate, navegadores
                navegando, etc."); ?>

    <?php $profissoes = $connection->run("SELECT * FROM tb_ilha_profissoes WHERE ilha= ?", "i", $userDetails->ilha["ilha"])->fetch_all_array(); ?>

    <?php
    $profissoes_lvl = array();
    foreach ($profissoes as $prof) {
        $profissoes_lvl[$prof["profissao"]] = $prof["profissao_lvl_max"];
    }
    ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            Profissões ensinadas nessa ilha:
        </div>
        <div class="panel-body">
            <ul class="text-left">
                <?php foreach ($profissoes as $prof): ?>
                    <li><?= nome_prof($prof["profissao"]) ?> até o nível <?= $prof["profissao_lvl_max"] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div>
        <?php render_personagens_pills(); ?>
    </div>

    <div class="tab-content">
        <?php foreach ($userDetails->personagens as $index => $pers): ?>
            <?php render_personagem_panel_top($pers, $index) ?>
            <?php render_personagem_sub_panel_with_img_top($pers); ?>
            <div class="panel-body">
                <?php if (!$pers["profissao"]): ?>
                    <ul class="list-group">
                        <?php foreach ($profissoes as $prof): ?>
                            <li class="list-group-item">
                                <h4><?= nome_prof($prof["profissao"]) ?></h4>
                                <p><?= desc_prof($prof["profissao"]) ?></p>
                                <p>Preço: <img src="Imagens/Icones/Berries.png" height="15px"/> 1.000</p>
                                <?php if ($userDetails->tripulacao["berries"] > 1000) : ?>
                                    <button href="Profissao/profissao_aprender.php?cod=<?= $pers["cod"]; ?>&prof=<?= $prof["profissao"] ?>"
                                            data-question="Deseja aprender essa profissão?"
                                            class="link_confirm btn btn-success">
                                        Aprender
                                    </button>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <h3><?= nome_prof($pers["profissao"]) ?></h3>
                    <h4>Nível: <?= $pers["profissao_lvl"]; ?></h4>
                    <?php if ($pers["profissao_xp"] < $pers["profissao_xp_max"]) : ?>
                        <div class="progress">
                            <div class="progress-bar"
                                 style="width: <?= $pers["profissao_xp"] / $pers["profissao_xp_max"] * 100 ?>%;">
                                <span>EXP:<?= $pers["profissao_xp"] . "/" . $pers["profissao_xp_max"] ?></span>
                            </div>
                        </div>
                    <?php elseif (isset($profissoes_lvl[$pers["profissao"]]) && $pers["profissao_lvl"] < $profissoes_lvl[$pers["profissao"]]) : ?>
                        <p>
                            Preço: <img src="Imagens/Icones/Berries.png"/> <?= $pers["profissao_lvl"] * 2000; ?><br>
                        </p>
                        <button href="link_Profissao/profissao_evoluir.php?cod=<?= $pers["cod"]; ?>"
                                class="link_send btn btn-success">
                            Evoluir
                        </button>
                    <?php else : ?>
                        <p>Essa ilha não tem tecnologia o suficiente para melhorar sua profissão.</p>
                    <?php endif; ?>
                    <?php if ($pers["profissao"] == PROFISSAO_CARTOGRAFO) : ?>
                        <?php $mapa = $connection->run("SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item='2'", "i", $userDetails->tripulacao["id"])->count(); ?>
                        <?php if (!$mapa) : ?>
                            <div>
                                <img src="Imagens/Itens/22.png"/>
                                <img src="Imagens/Icones/Berries.png" height="15px"/> 2.000
                            </div>
                            <button href="link_Profissao/comprar_mapa.php" class="link_send btn btn-primary">
                                Comprar Mapa
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php render_personagem_sub_panel_with_img_bottom(); ?>
            <?php render_personagem_panel_bottom() ?>
        <?php endforeach; ?>
    </div>
</div>