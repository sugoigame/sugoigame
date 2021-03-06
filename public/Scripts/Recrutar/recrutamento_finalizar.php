<?php
require_once "../../Includes/conectdb.php";
require_once "../../Includes/verifica_login.php";
require_once "../../Includes/verifica_missao.php";
require_once "../../Includes/verifica_combate.php";

if (!$conect) {
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    echo("#Você está em combate");
    exit();
}
if (!$inrecrute AND $inmissao) {
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    echo("#Você precisa estar em uma ilha!");
    exit();
}
if ($usuario["recrutando"] > atual_segundo() OR $usuario["recrutando"] == 0) {
    echo("#Você não concluiu uma procura por tripulantes");
    exit();
}
if (!isset($_POST["nome"])) {
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_POST["img"])) {
    echo("#Você informou algum caracter inválido.");
    exit();
}
$nome   = trim($_POST["nome"]);
$img    = $_POST["img"];
$lvl    = $protector->post_number_or_exit("lvl");

if (!preg_match("/^[\w ]+$/", $nome) OR strlen($nome) < 3) {
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $img)) {
    echo("#Você informou algum caracter inválido.");
    exit();
}

if ($lvl > 1) {
    $tipoLvl = $protector->post_enum_or_exit("tipoLvl", [
        "gold",
        "dobrao"
    ]);

    $preco = $lvl * PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO;
    if ($tipoLvl == "gold") {
        $protector->need_gold($preco);
    } else {
        $protector->need_dobroes(ceil($preco * PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO));
    }
}

if ($usuario["recrutando"] > atual_segundo() OR $usuario["recrutando"] == 0) {
    echo("#Voce não iniciou uma procura nessa ilha.");
    exit();
}

$result = $connection->run("SELECT * FROM tb_navio WHERE cod_navio = ?", 'i', [
    $usuario['navio']
]);

if ($result->count() == 0 OR $usuario["navio"] == 0) {

    echo("#Voce precisa de um navio.");
    exit();
}

$limite = $result->fetch_array();
if ($limite["limite"] <= sizeof($personagem)) {
    echo("#Seu navio está cheio.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE nome='$nome'";
$result = $connection->run("SELECT * FROM tb_personagens WHERE nome = ?", 's', [
    $nome
]);
if ($result->count() != 0) {
    echo("#Esse nome ja esta cadastrado");
    exit();
}

$result = $connection->run("SELECT * FROM tb_ilha_personagens WHERE ilha = ?", 'i', [
    $usuario['ilha']
]);

for ($x = 0; $sql = $result->fetch_array(); $x++) {
    $ilha_personagens[$x] = $sql;
}

$possivel = FALSE;
for ($x = 0; $x < sizeof($ilha_personagens); $x++) {
    if ($ilha_personagens[$x]["img"] == $img) {
        $possivel = TRUE;
    }
}

if (!$possivel) {
    $skins = $connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ? AND img = ?", "ii", [
        $userDetails->tripulacao["id"],
        $img
    ])->count();

    if (!$skins) {
        $protector->exit_error("Esse tripulante não esta disponível nessa ilha");
    }
}

$connection->run("UPDATE tb_usuarios SET recrutando = '0' WHERE id = ?", 'i', [
    $usuario["id"]
]);
if ($connection->affected_rows() == 0) {
    echo("#Você não iniciou um recrutamento");
    exit();
}

$xp = 0;
if ($lvl > 1) {
    for ($i = 1; $i < $lvl; $i++) {
        $xp += formulaExp($i);
    }
}

$result = $connection->run("INSERT INTO tb_personagens (id, img, nome, xp, xp_max) VALUES (?, ?, ?, ?, ?)", "iisii", [
    $userDetails->tripulacao["id"],
    $img,
    $nome,
    $xp,
    formulaExp()
]);
$personagemId = $result->last_id();

$connection->run("INSERT INTO tb_personagens_skil (cod,cod_skil,tipo,nome,descricao,icon) VALUES (?,?,?,'Soco','Tenta acerta um soco no oponente.','1')", 'iii', [
    $personagemId,
    COD_SKILL_SOCO,
    TIPO_SKILL_ATAQUE_CLASSE
]);

if ($lvl > 1) {
    $tipoLvl = $protector->post_enum_or_exit("tipoLvl", [
        "gold",
        "dobrao"
    ]);

    $precoGold      = ($lvl - 1) * PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO;
    $precoDobrao    = ($lvl - 1) * PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO;
    if ($tipoLvl == "gold") {
        $userDetails->reduz_gold($precoGold, "recrutar_lvl_alto");
    } else {
        $userDetails->reduz_dobrao($precoDobrao, "recrutar_lvl_alto");
    }
}

$personagem = $connection->run("SELECT * FROM tb_personagens WHERE cod = ?", "i", $personagemId)->fetch_array();
$response->send_conquista_pers($personagem, "{$nome} é um novo membro da sua tripulação!");