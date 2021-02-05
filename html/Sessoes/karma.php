<?php function render_buy_buttom($preco, $buff, $tipo) { ?>
    <?php global $userDetails; ?>
    <button class="btn btn-<?= $tipo == TIPO_KARMA_BOM ? "info" : "danger" ?> link_confirm"
            href="Karma/comprar.php?buff=<?= $buff ?>&tipo=<?= $tipo ?>"
            data-question="Deseja gastar <?= $preco ?> pontos de Karma <?= $tipo ?> para comprar esse benefício?"
        <?= $userDetails->tripulacao["karma_$tipo"] >= $preco && !$userDetails->buffs->has_buff($buff) ? "" : "disabled" ?>>
        Comprar
    </button>
<?php } ?>

<div class="panel-heading">
    Karma
</div>

<div class="panel-body">
    <?= ajuda("Karma", "Os pontos de Karma são obtidos ao realizar missões na ilha e podem ser usados para conprar bonus especiais.") ?>

    <?php render_karma_bars(); ?>

    <h3>Recompensas por Karma Bom:</h3>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 250px">
                <p><img src="Imagens/Icones/bonus_1.jpg"></p>
                <h4>Confiança de Herói</h4>
                <p>Sua convicção na vitória torna seus golpes mais efetivos</p>
                <p>Aumenta em 20% o dano causado pela sua tripulação ao enfrentar qualquer NPC exceto em incursões por 24
                    horas</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_1 ?> pontos de Karma Bom</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_1, 1, "bom"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 250px">
                <p><img src="Imagens/Icones/bonus_1.jpg"></p>
                <h4>Postura Honrosa</h4>
                <p>Sua postura firme e honrosa amedronta até a mais poderosa das criaturas</p>
                <p>Reduz em 20% o dano sofrido pela sua tripulação ao enfrentar qualquer NPC exceto em incursões por 24
                    horas</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_2 ?> pontos de Karma Bom</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_2, 2, "bom"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 250px">
                <p><img src="Imagens/Icones/bonus_1.jpg"></p>
                <h4>Amizade com os mercadores</h4>
                <p>Seu carisma surte tanto efeito nos mercadores que você consegue negociar melhor a seu favor</p>
                <p>Durante 24 horas o mercado das ilhas paga 25% mais caro pelos itens que você vender.</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_3 ?> pontos de Karma Bom</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_3, 3, "bom"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 250px">
                <p><img src="Imagens/Icones/bonus_1.jpg"></p>
                <h4>Sede de Aventura</h4>
                <p>Sua tripulação está tão motivada que até seu navio veleja mais rápido</p>
                <p>Aumenta em 10% a velocidade de navegação do seu barco por 24 horas</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_4 ?> pontos de Karma Bom</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_4, 4, "bom"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 250px">
                <p><img src="Imagens/Icones/bonus_1.jpg"></p>
                <h4>Trabalho bem feito</h4>
                <p>Seus mergulhadores e arqueólogos estão muito felizes, isso colabora para um trabalho bem feito</p>
                <p>Reduz em 15 minutos o tempo de espera para se realizar um novo Mergulho ou Exploração por 24 horas</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_5 ?> pontos de Karma Bom</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_5, 5, "bom"); ?>
            </div>
        </div>
    </div>
    <!-- <br /> -->
    <h3>Recompensas por Karma Mau:</h3>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 290px">
                <p><img src="Imagens/Icones/bonus_3.jpg"></p>
                <h4>Saqueador Experiente</h4>
                <p>Você usa seu aprendizado sobre ladinagem para roubar mais dinheiro dos seus inimigos</p>
                <p>Toda vez que saquear um jogador nas próximas 24 horas você receberá 20% dos Berries daquele jogador em
                    vez de 10%</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_1 ?> pontos de Karma Mau</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_1, 6, "mau"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 290px">
                <p><img src="Imagens/Icones/bonus_3.jpg"></p>
                <h4>Reputação Perigosa</h4>
                <p>Seus atos tem influenciado as autoridades locais, elas estão mais sujeitas a aumentar a recompensa pela
                    sua cabeça</p>
                <p>Nas próximas 24 horas, toda vez que um de seus tripulantes derrotar um tripulante inimigo, o preço de sua
                    recompensa será aumentado com um bônus de 20%</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_2 ?> pontos de Karma Mau</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_2, 7, "mau"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 290px">
                <p><img src="Imagens/Icones/bonus_3.jpg"></p>
                <h4>Nome Famoso</h4>
                <p>O seu nome já é conhecido por essas bandas, as autoridades não querem mais abaixar a recompensa pela sua
                    cabeça.</p>
                <p>Nas próximas 24 horas, toda vez que um de seus tripulantes for derrotado por um tripulante inimigo, o
                    preço de sua recompensa terá uma redução 20% menor do que teria normalmente.</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_3 ?> pontos de Karma Mau</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_3, 8, "mau"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 290px">
                <p><img src="Imagens/Icones/bonus_3.jpg"></p>
                <h4>Disparo Certeiro</h4>
                <p>Sua perícia ao usar o canhão alcançou um patamar elevado, você consegue fazer mais estragos nos navios
                    atingidos</p>
                <p>Durante 24 horas, seus disparos de canhão causam o dobro do dano nos navios atingidos.</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_4 ?> pontos de Karma Mau</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_4, 9, "mau"); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="box-item" style="min-height: 290px">
                <p><img src="Imagens/Icones/bonus_3.jpg"></p>
                <h4>Controle melhorado de Haki</h4>
                <p>Sua experiência de combate permite que você utilize seu Haki com perícia o suficiente para controlar os
                    efeitos das suas Akuma no Mi</p>
                <p>Anula todas as vantagens e desvantagens de todas as suas Akuma no Mi por 24 horas</p>
                <p>Preço: <?= PRECO_KARMA_BONUS_5 ?> pontos de Karma Mau</p>
                <?php render_buy_buttom(PRECO_KARMA_BONUS_5, 10, "mau"); ?>
            </div>
        </div>
    </div>
</div>