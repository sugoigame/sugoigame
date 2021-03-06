<div class="panel-heading">
    Tripulantes fora do barco
</div>

<div class="panel-body">
    <?= ajuda("Tripulantes fora do barco", "Está a fim de recrutar mais tripulantes e não tem mais espaço no barco? 
       Faça com que seus tripulantes revesem as tarefas deixando um na ilha para que outro vá com você no barco."); ?>

    <h3>Formações predefinidas:</h3>

    <?php $personagens_in_formacao = $connection->run(
        "SELECT * FROM tb_tripulacao_formacao f INNER JOIN tb_personagens p ON f.personagem_id = p.cod WHERE f.tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"])
    ); ?>

    <?php $last_id = null; ?>
    <?php if (!$personagens_in_formacao->count()): ?>
        <p>Você ainda não criou nenhuma formação de tripulantes ainda</p>
    <?php else: ?>
        <?php $personagens_in_formacao = $personagens_in_formacao->fetch_all_array(); ?>

        <?php
        $formacoes = array();
        foreach ($personagens_in_formacao as $personagem) {
            $formacoes[$personagem["formacao_id"]][] = $personagem;
        }
        ?>

        <ul class="list-group">
            <?php foreach ($formacoes as $id => $formacao): ?>
                <li class="list-group-item">
                    <h4>Formação: <?= $id ?></h4>
                    <div class="row">
                        <?php foreach ($formacao as $pers): ?>
                            <div class="col-md-1" style="padding: 2px">
                                <img width="100%" src="Imagens/Personagens/Icons/<?= get_img($pers, "r"); ?>.jpg">
                                <h5><?= $pers["nome"] ?></h5>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div>
                        <button class="btn btn-danger link_confirm"
                                href="Personagem/formacao_remover.php?id=<?= $formacao[0]["id"] ?>"
                                data-question="Deseja mesmo remover essa formacao?">
                            Remover
                        </button>
                        <?php if ($userDetails->vip["formacoes"]) : ?>
                            <button class="btn btn-success link_confirm"
                                    href="Personagem/formacao_ativar.php?id=<?= $formacao[0]["id"] ?>"
                                    data-question="Deseja mesmo ativar essa formacao?">
                                Ativar
                            </button>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($userDetails->vip["formacoes"]) : ?>
        <form class="form-inline ajax_form" method="post" data-question="Deseja criar essa formação?"
              action="Personagem/formacao_criar">
            <div class="form-group">
                <label>Nome da nova formação:</label>
                <input name="formacao_id" type="text" required>
            </div>
            <p>
                <button class="btn btn-success">
                    Criar formação com minha tripulação atual
                </button>
            </p>
        </form>
    <?php else: ?>
        <p>
            <a href="./?ses=vipLoja" class="link_content">
                Adquirir a vantagem VIP para usar as formações de tripulantes
            </a>
        </p>
    <?php endif; ?>

    <h3>Tripulantes no barco:</h3>

    <div class="list-group row">
        <?php foreach ($userDetails->personagens as $pers): ?>
            <?php if ($pers["cod"] != $userDetails->capitao["cod"]): ?>
                <div class="list-group-item col-md-2">
                    <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r"); ?>.jpg">
                    <h4><?= $pers["nome"] ?></h4>
                    <button class="btn btn-primary link_send"
                            data-question="Deseja retirar <?= $pers["nome"] ?> da tripulação para abrir espaço para um novo tripulante?<br/>Os tripulantes fora do barco não recebem experiência, não evoluem junto da tripulação e tem suas configurações de táticas resetadas."
                            href="link_Personagem/inativar.php?cod=<?= $pers["cod"] ?>">
                        Retirar
                    </button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h3>Tripulantes fora do barco:</h3>
    <?php $personages = $connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 0", "i", $userDetails->tripulacao["id"])->fetch_all_array(); ?>

    <?php $navio = $userDetails->navio
        ? $connection->run("SELECT * FROM tb_navio WHERE cod_navio = ?", "i", array($userDetails->navio["cod_navio"]))->fetch_array()
        : array("limite" => 1); ?>
    <div class="list-group row">
        <?php foreach ($personages as $pers): ?>
            <div class="list-group-item col-md-2">
                <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r"); ?>.jpg">
                <h4><?= $pers["nome"] ?></h4>
                <?php if (count($userDetails->personagens) < $navio["limite"] && !$pers["preso"]) : ?>
                    <button class="btn btn-info link_send"
                            href="link_Personagem/ativar.php?cod=<?= $pers["cod"] ?>">
                        Inserir
                    </button>
                <?php endif; ?>
                <?php if ($pers["preso"]) : ?>
                    <p>Esse tripulante foi preso e não pode entrar para a tripulação até que seja solto novamente</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>