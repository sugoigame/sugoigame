<?
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if (! $inilha) {

    echo "#Você precisa estar em uma ilha.";
    exit();
}
if ($usuario["ilha"] != 47) {

    echo "#Você precisa estar em uma ilha.";
    exit();
}

$query = "SELECT * FROM tb_jardim_laftel WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);

$possivel = FALSE;
if ($result->count() == 0)
    $possivel = TRUE;
else {
    $tempo = $result->fetch_array();

    if ($tempo["tempo"] < atual_segundo())
        $possivel = TRUE;
}

if (! $possivel) {

    echo "#Você ainda não pode colher uma Akuma no Mi.";
    exit();
}

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() >= $usuario["capacidade_iventario"]) {

    echo "#Seu inventário está cheio.";
    exit();
}

$tipo = TIPO_ITEM_AKUMA;
$cod = get_random_akuma()["cod_akma"];

$query = "DELETE FROM tb_jardim_laftel WHERE id='" . $usuario["id"] . "'";
$connection->run($query);

$ntime = atual_segundo() + 604800;

$query = "INSERT INTO tb_jardim_laftel (id, tempo) VALUES ('" . $usuario["id"] . "', '$ntime')";
$connection->run($query) or die("Nao foi possivel inserir o registro");

$query = "INSERT INTO tb_usuario_itens (id, tipo_item, cod_item)
	VALUES ('" . $usuario["id"] . "', '$tipo', '$cod')";
$connection->run($query) or die("Nao foi possivel inserir o registro");

echo "Você recebeu uma Akuma no Mi!";




