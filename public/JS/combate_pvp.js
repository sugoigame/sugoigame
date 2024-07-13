timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);

function refreshTimeout() {
    if (!$("#botao_atacar").length) {
        batalha();
    }
    timeOuts["batalha"] = setTimeout("refreshTimeout()", 3000);
}

function tempo_batalha() {
    timeOuts["tempo_batalha"] = setTimeout("tempo_batalha()", 1000);
    if (document.getElementById("tempo_batalha") != null) {
        let atual = document.getElementById("tempo_batalha").innerHTML;
        atual -= 1;
        if (atual <= 0) {
            $("#skills-modal").modal("hide");
            sendGet("Batalha/batalha_perdeuvez.php");
        }
        document.getElementById("tempo_batalha").innerHTML = atual;
    }
}

function getUrlTabuleiro() {
    return "Scripts/Batalha/batalha_tabuleiro_pvp.php";
}

function podeTerTurnoAutomatico() {
    return false;
}
