<div class="panel-heading">
    Centro de comércio
</div>

<script type="text/javascript">
    function loadOfertas() {
        $.ajax({
            url: 'Scripts/Dobroes/lista_ofertas.php',
            success: function (retorno) {
                if (!$('#compra-dobrao-unitario').is(":focus")
                    && !$('#compra-dobrao-unitario').val()
                    && !$('#venda-ouro-unitario').is(":focus")
                    && !$('#venda-ouro-unitario').val()
                ) {
                    $('#panel-ofertas').html(retorno);
                    $('#compra-dobrao-unitario').change(function () {
                        var preco = parseInt($('#compra-dobrao-preco').val(), 10);
                        var quant = parseInt($(this).val(), 10);
                        $('#compra-dobrao-total').html(mascaraBerries(preco * quant));
                    });
                    $('#venda-ouro-unitario').change(function () {
                        var preco = parseInt($('#compra-dobrao-preco').val(), 10);
                        var quant = parseInt($(this).val(), 10);
                        $('#venda-ouro-total').html(mascaraBerries(preco * quant));
                    });
                }
                timeOuts['leilao'] = setTimeout(loadOfertas, 5000);
            }
        });
    }

    $(function () {
        loadOfertas();
        $('#input-dobrao-gold').change(function () {
            $('#valor-gold-convertido').html($(this).val());
        });
    });
</script>

<div class="panel-body">

    <p>
        O Centro de comércio realiza compra e venda de Ouro entre os jogadores.
    </p>
    <p>
        Ao vender moedas e ouro para o centro de comércio, você recebe os Berries pela venda imediatamente.
    </p>
    <p>
        O Centro de comércio fará internamente a conversão das Moedas de Ouro por Dobrões de Ouro e os venderá para
        outros jogadores
    </p>

    <div id="panel-ofertas">

    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Histórico de compra por Berries:
        </div>
        <div class="panel-body">
            <?php $result = $connection->run("SELECT * FROM tb_dobroes_leilao_log WHERE comprador_id = ? ORDER BY data DESC LIMIT 20",
                "i", $userDetails->tripulacao["id"]); ?>

            <?php if (!$result->count()) : ?>
                <p>Você ainda não comprou nenhum Dobrão por Berries</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php while ($oferta = $result->fetch_array()): ?>
                        <li class="list-group-item">
                            <p>Quantidade: <?= $oferta["quant"] ?></p>
                            <p>
                                Valor unitário:
                                <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($oferta["preco_unitario"]) ?>
                            </p>
                            <p>
                                Valor total:
                                <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($oferta["quant"] * $oferta["preco_unitario"]) ?>
                            </p>
                            <p>
                                Data da compra:
                                <?= date("d/m/Y - H:i", strtotime($oferta["data"])) ?>
                            </p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Histórico de venda por Berries:
        </div>
        <div class="panel-body">
            <?php $result = $connection->run("SELECT * FROM tb_dobroes_leilao_log WHERE vendedor_id = ? ORDER BY data DESC LIMIT 20",
                "i", $userDetails->tripulacao["id"]); ?>

            <?php if (!$result->count()) : ?>
                <p>Você ainda não vendeu nenhum Dobrão por Berries</p>
            <?php else: ?>
                <ul class="row list-group">
                    <?php while ($oferta = $result->fetch_array()): ?>
                        <div class="list-group-item col-xs-6 col-md-4">
                            <p>Quantidade: <?= $oferta["quant"] ?></p>
                            <p>
                                Valor unitário:
                                <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($oferta["preco_unitario"]) ?>
                            </p>
                            <p>
                                Valor total:
                                <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($oferta["quant"] * $oferta["preco_unitario"]) ?>
                            </p>
                            <p>
                                Data da venda:
                                <?= date("d/m/Y - H:i", strtotime($oferta["data"])) ?>
                            </p>
                    </div>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

</div>