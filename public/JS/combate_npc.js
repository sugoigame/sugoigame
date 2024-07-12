//atualiza o tabuleiro
var v = 1;
$(function () {
    if ($("#turno-vez").length) {
        var turno = $("#turno-vez").val();
        setTurno(turno);

        if (turno != 1) {
            turnoNpc();
        } else {
            toggleTurn(2);
        }
    }
});
function batalha() {
    var turno = $("#turno-vez").val();
    setTurno(turno);
    $.ajax({
        type: "get",
        url: "Scripts/Batalha/batalha_tabuleiro.php",
        cache: false,
        success: function (retorno) {
            var scroll = $(".fight-zone").scrollLeft();
            $("#batalha-content").html(retorno);
            $(".fight-zone").scrollLeft(scroll);
            if ($("#botao_mover").data("move") == 5) {
                setTimeout(() => toggleTurn(v++), 30);
            }

            if ($("#botao_atacar").length) {
                bindDefaultAction();
            }
            var turno = $("#turno-vez").val();
            setTimeout(() => toggleTurn(turno), 30);

            if (turno != 1) {
                turnoNpc();
            }
        },
    });
}

function turnoNpc() {
    setTimeout(function () {
        var scroll = $(".fight-zone").scrollLeft();
        sendGet("Batalha/turno_npc.php", function () {
            $(".fight-zone").scrollLeft(scroll);
        });
    }, 1000);
}
function getUrlAtacar() {
    return "Scripts/Batalha/batalha_atacar.php";
}
