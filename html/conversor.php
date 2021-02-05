<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var money   = $("#input-money"),
            gold    = $("#input-gold"),
            dobrao  = $("#input-dobrao");

        money.on('change', function(e) {
            var valor   = parseFloat($(this).val()).toFixed(2) || 0,
                golds   = valor / 0.02,
                dobroes = golds * 2.4;

            gold.val(golds);
            dobrao.val(dobroes);
        });
        gold.on('change', function(e) {
            var golds   = parseInt($(this).val(), 10) || 0,
                valor   = parseFloat(golds * 0.02).toFixed(2),
                dobroes = parseInt(golds * 2.4, 10);

            money.val(valor);
            dobrao.val(dobroes);
        });
        dobrao.on('change', function(e) {
            var dobroes = parseInt($(this).val(), 10) || 0,
                golds   = parseInt(dobroes / 2.4, 10),
                valor   = parseFloat(golds * 0.02).toFixed(2);

            money.val(valor);
            gold.val(golds);
        });
    });
</script>
<div class="card card-success" style="max-width: 350px; margin: 50px;">
    <div class="card-header">
        <h5 class="card-title text-center text-uppercase" style="margin: 0;">Conversor</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th class="text-right" style="width: 125px; vertical-align: middle;">Dinheiro (R$):</th>
                <td class="text-center">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" placeholder="0" id="input-money" />
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-right" style="vertical-align: middle;">Golds:</th>
                <td class="text-center">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" placeholder="0" id="input-gold" />
                    </div>
                </td>
            </tr>
            <tr>
                <th class="text-right" style="vertical-align: middle;">Dobrões:</th>
                <td class="text-center">
                    <div class="form-group">
                        <input type="text" class="form-control" value="" placeholder="0" id="input-dobrao" />
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>