<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você não enviou todas as informações!");
    exit();
}
$cod = mysql_real_escape_string($_GET["cod"]);

if (!preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido");
    exit();
}
$query = "SELECT * FROM tb_realizacoes WHERE cod_realizacao='$cod'";
$result = mysql_query($query);
$realizacao = mysql_fetch_array($result);

$realizacoes_mng = new Realizacoes($userDetails, $connection);

$pers = NULL;
if ($realizacao["tipo"] == 1) {
    if (!isset($_GET["pers"])) {
        mysql_close();
        echo("#Você não enviou todas as informações!");
        exit();
    }
    $person = mysql_real_escape_string($_GET["pers"]);

    if (!preg_match("/^[\d]+$/", $person)) {
        mysql_close();
        echo("#Você informou algum caracter inválido");
        exit();
    }

    $verifi = 0;
    for ($x = 0; $x < sizeof($personagem); $x++) {
        if ($personagem[$x]["cod"] == $person) {
            $pers = $personagem[$x];
            $verifi++;
        }
    }
    if ($verifi == 0) {
        mysql_close();
        echo("#Personagem não encontrado");
        exit();
    }
}

$x = 0;
$query = "SELECT tipo FROM tb_realizacoes_concluidas 
	WHERE id='" . $usuario["id"] . "' AND cod_realizacao='" . $realizacao["cod_realizacao"] . "' AND tipo='" . $realizacao["tipo"] . "'";
if ($realizacao["tipo"] == 1) {
    $query .= " AND personagem='$person'";
}
$result = mysql_query($query);
if (mysql_num_rows($result) != 0) {
    mysql_close();
    echo("#Você não concluiu essa realização");
    exit();
}

$status_key = "status_" . $cod;
$status = $realizacoes_mng->$status_key($pers);

if ($status["status"]["status"] != 2) {
    mysql_close();
    echo("#Você não concluiu essa realização");
    exit();
}

if ($realizacao["titulo"] != 0) {
    $person = $realizacao["tipo"] == 1 ? $person : $userDetails->capitao["cod"];
    $query = "INSERT INTO tb_personagem_titulo (cod, titulo)
		VALUES ('$person', '" . $realizacao["titulo"] . "')";
    mysql_query($query);
}

$rep2 = $usuario["realizacoes"] + $realizacao["pontos"];
$query = "UPDATE tb_usuarios SET realizacoes='$rep2' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("não foi possivel atualizar realizacoes");

$pers_da_realizacao = $status["status"]["pers"];

if ($pers_da_realizacao == "0" || $pers_da_realizacao === NULL) {
    $pers_da_realizacao = "NULL";
} else {
    $pers_da_realizacao = "'$pers_da_realizacao'";
}

$query = "INSERT INTO tb_realizacoes_concluidas (id, cod_realizacao, tipo, personagem)
	VALUES ('" . $usuario["id"] . "', '" . $realizacao["cod_realizacao"] . "', '" . $realizacao["tipo"] . "', $pers_da_realizacao)";
mysql_query($query) or die("não foi possivel atualizar realizacoes");

echo "-Realização concluida!";
mysql_close();
	