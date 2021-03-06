<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_alive();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_ilha();
$protector->must_be_out_of_rota();

$tipo = $protector->get_number_or_exit("tipo");
if ($tipo != 16 AND $tipo != 17) {
    $protector->exit_error("Você informou algo inválido.");
}

if ($userDetails->tripulacao['iscas_usadas'] >= LIMITE_USOS_ISCA_DIA) {
    $protector->exit_error("Você já atingiu o limite de iscas que podem ser usadas por dia.");
}

if ($userDetails->navio["ultimo_disparo_sofrido"] > (atual_segundo() - 30)) {
    $protector->exit_error("Você foi atingido por um canhão a menos de 30 segundos e precisa esperar para usar uma isca.");
}

$result = $connection->run("SELECT * FROM tb_usuario_itens WHERE tipo_item = ? AND id = ?", 'ii', [
    $tipo,
    $userDetails->tripulacao['id']
]);
if ($result->count() <= 0) {
    $protector->exit_error("Você não possui esse item.");
}
$item = $result->fetch_array();
if ($item['quant'] <= 1) {
    $query = "DELETE FROM tb_usuario_itens WHERE tipo_item = ? AND id = ?";
} else {
    $nquant = $item['quant'] - 1;
    $query = "UPDATE tb_usuario_itens SET quant = {$nquant} WHERE tipo_item = ? AND id = ?";
}
$connection->run($query, 'ii', [
    $tipo,
    $userDetails->tripulacao['id']
]);

$connection->run("UPDATE tb_usuarios SET iscas_usadas = iscas_usadas + 1 WHERE id = ? LIMIT 1", 'i', [
    $userDetails->tripulacao['id']
]);

if ($tipo == 16)        $porc = 30;
else if ($tipo == 17)   $porc = 100;
else                    $porc = 0;

if (rand(1, 100) <= $porc) {
    atacar_rdm(rand(1, 4));
    echo "%combate";
} else {
    echo "Nada aconteceu.";
}