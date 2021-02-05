<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

if (!isset($_POST["notificationCode"]) OR !isset($_POST["notificationType"])) {
    echo "Requisicao invalida";
    exit();
}
$code = $_POST["notificationCode"];
$type = $_POST["notificationType"];

if (strlen($code) != 39) {
    echo "Requisicao invalida";
    exit();
}

$email = "ivan.i.n@hotmail.com";
//prod
$token = "2400470589D74E829C52453BB4E4B9DF";
// teste
//$token = "E94869CE37694C08BDE3C9080DC6751B";

//Testes
//$url = "https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/";

//Prod
$url = "https://ws.pagseguro.uol.com.br/v3/transactions/notifications/";
$url .= $code;
$url .= "?email=" . $email;

$url .= "&token=" . $token;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$response = curl_exec($ch);

$error = curl_error($ch);
if ($error) {
    throw new Exception($error);
}

curl_close($ch);

$r = mysql_real_escape_string($response);
$msg = "Recebido: $r";
$query = "INSERT INTO tb_vip_pagamentos (mensagem) VALUES ('$msg')";
mysql_query($query);


define('STATUS_REG', "/\<status\>([\d]+)\<\/status\>/");
define('ID_REG', "/\<id\>(.+)\<\/id\>/");
define('REF_REG', "/\<reference\>(.+)\<\/reference\>/");

$status = array();
preg_match(STATUS_REG, $response, $status);

if (((int)$status[1]) != 3) {
    return;
}
$id_a = array();
preg_match(ID_REG, $response, $id_a);
$cod = (int)$id_a[1];

$reference_a = array();
preg_match(REF_REG, $response, $reference_a);
$id_conta = mysql_real_escape_string($reference_a[1]);

switch ($cod) {
    case 1:
        $gold = 500;
        break;
    case 2:
        $gold = 1050;
        break;
    case 3:
        $gold = 2200;
        break;
    case 4:
        $gold = 3300;
        break;
    case 5:
        $gold = 4400;
        break;
    case 6:
        $gold = 5500;
        break;
    case 7:
        $gold = 11000;
        break;
}

$ssga = new ssga(ANALYTICS_UA_NUMBER, 'sugoigame.com.br');
$ssga->set_event('Gold', 'comprar', 'pag_seguro', $gold);
$ssga->send();

$query = "UPDATE tb_conta set gold = gold+$gold WHERE conta_id='$id_conta'";
mysql_query($query);

$msg = "Registrado: $gold moedas de ouro para $id_conta";
$query = "INSERT INTO tb_vip_pagamentos (mensagem) VALUES ('$msg')";
mysql_query($query);
