<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

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

$url = "http://www.sugoigame.com.br/?ses=recuperarSenha&token=$token";

require_once '../../Classes/PHPMailer.php';
$mail = new PHPMailer();
$mail->setFrom("cadastro@sugoigame.com.br", "Sugoi Game");
$mail->AddAddress($email, $inlognaam);
$mail->Subject = 'Sugoi Game - Recuperação de senha';
$mail->msgHTML('<div style="margin: 0 auto; background: #F5F5F5; border-radius: 5px; width: 520px; border: 1px dotted #D8D8D8; border-left: 4px solid #CE3233; border-right: 4px solid #CE3233;">
    <table width="100%" cellspacing="0" cellpadding="0" align="center">
        <tr><td><div style="padding: 1px 5px; font-size:12px; font-family: Arial, Helvetica, sans-serif;">
            Recueração de senha do Sugoi Game:<br /><br />
            Acesse o link abaixo para continuar:<br /><br />
            <a href="' . $url . '" target="_blank">' . $url . '</a>
        </div></td></tr>
        <tr><td align="center"><div style="background: rgba(0, 0, 0, .5); margin-top: 10px; padding: 5px; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
            <b style="color: #FFF;">&copy; 2017 - ' . date("Y"). ' - Sugoi Game | Todos os direitos reservados.</b>
        </div></td></tr>
    </table>
</div>');
if ($mail->Send()) {
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
}

header("location:../../?msg2=Um código de alteração foi enviado ao email informado. Siga as instruções do email para continuar com aa recuperação de senha");
exit;