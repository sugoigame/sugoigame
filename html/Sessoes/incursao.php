<div class="panel-heading">
    Incursão à Ilha
</div>

<div class="panel-body">
    <?= ajuda("Incursão à Ilha", "Avance em uma investida derrotando todos os oponentes que encontrar pelo caminho.") ?>

    <?php
    $incursoes = DataLoader::load("incursoes");
    $incursao = $incursoes[$userDetails->ilha["ilha"]];

    $incursao_nivel = $connection->run("SELECT * FROM tb_incursao_nivel WHERE tripulacao_id = ? AND ilha = ?",
        "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

    $incursao_nivel = $incursao_nivel->count() ? $incursao_nivel->fetch_array() : array("nivel" => 1);

    $incursao_progresso = $connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ? AND ilha = ?",
        "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

    $incursao_progresso = $incursao_progresso->count() ? $incursao_progresso->fetch_array() : array("progresso" => 1);
    ?>
    <p>
        Você pode receber as recompensas por derrotar o último adversário de cada nível 1 vez por semana.
    </p>
    <?php foreach ($incursao["niveis"] as $nivel_id => $nivel): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php if (isset($incursao["especial"])): ?>
                    Incursão Especial
                <?php else: ?>
                    <?= $nivel_id ?>º Nível
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php $derrotados = isset($incursao["especial"]) ? $incursao_nivel["nivel"] - 1 : 0; ?>
                    <?php foreach ($nivel as $adversario_id => $adversario): ?>
                        <?php if ($adversario_id !== "recompensas"): ?>
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>
                                            <?= isset($incursao["especial"]) ? $incursao_nivel["nivel"] : $adversario_id ?>
                                            º adversário
                                        </h4>
                                        <p>
                                            <?= $adversario["xp"] ?> pontos de experiência para toda a tripulação <br/>
                                            <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($adversario["berries"]) ?>
                                            <br/>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if ($incursao_progresso["progresso"] > $adversario_id): ?>
                                            <?php $derrotados++; ?>
                                        <?php endif; ?>
                                        <?php if (isset($incursao["especial"]) || $incursao_progresso["progresso"] == $adversario_id || $incursao_nivel["nivel"] == $adversario_id): ?>
                                            <button class="btn btn-success link_confirm"
                                                    data-question="Deseja atacar esse adversário?"
                                                    href="Incursao/atacar.php?alvo=<?= $adversario_id ?>">
                                                Atacar
                                            </button>
                                        <?php elseif ($incursao_progresso["progresso"] > $adversario_id || $incursao_nivel["nivel"] > $adversario_id): ?>
                                            <p class="text-success">
                                                Adversário derrotado <i class="fa fa-check"></i>
                                            </p>
                                            <button class="btn btn-danger btn-disabled" disabled>
                                                Indisponível
                                            </button>
                                            <?php /*<button class="btn btn-success link_send"
                                                    href="link_Incursao/atacar.php?alvo=<?= $adversario_id ?>">
                                                Atacar Novamente
                                            </button>*/ ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $primeiro = false; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($nivel["recompensas"])): ?>
                    <div>
                        <h4>Recompensas semanais por concluir este nível:</h4>
                        <?php $reagents = get_reagents_for_recompensa(); ?>
                        <?php $equipamentos = get_equipamentos_for_recompensa(); ?>
                        <?php foreach ($nivel["recompensas"] as $recompensa): ?>
                            <p>
                                <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                            </p>
                        <?php endforeach; ?>
                        <p> 1.000 pontos de experiência para toda a tripulação</p>
                        <?php if ($derrotados >= count($nivel) - 1): ?>
                            <?php $recebida = $connection->run("SELECT * FROM tb_incursao_recompensa_recebida WHERE tripulacao_id = ? AND ilha = ? AND nivel = ?",
                                "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $nivel_id))->count(); ?>
                            <?php if (!$recebida): ?>
                                <button class="btn btn-success link_send"
                                        href="link_Incursao/receber_recompensa.php?nivel=<?= $nivel_id ?>">
                                    Receber Recompensa
                                </button>
                            <?php else: ?>
                                <p class="text-success">
                                    Você já recebeu essa recompensa nessa semana <i class="fa fa-check"></i>
                                </p>
                                <p class="text-success">
                                    Volte semana que vem para obte-la novamente.
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (isset($incursao["especial"])): ?>
        <h3>Ranking</h3>

        <?php
        $query = "SELECT i.*, u.tripulacao, u.bandeira, u.faccao, p.* FROM tb_incursao_nivel i
                INNER JOIN tb_usuarios u ON i.tripulacao_id = u.id
                INNER JOIN tb_personagens p ON u.cod_personagem = p.cod
                WHERE i.ilha = ? ORDER BY nivel DESC LIMIT 10 ";
        $result = $connection->run($query, "i", array($userDetails->ilha["ilha"]));
        $nivel_mais_alto = 0;
        ?>
        <div class="list-group">
            <?php while ($famoso = $result->fetch_array()) : ?>
                <?php if (!$nivel_mais_alto) {
                    $nivel_mais_alto = $famoso["nivel"];
                } ?>
                <div class="list-group-item">
                    <div class="media">
                        <div class="media-left">
                            <img src="Imagens/Bandeiras/img.php?cod=<?= $famoso["bandeira"]; ?>&f=<?= $famoso["faccao"]; ?>"
                                 width="40"/>
                            <img src="Imagens/Personagens/Icons/<?= get_img($famoso, "r") ?>.jpg" width="40"/>
                        </div>
                        <div class="media-body">
                            <p><?= $famoso["nome"] ?> - <?= $famoso["tripulacao"] ?></p>
                            <div class="progress" style="margin: 0">
                                <a>
                                    <?= mascara_numeros_grandes($famoso["nivel"]) ?>º Adversário
                                </a>
                                <div class="progress-bar progress-bar-success"
                                     style="width: <?= $famoso["nivel"] / $nivel_mais_alto * 100 ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php endif; ?>
</div>
