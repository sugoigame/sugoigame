<div class="panel-heading">
    Incursão
    <?= ajuda("Incursão", "Avance em uma investida derrotando todos os oponentes que encontrar pelo caminho.  Você pode receber as recompensas por derrotar o último adversário de cada nível 1 vez por semana.") ?>
</div>

<div class="panel-body">

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
    <?php foreach ($incursao["niveis"] as $nivel_id => $nivel) : ?>
        <div class="list-group-item">
            <div class="panel-heading">
                <?php if (isset($incursao["especial"])) : ?>
                    Incursão Especial
                <?php else : ?>
                    <?= $nivel_id ?>º Nível
                <?php endif; ?>
            </div>
            <div class="row p1">
                <?php $derrotados = isset($incursao["especial"]) ? $incursao_nivel["nivel"] - 1 : 0; ?>
                <?php foreach ($nivel as $adversario_id => $adversario) : ?>
                    <?php if ($adversario_id !== "recompensas") : ?>
                        <div class="col col-xs-2 p1px">
                            <div class="panel panel-default m0 h-100">
                                <div class="panel-body">
                                    <div>
                                        <p>
                                            <?= isset($incursao["especial"]) ? $incursao_nivel["nivel"] : $adversario_id ?>
                                            º adversário
                                        </p>
                                        <p>
                                            <img src="Imagens/NPC/xp.jpg" height="18px">
                                            <?= $adversario["xp"] ?>
                                        </p>
                                        <p>
                                            <img src="Imagens/Icones/Berries.png" />
                                            <?= mascara_berries($adversario["berries"]) ?>
                                            <br />
                                        </p>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <?php if ($incursao_progresso["progresso"] > $adversario_id) : ?>
                                        <?php $derrotados++; ?>
                                    <?php endif; ?>
                                    <?php if (isset($incursao["especial"]) || $incursao_progresso["progresso"] == $adversario_id || $incursao_nivel["nivel"] == $adversario_id) : ?>
                                        <button class="btn btn-success link_confirm" data-question="Deseja atacar esse adversário?"
                                            href="Incursao/atacar.php?alvo=<?= $adversario_id ?>">
                                            Atacar
                                        </button>
                                    <?php elseif ($incursao_progresso["progresso"] > $adversario_id || $incursao_nivel["nivel"] > $adversario_id) : ?>
                                        <p class="text-success">
                                            Adversário derrotado <i class="fa fa-check"></i>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php $primeiro = false; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (isset($nivel["recompensas"])) : ?>
                    <div class="col col-xs-2 p1px">
                        <div class="panel panel-default m0 h-100">
                            <div class="panel-body">
                                <p>Recompensas:</p>
                                <?php $reagents = get_reagents_for_recompensa(); ?>
                                <?php $equipamentos = get_equipamentos_for_recompensa(); ?>
                                <?php foreach ($nivel["recompensas"] as $recompensa) : ?>
                                    <p>
                                        <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                                    </p>
                                <?php endforeach; ?>
                                <p>
                                    <img src="Imagens/NPC/xp.jpg" height="18px">
                                    1.000
                                </p>
                            </div>
                            <div class="panel-footer">
                                <?php if ($derrotados >= count($nivel) - 1) : ?>
                                    <?php $recebida = $connection->run("SELECT * FROM tb_incursao_recompensa_recebida WHERE tripulacao_id = ? AND ilha = ? AND nivel = ?",
                                        "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $nivel_id))->count(); ?>
                                    <?php if (! $recebida) : ?>
                                        <button class="btn btn-success link_send"
                                            href="link_Incursao/receber_recompensa.php?nivel=<?= $nivel_id ?>">
                                            Receber Recompensa
                                        </button>
                                    <?php else : ?>
                                        <p class="text-success">
                                            Recompensa recebida <i class="fa fa-check"></i><br />
                                            Volte semana que vem!
                                        </p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endforeach; ?>
    <?php if (isset($incursao["especial"])) : ?>
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
                <?php if (! $nivel_mais_alto) {
                    $nivel_mais_alto = $famoso["nivel"];
                } ?>
                <div class="list-group-item">
                    <div class="media">
                        <div class="media-left">
                            <img src="Imagens/Bandeiras/img.php?cod=<?= $famoso["bandeira"]; ?>&f=<?= $famoso["faccao"]; ?>"
                                width="40" />
                            <img src="Imagens/Personagens/Icons/<?= get_img($famoso, "r") ?>.jpg" width="40" />
                        </div>
                        <div class="media-body">
                            <p>
                                <?= $famoso["nome"] ?> -
                                <?= $famoso["tripulacao"] ?>
                            </p>
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
