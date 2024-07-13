function getUrlTabuleiro() {
    return "Scripts/Batalha/batalha_tabuleiro_bot.php";
}

function processaTurnoAdversario() {
    if ($("#batalha_background").length) {
        setTimeout(function () {
            sendGet("Batalha/turno_bot.php");
        }, 1000);
    }
}
