timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);

function refreshTimeout() {
    batalha();
    timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);
}

function batalha() {
    var queryParams = getQueryParams();
    $.ajax({
        type: 'get',
        url: 'Scripts/Batalha/batalha_tabuleiro_assistir.php?combate=' + queryParams['combate'],
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

                toggleTurn($('#vez-combate').val() == 1 ? 'eu' : 'ele');
            }
        }
    });
}
function tempo_batalha() {
    timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
    if (document.getElementById("tempo_batalha") != null) {
        var atual = document.getElementById("tempo_batalha").innerHTML;
        atual -= 1;
        document.getElementById("tempo_batalha").innerHTML = atual;
    }
}