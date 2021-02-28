<div class="panel-heading">
    Hall da Fama
</div>

<style type="text/css">
    .div-top {
        margin: 0;
        padding: 0;
        position: relative;
    }

    .texto-top {
        position: absolute;
        z-index: 2;
        left: 0;
        bottom: 0;
        width: 100%;
        margin-bottom: 10px;
        text-shadow: #000 1px -1px 2px, #000 -1px 1px 2px, #000 1px 1px 2px, #000 -1px -1px 2px;
    }

    .texto-top p {
        margin-bottom: 0;
    }

    .texto-top-cargo {
        font-size: 1em;
    }

    .texto-top-nome {
        font-size: 1.5em;
    }

    .texto-top-alcunha {
        font-size: 1em;
    }
</style>

<div class="panel-body">
    <?= ajuda("Hall da Fama", "Conheça os maiores jogadores de eras passadas.") ?>

    <div class="panel panel-default hidden">
        <div class="panel-heading">
            1º Grande Era dos Piratas - 03/09/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <?php render_painel_rdp(1); ?>
                </div>
                <div class="col-xs-6 col-md-6">
                    <?php render_painel_adf(2); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default hidden">
        <div class="panel-heading">
            1º Batalha pelos Grandes Poderes - 03/07/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 col-md-6">
                    <?php render_painel_yonkou(1, 1, 1, 1); ?>
                </div>
                <div class="col-xs-6 col-md-6">
                    <?php render_painel_almirante(2, 2, 2, 2); ?>
                </div>
            </div>
        </div>
    </div>
</div>