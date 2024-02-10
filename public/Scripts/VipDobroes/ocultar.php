<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}

$protector->must_be_out_of_any_kind_of_combat();

$protector->need_dobroes(PRECO_DOBRAO_CAMUFLAGEM);

$query = "DELETE FROM tb_mapa_contem WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel atualizar seu ouro");

$userDetails->reduz_dobrao(PRECO_DOBRAO_CAMUFLAGEM, "ocultar_tripulacao");

echo ("|Parabens!<br>Agora você estará invisível enquanto estiver parado!");
