<?php
include "../../Includes/conectdb.php";
if ($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET['code'])) {
    echo "Request method invalid";
    exit(0);
}

$appId = '';
$appSecret = '';

// Url informada no campo "Site URL"
$redirectUri = urlencode('https://sugoigame.com.br/Scripts/Geral/login_facebook.php');

$code = $_GET['code'];

$token_url = "https://graph.facebook.com/oauth/access_token?"
    . "client_id=" . $appId . "&redirect_uri=" . $redirectUri
    . "&client_secret=" . $appSecret . "&code=" . $code;

$response = @file_get_contents($token_url);
if (!$response) {
    echo "Erro de conexão com Facebook";
    exit(0);
}

$params = json_decode($response, true);
if (!isset($params['access_token']) || !$params['access_token']) {
    echo "Access Token Invalido";
    exit(0);
}

$graph_url = "https://graph.facebook.com/me?fields=name,email&access_token=" . $params['access_token'];
$user = json_decode(file_get_contents($graph_url));

// nesse IF verificamos se veio os dados corretamente
if (!isset($user->email) || !$user->email) {
    echo "Email not provided";
    exit(0);
}

$result = $connection->run("SELECT * FROM tb_conta WHERE email=?", "s", $user->email);

if (!$result->count()) {
    $id_encrip = md5(time());
    $connection->run(
        "INSERT INTO tb_conta (nome, id_encrip, email, senha, ativacao, dobroes) 
                      VALUES ( ?, ?, ?, '', NULL, 600)",
        "sss", array($user->name, $id_encrip, $user->email)
    );
    $result = $connection->run("SELECT * FROM tb_conta WHERE email=?", "s", $user->email);
}
$conta = $result->fetch_array();

$id = $conta["conta_id"];
$cookie = md5(uniqid(time()));

$_SESSION["sg_c"] = $id;
$_SESSION["sg_k"] = $cookie;

setcookie("chat", "0", time() + 80000, '/', FALSE, TRUE);
setcookie("sg_c", $id, time() + 80000, '/', FALSE, TRUE);
setcookie("sg_k", $cookie, time() + 80000, '/', FALSE, TRUE);

//atualiza o cookie do bd
$connection->run("UPDATE tb_conta SET cookie = ?, ativacao = NULL, fbid = ? WHERE conta_id = ?", "ssi", array($cookie, $user->id, $id));

//redireciona
if ($conta["tripulacao_id"]) {
    header("location:../../index.php?ses=home");
} else {
    header("location:../../index.php?ses=seltrip");
}