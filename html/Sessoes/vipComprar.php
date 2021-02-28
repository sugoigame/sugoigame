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

    <h2>Escolha um plano de doação:</h2>

    <div class="row">
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
                    <h4><img src="Imagens/Icones/Gold.png"/>
                        <strong><?=mascara_numeros_grandes($golds);?></strong></h4>
                    <h4><strong>R$ <?=number_format($plano['valor'], 2, ',', '.');?></strong></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="Scripts/Vip/adquirirPS.php?plano=<?=base64_encode($plano['id']);?>" target="_blank" class="btn btn-block btn-success">Doar com PagSeguro</a>
                        </div>
                        <div class="col-md-6">
                        	<?php if ($userDetails->tripulacao['adm']): ?>
	                            <a href="Scripts/Vip/adquirirPP.php?plano=<?=base64_encode($plano['id']);?>" target="_blank" class="btn btn-block btn-success">Doar com PayPal</a>
                            <?php else: ?>
                            	<button type="button" disabled class="btn btn-block btn-disabled btn-success">Doar com PayPal</a>
                        	<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            }
        }
        ?>
    </div>
</div>