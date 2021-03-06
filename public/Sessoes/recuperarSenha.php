<div class="panel-heading">
    Recuperação de senha
</div>

<script type="text/javascript">
    $(function () {
        $('#form-mudar-senha').on('submit', function (e) {
            if ($('#senha').val() != $('#senha_confirm').val()) {
                e.preventDefault();
                bootbox.alert('A confirmação de senha não é igual a senha digitada.');
            }
        });
    });
</script>

<div class="panel-body">
    <?php if (!isset($_GET["token"])): ?>
        <p>
            Informe seu email abaixo para proseguir com a recuperação da senha:
        </p>
        <form method="get" action="Scripts/Geral/recuperar_senha.php">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" required class="form-control" name="email"/>
            </div>
            <button type="submit" class="btn btn-success">Avançar</button>
        </form>
    <?php else: ?>
        <p>
            Informe uma nova senha:
        </p>
        <form id="form-mudar-senha" method="post" action="Scripts/Geral/recuperar_senha_mudar.php">
            <input type="hidden" name="token" value="<?= $_GET["token"]; ?>"/>
            <div class="form-group">
                <label>Nova senha:</label>
                <input id="senha" type="password" required class="form-control" name="senha"/>
            </div>
            <div class="form-group">
                <label>Confirmar senha:</label>
                <input id="senha_confirm" type="password" required class="form-control" name="senha_confirm"/>
            </div>
            <button type="submit" class="btn btn-success">Alterar</button>
        </form>
    <?php endif; ?>
</div>