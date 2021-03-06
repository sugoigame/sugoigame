<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

$protector->reject_conta();

$protector->redirect_alphanumeric_invalid($_POST["token"]);

$token = $_POST["token"];
$senha = $_POST["senha"];

$result = $connection->run(
    "SELECT 
    tb_conta.conta_id AS conta_id, 
    tb_conta.email AS email
    FROM tb_reset_senha_token 
    INNER JOIN tb_conta ON tb_reset_senha_token.conta_id = tb_conta.conta_id 
    WHERE tb_reset_senha_token.token = ?",
    "s", $token
);

if (!$result->count()) {
    $protector->redirect_error("Token invalido informado");
}

$conta = $result->fetch_array();

$connection->run(
    "UPDATE tb_conta SET senha = ? WHERE conta_id = ?",
    "si", array(password_hash($senha, PASSWORD_BCRYPT), $conta["conta_id"])
);

$connection->run("DELETE FROM tb_reset_senha_token WHERE conta_id = ?", "i", $conta["conta_id"]);

require_once '../../Classes/PHPMailer.php';
$mail = new PHPMailer();
$mail->setFrom("cadastro@sugoigame.com.br", "Sugoi Game");
$mail->AddAddress($email, $inlognaam);
$mail->Subject = 'Sugoi Game - Recuperação de senha';
$mail->msgHTML('<div style="margin: 0 auto; background: #F5F5F5; border-radius: 5px; width: 520px; border: 1px dotted #D8D8D8; border-left: 4px solid #CE3233; border-right: 4px solid #CE3233;">
    <table width="100%" cellspacing="0" cellpadding="0" align="center">
        <tr><td><div style="padding: 1px 5px; font-size:12px; font-family: Arial, Helvetica, sans-serif;">
            Sua senha foi alterada!<br /><br />
            https://sugoigame.com.br/
        </div></td></tr>
        <tr><td align="center"><div style="background: rgba(0, 0, 0, .5); margin-top: 10px; padding: 5px; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
            <b style="color: #FFF;">&copy; 2017 - ' . date("Y"). ' - Sugoi Game | Todos os direitos reservados.</b>
        </div></td></tr>
    </table>
</div>');
if($mail->Send()) {
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
}

header("location:../../?msg2=Senha alterada! Efetue login para começar a jogar!");
exit;