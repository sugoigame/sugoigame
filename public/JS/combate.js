$(function () {
    $(document).on("mouseenter", ".personagem", function () {
        hoverPersonagem(this);
    });
    $(document).on("taphold", ".personagem", function () {
        hoverPersonagem(this);
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

    checkTurno();
    updateBotaoTurnoAutomatico();
});

function checkTurno() {
    setTimeout(() => {
        toggleTurn($("#botao_atacar").length ? "eu" : "ele");
        if ($("#botao_atacar").length) {
            bindDefaultAction();
            checkTurnoAutomatico();
        } else {
            turnoAdversario();
        }
    }, 100);
}

function hoverPersonagem(pers) {
    const cod = $(pers).data("cod");

    let top = $(pers).offset().top;
    let left = $(pers).offset().left + 140;

    const elem = $("#personagem-info-" + cod).removeClass("hidden");
    if (!elem) {
        return;
    }

    const height = elem.height();
    const width = elem.width();

    const totalHeight = top + height;
    const totalWidth = left + width;
    const windowHeight = $(window).height();
    const windowWidth = $(window).width() - 70;
    if (totalWidth >= windowWidth) {
        left = $(pers).offset().left - width - 70;
    }
    if (totalHeight >= windowHeight) {
        top -= totalHeight - windowHeight;
    }
    elem.css("top", top).css("left", left);
}

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
        const key = getKeyCode(index);
        $(this).prepend(
            $("<SPAN>")
                .attr("id", "key-code-" + key)
                .html(key)
                .css("position", "absolute")
                .css("background", "rgba(0,0,0,0.7)")
                .css("padding", "3px 7px")
                .css("border-radius", "5px")
                .css("color", "white")
        );
    });
}

window.turno = null;

function setTurno(t) {
    window.turno = t;
}

function bindDefaultAction() {
    if (parseInt($("#moves_remain").val(), 10)) {
        mover();
    } else {
        atacar();
    }
}

function toggleTurn(vez) {
    if (window.turno != vez) {
        window.turno = vez;

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

        if (relatorio?.habilidade && window.ultimoRelatorioId != relatorio.id) {
            window.ultimoRelatorioId = relatorio.id;
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
                } else {
                    continue;
                }

                const animation = new Animation(animacao);

                const nomeSkill = $("<DIV>")
                    .css("position", "absolute")
                    .css("z-index", 1000000)
                    .css("background", "white")
                    .css("padding", "4px 8px")
                    .css("border", "1px solid #000")
                    .css("color", "#000")
                    .css("border-radius", "5px")
                    .html(relatorio.habilidade.nome);

                $("body").append(nomeSkill);

                nomeSkill
                    .css("top", origem.offset().top - 10)
                    .css("left", origemOffset.left - nomeSkill.width() / 2 + 10)
                    .animate(
                        {
                            top: origem.offset().top - 25,
                        },
                        2000,
                        function () {
                            $(this).remove();
                        }
                    );

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
    bootbox.confirm("Passar a vez?", function (result) {
        if (result) {
            sendGet("Batalha/batalha_passar.php");
        }
    });
}

function unbindClicks($elem) {
    ($elem || $(".td-selecao")).unbind("click");
    $("#npc").unbind("click");
}

function removeSelectors($elem) {
    removeSelectorsElem($elem || $(".td-selecao"));
    removeSelectorsElem($("#npc"));
}

function addSelectorsPersonagem(color, $elem) {
    return addSelectors($elem || $(".personagem:not(.inimigo)"), color);
}

function removeSelectorsPersonagem(pers) {
    removeSelectorsElem($(pers) || $(".personagem"));
}

function unbindClicksPersonagem(pers) {
    ($(pers) || $(".personagem")).unbind("click");
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
    unbindClicks();
    removeSelectors($(".td-selecao:not(.personagem)"));

    addSelectorsPersonagem("rgba(0,200,200,0.5)").click(function () {
        moveCom(this);
    });
    unbindClicks($(pers));
    addSelectorsPersonagem("rgba(255,100,100,0.5)", $(pers)).click(function () {
        ataqueCom(this);
    });

    const perm = pers.id.split("_");
    const permx = parseInt(perm[0], 10);
    const permy = parseInt(perm[1], 10);
    const alcance = parseInt($("#moves_remain").val(), 10);

    if (
        $(pers).hasClass("efeito-IMOBILIZACAO") ||
        $(pers).hasClass("efeito-ATORDOAMENTO")
    ) {
        return;
    }

    const grid = [];
    for (let y = 0; y < 20; y++) {
        grid[y] = [];
        for (let x = 0; x < 10; x++) {
            const quadro = "#" + x + "_" + y;
            grid[y][x] = $(quadro).hasClass("personagem") ? 1 : 0;
        }
    }

    for (let x = permx - alcance; x <= permx + alcance; x++) {
        for (let y = permy - alcance; y <= permy + alcance; y++) {
            const quadro = "#" + x + "_" + y;
            if (
                x >= 0 &&
                x < 10 &&
                y >= 0 &&
                y < 20 &&
                !$(quadro).hasClass("personagem") &&
                (x != permx || y != permy)
            ) {
                const easystar = new EasyStar.js();
                easystar.setGrid(grid);
                easystar.setAcceptableTiles([0]);
                easystar.enableDiagonals();
                easystar.findPath(permx, permy, x, y, (path) => {
                    if (path && path.length - 1 <= alcance) {
                        addSelectors($(quadro), "rgba(0,50,50,0.7");
                        $(quadro).click(function () {
                            movePara(path.slice(1), pers.id);
                        });
                    }
                });
                easystar.calculate();
            }
        }
    }
}

function movePara(path, origem) {
    const pers = document.getElementsByClassName(origem)[0].id;
    loadingIn();
    $.ajax({
        type: "get",
        url:
            "Scripts/Batalha/batalha_mover.php?quadro=" +
            path.map((it) => it.x + "_" + it.y).join(";") +
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
    if ($($elem).hasClass("efeito-ATORDOAMENTO")) {
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
    removeSelectorsPersonagem(pers);
    unbindClicksPersonagem(pers);

    addSelectors($(pers), "rgba(255,255,0,0.5");
    bindDefaultAction();

    showHabilidades(pers);
}

function showHabilidades(pers) {
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
    for (let i = 1; i <= alcance; i++) {
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
            url: "Scripts/Batalha/batalha_atacar.php",
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

function batalha() {
    $.ajax({
        type: "get",
        url: getUrlTabuleiro(),
        cache: false,
        success: function (retorno) {
            retorno = retorno.trim();
            if (retorno.substr(0, 1) == "#") {
                bancandoEspertinho(retorno.substr(0, retorno.length));
            } else if (retorno.substr(0, 1) == "%") {
                loadPagina(retorno.substr(1, retorno.length - 1));
            } else {
                const scrollRelatorio = $(
                    "#relatorio-combate-content"
                ).scrollTop();
                $("#batalha-content").html(retorno);
                $("#relatorio-combate-content").scrollTop(scrollRelatorio);

                checkTurno();
            }
        },
    });
}

function turnoAdversario() {
    if ($("#batalha_background").length) {
        addSelectorsPersonagem("rgba(0,0,0,0)").click(function () {
            showHabilidades(this);
        });

        timeOuts["turnoAdversario"] = setTimeout(function () {
            sendGet("Batalha/turno_adversario.php", null, false);
        }, 500);
    }
}

function toggleTurnoAutomatico() {
    sessionStorage.setItem(
        "turnoAutomatico",
        sessionStorage.getItem("turnoAutomatico") == "true" ? "false" : "true"
    );
    checkTurnoAutomatico();
}

function checkTurnoAutomatico() {
    const turnoAutomaticoAtivo =
        sessionStorage.getItem("turnoAutomatico") == "true";

    if (turnoAutomaticoAtivo) {
        setTimeout(() => turnoAutomatico(), 100);
    }
    updateBotaoTurnoAutomatico();
}

function updateBotaoTurnoAutomatico() {
    if (podeTerTurnoAutomatico()) {
        const turnoAutomaticoAtivo =
            sessionStorage.getItem("turnoAutomatico") == "true";

        if (turnoAutomaticoAtivo) {
            $("#botao_turno_automatico")
                .removeClass("btn-primary")
                .addClass("btn-warning");
            $("#botao_turno_automatico i")
                .removeClass("fa-pause")
                .addClass("fa-refresh");
        } else {
            $("#botao_turno_automatico")
                .removeClass("btn-warning")
                .addClass("btn-primary");
            $("#botao_turno_automatico i")
                .removeClass("fa-refresh")
                .addClass("fa-pause");
        }
    } else {
        $("#botao_turno_automatico").addClass("hidden");
    }
}

function turnoAutomatico() {
    if (
        podeTerTurnoAutomatico() &&
        $("#batalha_background").length &&
        $("#botao_atacar").length &&
        sessionStorage.getItem("turnoAutomatico") == "true"
    ) {
        timeOuts["turnoAutomatico"] = sendGet(
            "Batalha/turno_automatico.php",
            null,
            false
        );
    }
}
