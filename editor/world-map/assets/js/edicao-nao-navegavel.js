(function ($, global) {
    'use strict';

    function edicaoNaoNavegavel() {
        global.unbind();

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('nao_navegavel'),
            success: function (response) {
                var i, value, elemId;

                response = JSON.parse(response);

                $('.quadro').addClass('add-nao-navegavel');

                for (i = 0; i < response.length; i += 1) {
                    value = response[i];
                    elemId = '#quadro_' + value.x + '_' + value.y;
                    $(elemId)
                        .removeClass('add-nao-navegavel')
                        .addClass('com-nao-navegavel');
                }
            }
        });
    }

    var queue = [];
    var queueing = false;

    function setNaoNavegavel($quadro, x, y) {
        if (!queueing) {
            queueing = true;
            setNaoNavegavelAjax($quadro, x, y);
        } else {
            queue.push({
                m: setNaoNavegavelAjax,
                q: $quadro,
                x: x,
                y: y
            });
        }
    }

    function setNaoNavegavelAjax($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/add_nao_navegavel',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('add-nao-navegavel')
                    .addClass('com-nao-navegavel');

                if (queue.length) {
                    var q = queue.shift();
                    q.m(q.q, q.x, q.y);
                } else {
                    queueing = false;
                }
            }
        });
    }

    function setNavegavel($quadro, x, y) {
        if (!queueing) {
            queueing = true;
            setNavegavelAjax($quadro, x, y);
        } else {
            queue.push({
                m: setNavegavelAjax,
                q: $quadro,
                x: x,
                y: y
            });
        }
    }

    function setNavegavelAjax($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/remove_nao_navegavel',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('com-nao-navegavel')
                    .addClass('add-nao-navegavel');

                if (queue.length) {
                    var q = queue.shift();
                    q.m(q.q, q.x, q.y);
                } else {
                    queueing = false;
                }
            }
        });
    }

    $(function () {
        $('#nao-navegavel').button().click(function () {
            edicaoNaoNavegavel();
        });

        $(document).on('click', '.quadro.add-nao-navegavel', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            setNaoNavegavel($quadro, x, y);
        });

        $(document).on('click', '.quadro.com-nao-navegavel', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            setNavegavel($quadro, x, y);
        });
    });

}(window.jQuery, window));
