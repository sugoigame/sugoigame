<div class="panel-heading">
    Serviço de vendas por correio Den Den Mushi
</div>

<div class="panel-body">
    <?= ajuda("Serviço de vendas por correio Den Den Mushi", "Faça suas compras em alto mar!") ?>

    <div class="row">
        <div class="col-md-3">
            <img src="Imagens/Vip/vendasDenDen.png" alt="vendas denden"/>
        </div>
        <div class="col-md-9">
            <div class="list-group">
                <div class="list-group-item col-md-4">
                    <img src="Imagens/Itens/370.png"/>
                    <h4>Isca</h4>
                    <p>
                        Tem 30% de chance de iniciar uma batalha contra uma criatura marítmica quando usada.<br/>
                        Não pode ser usada logo após ser atingido por disparos de canhões.</br>
                        Item consumível, não acumulativo
                    </p>
                    <p>
                        Preço: <img src="Imagens/Icones/Berries.png" width="15px"/> 10.000
                    </p>
                    <p>Quantidade: 10</p>
                    <button class="link_send btn btn-info"
                            href="link_Vip/servicoDenDen_comprar.php?cod=0&tipo=16&quant=10">
                        Comprar
                    </button>
                </div>
                <div class="list-group-item col-md-4">
                    <img src="Imagens/Itens/371.png"/>
                    <h4>Isca Dourada</h4>
                    <p>
                        Tem 100% de chance de iniciar uma batalha contra uma criatura marítmica quando usada.<br/>
                        Não pode ser usada logo após ser atingido por disparos de canhões.</br>
                        Item consumível, não acumulativo
                    </p>
                    <p>Quantidade: 10</p>
                    <p>
                        <button class="link_send btn btn-info"
                                href="link_Vip/servicoDenDen_comprar.php?cod=0&tipo=17&quant=10"
                            <?= $userDetails->conta["gold"] < PRECO_GOLD_ISCA_10 ? "disabled" : "" ?>>
                            <?= PRECO_GOLD_ISCA_10 ?> <img src="Imagens/Icones/Gold.png"/> Comprar
                        </button>
                    </p>
                    <p>
                        <button class="link_send btn btn-info"
                                href="link_VipDobroes/servicoDenDen_comprar.php?cod=0&tipo=17&quant=10"
                            <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_ISCA_10 ? "disabled" : "" ?>>
                            <?= PRECO_DOBRAO_ISCA_10 ?> <img src="Imagens/Icones/Dobrao.png"/> Comprar
                        </button>
                    </p>
                </div>
                <div class="list-group-item col-md-4">
                    <img src="Imagens/Itens/371.png"/>
                    <h4>Isca Dourada</h4>
                    <p>
                        Tem 100% de chance de iniciar uma batalha contra uma criatura marítmica quando usada.<br/>
                        Não pode ser usada logo após ser atingido por disparos de canhões.</br>
                        Item consumível, não acumulativo
                    </p>
                    <p>
                    </p>
                    <p>Quantidade: 130</p>
                    <p>
                        <button class="link_send btn btn-info"
                                href="link_Vip/servicoDenDen_comprar.php?cod=0&tipo=17&quant=130"
                            <?= $userDetails->conta["gold"] < PRECO_GOLD_ISCA_130 ? "disabled" : "" ?>>
                            <?= PRECO_GOLD_ISCA_130 ?> <img src="Imagens/Icones/Gold.png"/> Comprar
                        </button>
                    </p>
                    <p>
                        <button class="link_send btn btn-info"
                                href="link_VipDobroes/servicoDenDen_comprar.php?cod=0&tipo=17&quant=130"
                            <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_ISCA_130 ? "disabled" : "" ?>>
                            <?= PRECO_DOBRAO_ISCA_130 ?> <img src="Imagens/Icones/Dobrao.png"/> Comprar
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>