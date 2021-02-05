<div class="panel-heading">
    Bandeira
</div>

<div class="panel-body">
    <?= ajuda("Bandeira", "Aqui você pode personalizar sua bandeira da maneira que preferir.") ?>
    <script type="text/javascript">
        $(function () {
            $(".bt_mudar_bandeira").click(function () {
                var tipo = $(this).data('tipo');
                bootbox.confirm({
                    title: 'Deseja mesmo alterar sua bandeira?',
                    message: 'Apenas a primeira mudança de bandeira é gratuita.',
                    buttons: {
                        confirm: {
                            label: 'Sim',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Não',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            var value = $("#cod_bandeira").val();
                            var pagina = "Geral/bandeira_mudar.php?cod=" + value + '&tipo=' + tipo;
                            sendGet(pagina);
                        }
                    }
                });
            });
        });

        function muda(sessao, item, qnt) {
            var F = new Array();
            var C = new Array();
            var A = new Array();
            cod = document.getElementById("cod_bandeira").value;
            d = 0;
            for (x = 0; x < 6; x++) {
                F[x] = cod.substr(d, 2);
                d += 2;
            }
            for (x = 0; x < 6; x++) {
                C[x] = cod.substr(d, 2);
                d += 2;
            }
            for (x = 0; x < 6; x++) {
                A[x] = cod.substr(d, 2);
                d += 2;
            }

            <?php
            if ($usuario["faccao"] == 1) {
                $ta = 14;
                $tc = 13;
                $tf = 9;
                $tcor = 8;
            } else {
                $ta = 9;
                $tc = 7;
                $tf = 9;
                $tcor = 8;
            }
            ?>
            if (sessao == "f") {
                F[item] = parseInt(F[item], 10);
                F[item] = F[item] + qnt;

                if (item == 0 && F[item] ==<?php echo $tf + 1; ?>) F[item] = 1;
                else if (item == 0 && F[item] == 0) F[item] =<?php echo $tf; ?>;

                if (item == 1 && F[item] == 9) F[item] = 1;
                else if (item == 1 && F[item] == 0) F[item] = 8;

                if (item == 2 && F[item] < 0) F[item] = 0;
                else if (item == 2 && F[item] > 95) F[item] = 95;

                if (item == 3 && F[item] < 0) F[item] = 0;
                else if (item == 3 && F[item] > 65) F[item] = 65;

                if (item == 4 && F[item] < 0) F[item] = 0;
                else if (item == 4 && F[item] > 99) F[item] = 99;

                if (item == 5 && F[item] < 0) F[item] = 0;
                else if (item == 5 && F[item] > 99) F[item] = 99;

                F[item] = F[item].toString();
                if (F[item].length == 1) F[item] = "0" + F[item];
            }
            else if (sessao == "c") {
                C[item] = parseInt(C[item], 10);
                C[item] += qnt;

                if (item == 0 && C[item] ==<?php echo $tc + 1; ?>) C[item] = 1;
                else if (item == 0 && C[item] == 0) C[item] =<?php echo $tc; ?>;

                if (item == 1 && C[item] == 9) C[item] = 1;
                else if (item == 1 && C[item] <= 0) C[item] = 8;

                if (item == 2 && C[item] < 0) C[item] = 0;
                else if (item == 2 && C[item] > 95) C[item] = 95;

                if (item == 3 && C[item] < 0) C[item] = 0;
                else if (item == 3 && C[item] > 65) C[item] = 65;

                if (item == 4 && C[item] < 0) C[item] = 0;
                else if (item == 4 && C[item] > 99) C[item] = 99;

                if (item == 5 && C[item] < 0) C[item] = 0;
                else if (item == 5 && C[item] > 99) C[item] = 99;

                C[item] = C[item].toString();
                if (C[item].length == 1) C[item] = "0" + C[item];
            }
            else if (sessao == "a") {
                A[item] = parseInt(A[item], 10);
                A[item] += qnt;

                if (item == 0 && A[item] ==<?php echo $ta + 1; ?>) A[item] = 1;
                else if (item == 0 && A[item] == 0) A[item] =<?php echo $ta; ?>;

                if (item == 1 && A[item] == 9) A[item] = 1;
                else if (item == 1 && A[item] <= 0) A[item] = 8;

                if (item == 2 && A[item] < 0) A[item] = 0;
                else if (item == 2 && A[item] > 95) A[item] = 95;

                if (item == 3 && A[item] < 0) A[item] = 0;
                else if (item == 3 && A[item] > 65) A[item] = 65;

                if (item == 4 && A[item] < 0) A[item] = 0;
                else if (item == 4 && A[item] > 99) A[item] = 99;

                if (item == 5 && A[item] < 0) A[item] = 0;
                else if (item == 5 && A[item] > 99) A[item] = 99;

                A[item] = A[item].toString();
                if (A[item].length == 1) A[item] = "0" + A[item];
            }

            ncod = F[0] + F[1] + F[2] + F[3] + F[4] + F[5] +
                C[0] + C[1] + C[2] + C[3] + C[4] + C[5] +
                A[0] + A[1] + A[2] + A[3] + A[4] + A[5];
            img = "Imagens/Bandeiras/img.php?cod=" + ncod + "&f=<?php echo $usuario["faccao"]; ?>";

            document.getElementById("nbandeira").src = img;
            document.getElementById("cod_bandeira").value = ncod;
        }
    </script>
    <p>Escolha as imagens e cores, depois posicione tudo para criar sua bandeira.<br>
        obs: Apenas a primeira mudança de bandeira é gratuita.</p><br><br><br>
    <img id="nbandeira"
         src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>">

    <div id="menu_bandeira" style="margin-bottom:10px">
        <input id="cod_bandeira" type="hidden" value="<?= $userDetails->tripulacao["bandeira"]; ?>" readonly="true">
        <div class="row">
            <div class="col-xs-4 col-md-4">
                <h4><?= ($usuario["faccao"] == 1) ? "Ossos Cruzado" : "Logo" ?></h4>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('f',0,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Desenho
                    <button class="btn btn-info pull-right" onclick="muda('f',0,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('f',1,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Cor
                    <button class="btn btn-info pull-right" onclick="muda('f',1,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('f',3,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Vertical
                    <button class="btn btn-info pull-right" onclick="muda('f',3,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('f',2,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Horizontal
                    <button class="btn btn-info pull-right" onclick="muda('f',2,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('f',5,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Altura
                    <button class="btn btn-info pull-right" onclick="muda('f',5,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('f',4,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Largura
                    <button class="btn btn-info pull-right" onclick="muda('f',4,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="col-xs-4 col-md-4">
                <h4><?= ($usuario["faccao"] == 1) ? "Caveira" : "Texto" ?></h4>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('c',0,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Desenho
                    <button class="btn btn-info pull-right" onclick="muda('c',0,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('c',1,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Cor
                    <button class="btn btn-info pull-right" onclick="muda('c',1,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('c',3,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Vertical
                    <button class="btn btn-info pull-right" onclick="muda('c',3,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('c',2,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Horizontal
                    <button class="btn btn-info pull-right" onclick="muda('c',2,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('c',5,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Altura
                    <button class="btn btn-info pull-right" onclick="muda('c',5,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('c',4,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Largura
                    <button class="btn btn-info pull-right" onclick="muda('c',4,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="col-xs-4 col-md-4">
                <h4>Complemento</h4>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('a',0,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Desenho
                    <button class="btn btn-info pull-right" onclick="muda('a',0,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('a',1,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Cor
                    <button class="btn btn-info pull-right" onclick="muda('a',1,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('a',3,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Vertical
                    <button class="btn btn-info pull-right" onclick="muda('a',3,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('a',2,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Horizontal
                    <button class="btn btn-info pull-right" onclick="muda('a',2,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('a',5,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Altura
                    <button class="btn btn-info pull-right" onclick="muda('a',5,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <div class="clearfix">
                    <button class="btn btn-info pull-left" onclick="muda('a',4,-1)">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                    Largura
                    <button class="btn btn-info pull-right" onclick="muda('a',4,1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if ($userDetails->tripulacao["bandeira"] == "010113046758010128123542010115204020") : ?>
        <button class="bt_mudar_bandeira btn btn-success">
            Mudar
        </button>
    <?php else: ?>
        <button class="bt_mudar_bandeira btn btn-success" data-tipo="gold"
            <?= $userDetails->conta["gold"] < PRECO_GOLD_TROCAR_BANDEIRA ? "disabled" : "" ?>>
            <?= PRECO_GOLD_TROCAR_BANDEIRA ?> <img src="Imagens/Icones/Gold.png">
            Mudar
        </button>
        <button class="bt_mudar_bandeira btn btn-success" data-tipo="dobrao"
            <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_TROCAR_BANDEIRA ? "disabled" : "" ?>>
            <?= PRECO_DOBRAO_TROCAR_BANDEIRA ?> <img src="Imagens/Icones/Dobrao.png">
            Mudar
        </button>
    <? endif; ?>
</div>