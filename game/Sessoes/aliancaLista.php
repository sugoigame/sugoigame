<div class="panel-heading">
    Conheça as Frotas e Alianças existentes até o momento
</div>

<div class="panel-body">
    <?= ajuda("Frotas e Alianças", "Aqui estão listadas todas as alianças e frotas do jogo.") ?>

    <?php $result = $connection->run("
SELECT 
ally.nome AS nome,
pers.nome AS lider,
user.faccao AS faccao
FROM tb_alianca ally 
INNER JOIN tb_alianca_membros allymemb ON ally.cod_alianca = allymemb.cod_alianca
INNER JOIN tb_usuarios user ON allymemb.id = user.id
INNER JOIN tb_personagens pers ON user.cod_personagem = pers.cod
WHERE allymemb.autoridade = '0' AND user.adm='0'"); ?>

    <?php if ($result->count()): ?>
        <ul class="list-group">
            <?php while ($ally = $result->fetch_array()): ?>
                <li class="list-group-item">
                    <h4><img src="Imagens/Icones/Bandeira_<?= $ally["faccao"] ?>.jpg"/> <?= $ally["nome"] ?></h4>
                    <p>
                        <strong>Líder:</strong> <?= $ally["lider"] ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>
            Nenhuma frota ou aliança foi criada ainda...
        </p>
    <?php endif; ?>

</div>