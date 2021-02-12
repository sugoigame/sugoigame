<?php
$recrutados = $connection->run("SELECT img FROM tb_personagens WHERE id = ?", "i", $userDetails->tripulacao["id"])->fetch_all_array();

function can_recruit($img) {
    global $recrutados;

    foreach ($recrutados as $pers) {
        if ($pers["img"] == $img) {
            return false;
        }
    }
    return true;
}

?>

<div class="panel-heading">
    Recrutamento
</div>
<script type="text/javascript">
    $(function () {
        timeOuts["atualiza_tempo"] = setTimeout("atualiza_tempo()", 1000);
        $('#recrutar-lvl-gold').change(function () {
            document.getElementById('recrutar-lvl-dobrao').checked = false;
        });
        $('#recrutar-lvl-dobrao').change(function () {
            document.getElementById('recrutar-lvl-gold').checked = false;
        });
    });
    var conttmp = 0;
    function atualiza_tempo() {
        var sec_rest = "tempo_sec";
        var min_rest = "tempo_min";
        if (document.getElementById(sec_rest) != null) {
            timeOuts["atualiza_tempo"] = setTimeout("atualiza_tempo()", 1000);
            var tmp = document.getElementById(sec_rest).innerHTML - conttmp;
            document.getElementById(min_rest).innerHTML = transforma_tempo(tmp);
        }
        if (tmp < 0) {
            enviarNotificacao('Recrutamento concluído!', {
                body: 'Veja quem está interessado em entrar na sua tripulação.',
                icon: 'https://sugoigame.com.br/Imagens/favicon.png',
                sound: 'Sons/tada.mp3'
            });
            reloadPagina();
        } else {
            if (tmp) {
                    document.title = '[' + transforma_tempo(tmp) + '] ' + gameTitle;
                } else {
                    document.title = gameTitle;
                }
        }
        conttmp += 1;
    }
    var otroobj = 0;
    function recruta(img, obj) {
        obj.style.border = "2px solid #FF0000";
        obj.style.margin = "-2px";
        if (otroobj != 0) {
            otroobj.style.border = "0px solid #FF0000";
            otroobj.style.margin = "0px";
        }
        otroobj = obj;
        document.getElementById("input_img").value = img;

    }
    function verifica_pers() {

        if (document.getElementById("input_nome").value.length >= 3) {
            $.ajax({
                type: 'get',
                data: 'capitao=' + document.getElementById("input_nome").value,
                url: 'Scripts/Verificadores/verifica_cadastro_capitao.php',
                cache: false,
                success: function (retorno) {
                    retorno = retorno.trim();
                    if (retorno == 1) {
                        //ok
                        document.getElementById("status").src = "Imagens/Icones/1.gif";
                        document.getElementById("status_text").innerHTML = "";
                    } else {
                        //fail
                        document.getElementById("status").src = "Imagens/Icones/0.gif";
                        document.getElementById("status_text").innerHTML = "O nome do capitão informado já está cadastrado";
                    }
                }
            });
        } else {
            document.getElementById("status").src = "Imagens/Icones/0.gif";
            document.getElementById("status_text").innerHTML = "O nome deve conter no mínimo 3 caracteres.";
        }

        var lvl = parseInt($('#input_lvl').val(), 10);
        if (lvl > 1) {
            var precoGold   = (lvl - 1) * <?= PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO ?>;
            var precoDobrao = (lvl - 1) * <?= PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO ?>;
            $('#preco-lvl-gold').html(precoGold);
            $('#preco-lvl-dobrao').html(precoDobrao);
        } else {
            $('#preco-lvl-gold').html(0);
            $('#preco-lvl-dobrao').html(0);
        }
    }
    function verifica_form() {
        var mensagem = "Os seguintes erros foram encontrados <br>";
        var erro = false;

        if (document.getElementById("input_nome").value.length < 3) {
            mensagem += "<br>* O nome deve conter no minimo 5 caracteres";
            erro = true;
        }
        if (document.getElementById("input_img").value.length < 4) {
            mensagem += "<br>* Você deve selecionar um personagem";
            erro = true;
        }
        var lvl = parseInt($('#input_lvl').val(), 10);
        if (lvl > 1 && !document.getElementById('recrutar-lvl-dobrao').checked && !document.getElementById('recrutar-lvl-gold').checked) {
            mensagem += "<br>* Você deve selecionar uma forma de pagamento caso queira recrutar um novo tripulante em nível alto";
            erro = true;
        }
        if (erro) {
            bootbox.alert(mensagem);
        } else {
            var nome = document.getElementById("input_nome").value;
            var img = document.getElementById("input_img").value;
            var tipoLvl = document.getElementById("recrutar-lvl-gold").checked
                ? 'gold'
                : document.getElementById("recrutar-lvl-dobrao").checked
                    ? 'dobrao'
                    : '';

            var obj = {
                nome: nome,
                img: img,
                lvl: lvl,
                tipoLvl: tipoLvl
            };
            var pagina = "Recrutar/recrutamento_finalizar";
            sendForm(pagina, obj);
        }
        return false;
    }
</script>

<div class="panel-body">
    <?= ajuda("Recrutamento", "Espalhe cartazes pela ilha para que personagens que se interessarem em entrar para sua 
    tripulação apareçam, depois você poderá selecionar seu novo tripulante.") ?>

    <?php $ilha_personagens_db = $connection->run("SELECT * FROM tb_ilha_personagens WHERE ilha = ?",
        "i", $userDetails->ilha["ilha"])->fetch_all_array(); ?>

    <?php $personagens_skins = $connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ? GROUP BY img",
        "i", $userDetails->tripulacao["id"])->fetch_all_array(); ?>

    <?php $ilha_personagens = array();
    foreach (array_merge($ilha_personagens_db, $personagens_skins) as $ilha_pers) {
        if (can_recruit($ilha_pers["img"])) {
            $ilha_personagens[] = $ilha_pers;
        }
    } ?>

    <?php if (!$userDetails->tripulacao["recrutando"]) : ?>
        <?php if ($userDetails->navio) : ?>
            <?php if (count($ilha_personagens) <= 0): ?>
                <p>Você já recrutou todos os personagens dessa ilha</p>
            <?php elseif (count($userDetails->personagens) < $userDetails->navio["limite"]) : ?>
                <h4>Está pronto para encontrar um novo companheiro?</h4>
                <p>
                    <button href="link_Recrutar/recrutamento_iniciar.php" class="link_send btn btn-success">
                        Procurar tripulantes
                    </button>
                </p>
            <?php else: ?>
                Seu navio não tem espaço para mais tripulantes.
            <?php endif; ?>
        <?php else : ?>
            Você precisa de um navio.
        <?php endif; ?>
    <?php elseif ($userDetails->tripulacao["recrutando"] > atual_segundo()) : ?>
        <h4>Recrutamento em andamento...</h4>
        Tempo restante:
        <span id="tempo_min"><?= transforma_tempo_min($userDetails->tripulacao["recrutando"] - atual_segundo()) ?></span>
        <span id="tempo_sec"
              style="display: none;"><?= ($userDetails->tripulacao["recrutando"] - atual_segundo()) ?></span>
        <p>
            <button href="Recrutar/recrutamento_cancelar.php" data-question="Deseja cancelar o recrutamento?"
                    class="link_confirm btn btn-danger">
                Cancelar
            </button>
        </p>
    <?php else : ?>
        <h4>Recrutamento concluído!</h4>
        <p>
            Os seguintes <?= ($userDetails->tripulacao["faccao"] == 0) ? "marinheiros" : "piratas" ?> se
            interessaram
            em entrar na sua tripulação:
        </p>
        <div class="row">
            <?php foreach ($ilha_personagens as $ilha_pers): ?>
                <div class="list-group-item col-md-2">
                    <img style="cursor: pointer"
                         onclick="recruta('<?= sprintf("%04d", $ilha_pers["img"]) ?>',this)"
                         src="Imagens/Personagens/Icons/<?= sprintf("%04d", $ilha_pers["img"]) ?>(0).jpg">
                </div>
            <?php endforeach; ?>
        </div>
        <br/>
        <form class="form-inline" onsubmit="event.preventDefault(); verifica_form()">
            <div id="form_recruta">
                <div class="form-group">
                    <label> Nome:</label>
                    <input id="input_nome" class="form-control" name="nome" value="" maxlength="15"
                           onblur="verifica_pers()"
                           onkeyup="verifica_pers();this.value=removeCaracteres2(this.value);">
                </div>
                <img id="status" src="Imagens/Icones/3.gif"/><br>
                <span id="status_text" class="status_fail"></span>
                <br/>
                <br/>
                <div class="form-group">
                    <label> Nível do novo tripulante:</label>
                    <input id="input_lvl" class="form-control" name="lvl" value="1" type="number" min="1"
                           max="<?= $userDetails->capitao["lvl"]; ?>"
                           onchange="verifica_pers()">
                </div>
                <div class="form-group">
                    <label>
                        <input name="gold" type="checkbox" id="recrutar-lvl-gold">
                        <span id="preco-lvl-gold">0</span> <img src="Imagens/Icones/Gold.png">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input name="dobrao" type="checkbox" id="recrutar-lvl-dobrao">
                        <span id="preco-lvl-dobrao">0</span> <img src="Imagens/Icones/Dobrao.png">
                    </label>
                </div>
                <br/>
                <input id="input_img" name="img" value="" readonly="true" type="hidden"/><br>

                <button type="button" href="link_Recrutar/recrutamento_cancelar.php"
                        class="link_send btn btn-danger">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-success">
                    Recrutar
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>