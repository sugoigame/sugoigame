<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}
if (! isset($_GET["afilhado"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$afilhado = $protector->get_number_or_exit("afilhado");

if (! preg_match("/^[\d]+$/", $afilhado)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_afilhados WHERE id='" . $usuario["id"] . "' AND afilhado='$afilhado'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Você não é padrinho deste personagem.");
    exit();
}

$query = "SELECT * FROM tb_usuarios WHERE id='$afilhado'";
$result = $connection->run($query);
$afilhado_info = $result->fetch_array();

$query = "SELECT * FROM tb_personagens WHERE cod='" . $afilhado_info["cod_personagem"] . "'";
$result = $connection->run($query);
$afilhado_pers = $result->fetch_array();

if ($afilhado_pers["lvl"] < 30) {

    echo ("#Esse afilhado nao alcançou o nível 30 ainda.");
    exit();
}

$query = "SELECT * FROM tb_afilhados_recrutados WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {
    $query = "INSERT INTO tb_afilhados_recrutados (id, quant)
		VALUES ('" . $usuario["id"] . "', '1')";
    $connection->run($query) or die("nao foi possivel registrar o jogador");
} else {
    $afilhado_registrado = $result->fetch_array();
    $query = "UPDATE tb_afilhados_recrutados SET quant='" . ($afilhado_registrado["quant"] + 1) . "'
		WHERE id='" . $usuario["id"] . "'";
    $connection->run($query) or die("nao foi possivel registrar o jogador");
}

$gold = $usuario["gold"] + 25;
$query = "UPDATE tb_conta SET gold='$gold' WHERE conta_id='" . $userDetails->conta["conta_id"] . "'";
$connection->run($query) or die("Nao foi possivel pegar o gold");

$query = "DELETE FROM tb_afilhados WHERE id='" . $usuario["id"] . "' AND afilhado='$afilhado'";
$connection->run($query) or die("Nao foi possivel remover o afilhado");

echo ("Parabéns!<br>Você pegou sua recompensa por ter recrutado esse jogador!");
?>

