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

    <div class="panel panel-default">
        <div class="panel-heading">
            7º Batalha pelos Grandes Poderes - 07/10/2018
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(160, 1073, 102, 173); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(37, 73, 425, 2211); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            2 Grande Era dos Piratas - 03/12/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_rdp(160); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_adf(73); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            6º Batalha pelos Grandes Poderes - 03/12/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(160, 94, 102, 766); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(73, 37, 110, 1975); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            5º Batalha pelos Grandes Poderes - 05/11/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(4, 766, 94, 102); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(73, 110, 1581, 913); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            4º Batalha pelos Grandes Poderes - 07/10/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(27, 160, 94, 766); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(73, 154, 110, 0); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            1º Grande Era dos Piratas - 03/09/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_rdp(160); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_adf(73); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            3º Batalha pelos Grandes Poderes - 03/09/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(27, 160, 94, 4); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(73, 9, 110, 106); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            2º Batalha pelos Grandes Poderes - 03/08/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(160, 27, 4, 94); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(110, 73, 37, 106); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            1º Batalha pelos Grandes Poderes - 03/07/2017
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php render_painel_yonkou(22, 74, 34, 14); ?>
                </div>
                <div class="col-md-6">
                    <?php render_painel_almirante(37, 2, 110, 11); ?>
                </div>
            </div>
        </div>
    </div>
</div>