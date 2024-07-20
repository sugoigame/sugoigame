/**
 * Todas as funcoes e variáveis globais do javascript ficam nesse arquivo
 */

myCoord = {
    x: parseInt($("#coord_x_navio").val(), 10),
    y: parseInt($("#coord_y_navio").val(), 10),
};

//Não sei pra que diabos existe isso, quando descobrir refatorar
cont_rota = 0;
xatual = myCoord.x;
yatual = myCoord.y;
quadro = 0;

function getQueryParams() {
    var vars = [],
        hash;
    var hashes = window.location.href
        .slice(window.location.href.indexOf("?") + 1)
        .split("&");
    for (var i = 0; i < hashes.length; i++) {
        if (hashes[i].length) {
            hash = hashes[i].split("=");
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
    }
    return vars;
}

function urldecode(url) {
    return decodeURIComponent(url.replace(/\+/g, " "));
}

function escapeString(value) {
    var entityMap = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
        "/": "&#x2F;",
        "`": "&#x60;",
        "=": "&#x3D;",
    };
    return String(value).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    });
}

function mascaraBerries(price) {
    return price.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
}

function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value =
        escape(value) +
        (exdays == null ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}
function getCookie(c_name) {
    var i,
        x,
        y,
        ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
}

function transforma_tempo(tempo) {
    var resto_segundos = tempo % 60;
    tempo -= resto_segundos;
    tempo /= 60;
    var resto_minutos = tempo % 60;
    tempo -= resto_minutos;
    tempo /= 60;
    resto_horas = tempo % 24;
    tempo -= resto_horas;
    tempo /= 24;
    string_tempo = "";
    if (tempo > 1) {
        string_tempo += tempo + " dias ";
    } else if (tempo == 1) {
        string_tempo += tempo + " dia ";
    }
    if (resto_horas < 10) {
        string_tempo += "0";
        string_tempo += resto_horas;
    } else {
        string_tempo += resto_horas;
    }
    string_tempo += ":";
    if (resto_minutos < 10) {
        string_tempo += "0";
        string_tempo += resto_minutos;
    } else {
        string_tempo += resto_minutos;
    }
    string_tempo += ":";
    if (resto_segundos < 10) {
        string_tempo += "0";
        string_tempo += resto_segundos;
    } else {
        string_tempo += resto_segundos;
    }
    return string_tempo;
}
function removeCaracteres(str) {
    er = /[^a-z0-9]/gi;
    str = str.replace(er, "");
    return str;
}
function removeCaracteres2(str) {
    er = /[^a-z0-9 ]/gi;
    str = str.replace(er, "");
    return str;
}

function background(ilha, pasta) {
    if (!pasta) {
        var hours = new Date().getHours();
        pasta = hours >= 18 || hours <= 7 ? "noite" : "dia";
    }

    $("body").css(
        "background",
        "#" +
            (pasta == "dia" ? "1d50a9" : "000269") +
            " url(Imagens/" +
            pasta +
            "/0.jpg) repeat-x top center fixed"
    );
}

var timeOuts = [];
function clearAllTimeouts() {
    for (key in timeOuts) {
        clearTimeout(timeOuts[key]);
    }
}

function getFormData($form) {
    var values = [];
    $.each($form.serializeArray(), function (i, field) {
        values.push(field.name + "=" + encodeURIComponent(field.value));
    });
    return values.join("&");
}

function ajaxError() {
    bootbox.alert({
        className: "modal-danger",
        title: "Ocorreu algum erro ao tentar se conectar com o servidor.",
        message: "Por favor atualize a página e tente novamente.",
    });
}

function proccessResponseAlert(retorno) {
    retorno = retorno.trim();

    if (retorno.substr(0, 1) == "#") {
        bancandoEspertinho(retorno.substr(1, retorno.length - 1));
        return false;
    } else if (retorno.substr(0, 1) == "!") {
        location.href = "./?ses=" + retorno.substr(1, retorno.length - 1);
        return false;
    } else if (retorno.substr(0, 1) == "%") {
        window.WorldMap.reload();
        loadPagina(retorno.substr(1, retorno.length - 1));
        return false;
    } else if (retorno.substr(0, 1) == "@") {
        window.WorldMap.reload();
        reloadPagina();
        responseAlert(retorno.substr(1, retorno.length));
        return false;
    } else if (retorno.substr(0, 1) == "|") {
        reloadPagina();
        responseAlert(retorno.substr(1, retorno.length));
        return false;
    } else if (retorno.substr(0, 1) == "?") {
        reloadPagina();
        responseAlert(retorno.substr(1, retorno.length));
        return false;
    } else if (retorno.substr(0, 1) == "-") {
        responseAlert(retorno.substr(1, retorno.length));
        reloadPagina();
        return false;
    } else if (retorno.substr(0, 1) == ":") {
        reloadPagina();
        return false;
    } else {
        return true;
    }
}

function responseAlert(msg, callback) {
    bootbox.alert({
        size: "large",
        message: msg,
        callback: callback,
    });
}

function setQueryParam(param, value) {
    var queryParams = getQueryParams();
    queryParams[param] = value;

    var paramsFromPaginaAtual = pagina_atual.split("&");

    var isset = false;
    for (var i = 1; i < paramsFromPaginaAtual.length; i++) {
        var subParams = paramsFromPaginaAtual[i].split("=");

        if (subParams[0] == param) {
            paramsFromPaginaAtual[i] = param + "=" + value;
            isset = true;
            break;
        }
    }
    if (!isset) {
        paramsFromPaginaAtual.push(param + "=" + value);
    }

    pagina_atual = paramsFromPaginaAtual.join("&");

    window.history.pushState(
        { status: "ok" },
        "One Piece Sugoi Game - " + paramsFromPaginaAtual[0],
        "?ses=" + pagina_atual
    );
}

var paginas_visualizadas = 0;
var pagina_atual = "home";
function loadPagina(pagina, callback, preventPushState) {
    pagina_atual = pagina;
    paginas_visualizadas++;
    $.ajax({
        type: "get",
        url: "pagina.php",
        data: "sessao=" + pagina,
        cache: false,
        error: ajaxError,
        success: function (retorno) {
            retorno = retorno.trim();
            clearAllTimeouts();

            loadingOut();
            if (proccessResponseAlert(retorno)) {
                // document.title = 'Sugoi Game';
                $("#tudo").html(retorno);

                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover({});
                $(".selectpicker").selectpicker({
                    noneSelectedText: "Selecione...",
                });
                if (!preventPushState) {
                    window.history.pushState(
                        { status: "ok" },
                        "One Piece Sugoi Game - " + pagina,
                        "?ses=" + pagina
                    );
                }
                myCoord = {
                    x: parseInt($("#coord_x_navio").val(), 10),
                    y: parseInt($("#coord_y_navio").val(), 10),
                };

                background(parseInt($("#ilha_atual").val(), 10));

                if (window.WorldMap) {
                    if ($("#should_show_world_map").val() == 1) {
                        window.WorldMap.load();
                    } else {
                        window.WorldMap.unload();
                    }
                }
            } else if (paginas_visualizadas == 1) {
                loadPagina("home");
                return;
            }
            verificaSeTemNav();

            if (callback) {
                callback(retorno);
            }
        },
    });
}

function reloadPagina(callback) {
    loadPagina(pagina_atual, callback);
}

function sendForm(pagina, obj, callback) {
    $("#icon_carregando").fadeIn();
    var data = "";

    if (typeof obj === "string") {
        data = obj;
    } else {
        var items = [];
        for (var i in obj) {
            items.push(i + "=" + obj[i]);
            data = items.join("&");
        }
    }

    $.ajax({
        type: "post",
        url: "Scripts/" + pagina + ".php",
        data: data,
        cache: false,
        error: ajaxError,
        success: function (retorno) {
            retorno = retorno.trim();
            $("#icon_carregando").fadeOut();

            let success = proccessResponseAlert(retorno);

            if (success && retorno.length) {
                responseAlert(retorno, function () {
                    if (callback) {
                        callback(retorno);
                    }
                });
            } else if (callback) {
                callback(retorno);
            }
            if (success) {
                reloadPagina();
            }
        },
    });
}

function loadSubSession(pagina, destination, callback) {
    $.ajax({
        type: "get",
        url: "Scripts/" + pagina,
        cache: false,
        error: ajaxError,
        success: function (retorno) {
            retorno = retorno.trim();
            clearAllTimeouts();

            loadingOut();
            if (proccessResponseAlert(retorno)) {
                // document.title = 'Sugoi Game';
                $(destination).html(retorno);

                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover({});
                $(".selectpicker").selectpicker({
                    noneSelectedText: "Selecione...",
                });
            } else if (paginas_visualizadas == 1) {
                loadPagina("home");
                return;
            }

            if (callback) {
                callback(retorno);
            }
        },
    });
}

getDisponivel = true;
function sendGet(locale, callback, showLoading = true) {
    if (getDisponivel) {
        if (showLoading) {
            $("#icon_carregando").fadeIn();
        }
        getDisponivel = false;
        $.ajax({
            type: "get",
            url: "Scripts/" + locale,
            cache: false,
            error: function () {
                getDisponivel = true;
                ajaxError();
            },
            success: function (retorno) {
                retorno = retorno.trim();
                $("#icon_carregando").fadeOut();
                getDisponivel = true;
                const success = proccessResponseAlert(retorno);

                if (retorno.length && success) {
                    responseAlert(retorno, function () {
                        if (callback) {
                            callback(retorno);
                        }
                    });
                } else if (callback) {
                    callback(retorno);
                }
                if (success) {
                    reloadPagina();
                }
            },
        });
    }
}

berriesDisponivel = true;
function atualizaBerries(callback) {
    if (berriesDisponivel) {
        berriesDisponivel = false;
        $.ajax({
            type: "get",
            url: "berries.php",
            cache: false,
            error: function () {
                berriesDisponivel = true;
                ajaxError();
            },
            success: function (retorno) {
                berriesDisponivel = true;
                if (retorno.substr(0, 1) == "#") {
                    bancandoEspertinho(retorno.substr(1, retorno.length - 1));
                } else {
                    $("#span_berries").html(retorno);
                }

                if (callback) {
                    callback(retorno);
                }
            },
        });
    }
}

goldDisponivel = true;
function atualizaGold(callback) {
    if (goldDisponivel) {
        goldDisponivel = false;
        $.ajax({
            type: "get",
            url: "gold.php",
            cache: false,
            error: function () {
                goldDisponivel = true;
                ajaxError();
            },
            success: function (retorno) {
                goldDisponivel = true;
                if (retorno.substr(0, 1) == "#") {
                    bancandoEspertinho(retorno.substr(1, retorno.length - 1));
                } else {
                    $("#span_gold").html(retorno);
                }

                if (callback) {
                    callback(retorno);
                }
            },
        });
    }
}

function bancandoEspertinho(msg) {
    bootbox.alert({
        className: "modal-danger",
        title: "Bancando o espertinho?",
        message:
            '<img src="Imagens/erro.jpg" /><br /><br />' +
            msg +
            "<br /><br />Estamos de olho em você!",
    });
}

function mostraimgs(id) {
    var imgs = document.getElementById(id);
    if (imgs.style.display == "none") {
        imgs.style.display = "block";
    } else {
        imgs.style.display = "none";
    }
}

function geraImgsSkill(list, input, img, totalImg) {
    var listElem = $("#" + list),
        inputElem = $("#" + input),
        imgElem = $("#" + img);

    listElem.toggle();

    if (!listElem.children().length) {
        var imgs = [];
        for (var x = 1; x <= totalImg; x++) {
            imgs.push(x);
        }
        imgs.forEach(function (x) {
            listElem.append(
                $("<IMG>")
                    .attr("src", "Imagens/Skils/" + x + ".jpg")
                    .css("width", "50px")
                    .css("height", "50px")
                    .on("click", function () {
                        imgElem.attr("src", $(this).attr("src"));
                        inputElem.val(x);
                        listElem.toggle();
                    })
            );
        });
    }
}

function selectimg(img, campoimg, inputimg, imgs) {
    document.getElementById(inputimg).value = img;
    document.getElementById(campoimg).src = "Imagens/Skils/" + img + ".jpg";
    document.getElementById(imgs).style.display = "none";
}

var loading = false;

function loadingIn() {
    if (!loading) {
        loading = true;
        $("#icon_carregando").fadeIn();
    }
}

function loadingOut() {
    $("#icon_carregando").stop().fadeOut();
    loading = false;
}

window.audioEnable = true;

function loadAudioConfig() {
    if (window.localStorage) {
        window.audioEnable = JSON.parse(
            window.localStorage.getItem("audioEnable")
        );
        if (window.audioEnable === null) {
            window.audioEnable = true;
        }
    }
    setAudioEnableButtonAparence();
}

function setAudioEnable(value) {
    window.audioEnable = value;
    if (window.localStorage) {
        window.localStorage.setItem("audioEnable", value);
    }
}

function toggleAudioEnable() {
    setAudioEnable(!audioEnable);
}

function playAudio(audio, element) {
    if (!window.audioEnable) {
        return;
    }
    if (element) {
        document.getElementById(audio).play();
    } else {
        var sound = new Audio(audio);
        sound.play();
    }
}

function pauseAudio(elementId) {
    if (!audioEnable) {
        return;
    }
    document.getElementById(elementId).pause();
}

function setAudioEnableButtonAparence() {
    var content = window.audioEnable
        ? '<i class="fa fa-volume-up" aria-hidden="true"></i>'
        : '<i class="fa fa-volume-off" aria-hidden="true"></i>';
    $("#audio-toggle").html(content);
}

function enviarNotificacao(title, config) {
    if (config.sound) {
        playAudio(config.sound);
    }

    if (!config.vibrate) {
        config.vibrate = [200, 100, 200];
    }

    try {
        if (window.Notification) {
            if (Notification.permission === "granted") {
                // If it's okay let's create a notification
                var notification = new Notification(title, config);

                notification.onclick = function () {
                    window.focus();
                    this.close();
                };
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission(function (permission) {
                    if (permission === "granted") {
                        enviarNotificacao(title, config);
                    }
                });
            }
        }
    } catch (e) {
        console.log("Este navegador não suporta notificacoes");
    }
}

function loadMapaCartografo(marId, mapId) {
    $.ajax({
        type: "get",
        url: "Scripts/Cartografo/mapa_cartografo.php",
        data: {
            mar: marId,
            cod: mapId,
        },
        cache: false,
        beforeSend: function () {
            $(".menu-cartografo li.active").removeClass("active");
            $(".menu-cartografo li a#" + marId)
                .parent()
                .addClass("active");

            $("#mapa_cartografo_oceano")
                .css(
                    "background",
                    'url("Imagens/Mapa/Mapa_Cartografo/carregando.jpg") center center'
                )
                .css("height", (marId > 4 ? 800 : 500) + "px")
                .css("width", "100%")
                .html("");
        },
        success: function (retorno) {
            if (retorno.substr(0, 1) == "#") {
                bancandoEspertinho(retorno.substr(1, retorno.length));
            } else {
                $("#mapa_cartografo_oceano")
                    .css(
                        "background",
                        'url("Imagens/Mapa/Mapa_Cartografo/' + marId + '.jpg")'
                    )
                    .css("height", "auto")
                    .css("width", "1000px")
                    .html(retorno);

                $('[data-toggle="tooltip"]').tooltip();
            }
        },
    });
}

function verificaSeTemNav() {
    var tmp = $("#destino_sec").html();
    if (typeof tmp !== "undefined" && tmp.length) {
        iniciaNav();
    }
}

var navTimeout = null;

function iniciaNav() {
    if (!navTimeout) {
        navTimeout = setTimeout(function () {
            navTimeoutFunc();
        }, 1000);
    }
}

var gameTitle = document.title;
function navTimeoutFunc() {
    var tmp = parseInt($("#destino_sec").html(), 10);
    tmp -= 1;
    $("#destino").html(transforma_tempo(tmp));
    $("#destino_sec").html(tmp);
    if (tmp <= 0) {
        finalizaNav();
        navTimeout = null;
        document.title = gameTitle;
    } else {
        navTimeout = setTimeout(function () {
            navTimeoutFunc();
        }, 1000);
        document.title = "[" + transforma_tempo(tmp) + "] " + gameTitle;
    }
}

function finalizaNav() {
    $.ajax({
        type: "get",
        url: "Scripts/Mapa/verifica_nav.php",
        cache: false,
        success: function (retorno) {
            retorno = JSON.parse(retorno);
            if (retorno.redirect) {
                loadPagina(retorno.redirect);
            } else if (retorno.error) {
                bancandoEspertinho(retorno.error);
            } else {
                if (retorno.navegacao) {
                    $("#destino").html(transforma_tempo(retorno.navegacao));
                    $("#destino_sec").html(retorno.navegacao);
                    iniciaNav();
                } else {
                    $("#destino").html(" ");
                    enviarNotificacao("Você chegou ao seu destino!", {
                        body: "Se navio concluiu a rota designada.",
                        icon: "https://sugoigame.com.br/Imagens/favicon.png",
                    });
                }

                if (
                    pagina_atual.startsWith("oceano") ||
                    $("#destino_ilha").html() != retorno.ilha
                ) {
                    reloadPagina();
                }

                background(retorno.mapa.ilha);

                $("#destino_ilha").html(retorno.ilha);
                $("#destino_mar").html(retorno.mar);
                $("#location").html(retorno.coord);
            }
        },
    });
}

function tracar_rota(x, y) {
    xatual = parseInt(xatual);
    yatual = parseInt(yatual);
    var td = "tracar_rota_c_";
    td += quadro;
    var coor = "coor_" + x + "_" + y;
    if (quadro < 25) {
        if (x == xatual + 1 || x == xatual - 1) {
            if (y == yatual || y == yatual + 1 || y == yatual - 1) {
                document.getElementById(td).value = x + "_" + y;
                xatual = x;
                yatual = y;
                quadro += 1;
                document.getElementById(coor).style.background = "#ff0000";
                document.getElementById(coor).style.opacity = "0.5";
                var l = x;
                var n = y;
                n = 101 - n;
                n -= 50;
                if (l > 100) {
                    l -= 200;
                }
                text = document.getElementById("text_coor").value;
                text += "=>" + l + "ºL, " + n + "ºN ";
                document.getElementById("text_coor").value = text;
                document.getElementById("erro").innerHTML = "";
            } else {
                document.getElementById("erro").innerHTML =
                    "Voce so pode se movimentar um quadro por vez";
            }
        } else if (x == xatual) {
            if (y == yatual + 1 || y == yatual - 1) {
                document.getElementById(td).value = x + "_" + y;
                xatual = x;
                yatual = y;
                quadro += 1;
                document.getElementById(coor).style.background = "#ff0000";
                document.getElementById(coor).style.opacity = "0.5";
                var l = x;
                var n = y;
                n = 101 - n;
                n -= 50;
                if (l > 100) {
                    l -= 200;
                }
                text = document.getElementById("text_coor").value;
                text += "=>" + l + "ºL, " + n + "ºN ";
                document.getElementById("text_coor").value = text;
                document.getElementById("erro").innerHTML = "";
            } else {
                document.getElementById("erro").innerHTML =
                    "Voce so pode se movimentar um quadro por vez";
            }
        } else {
            document.getElementById("erro").innerHTML =
                "Voce so pode se movimentar um quadro por vez";
        }
    } else {
        document.getElementById("erro").innerHTML =
            "A rota deve ser de no máximo 25 quadros";
    }
}

function remover_rota() {
    if (quadro > 0) {
        var td = "tracar_rota_c_";
        td += quadro - 1;
        var id = document.getElementById(td).value;
        var coor = "coor_";
        coor += id;
        document.getElementById(td).value = "";
        document.getElementById(coor).style.background = "transparent";
        document.getElementById(coor).style.opacity = "1";
        quadro -= 1;
        td = "tracar_rota_c_";
        td += quadro - 1;
        var id = document.getElementById(td).value;
        text = document.getElementById("text_coor").value;
        i = 1;
        cont = 0;
        for (x = text.length - 1; x > 0; x--) {
            if (text.substr(x, 1) != "=") {
                i += 1;
                var newtext = text.substr(0, text.length - i);
            } else {
                cont += 1;
                if (cont == 1) {
                    x = 0;
                }
            }
        }
        document.getElementById("text_coor").value = newtext;
        if (id == "") {
            coordx = myCoord.x;
            coordy = myCoord.y;
        } else {
            cod = 0;
            for (x = 0; x < id.length; x++) {
                if (id.substr(x, 1) == "_") {
                    cod = 1;
                } else {
                    if (cod == 0) {
                        var coordx = id.substr(x, 1);
                        cod = 2;
                    } else if (cod == 2) {
                        coordx += id.substr(x, 1);
                    } else if (cod == 1) {
                        var coordy = id.substr(x, 1);
                        cod = 3;
                    } else if (cod == 3) {
                        coordy += id.substr(x, 1);
                    }
                }
            }
        }
        xatual = coordx;
        yatual = coordy;
    }
}

popupAmigavel = false;
function verifica_nova_msg() {
    setTimeout("verifica_nova_msg()", 10000);
    $.ajax({
        type: "get",
        url: "Scripts/Denden/verifica_nova_msg.php",
        cache: false,
        success: function (retorno) {
            retorno = JSON.parse(retorno);
            if (!retorno.strlen) return;

            if (retorno.inCombate) {
                if (pagina_atual != "combate") {
                    loadPagina("combate");
                }
            }
            if (!retorno.msgBoxClear) {
                setTimeout(function () {
                    n_puru(retorno.inCombate);
                }, 1000);
            }

            if (retorno.amigavel) {
                if (!popupAmigavel) {
                    popupAmigavel = true;
                    bootbox.confirm(
                        "A tripulação " +
                            retorno.amigavel +
                            " está te desafiando para uma disputa amigável, Deseja aceitar?",
                        callbackDesafio
                    );
                }
            }

            if (retorno.coliseu) {
                if (
                    pagina_atual != "coliseu" &&
                    pagina_atual != "localizadorCasual" &&
                    pagina_atual != "localizadorCompetitivo"
                ) {
                    enviarNotificacao(
                        "Um adversário foi encontrado no Coliseu!",
                        {
                            body: "Prepare-se para batalha!",
                            icon: "https://sugoigame.com.br/Imagens/favicon.png",
                            sound: "Sons/tada.mp3",
                        }
                    );
                    loadPagina("coliseu");
                }
            }
            if (retorno.torneio) {
                sendGet(
                    "Mapa/mapa_atacar.php?id=" + retorno.torneio + "&tipo=" + 8
                );
            }
        },
    });
}

function callbackDesafio(v) {
    popupAmigavel = false;
    if (v) {
        $.ajax({
            type: "get",
            url: "Scripts/Batalha/aceitar_desafio.php",
            cache: false,
            success: function (retorno) {
                if (retorno.substr(0, 1) == "#") {
                    bancandoEspertinho(retorno.substr(1, retorno.length));
                } else {
                    loadPagina("combate");
                }
            },
        });
    } else {
        $.ajax({
            type: "get",
            url: "Scripts/Batalha/recusar_desafio.php",
            cache: false,
        });
    }
}

function n_puru(hidePopover) {
    $("#denden_mushi").attr("src", "Imagens/Icones/Denden_0.png").popover({
        html: true,
        title: "Puru Puru Puru Puru...",
        content:
            '<div style="width: 145px;" class="text-center">Mensagem não lida!</div>',
        placement: "bottom",
        animation: true,
    });

    if (!hidePopover) {
        $("#denden_mushi").popover("show");
    }

    playAudio("toque_nova_msg", true);
}

/**
 * Animation
 * Classe responsavel por reproduzir animacoes de skills/golpes dos personagens
 */
(function () {
    var animations = [];

    $.ajax({
        url: "Imagens/Skils/Animacoes/Animations.json",
        success: function (response) {
            animations = response;
        },
    });

    window.Animation = function (name) {
        this.name = name;
        this.animation = null;

        for (var i = 1; i < animations.length; i++) {
            if (animations[i].name == name) {
                this.animation = animations[i];
                break;
            }
        }
        if (!this.animation) {
            return;
        }

        this.background1 =
            "Imagens/Skils/Animacoes/" + this.animation.animation1Name + ".png";
        this.background2 =
            "Imagens/Skils/Animacoes/" + this.animation.animation2Name + ".png";

        var self = this;
        if (this.animation.animation1Name) {
            this.img1 = new Image();
            this.img1.src = this.background1;
            this.img1Load = false;
            this.img1.onload = function () {
                self.img1Load = true;
                self.requestPlayFrame();
            };
        } else {
            this.img1Load = true;
        }
        if (this.animation.animation2Name) {
            this.img2 = new Image();
            this.img2.src = this.background2;
            this.img2Load = false;
            this.img2.onload = function () {
                self.img2Load = true;
                self.requestPlayFrame();
            };
        } else {
            this.img2Load = true;
        }

        this.playing = false;
        this.playRequested = false;
    };

    Animation.prototype.play = function (options) {
        if (!this.animation) {
            return;
        }
        options = options || {};

        this.top = options.top || 100;
        this.left = options.left || 100;
        this.delay = options.delay || 70;
        this.scale = options.scale || 0.5;
        this.callback = options.callback;
        this.frame = 0;

        this.$elem = $("<DIV>").css("position", "relative");

        this.$conteiner = $("<DIV>")
            .css("position", options.fixed ? "fixed" : "absolute")
            .css("z-index", 5000)
            .css("top", this.top - (192 / 2) * this.scale)
            .css("left", this.left - (192 / 2) * this.scale)
            .css("-moz-transform", "scale(" + this.scale + ")")
            .css("-o-transform", "scale(" + this.scale + ")")
            .css("-webkit-transform", "scale(" + this.scale + ")")
            .css("transform", "scale(" + this.scale + ")")
            .append(this.$elem);

        $("body").append(this.$conteiner);

        this.playRequested = true;
        this.requestPlayFrame();
    };

    Animation.prototype.requestPlayFrame = function () {
        if (
            this.playRequested &&
            this.img1Load &&
            this.img2Load &&
            !this.playing
        ) {
            this.playFrame();
        }
    };

    Animation.prototype.playFrame = function () {
        this.playing = true;
        this.$elem.empty();

        var frame = this.animation.frames[this.frame];

        for (var i = 0; i < frame.length; i++) {
            var item = frame[i];
            if (item[0] >= 0) {
                var tile = item[0];
                var background =
                    tile > 99 ? this.background2 : this.background1;

                var hue =
                    tile > 99
                        ? this.animation.animation2Hue
                        : this.animation.animation1Hue;

                if (tile > 99) {
                    tile -= 100;
                }
                var displaceLeft = (tile % 5) * 192;
                var displaceTop = Math.floor(tile / 5) * 192;

                this.$elem.append(
                    $("<DIV>")
                        .css("display", "block")
                        .css("position", "absolute")
                        .css("width", "192px")
                        .css("height", "192px")
                        .css("background", 'url("' + background + '")')
                        .css(
                            "background-position",
                            -displaceLeft + "px " + -displaceTop + "px"
                        )
                        .css("filter", "hue-rotate(" + hue + "deg)")
                        .css("opacity", item[6] / 255)
                        .css("left", item[1] / 2)
                        .css("top", item[2] / 2)
                        .css("-moz-transform", "scale(" + item[3] / 100 + ")")
                        .css("-o-transform", "scale(" + item[3] / 100 + ")")
                        .css(
                            "-webkit-transform",
                            "scale(" + item[3] / 100 + ")"
                        )
                        .css("transform", "scale(" + item[3] / 100 + ")")
                        .css("-moz-transform", "rotate(" + item[4] + "deg)")
                        .css("-o-transform", "rotate(" + item[4] + "deg)")
                        .css("-webkit-transform", "rotate(" + item[4] + "deg)")
                        .css("transform", "rotate(" + item[4] + "deg)")
                );
            }
        }

        for (i = 0; i < this.animation.timings.length; i++) {
            var timing = this.animation.timings[i];
            if (timing.frame == this.frame && timing.se) {
                playAudio("Sons/se/" + timing.se.name + ".ogg");
            }
        }

        this.frame++;
        var self = this;
        if (this.animation.frames[this.frame]) {
            setTimeout(function () {
                self.playFrame();
            }, this.delay);
        } else {
            setTimeout(function () {
                self.$conteiner.remove();
                self.playing = false;
                if (self.callback) {
                    self.callback();
                }
            }, this.delay);
        }
    };
})();

/**
 * WorldMap
 * Responsavel pelo carregamento do world map no background da sessao ativa
 */
(function () {
    var worldMapVisible = false;
    var oceanGame = null;
    var oceanWS = null;

    window.WorldMap = {
        setOceanGame(game) {
            oceanGame = game;
        },
        destroyOceanGame() {
            if (oceanGame) {
                oceanGame.destroy();
                oceanGame = null;
            }
        },
        setOceanWS(ws) {
            if (oceanWS) {
                oceanWS.close();
            }
            oceanWS = ws;
        },
        destroyOceanWS() {
            if (oceanWS) {
                oceanWS.close();
                oceanWS = null;
            }
        },
        reload() {
            window.WorldMap.unload();
            window.WorldMap.load();
        },
        load() {
            if (!worldMapVisible) {
                worldMapVisible = true;
                $.ajax({
                    type: "get",
                    url: "world_map.php",
                    cache: false,
                    error: ajaxError,
                    success: function (retorno) {
                        if (worldMapVisible) {
                            retorno = retorno.trim();
                            window.WorldMap.destroyOceanGame();
                            window.WorldMap.destroyOceanWS();

                            $("#world-map-background").html(retorno);

                            $('[data-toggle="tooltip"]').tooltip();
                            $('[data-toggle="popover"]').popover({});
                            $(".selectpicker").selectpicker({
                                noneSelectedText: "Selecione...",
                            });
                        }
                    },
                });
            }
        },
        unload() {
            if (worldMapVisible) {
                worldMapVisible = false;
                $("#world-map-background").html("");
            }
        },
    };
})();
