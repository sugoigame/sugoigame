$(function () {
    $(document).on("mouseenter", ".personagem", function () {
        if ($(document).width() >= 1000) {
            var cod = $(this).data("cod");
            var x = $(this).data("x");
            var top = $(this).position().top;
            var left = $(this).position().left + 130;
            if (left + 500 > $("#navio_batalha").width()) {
                left -= 500;
            }
            if (x >= 5) {
                top += 200;
            }
            $("#personagem-info-" + cod)
                .css("top", top)
                .css("left", left)
                .removeClass("hidden");
        }
    });
    
    $(document).on("mouseleave", ".personagem", function () {
        $(".personagem-info").addClass("hidden");
    });

    $(document)
        .unbind("keypress")
        .on("keypress", function (e) {
            if (e.key.toLowerCase() == "a") {
                $("#botao_atacar").click();
                bindShortcutsToPers();
            } else if (e.key.toLowerCase() == "m") {
                $("#botao_mover").click();
                bindShortcutsToPers();
            } else {
                $("#key-code-" + e.key).click();
            }
        });
});

function getKeyCode(index) {
    if (index < 9) {
        return index + 1;
    } else if (index == 9) {
        return 0;
    } else {
        switch (index) {
            case 10:
                return "q";
            case 11:
                return "w";
            case 12:
                return "e";
            case 13:
                return "r";
            case 14:
                return "t";
            default:
                return null;
        }
    }
}

function bindShortcutsToPers() {
    $(".personagem.aliado").each(function (index) {
        var key = getKeyCode(index);
        $(this).prepend(
            $("<SPAN>")
                .attr("id", "key-code-" + key)
                .html(key)
                .css("position", "absolute")
                .css("background", "rgba(0,0,0,0.7)")
                .css("padding", "3px 7px")
                .css("border-radius", "5px")
        );
    });
}

var turno = "eu";

function setTurno(t) {
    turno = t;
}

function bindDefaultAction() {
    if (parseInt($("#moves_remain").val(), 10)) {
        mover();
    } else {
        atacar();
    }
}

function toggleTurn(vez) {
    if (turno != vez) {
        turno = vez;

        if ($("#botao_atacar").length) {
            bindDefaultAction();
        }

        const relatorio = $(
            "#relatorio-combate-content .relatorio-meta-data"
        ).data("log");

        const origem = $(
            '.selecionavel[data-cod="' + relatorio?.personagem?.cod + '"]'
        );
        const origemOffset = origem.offset();
        if (origemOffset) {
            origemOffset.top += origem.height() / 2 - 20;
            origemOffset.left += origem.width() / 2 - 20;
        }

        const animacao =
            relatorio?.habilidade?.animacao || "Atingir fisicamente";

        if (relatorio?.habilidade) {
            for (let consequencia of relatorio.consequencias) {
                const target = $(
                    consequencia.quadro.x == "npc"
                        ? "#npc"
                        : "#" +
                              consequencia.quadro.x +
                              "_" +
                              consequencia.quadro.y
                );
                const offset = target.offset();
                if (offset) {
                    offset.top += target.height() / 2 - 20;
                    offset.left += target.width() / 2 - 20;
                }

                const animation = new Animation(animacao);

                $("body").append(
                    $("<DIV>")
                        .css("position", "absolute")
                        .css("top", origemOffset.top)
                        .css("left", origemOffset.left)
                        .css("z-index", 1000000)
                        .html(
                            '<img src="Imagens/Skils/' +
                                relatorio.habilidade.icone +
                                '.jpg" width="35px" />'
                        )
                        .animate(
                            {
                                top: offset.top,
                                left: offset.left,
                            },
                            500,
                            function () {
                                $(this).remove();
                                animation.play({
                                    top: offset.top + 20,
                                    left: offset.left + 20,
                                    callback: function () {
                                        if (
                                            consequencia.dano ||
                                            typeof consequencia.cura !==
                                                "undefined"
                                        ) {
                                            const $danoDiv = $("<DIV>")
                                                .css("position", "absolute")
                                                .css("top", offset.top)
                                                .css("left", offset.left)
                                                .css("color", "#E00")
                                                .css("font-weight", "800")
                                                .css("padding", "1px 3px")
                                                .css("border-radius", "3px")
                                                .css("z-index", "1000")
                                                .css(
                                                    "text-shadow",
                                                    "1px 0 #000, -1px 0 #000, 0 1px #000, 0 -1px #000, 1px 1px #000,  -1px -1px #000, 1px -1px #000, -1px 1px #000"
                                                )
                                                .html(
                                                    typeof consequencia.cura !==
                                                        "undefined"
                                                        ? consequencia.cura
                                                        : consequencia.dano
                                                              ?.dano
                                                )
                                                .animate(
                                                    {
                                                        top: offset.top - 20,
                                                    },
                                                    3000,
                                                    function () {
                                                        $(this).remove();
                                                    }
                                                );

                                            if (consequencia.dano) {
                                                if (consequencia.dano.critou) {
                                                    $danoDiv
                                                        .addClass("critico")
                                                        .css("color", "#fff");
                                                }

                                                if (
                                                    consequencia.dano.bloqueou
                                                ) {
                                                    $danoDiv
                                                        .removeClass("critico")
                                                        .addClass("bloqueio")
                                                        .css("color", "#fff");
                                                }

                                                if (
                                                    consequencia.dano.esquivou
                                                ) {
                                                    $danoDiv
                                                        .addClass("esquiva")
                                                        .css(
                                                            "font-weight",
                                                            "normal"
                                                        )
                                                        .css(
                                                            "font-size",
                                                            "10px"
                                                        )
                                                        .css("color", "#fff")
                                                        .css(
                                                            "padding",
                                                            "1px 1px"
                                                        )
                                                        .html("esquivou");
                                                }
                                            } else {
                                                $danoDiv.css("color", "#0F0");
                                            }

                                            $("body").append($danoDiv);
                                        }
                                    },
                                });
                            }
                        )
                );
            }
        }
    }
}

function passar_vez() {
    var locale = "Batalha/batalha_passar.php";
    bootbox.confirm("Passar a vez?", function (result) {
        if (result) {
            sendGet(locale);
        }
    });
}

function unbindClicks() {
    $(".td-selecao").unbind("click");
    $("#npc").unbind("click");
}

function removeSelectors() {
    removeSelectorsElem($(".td-selecao"));
    removeSelectorsElem($("#npc"));
}

function addSelectorsPersonagem(color, $elem) {
    return addSelectors($elem || $(".personagem:not(.inimigo)"), color);
}

function removeSelectorsPersonagem() {
    removeSelectorsElem($(".personagem"));
}

function unbindClicksPersonagem() {
    $(".personagem").unbind("click");
}

function removeSelectorsElem($elem) {
    $elem
        .css("background", "transparent")
        .css("cursor", "auto")
        .removeClass("personagem-selecionavel");
}

function addSelectors($elem, color) {
    return $elem
        .css("background", color)
        .css("cursor", "pointer")
        .addClass("personagem-selecionavel");
}

var quadros_area = "";

function mover() {
    unbindClicks();
    removeSelectors();

    addSelectorsPersonagem("rgba(0,200,200,0.5)").click(function () {
        moveCom(this);
        atacarWithOne($(this));
    });

    quadros_area = "";
}

function moveCom(pers) {
    removeSelectorsPersonagem();
    unbindClicksPersonagem();

    addSelectors($(pers), "rgba(255,255,0,0.5");

    var perm = pers.id.split("_");
    var permx = parseInt(perm[0], 10);
    var permy = parseInt(perm[1], 10);
    var quadro;
    var alcance = parseInt($("#moves_remain").val(), 10);
    var x;
    var y;

    if (
        $(pers).hasClass("efeito-IMOBILIZACAO") ||
        $(pers).hasClass("efeito-ATORDOAMENTO")
    ) {
        return;
    }

    for (var dirX = -1; dirX <= 1; dirX++) {
        for (var dirY = -1; dirY <= 1; dirY++) {
            if (dirX != 0 || dirY != 0) {
                for (var i = 1; i <= alcance; i++) {
                    x = permx + i * dirX;
                    y = permy + i * dirY;
                    quadro = "#" + x + "_" + y;
                    if ($(quadro).length && !$(quadro).hasClass("personagem")) {
                        addSelectors($(quadro), "rgba(0,50,50,0.7");
                        $(quadro).click(function () {
                            movePara(this, pers.id);
                        });
                    } else if ($(quadro).length) {
                        i = alcance + 1;
                    } else {
                        i = alcance + 1;
                    }
                }
            }
        }
    }
}

function movePara(quadro, origem) {
    var quadro = quadro.id;
    var pers = document.getElementsByClassName(origem)[0].id;
    loadingIn();
    $.ajax({
        type: "get",
        url:
            "Scripts/Batalha/batalha_mover.php?quadro=" +
            quadro +
            "&pers=" +
            pers,
        cache: false,
        success: function (retorno) {
            retorno = retorno.trim();
            loadingOut();
            batalha();
            if (retorno.substr(0, 1) == "#") {
                bancandoEspertinho(retorno.substr(0, retorno.length));
            }
        },
    });
}

//Ataques
function atacar($elem) {
    unbindClicks();
    removeSelectors();

    addSelectorsPersonagem("rgba(255,100,100,0.5)", $elem).click(function () {
        ataqueCom(this);
    });
    quadros_area = "";
}

function atacarWithOne($elem) {
    if ($(elem).hasClass("efeito-ATORDOAMENTO")) {
        return;
    }
    addSelectorsPersonagem("rgba(255,100,100,0.5)", $elem).click(function () {
        unbindClicks();
        removeSelectors();
        ataqueCom(this);
    });
    quadros_area = "";
}

function ataqueCom(pers) {
    removeSelectorsPersonagem();
    unbindClicksPersonagem();

    addSelectors($(pers), "rgba(255,255,0,0.5");

    pers = document.getElementsByClassName(pers.id)[0].id;
    loadingIn();
    $.ajax({
        type: "get",
        url: "Scripts/Batalha/batalha_skils.php",
        data: "cod=" + pers,
        cache: false,
        success: function (retorno) {
            loadingOut();
            retorno = retorno.trim();
            if (retorno.substr(0, 1) == "#") {
                bancandoEspertinho(retorno.substr(0, retorno.length));
            } else {
                $("#skills-personagem").html(retorno);
                $('[data-toggle="popover"]').popover({});
                $("#skills-modal").modal();
            }
        },
    });
    bindDefaultAction();
}

function cancelaskil() {
    atacar();
}

//usa a skil selecionada
function usaSkil(cod, pers, alcance, tipo, area) {
    alcance = parseInt(alcance);

    removeSelectorsPersonagem();
    unbindClicksPersonagem();

    var perm = document.getElementById(pers).className.split("_");
    var permx = parseInt(perm[0], 10);
    var permy = parseInt(perm[1], 10);

    for (var x = -1; x <= 1; x++) {
        for (var y = -1; y <= 1; y++) {
            if (x != 0 || y != 0) {
                runDirectionAtachingAttackUntilPers(
                    cod,
                    pers,
                    tipo,
                    alcance,
                    area,
                    permx,
                    permy,
                    x,
                    y
                );
            }
        }
    }
}

function runDirectionAtachingAttackUntilPers(
    cod,
    pers,
    tipo,
    alcance,
    area,
    startX,
    startY,
    dirX,
    dirY
) {
    var quadro;
    var x;
    var y;
    for (
        var i = tipo == 1 || tipo == 4 || tipo == 7 ? 1 : 0;
        i <= alcance;
        i++
    ) {
        x = startX + i * dirX;
        y = startY + i * dirY;
        quadro = "#" + x + "_" + y;
        if (
            $(quadro).length &&
            (!$(quadro).hasClass("personagem") || (x == startX && y == startY))
        ) {
            addSelectors($(quadro), "rgba(255,0,0,0.7)");
            $(quadro)
                .unbind("click")
                .click(function () {
                    atacaquadro(this, cod, pers, tipo, area);
                });
        } else if ($(quadro).length) {
            addSelectors($(quadro), "rgba(255,0,0,0.7)");
            $(quadro)
                .unbind("click")
                .click(function () {
                    atacaquadro(this, cod, pers, tipo, area);
                });
            i = alcance + 1;
        } else {
            i = alcance + 1;
        }
        if (x < 0) {
            addSelectors($("#npc"), "rgba(255,0,0,0.7)");
            $("#npc")
                .unbind("click")
                .click(function () {
                    atacaquadro(this, cod, pers, tipo, area);
                });
        }
    }
}

function atacaquadro(quadro, cod, pers, tipo, area) {
    unbindClicks();
    if (area == 1 || quadro.id == "npc") {
        removeSelectors();
        loadingIn();
        quadro = quadro.id;
        if (quadros_area != "") {
            quadro = quadros_area + quadro;
        }
        $.ajax({
            type: "post",
            url: getUrlAtacar(),
            data:
                "cod_skil=" +
                cod +
                "&pers=" +
                pers +
                "&tipo=" +
                tipo +
                "&quadro=" +
                quadro,
            cache: false,
            success: function (retorno) {
                retorno = retorno.trim();
                loadingOut();
                batalha();
                if (retorno.substr(0, 1) == "#") {
                    bancandoEspertinho(retorno.substr(0, retorno.length));
                } else {
                    quadros_area = "";
                }
            },
        });
    } else {
        quadros_area += quadro.id + ";";

        removeSelectors();
        unbindClicks();

        var perm = quadro.id.split("_");
        var permx = parseInt(perm[0], 10);
        var permy = parseInt(perm[1], 10);

        for (var x = -1; x <= 1; x++) {
            for (var y = -1; y <= 1; y++) {
                if (x != 0 || y != 0) {
                    runDirectionAtachingAttackUntilPers(
                        cod,
                        pers,
                        tipo,
                        1,
                        area - 1,
                        permx,
                        permy,
                        x,
                        y
                    );
                }
            }
        }

        if (quadros_area != "") {
            var selected = quadros_area.split(";");
            for (var i = 0; i < selected.length; i++) {
                quadro = $("#" + selected[i]);
                addSelectors(quadro, "rgba(0,0,255,0.7)");
                quadro.unbind("click");
            }
        }
    }
}

function desistir() {
    bootbox.confirm(
        "Tem certeza que deseja desistir desse combate?",
        function (result) {
            if (result) {
                sendGet("Batalha/batalha_desistir.php");
            }
        }
    );
}

function relatorioCombate() {
    $("#modal-relatorio-combate-content").html(
        $("#relatorio-combate-content").html()
    );

    $("#modal-relatorio-combate").modal();
}
