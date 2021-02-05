<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    mysql_close();
    echo("#Você está em combate!");
    exit();
}
$protector->need_dobroes(PRECO_DOBRAO_FINALIZAR_TRATAMENTO_HOSPITAL);
if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = $_GET["cod"];
if (!preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont == 0) {
    mysql_close();
    echo("#Personagem não encontrado.");
    exit();
}
$personagem = mysql_fetch_array($result);
if ($personagem["hp"] == 0) {

    if ($personagem["respawn_tipo"] == 1) {
        $connection->run(
            "UPDATE tb_personagens SET profissao_xp = profissao_xp + 1 
            WHERE profissao = ? AND profissao_xp < profissao_xp_max AND id = ? AND ativo = 1",
            "ii", array(PROFISSAO_MEDICO, $userDetails->tripulacao["id"])
        );
    }

    $query = "UPDATE tb_personagens SET respawn_tipo='0', respawn='0', mp='" . $personagem["mp_max"] . "',  hp='" . $personagem["hp_max"] . "' WHERE cod='$cod'";
    mysql_query($query) or die("nao foi possivel finalizar a recuperacao");

    $userDetails->reduz_dobrao(PRECO_DOBRAO_FINALIZAR_TRATAMENTO_HOSPITAL, "finalizar_hospital");

    mysql_close();
    echo "-Tratamento Concluido!";
} else {
    mysql_close();
    echo("#Este personagem não iniciou algum tratamento ou você nao tem ouro sufuciente para pagar.");
}
?>