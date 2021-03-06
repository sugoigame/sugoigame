<html>
<body>
<form id="main-form">
    <table>
        <tr>
            <td>
                <select id="table">
                    <option>tb_combinacoes_forja</option>
                    <option>tb_combinacoes_artesao</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Slot</td>
            <td>Cod</td>
            <td>Tipo</td>
            <td>Quant</td>
        </tr>
        <?php for($x= 1; $x<=8; $x++): ?>
            <tr>
                <td><?= $x ?></td>
                <td><input id="<?= $x ?>" value="0"></td>
                <td><input id="<?= $x ?>_t" value="0"></td>
                <td><input id="<?= $x ?>_q" value="0"></td>
            </tr>
        <?php endfor; ?>
        <tr>
            <td>lvl</td>
            <td><input id="lvl"></td>
        </tr>
        <tr>
            <td>Resultado Cod</td>
            <td><input id="cod"></td>
        </tr>
        <tr>
            <td>Resultado Tipo</td>
            <td><input id="tipo"></td>
        </tr>
        <tr>
            <td>Resultado Quant</td>
            <td><input id="quant"></td>
        </tr>
    </table>
    <button>Adicionar</button>
</form>

<pre id="output-insert"></pre>
<textarea cols="200" rows="10" id="output"></textarea>
</body>
<script src="../JS/jquery-2.2.2.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#main-form').on('submit', function (e) {
            e.preventDefault();

            var slots = [];
            for(var x = 1; x <= 8; x++){
                slots.push('`' + x + '`');
                slots.push('`' + x + '_t`');
                slots.push('`' + x + '_q`');
            }
            var insert = 'INSERT INTO ' + $('#table').val()
                + ' (aleatorio, ' + slots.join(' , ') + ', lvl, cod, tipo, quant, visivel) '
                + ' VALUES';

            $('#output-insert').html(insert);

            slots = [];
            for(x = 1; x <= 8; x++){
                slots.push("'" + $('#' + x).val() + "'");
                slots.push("'" + $('#' + x + '_t').val() + "'");
                slots.push("'" + $('#' + x + '_q').val() + "'");
            }
            var sql = '(0, ' + slots.join(' , ') + ', '+$('#lvl').val()+', '+$('#cod').val()+', '+$('#tipo').val()+', '+$('#quant').val()+', 0)';

            $('#output').val($('#output').val()+',\n'+sql);
        });
    });
</script>
</html>