<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    echo("#Você precisa estar logado!");
    exit();
}
$protector->must_be_out_of_any_kind_of_combat();

$protector->need_gold(PRECO_GOLD_CAMUFLAGEM);

$connection->run("UPDATE tb_usuarios SET mar_visivel = 0 WHERE id = ?", "i", [
    $userDetails->tripulacao["id"]
]);

$userDetails->reduz_gold(PRECO_GOLD_CAMUFLAGEM, "ocultar_tripulacao");

echo("|Parabens!<br>Agora você estará invisível enquanto estiver parado!");
