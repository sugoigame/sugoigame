<div class="panel-heading">
    Missões repetitivas
</div>

<script type="text/javascript">
    $(function () {
        timeOuts["tempo_mis"] = setTimeout("tempo_mis()", 1000);
    });

    var conta_mis = 1;
    function tempo_mis() {
        if (document.getElementById("tempo_sec") != null) {
            var tempo = document.getElementById("tempo_sec").value;
            tempo -= conta_mis;
            if (tempo <= 0) {
                enviarNotificacao('Missão concluída!', {
                    body: 'Sua tripulação concluiu a missão.',
                    icon: 'https://sugoigame.com.br/Imagens/favicon.png',
                    sound: 'Sons/tada.mp3'
                });
                reloadPagina();
            } else {
                if (tempo) {
                    document.title = '[' + transforma_tempo(tempo) + '] ' + gameTitle;
                } else {
                    document.title = gameTitle;
                }
                timeOuts["tempo_mis"] = setTimeout("tempo_mis()", 1000);
            }
            document.getElementById("tempo_min").innerHTML = transforma_tempo(tempo);
            conta_mis++;
        }
    }

    $(function () {
        $("#iniciar_missao_r").click(function () {
            var duracao = $("#duracao_missao_r").val();
            var obj = {
                duracao: duracao
            };
            var pagina = "Missoes/missoes_r_iniciar";
            sendForm(pagina, obj);
        });
    });
</script>
<div class="panel-body">
    <?= ajuda("Missões repetitivas", "Precisando de dinheiro? Aqui você pode fazer tarefas que vão lhe permitir sair da miséria."); ?>

    <?php $missao_r_ini = $connection->run("SELECT * FROM tb_missoes_r WHERE id = ?",
        "i", array($userDetails->tripulacao["id"])); ?>

    <?php if ($missao_r_ini->count()): ?>
        <?php $missao = $missao_r_ini->fetch_array(); ?>
        <?php if ($missao["fim"] > atual_segundo()) : ?>
            <p>
                Tempo restante:
                <span id="tempo_min"><?= transforma_tempo_min($missao["fim"] - atual_segundo()); ?></span>
                <input style="display: none" id="tempo_sec" value="<?= ($missao["fim"] - atual_segundo()); ?>">
            </p>
            <button href="Missoes/missoes_r_cancelar.php" data-question="Deseja cancelar essa missão?"
                    class="link_confirm btn btn-danger">
                Cancelar
            </button>
        <?php else: ?>
            <button href="link_Missoes/missoes_r_finalizar.php" class="link_send btn btn-success">
                Finalizar
            </button>
        <?php endif; ?>
    <?php else: ?>
        <?php $missao_r_dia = $connection->run("SELECT * FROM tb_missoes_r_dia WHERE id = ? AND x = ? AND y = ?",
            "iii", array($userDetails->tripulacao["id"], $userDetails->tripulacao["x"], $userDetails->tripulacao["y"])); ?>

        <?php if ($missao_r_dia->count()): ?>
            <p>Você já esteve aqui hoje, volte amanhã para pegar mais recompensas.</p>
        <?php else: ?>
            <?php if ($userDetails->tripulacao["faccao"] == 0) : ?>
                <p>
                    Você está sem dinheiro para recuperar sua tripulação ou seu navio?
                </p>
                <p>
                    As pessoas da ilha podem lhe dar dinheiro se você as ajudar, mas você só pode ajudar as pessoas
                    dessa ilha uma vez por dia, pois existem vários marinheiros por aqui também.
                </p>
                <p>
                    Escolha a duração dos seus trabalhos:
                </p>
            <?php else : ?>
                <p>
                    Você está sem dinheiro para recuperar sua tripulação ou seu navio?
                </p>
                <p>
                    Você pode sair atrás de dinheiro por aí, dizem que existem vários tesouros escondidos nessa ilha.
                    Mas lembre-se, você só pode ir atrás de tesouros nessa ilha uma vez por dia, se ficar dando mole
                    algum marinheiro pode te encontrar.
                </p>
                <p>
                    Escolha a duração da sua aventura:
                </p>
            <?php endif ?>
            <div class="form-inline">
                <div class="form-group">
                    <select id="duracao_missao_r" class="form-control">
                        <option value="1">00:30:00 | 30.000 berries + 450 exp</option>
                        <option value="2">01:00:00 | 60.000 berries + 900 exp</option>
                        <option value="3">01:30:00 | 90.000 berries + 1.350 exp</option>
                        <option value="4">02:00:00 | 120.000 berries + 1.800 exp</option>
                        <option value="5">03:00:00 | 180.000 berries + 2.700 exp</option>
                        <option value="6">04:00:00 | 240.000 berries + 3.600 exp</option>
                        <option value="7">08:00:00 | 480.000 berries + 7.200 exp</option>
                    </select>
                    <button id="iniciar_missao_r" class="btn btn-success">
                        Iniciar
                    </button>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>