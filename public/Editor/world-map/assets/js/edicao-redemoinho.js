/*jslint white: true*/
(function ($, global) {
    'use strict';

    function addQuadro(x, y, intensidade) {
        var elemId = '#quadro_' + x + '_' + y;
        $(elemId)
            .removeClass('add-redemoinho')
            .addClass('com-redemoinho')
            .css('background', global.heatMapTransparent(intensidade, 1, 100, 0.5))
            .data('quadro-info', 'Intensidade: ' + intensidade);
    }

    function edicao() {
        global.unbind();

        $('#redemoinho-panel').dialog("open");

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('redemoinho'),
            success: function (response) {
                var i, value, elemId;

                response = JSON.parse(response);

                $('.quadro').addClass('add-redemoinho');

                for (i = 0; i < response.length; i += 1) {
                    value = response[i];
                    addQuadro(value.x, value.y, value.intensidade);
                }
            }
        });
    }

    function add(x, y, intensidade) {
        $.ajax({
            url: 'server/index.php/welcome/add_redemoinho',
            data: 'x=' + x + '&y=' + y + '&intensidade=' + intensidade,
            success: function () {
                addQuadro(x, y, intensidade);
            }
        });
    }

    function remove($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/remove_redemoinho',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('com-redemoinho')
                    .addClass('add-redemoinho')
                    .css('background', 'transparent')
                    .removeData('quadro-info');
            }
        });
    }

    $(function () {
        $('#redemoinho').button().click(function () {
            edicao();
        });
        $('#redemoinho-panel').dialog({
            dialogClass: "no-close",
            autoOpen: false
        });

        $(document).on('click', '.quadro.add-redemoinho', function () {
            var x = $(this).data('x'),
                y = $(this).data('y'),
                intensidade = parseInt($('#intensidade-redemoinho-input').val(), 10);

            add(x, y, intensidade);
        });

        $(document).on('click', '.quadro.com-redemoinho', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            remove($quadro, x, y);
        });
    });

}(window.jQuery, window));
