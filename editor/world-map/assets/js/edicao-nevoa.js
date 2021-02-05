(function ($, global) {
    'use strict';

    function edicaoNevoa() {
        global.unbind();

        $('#nevoa-panel').dialog("open");

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('nevoa'),
            success: function (response) {
                var i, value, elemId, intensidade;

                response = JSON.parse(response);

                $('.quadro').addClass('add-nevoa');

                for (i = 0; i < response.length; i += 1) {
                    value = response[i];
                    elemId = '#quadro_' + value.x + '_' + value.y;
                    intensidade = parseInt(value.info, 10);
                    $(elemId)
                        .removeClass('add-nevoa')
                        .addClass('com-nevoa')
                        .css('background', global.heatMapTransparent(intensidade, 50, 100, 0.5))
                        .data('quadro-info', 'Intensidade: ' + intensidade);
                }
            }
        });
    }

    function addNevoa($quadro, x, y, intensidade) {
        $.ajax({
            url: 'server/index.php/welcome/add_nevoa',
            data: 'x=' + x + '&y=' + y + '&intensidade=' + intensidade,
            success: function () {
                $quadro
                    .removeClass('add-nevoa')
                    .addClass('com-nevoa')
                    .css('background', global.heatMapTransparent(intensidade, 50, 100, 0.5))
                    .data('quadro-info', 'Intensidade: ' + intensidade);
            }
        });
    }

    function removeNevoa($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/remove_nevoa',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('com-nevoa')
                    .addClass('add-nevoa')
                    .css('background', 'transparent')
                    .removeData('quadro-info');
            }
        });
    }

    $(function () {
        $('#nevoa').button().click(function () {
            edicaoNevoa();
        });
        $('#nevoa-panel').dialog({
            dialogClass: "no-close",
            autoOpen: false
        });

        $(document).on('click', '.quadro.add-nevoa', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y'),
                intensidade = parseInt($('#intensidade-nevoa-input').val(), 10);

            addNevoa($quadro, x, y, intensidade);
        });

        $(document).on('click', '.quadro.com-nevoa', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            removeNevoa($quadro, x, y);
        });
    });

}(window.jQuery, window));
