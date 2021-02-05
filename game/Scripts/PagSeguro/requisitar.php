<?php
/*
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!isset($_POST["code"]) OR !preg_match(INT_FORMAT, $_POST["code"])) {
    mysql_close();
    header("location:../../index.php?ses=vipComprar&msg=Você informou algum dado inválido.");
    exit();
}

$code = $_POST["code"];

if($code < 0 OR $code > 5){
    mysql_close();
    header("location:../../index.php?ses=vipComprar&msg=Você informou algum dado inválido.");
    exit();
}

switch ($code){
    case 0:
        $desc = "Bau de Madeira";
        $price = "10.00";
        break;
    case 1:
        $desc = "Bau de Ferro";
        $price = "20.00";
        break;
    case 2:
        $desc = "Bau de Bronze";
        $price = "30.00";
        break;
    case 3:
        $desc = "Bau de Prata";
        $price = "40.00";
        break;
    case 4:
        $desc = "Bau de Ouro";
        $price = "50.00";
        break;
    case 5:
        $desc = "Bau de Diamante";
        $price = "100.00";
        break;
}

    
$email = "ivan.i.n@hotmail.com";
$token = "2400470589D74E829C52453BB4E4B9DF";
    
$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/checkout/";
$url .= '?email=' . $email . '&token=' . $token;

$xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>'
.'<checkout>'
    .'<currency>BRL</currency>'  
    .'<items>'  
        .'<item>'  
            .'<id>'.$code.'</id>'  
            .'<description>'.$desc.'</description>'  
            .'<amount>'.$price.'</amount>'  
            .'<quantity>1</quantity>'  
        .'</item>' 
    .'</items>'
    . '<metadata>'
        .'<item>'
            .'<key>PLAYER_ID</key>'
            .'<value>'.$conta["conta_id"].'</value>'
        .'</item>'
    .'</metadata>'
    .'<reference>'.$conta["email"].'</reference>' 
.'</checkout>';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml; charset=UTF-8'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

$response = curl_exec($ch);

$error = curl_error($ch);
if ($error) {
    mysql_close();
    header("location:../../index.php?ses=vipComprar&msg=Ocorreu um erro ao tentar se conectar com a API de pagamentos.");
    exit();
}

define('CODE_REG', "/\<code\>(.+)\<\/code\>/");

$codeReturn = array();
preg_match(CODE_REG, $response, $codeReturn);

var_dump($response);
var_dump($codeReturn);

return;

header("location:https://pagseguro.uol.com.br/v2/checkout/payment.html?code=".$codeReturn[0]);
