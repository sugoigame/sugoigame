/*jslint white: true*/
(function ($, global) {
    'use strict';

    function addQuadro(x, y, zona) {
        var elemId = '#quadro_' + x + '_' + y;
        $(elemId)
            .removeClass('add-mergulho')
            .addClass('com-mergulho')
            .html(zona)
            .css('background', global.heatMapTransparent(zona, 1, 10, 0.5))
            .data('quadro-info', 'Zona: ' + zona);
    }

    function edicao() {
        global.unbind();

        $('#mergulho-panel').dialog("open");

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('mergulho'),
            success: function (response) {
                var i, value, elemId;

                response = JSON.parse(response);

                $('.quadro').addClass('add-mergulho');

                for (i = 0; i < response.length; i += 1) {
                    value = response[i];
                    addQuadro(value.x, value.y, value.zona);
                }
            }
        });
    }

    function add(x, y, zona) {
        $.ajax({
            url: 'server/index.php/welcome/add_mergulho',
            data: 'x=' + x + '&y=' + y + '&zona=' + zona,
            success: function () {
                addQuadro(x, y, zona);
            }
        });
    }

    function remove($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/remove_mergulho',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('com-mergulho')
                    .addClass('add-mergulho')
                    .css('background', 'transparent')
                    .html('')
                    .removeData('quadro-info');
            }
        });
    }

    $(function () {
        $('#mergulho').button().click(function () {
            edicao();
        });
        $('#mergulho-panel').dialog({
            dialogClass: "no-close",
            autoOpen: false
        });

        $(document).on('click', '.quadro.add-mergulho', function () {
            var x = $(this).data('x'),
                y = $(this).data('y'),
                zona = parseInt($('#zona-mergulho-input').val(), 10);

            add(x, y, zona);
        });

        $(document).on('click', '.quadro.com-mergulho', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            remove($quadro, x, y);
        });
    });

}(window.jQuery, window));
