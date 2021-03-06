/*jslint white: true*/
(function ($, global) {
    'use strict';

    function getDirectionName(direcao) {
        direcao = parseInt(direcao, 10);
        switch (direcao) {
            case 0:
                return "Norte";
            case 1:
                return "Nordeste";
            case 2:
                return "Leste";
            case 3:
                return "Sudeste";
            case 4:
                return "Sul";
            case 5:
                return "Sudoeste";
            case 6:
                return "Oeste";
            case 7:
                return "Noroeste";
            default:
                return "";
        }
    }

    function addQuadroCorrente(x, y, intensidade, direcao) {
        direcao = parseInt(direcao, 10);
        var elemId = '#quadro_' + x + '_' + y;
        $(elemId)
            .removeClass('add-corrente')
            .addClass('com-corrente')
            .css('background', global.heatMapTransparent(intensidade, 3, 20, 0.5))
            .data('quadro-info', 'Intensidade: ' + intensidade + ', Direção: ' + getDirectionName(direcao))
            .append(
                $('<IMG>')
                    .attr('src', '../../Imagens/Oceano/Correntes/C.png')
                    .css('transform', 'rotate(' + (direcao * 45) + 'deg)')
            );
    }

    function edicaoCorrente() {
        global.unbind();

        $('#corrente-panel').dialog("open");

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('corrente'),
            success: function (response) {
                var i, value, elemId;

                response = JSON.parse(response);

                $('.quadro').addClass('add-corrente');

                for (i = 0; i < response.length; i += 1) {
                    value = response[i];
                    addQuadroCorrente(value.x, value.y, value.intensidade, value.direcao);
                }
            }
        });
    }

    function addCorrente(x, y, intensidade, direcao) {
        $.ajax({
            url: 'server/index.php/welcome/add_corrente',
            data: 'x=' + x + '&y=' + y + '&intensidade=' + intensidade + '&direcao=' + direcao,
            success: function () {
                addQuadroCorrente(x, y, intensidade, direcao);
            }
        });
    }

    function removeCorrente($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/remove_corrente',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('com-corrente')
                    .addClass('add-corrente')
                    .css('background', 'transparent')
                    .removeData('quadro-info')
                    .html('');
            }
        });
    }

    $(function () {
        $('#corrente').button().click(function () {
            edicaoCorrente();
        });
        $('#corrente-panel').dialog({
            dialogClass: "no-close",
            autoOpen: false
        });

        $(document).on('click', '.quadro.add-corrente', function () {
            var x = $(this).data('x'),
                y = $(this).data('y'),
                intensidade = parseInt($('#intensidade-corrente-input').val(), 10),
                direcao = parseInt($('#direcao-corrente-input').val(), 10);

            addCorrente(x, y, intensidade, direcao);
        });

        $(document).on('click', '.quadro.com-corrente', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            removeCorrente($quadro, x, y);
        });
    });

}(window.jQuery, window));
