<div class="panel-heading">
    <h4>Desenvolvimento colaborativo - Somos transparentes e trabalhamos pela comunidade!</h4>
</div>

<div class="panel-body">
    <p>
        Caso queira sugerir algum novo sistema no jogo, fique a vontade para iniciar uma discussão no nosso
        <a class="link_content" href="?ses=forum">Fórum</a>. Sistemas que são bem aceitos pela comunidade são
        desenvolvidos pela nossa equipe!
    </p>
    <p>
        Em caso de dúvidas, você também pode entrar em contato com a nossa
        <a href="https://www.facebook.com/sugoigamebr" target="_blank">Página no Facebook</a>.
    </p>
    <div>
        <iframe src="https://trello.com/b/PBUs0mxp.html" style="width: 100%; height: 100vh; border: none;"></iframe>
    </div>
</div>
<!--
<?php
function get_kanban_itens($column) {
    global $connection;
    global $userDetails;
    return $connection->run(
        "SELECT 
         item.id AS id,
         item.title AS title,
         item.description AS description,
         item.`column` AS `column`,
         usr.tripulacao AS tripulacao,
         (SELECT avg(rate.rate) FROM tb_kanban_rate rate WHERE rate.kanban_item_id = item.id) AS rate,
         (SELECT count(rate.id) FROM tb_kanban_rate rate WHERE rate.kanban_item_id = item.id) AS total,
         (SELECT rate.rate FROM tb_kanban_rate rate WHERE rate.kanban_item_id = item.id AND rate.conta_id = ?) AS my_rate
        FROM tb_kanban_item item 
        INNER JOIN tb_usuarios usr ON item.tripulacao_id = usr.id
        WHERE item.`column` = '$column'
        ORDER BY rate DESC, item.title",
        "i", $userDetails->conta["conta_id"]
    );
}

?>

<?php function render_kanban_panel($result) { ?>
    <?php global $userDetails; ?>

    <?php while ($sugestao = $result->fetch_array()) : ?>
        <div class="panel panel-info">
            <div class="sugestao panel-heading"
                 data-title="<?= htmlspecialchars($sugestao["title"], ENT_QUOTES, 'UTF-8'); ?>"
                 data-description="<?= htmlspecialchars($sugestao["description"], ENT_QUOTES, 'UTF-8'); ?>"
                 onclick="var $t = $(this); bootbox.alert({title: escapeString($t.data('title')), message: escapeString($t.data('description'))});">
                <?= htmlspecialchars($sugestao["title"], ENT_QUOTES, 'UTF-8') ?>
                <i class="fa fa-external-link"></i>
            </div>
            <div class="panel-body">
                <div class="text-success"><?= $sugestao["total"] ?> votos</div>
                <div>
                    <div>
                        Importância para a comunidade:
                    </div>
                    <div data-toggle="tooltip" data-placement="bottom" title="<?= round($sugestao["rate"], 2) ?>">
                        <div id="importance-<?= $sugestao["id"] ?>"></div>
                    </div>
                </div>
                <div class="text-warning">
                    <div>Importância para mim:</div>
                    <div>
                        <div id="my-rate-<?= $sugestao["id"] ?>"></div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <p>
                    Criado por: <?= $sugestao["tripulacao"] ?>
                </p>
                <?php if ($userDetails->tripulacao["adm"]): ?>
                    <button class="btn btn-danger link_confirm" data-question="Deseja remover esta sugestão?"
                            href="Kanban/remove_item.php?id=<?= $sugestao["id"] ?>">
                        Remover
                    </button>
                    <br/>
                    <?php if ($sugestao["column"] > 0): ?>
                        <button class="btn btn-primary link_send"
                                href="link_Kanban/voltar.php?id=<?= $sugestao["id"] ?>">
                            Voltar
                        </button>
                    <?php endif; ?>
                    <?php if ($sugestao["column"] <= 3): ?>
                        <button class="btn btn-success link_send"
                                href="link_Kanban/avancar.php?id=<?= $sugestao["id"] ?>">
                            Avançar
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <script type="text/javascript">
            $(function () {
                $('#importance-<?= $sugestao["id"] ?>').starrr({
                    rating: <?= round($sugestao["rate"]) ?>,
                    readOnly: true
                });
                $('#my-rate-<?= $sugestao["id"] ?>').starrr({
                    rating: <?= round($sugestao["my_rate"]) ?>,
                    change: function (e, value) {
                        sendGet('Kanban/rate.php?id=<?= $sugestao["id"] ?>&rate=' + value);
                    }
                });
            });
        </script>
    <?php endwhile; ?>
<?php } ?>

<div class="panel-heading">
    Lista de desenvolvimento
</div>

<style type="text/css">
    .kanban-panel {
        height: 600px;
        overflow: auto;
    }

    .sugestao {
        cursor: pointer;
    }

    .painels .col-md-4 {
        padding: 0;
    }

    .painels .panel-body {
        padding: 10px 0;
    }

</style>

<div class="panel-body">
    <?= ajuda("Lista de desenvolvimento", "Neste espaço você pode ajudar a equipe do Sugoi Game a priorizar os novos recursos
    que estão pra vir no jogo.<br/>Lembre-se de votar na importância de cada novidade pra você!") ?>

    <h3>O nosso volume de trabalho está <span class="text-info">Mediano</span></h3>

    <p>
        <button class="btn btn-success" data-toggle="modal" data-target="#new-sugestion-modal">
            Enviar sugestão
        </button>
    </p>

    <?php $novas = get_kanban_itens(0); ?>
    <?php $aprovadas = get_kanban_itens(1); ?>
    <?php $desenvolvimento = get_kanban_itens(2); ?>
    <?php $concluidas = get_kanban_itens(3); ?>

    <div class="row painels">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Novas sugestões a serem avaliadas</div>
                <div class="panel-body kanban-panel">
                    <?php render_kanban_panel($novas); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Sugestões aprovadas pela Staff</div>
                <div class="panel-body kanban-panel">
                    <?php render_kanban_panel($aprovadas); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Sugestões em desenvolvimento</div>
                <div class="panel-body kanban-panel">
                    <?php render_kanban_panel($desenvolvimento); ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Sugestões concluídas/finalizadas</div>
                <div class="panel-body kanban-panel">
                    <?php render_kanban_panel($concluidas); ?>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="new-sugestion-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Nova sugestão</h4>
            </div>
            <form class="ajax_form" method="post" action="Kanban/create_item"
                  onsubmit="$('#new-sugestion-modal').modal('hide');">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Título:</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Descrição:</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
-->