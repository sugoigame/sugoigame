$(function () {
    document.formulario_comer_akuma.reset();
    $('#formulario_comer_akuma').on('submit', function (e) {
        e.preventDefault();

        if (verificaForm()) {
            var action = $(this).attr('action');
            var values = getFormData($(this));
            sendForm(action, values);
        }
    });
});
function sel_atk(cod) {
    var quant = parseInt(document.getElementById("quantidade_ataques").innerHTML, 10);
    quant -= 1;
    document.getElementById("quantidade_ataques").innerHTML = quant;
    var theClass1 = "radio_atk_" + cod;
    var theClass2 = "radio_buf_" + cod;
    var theClass3 = "radio_pas_" + cod;
    $('.' + theClass1).css('display', 'none');
    $('.' + theClass2).css('display', 'none');
    $('.' + theClass3).css('display', 'none');
    var select = "select_atributo_" + cod;
    document.getElementById(select).innerHTML = "Dano:";
    var dano = [];
    var calc = 10;
    for (x = 0; x < 56; x += 5) {
        calc += 90;
        if (x == 0) {
            dano[x + 1] = calc
        }
        else {
            dano[x] = calc
        }
    }
    var bonus = "bonus_value_" + cod;
    document.getElementById(bonus).value = dano[cod];
    var energiavalue = dano[cod] / 10;
    var energia = "energia_" + cod;
    document.getElementById(energia).value = energiavalue;
    select = "alcance_" + cod;
    document.getElementById(select).value = 1;
    select = "area_" + cod;
    document.getElementById(select).value = 1;
    select = "duracao_" + cod;
    document.getElementById(select).parentNode.style.display = "none";
    select = "skill-details-" + cod;
    document.getElementById(select).style.display = "block";
    if (quant == 0) {
        var theClass = [];
        var i = 0;
        for (x = 0; x < 56; x += 5) {
            if (x == 0) {
                theClass[i] = "radio_atk_1"
            } else {
                theClass[i] = "radio_atk_" + x
            }
            i += 1
        }
        for (x = 0; x < theClass.length; x++) {
            $('.' + theClass[x]).css('display', 'none');
        }
    }
}
function sel_buf(cod) {
    var quant = parseInt(document.getElementById("quantidade_buffs").innerHTML);
    quant -= 1;
    document.getElementById("quantidade_buffs").innerHTML = quant;
    var theClass1 = "radio_atk_" + cod;
    var theClass2 = "radio_buf_" + cod;
    var theClass3 = "radio_pas_" + cod;
    $('.' + theClass1).css('display', 'none');
    $('.' + theClass2).css('display', 'none');
    $('.' + theClass3).css('display', 'none');
    var select = "select_atributo_" + cod;
    var sel = document.getElementById(select).innerHTML;
    document.getElementById(select).innerHTML = "Buff " + sel;
    var dano = [];
    var duracao = [];
    var calc = 45;
    for (x = 0; x < 56; x += 5) {
        calc += 5;
        if (x == 0) {
            dano[x + 1] = calc;
            duracao[x + 1] = 1
        } else {
            dano[x] = calc;
            duracao[x] = (x / 10) + 1
        }
        if (x == 5) {
            duracao[x] = 1
        }
    }
    duracao[cod] = parseInt(duracao[cod]);
    var dur = "duracao_" + cod;
    document.getElementById(dur).value = duracao[cod];
    var bonus = "bonus_value_" + cod;
    document.getElementById(bonus).value = dano[cod];
    var oper = "add_ou_sub_" + cod;
    document.getElementById(oper).style.display = "block";
    var energiavalue = 100;
    var energia = "energia_" + cod;
    document.getElementById(energia).value = energiavalue;
    select = "alcance_" + cod;
    document.getElementById(select).value = 1;
    select = "area_" + cod;
    document.getElementById(select).value = 1;
    select = "skill-details-" + cod;
    document.getElementById(select).style.display = "block";
    select = "cooldown_" + cod;
    document.getElementById(select).parentNode.style.display = "block";
    document.getElementById(select).value = parseInt(duracao[cod], 10) * 2;
    if (quant == 0) {
        var theClass = [];
        var i = 0;
        for (x = 0; x < 51; x += 5) {
            if (x == 0) {
                theClass[i] = "radio_buf_1"
            }
            else {
                theClass[i] = "radio_buf_" + x
            }
            i += 1
        }
        for (x = 0; x < theClass.length; x++) {
            $('.' + theClass[x]).css('display', 'none');
        }
    }
}

function sel_pas(cod) {
    var quant = parseInt(document.getElementById("quantidade_passivas").innerHTML);
    quant -= 1;
    document.getElementById("quantidade_passivas").innerHTML = quant;
    var theClass1 = "radio_atk_" + cod;
    var theClass2 = "radio_buf_" + cod;
    var theClass3 = "radio_pas_" + cod;
    $('.' + theClass1).css('display', 'none');
    $('.' + theClass2).css('display', 'none');
    $('.' + theClass3).css('display', 'none');
    var select = "select_atributo_" + cod;
    var sel = document.getElementById(select).innerHTML;
    document.getElementById(select).innerHTML = "Passiva " + sel;
    var dano = [];
    var calc = 0;
    for (x = 0; x < 56; x += 5) {
        calc += 2;
        if (x == 0) {
            dano[x + 1] = calc
        } else {
            dano[x] = calc
        }
    }
    var oper = "add_sb_" + cod;
    var opervalue = document.getElementById(oper).innerHTML;
    document.getElementById(oper).innerHTML = "+" + opervalue;
    var bonus = "bonus_value_" + cod;
    document.getElementById(bonus).value = dano[cod];
    select = "alcance_" + cod;
    document.getElementById(select).parentNode.style.display = "none";
    select = "area_" + cod;
    document.getElementById(select).parentNode.style.display = "none";
    select = "energia_" + cod;
    document.getElementById(select).parentNode.style.display = "none";
    select = "duracao_" + cod;
    document.getElementById(select).parentNode.style.display = "none";
    select = "cooldown_" + cod;
    document.getElementById(select).parentNode.style.display = "none";
    select = "skill-details-" + cod;
    document.getElementById(select).style.display = "block";
    if (quant == 0) {
        var theClass = [];
        var i = 0;
        for (x = 0; x < 51; x += 5) {
            if (x == 0) {
                theClass[i] = "radio_pas_1"
            } else {
                theClass[i] = "radio_pas_" + x
            }
            i += 1
        }
        for (x = 0; x < theClass.length; x++) {
            $('.' + theClass[x]).css('display', 'none');
        }
    }
}
function abre_img_skil(cod) {
    var id = "img_skil_" + cod;
    var imgs = document.getElementById(id);
    if (imgs.style.display == "none") {
        imgs.style.display = "block"
    } else {
        imgs.style.display = "none"
    }
}
function selecionaimg(cod, img) {
    var inp = "img_selec" + cod;
    inp = document.getElementById(inp);
    inp.value = img;
    var campoimg = "img_" + cod;
    campoimg = document.getElementById(campoimg);
    campoimg.src = "Imagens/Skils/" + img + ".jpg";
    var id = "img_skil_" + cod;
    var imgs = document.getElementById(id);
    imgs.style.display = "none"
}
function atualizaBonus(sessao, mod, cod) {
    var select = "select_atributo_" + cod;
    select = document.getElementById(select).innerHTML;
    var dano = new Array;
    if (select == "Dano:") {
        var aum = 90;
        var calc = 10;
    } else if (select.substr(0, 3) == "Buf") {
        var aum = 5;
        var calc = 50;
    } else {
        var aum = 2;
        var calc = 0;
    }
    for (x = 0; x < 56; x += 5) {
        calc += aum;
        if (x == 0) {
            dano[x + 1] = calc;
        } else {
            dano[x] = calc;
        }
    }
    if (sessao == 1) {
        select = "area_" + cod;
        var mod2 = document.getElementById(select).value;
        if (mod != 1) {
            dano[cod] = (dano[cod] / mod) + (dano[cod] * 0.4);
            dano[cod] = dano[cod] * (1 / mod2);
        } else {
            dano[cod] = dano[cod] * (1 / mod2);
        }
    } else if (sessao == 2) {
        select = "alcance_" + cod;
        var mod2 = document.getElementById(select).value;
        if (mod2 != 1) {
            dano[cod] = (dano[cod] / mod2) + (dano[cod] * 0.4)
        }
        dano[cod] = dano[cod] * (1 / mod)
    }
    dano[cod] = parseInt(dano[cod], 10);
    var bonus = "bonus_value_" + cod;
    document.getElementById(bonus).value = dano[cod]
}
function verificaForm() {
    var mensagem = "Os seguintes erros foram encontrados <br>";
    var erro = true;
    if (document.getElementById("formulario_comer_akuma").nome.value.length < 5) {
        mensagem += "<br>* O nome deve conter no minimo 5 caracteres";
        erro = false
    }
    if (document.getElementById("formulario_comer_akuma").descricao.value.length == 0) {
        mensagem += "<br>* Insira uma descrição do poder dessa Akuma no Mi";
        erro = false
    }
    if (document.getElementById("quantidade_ataques").innerHTML != 0) {
        mensagem += "<br>* Você ainda tem ataques para criar.";
        erro = false
    }
    if (document.getElementById("quantidade_buffs").innerHTML != 0) {
        mensagem += "<br>* Você ainda tem buffs para criar.";
        erro = false
    }
    if (document.getElementById("quantidade_passivas").innerHTML != 0) {
        mensagem += "<br>* Você ainda tem habilidades passivas para criar.";
        erro = false
    }
    if (!erro) {
        bootbox.alert(mensagem)
    }
    return erro;
}
function verifica_nome() {
    input = document.getElementById("nome_akuma").value;
    input = removeCaracteres2(input);
    document.getElementById("nome_akuma").value = input;
    $.ajax({
        type: 'get',
        url: 'Scripts/Verificadores/verifica_cadastro_akuma.php',
        data: 'akuma=' + input,
        cache: false,
        success: function (retorno) {
            if (retorno.trim() == "1") {
                document.getElementById("img_status_nome").src = "Imagens/Icones/1.gif";
                document.getElementById("texto_status_nome").innerHTML = ""
            }
            else {
                document.getElementById("img_status_nome").src = "Imagens/Icones/0.gif";
                document.getElementById("texto_status_nome").innerHTML = "O nome informado ja esta cadastrado";
            }
        }
    });
}
