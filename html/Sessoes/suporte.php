<div class="panel-heading">
    Suporte
</div>

<div class="panel-body">
    <?= ajuda("Suporte", "Envie suas dúvidas, críticas ou sugestões para nossa equipe.") ?>
    <script type="text/javascript">
        $(function () {
            $("#form_suporte").submit(function (e) {
                e.preventDefault();
                var nome = document.getElementById("form_suporte").nome.value;
                var assunto = document.getElementById("form_suporte").assunto.value;
                var mensagem = document.getElementById("form_suporte").mensagem.value;
                var email = document.getElementById("form_suporte").email.value;
                var motivo = document.getElementById("form_suporte").motivo.value;

                var obj = {
                    nome: nome,
                    assunto: assunto,
                    mensagem: mensagem,
                    email: email,
                    motivo: motivo
                };
                var pagina = "Geral/suporte_enviar";
                sendForm(pagina, obj);
                $("#form_suporte")[0].reset();
            });
        })
    </script>
    Envie suas dúvidas ou sugestões, basta preencher o formulário abaixo:
    <form id="form_suporte" name="formulario" class="text-left">
        <div class="form-group">
            <label>Motivo:</label>
            <select class="form-control" name="motivo" required>
                <option value="Duvida">Dúvida</option>
                <option value="Sugestao">Sugestão</option>
                <option value="Reporte de Erro">Reportar erro</option>
            </select>
        </div>
        <div class="form-group">
            <label>Seu Nome:</label>
            <input type="text" name="nome" class="form-control" required minlength="5"/>
        </div>
        <div class="form-group">
            <label>Email para contato:</label>
            <input type="email" name="email" class="form-control" required/>
        </div>
        <div class="form-group">
            <label>Assunto:</label>
            <input type="text" name="assunto" class="form-control" required/>
        </div>
        <div class="form-group">
            <label>Mensagem:</label>
            <textarea type="text" name="mensagem" rows="10" class="form-control" required></textarea>
        </div>

        <button class="btn btn-danger" type="reset">Limpar</button>
        <button class="btn btn-success" type="submit">Enviar</button>
    </form>
</div>