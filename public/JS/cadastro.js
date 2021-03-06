$(function () {
    var plus = 0;
    var selecionado_antes = "#1";
    var ses = 1;
    $('#navegador_voltar').click(function () {
        $('#sessao_' + ses).fadeOut(0);
        ses--;
        $('#sessao_' + ses).fadeIn();
        if (ses == 1) {
            $('#navegador_voltar').fadeOut(0);
        }
        $('#navegador_avancar').fadeIn();
    });

    $('#navegador_avancar').click(function () {
        $('#sessao_' + ses).fadeOut(0);
        ses++;
        $('#sessao_' + ses).fadeIn();
        if (ses == 4) {
            $('#navegador_avancar').fadeOut(0);
        }
        $('#navegador_voltar').fadeIn();
    });
    $('#apelido').blur(verifica_apelido);
    $('#nome').blur(verifica_nome);
    $('#email').blur(verifica_email);
    $('#login').blur(verifica_login);
    $('#senha').blur(verifica_senha);
    $('#senha2').blur(verifica_senha2);
    $('#capitao').blur(verifica_capitao);

    $('#cria-trip-form').submit(verifica_cadastro);


    $('.capitao-selectable-img').click(function () {
        var img = $(this).data("img");
        $('.capitao-selectable-img').css('border', 'none');
        $(this).css('border', '4px solid #870000');
        $("#img_capitao").attr("src", "Imagens/Personagens/Big/" + img + "(0).jpg");
        $("#icon_capitao").val(img);
    });

});

function verifica_capitao() {
    input = document.getElementById("capitao").value;
    input = removeCaracteres2(input);
    document.getElementById("capitao").value = input;
    if (document.getElementById("capitao").value.length < 3) {
        document.getElementById("capitao_status").src = "Imagens/Icones/0.gif";
        document.getElementById("capitao_status_fail").innerHTML = "O nome do capitão deve conter no minimo 3 caracteres";
    }
    else {
        $.ajax({
            type: 'get',
            data: 'capitao=' + input,
            url: 'Scripts/Verificadores/verifica_cadastro_capitao.php',
            cache: false,
            success: function (retorno) {
                if (retorno == 1) {
                    //ok
                    document.getElementById("capitao_status").src = "Imagens/Icones/1.gif";
                    document.getElementById("capitao_status_fail").innerHTML = "";
                }
                else {
                    //fail
                    document.getElementById("capitao_status").src = "Imagens/Icones/0.gif";
                    document.getElementById("capitao_status_fail").innerHTML = "O nome do capitão informado já está cadastrado";
                }
            }
        });
    }
}

function verifica_senha2() {
    if (document.getElementById("senha2").value != document.getElementById("senha").value) {
        document.getElementById("senha2_status").src = "Imagens/Icones/0.gif";
        document.getElementById("senha2_status_fail").innerHTML = "A confirmação e a senha devem ser iguais";
    }
    else {
        document.getElementById("senha2_status").src = "Imagens/Icones/1.gif";
        document.getElementById("senha2_status_fail").innerHTML = "";
    }
}

function verifica_senha() {
    if (document.getElementById("senha").value.length < 5) {
        document.getElementById("senha_status").src = "Imagens/Icones/0.gif";
        document.getElementById("senha_status_fail").innerHTML = "A senha deve conter no minimo 5 caracteres";
    }
    else {
        document.getElementById("senha_status").src = "Imagens/Icones/1.gif";
        document.getElementById("senha_status_fail").innerHTML = "";
    }
}

function verifica_login() {
    input = document.getElementById("login").value;
    input = removeCaracteres(input);
    document.getElementById("login").value = input;
    if (document.getElementById("login").value.length < 5) {
        document.getElementById("login_status").src = "Imagens/Icones/0.gif";
        document.getElementById("login_status_fail").innerHTML = "O Login deve conter no minimo 5 caracteres";
    }
    else {
        //ajax
        $.ajax({
            type: 'get',
            data: 'login=' + input,
            url: 'Scripts/Verificadores/verifica_cadastro_login.php',
            cache: false,
            success: function (retorno) {
                if (retorno == 1) {
                    //ok
                    document.getElementById("login_status").src = "Imagens/Icones/1.gif";
                    document.getElementById("login_status_fail").innerHTML = "";
                }
                else {
                    //fail
                    document.getElementById("login_status").src = "Imagens/Icones/0.gif";
                    document.getElementById("login_status_fail").innerHTML = "O login informado já está cadastrado";
                }
            }
        });
    }
}

function verifica_apelido() {
    input = document.getElementById("apelido").value;
    input = removeCaracteres2(input);
    document.getElementById("apelido").value = input;
    if (document.getElementById("apelido").value.length < 3) {
        document.getElementById("apelido_status").src = "Imagens/Icones/0.gif";
        document.getElementById("apelido_status_fail").innerHTML = "O apelido da tripulação deve conter no minimo 3 caracteres";
    }
    else {
        $.ajax({
            type: 'get',
            data: 'apelido=' + input,
            url: 'Scripts/Verificadores/verifica_cadastro_apelido.php',
            cache: false,
            success: function (retorno) {
                if (retorno == 1) {
                    //ok
                    document.getElementById("apelido_status").src = "Imagens/Icones/1.gif";
                    document.getElementById("apelido_status_fail").innerHTML = "";
                }
                else {
                    //fail
                    document.getElementById("apelido_status").src = "Imagens/Icones/0.gif";
                    document.getElementById("apelido_status_fail").innerHTML = "O apelido informado já está cadastrado";
                }
            }
        });
    }
}
function verifica_nome() {
    input = document.getElementById("nome").value;
    input = removeCaracteres2(input);
    document.getElementById("nome").value = input;
    if (document.getElementById("nome").value.length < 5) {
        document.getElementById("nome_status").src = "Imagens/Icones/0.gif";
        document.getElementById("nome_status_fail").innerHTML = "O nome deve conter no minimo 5 caracteres";
    }
    else {
        document.getElementById("nome_status").src = "Imagens/Icones/1.gif";
        document.getElementById("nome_status_fail").innerHTML = "";
    }
}
function verifica_email() {
    var mai = document.getElementById("email").value;
    var ermai = /^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/;
    //verifica se o email tem um formato valido
    if (!(ermai.test(mai)) || mai.length == 0) {
        document.getElementById("email_status").src = "Imagens/Icones/0.gif";
        document.getElementById("email_status_fail").innerHTML = "Email inválido";
    }
    else {
        $.ajax({
            type: 'get',
            data: 'email=' + mai,
            url: 'Scripts/Verificadores/verifica_cadastro_email.php',
            cache: false,
            success: function (retorno) {
                if (retorno == 1) {
                    //ok
                    document.getElementById("email_status").src = "Imagens/Icones/1.gif";
                    document.getElementById("email_status_fail").innerHTML = "";
                }
                else {
                    //fail
                    document.getElementById("email_status").src = "Imagens/Icones/0.gif";
                    document.getElementById("email_status_fail").innerHTML = "O email informado já está cadastrado";
                }
            }
        });
    }
}

function verifica_cadastro(event) {
    var mensagem = "Os seguintes erros foram encontrados <br>";
    var erro = false;

    if (document.getElementById("apelido").value.length < 3) {
        mensagem += "<br>* O apelido da tripulação deve conter no minimo 3 caracteres";
        erro = true;
    }

    if (document.getElementById("capitao").value.length < 3) {
        mensagem += "<br>* O nome do capitão deve conter no minimo 3 caracteres";
        erro = true;
    }

    if (document.getElementById("icon_capitao").value.length == 0) {
        mensagem += "<br>* Você deve selecionar uma imagem para seu capitão";
        erro = true;
    }
    if (erro) {
        event.preventDefault();
        bootbox.alert({
            className: 'modal-danger',
            title: 'Os seguintes erros foram encontrados',
            message: mensagem
        });
    }
}