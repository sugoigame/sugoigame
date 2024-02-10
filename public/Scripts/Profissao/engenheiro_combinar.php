<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

	echo ("#Você precisa estar logado para executar essa ação.");
	exit;
}
if ($inmissao) {

	echo ("#Você está ocupado em uma missão neste meomento.");
	exit;
}
$pers = $protector->post_number_or_exit("pers");
$contzero = 0;
for ($x = 1; $x < 9; $x++) {
	$slot[$x] = explode("_", $_POST["slot_$x"]);

	if (isset($slot[$x][0]) and isset($slot[$x][1])) {
		if (! preg_match("/^[\d]+$/", $slot[$x][0]) or ! preg_match("/^[\d]+$/", $slot[$x][1])) {

			echo ("#Você informou algum caracter inválido.");
			exit();
		}
		if ($slot[$x][1] != 14) {
			$query = "SELECT * FROM tb_usuario_itens 
				WHERE cod_item='" . $slot[$x][0] . "' AND tipo_item='" . $slot[$x][1] . "' AND id='" . $usuario["id"] . "'";
			$result = $connection->run($query);
			if ($result->count() == 0) {

				echo ("#Você não possui todos os itens iformados.");
				exit();
			}
		} else {
			$query = "SELECT * FROM tb_item_equipamentos INNER JOIN tb_usuario_itens 
				ON tb_item_equipamentos.cod_equipamento=tb_usuario_itens.cod_item
				WHERE tb_item_equipamentos.item='" . $slot[$x][0] . "' AND tb_usuario_itens.tipo_item='" . $slot[$x][1] . "' 
				AND tb_usuario_itens.id='" . $usuario["id"] . "'";
			$result = $connection->run($query);
			if ($result->count() == 0) {

				echo ("#Você não possui todos os itens iformados.");
				exit();
			}
		}
	} else {
		$slot[$x][0] = "0";
		$slot[$x][1] = "0";
		$contzero++;
	}
}
if ($contzero >= 8) {

	echo ("Nada aconteceu...");
	exit();
}

$query = "SELECT * FROM tb_personagens WHERE cod='$pers' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

	echo ("#Personagem não encontrado");
	exit();
}
$personagem = $result->fetch_array();

if ($personagem["profissao"] != 10) {

	echo ("#Esse personagem não possui a profissão adequada");
	exit();
}

$query = "SELECT * FROM tb_combinacoes WHERE
	`1`='" . $slot[1][0] . "' AND `1_t`='" . $slot[1][1] . "' AND
	`2`='" . $slot[2][0] . "' AND `2_t`='" . $slot[2][1] . "' AND
	`3`='" . $slot[3][0] . "' AND `3_t`='" . $slot[3][1] . "' AND
	`4`='" . $slot[4][0] . "' AND `4_t`='" . $slot[4][1] . "' AND
	`5`='" . $slot[5][0] . "' AND `5_t`='" . $slot[5][1] . "' AND
	`6`='" . $slot[6][0] . "' AND `6_t`='" . $slot[6][1] . "' AND
	`7`='" . $slot[7][0] . "' AND `7_t`='" . $slot[7][1] . "' AND
	`8`='" . $slot[8][0] . "' AND `8_t`='" . $slot[8][1] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

	echo ("Nada aconteceu...");
	exit();
}

$receita = $result->fetch_array();
if ($receita["lvl"] > $personagem["profissao_lvl"]) {

	echo ("#Esse personagem não possui o nível de profissão adequado");
	exit();
}

$xp = $personagem["profissao_xp"] + 2;
if ($xp > $personagem["profissao_xp_max"])
	$xp = $personagem["profissao_xp_max"];

$query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE cod='$pers'";
$connection->run($query) or die("não foi possivel atualizar a xp de profissao");

for ($x = 1; $x < 9; $x++) {
	if ($slot[$x][1] != 14) {
		$query = "DELETE FROM tb_usuario_itens 
			WHERE cod_item='" . $slot[$x][0] . "' AND tipo_item='" . $slot[$x][1] . "' AND id='" . $usuario["id"] . "' LIMIT 1";
		$connection->run($query) or die("Nao foi possivel remover os itens");
	} else {
		$query = "SELECT * FROM tb_item_equipamentos INNER JOIN tb_usuario_itens 
			ON tb_item_equipamentos.cod_equipamento=tb_usuario_itens.cod_item
			WHERE tb_item_equipamentos.item='" . $slot[$x][0] . "' AND tb_usuario_itens.tipo_item='" . $slot[$x][1] . "' 
			AND tb_usuario_itens.id='" . $usuario["id"] . "'";
		$result = $connection->run($query);
		$item_del = $result->fetch_array();

		$query = "DELETE FROM tb_usuario_itens 
			WHERE cod_item='" . $item_del["cod_equipamento"] . "' AND tipo_item='14' AND id='" . $usuario["id"] . "' LIMIT 1";
		$connection->run($query) or die("Nao foi possivel remover os itens");

		$query = "DELETE FROM tb_item_equipamentos 
			WHERE cod_equipamento='" . $item_del["cod_equipamento"] . "'";
		$connection->run($query) or die("Nao foi possivel remover os itens");
	}
}

if ($receita["tipo"] != 14) {
	$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
		VALUES ('" . $usuario["id"] . "', '" . $receita["cod"] . "', '" . $receita["tipo"] . "')";
	$connection->run($query) or die("Não foi possivel criar o item");
} else {
	$query = "SELECT * FROM tb_equipamentos WHERE item='" . $receita["cod"] . "'";
	$result = $connection->run($query);
	$equip = $result->fetch_array();

	$query = "INSERT INTO tb_item_equipamentos 
		(item, img, cat_dano, b_1, b_2, categoria, nome, descricao, lvl, treino_max, slot, requisito)  
		VALUES 
		('" . $equip["item"] . "', '" . $equip["img"] . "', '" . $equip["cat_dano"] . "', '" . $equip["b_1"] . "', 
		'" . $equip["b_2"] . "', '" . $equip["categoria"] . "', '" . $equip["nome"] . "', '" . $equip["descricao"] . "', 
		'" . $equip["lvl"] . "', '" . $equip["treino_max"] . "', '" . $equip["slot"] . "', '" . $equip["requisito"] . "')";
	$connection->run($query) or die("Nao foi possivel cadastrar o item");
	$cod_item = mysql_insert_id();

	$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
		VALUES ('" . $usuario["id"] . "', '$cod_item', '14')";
	$connection->run($query) or die("Não foi possivel criar o item");
}


echo ("Novo item criado!");
?>

