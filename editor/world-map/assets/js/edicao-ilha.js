(function ($, global) {
    'use strict';
    var addIlhaDialog;

    function edicaoIlha() {
        global.unbind();

        $.ajax({
            url: 'server/index.php',
            data: global.formatTipoData('ilha'),
            success: function (response) {
                var i, ilha, elemId;

                response = JSON.parse(response);

                $('.quadro').addClass('add-ilha');

                for (i = 0; i < response.length; i += 1) {
                    ilha = response[i];
                    elemId = '#quadro_' + ilha.x + '_' + ilha.y;
                    $(elemId)
                        .removeClass('add-ilha')
                        .addClass('com-ilha')
                        .data('nome', ilha.nome)
                        .data('id', ilha.ilha_id)
                        .data('quadro-info', '#' + ilha.ilha_id + ' - ' + ilha.nome);
                }
            }
        });
    }

    function apagarIlha() {
        var id = $('#add-ilha-input-id').val();
        $.ajax({
            url: 'server/index.php/welcome/remove_ilha',
            data: 'id=' + id,
            success: function () {
                edicaoIlha();
            }
        });
        addIlhaDialog.dialog("close");
    }

    function salvarIlha() {
        var x = $('#add-ilha-input-x').val(),
            y = $('#add-ilha-input-y').val(),
            nome = $('#add-ilha-input-nome').val(),
            id = $('#add-ilha-input-id').val();

        $.ajax({
            url: 'server/index.php/welcome/add_ilha',
            data: 'x=' + x + '&y=' + y + '&nome=' + nome + '&id=' + id,
            success: function () {
                edicaoIlha();
            }
        });
        addIlhaDialog.dialog("close");
    }

    $(function () {
        $('#ilha').button().click(function () {
            edicaoIlha();
        });

        addIlhaDialog = $("#dialog-add-ilha").dialog({
            autoOpen: false,
            height: 300,
            width: 500,
            modal: true,
            buttons: {
                "Apagar ilha": apagarIlha,
                "Salvar ilha": salvarIlha,
                Cancelar: function () {
                    addIlhaDialog.dialog("close");
                }
            }
        });

        $(document).on('click', '.quadro.add-ilha', function () {
            var x = $(this).data('x'),
                y = $(this).data('y');

            $('#add-ilha-input-x').val(x);
            $('#add-ilha-input-y').val(y);
            $('#add-ilha-input-nome').val('');
            $('#add-ilha-input-id').val('novo');

            addIlhaDialog.dialog("open");
        });

        $(document).on('click', '.quadro.com-ilha', function () {
            var x = $(this).data('x'),
                y = $(this).data('y'),
                nome = $(this).data('nome'),
                id = $(this).data('id');

            $('#add-ilha-input-x').val(x);
            $('#add-ilha-input-y').val(y);
            $('#add-ilha-input-nome').val(nome);
            $('#add-ilha-input-id').val(id);

            addIlhaDialog.dialog("open");
        });
    });

}(window.jQuery, window));
