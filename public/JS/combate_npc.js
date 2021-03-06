//atualiza o tabuleiro
var v = 1;
function batalha() {
    $.ajax({
        type: 'get',
        url: 'Scripts/Batalha/batalha_tabuleiro.php',
        cache: false,
        success: function (retorno) {
            var scroll = $('.fight-zone').scrollLeft();
            $("#navio_batalha").html(retorno);
            $('.fight-zone').scrollLeft(scroll);
            if ($('#botao_mover').data('move') == 5) {
                toggleTurn(v++);
            }

            if ($('#botao_atacar').length) {
                bindDefaultAction();
            }
        }
    });
}

function getUrlAtacar() {
    return 'Scripts/Batalha/batalha_atacar_npc.php';
}