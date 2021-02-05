<?php
include "../../sendgrid-php/sendgrid-php.php";
include "../../Funcoes/input_validations.php";

$sendgrid = new SendGrid('', '', array("turn_off_ssl_verification" => true));
$emailS = new SendGrid\Email();
$emailS
    ->addTo("ivan.i.n@hotmail.com")
    ->setFrom($_POST["email"])
    ->setSubject("Sugoi Game Suporte - " . htmlspecialchars($_POST["motivo"] . " - " . $_POST["assunto"], ENT_QUOTES))
    ->setHtml(htmlspecialchars($_POST["nome"], ENT_QUOTES) . ":<br/>\n" . htmlspecialchars($_POST["mensagem"], ENT_QUOTES));

$sendgrid->send($emailS);

echo("Mensagem enviada com sucesso!");
