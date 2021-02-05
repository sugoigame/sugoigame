<div class="panel-heading">
    Banco da <?= ($usuario["faccao"] == 0) ? "Frota" : "Aliança" ?>
</div>

<div class="panel-body">
    <?= ajuda("Banco", "Guarde seus itens e ganhe itens da sua Aliança / Frota. Vale lembrar que o limite do banco 
    da aliança ou frota é de 100 itens") ?>

    <?php $result = $connection->run("SELECT * FROM tb_alianca_banco_log WHERE cod_alianca=? ORDER BY momento DESC LIMIT 20",
        "i", $userDetails->ally["cod_alianca"]); ?>
    <div>
        <a class="btn btn-primary" data-toggle="collapse" href="#painel-historico">
            Histórico de transações
        </a>
    </div>
    <div id="painel-historico" class="collapse out">
        <ul class="list-group">
            <?php while ($log = $result->fetch_array()): ?>
                <li class="list-group-item">
                    <h4>
                        <?= $log["usuario"] ?> <?= ($log["tipo"] == 1) ? "depositou" : "saqueou"; ?> <?= $log["item"] ?>
                    </h4>
                    <p><?= date("d/m/Y - H:i", strtotime($log["momento"])) ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <h3>Transações Monetárias</h3>
    <script type="text/javascript">
        $(function () {
            $("#bt_deposito_berries").click(function () {
                var quant = $("#deposito_berries").val();
                var pagina = "Alianca/deposito_berries.php?quant=" + quant;
                sendGet(pagina);
            });
            $("#bt_saque_berries").click(function () {
                var quant = $("#saque_berries").val();
                var pagina = "Alianca/saque_berries.php?quant=" + quant;
                sendGet(pagina);
            });
            $(".saque_item_banco").click(function () {
                quant = $("#" + this.id + "_input").val();
                if (quant == null) quant = 0;
                info = $(this).attr("data");

                sendGet("Alianca/alianca_saque.php?" + info + "&quant=" + quant);
            });
            $(".deposito_item_banco").click(function () {
                quant = $("#" + this.id + "_input").val();
                if (quant == null) quant = 0;
                info = $(this).attr("data");

                sendGet("Alianca/alianca_deposito.php?" + info + "&quant=" + quant);
            });
        });
    </script>
    Dinheiro no banco da <?= ($usuario["faccao"] == 0) ? "Frota" : "Aliança" ?>:
    <img src="Imagens/Icones/Berries.png" alt="berries"/> <?= mascara_berries($userDetails->ally["banco"]) ?><br/><br/>
    <!--
    <?php if (can_guardar_berries($userDetails->ally)) : ?>
        <div class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" id="deposito_berries" placeholder="Depósito">
            </div>
            <button style="width: 100px" class="btn btn-primary" id="bt_deposito_berries">Depositar</button>
        </div>
    <?php endif; ?>
    -->
    <?php if (can_sacar_berries($userDetails->ally)) : ?>
        <div class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" id="saque_berries" placeholder="Saque">
            </div>
            <button style="width: 100px" class="btn btn-primary" id="bt_saque_berries">Sacar</button>
        </div>
    <?php endif; ?>
    <h3>Itens no Banco da <? if ($usuario["faccao"] == 0) echo "Frota"; else echo "Aliança"; ?></h3>
    <h4>Taxa para saque ou depósito de itens: <img src="Imagens/Icones/Berries.png" alt="berries"/> 10.000</h4>

    <?php $items = get_many_results_joined_mapped_by_type("tb_alianca_banco", "cod_item", "tipo_item", array(
        array("nome" => "tb_item_acessorio", "coluna" => "cod_acessorio", "tipo" => TIPO_ITEM_ACESSORIO),
        array("nome" => "tb_item_comida", "coluna" => "cod_comida", "tipo" => TIPO_ITEM_COMIDA),
        array("nome" => "tb_item_navio_leme", "coluna" => "cod_leme", "tipo" => TIPO_ITEM_LEME),
        array("nome" => "tb_item_navio_velas", "coluna" => "cod_velas", "tipo" => TIPO_ITEM_VELAS),
        array("nome" => "tb_item_remedio", "coluna" => "cod_remedio", "tipo" => TIPO_ITEM_REMEDIO),
        array("nome" => "tb_item_navio_canhao", "coluna" => "cod_canhao", "tipo" => TIPO_ITEM_CANHAO),
        array("nome" => "tb_item_reagents", "coluna" => "cod_reagent", "tipo" => TIPO_ITEM_REAGENT)
    ), "WHERE origem.cod_alianca = ?", "i", $userDetails->ally["cod_alianca"]); ?>

    <div class="row">
        <?php foreach ($items as $x => $item) : ?>
            <div class="list-group-item col-md-4">
                <h5><?= get_img_item($item) ?><br> <?= $item["nome"] ?></h5>
                <p>
                    Quantidade: <?= $item["quant"] ?>
                </p>
                <?php if (can_sacar_itens($userDetails->ally)) : ?>
                    <div class="form-inline">
                        <div class="form-group">
                            <?php if ($item["quant"] > 1) : ?>
                                <label>Quantidade:</label>
                                <input class="form-control" type="number" id="saque_<?= $x ?>_input" size="5"
                                       type="number" min="1" max="<?= $item["quant"] ?>"/>
                            <?php endif; ?>
                            <button id="saque_<?= $x ?>"
                                    data="item=<?= $item["cod_item"] ?>&tipo=<?= $item["tipo_item"] ?>"
                                    class="saque_item_banco btn btn-success">Sacar item
                            </button>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>