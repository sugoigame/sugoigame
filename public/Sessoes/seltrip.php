<div class="panel-heading">
    Bem vindo <?= $userDetails->conta["nome"] ?>!
</div>

<script type="text/javascript">
    $(function () {
        $(".remover-trip").on('click', function () {
            var nome = $(this).data('nome');
            var id = $(this).data('trip');
            var hash = $(this).data('hash');
            bootbox.prompt('ATENÇÃO! <br/>Você está prestes a remover a tripulação ' + nome + '. <br/>Uma vez removida não será possível restaura-la.' +
                '<br>Se deseja mesmo continuar digite o código no campo abaixo: <br><br>' + hash, function (result) {
                if (result == hash) {
                    sendGet('Geral/apagar_trip.php?trip=' + id);
                }
            });
        });
    });
</script>

<div class="panel-body">
    <script type="text/javascript">
        <?php include "JS/cadastro.js"; ?>
    </script>
    <style type="text/css">
        <?php include "CSS/cadastro.css"; ?>
    </style>
    <div id="sessoes">
        <div class="sessao" id="sessao_1">
            <?php if (count($userDetails->tripulacoes)): ?>
                <h3>Selecione sua tripulação e começar a navegar!</h3>
                <div class="row">
                <?php foreach ($userDetails->tripulacoes as $trip): ?>
                <div class="col-xs-6 col-sm-4 col-md-2">
                    <div class="box-item">
                        <div class="media text-center">
                            <div class="text-center">
                                    <img src="Imagens/Bandeiras/img.php?cod=<?= $trip["bandeira"]; ?>&f=<?= $trip["faccao"]; ?>" style="    margin-bottom: 10px;" />
                                    <h4 class="media-heading">
                                        <?= $trip["tripulacao"] ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="media text-center">
                                <div class="text-center">
                                    
                                <a href="Scripts/Geral/seltrip.php?trip=<?= $trip["id"] ?>"
                                        class="btn btn-success btn-block link_redirect">
                                        <i class="fa fa-check"></i>
                                        Selecionar
                                    </a>
                                    <button class="remover-trip btn btn-danger btn-block"
                                            data-trip="<?= $trip["id"] ?>"
                                            data-nome="<?= $trip["tripulacao"] ?>"
                                            data-hash="<?= substr(md5(time()), 0, 5) ?>">
                                        <i class="fa fa-times"></i>
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Clique no botão abaixo para criar sua primeira tripulação e começar a navegar!</p>
            <?php endif; ?>
        </div>
        <form action="Scripts/Geral/criartrip.php" id="cria-trip-form" method="post">
            <div class="sessao clearfix" id="sessao_2">
                <h4>Escolha sua facção:</h4>
                <div class="row">
                    <div class="col-md-6">
                        <img src="Imagens/Bandeiras/img.php?cod=010113046758010128123542010115204020&f=0"
                             onclick="document.getElementById('faccao_0').checked='1'" data-toggle="tooltip"
                             data-placement="bottom"
                             title="Marinheiros são defensores da justiça e da ordem! Eles usam bandeiras e velas
                             brancas com símbolos da marinha. Os marinheiros não lutam entre si, eles apenas
                             combatem piratas, tendo a vantagem de ter menos inimigos com que se preocupar."/><br>
                        <input type="radio" name="faccao" value="0" checked="1" id="faccao_0"/> Marinheiro
                    </div>
                    <div class="col-md-6">
                        <img src="Imagens/Bandeiras/img.php?cod=010113046758010128123542010115204020&f=1"
                             onclick="document.getElementById('faccao_1').checked='1'" data-toggle="tooltip"
                             data-placement="bottom"
                             title="Piratas são aventureiros seguindo suas próprias leis!
                             Usando bandeiras pretas estão sempre dando trabalho para marinha.
                             Os piratas enfrentam tudo e todos, tendo assim muitos oponentes para derrotar."/><br>
                        <input type="radio" name="faccao" value="1" id="faccao_1"/>Pirata
                    </div>
                </div>
                <br/>
            </div>
            <div class="sessao clearfix text-left" id="sessao_3">
                <div class="form-group">
                    <label>Apelido da tripulação:</label>
                    <input type="text" id="apelido" name="apelido" size="15"
                           maxlength="15" class="form-control"
                           placeholder="O apelido é o nome da tripulação, ex: Bando do Chapéu de Palha"/>
                    <img id="apelido_status" src="Imagens/Icones/3.gif">
                    <span id="apelido_status_fail" class="fail"></span>
                </div>
                <div class="form-group">
                    <label>Nome do capitão:</label>
                    <input type="text" id="capitao" name="capitao" size="15"
                           maxlength="15" class="form-control"
                           placeholder="O capitão é o líder da sua tripulação, você se comunicará com os outros jogadores usando o nome que der para o seu capitão."/>
                    <img id="capitao_status" src="Imagens/Icones/3.gif">
                    <span id="capitao_status_fail" class="fail"></span>
                </div>
                <h4>Escolha o seu capitão:</h4>
                <input type="hidden" id="icon_capitao" name="icon_capitao"/>
                <div class="row">
                    <div class="col-md-8">
                        <div class="sel-personagem">
                            <?php for ($x = 1; $x <= PERSONAGENS_MAX; $x++): ?>
                                <img class="capitao-selectable-img" data-img="<?= sprintf("%04d", $x) ?>"
                                     src="Imagens/Personagens/Icons/<?= sprintf("%04d", $x) ?>(0).jpg" width="50px">
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <img id="img_capitao" src="Imagens/Personagens/Big/0000.png" width="200px" height="300px">
                    </div>
                </div>
            </div>
            <div class="sessao clearfix" id="sessao_4">
                <h5>Mar de Origem</h5>
                <p>Escolha em qual oceano você quer começar a jogar.</p>
                <input type="radio" name="oceano" value="1"> East Blue <br>
                <?php /*<input type="radio" name="oceano" value="3"> South Blue<br>
                <input type="radio" name="oceano" value="2"> North Blue <br>
                <input type="radio" name="oceano" value="4"> West Blue <br>
                <input type="radio" name="oceano" value="0" checked="1"> Aleatório*/ ?>
                <br><br>
                <button class="btn btn-success" type="submit">
                    Criar minha tripulação <i class="fa fa-check"></i>
                </button>
                <br><br><br><br>
            </div>
        </form>

        <?php if (count($userDetails->tripulacoes) < 3): ?>
            <div id="nav-bts">
                <div class="row">
                    <div class="col-xs-6 col-md-6"><button class="btn btn-info noHref btn-block" id="navegador_voltar">&laquo; Voltar</button></div>
                    <div class="col-xs-6 col-md-6"><button class="btn btn-info noHref btn-block" id="navegador_avancar">Criar Tripulação &raquo;</button></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>