<?php $termo_correto = $userDetails->tripulacao["faccao"] == 0 ? "Frota" : "Aliança"; ?>

<div class="panel-heading">
    <?= $termo_correto ?>
</div>

<div class="panel-body">
    <?= ajuda("Frotas / Alianças", "Aqui você pode gerenciar seus convites para frotas/alianças ou criar sua própria frota/aliança") ?>

    <script type="text/javascript">
        $(function () {
            $('#nome_alianca').blur(function () {
                var input = document.getElementById("nome_akuma").value;
                input = removeCaracteres(input);
                document.getElementById("nome_akuma").value = input;
                if (input.length < 5) {
                    document.getElementById("img_status_nome").src = "Imagens/Icones/0.gif";
                    document.getElementById("texto_status_nome").innerHTML = "Nome muito curto";
                }
                else {
                    $.ajax({
                        type: 'get',
                        url: 'Scripts/Verificadores/verifica_cadastro_alianca.php',
                        data: "alianca=" + input,
                        cache: false,
                        success: function (retorno) {
                            if (retorno == 1) {
                                document.getElementById("img_status_nome").src = "Imagens/Icones/1.gif";
                                document.getElementById("texto_status_nome").innerHTML = ""
                            } else {
                                document.getElementById("img_status_nome").src = "Imagens/Icones/0.gif";
                                document.getElementById("texto_status_nome").innerHTML = "O nome informado ja esta cadastrado";
                            }
                        }
                    });
                }
            })
        });
    </script>
    <?php if ($userDetails->capitao["lvl"] >= 15): ?>
        <h5>Escolha um nome e uma forma de pagamento para criar sua própria <?= $termo_correto ?>: </h5>

        <form method="post" action="Scripts/Alianca/alianca_criar.php">
            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" class="form-control" id="nome_alianca"
                       placeholder="Insira aqui um nome para a sua <?= $termo_correto ?>">
                <img id="img_status_nome" src="Imagens/Icones/3.gif">
                <span id="texto_status_nome"></span>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="pagamento" value="1" checked="1"/>
                    <img src="Imagens/Icones/Berries.png"/>10.000.000
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="pagamento" value="2"/>
                    <img src="Imagens/Icones/Gold.png"/>8<br><br>
                </label>
            </div>

            <button class="btn btn-success" type="submit">Criar minha <?= $termo_correto ?></button>
        </form>
    <?php else: ?>
        É necessário ter um capitão no nível 15 ou superior para criar uma<?= $termo_correto ?>
    <?php endif; ?>
    <h4>Convites</h4>
    <?php $result = $connection->run("SELECT * FROM tb_alianca_convite convite INNER JOIN tb_alianca ally ON convite.cod_alianca = ally.cod_alianca WHERE convite.convidado = ?",
        "i", $userDetails->tripulacao["id"]); ?>

    <?php if ($result->count()): ?>
        <ul class="list-group">
            <?php while ($convite = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <strong><?= $convite["nome"] ?></strong>
                        te convidou para participar de uma <?= $termo_correto ?>
                    </h4>
                    <p>
                        <button class="btn btn-danger"
                                onclick="location.href='Scripts/Alianca/alianca_recusar.php?cod=<?= $convite["cod_alianca"]; ?>'">
                            <i class="fa fa-times"></i> Recusar
                        </button>
                        <button class="btn btn-success"
                                onclick="location.href='Scripts/Alianca/alianca_confirmar.php?cod=<?= $convite["cod_alianca"]; ?>';">
                            <i class="fa fa-check"></i> Aceitar
                        </button>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Você não recebeu nenhum convite para participar de nenhuma <?= $termo_correto ?> ainda</p>
    <?php endif; ?>
</div>