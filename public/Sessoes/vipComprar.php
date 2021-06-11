<?php //if ($userDetails->tripulacao['adm']) { ?>
<div class="panel-heading">Faça uma doação</div>
<div class="panel-body">
    <div class="panel text-left">
        <div class="panel-body">
            <h3>Por que fazer uma doação?</h3>
            <p>
                O Sugoi Game é um jogo gratuito e sem fins lucrativos, e por isso a contribuição de seus jogadores é
                fundamental para que, cada dia mais, o jogo se desenvolva e melhore suas funcionalidades.<br />

                Qualquer tipo de doações feitas ao Sugoi Game serão revertidas em manutenção e melhorias
                ao site, bem como divulgação deste e do anime.<br />

                Ao realizar uma doação para o jogo, você além de nos ajudar a cada dia
                melhorar o Sugoi Game, você jogador receberá uma quantia de <b>Golds</b> referente ao valor da sua doação.<br />

                Os <b>Golds</b> é uma moeda exclusiva dentro do jogo que te permite adquirir vantagens exclusivas.
            </p>

            <h3>Ao fazer uma doação, tenha a certeza que:</h3>
            <ul>
                <!-- <li>Você participará de sorteios exclusivos para doadores.</li> -->
                <li>Os Golds recebidos não irão expirar por falta de uso.</li>
                <li>Todas as tripulações de sua conta podem usufruir dos Golds.</li>
                <li>Estará colaborando com a manutenção e evolução do jogo</li>
            </ul>
            <p>Com a opção PagSeguro, você poderá adquirir moedas com todos os <u>Cartões de Crédito</u>, <b>PIX</b> e <u>Boleto Bancário</u>.
                Ao fazer uma doação por <b>Boleto Bancário</b>, fique ciente que pode demorar <b>até 3 dias úteis</b> para a doação ser processada!</p>
        </div>
    </div>

    <ul class="nav nav-pills nav-justified" id="methods-details-tabs">
        <?php
        $methods = [
			'pagseguro'		=> 'BRL',
			'paypal_eur'	=> 'EUR',
			'paypal_usd'	=> 'USD',
			// 'paypal_brl'	=> 'BRL'
		];
        $symbols = [
            'BRL'   => 'R$',
            'EUR'   => '€',
            'USD'   => '$'
        ];
        ?>
        <?php $i = 1; foreach($methods as $method => $currency) { ?>
        <li class="<?php echo $i == 1 ? 'active' : ''; ?>">
            <a href="#method-<?php echo $method?>-list" role="tab" data-toggle="tab">
                <img src="/Imagens/<?php echo $method . ".png"; ?>" width="147"/>
            </a>
        </li>
        <?php $i++; } ?>
    </ul><br />
    <div class="tab-content">
    <style>
        .vermelho {
            color: #f43b3b !important;
        }
        .verde {
            color: #76e698 !important;
        }
    </style>
    <?php $is_dbl   = $connection->run("SELECT `id` FROM tb_vip_dobro WHERE NOW() BETWEEN data_inicio AND data_fim LIMIT 1")->count(); ?>
	<?php $i = 1; foreach($methods as $method => $currency) { ?>
        <div id="method-<?php echo $method?>-list" class="tab-pane <?php echo $i == 1 ? 'active' : ''; ?>">
            <?php
            $planos = $connection->run("SELECT * FROM tb_vip_planos ORDER BY valor ASC");
            if ($planos->count() > 0) {
                while($plano = $planos->fetch_array()) {
                    $golds = $plano['golds'];
                    if ($plano['bonus'] > 0)
                        $golds = $plano['golds'] * (($plano['bonus'] / 100) + 1);
            ?>
                <div class="col-xs-12 col-md-4">
                    <div class="box-item">
                        <h3><?=$plano['nome'];?></h3>
                        <h4>
                            <img src="Imagens/Icones/Gold.png" />
                            <span class="amarelo_claro" style="font-size: 16px; margin-left: 5px; top: 2px; position: relative">
                                <?php if ($is_dbl) { ?>
                                    <span class="vermelho" style="text-decoration: line-through; font-size: 12px"><?=mascara_numeros_grandes($golds);?></span>
                                    <b class="verde"><?=mascara_numeros_grandes($golds * 2);?></b>
                                <?php } else { ?>
                                    <?=mascara_numeros_grandes($golds);?>
                                <?php } ?>
                            </span>
                        </h4>
                        <h4><strong> <?=($symbols[$currency] . ' ' . number_format($plano['valor_' . strtolower($currency)], 2, ',', '.'));?></strong></h4>
                        <?php if ($method == 'pagseguro') { ?>
                            <a href="Scripts/Vip/adquirirPS.php?plano=<?=base64_encode($plano['id']);?>" target="_blank" class="btn btn-success">Fazer doação</a>
                        <?php } else { ?>
                            <a href="Scripts/Vip/adquirirPP.php?plano=<?=base64_encode($plano['id']);?>&method=<?php echo $method?>" target="_blank" class="btn btn-success">Fazer doação</a>
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            }
            ++$i;
            ?>
        </div>
    <?php } ?>
</div>
<?php //} ?>
