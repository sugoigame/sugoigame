<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if (! isset($_GET["cod"])) {

    echo ("#Você não informou as informações necessárias.");
    exit();
}
$personagem = $protector->get_number_or_exit("cod");

if (! preg_match("/^[\d]+$/", $personagem)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE cod='$personagem' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Persoangem nao encontrado");
    exit();
}
$pers = $result->fetch_array();

$protector->need_dobroes(PRECO_DOBRAO_RESET_ATRIBUTOS);

$query = "INSERT INTO tb_resets (tipo, cod) VALUES ('1', '$personagem')";
$connection->run($query) or die("Nao foi possivel atualizar seu ouro");

$att = (($pers["lvl"] - 1) * 4) + 69;
$hp = (($pers["lvl"] - 1) * 100) + 2500;
$mp = (($pers["lvl"] - 1) * 7) + 100;

$bonus = get_bonus_excelencia($pers["classe"], $pers["excelencia_lvl"]);

$hp += $bonus["hp_max"];
$mp += $bonus["mp_max"];

$connection->run(
    "UPDATE tb_personagens 
	SET 
	atk = ?, 
	def = ?, 
	agl = ?, 
	res = ?, 
	pre = ?, 
	dex = ?, 
	con = ?, 
	vit = ?, 
	pts = '$att', 
	hp = '$hp', 
	hp_max = '$hp', 
	mp_max = '$mp', 
	mp = '$mp' 
	WHERE cod = ?",
    "iiiiiiiii", array(
        1 + $bonus["atk"],
        1 + $bonus["def"],
        1 + $bonus["agl"],
        1 + $bonus["res"],
        1 + $bonus["pre"],
        1 + $bonus["dex"],
        1 + $bonus["con"],
        1 + $bonus["vit"],
        $personagem
    )
);
$skils_nao_resetaveis = array_merge($COD_HAOSHOKU_LVL, array(1, 2));

$query = "DELETE FROM tb_personagens_skil WHERE cod='$personagem' AND 
	((tipo='1' AND cod_skil NOT IN (" . implode(",", $skils_nao_resetaveis) . ")) OR tipo='2' OR tipo='3')";
$connection->run($query) or die("Nao foi possivel remover as habilidades.");

$userDetails->reduz_dobrao(PRECO_DOBRAO_RESET_ATRIBUTOS, "resetar_atributos");


echo ("-Atributos Resetados!");
?>

