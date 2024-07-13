function getUrlTabuleiro() {
    return "Scripts/Batalha/batalha_tabuleiro.php";
}

function processaTurnoAdversario() {
    if ($("#batalha_background").length) {
        setTimeout(function () {
            sendGet("Batalha/turno_npc.php");
        }, 1000);
    }
}
