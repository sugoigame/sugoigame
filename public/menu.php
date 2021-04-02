<?php
function menu_link($ses, $text, $img, $title, $href_prefix = "./?ses=", $class = "link_content", $id = "", $data = "") {
	$sess = $_GET["sessao"];
	global $userDetails;

	if ($class != 'link_content')
		$href_prefix = '';

	return "<li class=\"" . ($sess == $ses ? "active" : "") . "\">
			<a id=\"$id\" href=\"$href_prefix$ses\" class=\"$class \" title=\"$title\" $data>
				 <i class=\"$img fa-fw hidden-sm\"></i>$text" . ($userDetails->has_alert($ses) ? get_alert("pull-right") : "") . "
			</a>
		</li>";
}

function super_menu_link($href, $href_toggle, $text, $super_menu, $icon) {
	global $userDetails;
	return "<div class=\"nav navbar-nav text-left\">
				<a href=\"#$href_toggle\" class=\"" . super_menu_active($super_menu) . "\" data-toggle=\"collapse\" data-parent=\"#vertical-menu\">
					<img height='35px' src=\"Imagens/Icones/Sessoes/$icon.png\" class=\"hidden-sm\" />
					<span class='super-menu-text'>$text</span>
					" . ($userDetails->has_super_alert($super_menu) ? get_alert("pull-right") : "") . "
				</a>
			</div>";
}

function super_menu_in_out($menu) {
	return super_menu_can_be_active($menu) ? "in" : "out";
}

function super_menu_active($menu) {
	return super_menu_can_be_active($menu) ? "active" : "";
}

function super_menu_can_be_active($menu) {
	return get_super_menu() == $menu;
}
?>
<?php if ($userDetails->tripulacao) : ?>
	<?php $news = $connection->run("SELECT *, unix_timestamp(data) AS timestamp FROM tb_news_coo ORDER BY data DESC LIMIT 6")->fetch_all_array(); ?>
	<?php if ($news): ?>
		<div class="vertical-menu-news clearfix" style="margin-bottom: 10px;">
			<?php foreach ($news as $new): ?>
				<div class="row <?= $new["timestamp"] > ($userDetails->tripulacao["ultimo_logon"] - 300) ? "new" : "" ?>">
					<div class="col-xs-4 col-sm-12 col-md-4">
						<img class="hidden-sm" src="Imagens/news.png" />
					</div>
					<div class="col-xs-8 col-sm-12 col-md-8 news-coo-text">
						<small><?=date("d/m/Y - h:i", $new["timestamp"]);?></small>
						<br />
						<p class="text-justify"><?=$new["msg"];?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php $anuncios = array(
		// 1 => "./?ses=noticia&cod=22",
		2 => "./?ses=recrutamento",
		3 => "./?ses=vipComprar"
	);

	if (is_coliseu_aberto()) {
		$anuncios[4] = "./?ses=coliseu";
	}

	$anuncio = array_rand($anuncios); ?>

	<div style="border-left: 1px solid #000; border-right: 1px solid #000; margin-bottom: 10px;">
		<a class="link_content" href="<?= $anuncios[$anuncio]; ?>">
			<img src="Imagens/Banners/destaque-<?= $anuncio ?>.jpg" width="100%"/>
		</a>
	</div>
<?php endif; ?>
<div id="vertical-menu" class="sidebar-nav">
	<div class="navbar navbar-default" role="navigation">
		<div class="navbar-header menu-toggler">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".sidebar-navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<span class="visible-xs navbar-brand">Menu</span>
		</div>
		<div class="navbar-collapse collapse sidebar-navbar-collapse menu-navbar-collapse">
			<?= super_menu_link("home", "menu-principal", "Principal", "principal", "principal") ?>
			<?php if ($userDetails->tripulacao && ($userDetails->in_ilha || $userDetails->tripulacao_alive)) : ?>
				<div id="menu-principal" class="collapse <?= super_menu_in_out("principal") ?>">
					<ul class="vertical-nav nav navbar-nav ">
						<?= menu_link("home", "Home", "fa fa-home", "Mantenha-se informado! Nunca se sabe a hora em que algo importante poderá acontecer.") ?>
						<?= menu_link("noticias", "Notícias", "fa fa-newspaper-o", "") ?>
						<?= menu_link("recrutamento", "Recrute um Amigo", "fa fa-user-plus", "") ?>
						<?= menu_link("akumaBook", "Akuma Book", "fa fa-book", "Veja quais foram as Akuma no Mi já encontradas") ?>
						<?= menu_link("hall", "Hall da fama", "fa fa-trophy", "Veja quais foram os melhores jogadores de eras passadas") ?>
						<?= menu_link("ranking", "Ranking", "fa fa-trophy", "") ?>
						<?= menu_link("calendario", "Calendário do jogo", "fa fa-calendar", "") ?>
						<?= menu_link("conta", "Minha Conta", "fa fa-address-card", "") ?>
						<?= menu_link("forum", "Suporte & Fórum", "fa fa-bars", "") ?>
						<?= menu_link("calculadoras", "Calculadoras", "fa fa-calculator", "") ?>
						<?= menu_link("#", "Destravar Tripulação", "fa fa-cogs", "Corrigir bugs que podem ter travado sua conta.", "", "", "unstuck-acc") ?>
						<?= menu_link("vipLoja", "Gold Shop", "fa fa-shopping-cart", "") ?>
						<?= menu_link("vipComprar", "Faça uma doação", "fa fa-diamond", "") ?>
						<?= menu_link("#", "Selecionar tripulação", "fa fa-sign-out", "É hora de dar tchau!", "", "link_redirect", "link_Scripts/Geral/deslogartrip") ?>
						<?= menu_link("#", "Logout", "fa fa-sign-out", "É hora de dar tchau!", "", "link_redirect", "link_Scripts/Geral/deslogar") ?>
					</ul>
				</div>
				<?php if (!$userDetails->combate_pvp && !$userDetails->combate_pve && !$userDetails->combate_bot) : ?>
					<?php if ($userDetails->tripulacao["campanha_impel_down"] || $userDetails->tripulacao["campanha_enies_lobby"]) : ?>
						<?= super_menu_link("campanhaImpelDown", "menu-campanha", "Campanhas", "campanha", "campanha") ?>
						<div id="menu-campanha" class="collapse <?= super_menu_in_out("campanha") ?>">
							<ul class="vertical-nav nav navbar-nav">
								<?php if ($userDetails->tripulacao["campanha_impel_down"]): ?>
									<?= menu_link("campanhaImpelDown", "Impel Down", "fa fa-book", "") ?>
								<?php endif; ?>
								<?php if ($userDetails->tripulacao["campanha_enies_lobby"]): ?>
									<?= menu_link("campanhaEniesLobby", "Enies Lobby", "fa fa-book", "") ?>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?= super_menu_link("status", "menu-tripulacao", "Tripulação", "tripulacao", "tripulacao") ?>

					<div id="menu-tripulacao" class="collapse <?= super_menu_in_out("tripulacao") ?>">
						<ul class="vertical-nav nav navbar-nav">
							<?= menu_link("status", "Visão Geral", "fa fa-file-text", "") ?>
							<?= menu_link("haki", "Haki", "fa fa-certificate", "") ?>
							<?= menu_link("karma", "Karma", "glyphicon glyphicon-adjust", "") ?>
							<?= menu_link("realizacoes", "Conquistas", "glyphicon glyphicon-star-empty", "") ?>
							<?= menu_link("listaNegra", "Lista Negra", "fa fa-th-list", "") ?>
							<?= menu_link("tatics", "Táticas", "glyphicon glyphicon-knight", "") ?>
							<?= menu_link("combateLog", "Meus Combate", "fa fa-file-text", "") ?>
							<?= menu_link("wantedLog", "Minhas Recompensas", "fa fa-file-text", "") ?>
						</ul>
					</div>
					<?php if ($userDetails->navio) : ?>
						<?= super_menu_link("statusNavio", "menu-navio", "Navio", "navio", "navio") ?>
						<div id="menu-navio" class="collapse <?= super_menu_in_out("navio") ?>">
							<ul class="vertical-nav nav navbar-nav">
								<?= menu_link("statusNavio", "Visão Geral", "fa fa-ship", "") ?>
								<?= menu_link("navioSkin", "Aparência", "fa fa-ship", "") ?>
								<?= menu_link("obstaculos", "Obstáculos do Navio", "glyphicon glyphicon-knight", "") ?>
								<?php if (!$userDetails->tripulacao["recrutando"] && !$userDetails->missao) : ?>
									<?= menu_link("quartos", "Quartos", "fa fa-bed", "") ?>
									<?= menu_link("forja", "Forja", "fa fa-fire", "") ?>
									<?= menu_link("oficina", "Oficina", "fa fa-gavel", "") ?>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>
					<?php if ($userDetails->in_ilha) : ?>
						<?= super_menu_link($userDetails->tripulacao["recrutando"]
							? "recrutar"
							: ($userDetails->missao_r
								? "missoesR"
								: "missoes"), "menu-ilha", "Ilha Atual", "ilha", "ilha") ?>

						<div id="menu-ilha" class="collapse <?= super_menu_in_out("ilha") ?>">
							<ul class="vertical-nav nav navbar-nav">
								<?= menu_link(
									"missoes",
									($userDetails->tripulacao["faccao"] == FACCAO_MARINHA) ? "Base da Marinha" : "Subúrbio",
									"fa " . ($userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? 'fa-ship' : 'fa-road'),
									($userDetails->tripulacao["faccao"] == FACCAO_MARINHA) ? "Apresente-se soldado! Temos tarefas para você!" : "Aventure-se! Essa ilha tem muito a ser explorado!"
								) ?>
								<?= menu_link("incursao", "Incursão", "fa fa-fort-awesome", "") ?>
								<?= menu_link("recrutar", "Recrutar", "fa fa-street-view", "") ?>
								<?php if (!$userDetails->tripulacao["recrutando"]) : ?>
									<?php if (count($userDetails->personagens) > 1) : ?>
										<?= menu_link("expulsar", "Expulsar Trip.", "fa fa-user-times", "") ?>
									<?php endif; ?>

									<?= menu_link("tripulantesInativos", "Tripulantes fora do barco", "fa fa-users", "") ?>
									<?= menu_link("politicaIlha", "Domínio da Ilha", "fa fa-globe", "") ?>
									<?= menu_link("mercado", "Mercado", "fa fa-shopping-cart", "") ?>
									<?= menu_link("restaurante", "Restaurante", "fa fa-cutlery", "") ?>
									<?= menu_link("leiloes", "Centro de comércio", "fa fa-exchange", "") ?>
									<?= menu_link("upgrader", "Aprimoramentos", "fa fa-arrow-up", "") ?>
									<?= menu_link("estaleiro", "Estaleiro", "fa fa-ship", "") ?>
									<?= menu_link("hospital", "Hospital", "fa fa-h-square", "") ?>
									<?= menu_link("academia", "Academia", "fa fa-star-o", "") ?>
									<?= menu_link("profissoesAprender", "Escola de Profissões", "fa fa-university", "") ?>
									<?= menu_link("missoesCaca", "Missões de caça", "glyphicon glyphicon-screenshot", "") ?>
									<?= menu_link(
										"missoesR",
										($userDetails->tripulacao["faccao"] == FACCAO_MARINHA) ? "Serviços comunitários" : "Procurar tesouros",
										"fa " . ($userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? 'fa-users' : 'fa-times'),
										($userDetails->tripulacao["faccao"] == FACCAO_MARINHA) ? "Ganhe dinheiro ajudando os moradores da ilha." : "Vamos atrás do dinheiro por daqui!"
									) ?>
								<?php endif; ?>
								<?php if ($userDetails->ilha["ilha"] == 47) : ?>
									<?= menu_link("arvoreAnM", "Jardim de Laftel", "fa fa-circle", "") ?>
								<?php endif; ?>
							</ul>

							<?php if (
								($userDetails->tripulacao["x"] != $userDetails->tripulacao["res_x"]
									|| $userDetails->tripulacao["y"] != $userDetails->tripulacao["res_y"])
								&& $userDetails->in_ilha
							) : ?>
								<ul class="vertical-nav nav navbar-nav">
									<?= menu_link("Geral/ilha_salvar_respown.php", "Salvar retorno", "fa fa-gear", "Venha para esta ilha quando sua tripulação for derrotada.", "", "link_confirm", "", "data-question=\"Tem certeza que deseja salvar seu retorno nessa ilha?\"") ?>
								</ul>
							<?php endif; ?>

							<?php if ((($userDetails->ilha["ilha"] == 42 AND $userDetails->tripulacao["faccao"] == FACCAO_PIRATA)
									OR ($userDetails->ilha["ilha"] == 43 AND $userDetails->tripulacao["faccao"] == FACCAO_MARINHA))
								AND $userDetails->capitao["lvl"] >= 45
							) : ?>
								<ul class="vertical-nav nav navbar-nav">
									<?= menu_link("Geral/novo_mundo.php", "Ir para o Novo Mundo", "fa fa-globe", "Hora de navegar! Rumo ao desconhecido!", "", "link_sends") ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ($userDetails->navio) : ?>
						<?= super_menu_link("oceano", "menu-oceano", "Oceano", "oceano", "oceano") ?>
						<div id="menu-oceano" class="collapse <?= super_menu_in_out("oceano") ?>">
							<ul class="vertical-nav nav navbar-nav">
								<?php if (!$userDetails->missao && !$userDetails->tripulacao["recrutando"] && $userDetails->navio): ?>
									<?= menu_link("oceano", "Ir para o oceano", "glyphicon glyphicon-tint", "") ?>
									<?= menu_link("amigaveis", "Batalhas Amigáveis", "glyphicon glyphicon-screenshot", "") ?>
								<?php endif; ?>
								<?php if (!$userDetails->in_ilha) : ?>
									<?= menu_link("servicoDenDen", "Vendas por correio", "fa fa-shopping-basket", "") ?>
								<?php endif; ?>
								<?php if (!$userDetails->rotas
									&& !$userDetails->missao && !$userDetails->tripulacao["recrutando"]
									&& $userDetails->navio
								) : ?>
									<?= menu_link("transporte", "Serviço de transporte", "fa fa-paper-plane", "") ?>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<?= super_menu_link("combate", "menu-combate", "Combate", "combate", "combate") ?>
					<div id="menu-combate" class="collapse <?= super_menu_in_out("combate") ?>">
						<ul class="vertical-nav nav navbar-nav">
							<?= menu_link("combate", "Combate", "fa fa-bolt", "") ?>
						</ul>
					</div>
				<?php endif; ?>
				<?= super_menu_link(
				"aliancaLista",
				"menu-alianca",
				$userDetails->tripulacao["faccao"] == FACCAO_PIRATA
					? "Aliança"
					: "Frota",
				"alianca",
				$userDetails->tripulacao["faccao"] == FACCAO_PIRATA
					? "alianca"
					: "frota") ?>

				<div id="menu-alianca" class="collapse <?= super_menu_in_out("alianca") ?>">
					<ul class="vertical-nav nav navbar-nav">
						<?php if (!$userDetails->ally) : ?>
							<?= menu_link("aliancaCriar", "Juntar-se", "fa fa-users", "") ?>
						<?php else : ?>
							<?= menu_link("alianca", "Visão geral", "fa fa-file-text", "") ?>
							<?= menu_link("aliancaDiplomacia", "Diplomacia", "fa fa-handshake-o", "") ?>
							<?= menu_link("aliancaCooperacao", "Cooperação", "fa fa-users", "") ?>
							<?= menu_link("aliancaMissoes", "Missões", "fa fa-list", "") ?>

							<?php if ($userDetails->in_ilha) : ?>
								<?= menu_link("aliancaBanco", "Banco da " . ($userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "Frota" : "Aliança"), "fa fa-archive", "") ?>
							<?php endif; ?>
						<?php endif; ?>
						<?= menu_link("aliancaLista", "Frotas e Alianças", "fa fa-th-list", "") ?>
					</ul>
				</div>

				<?= super_menu_link("lojaEvento", "menu-events", "Eventos", "eventos", "eventos") ?>
				<div id="menu-events" class="collapse <?= super_menu_in_out("eventos") ?>">
					<ul class="vertical-nav nav navbar-nav">
						<?= menu_link("lojaEvento", "Loja de Eventos", "fa fa-certificate", ""); ?>
						<?/*= menu_link("eventoAnoNovo", "Evento de Ano Novo", "fa fa-bolt", ""); */?>
						<?/*= menu_link("eventoNatal", "Evento de Natal", "fa fa-bolt", "");*/?>
						<?/*= menu_link("eventoHalloween", "Semana do Terror", "fa fa-bolt", ""); */?>
						<?/*= menu_link("eventoCriancas", "Semana das Crianças", "fa fa-bolt", ""); */?>
						<?/*= menu_link("eventoIndependencia", "Evento da Independência", "fa fa-bolt", ""); */?>
						<?/*= menu_link("eventoDiaPais", "Dia dos Pais", "fa fa-bolt", ""); */?>

						<?php $evento_periodico_ativo = get_value_varchar_variavel_global(VARIAVEL_EVENTO_PERIODICO_ATIVO); ?>
						<?php if ($evento_periodico_ativo == "eventoLadroesTesouro"): ?>
							<?= menu_link("eventoLadroesTesouro", "Caça aos ladrões de tesouro", "fa fa-bolt", ""); ?>
						<?php elseif ($evento_periodico_ativo == "eventoChefesIlhas"): ?>
							<?= menu_link("eventoChefesIlhas", "Equilibrando os poderes do mundo", "fa fa-bolt", ""); ?>
						<?php elseif ($evento_periodico_ativo == "boss"): ?>
							<?= menu_link("boss", "Caça ao Chefão", "fa fa-bolt", ""); ?>
						<?php elseif ($evento_periodico_ativo == "eventoPirata"): ?>
							<?= menu_link("eventoPirata", "Caça aos Piratas", "fa fa-bolt", ""); ?>
						<?php endif; ?>
					</ul>
				</div>
			<?php elseif ($userDetails->tripulacao && !$userDetails->in_ilha) : ?>
				<?= super_menu_link("oceano", "menu-oceano", "Oceano", "oceano", "oceano") ?>
				<div id="menu-oceano" class="collapse <?= super_menu_in_out("oceano") ?>">
					<ul class="vertical-nav nav navbar-nav">
						<?= menu_link("respawn", "Tripulação Derrotada", "fa fa-times", "") ?>
					</ul>
				</div>
			<?php elseif ($userDetails->conta) : ?>
				<div id="menu-principal" class="collapse <?= super_menu_in_out("principal") ?>">
					<ul class="vertical-nav nav navbar-nav">
						<?= menu_link("home", "Home", "fa fa-home", "Mantenha-se informado! Nunca se sabe a hora em que algo importante poderá acontecer.") ?>
						<?= menu_link("noticias", "Notícias", "fa fa-newspaper-o", "") ?>
						<?= menu_link("seltrip", "Minhas Tripulações", "fa fa-users", "") ?>
						<?= menu_link("#", "Logout", "fa fa-sign-out", "É hora de dar tchau!", "", "link_redirect", "link_Scripts/Geral/deslogar") ?>
					</ul>
				</div>
			<?php else: ?>
				<div id="menu-principal" class="collapse <?= super_menu_in_out("principal") ?>">
					<ul class="vertical-nav nav navbar-nav">
						<?= menu_link("home", "Home", "fa fa-home", "Mantenha-se informado! Nunca se sabe a hora em que algo importante poderá acontecer.") ?>
						<?= menu_link("noticias", "Notícias", "fa fa-newspaper-o", "") ?>
						<?= menu_link("cadastro", "Cadastrar", "fa fa-user-plus", "") ?>
						<?= menu_link("recuperarSenha", "Recuperar Senha", "fa fa-envelope-o", "") ?>
						<?= menu_link("regras", "Regras e Punições", "fa fa-ban", "") ?>
						<?= menu_link("politica", "Política de Privacidade", "fa fa-th-list", "") ?>
					</ul>
				</div>
			<?php endif; ?>

			<?= super_menu_link("forum", "menu-forum", "Suporte & Fórum", "forum", "tutoriais") ?>
			<div id="menu-forum" class="collapse <?= super_menu_in_out("forum") ?>">
				<ul class="vertical-nav nav navbar-nav">
					<?php if ($userDetails->tripulacao): ?>
						<?= menu_link("forum", "Suporte & Fórum", "fa fa-bars", "") ?>
						<?php /*$categorias = $connection->run(
							"SELECT *, 
							(SELECT count(*) FROM tb_forum_topico p WHERE p.categoria_id = c.id) AS topics,
							(SELECT count(*) FROM tb_forum_topico p INNER JOIN tb_forum_topico_lido l ON p.id = l.topico_id AND l.tripulacao_id = ? WHERE p.categoria_id = c.id) AS topics_lidos 
							FROM tb_forum_categoria c ",
							"i", array($userDetails->tripulacao["id"])); ?>
						<?php while ($categoria = $categorias->fetch_array()): ?>
							<?php $nao_lidos = $categoria["topics"] - $categoria["topics_lidos"]; ?>
							<?php $badge = $nao_lidos ? " (" . ($categoria["topics"] - $categoria["topics_lidos"]) . ")" : ""; ?>
							<?= menu_link("forumTopics&categoria=" . $categoria["id"], $categoria["nome"] . $badge, $categoria["icon"], "") ?>
						<?php endwhile;*/ ?>
					<?php endif; ?>
					<?= menu_link("faq", "Base de Conhecimento", "fa fa-question-circle", "") ?>
					<?= menu_link("https://fb.com/sugoigamebr", "Sugoi no Facebook", "fa fa-facebook-square", "", "", "", "", 'target="_blank"') ?>
					<?= menu_link("https://instagram.com/sugoigame", "Sugoi no Instagram", "fa fa-instagram", "", "", "", "", 'target="_blank"') ?>
				</ul>
			</div>

			<?php if ($userDetails->tripulacao['adm'] > 0): ?>
			<?= super_menu_link("admin", "menu-admin", "Administração", "admin", "admin") ?>
			<div id="menu-admin" class="collapse <?= super_menu_in_out("admin") ?>">
				<ul class="vertical-nav nav navbar-nav">
					<?= menu_link("admin-news", "Gerenciar Noticias", "fa fa-newspaper-o", "") ?>
					<?= menu_link("admin-mails", "Gerenciar Den Den", "fa fa-envelope-o", "") ?>
					<?= menu_link("admin-estatisticas", "Estatísticas", "fa fa-envelope-o", "") ?>
					<?= menu_link("admin-combinacaoferreiro", "Combinações do Ferreiro", "fa fa-envelope-o", "") ?>
					<?= menu_link("admin-combinacaoartesao", "Combinações do artesao", "fa fa-envelope-o", "") ?>
					<?= menu_link("admin-combinacaocarpinteiro", "Combinações do carpinteiro", "fa fa-envelope-o", "") ?>
					<?= menu_link("admin-combinacaoequips", "Equipamentos do jogo", "fa fa-envelope-o", "") ?>
					<?= menu_link("admin-batalhas", "Log de Batalhas PvP", "fa fa-envelope-o", "") ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php if ($userDetails->conta): ?>
	<?php if ($userDetails->tripulacao['adm'] > 0): ?>
		<div class="vertical-menu-header clearfix">
			<p class="navbar-text">
				<?php $total = $connection->run("SELECT count(id) AS total FROM tb_usuarios WHERE ultimo_logon > ?", "i", atual_segundo() - (15 * 60))->fetch_array()["total"]; ?>
				Jogadores online: <?=($total);?>
			</p>
		</div>
	<?php endif; ?>
	<div style="margin: 10px 0; text-align: left">
		<button class="btn btn-primary btn-blocks" id="audio-toggle">
			<i class="glyphicon glyphicon-volume-up"></i> Som Ligado
		</button>
	</div>
<?php endif; ?>