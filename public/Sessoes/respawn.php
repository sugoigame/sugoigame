<?php if ($userDetails->tripulacao_alive) : ?>
    <div class="panel-heading">
        Sua tripulação está bem
    </div>

    <div class="panel-body">
        <a class="btn btn-info link_content" href="./?ses=oceano">
            Ir para o Oceano
        </a>
    </div>
<?php else : ?>
    <div class="panel-heading">
        Você foi derrotado
    </div>

    <div class="panel-body">
        <?php if ($userDetails->in_ilha) : ?>
            <p>
                <a class="btn btn-info link_content" href="./?ses=hospital">
                    Ir para o Hospital
                </a>
            </p>
        <?php else : ?>
            <?php $ilha_retorno = get_ilha_by_coord($userDetails->tripulacao["res_x"], $userDetails->tripulacao["res_y"]); ?>
            <p>
                <button class="btn btn-primary link_confirm"
                    data-question="Deseja voltar para <?= nome_ilha($ilha_retorno["ilha"]) ?>?"
                    href="Mapa/derrotado_respawn.php">
                    Voltar para ilha de retorno (
                    <?= nome_ilha($ilha_retorno["ilha"]) ?>)
                </button>
            </p>
            <?php if ($userDetails->medicos) : ?>
                <p>
                    <button class="btn btn-info link_confirm"
                        data-question="O seu capitão receberá 1 ponto de vida, assim você poderá recuperar seus tripulantes no quarto no navio. Deseja pagar essa quantia em Berries para ficar na coordenada atual?"
                        href="Mapa/derrotado_ficar_coordenada_capitao.php">
                        Recuperar meu capitão e ficar na coordenada atual
                        <img src="Imagens/Icones/Berries.png" />
                        <?= mascara_berries(preco_ficar_coordenada_derrotado_capitao()) ?>
                    </button>
                </p>
            <?php endif; ?>
            <p>
                <button class="btn btn-success link_confirm"
                    data-question="Todos os seus tripulantes receberão 1 ponto de vida, assim você poderá recupera-los utilizando comidas. Deseja pagar essa quantia em Berries para ficar na coordenada atual?"
                    href="Mapa/derrotado_ficar_coordenada_tripulacao.php">
                    Recuperar minha tripulação e ficar na coordenada atual
                    <img src="Imagens/Icones/Berries.png" />
                    <?= mascara_berries(preco_ficar_coordenada_derrotado_tripulacao()) ?>
                </button>
            </p>
        <?php endif; ?>
    </div>
<?php endif; ?>

