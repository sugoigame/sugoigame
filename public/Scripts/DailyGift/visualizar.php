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
$comidas_db = $connection->run("SELECT * FROM tb_item_comida")->fetch_all_array();
$comidas = array();
foreach ($comidas_db as $comida) {
	$comidas[$comida["cod_comida"]] = $comida;
}

function dias_restantes_color($dias_restantes) {
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
<?php function render_evento_periodico($session, $name) { ?>
	<div class="list-group-item col-md-4">
		<h4>
			<?= $name ?>
		</h4>
		<a href="./?ses=<?= $session ?>" data-dismiss="modal" class="link_content btn btn-info">
			Recompensas
		</a>
	</div>
<?php } ?>
<div class="modal-body">
	<div>
		<ul class="nav nav-pills nav-justified">
			<li class="active">
				<a href="#calendar-tab-presentes" data-toggle="tab">
					Presentes Diários
					<?= !$userDetails->tripulacao["presente_diario_obtido"] ? '<span class="label label-danger">1</span>' : ""; ?>
				</a>
			</li>
			<li>
				<a href="#calendar-tab-eventos" data-toggle="tab">
					Eventos Ativos
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
	<br/>
	<div class="tab-content">
		<div class="tab-pane active" id="calendar-tab-presentes">
			<h4>Conecte-se ao Sugoi Game diariamente para ganhar um novo presente a cada dia</h4>
			<div class="row">
				<?php foreach ($recompensas as $day => $recompensa): ?>
					<div class="list-group-item col-md-3 <?= $userDetails->tripulacao["presente_diario_count"] > $day ? "text-muted" : "" ?>"
						 style="height: 200px">
						<h4><i class="fa fa-gift"></i> <?= $day + 1 ?>º dia</h4>
						<p>
							<img src="Imagens/Icones/Berries.png"> <?= mascara_berries($recompensa["berries"]) ?>
						</p>
						<?php if (isset($recompensa["haki"])): ?>
							<p>
								<i class="fa fa-certificate"></i>
								<?= $recompensa["haki"] ?> pontos de Haki para toda a tripulação
							</p>
						<?php endif; ?>
						<?php if (isset($recompensa["xp"])): ?>
							<p>
								<?= $recompensa["xp"] ?> pontos de experiência para toda a tripulação
							</p>
						<?php endif; ?>
						<?php if (isset($recompensa["dobroes"])): ?>
							<p>
								<?= $recompensa["dobroes"] ?> <img src="Imagens/Icones/Dobrao.png">
							</p>
						<?php endif; ?>
						<?php if (isset($recompensa["akuma"])): ?>
							<div class="equipamentos_casse_6 pull-left">
								<img src="Imagens/Itens/100.png">
							</div>
							<p>
								Akuma no Mi aleatória
							</p>
						<?php endif; ?>
						<?php if (isset($recompensa["tipo_item"])): ?>
							<?php if ($recompensa["tipo_item"] == TIPO_ITEM_REAGENT): ?>
								<div class="equipamentos_casse_1 pull-left">
									<?= get_img_item($reagents[$recompensa["cod_item"]]) ?>
								</div>
								<p>
									<?= $reagents[$recompensa["cod_item"]]["nome"] ?>
									x <?= $recompensa["quant"] ?>
								</p>
							<?php elseif ($recompensa["tipo_item"] == TIPO_ITEM_EQUIPAMENTO): ?>
								<div class="equipamentos_casse_<?= $equipamentos[$recompensa["cod_item"]]["categoria"] ?> pull-left">
									<img src="Imagens/Itens/<?= $equipamentos[$recompensa["cod_item"]]["img"] ?>.png">
								</div>
								<p>
									<?= $equipamentos[$recompensa["cod_item"]]["nome"] ?>
								</p>
							<?php elseif ($recompensa["tipo_item"] == TIPO_ITEM_COMIDA): ?>
								<div class="equipamentos_casse_1 pull-left">
									<img src="Imagens/Itens/<?= $comidas[$recompensa["cod_item"]]["img"] ?>.png">
								</div>
								<p>
									<?= $comidas[$recompensa["cod_item"]]["nome"] ?>
									x <?= $recompensa["quant"] ?>
								</p>
							<?php endif; ?>
						<?php endif; ?>
						<?php if (isset($recompensa["reputacao"])): ?>
							<p>
								<?= $recompensa["reputacao"] ?> pontos de reputação.
							</p>
						<?php endif; ?>
						<p style="position: absolute; bottom: 10px;">
							<?php if ($userDetails->tripulacao["presente_diario_count"] == $day && !$userDetails->tripulacao["presente_diario_obtido"]): ?>
								<button class="btn btn-success link_send" href="link_DailyGift/receber.php"
										data-dismiss="modal">
									<i class="fa fa-check"></i> Receber o presente
								</button>
							<?php elseif ($userDetails->tripulacao["presente_diario_count"] > $day): ?>
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
				<div class="list-group-item col-md-4">
					<?php $end = new DateTime("2021-09-17 00:00:00"); ?>
					<?php $now = new DateTime(date("Y-m-d H:i:s")); ?>
					<?php $tempo_restante = $now->diff($end); ?>
					<?php $dias_restantes = $tempo_restante->format('%a'); ?>
					<h4>
						<a href="./?ses=era" data-dismiss="modal" class="link_content">
							2º Grande Era dos Piratas 
						</a>
					</h4>
					<h5>Duração: 90 dias</h5>
					<div class="progress">
						<div class="progress-bar progress-bar-<?= dias_restantes_color($dias_restantes) ?>"
							 style="width: <?= (90 - $dias_restantes) / 90 * 100 ?>%">
							<a href="./?ses=ranking&rank=reputacao" data-dismiss="modal" class="link_content">
								Restante: <?= $tempo_restante->format('%a') ?>
								dias, <?= $tempo_restante->format('%h') ?> horas
								e <?= $tempo_restante->format('%i') ?> minutos
							</a>
						</div>
					</div>
					<a href="./?ses=era" data-dismiss="modal" class="link_content btn btn-info">
						Premiação
					</a>
					<a href="./?ses=ranking&rank=reputacao" data-dismiss="modal" class="link_content btn btn-success">
						Ranking
					</a>
				</div>
				<div class="list-group-item col-md-4">
					<?php $end = new DateTime("2021-07-17 00:00:00"); ?>
					<?php $tempo_restante = $now->diff($end); ?>
					<?php $dias_restantes = $tempo_restante->format('%a'); ?>
					<h4>
						<a href="./?ses=batalhaPoderes" data-dismiss="modal" class="link_content">
							Batalha dos Grandes Poderes
						</a>
					</h4>
					<h5>Duração: 30 dias</h5>
					<div class="progress">
						<div class="progress-bar progress-bar-<?= dias_restantes_color($dias_restantes) ?>"
							 style="width: <?= (30 - $dias_restantes) / 30 * 100 ?>%">
							<a href="./?ses=ranking&rank=reputacao_mensal" data-dismiss="modal" class="link_content">
								Restante: <?= $tempo_restante->format('%a') ?>
								dias, <?= $tempo_restante->format('%h') ?> horas
								e <?= $tempo_restante->format('%i') ?> minutos
							</a>
						</div>
					</div>
					<a href="./?ses=batalhaPoderes" data-dismiss="modal" class="link_content btn btn-info">
						Premiação
					</a>
					<a href="./?ses=ranking&rank=reputacao_mensal" data-dismiss="modal"
					   class="link_content btn btn-success">
						Ranking
					</a>
				</div>
				<?php $disputas = $connection->run("SELECT * FROM tb_ilha_disputa LEFT JOIN tb_usuarios ON tb_ilha_disputa.vencedor_id = tb_usuarios.id"); ?>
				<?php while ($disputa = $disputas->fetch_array()): ?>
					<div class="list-group-item col-md-4">
						<h4>
							Disputa por <?= nome_ilha($disputa["ilha"]) ?>
						</h4>
						<?php if ($disputa["vencedor_id"]): ?>
							<h4>
								<?= $disputa["tripulacao"] ?> concluiu a incursão pela ilha
							</h4>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>

				<?php $evento_periodico_ativo = get_value_varchar_variavel_global(VARIAVEL_EVENTO_PERIODICO_ATIVO); ?>
				<?php if ($evento_periodico_ativo == "eventoLadroesTesouro"): ?>
					<?php render_evento_periodico("eventoLadroesTesouro", "Em busca do tesouro roubado"); ?>
				<?php elseif ($evento_periodico_ativo == "eventoChefesIlhas"): ?>
					<?php render_evento_periodico("eventoChefesIlhas", "Equilibrando os poderes do mundo"); ?>
				<?php elseif ($evento_periodico_ativo == "boss"): ?>
					<?php render_evento_periodico("boss", "Caça ao Chefão"); ?>
				<?php elseif ($evento_periodico_ativo == "eventoPirata"): ?>
					<?php render_evento_periodico("eventoPirata", "Caça aos Piratas"); ?>
				<?php endif; ?>
				<!--<div class="list-group-item col-md-4">
					<h4>
						Evento de Ano Novo
					</h4>
					<a href="./?ses=eventoAnoNovo" data-dismiss="modal" class="link_content btn btn-info">
						Recompensas
					</a>
				</div>
				<div class="list-group-item col-md-4">
					<h4>
						Bonus de Experiência
					</h4>
				</div>-->
			</div>
			<h4>Calendário do jogo</h4>
			<div>
				<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=sms7pac1e14pshj0om7knh78ng%40group.calendar.google.com&amp;color=%232F6309&amp;src=c20petsj832cqfr06ldnrsbsj0%40group.calendar.google.com&amp;color=%23BE6D00&amp;src=52efetkf9qqlb7du0g04h8vftg%40group.calendar.google.com&amp;color=%23B1440E&amp;src=muumg01esl24lipcttqs76drs0%40group.calendar.google.com&amp;color=%23B1365F&amp;src=hi7n536og0a6ukl2uvs1ti805o%40group.calendar.google.com&amp;color=%2328754E&amp;src=nb59ici543mjciji179i8rd2j8%40group.calendar.google.com&amp;color=%235229A3&amp;src=8uconpno7gg52k4dvkj0j30stc%40group.calendar.google.com&amp;color=%23AB8B00&amp;ctz=America%2FSao_Paulo"
						style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
			</div>
		</div>
		<div class="tab-pane" id="calendar-tab-missoes-diarias">
			<?php if ($userDetails->tripulacao["missao_caca"]): ?>
				<?php $missao = $missoes[$userDetails->tripulacao["missao_caca"]]; ?>
				<?php $rdm = $rdms[$missao["objetivo"]]; ?>
				<h3><?= $missao["nome"] ?></h3>
				<h4>
					Objetivo: <?= $rdm["nome"] ?> x<?= $missao["quant"] ?>
				</h4>
				<h4>
					Recompensas:
				</h4>
				<ul class="text-left">
					<li><img src="Imagens/Icones/Berries.png"> <?= mascara_berries($missao["berries"]) ?></li>
					<?php if (isset($missao["recompensas"])): ?>
						<?php foreach ($missao["recompensas"] as $recompensa): ?>
							<li><?php render_recompensa($recompensa, $reagents, $equipamentos); ?></li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				<?php if (isset($missao["boss"]) && $missao["boss"]): ?>
					<h5>Essa criatura pode ser encontrada nas seguintes coordenadas:</h5>
					<?php $zonas = $connection->run("SELECT x, y FROM tb_mapa_rdm WHERE rdm_id = ?", "i", $missao["objetivo"]); ?>
					<?php while ($quadro = $zonas->fetch_array()) : ?>
						<p>
							<?= get_human_location($quadro["x"], $quadro["y"]) ?>
							- <?= nome_mar(get_mar($quadro["x"], $quadro["y"])) ?>
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
						<span><?= $userDetails->tripulacao["missao_caca_progress"] . "/" . $missao["quant"] ?></span>
					</div>
				</div>

				<p>
					<?php if ($userDetails->tripulacao["missao_caca_progress"] < $missao["quant"]) : ?>
						<button href="MissaoCaca/missao_caca_cancelar.php"
								data-question="Deseja cancelar essa missão?"
								data-dismiss="modal"
								class="link_confirm btn btn-danger">
							Cancelar
						</button>
					<?php else: ?>
						<button href="link_MissaoCaca/missao_caca_finalizar.php"
								data-dismiss="modal"
								class="link_send btn btn-success">
							Finalizar
						</button>
					<?php endif; ?>
				</p>
			<?php else: ?>
				<?php foreach ($missoes as $id => $missao): ?>
					<?php if (!isset($missao["diario"]) || !$missao["diario"]) {
						continue;
					} ?>
					<?php if (in_array($userDetails->ilha["mar"], $missao["mares"])): ?>
						<?php $rdm = $rdms[$missao["objetivo"]]; ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<?= $missao["nome"]; ?> <?= isset($missao["diario"]) && $missao["diario"] ? "(Diária)" : "" ?>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6">
										<h4>
											Objetivo: <?= $rdm["nome"] ?> x<?= $missao["quant"] ?>
										</h4>
									</div>
									<div class="col-md-6 ">
										<h4>
											Recompensas:
										</h4>
										<ul class="text-left">
											<li>
												<img src="Imagens/Icones/Berries.png"> <?= mascara_berries($missao["berries"]) ?>
											</li>
											<?php if (isset($missao["recompensas"])): ?>
												<?php foreach ($missao["recompensas"] as $recompensa): ?>
													<li><?php render_recompensa($recompensa, $reagents, $equipamentos); ?></li>
												<?php endforeach; ?>
											<?php endif; ?>
										</ul>
									</div>
								</div>
								<div>
									<?php if (isset($missao["diario"]) && $missao["diario"]): ?>
										<?php $completa = $connection->run("SELECT * FROM tb_missoes_caca_diario WHERE tripulacao_id = ? AND missao_caca_id = ?",
											"ii", array($userDetails->tripulacao["id"], $id))->count(); ?>
										<?php if (!$completa): ?>
											<button href="MissaoCaca/missao_caca_iniciar.php?cod=<?= $id ?>"
													data-question="Deseja iniciar essa missão?"
													data-dismiss="modal"
													class="link_confirm btn btn-success">
												Iniciar
											</button>
										<?php else: ?>
											<p>Você já completou essa missão hoje, volte aqui amanhã.</p>
										<?php endif; ?>
									<?php else: ?>
										<button href="MissaoCaca/missao_caca_iniciar.php?cod=<?= $id ?>"
												data-question="Deseja iniciar essa missão?"
												data-dismiss="modal"
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
							<?php if ($event["desde_inicio"] < 300): ?>
								<span class="label label-warning">Novo!</span>
							<?php endif; ?>
						</h4>
						<h5>Essa criatura pode ser encontrada em:</h5>
						<?php while ($quadro = $coordenadas->fetch_array()) : ?>
							<p>
								<?= get_human_location($quadro["x"], $quadro["y"]) ?>
								- <?= nome_mar(get_mar($quadro["x"], $quadro["y"])) ?>
							</p>
						<?php endwhile; ?>
						<h5>Recompensas:</h5>
						<?php foreach ($event_detail["recompensas"][$event["pack_recompensa"]] as $recompensa): ?>
							<div><?php render_recompensa($recompensa, $reagents, $equipamentos); ?></div>
						<?php endforeach; ?>
						<h5>Tempo restante: <?= transforma_tempo_min($event["restante"]) ?></h5>
						<?php if ($event["momento"]): ?>
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
