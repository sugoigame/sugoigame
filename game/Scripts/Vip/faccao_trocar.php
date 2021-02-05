<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($inally) {
    mysql_close();
    echo("#Você não pode trocar de faccao se fizer parte de uma aliança ou frota");
    exit();
}

$protector->need_gold(PRECO_GOLD_TROCAR_FACCAO);

if ($usuario["faccao"] == 0) $faccao = 1;
else if ($usuario["faccao"] == 1) $faccao = 0;
$query = "UPDATE tb_usuarios usr SET usr.faccao='$faccao', usr.bandeira='010113046758010128123542010115204020',
usr.reputacao = (SELECT IF(sum(pers.lvl) < 50 * 15, sum(pers.lvl), 50 * 15) FROM tb_personagens pers WHERE pers.id = usr.id) * 5,
usr.reputacao_mensal = (SELECT IF(sum(pers.lvl) < 50 * 15, sum(pers.lvl), 50 * 15) FROM tb_personagens pers WHERE pers.id = usr.id) * 5
WHERE usr.id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel trocar de faccao");

$userDetails->reduz_gold(PRECO_GOLD_TROCAR_FACCAO, "mudar_faccao");

echo("|Facção trocada!<br>Atualize a página!");
mysql_close();
