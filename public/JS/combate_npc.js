//atualiza o tabuleiro
var v = 1;
$(function () {
    toggleTurn(v);
});
function batalha() {
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
        },
    });
}

function getUrlAtacar() {
    return "Scripts/Batalha/batalha_atacar_npc.php";
}
