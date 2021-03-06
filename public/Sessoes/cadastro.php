<div class="panel-heading">
    Cadastro
</div>

<div class="panel-body">
    <script type="text/javascript">
        $('#form-cadastro').submit(function (event) {
            if (document.getElementById("senha2").value != document.getElementById("senha").value) {
                document.getElementById("senha2_status").src = "Imagens/Icones/0.gif";
                document.getElementById("senha2_status_fail").innerHTML = "A confirmação e a senha devem ser iguais";
                event.preventDefault();
            }
        });
    </script>
    <form id="form-cadastro" action="Scripts/Geral/cadastro.php" method="post">
        <?php if (isset($_GET["id"]) && validate_alphanumeric($_GET["id"])) : ?>
            <?php $result = $connection->run("SELECT * FROM tb_conta WHERE id_encrip = ?", "s", $_GET["id"]); ?>
            <?php if ($result->count()): ?>
                <?php $padrinho = $result->fetch_array(); ?>
                <p>
                    Você está se cadastrando com o link de recrutamento de <?= $padrinho["nome"] ?>
                </p>
                <p>
                    Sua conta será vinculada com a deste jogador e vocês poderão ganhar recompensas exclusivas.
                </p>
                <input name="padrinho" type="hidden" readonly="true"
                       value="<?= htmlspecialchars($_GET["id"], ENT_QUOTES); ?>"/>
            <?php endif; ?>
        <?php endif; ?>
        <div class="text-left">
            <h4>Informações Pessoais:</h4>

            <div class="form-group">
                <label>Seu Nome:</label>
                <input class="form-control" type="text" id="nome" name="nome" minlength="5" required/>
            </div>

            <div class="form-group">
                <label>Seu E-mail:</label>
                <input class="form-control" type="email" id="email" name="email" required/>
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input class="form-control" type="password" id="senha" name="senha" required/>
            </div>

            <div class="form-group">
                <label>Confirmar Senha:</label>
                <input class="form-control" type="password" id="senha2" name="senha2" required/>
                <img id="senha2_status" src="Imagens/Icones/3.gif">
                <span id="senha2_status_fail" class="fail"></span>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input id="contrato" name="contrato" type="checkbox" required> Ao clicar em "Cadastrar",
                        confirmo que li e concordo com a <a href="./?ses=politica" class="link_content">Política de
                            Privacidade</a> e com as <a href="./?ses=regras" class="link_content">Regras do jogo</a>.
                    </label>
                </div>
            </div>

            <button class="btn btn-success" type="submit">Cadastrar</button>
        </div>
    </form>
</div>
