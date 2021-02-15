 <?php
require_once "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers		= $protector->get_tripulante_or_exit("cod");
$cod_skill	= $protector->get_number_or_exit("codskill");
$tipo_skill	= $protector->get_enum_or_exit("tiposkill", [
	TIPO_SKILL_ATAQUE_CLASSE,
	TIPO_SKILL_BUFF_CLASSE,
	TIPO_SKILL_PASSIVA_CLASSE
]);

$exists = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?", "iii", [
	$pers["cod"],
	$cod_skill,
	$tipo_skill
]);

if ($exists->count()) {
    $protector->exit_error("Você já possui essa habilidade");
}

if ($tipo_skill == TIPO_SKILL_ATAQUE_CLASSE) {
    $tb = "tb_skil_atk";
} else if ($tipo_skill == TIPO_SKILL_BUFF_CLASSE) {
    $tb = "tb_skil_buff"; 
} else {
    $tb = "tb_skil_passiva";
}

$result = $connection->run("SELECT * FROM $tb WHERE cod_skil = ? AND maestria = 0", "i", $cod_skill);
if (!$result->count()) {
    $protector->exit_error("Habilidade inválida");
}

$skill	= $result->fetch_array();
$rec_1	= nome_atributo_tabela($skill["requisito_atr_1"]);
$rec_2	= nome_atributo_tabela($skill["requisito_atr_2"]);

if ($pers["lvl"] < $skill["requisito_lvl"]
    || $pers[$rec_1] < $skill["requisito_atr_1_qnt"]
    || $pers[$rec_2] < $skill["requisito_atr_2_qnt"]
    || $userDetails->tripulacao["berries"] < $skill["requisito_berries"]
    || $pers["classe"] != $skill["requisito_classe"]
) {
    $protector->exit_error("Você não cumpre os requisitos para aprender essa habilidade");
}

$skills_personagem = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND (tipo = 1 OR tipo = 2 OR tipo = 3)", "i", $pers["cod"])->fetch_all_array();
foreach ($skills_personagem as $x => $outra_skill) {
	switch ($outra_skill["tipo"]) {
		case TIPO_SKILL_ATAQUE_CLASSE:
			$table = "tb_skil_atk";
			break;
		case TIPO_SKILL_BUFF_CLASSE:
			$table = "tb_skil_buff";
			break;
		default:
			$table = "tb_skil_passiva";
			break;
	}

	$result = $connection->run("SELECT * FROM `tb_personagens_skil` `ps` INNER JOIN `{$table}` `info` ON `ps`.`cod_skil` = `info`.`cod_skil` WHERE `ps`.`cod` = ? AND `ps`.`tipo` = ? AND `info`.`requisito_lvl` = ? AND `requisito_classe` <> 0 AND `maestria` = 0", "iii", [
		$pers["cod"],
		$outra_skill["tipo"],
		$skill["requisito_lvl"]
	]);

	if ($result->count()) {
		$protector->exit_error("Você já aprendeu outra habilidade desse nível.");
	}
}

$habilidade = habilidade_random();
$icon = rand(1, SKILLS_ICONS_MAX);

$connection->run("INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon) VALUE (?,?,?,?,?,?)", "iiissi", [
	$pers["cod"],
	$cod_skill,
	$tipo_skill,
	$habilidade["nome"],
	$habilidade["descricao"],
	$icon
]);

$userDetails->reduz_berries($skill["requisito_berries"]);

$response->send($pers["nome"] . " aprendeu uma nova habilidade. Visite o menu de Habilidades para customiza-la!");