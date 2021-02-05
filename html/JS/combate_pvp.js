timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);

function refreshTimeout() {
    if (!$('#botao_atacar').length) {
        batalha();
    }
    timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);
}

function batalha() {
    $.ajax({
        type: 'get',
        url: 'Scripts/Batalha/batalha_tabuleiro_pvp.php',
        cache: false,
        success: function (retorno) {
            retorno = retorno.trim();
            if (retorno.substr(0, 1) == "#") {
                bancandoEspertinho(retorno.substr(0, retorno.length));
            } else if (retorno.substr(0, 1) == "%") {
                loadPagina(retorno.substr(1, (retorno.length - 1)));
            } else {
                var scroll = $('.fight-zone').scrollLeft();
                var scrollRelatorio = $('#relatorio-combate-content').scrollTop();
                $("#navio_batalha").html(retorno);
                $('.fight-zone').scrollLeft(scroll);
                $('#relatorio-combate-content').scrollTop(scrollRelatorio);

                toggleTurn($('#botao_atacar').length ? 'eu' : 'ele');
                if ($('#botao_atacar').length) {
                    bindDefaultAction();
                }
            }
        }
    });
}

function tempo_batalha() {
    timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
    if (document.getElementById("tempo_batalha") != null) {
        var atual = document.getElementById("tempo_batalha").innerHTML;
        atual -= 1;
        if (atual <= 0) {
            $('#skills-modal').modal('hide');
            sendGet("Batalha/batalha_perdeuvez.php");
        }
        document.getElementById("tempo_batalha").innerHTML = atual;
    }
}

function getUrlAtacar() {
    return 'Scripts/Batalha/batalha_atacar_pvp.php';
}