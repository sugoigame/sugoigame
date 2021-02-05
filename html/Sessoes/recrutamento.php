<div class="panel-heading">
    Recrute um Amigo
</div>

<script type="text/javascript">
    $(function () {
        $('#copy-link').click(function () {
            document.getElementById('link-to-copy').select();
            document.execCommand('copy');
        });
    });
</script>

<div class="panel-body">
    <?= ajuda("Recrute um Amigo", "Chame seus amigos pra jogar e ganhe recompensas exclusivas!"); ?>

    <p>
        Compartilhe seu link de recrutamento para que seus amigos se cadastrem no jogo através dele. Os amigos que se
        cadastrarem através do seu link ganharão benefícios exclusivos.
    </p>
    <p>
        Quando um amigo cadastrado através do seu link alcançar certos objetivos no jogo você também será recompensado.
    </p>

    <h3>O seu link de recrutamento:</h3>
    <p>
    <textarea class="form-control" id="link-to-copy"
              readonly>https://sugoigame.com.br/?ses=cadastro&id=<?= $userDetails->conta["id_encrip"] ?></textarea>
    </p>
    <button id="copy-link" class="btn btn-info">
        Copiar
    </button>

    <h3>O meu recrutador:</h3>
    <p>
        Novos jogadores recrutados recebem um Pacote do Iniciante que pode ser aberto a cada 5 níveis dando recompensas
        incríveis para ajudar o seu amigo a começar bem o jogo.
    </p>
    <?php $padrinho = $connection->run(
        "SELECT * FROM tb_afilhados a INNER JOIN tb_conta c ON a.id = c.conta_id WHERE a.afilhado = ?",
        "i", array($userDetails->conta["conta_id"])
    ); ?>
    <?php if ($padrinho->count()): ?>
        <?php $padrinho = $padrinho->fetch_array(); ?>
        <h4>Você foi recrutado por <?= $padrinho["nome"] ?></h4>
        <?php if (!$padrinho["bau_ganho"]): ?>
            <button class="btn btn-success link_send"
                    href="link_Geral/pacote_iniciante.php">
                Receber seu pacote do iniciante
            </button>
        <?php endif; ?>
    <?php else: ?>
        <p>Você ainda não foi recrutado por nenhum outro jogador</p>
        <?php if ((time() - strtotime($userDetails->conta["cadastro"])) > (7 * 24 * 60 * 60)): ?>
            <p>
                Sua conta é muito antiga para ser recrutada, apenas contas criadas nos último 7 dias podem receber um
                recrutador
            </p>
        <?php else: ?>
            <form class="ajax_form" method="post" action="Geral/add_recrutador">
                <div class="form-group">
                    <label>Informe o link de recrutamento do jogador que gostaria que fosse o seu recrutador:</label>
                    <input name="link" class="form-control" type="text">
                </div>
                <div>
                    <button type="submit" class="btn btn-info">Registrar Recrutador</button>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <h3>Amigos já recrutados:</h3>

    <p>
        Quando um amigo recrutado alcançar o nível do mais forte 50 você será recompensado com 10 milhões de Berries
    </p>

    <?php $afilhados = $connection->run(
        "SELECT 
          a.*, 
          c.nome AS nome,
          c.ativacao AS ativacao,
          u.tripulacao AS tripulacao,
          p.nome AS capitao,
         (SELECT max(p.lvl) FROM tb_personagens p INNER JOIN tb_usuarios u ON p.id = u.id WHERE u.conta_id = c.conta_id) AS lvl_mais_forte,
         (SELECT count(p.id) FROM tb_vip_pagamentos p WHERE p.mensagem LIKE concat('%ouro para ', CAST(a.afilhado AS UNSIGNED))) AS ouro_comprado
         FROM tb_afilhados a 
         INNER JOIN tb_conta c ON a.afilhado = c.conta_id
         LEFT JOIN tb_usuarios u ON c.tripulacao_id = u.id
         LEFT JOIN tb_personagens p ON u.cod_personagem = p.cod 
         WHERE a.id = ?",
        "i", array($userDetails->conta["conta_id"])
    ); ?>

    <?php if ($afilhados->count()): ?>
        <ul class="list-group">
            <?php while ($afilhado = $afilhados->fetch_array()): ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>
                                <?= $afilhado["nome"]; ?>
                                <?php if ($afilhado["ativacao"]): ?>
                                    <small>Este jogador ainda não ativou sua conta</small>
                                <?php endif; ?>
                            </h4>
                            <p>
                                Tripulação: <?= $afilhado["tripulacao"] ?><br/>
                                Capitão: <?= $afilhado["capitao"] ?>
                            </p>
                            <p>
                                Nível do mais
                                forte: <?= $afilhado["lvl_mais_forte"] ? $afilhado["lvl_mais_forte"] : 0 ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <?php if (!$afilhado["berries_ganhos"]): ?>
                                <p>
                                    <?php if ($afilhado["lvl_mais_forte"] < 50): ?>
                                        Quando este jogador alcançar o nível do mais forte 50, você receberá<br/>
                                        <img src="Imagens/Icones/Berries.png"/> 10.000.000
                                    <?php else: ?>
                                        <button class="btn btn-success link_send"
                                                href="link_Geral/recompensa_berries_recrutamento.php?id=<?= $afilhado["afilhado"] ?>">
                                            Receber recompensa
                                        </button>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!$afilhado["medalha_ganha"]): ?>
                                <p>
                                    <?php if (!$afilhado["ouro_comprado"]): ?>
                                        Quando este jogador comprar moedas de ouro você receberá 1 medalha de recrutamento.
                                    <?php else: ?>
                                        <button class="btn btn-success link_send"
                                                href="link_Geral/recompensa_medalha_recrutamento.php?id=<?= $afilhado["afilhado"] ?>">
                                            Receber recompensa
                                        </button>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Você ainda não recrutou nenhum outro jogador</p>
    <?php endif; ?>
    <?php $recompensas = DataLoader::load("loja_recrutamento"); ?>
    <h3>Premios de recrutamento:</h3>
    <h4>
        Você possui <?= $userDetails->conta["medalhas_recrutamento"] ?>
        <img src="Imagens/Icones/MoedaRecrutamento.png">
        Medalhas de Recrutamento
    </h4>

    <p>
        Quando um amigo recrutado colocar Moedas de Ouro na conta você será recompensado com uma Medalha de
        Recrutamento
    </p>

    <div class="row">
        <?php foreach ($recompensas as $id => $recompensa): ?>
            <div class="list-group-item col-md-4">
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
                <?php if (isset($recompensa["skin_navio"])): ?>
                    <p>Aparência de navio exclusiva</p>
                    <p>
                        <?php render_navio_skin($userDetails->tripulacao["bandeira"], $userDetails->tripulacao["faccao"], $recompensa["skin_navio"]); ?>
                    </p>
                <?php endif; ?>
                <br/>
                <p>
                    Preço: <?= $recompensa["preco"] ?>
                    <img src="Imagens/Icones/MoedaRecrutamento.png">
                </p>
                <p>
                    <button class="btn btn-success link_confirm" href="Geral/recrutamento_comprar.php?rec=<?= $id ?>"
                            data-question="Deseja comprar este item?"
                        <?= $userDetails->conta["medalhas_recrutamento"] >= $recompensa["preco"] ? "" : "disabled" ?>>
                        Comprar
                    </button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>