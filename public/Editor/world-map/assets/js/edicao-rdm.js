/*jslint white: true*/
(function ($, global) {
    'use strict';

    function addQuadro(x, y, zona) {
        var elemId = '#quadro_' + x + '_' + y;
        $(elemId)
            .removeClass('add-rdm')
            .addClass('com-rdm')
            .html(zona)
            .css('background', global.heatMapTransparent(zona, 0, 30, 0.5))
            .data('quadro-info', 'Zona: ' + zona);
    }

    function updateRdmList() {
        var zona = $("#zona-rdm-input").val();
        $.ajax({
            url: 'server/index.php/welcome/get_rdm_zona',
            data: 'zona=' + zona,
            success: function (response) {
                $('#rdm-list').html('');
                var lista = JSON.parse(response),
                    i;

                for (i = 0; i < lista.length; i += 1) {
                    $('#rdm-list').append('<div>' +
                        lista[i].nome + ', ' + lista[i].chance + '% ' +
                        '<a href="#" data-rdm-id="' + lista[i].rdm_id + '"  data-zona="' + zona + '" class="remove-rdm-zona">remove</a>' +
                        '</div>');
                }
            }
        });
    }

    function edicao() {
        global.unbind();

        $('#rdm-panel').dialog("open");

        updateRdmList();

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('rdm'),
            success: function (response) {
                var i, value, elemId;

                response = JSON.parse(response);

                $('.quadro').addClass('add-rdm');

                for (i = 0; i < response.length; i += 1) {
                    value = response[i];
                    addQuadro(value.x, value.y, value.zona);
                }
            }
        });
    }

    function add(x, y, zona) {
        $.ajax({
            url: 'server/index.php/welcome/add_zona_rdm',
            data: 'x=' + x + '&y=' + y + '&zona=' + zona,
            success: function () {
                addQuadro(x, y, zona);
            }
        });
    }

    function remove($quadro, x, y) {
        $.ajax({
            url: 'server/index.php/welcome/remove_zona_rdm',
            data: 'x=' + x + '&y=' + y,
            success: function () {
                $quadro
                    .removeClass('com-rdm')
                    .addClass('add-rdm')
                    .css('background', 'transparent')
                    .html('')
                    .removeData('quadro-info');
            }
        });
    }

    $(function () {
        $('#rdm').button().click(function () {
            edicao();
        });
        $('#rdm-panel').dialog({
            dialogClass: "no-close",
            autoOpen: false
        });
        $('#select-rdm-panel').dialog({
            autoOpen: false
        });

        $(document).on('click', '.quadro.add-rdm', function () {
            var x = $(this).data('x'),
                y = $(this).data('y'),
                zona = parseInt($('#zona-rdm-input').val(), 10);

            add(x, y, zona);
        });

        $(document).on('click', '.quadro.com-rdm', function () {
            var $quadro = $(this),
                x = $quadro.data('x'),
                y = $quadro.data('y');

            remove($quadro, x, y);
        });

        $(document).on('click', '.remove-rdm-zona', function () {
            var zona = $(this).data('zona'),
                rdm = $(this).data('rdm-id');

            $.ajax({
                url: 'server/index.php/welcome/remove_rdm_da_zona',
                data: 'zona=' + zona + '&rdm_id=' + rdm,
                success: function () {
                    updateRdmList();
                }
            });
        });

        $(document).on('click', '#add-rdm-a-zona', function () {
            $('#select-rdm-panel').dialog("open");

            $.ajax({
                url: 'server/index.php/welcome/get_rdms',
                success: function (response) {
                    $('#select-rdm-list').html('');
                    var lista = JSON.parse(response),
                        i;

                    for (i = 0; i < lista.length; i += 1) {
                        $('#select-rdm-list').append('<div>' +
                            lista[i].nome + ': <input id="chance-' + lista[i].rdm_id + '" type="text" class="spinner" min="1" max="100"/> ' +
                            '<a href="#" data-rdm-id="' + lista[i].rdm_id + '" class="add-rdm-to-zona">add</a>' +
                            '</div>');
                    }

                    $('.spinner').spinner();
                }
            });
        });

        $('#zona-rdm-input').on("selectmenuchange", function (event, ui) {
            updateRdmList();
        });
        $(document).on("click", '.add-rdm-to-zona', function (event, ui) {
            var zona = $("#zona-rdm-input").val(),
                rdm = $(this).data('rdm-id'),
                chance = $("#chance-" + rdm).val();

            $.ajax({
                url: 'server/index.php/welcome/add_rdm_a_zona',
                data: 'zona=' + zona + '&rdm_id=' + rdm + '&chance=' + chance,
                success: function () {
                    updateRdmList();
                }
            });
        });
    });

}(window.jQuery, window));
