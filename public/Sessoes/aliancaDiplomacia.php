<div class="panel-heading">
    Diplomacia
</div>

<script type="text/javascript">
    $(function () {
        timeOuts["tempo_guerra"] = setTimeout("tempo_guerra()", 1000);
    });

    var cont_guerra = 1;
    function tempo_guerra() {
        if (document.getElementById("guerra_tempo_sec") != null) {
            timeOuts["tempo_guerra"] = setTimeout("tempo_guerra()", 1000);
            var tmp = document.getElementById("guerra_tempo_sec").innerHTML;
            tmp -= cont_guerra;
            document.getElementById("guerra_tempo_min").innerHTML = transforma_tempo(tmp);
            cont_guerra++;
        }
    }
</script>

<div class="panel-body">
    <?= ajuda("Diplomacia", "Veja quem são os inimigos da sua aliança!") ?>

    <?php
    $query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
    $result = mysql_query($query);
    $permicao = mysql_fetch_array($result);

    $query = "SELECT * FROM tb_alianca_guerra WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        ?>
        Você não está em guerra com nenhuma aliança ou frota.<br>
        É necessário, no mínimo, ter a Aliança/Frota no nível 5 e 15 membros para poder entrar em guerra.<br>
        <?php
        $query = "SELECT * FROM tb_alianca_membros WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
        $result = mysql_query($query);
        $quant_membros = mysql_num_rows($result);
        if (substr($usuario["alianca"][$permicao["autoridade"]], 4, 1) == 1 AND $usuario["alianca"]["lvl"] > 4 && $quant_membros >= 15) {
            $query = "SELECT * FROM tb_alianca_guerra_pedidos WHERE convidado='" . $usuario["alianca"]["cod_alianca"] . "'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) != 0) {
                ?>
                <h4>Desafios de guerra:</h4><br>
                <?php
            }
            while ($pedido = mysql_fetch_array($result)) {
                $query2 = "SELECT * FROM tb_alianca WHERE cod_alianca='" . $pedido["cod_alianca"] . "'";
                $result2 = mysql_query($query2);
                $pedido2 = mysql_fetch_array($result2);
                echo $pedido2["nome"] . " - " . $pedido["tipo"] . " vitórias";
                ?>
                <button href='link_Alianca/alianca_guerra_recusar.php?cod=<?php echo $pedido["cod_alianca"] ?>'
                        class="link_send btn btn-danger">
                    Recusar
                </button>
                <button href='link_Alianca/alianca_guerra_confirmar.php?cod=<?php echo $pedido["cod_alianca"] ?>'
                        class="link_send btn btn-success">
                    Aceitar
                </button>
            <? } ?>
            <?php
            $query = "SELECT * FROM tb_alianca_guerra_pedidos WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) != 0) { ?>
                <br><br>
                <h4>Desafios enviados:</h4><br>
            <? }
            while ($pedido = mysql_fetch_array($result)) {
                $query2 = "SELECT * FROM tb_alianca WHERE cod_alianca='" . $pedido["convidado"] . "'";
                $result2 = mysql_query($query2);
                $pedido2 = mysql_fetch_array($result2);
                echo $pedido2["nome"] . " - " . $pedido["tipo"] . " vitórias";
                ?>
                <button href='link_Alianca/alianca_guerra_cancelar.php?cod=<?php echo $pedido2["cod_alianca"] ?>'
                        class="link_send btn btn-danger">
                    Cancelar
                </button>
                <?php
            }

            $query = "SELECT * FROM tb_alianca_guerra_pedidos 
		WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' 
		OR convidado='" . $usuario["alianca"]["cod_alianca"] . "'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) == 0) {
                ?>
                <br><br>
                <h4>Enviar declaração de guerra:</h4>
                <form method="post" class="form-inline" action="Scripts/Alianca/alianca_guerra_convidar.php">
                    <div class="form-group">
                        <input type="text" name="nome" class="form-control" size="50"
                               placeholder="Digite o nome da aliança ou frota que deseja enfrentar."
                               onkeyup="this.value=removeCaracteres(this.value)"
                               onblur="this.value=removeCaracteres(this.value)"/>
                    </div>
                    <div class="form-group">
                        <select name="tipo" class="form-control">
                            <option value="5">5 vitórias</option>
                            <option value="10">10 vitórias</option>
                            <option value="15">15 vitórias</option>
                            <option value="25">25 vitórias</option>
                            <option value="50">50 vitórias</option>
                        </select>
                    </div>
                    <button class="btn btn-success" type="submit">Desafiar</button>
                </form>
                <?php
            }
        }
    } else {
        $inimigo = mysql_fetch_array($result);
        $query = "SELECT * FROM tb_alianca WHERE cod_alianca='" . $inimigo["cod_inimigo"] . "'";
        $result = mysql_query($query);
        $inimigo_info = mysql_fetch_array($result);

        $query = "SELECT * FROM tb_alianca_guerra WHERE cod_alianca='" . $inimigo["cod_inimigo"] . "'";
        $result = mysql_query($query);
        $inimigo_info2 = mysql_fetch_array($result);

        ?>
        <font style="font-size: 20px"><b>Sua <?php if ($usuario["faccao"] == 0) echo "frota"; else echo "aliança" ?>
                está em guerra!</b></font><br><br>
        <font style="font-size: 25px"><b><?php echo $usuario["alianca"]["nome"] . "</b> : " . $inimigo["pts"] . " vitórias" ?>
        </font><br>
        <font style="font-size: 25px"><b><?php echo $inimigo_info["nome"] . "</b> : " . $inimigo_info2["pts"] . " vitórias" ?>
        </font><br>
        <br>
        <b>Tempo restante:</b><br>
        <? if ($inimigo["fim"] >= atual_segundo()) { ?>
            <span id="guerra_tempo_min">
	<? echo transforma_tempo_min($inimigo["fim"] - atual_segundo()); ?>
	</span>
            <span id="guerra_tempo_sec" style="display: none;">
	<? echo $inimigo["fim"] - atual_segundo(); ?>
	</span>
        <? } else if (substr($usuario["alianca"][$permicao["autoridade"]], 5, 1) == 1) { ?>
            <button href='link_Alianca/alianca_guerra_finalizar.php' class="link_send btn btn-primary">
                Finalizar
            </button>
        <? } ?>
        <br><br>
        <b>Condições de vitória:</b><br>
        <?php echo $inimigo["vitoria"] . " vitórias" ?><br><br>
        <b>Sua cooperação:</b><br>
        <?php
        $query = "SELECT * FROM tb_alianca_guerra_ajuda WHERE id='" . $usuario["id"] . "'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) == 0) echo "0";
        else {
            $ajuda = mysql_fetch_array($result);
            echo $ajuda["quant"];
        }
        ?> vitórias<br><br>
        <br>
        <? if ($inimigo["pts"] >= $inimigo["vitoria"] AND substr($usuario["alianca"][$permicao["autoridade"]], 5, 1) == 1) { ?>
            Você ganhou essa Guerra!<br><br>
            <? if (substr($usuario["alianca"][$permicao["autoridade"]], 5, 1) == 1) { ?>
                <button href='link_Alianca/alianca_guerra_finalizar.php' class="link_send btn btn-success">
                    Pegar Recompensa
                </button>
            <? } ?>
        <? } else if ($inimigo_info2["pts"] >= $inimigo["vitoria"]) { ?>
            Você perdeu essa Guerra!<br><br>
            <? if (substr($usuario["alianca"][$permicao["autoridade"]], 5, 1) == 1) { ?>
                <button href='link_Alianca/alianca_guerra_finalizar.php' class="link_send btn btn-primary">
                    Finalizar
                </button>
            <? } ?>
        <? } else { ?>
            <b>Recompensas:</b><br>
            <?php echo ($inimigo["vitoria"] / 5) * 50 ?> pts de Reputação para <?php if ($usuario["faccao"] == 0) echo "frota"; else echo "aliança" ?>.
            <br>
            <?php echo ($inimigo["vitoria"] / 5) * 10 ?> pts de Experiência para <?php if ($usuario["faccao"] == 0) echo "frota"; else echo "aliança" ?>.
            <br>
            <?php echo $inimigo["vitoria"] * 5 ?> pts de Cooperação para cada jogador que participar.<br><br>
        <? } ?>
    <? } ?>
</div>
</div>