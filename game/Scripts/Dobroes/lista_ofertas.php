<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$valor_dobrao = calc_cotacao_dobrao();
?>
<ul class="list-group">
    <li class="list-group-item">
        <h3>
            A cotação do Ouro neste momento é de
            <img src="Imagens/Icones/Berries.png"/>
            <?= mascara_berries($valor_dobrao) ?>
        </h3>
        <form class="ajax_form" action="Dobroes/vender" method="post"
              data-question="Deseja mesmo vender estas Moedas de Ouro por Berries?">
            <input id="compra-dobrao-preco" name="preco_unitario" type="hidden"
                   value="<?= $valor_dobrao ?>">
            <label>Informe a quantidade de Moedas de Ouro que deseja vender:</label>
            <h4>
                <img src="Imagens/Icones/Gold.png"/>
                <input id="venda-ouro-unitario" name="quant" type="number" min="1"
                       max="<?= min($userDetails->conta["gold"], 100) ?>" class="form-control"
                       style="width: 100px;display: inline-block" required>
                =
                <img src="Imagens/Icones/Berries.png">
                <span id="venda-ouro-total">0</span>
                <button class="btn btn-info">Vender</button>
            </h4>
        </form>
        <p>
            ou
        </p>
        <form class="ajax_form" action="Dobroes/comprar" method="post"
              data-question="Deseja mesmo comprar estes Dobrões por Berries?">
            <input id="compra-dobrao-preco" name="preco_unitario" type="hidden"
                   value="<?= $valor_dobrao ?>">
            <label>Informe a quantidade de Dobrões que deseja comprar:</label>
            <h4>
                <img src="Imagens/Icones/Berries.png"/>
                <span id="compra-dobrao-total">0</span>
                =
                <input id="compra-dobrao-unitario" name="quant" type="number" min="1"
                       max="100" class="form-control"
                       style="width: 100px;display: inline-block" required>
                <img src="Imagens/Icones/Dobrao.png">
                <button class="btn btn-success">Comprar</button>
            </h4>
        </form>
    </li>
</ul>
