timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);

function refreshTimeout() {
    batalha();
    timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);
}

function getUrlTabuleiro() {
    const queryParams = getQueryParams();
    return (
        "Scripts/Batalha/batalha_tabuleiro_assistir.php?combate=" +
        queryParams["combate"]
    );
}

function tempo_batalha() {
    timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
    if (document.getElementById("tempo_batalha") != null) {
        var atual = document.getElementById("tempo_batalha").innerHTML;
        atual -= 1;
        document.getElementById("tempo_batalha").innerHTML = atual;
    }
}

function processaTurnoAdversario() {
    // nothing
}
