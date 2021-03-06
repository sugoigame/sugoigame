<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
	$protector->exit_error("Personagem inválido");
}
?>


<?php $titulos_compartilhados = $connection->run(
	"SELECT tit.cod_titulo, tit.nome, tit.nome_f, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
			INNER JOIN tb_personagens per ON pertit.cod = per.cod
			INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
			WHERE tit.compartilhavel = 1 AND per.id = ?",
	"i", array($userDetails->tripulacao["id"])
)->fetch_all_array(); ?>

<?php $bordas = DataLoader::load("bordas"); ?>
<?php $minhas_bordas_db = $connection->run("SELECT * FROM tb_tripulacao_bordas WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->fetch_all_array(); ?>
<?php $minhas_bordas = array(); ?>

<?php foreach ($minhas_bordas_db as $borda) {
	$minhas_bordas[$borda["borda"]] = TRUE;
} ?>

<?php $skins = DataLoader::load("skins"); ?>
<?php $fan_arts = DataLoader::load("fan_arts"); ?>
<?php $minhas_skins_db = $connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->fetch_all_array(); ?>
<?php $minhas_skins = array(); ?>

<?php foreach ($minhas_skins_db as $skin) {
	$minhas_skins[$skin["img"]][] = $skin["skin"];
} ?>

<?php $skins_pers = isset($skins[$pers["img"]]) ? count($skins[$pers["img"]]) : 0; ?>

<?php
$titulos_bd = $connection->run(
	"SELECT tit.cod_titulo, tit.nome, tit.nome_f, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
							INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
							WHERE pertit.cod = ?", "i", $pers["cod"]
)->fetch_all_array();
$titulos = array();
foreach ($titulos_bd as $titulo) {
	if ($pers["sexo"] == 1) {
		$titulo["nome"] = $titulo["nome_f"];
	}
	$titulos[$titulo["cod_titulo"]] = $titulo;
}
foreach ($titulos_compartilhados as $titulo) {
	if ($pers["sexo"] == 1) {
		$titulo["nome"] = $titulo["nome_f"];
	}
	$titulos[$titulo["cod_titulo"]] = $titulo;
}

$pers_titulo = FALSE;
if ($pers["titulo"]) {
	foreach ($titulos as $titulo) {
		if ($pers["titulo"] == $titulo["cod_titulo"]) {
			$pers_titulo = $titulo["nome"];
		}
	}
}
?>
<style type="text/css">
	.skin-ativa {
		border: 5px solid red;
		cursor: pointer;
	}

	.skin-nao-ativa {
		border: 5px solid transparent;
		cursor: pointer;
	}
</style>

<script type="text/javascript">
	$(function () {
		$(".muda_alcunha").change(function () {
			var data = "?cod=" + $(this).attr("data") + "&alc=" + $(this).val();
			sendGet('Personagem/muda_alcunha.php' + data);
		});

		$('.reset-nome').click(function () {
			var cod = $(this).data('pers');
			bootbox.prompt('Escreva um novo nome para esse personagem:', function (input) {
				if (input) {
					sendGet('Vip/reset_nome.php?nome=' + input + '&cod=' + cod);
				}
			});
		});
		$('.reset-nome-dobroes').click(function () {
			var cod = $(this).data('pers');
			bootbox.prompt('Escreva um novo nome para esse personagem:', function (input) {
				if (input) {
					sendGet('VipDobroes/reset_nome.php?nome=' + input + '&cod=' + cod);
				}
			});
		});

		$('.trocar-personagem').click(function () {
			var pers = $(this).data('pers');
			var tipo = $(this).data('tipo');
			$('#pers-trocar-personagem').val(pers);
			$('#tipo-trocar-personagem').val(tipo);

			$('#modal-trocar-personagem').modal('show');
		});

		$('.capitao-selectable-img').click(function () {
			var img = $(this).data("img");
			$('.capitao-selectable-img').css('border', 'none');
			$(this).css('border', '4px solid #870000');
			$("#img_capitao").attr("src", "Imagens/Personagens/Big/" + img + "(0).jpg");
			$("#img-trocar-personagem").val(img);
		});

		$(".trocar-personagem-confirm").click(function () {
			$('#modal-trocar-personagem').modal('hide');
			var pers = $('#pers-trocar-personagem').val();
			var tipo = $('#tipo-trocar-personagem').val();
			var img = $('#img-trocar-personagem').val();
			sendGet('Vip/trocar_personagem.php?pers=' + pers + '&img=' + img + '&tipo=' + tipo);
		});
	});

</script>
<div class="row">
	<div class="col-md-3">
		<?= big_pers_skin($pers["img"], $pers["skin_c"], $pers["borda"], "hidden-sm hidden-xs") ?>
		<button class="btn btn-info trocar-personagem"
				data-pers="<?= $pers["cod"] ?>"
				data-tipo="gold"
			<?= $userDetails->conta["gold"] >= PRECO_GOLD_TROCAR_PERSONAGEM ? "" : "disabled" ?>>
			<?= PRECO_GOLD_TROCAR_PERSONAGEM ?>
			<img src="Imagens/Icones/Gold.png"/>
			Trocar Personagem
		</button>
		<button class="btn btn-info trocar-personagem"
				data-pers="<?= $pers["cod"] ?>"
				data-tipo="dobrao"
			<?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_TROCAR_PERSONAGEM ? "" : "disabled" ?>>
			<?= PRECO_DOBRAO_TROCAR_PERSONAGEM ?>
			<img src="Imagens/Icones/Dobrao.png"/>
			Trocar Personagem
		</button>
	</div>
	<div class="col-md-9">
		<h4>
			Alcunha:
			<?php if (count($titulos)): ?>
				<select data="<?= $pers["cod"]; ?>" class="muda_alcunha">
					<option value="0">Sem alcunha</option>
					<?php foreach ($titulos as $titulo) : ?>
						<option value="<?= $titulo["titulo"] ?>" <?= ($titulo["titulo"] == $pers["titulo"]) ? 'selected="1"' : "" ?>>
							<?= $titulo["nome"] ?>
						</option>
					<?php endforeach; ?>
				</select>
			<?php else: ?>
				Sem alcunha
			<?php endif; ?>
		</h4>
		<p>
			<button class="btn btn-info link_send"
					href="link_Personagem/change_sex.php?pers=<?= $pers["cod"] ?>">
				Usar alcunha <?= $pers["sexo"] == 0 ? "Feminina" : "Masculina"; ?>
			</button>
		</p>

		<h3>Nome: <?= $pers["nome"]; ?></h3>
		<p>
			<button class="reset-nome btn btn-info" data-pers="<?= $pers["cod"] ?>"
				<?= $userDetails->conta["gold"] >= PRECO_GOLD_RESET_NOME_PERSONAGEM ? "" : "disabled" ?> >
				<?= PRECO_GOLD_RESET_NOME_PERSONAGEM ?> <img src="Imagens/Icones/Gold.png"/>
				Renomear o personagem
			</button>
			<button class="reset-nome-dobroes btn btn-info" data-pers="<?= $pers["cod"] ?>"
				<?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_NOME_PERSONAGEM ? "" : "disabled" ?>>
				<?= PRECO_DOBRAO_RESET_NOME_PERSONAGEM ?> <img
						src="Imagens/Icones/Dobrao.png"/>
				Renomear o personagem
			</button>
		</p>

		<p>
			<a class="btn btn-success link_content" href="./?ses=skins&cod=<?= $pers["cod"] ?>">
				Mudar a Aparência do tripulante
			</a>
		</p>

	</div>
</div>

<h3>Aparências</h3>
<?php if ($userDetails->tripulacao["credito_skin"]): ?>
	<h4>Você tem direito à <?= $userDetails->tripulacao["credito_skin"] ?> Aparência(s) Gratuita(s)</h4>
<?php endif; ?>
<div class="row">
	<?php for ($skin = 0; $skin <= $skins_pers; $skin++): ?>
		<?php if ($skin == 0 || substr($skins[$pers["img"]][$skin], 0, 2) != "ID" || $skins[$pers["img"]][$skin] == "ID" . $userDetails->tripulacao["id"]): ?>
			<div class="col-xs-12 col-sm-6 col-md-3">
				<div class="box-item" style="min-height: 460px">
					<?php $tem_skin = $skin == 0 || (isset($minhas_skins[$pers["img"]]) && in_array($skin, $minhas_skins[$pers["img"]])); ?>
					<img class="<?= $pers["skin_r"] == $skin ? "skin-ativa" : "skin-nao-ativa" ?> <?= $tem_skin ? "link_send" : "" ?>"
						<?= $tem_skin ? 'href="link_Personagem/mudar_skin.php?pers=' . $pers["cod"] . '&tipo=r&skin=' . $skin . '"' : "" ?>
						 src="Imagens/Personagens/Icons/<?= get_img(array("img" => $pers["img"], "skin_r" => $skin), "r") ?>.jpg">
					<br/>
					<div class="<?= $pers["skin_c"] == $skin ? "skin-ativa" : "skin-nao-ativa" ?> <?= $tem_skin ? "link_send" : "" ?>"
						 style="display: inline-block"
						<?= $tem_skin ? 'href="link_Personagem/mudar_skin.php?pers=' . $pers["cod"] . '&tipo=c&skin=' . $skin . '"' : "" ?>>
						<?= big_pers_skin($pers["img"], $skin, $pers["borda"], "", 'style="max-width: 100%"') ?>
					</div>
					<?php if (isset($fan_arts[$pers["img"]]) && isset($fan_arts[$pers["img"]][$skin])): ?>
						<p>
							Essa aparência é uma cortesia de
							<a href="<?= $fan_arts[$pers["img"]][$skin]["link"] ?>" target="_blank">
								<?= $fan_arts[$pers["img"]][$skin]["autor"] ?>
							</a>
						</p>
					<?php endif; ?>
					<div style="margin-top: 10px">
						<?php if (!$tem_skin): ?>
							<?php if ($skins[$pers["img"]][$skin] != -1 && substr($skins[$pers["img"]][$skin], 0, 2) != "ID"): ?>
								<button href="Vip/comprar_skin.php?tipo=gold&img=<?= $pers["img"] ?>&skin=<?= $skin ?>"
										data-question="Deseja comprar essa aparência para <?= $pers["nome"] ?>?"
										class="btn btn-info link_confirm">
									<?= $skins[$pers["img"]][$skin] ?>
									<img src="Imagens/Icones/Gold.png">
								</button>
								<button href="Vip/comprar_skin.php?tipo=dobrao&img=<?= $pers["img"] ?>&skin=<?= $skin ?>"
										data-question="Deseja comprar essa aparência para <?= $pers["nome"] ?>?"
										class="btn btn-info link_confirm">
									<?= ceil($skins[$pers["img"]][$skin] * 1.2) ?>
									<img src="Imagens/Icones/Dobrao.png">
								</button>
							<?php else: ?>
								<p>
									Essa é uma aparência rara de eventos e não está disponível para compra
								</p>
							<?php endif; ?>
							<?php if ($userDetails->tripulacao["credito_skin"]): ?>
								<?php if ($skin == 0 || substr($skins[$pers["img"]][$skin], 0, 2) != "ID" || $skins[$pers["img"]][$skin] == "ID" . $userDetails->tripulacao["id"]): ?>
									<button href="Vip/comprar_skin.php?tipo=credito&img=<?= $pers["img"] ?>&skin=<?= $skin ?>"
											data-question="Deseja comprar essa aparência para <?= $pers["nome"] ?>?"
											class="btn btn-info link_confirm">
										Adquirir gratuitamente
									</button>
								<?php endif; ?>
							<?php endif; ?>
						<?php else: ?>
							<p>
								Aparência disponível. <i class="fa fa-check"></i> <br/>
								Clique na foto para ativar
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endfor; ?>
</div>

<h3>Bordas</h3>
<div class="row">
	<?php foreach ($bordas as $id => $borda): ?>
		<?php $tem_skin = $id == 0 || isset($minhas_bordas[$id]); ?>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="box-item" style="min-height: 420px">
				<div class="<?= $pers["borda"] == $id ? "skin-ativa" : "skin-nao-ativa" ?> <?= $tem_skin ? "link_send" : "" ?>" style="display: inline-block;"
					<?= $tem_skin ? 'href="link_Personagem/mudar_borda.php?pers=' . $pers["cod"] . '&borda=' . $id . '"' : "" ?>>
					<?= big_pers_skin($pers["img"], $pers["skin_c"], $id, "", 'style="max-width: 100%"') ?>
				</div>
				<p>
					<?= $borda["msg"] ?>
				</p>
				<?php if ($tem_skin): ?>
					<p class="text-success">Borda disponível. <i class="fa fa-check"></i></p>
					<p>Clique na foto para ativar</p>
				<?php elseif ($borda["preco"] > 0): ?>
					<button class="btn btn-info link_confirm" data-question="Deseja adquirir essa borda para sua tripulação?" href="Vip/comprar_borda.php?borda=<?= $id ?>">
						<?= $borda["preco"] ?>
						<img src="Imagens/Icones/Gold.png" />
					</button>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
