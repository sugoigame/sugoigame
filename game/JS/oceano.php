<script type="text/javascript">
    $(function () {
        $("#formulario_tracar_rota").submit(function (e) {
            e.preventDefault();
            var pagina = "Mapa/mapa_navegar";

            var obj = {};
            for (var x = 0; x < 25; x++) {
                obj["r_" + x] = $("#rota_r_" + x).val();
            }
            obj.vem = 'oceano';
            sendForm(pagina, obj, function () {
                iniciaNav();
            });
        });

        $("#cancelar_navegacao").click(function () {
            var locale = $(this).attr("href");
            sendGet(locale);
            $("#destino_sec").html("0");
        });
    });

    var rota_criada = 0;

    <? if($usuario["mergulho"] != 0 AND $usuario["mergulho"] > atual_segundo()){ ?>
    timeOuts["atualizaTempoMergulho"] = setTimeout("atualizaTempoMergulho()", 1000);
    var cont_mergulho = 1;

    function atualizaTempoMergulho() {
        timeOuts["atualizaTempoMergulho"] = setTimeout("atualizaTempoMergulho()", 1000);
        if (document.getElementById("mergulhador_sec") != null) {
            var tmp = document.getElementById("mergulhador_sec").innerHTML;
            tmp -= cont_mergulho;
            if (tmp < 0) {
                loadPagina(pagina_atual);
            }
            document.getElementById("mergulhador_min").innerHTML = transforma_tempo(tmp);
        }
        cont_mergulho++;
    }
    <? } if($usuario["expedicao"] != 0 AND $usuario["expedicao"] > atual_segundo()){ ?>
    timeOuts["atualizaTempoExpedicao"] = setTimeout("atualizaTempoExpedicao()", 1000);
    var cont_expedicao = 1;

    function atualizaTempoExpedicao() {
        timeOuts["atualizaTempoExpedicao"] = setTimeout("atualizaTempoExpedicao()", 1000);
        if (document.getElementById("expedicao_sec") != null) {
            var tmp = document.getElementById("expedicao_sec").innerHTML;
            tmp -= cont_expedicao;
            if (tmp < 0) {
                loadPagina(pagina_atual);
            }
            document.getElementById("expedicao_min").innerHTML = transforma_tempo(tmp);
        }
        cont_expedicao++;
    }
    <? }if($usuario["desenho"] != 0 AND $usuario["desenho"] > atual_segundo()){ ?>
    timeOuts["atualizaTempoDesenho"] = setTimeout("atualizaTempoDesenho()", 1000);
    var cont_desenho = 1;

    function atualizaTempoDesenho() {
        timeOuts["atualizaTempoDesenho"] = setTimeout("atualizaTempoDesenho()", 1000);
        if (document.getElementById("desenho_sec") != null) {
            var tmp = document.getElementById("desenho_sec").innerHTML;
            tmp -= cont_desenho;
            if (tmp < 0) {
                loadPagina(pagina_atual);
            }
            document.getElementById("desenho_min").innerHTML = transforma_tempo(tmp);
        }
        cont_desenho++;
    }
    <? }if($usuario["mining"] != 0 AND $usuario["mining"] > atual_segundo()){ ?>
    timeOuts["atualizaTempoMining"] = setTimeout("atualizaTempoMining()", 1000);
    var cont_mining = 1;

    function atualizaTempoMining() {
        timeOuts["atualizaTempoMining"] = setTimeout("atualizaTempoMining()", 1000);
        if (document.getElementById("mining_sec") != null) {
            var tmp = document.getElementById("mining_sec").innerHTML;
            tmp -= cont_mining;
            if (tmp < 0) {
                loadPagina(pagina_atual);
            }
            document.getElementById("mining_min").innerHTML = transforma_tempo(tmp);
        }
        cont_mining++;
    }
    <? }if($usuario["madeira"] != 0 AND $usuario["madeira"] > atual_segundo()){ ?>
    timeOuts["atualizaTempoMadeira"] = setTimeout("atualizaTempoMadeira()", 1000);
    var cont_madeira = 1;

    function atualizaTempoMadeira() {
        timeOuts["atualizaTempoMadeira"] = setTimeout("atualizaTempoMadeira()", 1000);
        if (document.getElementById("madeira_sec") != null) {
            var tmp = document.getElementById("madeira_sec").innerHTML;
            tmp -= cont_madeira;
            if (tmp < 0) {
                loadPagina(pagina_atual);
            }
            document.getElementById("madeira_min").innerHTML = transforma_tempo(tmp);
        }
        cont_madeira++;
    }
    <? } ?>


    timeOuts["carrega_oceano"] = setTimeout("carrega_oceano()", 5000);

    function carrega_oceano() {
        timeOuts["carrega_oceano"] = setTimeout("carrega_oceano()", 5000);
        $.ajax({
            type: 'get',
            url: 'Scripts/Mapa/mapa_oceano.php',
            cache: false,
            success: function (retorno) {
                retorno = retorno.trim();
                proccessResponseAlert(retorno);
                $("#oceano_borda").html(retorno);
            }
        });
    }

    window.onload = setTimeout("limpaform()", 100);

    function limpaform() {
        if (document.formulario_tracar_rota != null)
            document.formulario_tracar_rota.reset();
        rota_criada = 0;
    }

    otrox = 0;
    otroy = 0;
    var selectx =<?php echo $usuario["coord_x_navio"]; ?>;
    var selecty =<?php echo $usuario["coord_y_navio"]; ?>;
    var alvoMostrado = new Array();
    timeOuts["clearAlvoMostrado"] = setTimeout("clearAlvoMostrado()", 20000);

    function clearAlvoMostrado() {
        timeOuts["clearAlvoMostrado"] = setTimeout("clearAlvoMostrado()", 20000);
        alvoMostrado = new Array();
    }

    function mostraAlvo(x, y, nav) {
        l = x;
        n = y;
        n = 101 - n;
        n -= 50;
        if (l > 100) {
            l -= 200;
        }
        document.getElementById("coord_alvo").innerHTML = l + "ºL, " + n + "ºN";
        var camp = "sel_" + x + "_" + y;
        if (document.getElementById(camp) != null) {
            document.getElementById(camp).style.border = "1px solid #3333FF";
            document.getElementById(camp).style.margin = "-2px";
        }
        if (otrox != 0 && otroy != 0 && (otrox != x || otroy != y)) {
            escondeAlvo(otrox, otroy);
        }
        otrox = x;
        otroy = y;
        ok = true;
        for (key in alvoMostrado) {
            if (key == (x + "_" + y)) ok = false;
        }
        if (ok) {
            $.ajax({
                type: 'get',
                url: 'Scripts/Mapa/mapa_vercoord.php',
                data: "x=" + x + "&y=" + y,
                cache: false,
                success: function (retorno) {
                    $("#coord_selec").html(retorno);
                    alvoMostrado[x + "_" + y] = retorno;
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
        else {
            $("#coord_selec").html(alvoMostrado[x + "_" + y]);
        }
    }
    <? if($userDetails->rotas){ ?>
    timeOuts["atualiza_rota_andamento_tempo"] = setTimeout("atualiza_rota_andamento_tempo()", 1000);
    <? } ?>
    var cont_tempo = 0;

    function atualiza_rota_andamento_tempo() {
        timeOuts["atualiza_rota_andamento_tempo"] = setTimeout("atualiza_rota_andamento_tempo()", 1000);
        var tmp;
        <? if($userDetails->rotas){
        foreach($userDetails->rotas as $rota){ ?>
        if (document.getElementById("rota_andamento_tempo_sec_<?php echo $rota["indice"]; ?>")) {
            tmp = document.getElementById("rota_andamento_tempo_sec_<?php echo $rota["indice"]; ?>").innerHTML;
            tmp = parseInt(tmp);
            tmp -= cont_tempo;
            document.getElementById("rota_andamento_tempo_<?php echo $rota["indice"]; ?>").innerHTML = tmp;
            if (tmp <= 0) {
                document.getElementById("rota_andamento_tempo_<?php echo $rota["indice"]; ?>").style.display = "none";
            }
        }
        <? } ?>
        <?} ?>
        cont_tempo++;
    }

    function escondeAlvo(x, y) {
        var camp = "sel_" + x + "_" + y;
        if (document.getElementById(camp) != null) {
            document.getElementById(camp).style.border = "0px";
            document.getElementById(camp).style.margin = "-1px";
        }
    }

    function verAddRota(inpx, inpy, nav) {
        <? if(!isset($rota)){ ?>
        var x = inpx;
        var y = inpy;
        selectx = parseInt(selectx);
        selecty = parseInt(selecty);
        if (Math.sqrt(Math.pow(x - selectx, 2) + Math.pow(y - selecty, 2)) <= 1.5
            && (x != selectx || y != selecty)) {
            document.getElementById('add_rota').disabled = false;
            document.getElementById('add_rota').onclick = function () {
                addRota(inpx, inpy, nav)
            };
        }
        else {
            document.getElementById('add_rota').disabled = true;
            document.getElementById('add_rota').onclick = "";
        }
        <? } ?>
    }

    function addRota(inpx, inpy, nav) {
        <? if(!isset($rota)){ ?>
        var x = inpx;
        var y = inpy;
        selectx = parseInt(selectx);
        selecty = parseInt(selecty);
        if (Math.sqrt(Math.pow(x - selectx, 2) + Math.pow(y - selecty, 2)) <= 1.5
            && (x != selectx || y != selecty)) {
            for (i = 1; i < 7; i++) {
                id = "rota_" + i;
                if (i == 6) {
                    $(".ajuda_texto").html("Você não pode traçar rotas com mais de 5 coordenadas no oceano");
                    $(".bt_ajuda").click();
                    i = 7;
                }
                else {
                    if (document.getElementById(id).value.length == 0) {
                        l = inpx;
                        n = inpy;
                        n = 101 - n;
                        n -= 50;
                        if (l > 100) {
                            l -= 200;
                        }
                        val = l + "ºL, " + n + "ºN";
                        document.getElementById(id).value = val;
                        r = inpx + "_" + inpy;
                        id2 = "rota_r_" + i;
                        k = "sel_" + x + "_" + y;
                        document.getElementById(id2).value = r;
                        document.getElementById(r).style.opacity = "0.5";
                        document.getElementById(r).style.background = "#87CEFA";
                        selectx = inpx;
                        selecty = inpy;
                        i = 7;
                        document.getElementById('add_rota').disabled = true;
                        document.getElementById('add_rota').onclick = "";
                        rota_criada = 1;
                    }
                }
            }
        }
        <? } ?>
    }

    function limpa_rota() {
        for (i = 1; i < 6; i++) {
            id = "rota_" + i;
            camp = document.getElementById(id).value;
            document.getElementById(id).value = "";

            id2 = "rota_r_" + i;
            r = document.getElementById(id2).value;
            if (document.getElementById(r) != null) {
                document.getElementById(r).style.background = "transparent";
                document.getElementById(r).style.opacity = "1";
            }
            document.getElementById(id2).value = "";
        }
        selectx =<?php echo $usuario["coord_x_navio"]; ?>;
        selecty =<?php echo $usuario["coord_y_navio"]; ?>;
        rota_criada = 0;
    }
</script>