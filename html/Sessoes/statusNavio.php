<div class="panel-heading">
    Navio
</div>

<script type="text/javascript">
    $(function () {
        timeOuts["atualizaTempoReparo"] = setTimeout("atualizaTempoReparo()", 1000);
    });
    var cont = 0;

    function atualizaTempoReparo() {
        if (document.getElementById("tempo_reparo") != null) {
            timeOuts["atualizaTempoReparo"] = setTimeout("atualizaTempoReparo()", 1000);
            var input = document.getElementById("tempo_reparo").innerHTML - cont;
            document.getElementById("tempo_reparo_min").innerHTML = transforma_tempo(input);
            cont += 1;
            if (input < 0) {
                window.location.reload();
            }
        }
    }
</script>

<div class="panel-body">
    <?= ajuda("Navio", "Veja aqui as informações sobre o seu navio, como nível, partes equipadas, etc.") ?>

    <div>
        <?php render_navio_icon(); ?>
    </div>
    <h4>
        <?= $userDetails->navio["nome"]; ?>
    </h4>

    <p>
        <?= $userDetails->navio["descricao"]; ?>
    </p>

    <div class="clearfix">
        <?php render_navio_hp_bar() ?>

        <h4>Nível <?= $userDetails->navio["lvl"] ?></h4>
        <p>Seu navio fica ganha mais pontos de vida a cada nível evoluido</p>
        <?php render_navio_xp_bar() ?>
    </div>

    <div class="list-group-item">
        <h4>Invetário:</h4>
        <p>Capacidade: <?= $userDetails->navio["capacidade_inventario"] ?></p>
        <p>
            A cada nível de profissão evoluído, o carpinteiro da sua tripulação
            pode aumentar a capacidade do seu inventário em 10 espaços.
        </p>
        <?php if (($userDetails->navio["capacidade_inventario"] - 55) < ($userDetails->lvl_carpinteiro * 10)): ?>
            <?php $preco = ((($userDetails->navio["capacidade_inventario"] - 55) / 10) + 1) * 100000; ?>
            <p>
                <button class="btn btn-info link_confirm" href="Navio/navio_aumentar_inventario.php"
                        data-question="Deseja aprimorar seu inventario?" <?= $userDetails->tripulacao["berries"] < $preco ? "disabled" : "" ?>>
                    <img src="Imagens/Icones/Berries.png"> <?= mascara_berries($preco) ?>
                    <br/>
                    Aumentar a capacidade do inventário
                </button>
            </p>
        <?php endif; ?>
    </div>

    <h3>Partes equipadas:</h3>
    <div class="list-group clearfix">
        <?php if ($userDetails->navio["cod_casco"]): ?>
            <?php $casco = $connection->run("SELECT * FROM tb_item_navio_casco WHERE cod_casco = ?",
                "i", $userDetails->navio["cod_casco"])->fetch_array(); ?>
            <div class="list-group-item col-md-3">
                <img src="Imagens/Itens/<?= $casco["img"]; ?>.png"/>
                <h4><?= $casco["nome"]; ?></h4>
                <p>HP + <?= $casco["bonus"]; ?></p>
            </div>
        <?php endif; ?>
        <?php if ($userDetails->navio["cod_leme"]): ?>
            <?php $leme = $connection->run("SELECT * FROM tb_item_navio_leme WHERE cod_leme = ?",
                "i", $userDetails->navio["cod_leme"])->fetch_array(); ?>
            <div class="list-group-item col-md-3">
                <img src="Imagens/Itens/<?= $leme["img"]; ?>.png"/>
                <h4><?= $leme["nome"]; ?></h4>
                <p>Efeito de correntes + <?= $leme["bonus"]; ?>%</p>
            </div>
        <?php endif; ?>
        <?php if ($userDetails->navio["cod_velas"]): ?>
            <?php $velas = $connection->run("SELECT * FROM tb_item_navio_velas WHERE cod_velas = ?",
                "i", $userDetails->navio["cod_velas"])->fetch_array(); ?>
            <div class="list-group-item col-md-3">
                <img src="Imagens/Itens/<?= $velas["img"]; ?>.png"/>
                <h4><?= $velas["nome"]; ?></h4>
                <p>Efeito de ventos + <?= $velas["bonus"]; ?>%</p>
            </div>
        <?php endif; ?>
        <?php if ($userDetails->navio["cod_canhao"]): ?>
            <?php $canhao = $connection->run("SELECT * FROM tb_item_navio_canhao WHERE cod_canhao = ?",
                "i", $userDetails->navio["cod_canhao"])->fetch_array(); ?>
            <div class="list-group-item col-md-3">
                <img src="Imagens/Itens/<?= $canhao["img"]; ?>.png"/>
                <h4><?= $canhao["nome"]; ?></h4>
                <p>Chance de acerto: <?= $canhao["bonus"]; ?>%</p>
            </div>
        <?php endif; ?>
    </div>

    <h3>Itens no seu invetário:</h3>

    <?php $items = get_many_results_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", array(
        array("nome" => "tb_item_navio_casco", "coluna" => "cod_casco", "tipo" => TIPO_ITEM_CASCO),
        array("nome" => "tb_item_navio_leme", "coluna" => "cod_leme", "tipo" => TIPO_ITEM_LEME),
        array("nome" => "tb_item_navio_velas", "coluna" => "cod_velas", "tipo" => TIPO_ITEM_VELAS),
        array("nome" => "tb_item_navio_canhao", "coluna" => "cod_canhao", "tipo" => TIPO_ITEM_CANHAO),
    ), "WHERE origem.id = ?", "i", $userDetails->tripulacao["id"]); ?>

    <div class="row">
        <?php foreach ($items as $item) : ?>
            <div class="list-group-item col-md-4">
                <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                <p>
                    <?php if ($userDetails->carpinteiros) : ?>
                        <button class="link_confirm btn btn-primary"
                                data-question="Deseja equipar este item no navio?<br/> Itens já equipados serão perdidos."
                                href="Navio/navio_equipar.php?cod=<?= $item["cod_item"] ?>&tipo=<?= $item["tipo_item"] ?>">
                            Instalar
                        </button>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>