<div class="panel-heading">
    Quase lá...
</div>

<div class="panel-body">
    <p>
        Seu cadastro foi efetuado com sucesso!
    </p>
    <p>
        Um email foi enviando para endereço informado contendo um código de ativação, insira-o no campo
        abaixo, ou clique no link contido no email para ativar sua conta e começar a jogar.
    </p>
    <form method="get" action="Scripts/Geral/ativar.php">
        <div class="form-group">
            <label>Email:</label>
            <input type="email" required class="form-control" name="email"/>
        </div>
        <div class="form-group">
            <label>Código de ativação:</label>
            <input type="text" required class="form-control" name="cod"/>
        </div>
        <button type="submit" class="btn btn-success">Ativar</button>
    </form>
</div>
