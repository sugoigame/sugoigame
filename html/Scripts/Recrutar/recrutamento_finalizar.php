<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    mysql_close();
    echo("#Você está em combate");
    exit();
}
if (!$inrecrute AND $inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha!");
    exit();
}
if ($usuario["recrutando"] > atual_segundo() OR $usuario["recrutando"] == 0) {
    mysql_close();
    echo("#Você não concluiu uma procura por tripulantes");
    exit();
}
if (!isset($_POST["nome"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_POST["img"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$nome = trim(mysql_real_escape_string($_POST["nome"]));
$img = mysql_real_escape_string($_POST["img"]);
$lvl = $protector->post_number_or_exit("lvl");
if (!preg_match("/^[\w ]+$/", $nome) OR strlen($nome) < 3) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $img)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

if ($lvl > 1) {
    $tipoLvl = $protector->post_enum_or_exit("tipoLvl", array("gold", "dobrao"));

    $preco = $lvl * PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO;
    if ($tipoLvl == "gold") {
        $protector->need_gold($preco);
    } else {
        $protector->need_dobroes(ceil($preco * PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO));
    }
}

if ($usuario["recrutando"] > atual_segundo() OR $usuario["recrutando"] == 0) {
    mysql_close();
    echo("#Voce não iniciou uma procura nessa ilha.");
    exit();
}
$query = "SELECT * FROM tb_navio WHERE cod_navio='" . $usuario["navio"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0 OR $usuario["navio"] == 0) {
    mysql_close();
    echo("#Voce precisa de um navio.");
    exit();
}
$limite = mysql_fetch_array($result);

if ($limite["limite"] <= sizeof($personagem)) {
    mysql_close();
    echo("#Seu navio está cheio.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE nome='$nome'";
$result = mysql_query($query);
if (mysql_fetch_array($result) != 0) {
    mysql_close();
    echo("#Esse nome ja esta cadastrado");
    exit();
}

$query = "SELECT * FROM tb_ilha_personagens WHERE ilha='" . $usuario["ilha"] . "'";
$result = mysql_query($query);
for ($x = 0; $sql = mysql_fetch_array($result); $x++) {
    $ilha_personagens[$x] = $sql;
}
$possivel = FALSE;
for ($x = 0; $x < sizeof($ilha_personagens); $x++) {
    if ($ilha_personagens[$x]["img"] == $img) {
        $possivel = TRUE;
    }
}

if (!$possivel) {
    $skins = $connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ? AND img = ?",
        "ii", array($userDetails->tripulacao["id"], $img))->count();

    if (!$skins) {
        $protector->exit_error("Esse tripulante não esta disponível nessa ilha");
    }
}

$query = "UPDATE tb_usuarios SET recrutando='0' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die ("nao foi possivel concluir");
if (mysql_affected_rows() == 0) {
    mysql_close();
    echo("#Você não iniciou um recrutamento");
    exit();
}

$xp = 0;

if ($lvl > 1) {
    $lvl -= 1;// corrige pra nao dar xp por um nivel a mais
    $xp = (((250 * ($lvl - 1) + 500) + 500) * $lvl) / 2;
}

$result = $connection->run("INSERT INTO tb_personagens (id, img, nome, xp) VALUES (?, ?, ?, ?)",
    "iisi", array($userDetails->tripulacao["id"], $img, $nome, $xp));

$cod = $result->last_id();

$query = "INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon)
		VALUES ('$cod', '" . COD_SKILL_SOCO . "', '" . TIPO_SKILL_ATAQUE_CLASSE . "', 'Soco', 'Tenta acerta um soco no oponente.', '1')";
mysql_query($query) or die ("nao foi possivel criar skil1");

if ($lvl > 1) {
    $tipoLvl = $protector->post_enum_or_exit("tipoLvl", array("gold", "dobrao"));

    $preco = $lvl * PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO;
    if ($tipoLvl == "gold") {
        $userDetails->reduz_gold($preco, "recrutar_lvl_alto");
    } else {
        $userDetails->reduz_dobrao(ceil($preco * PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO), "recrutar_lvl_alto");
    }
}

$personagem = $connection->run("SELECT * FROM tb_personagens WHERE cod = ?", "i", array($cod))->fetch_array();

$response->send_conquista_pers($personagem, "$nome é um novo membro da sua tripulação!");