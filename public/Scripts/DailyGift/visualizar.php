<?php
require "../../Includes/conectdb.php";

$recompensas = DataLoader::load("daily_gift");

$reagents_db = $connection->run("SELECT * FROM tb_item_reagents")->fetch_all_array();
$reagents = array();
foreach ($reagents_db as $reagent) {
    $reagents[$reagent["cod_reagent"]] = $reagent;
}
$equipamentos_db = $connection->run("SELECT * FROM tb_equipamentos")->fetch_all_array();
$equipamentos = array();
foreach ($equipamentos_db as $equip) {
    $equipamentos[$equip["item"]] = $equip;
}
$comidas_db = MapLoader::load("comidas");
$comidas = array();
foreach ($comidas_db as $comida) {
    $comidas[$comida["cod_comida"]] = $comida;
}

function dias_restantes_color($dias_restantes)
{
    if ($dias_restantes <= 7) {
        return "danger";
    } elseif ($dias_restantes <= 15) {
        return "warning";
    } elseif ($dias_restantes <= 30) {
        return "success";
    } else {
        return "info";
    }
}

$missoes = DataLoader::load("missoes_caca");
$rdms = DataLoader::load("rdm");

$novos_mini_eventos = $connection->run("SELECT count(*) AS total FROM tb_mini_eventos WHERE inicio > DATE_SUB(NOW(), INTERVAL 5 MINUTE) ")->fetch_array()["total"];

?>
<?php function render_evento_ativo($session, $name, $evento, $ranking = null)
{ ?>
    <?php $tempo_total = $evento["end"] - $evento["start"] ?>
    <?php $dias_totais = round($tempo_total / (60 * 60 * 24)); ?>
    <?php $tempo_restante = $evento["end"] - time() ?>
    <?php $dias_restantes = round($tempo_restante / (60 * 60 * 24)); ?>
    <div class="list-group-item col-md-12">
        <h4>
            <?= $name ?>
        </h4>
        <h5>
            De
            <?= date("d/m/Y H:i:s", $evento["start"]) ?> até
            <?= date("d/m/Y H:i:s", $evento["end"]) ?>
        </h5>
        <?php if ($dias_totais > 0) : ?>
            <div class="progress">
                <div class="progress-bar progress-bar-<?= dias_restantes_color($dias_restantes) ?>"
                    style="width: <?= ($dias_totais - $dias_restantes) / $dias_totais * 100 ?>%">
                    <a href="./?ses=<?= $session ?>" data-dismiss="modal" class="link_content">
                        Restante:
                        <?= $dias_restantes ?>
                        dias
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($session) : ?>
            <a href="./?ses=<?= $session ?>" data-dismiss="modal" class="link_content btn btn-info">
                Recompensas
            </a>
        <?php endif; ?>
        <?php if ($ranking) : ?>
            <a href="./?ses=<?= $ranking ?>" data-dismiss="modal" class="link_content btn btn-success">
                Ranking
            </a>
        <?php endif; ?>
    </div>
<?php } ?>
<div class="modal-body">
    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="active">
                <a href="#calendar-tab-presentes" data-toggle="tab">
                    Presentes Diários
                    <?= ! $userDetails->tripulacao["presente_diario_obtido"] ? '<span class="label label-danger">1</span>' : ""; ?>
                </a>
            </li>
            <li>
                <a href="#calendar-tab-eventos" data-toggle="tab">
                    Eventos Ativos
                </a>
            </li>
            <li>
                <a href="#calendar-tab-pvp" data-toggle="tab">
                    Calendário PvP
                </a>
            </li>
            <li>
                <a href="#calendar-tab-missoes-diarias" data-toggle="tab">
                    Missões diárias
                </a>
            </li>
            <li>
                <a href="#calendar-tab-mini-eventos" data-toggle="tab">
                    Mini eventos
                    <?= $novos_mini_eventos ? ('<span class="label label-danger">' . $novos_mini_eventos . '</span>') : ""; ?>
                </a>
            </li>
        </ul>
    </div>
    <br />
    <div class="tab-content">
        <div class="tab-pane active" id="calendar-tab-presentes">
            <h4>Conecte-se ao Sugoi Game diariamente para ganhar um novo presente a cada dia</h4>
            <div class="row">
                <?php foreach ($recompensas as $day => $recompensa) : ?>
                    <div class="list-group-item col-md-3 <?= $userDetails->tripulacao["presente_diario_count"] > $day ? "text-muted" : "" ?>"
                        style="height: 200px">
                        <h4><i class="fa fa-gift"></i>
                            <?= $day + 1 ?>º dia
                        </h4>
                        <p>
                            <img src="Imagens/Icones/Berries.png">
                            <?= mascara_berries($recompensa["berries"]) ?>
                        </p>
                        <?php if (isset($recompensa["haki"])) : ?>
                            <p>
                                <i class="fa fa-certificate"></i>
                                <?= $recompensa["haki"] ?> pontos de Haki para toda a tripulação
                            </p>
                        <?php endif; ?>
                        <?php if (isset($recompensa["xp"])) : ?>
                            <p>
                                <?= $recompensa["xp"] ?> pontos de experiência para toda a tripulação
                            </p>
                        <?php endif; ?>
                        <?php if (isset($recompensa["dobroes"])) : ?>
                            <p>
                                <?= $recompensa["dobroes"] ?> <img src="Imagens/Icones/Dobrao.png">
                            </p>
                        <?php endif; ?>
                        <?php if (isset($recompensa["akuma"])) : ?>
                            <div class="equipamentos_classe_6 pull-left">
                                <img src="Imagens/Itens/100.png">
                            </div>
                            <p>
                                Akuma no Mi aleatória
                            </p>
                        <?php endif; ?>
                        <?php if (isset($recompensa["tipo_item"])) : ?>
                            <?php if ($recompensa["tipo_item"] == TIPO_ITEM_REAGENT) : ?>
                                <div class="equipamentos_classe_1 pull-left">
                                    <?= get_img_item($reagents[$recompensa["cod_item"]]) ?>
                                </div>
                                <p>
                                    <?= $reagents[$recompensa["cod_item"]]["nome"] ?>
                                    x
                                    <?= $recompensa["quant"] ?>
                                </p>
                            <?php elseif ($recompensa["tipo_item"] == TIPO_ITEM_EQUIPAMENTO) : ?>
                                <div
                                    class="equipamentos_classe_<?= $equipamentos[$recompensa["cod_item"]]["categoria"] ?> pull-left">
                                    <img src="Imagens/Itens/<?= $equipamentos[$recompensa["cod_item"]]["img"] ?>.png">
                                </div>
                                <p>
                                    <?= $equipamentos[$recompensa["cod_item"]]["nome"] ?>
                                </p>
                            <?php elseif ($recompensa["tipo_item"] == TIPO_ITEM_COMIDA) : ?>
                                <div class="equipamentos_classe_1 pull-left">
                                    <img src="Imagens/Itens/<?= $comidas[$recompensa["cod_item"]]["img"] ?>.png">
                                </div>
                                <p>
                                    <?= $comidas[$recompensa["cod_item"]]["nome"] ?>
                                    x
                                    <?= $recompensa["quant"] ?>
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (isset($recompensa["reputacao"])) : ?>
                            <p>
                                <?= $recompensa["reputacao"] ?> pontos de reputação.
                            </p>
                        <?php endif; ?>
                        <p style="position: absolute; bottom: 10px;">
                            <?php if ($userDetails->tripulacao["presente_diario_count"] == $day && ! $userDetails->tripulacao["presente_diario_obtido"]) : ?>
                                <button class="btn btn-success link_send" href="link_DailyGift/receber.php"
                                    data-dismiss="modal">
                                    <i class="fa fa-check"></i> Receber o presente
                                </button>
                            <?php elseif ($userDetails->tripulacao["presente_diario_count"] > $day) : ?>
                                <i class="fa fa-check text-success"></i> Presente já recebido!
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="tab-pane" id="calendar-tab-eventos">
            <h4>Eventos ativos neste momento:</h4>
            <div class="row">

                <?php $disputa_ativa = get_current_disputa_ilha(); ?>
                <?php if ($disputa_ativa) : ?>
                    <?php render_evento_ativo(
                        null,
                        "Disputa por " . $disputa_ativa["id"],
                        $disputa_ativa
                    ); ?>
                <?php endif; ?>

                <?php $evento_periodico_ativo = get_current_evento_periodico(); ?>
                <?php if ($evento_periodico_ativo["id"] == "eventoLadroesTesouro") : ?>
                    <?php render_evento_ativo(
                        "eventoLadroesTesouro",
                        "Em busca do tesouro roubado",
                        $evento_periodico_ativo
                    ); ?>
                <?php elseif ($evento_periodico_ativo["id"] == "eventoChefesIlhas") : ?>
                    <?php render_evento_ativo(
                        "eventoChefesIlhas",
                        "Equilibrando os poderes do mundo",
                        $evento_periodico_ativo
                    ); ?>
                <?php elseif ($evento_periodico_ativo["id"] == "boss") : ?>
                    <?php render_evento_ativo(
                        "boss",
                        "Caça ao Chefão",
                        $evento_periodico_ativo
                    ); ?>
                <?php elseif ($evento_periodico_ativo["id"] == "eventoPirata") : ?>
                    <?php render_evento_ativo(
                        "eventoPirata",
                        "Caça aos Piratas",
                        $evento_periodico_ativo
                    ); ?>
                <?php endif; ?>

                <?php render_evento_ativo(
                    "batalhaPoderes",
                    "Batalha pelos Poneglyphs",
                    $eventos_ativos["batalhaPoneglyphs"],
                    "ranking&rank=reputacao_mensal"
                ) ?>
                <?php render_evento_ativo(
                    "era",
                    "Grande Era dos Piratas",
                    $eventos_ativos["eraDosPiratas"],
                    "ranking&rank=reputacao"
                ) ?>
            </div>
        </div>
        <div class="tab-pane" id="calendar-tab-pvp">
            <div class="row">
                <?php foreach ($disputas_ilhas as $disputa) : ?>
                    <div class="list-group-item col-md-4">
                        <h4>
                            Batalha por
                            <?= $disputa["id"] ?>
                        </h4>
                        <h5>
                            Todo(a)
                            <?= utf8_encode(strftime('%A', strtotime("Sunday +" . $disputa["day_of_week"] . " days"))); ?>
                            às
                            <?= $disputa["start_hour"] ?>
                        </h5>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="tab-pane" id="calendar-tab-missoes-diarias">
            <?php if ($userDetails->tripulacao["missao_caca"]) : ?>
                <?php $missao = $missoes[$userDetails->tripulacao["missao_caca"]]; ?>
                <?php $rdm = $rdms[$missao["objetivo"]]; ?>
                <h3>
                    <?= $missao["nome"] ?>
                </h3>
                <h4>
                    Objetivo:
                    <?= $rdm["nome"] ?> x
                    <?= $missao["quant"] ?>
                </h4>
                <h4>
                    Recompensas:
                </h4>
                <ul class="text-left">
                    <li><img src="Imagens/Icones/Berries.png">
                        <?= mascara_berries($missao["berries"]) ?>
                    </li>
                    <?php if (isset($missao["recompensas"])) : ?>
                        <?php foreach ($missao["recompensas"] as $recompensa) : ?>
                            <li>
                                <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <?php if (isset($missao["boss"]) && $missao["boss"]) : ?>
                    <h5>Essa criatura pode ser encontrada nas seguintes coordenadas:</h5>
                    <?php $zonas = $connection->run("SELECT x, y FROM tb_mapa_rdm WHERE rdm_id = ?", "i", $missao["objetivo"]); ?>
                    <?php while ($quadro = $zonas->fetch_array()) : ?>
                        <p>
                            <?= get_human_location($quadro["x"], $quadro["y"]) ?>
                            -
                            <?= nome_mar(get_mar($quadro["x"], $quadro["y"])) ?>
                        </p>
                    <?php endwhile; ?>
                <?php endif; ?>
                <p>
                    Agora vá! Só volte aqui quando tiver derrotado todas as criaturas necessárias para pegar sua
                    recompensa.
                </p>
                <div class="progress">
                    <div class="progress-bar progress-bar-info"
                        style="width: <?= $userDetails->tripulacao["missao_caca_progress"] / $missao["quant"] * 100 ?>%">
                        <span>
                            <?= $userDetails->tripulacao["missao_caca_progress"] . "/" . $missao["quant"] ?>
                        </span>
                    </div>
                </div>

                <p>
                    <?php if ($userDetails->tripulacao["missao_caca_progress"] < $missao["quant"]) : ?>
                        <button href="MissaoCaca/missao_caca_cancelar.php" data-question="Deseja cancelar essa missão?"
                            data-dismiss="modal" class="link_confirm btn btn-danger">
                            Cancelar
                        </button>
                    <?php else : ?>
                        <button href="link_MissaoCaca/missao_caca_finalizar.php" data-dismiss="modal"
                            class="link_send btn btn-success">
                            Finalizar
                        </button>
                    <?php endif; ?>
                </p>
            <?php else : ?>
                <?php foreach ($missoes as $id => $missao) : ?>
                    <?php if (! isset($missao["diario"]) || ! $missao["diario"]) {
                        continue;
                    } ?>
                    <?php if (in_array($userDetails->ilha["mar"], $missao["mares"])) : ?>
                        <?php $rdm = $rdms[$missao["objetivo"]]; ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?= $missao["nome"]; ?>
                                <?= isset($missao["diario"]) && $missao["diario"] ? "(Diária)" : "" ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>
                                            Objetivo:
                                            <?= $rdm["nome"] ?> x
                                            <?= $missao["quant"] ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-6 ">
                                        <h4>
                                            Recompensas:
                                        </h4>
                                        <ul class="text-left">
                                            <li>
                                                <img src="Imagens/Icones/Berries.png">
                                                <?= mascara_berries($missao["berries"]) ?>
                                            </li>
                                            <?php if (isset($missao["recompensas"])) : ?>
                                                <?php foreach ($missao["recompensas"] as $recompensa) : ?>
                                                    <li>
                                                        <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <?php if (isset($missao["diario"]) && $missao["diario"]) : ?>
                                        <?php $completa = $connection->run("SELECT * FROM tb_missoes_caca_diario WHERE tripulacao_id = ? AND missao_caca_id = ? AND cast(inicio as date) = CURRENT_DATE()",
                                            "ii", array($userDetails->tripulacao["id"], $id))->count(); ?>
                                        <?php if (! $completa) : ?>
                                            <button href="MissaoCaca/missao_caca_iniciar.php?cod=<?= $id ?>"
                                                data-question="Deseja iniciar essa missão?" data-dismiss="modal"
                                                class="link_confirm btn btn-success">
                                                Iniciar
                                            </button>
                                        <?php else : ?>
                                            <p>Você já completou essa missão hoje, volte aqui amanhã.</p>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <button href="MissaoCaca/missao_caca_iniciar.php?cod=<?= $id ?>"
                                            data-question="Deseja iniciar essa missão?" data-dismiss="modal"
                                            class="link_confirm btn btn-success">
                                            Iniciar
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="tab-pane" id="calendar-tab-mini-eventos">
            <h4>Mini Eventos Ativos:</h4>
            <div class="row">
                <?php $events_details = DataLoader::load("mini_eventos"); ?>
                <?php $events = $connection->run(
                    "SELECT *, (unix_timestamp(fim) - unix_timestamp()) AS restante, (unix_timestamp() - unix_timestamp(inicio)) AS desde_inicio
						 FROM tb_mini_eventos m
						 LEFT JOIN tb_mini_eventos_concluidos mc ON mc.mini_evento_id = m.id AND mc.tripulacao_id = ?
						 ORDER BY m.fim, m.id",
                    "i", array($userDetails->tripulacao["id"])
                )->fetch_all_array(); ?>
                <?php foreach ($events as $event) : ?>
                    <?php $event_detail = $events_details[$event["id"]]; ?>
                    <?php $coordenadas = $connection->run("SELECT * FROM tb_mapa_rdm WHERE rdm_id IN (" . implode(",", $event_detail["zonas"]) . ")"); ?>
                    <div class="list-group-item col-md-4">
                        <h4>
                            <?= $event_detail["nome"] ?>
                            <?php if ($event["desde_inicio"] < 300) : ?>
                                <span class="label label-warning">Novo!</span>
                            <?php endif; ?>
                        </h4>
                        <h5>Essa criatura pode ser encontrada em:</h5>
                        <?php while ($quadro = $coordenadas->fetch_array()) : ?>
                            <p>
                                <?= get_human_location($quadro["x"], $quadro["y"]) ?>
                                -
                                <?= nome_mar(get_mar($quadro["x"], $quadro["y"])) ?>
                            </p>
                        <?php endwhile; ?>
                        <h5>Recompensas:</h5>
                        <?php foreach ($event_detail["recompensas"][$event["pack_recompensa"]] as $recompensa) : ?>
                            <div>
                                <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                            </div>
                        <?php endforeach; ?>
                        <h5>Tempo restante:
                            <?= transforma_tempo_min($event["restante"]) ?>
                        </h5>
                        <?php if ($event["momento"]) : ?>
                            <p class="text-success">
                                <i class="fa fa-check"></i>
                                Você já concluiu esse evento!
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
