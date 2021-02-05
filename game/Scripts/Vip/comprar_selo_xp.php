<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers = $protector->get_tripulante_or_exit("cod");

$protector->need_gold(PRECO_GOLD_SELO_EXP);

$connection->run("UPDATE tb_personagens SET selos_xp = selos_xp + 1 WHERE cod = ?",
    "i", array($pers["cod"]));

$userDetails->reduz_gold(PRECO_GOLD_SELO_EXP, "selos_xp");

$response->send_share_msg($pers["nome"] . " recebeu 1 Selo de Experiência!");