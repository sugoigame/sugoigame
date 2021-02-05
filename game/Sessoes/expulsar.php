<div class="panel-heading">
    Expulsar tripulante
</div>

<div class="panel-body">
    <?= ajuda("Expulsar tripulante", "Faça os inúteis da sua tripulação andarem na pracha.") ?>

    <script type="text/javascript">
        $(function () {
            $(".bt_expulsar").click(function () {
                var locale = $(this).attr("href");
                bootbox.prompt('Expulsar esse tripulante?<br>Escreva "EXPULSAR" abaixo:', function (result) {
                    if (result == "EXPULSAR") {
                        sendGet(locale);
                    }
                });
            });
        });
    </script>
    <ul class="list-group">
        <?php foreach ($userDetails->personagens as $pers): ?>
            <?php if ($pers["cod"] != $userDetails->capitao["cod"]): ?>
                <li class="list-group-item">
                    <h4><img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"/><br><?= $pers["nome"] ?>
                    </h4>
                    <p>
                        <button class="bt_expulsar btn btn-danger" href="Recrutar/demitir.php?cod=<?= $pers["cod"] ?>">
                            Expulsar
                        </button>
                    </p>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>