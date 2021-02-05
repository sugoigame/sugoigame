<div class="panel-heading">
    Lista Negra
</div>

<div class="panel-body">
    <?= ajuda("Lista Negra", "Ganhe um bônus de Fama/Ameaça ao derrotar personagens que te derrotaram em batalha.<br />
			OBS: Para cada vingança bem sucedida, você ganha 50 pontos em fama/ameaça.") ?>

    <?php $inimigos = $connection->run("
SELECT 
ini.inimigo AS inimigo_cod,
ini.personagem AS personagem_cod,
ini.fa AS fa,
perini.img AS inimigo_img,
perini.skin_r AS inimigo_skin_r,
perini.nome AS inimigo_nome,
usrini.tripulacao AS inimigo_tripulacao,
usrini.bandeira AS inimigo_bandeira,
usrini.faccao AS inimigo_faccao
FROM tb_inimigos ini 
INNER JOIN tb_personagens perini ON perini.cod = ini.inimigo
INNER JOIN tb_usuarios usrini ON perini.id = usrini.id
WHERE ini.id = ?", "i", $userDetails->tripulacao["id"])->fetch_all_array(); ?>

    <?php if (count($inimigos)): ?>
        <ul class="list-group">
            <?php foreach ($inimigos as $inimigo): ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $inimigo["inimigo_bandeira"] ?>&f=<?= $inimigo["inimigo_faccao"] ?>"/>
                        <img src="Imagens/Personagens/Icons/<?= get_img(array("img" => $inimigo["inimigo_img"], "skin_r" => $inimigo["inimigo_skin_r"]), "r") ?>.jpg"/>
                    </h4>
                    <h4><?= $inimigo["inimigo_tripulacao"] ?> - <?= $inimigo["inimigo_nome"] ?></h4>
                    <p>
                        <?= $userDetails->tripulacao["faccao"] == 0 ? "Fama" : "Ameaça" ?>
                        a recuperar: <?= $inimigo["fa"] ?>
                    </p>

                    <button href="link_Geral/remover_lista_negra.php?pers=<?= $inimigo["personagem_cod"] ?>&ini=<?= $inimigo["inimigo_cod"] ?>&fa=<?= $inimigo["fa"] ?>"
                            class="link_send btn btn-danger">
                        Remover da lista
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        Sua lista negra está vazia
    <?php endif; ?>
</div>