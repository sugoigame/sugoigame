<div class="panel-heading">
    Minha Conta
</div>

<div class="panel-body">
    <?= ajuda("Minha Conta", "Mantenha suas informações sempre atualizadas.") ?>
    <script type="text/javascript">
        $(function() {
            $("#conta_editar").submit(function(e) {
                e.preventDefault();
                verifica_ed();
            });
        });

        function verifica_ed() {
            var msg = '';
            var erro = false;
            if (document.getElementById("senha_old").value.length != 0) {
                if (document.getElementById("senha_new").value.length < 5) {
                    msg += "Sua nova senha deve conter no minimo 5 caracteres\n";
                    erro = true;
                }
                if (document.getElementById("senha_new_2").value != document.getElementById("senha_new").value) {
                    msg += "A confirmação de senha não confere com a senha original\n";
                    erro = true;
                }
            }
            if (erro) {
                bootbox.alert(msg);
            } else {
                var campo_nome = $("#nome").val();
                var campo_old = $("#senha_old").val();
                var campo_new = $("#senha_new").val();
                obj = {
                    nome: campo_nome,
                    senha_antiga: campo_old,
                    senha_nova: campo_new
                };
                pagina = "Geral/conta_editar"
                sendForm(pagina, obj);
            }
        }
    </script>
    <form id="conta_editar" class="text-left">
        <div class="row">
            <div class="form-group col-xs-6 col-md-4">
                <label>Email:</label>
                <input class="form-control" value="<?= $userDetails->conta["email"]; ?>" disabled>
            </div>
            <div class="form-group col-xs-6 col-md-4">
                <label>Nome:</label>
                <input class="form-control" id="nome" name="nome" type="text" value="<?= $userDetails->conta["nome"]; ?>" required>
            </div>
            <div class="form-group col-xs-6 col-md-4">
                <label>Senha atual:</label>
                <input class="form-control" id="senha_old" name="senha_antiga" type="password" />
            </div>
            <div class="form-group col-xs-6 col-md-4">
            </div>
            <div class="form-group col-xs-6 col-md-4">
                <label>Nova senha:</label>
                <input class="form-control" id="senha_new" name="senha_nova" type="password" />
            </div>
            <div class="form-group col-xs-6 col-md-4">
                <label>Confirmar senha:</label>
                <input class="form-control" id="senha_new_2" name="senha_nova_2" type="password" />
            </div>
            <div class="form-group col-xs-6 col-md-6 text-center" style="display:flex;align-items: center;">
                <div>Se você trocar sua senha, terá de fazer login novamente.</div>
            </div>
            <div class="form-group col-xs-6 col-md-6">
                <button class="btn btn-success btn-block" type="submit">Salvar alterações</button>
            </div>
        </div>
    </form>
</div>