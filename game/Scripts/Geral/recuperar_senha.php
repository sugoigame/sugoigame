<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";
include "../../sendgrid-php/sendgrid-php.php";

$protector->reject_conta();

$protector->redirect_email_invalid($_GET["email"]);

$email = $_GET["email"];

$result = $connection->run("SELECT conta_id, email FROM tb_conta WHERE email = ?", "s", $email);

if (!$result->count()) {
    $protector->redirect_error("Não existe conta cadastrada para o email informado");
}

$conta = $result->fetch_array();

$token = md5(time());

$connection->run(
    "INSERT INTO tb_reset_senha_token (conta_id, token, expiration) VALUE (?, ?, adddate(now(), '2 days'))",
    "is", array($conta["conta_id"], $token)
);

$url = "http://www.sugoigame.com.br/index.php?ses=recuperarSenha&token=$token";

$sendgrid = new SendGrid('', '', array("turn_off_ssl_verification" => true));
$emailS = new SendGrid\Email();
$emailS
    ->addTo($email)
    ->setFrom('cadastro@sugoigame.com.br')
    ->setSubject('Recuperação de senha - Sugoi Game')
    ->setHtml("Recueração de senha do Sugoi Game:<br/><br/>Acesse o link abaixo para continuar:<br/><br/>"
        . "<a href='$url' target='_blank'>$url</a>");

$sendgrid->send($emailS);

header("location:../../index.php?msg2=Um código de alteração foi enviado ao email informado. Siga as instruções do email para continuar com aa recuperação de senha");