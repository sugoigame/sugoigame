<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod = $protector->get_number_or_exit("pers");

$personagem = $userDetails->get_pers_by_cod($cod);

if (!$personagem) {
    $protector->exit_error("Personagem Inválido");
}

$connection->run("UPDATE tb_personagens SET sexo = ? WHERE cod = ?",
    "ii", array($personagem["sexo"] == 0 ? 1 : 0, $cod));

echo "Sexo alterado.";