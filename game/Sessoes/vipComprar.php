<?php function formPagSeguro($cod, $desc, $price) {
    global $userDetails; ?>
    <form method="post" target="pagseguro"
          action="https://pagseguro.uol.com.br/v2/checkout/payment.html">
        <!--  action="/pagseguro/checkout.php" -->
        <!--  action="https://pagseguro.uol.com.br/v2/checkout/payment.html" -->
        <!-- Campos obrigatórios -->
        <input name="receiverEmail" type="hidden" value="ivan.i.n@hotmail.com">
        <input name="currency" type="hidden" value="BRL">

        <!-- Itens do pagamento (ao menos um item é obrigatório) -->
        <input name="itemId1" type="hidden" value="<?= $cod ?>">
        <input name="itemDescription1" type="hidden" value="<?= $desc ?>">
        <input name="itemAmount1" type="hidden" value="<?= $price ?>">
        <input name="itemQuantity1" type="hidden" value="1">

        <!-- Código de referência do pagamento no seu sistema (opcional) -->
        <input name="reference" type="hidden" value="<?= $userDetails->conta["conta_id"]; ?>">

        <!-- submit do form (obrigatório) -->
        <input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/pagamentos/205x30-comprar.gif"
               name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!"/>
    </form>
<?php } ?>

<?php function formPayPal($cod, $desc, $price) { ?>
    <?php return ""; ?>
    <form class="wps-bn" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <!--Tipo do botão-->
        <input type="hidden" name="cmd" value="_xclick"/>

        <!--Vendedor e URL de retorno, cancelamento e notificação-->
        <input type="hidden" name="business" value="teste@empresa.com.br"/>
        <input type="hidden" name="return" value="http://localhost/sugoigame/game/index.php?ses=vipComprar&"/>
        <input type="hidden" name="cancel" value="http://localhost/sugoigame/game/index.php?ses=vipComprar&"/>
        <input type="hidden" name="notify_url" value="http://loja.com.br/notificacao"/>

        <!--Internacionalização e localização da página de pagamento-->
        <input type="hidden" name="charset" value="utf-8"/>
        <input type="hidden" name="lc" value="BR"/>
        <input type="hidden" name="country_code" value="BR"/>
        <input type="hidden" name="currency_code" value="BRL"/>

        <!--Informações sobre o produto e seu valor-->
        <input type="hidden" name="amount" value="<?= $price ?>"/>
        <input type="hidden" name="item_name" value="<?= $desc ?>"/>
        <input type="hidden" name="quantity" value="1"/>

        <!--Botão para submissão do formulário-->
        <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_buynowCC_LG.gif"/>
    </form>
<?php } ?>

<div class="panel-heading">
    Adquira Moedas de Ouro
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //Pegamos o formulário do botão
        var wpsBn = jQuery('.wps-bn');

        //Interceptamos o clique no botão
        wpsBn.click(function (e) {
            //Evitamos o comportamento padrão, de submeter o formulário
            e.preventDefault();

            //Mostramos a mensagem de redirecionamento
            jQuery('<div class="sa_payPal_overlay" style="visibility:visible;position:fixed; width:100%; height:100%; filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=1, StartColorStr=\'#88ffffff\', EndColorStr=\'#88ffffff\'); background: rgba(255,255,255,0.8); top:0; left:0; z-index: 999999;"><div style=" background: #FFF; background-image: linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -o-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -moz-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -webkit-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -ms-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -webkit-gradient(linear, left top,left bottom,color-stop(0.45, #FFFFFF),color-stop(0.8, #E9ECEF));display: block;margin: auto;position: fixed; margin-left:-220px; left:45%;top: 40%;text-align: center;color: #2F6395;font-family: Arial;padding: 15px;font-size: 15px;font-weight: bold;width: 530px;-webkit-box-shadow: 3px 2px 13px rgba(50, 50, 49, 0.25);box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 0px 5px;border: 1px solid #CFCFCF;border-radius: 6px;"><img style="display:block;margin:0 auto 10px" src="https://www.paypalobjects.com/en_US/i/icon/icon_animated_prog_dkgy_42wx42h.gif"><h2>Aguarde alguns segundos.</h2> <p style="font-size:13px; color: #003171; font-weight:400">Você está sendo redirecionado para um ambiente seguro do PayPal<br /> para finalizar seu pagamento.</p><div style="margin:30px auto 0;"><img src="https://www.paypal-brasil.com.br/logocenter/util/img/logo_paypal.png"/></div></div></div>').appendTo('body');

            //Submetemos o formulário após a exibição da mensagem
            wpsBn.submit();
        });
    });
</script>

<div class="panel-body">
    <div class="panel text-left">
        <div class="panel-body">
            <h3>Sobre as moedas de ouro</h3>
            <p>
                O Sugoi Game é um jogo gratuito e sem fins lucrativos, e por isso a contribuição de seus jogadores é
                fundamental para que, cada dia mais, o jogo se desenvolva e melhore suas funcionalidades.
            </p>
            <p>
                Qualquer tipo de arrecadação ou doações feitas ao Sugoi Game serão revertidas em manutenção e melhorias
                ao site, bem como divulgação deste e do anime.
            </p>
            <p>
                Contribuindo com sua doação ao jogo, além de nos ajudar a cada dia
                melhorar o Sugoi Game, você, jogador tem acesso à vantagens exclusivas.
            </p>
            <p>
                As moedas de ouro são um tipo de dinheiro que te permite comprar vantagens dentro do jogo.
            </p>

            <h3>Ao adquirir seu Plano tenha a certeza que:</h3>

            <ul>
                <li>As moedas não expiram por falta de uso.</li>
                <li>Todas as tripulações de sua conta podem usufluir dos créditos.</li>
                <li>Estará colaborando com a manuntenção e evolução do jogo</li>
            </ul>
            <p>
                Com a opção PagSeguro, você poderá adquirir moedas com todos os <u>Cartões de Crédito</u> e <u>Boletos
                    Bancários</u>
            </p>
            <p>
                Na aquisição por Boleto Bancário, pode demorar até no máximo 3 dias úteis para o pagamento ser
                processado e as moedas ficarem disponíveis
            </p>
        </div>
    </div>

    <h3>Escolha seu pacote:</h3>

    <div class="row">
        <div class="list-group-item col-md-4">
            <h4>Baú de Papel</h4>
            <h3><strong>500</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 4,99</strong></p>
            <p><?php formPagSeguro(1, "Bau de Papel", "4.99") ?></p>
            <p><?php formPayPal(1, "Bau de Papel", "4.99") ?></p>
        </div>
        <div class="list-group-item col-md-4">
            <h4>Baú de Madeira</h4>
            <h3><strong>1.050</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 9,99</strong></p>
            <p><?php formPagSeguro(2, "Bau de Madeira", "9.99") ?></p>
            <p><?php formPayPal(2, "Bau de Madeira", "9.99") ?></p>
        </div>
        <div class="list-group-item col-md-4">
            <h4>Baú de Ferro</h4>
            <h3><strong>2.200</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 19,99</strong></p>
            <p><?php formPagSeguro(3, "Bau de Ferro", "19.99") ?></p>
            <p><?php formPayPal(3, "Bau de Ferro", "19.99") ?></p>
        </div>
        <div class="list-group-item col-md-4">
            <h4>Baú de Bronze</h4>
            <h3><strong>3.300</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 29,99</strong></p>
            <p><?php formPagSeguro(4, "Bau de Bronze", "29.99") ?></p>
            <p><?php formPayPal(4, "Bau de Bronze", "29.99") ?></p>
        </div>
        <div class="list-group-item col-md-4">
            <h4>Baú de Prata</h4>
            <h3><strong>4.400</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 39,99</strong></p>
            <p><?php formPagSeguro(5, "Bau de Prata", "39.99") ?></p>
            <p><?php formPayPal(5, "Bau de Prata", "39.99") ?></p>
        </div>
        <div class="list-group-item col-md-4">
            <h4>Baú de Ouro</h4>
            <h3><strong>5.500</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 49,99</strong></p>
            <p><?php formPagSeguro(6, "Bau de Ouro", "49.99") ?></p>
            <p><?php formPayPal(6, "Bau de Ouro", "49.99") ?></p>
        </div>
        <div class="list-group-item col-md-4">
            <h4>Baú de Diamante</h4>
            <h3><strong>11.000</strong> <img src="Imagens/Icones/Gold.png"/></h3>
            <p><strong>R$ 99,99</strong></p>
            <p><?php formPagSeguro(7, "Bau de Diamante", "99.99") ?></p>
            <p><?php formPayPal(7, "Bau de Diamante", "99.99") ?></p>
        </div>
    </div>

</div>