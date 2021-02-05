<?php
function is_buff_ativo($bonus_ativos, $buff_id) {
    foreach ($bonus_ativos as $bonus) {
        if ($bonus["buff_id"] == $buff_id) {
            return true;
        }
    }

    return false;
}

?>

<div class="panel-heading">
    Domínio da ilha
</div>

<div class="panel-body">
    <?= ajuda("Domínio da ilha",
        "As ilhas podem ser disputadas pelos jogadores. 
        Consulte o calendário para saber a data e horário em que acontece a disputa por cada ilha. 
        O jogador que governa uma ilha pode ativar bônus para os jogadores próximos à ilha e negociar recursos com os governantes de outras ilhas do jogo. 
        Esses recursos podem ser saqueados e vendidos para ganhar recompensas. 
        Fique de olho na Gaivota Mensageira para saber quando um navio marcador iniciou sua jornada e assim poder saquea-lo.") ?>

    <?php $disputa = $connection->run("SELECT * FROM tb_ilha_disputa d LEFT JOIN tb_usuarios u ON d.vencedor_id = u.id WHERE d.ilha = ?",
        "i", array($userDetails->ilha["ilha"])); ?>

    <?php if ($disputa->count()): ?>
        <?php $disputa = $disputa->fetch_array(); ?>
        <h3>Essa ilha está sob disputa!</h3>

        <?php if ($disputa["vencedor_id"]): ?>
            <h3><?= $disputa["tripulacao"] ?> conseguiu o direito de enfrentar o governante da ilha!</h3>

            <p>
                Se nenhum dos jogadores aparecer para o combate dentro do tempo de tolerância, essa ilha ficará sem
                governante.
            </p>

            <p>
                Tempo de tolerância restante: <?= transforma_tempo_min($disputa["fim"] - atual_segundo()) ?>
            </p>

            <?php if ($disputa["fim"] < atual_segundo()) {
                $connection->run("CALL finaliza_disputa_ilha(" . $userDetails->ilha["ilha"] . ")");
            } ?>

            <?php if ($disputa["vencedor_id"] == $userDetails->tripulacao["id"]): ?>
                <?php if ($disputa["vencedor_pronto"]): ?>
                    <p>Aguardando o governante da ilha se preparar para o combate...</p>
                <?php else: ?>
                    <button class="btn btn-success link_confirm"
                            data-question="Você irá enfrentar o governante agora! Você está pronto?"
                            href="Ilhas/desafio_pronto.php">
                        Estou pronto para enfrentar o governante da ilha!
                    </button>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]): ?>
                <?php if ($disputa["dono_pronto"]): ?>
                    <p>Aguardando o desafiante se preparar para o combate...</p>
                <?php else: ?>
                    <button class="btn btn-success link_confirm"
                            data-question="Você irá enfrentar seu desafiante agora! Você está pronto?"
                            href="Ilhas/desafio_pronto.php">
                        Estou pronto para enfrentar o desafiante!
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <p>
                O primeiro jogador que conseguir concluir a incursão de proteção tem o direito de enfrentar o
                governante em uma batalha pelo controle da ilha.
            </p>

            <?php if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]): ?>
                <p>Aguarde até que algum jogador consiga derrotar a sua incursão de proteção para enfrenta-lo.</p>
            <?php else: ?>
                <?php
                $result = $connection->run("SELECT * FROM tb_ilha_disputa_progresso WHERE ilha = ? AND tripulacao_id = ?",
                    "ii", array($userDetails->ilha["ilha"], $userDetails->tripulacao["id"]));
                $progresso = $result->count() ? $result->fetch_array() : array("progresso" => 0);
                ?>

                <div class="list-group">
                    <?php for ($sequencia = 0; $sequencia < 3; $sequencia++): ?>
                        <div class="list-group-item">
                            <h4><?= $sequencia + 1 ?>º Adversário</h4>
                            <?php if ($progresso["progresso"] == $sequencia): ?>
                                <button class="btn btn-success link_send" href="link_Ilhas/atacar_incursao.php">
                                    Atacar
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $dono = $userDetails->ilha["ilha_dono"]
            ? $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", array($userDetails->ilha["ilha_dono"]))->fetch_array()
            : array("tripulacao" => "Governo Mundial", "faccao" => FACCAO_MARINHA, "bandeira" => "030128044241030118456317010115204020", "karma_bom" => 1, "karma_mau" => 0) ?>
        <h3>
            <?= nome_ilha($userDetails->ilha["ilha"]) ?>
        </h3>
        <h4>
            <img src="Imagens/Bandeiras/img.php?cod=<?= $dono["bandeira"] ?>&f=<?= $dono["faccao"] ?>">
            Essa ilha
            está <?= $dono["karma_bom"] ? "protegida pelo" : "sob o domínio de" ?> <?= $dono["tripulacao"] ?>
        </h4>

        <?php if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]): ?>
            <p>
                Você poderá usar o serviço de transporte pagando por Berries para se teleportar para as ilhas que você
                controla.
            </p>
        <?php endif; ?>

        <?php $bonus_disponiveis = DataLoader::load("bonus_ilha"); ?>
        <?php $buffs = DataLoader::load("buffs_tripulacao"); ?>
        <?php $bonus_ativos = $connection->run("SELECT * FROM tb_ilha_bonus_ativo WHERE ilha = ?",
            "i", array($userDetails->ilha["ilha"]))->fetch_all_array(); ?>

        <div>
            <ul class="nav nav-pills nav-justified">
                <li class="<?= !isset($_GET["tab"]) || $_GET["tab"] == "bonusAtivos" ? "active" : "" ?>">
                    <a href="./?ses=politicaIlha&tab=bonusAtivos" class="link_content">
                        Bônus Ativos
                    </a>
                </li>
                <li class="<?= isset($_GET["tab"]) && $_GET["tab"] == "bonusMundial" ? "active" : "" ?>">
                    <a href="./?ses=politicaIlha&tab=bonusMundial" class="link_content">
                        Situação Mundial
                    </a>
                </li>
                <li class="<?= isset($_GET["tab"]) && $_GET["tab"] == "cargasRoubadas" ? "active" : "" ?>">
                    <a href="./?ses=politicaIlha&tab=cargasRoubadas" class="link_content">
                        Mercado de Cargas Roubadas
                    </a>
                </li>
            </ul>
            <?php if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]): ?>
                <ul class="nav nav-pills nav-justified">
                    <li class="<?= isset($_GET["tab"]) && $_GET["tab"] == "recursosIlha" ? "active" : "" ?>">
                        <a href="./?ses=politicaIlha&tab=recursosIlha" class="link_content">
                            Recursos e Bônus disponíveis
                        </a>
                    </li>
                    <li class="<?= isset($_GET["tab"]) && $_GET["tab"] == "negociarRecursos" ? "active" : "" ?>">
                        <a href="./?ses=politicaIlha&tab=negociarRecursos" class="link_content">
                            Negociar Recursos
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
        <div class="tab-content">
            <?php if (!isset($_GET["tab"]) || $_GET["tab"] == "bonusAtivos"): ?>
                <div class="tab-pane active">
                    <?php if (count($bonus_ativos)): ?>
                        <div class="list-group">
                            <?php foreach ($bonus_ativos as $bonus): ?>
                                <?php $buff = $buffs[$bonus["buff_id"]]; ?>
                                <div class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img src="Imagens/Icones/<?= $buff["icon"] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <?= $buff["descricao"] ?>
                                        </div>
                                        <div class="col-md-4">
                                            Tempo
                                            restante: <?= transforma_tempo_min($bonus["expiracao"] - atual_segundo()) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>
                            Não há nenhum bônus ativo nessa ilha.
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET["tab"]) && $_GET["tab"] == "bonusMundial"): ?>
                <div class="tab-pane active">
                    <?php $ilhas = $connection->run(
                        "SELECT * FROM tb_mapa m INNER JOIN tb_usuarios u ON m.ilha_dono = u.id WHERE m.ilha <> 0 ORDER BY ilha"
                    )->fetch_all_array(); ?>
                    <?php $todos_bonus_ativos_db = $connection->run("SELECT * FROM tb_ilha_bonus_ativo")->fetch_all_array() ?>

                    <?php
                    $todos_bonus_ativos = [];
                    foreach ($todos_bonus_ativos_db as $bonus) {
                        $todos_bonus_ativos[$bonus["ilha"]][] = $bonus;
                    }
                    ?>

                    <div class="list-group">
                        <?php foreach ($ilhas as $ilha): ?>
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>
                                            <?= nome_ilha($ilha["ilha"]) ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>
                                            <?php if ($ilha["tripulacao"]): ?>
                                                <img src="Imagens/Bandeiras/img.php?cod=<?= $ilha["bandeira"] ?>&f=<?= $ilha["faccao"] ?>">
                                                <?= $ilha["tripulacao"] ?>
                                            <?php else: ?>
                                                Sem governante
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                </div>
                                <?php if (isset($todos_bonus_ativos[$ilha["ilha"]])): ?>
                                    <h5>Bônus Ativos:</h5>
                                    <div class="list-group">
                                        <?php foreach ($todos_bonus_ativos[$ilha["ilha"]] as $bonus): ?>
                                            <?php $buff = $buffs[$bonus["buff_id"]]; ?>
                                            <div class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <img src="Imagens/Icones/<?= $buff["icon"] ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $buff["descricao"] ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Tempo
                                                        restante: <?= transforma_tempo_min($bonus["expiracao"] - atual_segundo()) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET["tab"]) && $_GET["tab"] == "cargasRoubadas"): ?>
                <div class="tab-pane active">
                    <?php $cargas = $userDetails->get_item(CARGA_ROUBADA_ID, TIPO_ITEM_REAGENT); ?>
                    <h3>
                        <img src="Imagens/Itens/94.jpg"> Cargas Roubadas: <?= $cargas ? $cargas["quant"] : 0 ?>
                    </h3>
                    <?php
                    $recompensas = DataLoader::load("loja_cargas");

                    $reagents_db = $connection->run("SELECT * FROM tb_item_reagents")->fetch_all_array();
                    $reagents = array();
                    foreach ($reagents_db as $reagent) {
                        $reagents[$reagent["cod_reagent"]] = $reagent;
                    }
                    ?>
                    <div class="row">
                        <?php foreach ($recompensas as $id => $recompensa): ?>
                            <div class="list-group-item col-md-4">
                                <?php if (isset($recompensa["haki"])): ?>
                                    <p>
                                        <i class="fa fa-certificate"></i>
                                        <?= $recompensa["haki"] ?> pontos de Haki para serem distribuidos entre a
                                        tripulação.
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["xp"])): ?>
                                    <p>
                                        <?= $recompensa["xp"] ?> pontos de experiência para toda a tripulação
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["dobroes"])): ?>
                                    <p>
                                        <?= $recompensa["dobroes"] ?> <img src="Imagens/Icones/Dobrao.png">
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["fa"])): ?>
                                    <p>
                                        Aumento na recompensa de toda a tripulação em
                                        <img src="Imagens/Icones/Berries.png"/>
                                        <?= mascara_berries(calc_recompensa($recompensa["fa"])) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["akuma"])): ?>
                                    <div class="equipamentos_casse_6 pull-left">
                                        <img src="Imagens/Itens/100.png">
                                    </div>
                                    <p>
                                        Akuma no Mi aleatória
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["alcunha"])): ?>
                                    <?php $alcunha = $connection->run("SELECT * FROM tb_titulos WHERE cod_titulo = ?", "i", array($recompensa["alcunha"]))->fetch_array(); ?>
                                    <p>
                                        Alcunha: <?= $alcunha["nome"]; ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["img"]) && isset($recompensa["skin"])): ?>
                                    <p>Aparência exclusiva</p>
                                    <p>
                                        <img src="Imagens/Personagens/Icons/<?= get_img(array("img" => $recompensa["img"], "skin_r" => $recompensa["skin"]), "r") ?>.jpg">
                                    </p>
                                    <p>
                                        <img src="Imagens/Personagens/Big/<?= get_img(array("img" => $recompensa["img"], "skin_c" => $recompensa["skin"]), "c") ?>.jpg">
                                    </p>
                                <?php endif; ?>
                                <?php if (isset($recompensa["tipo_item"])): ?>
                                    <?php if ($recompensa["tipo_item"] == TIPO_ITEM_REAGENT): ?>
                                        <div class="clearfix">
                                            <div class="equipamentos_casse_1 pull-left">
                                                <img src="Imagens/Itens/<?= $reagents[$recompensa["cod_item"]]["img"] ?>.png">
                                            </div>
                                            <p>
                                                <?= $reagents[$recompensa["cod_item"]]["nome"] ?>
                                                x <?= $recompensa["quant"] ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (isset($recompensa["effect"])): ?>
                                    <p>Animação de habilidade</p>
                                    <p><?= $recompensa["effect"] ?></p>
                                    <p>
                                        <button class="play-effect btn btn-info"
                                                data-effect="<?= $recompensa["effect"] ?>">
                                            <i class="fa fa-play"></i>
                                        </button>
                                    </p>
                                <?php endif; ?>
                                <br/>
                                <p>
                                    Preço: <?= $recompensa["preco"] ?>
                                    <img src="Imagens/Itens/94.jpg">
                                </p>
                                <p>
                                    <button class="btn btn-success link_confirm"
                                            href="Ilhas/cargas_comprar.php?rec=<?= $id ?>"
                                            data-question="Deseja comprar este item?"
                                        <?= $cargas["quant"] >= $recompensa["preco"] ? "" : "disabled" ?>>
                                        Comprar
                                    </button>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($userDetails->ilha["ilha_dono"] == $userDetails->tripulacao["id"]): ?>
                <?php $recursos = $connection->run("SELECT * FROM tb_ilha_recurso WHERE ilha = ?", "i", array($userDetails->ilha["ilha"]))->fetch_array(); ?>
                <?php if (isset($_GET["tab"]) && $_GET["tab"] == "recursosIlha"): ?>
                    <div class="tab-pane active">
                        <h4>
                            Essa ilha produz: <?= nome_recurso($recursos["recurso_gerado"]) ?>
                            <img src="Imagens/Itens/<?= icon_recurso($recursos["recurso_gerado"]) ?>.jpg"/>
                        </h4>
                        <p>
                            Preço para gerar uma unidade de <?= nome_recurso($recursos["recurso_gerado"]) ?>:
                            <img src="Imagens/Icones/Berries.png"/>
                            <?= mascara_berries(PRECO_GERAR_RECURSO_ILHA) ?>
                        </p>
                        <form class="form-inline ajax_form"
                              data-question="Deseja gerar estes recursos?"
                              method="post" action="Ilhas/gerar_recurso">
                            <div class="form-group">
                                <label>
                                    Quantidade:
                                </label>
                                <input value="1" type="number" class="form-control" name="quant" min="1"
                                       max="<?= floor($userDetails->tripulacao["berries"] / PRECO_GERAR_RECURSO_ILHA) ?>"
                                       required>
                            </div>
                            <button class="btn btn-success">
                                Gerar Recursos
                            </button>
                        </form>
                        <br/>
                        <br/>
                        <h4>Recursos disponíveis:</h4>
                        <div class="row list-group">
                            <?php for ($x = 0; $x <= 2; $x++): ?>
                                <div class="list-group-item col col-md-4">
                                    <img src="Imagens/Itens/<?= icon_recurso($x) ?>.jpg"/>
                                    <?= nome_recurso($x) ?>:
                                    <?= $recursos["recurso_$x"] ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <br/>
                        <h4>Bonus disponíveis</h4>
                        <div class="row list-group">
                            <?php foreach ($bonus_disponiveis as $bonus_id => $bonus): ?>
                                <?php $buff = $buffs[$bonus["buff_id"]]; ?>
                                <div class="list-group-item col col-md-4">
                                    <p><img src="Imagens/Icones/<?= $buff["icon"] ?>"></p>
                                    <p><?= $buff["descricao"] ?></p>
                                    <p>Alcance: <?= ALCANCE_BONUS_ILHA ?> quadros</p>
                                    <p>Duração: <?= transforma_tempo_min(DURACAO_BONUS_ILHA) ?></p>
                                    <p>Preço:</p>
                                    <?php for ($x = 0; $x <= 2; $x++): ?>
                                        <p>
                                            <img src="Imagens/Itens/<?= icon_recurso($x) ?>.jpg"/>
                                            <?= nome_recurso($x) ?>:
                                            <?= $bonus["preco_$x"] ?>
                                        </p>
                                    <?php endfor; ?>
                                    <p>
                                        Se ativar este bônus, a recompensa pela seu capitão irá aumentar em<br/>
                                        <img src="Imagens/Icones/Berries.png"/>
                                        <?= mascara_berries(calc_recompensa($bonus["fa"])) ?>
                                    </p>
                                    <button class="btn btn-success link_confirm"
                                            href="Ilhas/ativar_bonus.php?bonus=<?= $bonus_id ?>"
                                            data-question="Esse benefício ficará disponível para jogadores que estiverem em até <?= ALCANCE_BONUS_ILHA ?> quadros de distância da ilha. Deseja ativa-lo?"
                                        <?= !is_buff_ativo($bonus_ativos, $bonus["buff_id"])
                                        && $recursos["recurso_0"] >= $bonus["preco_0"]
                                        && $recursos["recurso_1"] >= $bonus["preco_1"]
                                        && $recursos["recurso_2"] >= $bonus["preco_2"] ? "" : "disabled" ?>>
                                        Ativar
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET["tab"]) && $_GET["tab"] == "negociarRecursos"): ?>
                    <div class="tab-pane active">
                        <?php $mercadores = $connection->run(
                            "SELECT * FROM tb_ilha_mercador m 
                            LEFT JOIN tb_mapa_contem c ON m.id = c.mercador_id
                            WHERE m.ilha_destino = ?",
                            "i", array($userDetails->ilha["ilha"])
                        )->fetch_all_array(); ?>
                        <?php if (count($mercadores)): ?>
                            <h4>Mercadores a caminho da sua ilha:</h4>
                            <div class="list-group">
                                <?php foreach ($mercadores as $mercador): ?>
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img src="Imagens/Batalha/Npc/Navios/4.png"/><br/>
                                                <?php if ($mercador["finalizou"]): ?>
                                                    <?= nome_ilha($userDetails->ilha["ilha"]) ?>
                                                <?php else: ?>
                                                    <?= get_human_location($mercador["x"], $mercador["y"]) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-5">
                                                <img src="Imagens/Itens/<?= icon_recurso($mercador["recurso"]) ?>.jpg"/>
                                                <?= nome_recurso($mercador["recurso"]) ?>:
                                                <?= $mercador["quant"] ?>
                                            </div>
                                            <div class="col-md-5">
                                                <?php if ($mercador["finalizou"]): ?>
                                                    <button class="btn btn-success link_send"
                                                            href="link_Ilhas/receber_encomenda.php?id=<?= $mercador["id"] ?>">
                                                        Receber a encomenda
                                                    </button>
                                                <?php else: ?>
                                                    O mercador está a caminho
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <br/>
                        <?php endif; ?>
                        <h4>Negociações em aberto:</h4>
                        <?php $vendas = $connection->run("SELECT * FROM tb_ilha_recurso_venda iv INNER JOIN tb_mapa m ON iv.ilha = m.ilha")->fetch_all_array(); ?>
                        <?php if (count($vendas)): ?>
                            <div class="list-group">
                                <?php foreach ($vendas as $venda): ?>
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= nome_ilha($venda["ilha"]) ?>
                                            </div>
                                            <div class="col-md-3">
                                                Oferece <?= $venda["quant"] ?><br/>
                                                <img src="Imagens/Itens/<?= icon_recurso($venda["recurso_oferecido"]) ?>.jpg"/>
                                                <?= nome_recurso($venda["recurso_oferecido"]) ?>
                                            </div>
                                            <div class="col-md-3">
                                                Em troca de <?= $venda["quant"] ?><br/>
                                                <img src="Imagens/Itens/<?= icon_recurso($venda["recurso_desejado"]) ?>.jpg"/>
                                                <?= nome_recurso($venda["recurso_desejado"]) ?>
                                            </div>
                                            <div class="col-md-2">
                                                <?php if ($venda["ilha"] == $userDetails->ilha["ilha"]): ?>
                                                    <button class="btn btn-danger link_send"
                                                            href="link_Ilhas/cancelar_negociacao.php?id=<?= $venda["id"] ?>">
                                                        Cancelar
                                                    </button>
                                                <?php elseif ($recursos["recurso_" . $venda["recurso_desejado"]] >= $venda["quant"]): ?>
                                                    <button class="btn btn-success link_confirm"
                                                            data-question="Ao aceitar a negociação, um navio mercador sairá de cada uma das ilhas transportando os recursos. Você só receberá os recursos quando o navio mercador chegar à sua ilha. Lembre-se que esse navio é vulnerável a ser saqueado por outros jogadores. Deseja aceitar a negociação?"
                                                            href="Ilhas/aceitar_negociacao.php?id=<?= $venda["id"] ?>">
                                                        Aceitar
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Não existe nenhuma negociação em aberto ainda</p>
                        <?php endif; ?>

                        <br/>
                        <h4>Criar uma negociação:</h4>

                        <form class="form-inline ajax_form" method="post" action="Ilhas/criar_negociacao">
                            <div>
                                <div class="form-group">
                                    <label>Oferecer: </label>
                                    <input name="quant" class="form-control" type="number" value="1" min="1" max="500"
                                           required>
                                    <select name="recurso_oferecido" class="form-control" required>
                                        <?php for ($x = 0; $x <= 2; $x++): ?>
                                            <?php if ($recursos["recurso_$x"]): ?>
                                                <option value="<?= $x ?>">
                                                    <?= nome_recurso($x) ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <br/>
                            <div>
                                <div class="form-group">
                                    <label>Em troca de: </label>
                                    <select name="recurso_procurado" class="form-control" required>
                                        <?php for ($x = 0; $x <= 2; $x++): ?>
                                            <option value="<?= $x ?>">
                                                <?= nome_recurso($x) ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <br/>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    Criar Negociação
                                </button>
                            </div>
                        </form>
                        <br/>
                        <h4>Recursos disponíveis:</h4>
                        <div class="row list-group">
                            <?php for ($x = 0; $x <= 2; $x++): ?>
                                <div class="list-group-item col col-md-4">
                                    <img src="Imagens/Itens/<?= icon_recurso($x) ?>.jpg"/>
                                    <?= nome_recurso($x) ?>:
                                    <?= $recursos["recurso_$x"] ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>