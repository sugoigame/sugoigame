//atualiza o tabuleiro
$(function () {
    if ($('#turno-vez').length) {
        var turno = $('#turno-vez').val();
        setTurno(turno);

        if (turno != 1) {
            turnoBot();
        } else {
            toggleTurn(2);
        }
    }
});

function batalha() {
    var turno = $('#turno-vez').val();
    setTurno(turno);
    $.ajax({
        type: 'get',
        url: 'Scripts/Batalha/batalha_tabuleiro_bot.php',
        cache: false,
        success: function (retorno) {
            var scroll = $('.fight-zone').scrollLeft();
            $("#navio_batalha").html(retorno);
            $('.fight-zone').scrollLeft(scroll);
            if ($('#turno-vez').length) {

                if ($('#botao_atacar').length) {
                    bindDefaultAction();
                }

                var turno = $('#turno-vez').val();
                toggleTurn(turno);

                if (turno != 1) {
                    turnoBot();
                }
            }
        }
    });
}

function turnoBot() {
    setTimeout(function () {
        var scroll = $('.fight-zone').scrollLeft();
        sendGet('Batalha/turno_bot.php', function () {
            $('.fight-zone').scrollLeft(scroll);
        });
    }, 1000);
}

function getUrlAtacar() {
    return 'Scripts/Batalha/batalha_atacar_bot.php';
}