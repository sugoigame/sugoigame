<?php
include "../../Includes/conectdb.php";

if (!isset($_POST["nome"])
    OR !isset($_POST["email"]) || !validate_email($_POST["email"])
    OR !isset($_POST["senha2"])
) {
    header("location:../../?ses=cadastro&msg=Formulário incompleto.");
    exit();
}

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha2"];

if (isset($_POST["padrinho"])) {
    $padrinho = $_POST["padrinho"];
    if (!validate_alphanumeric($padrinho)) {
        header("location:../../?ses=cadastro&msg=Você informou algum caracter inválido.");
        exit();
    }
}

if (strlen($nome) < 5 || strlen($senha) < 5) {
    header("location:../../?ses=cadastro&msg=Algum valor informado nao atende os requisitos de tamanho.");
    exit();
}
$senha = password_hash($senha, PASSWORD_BCRYPT);

$result = $connection->run("SELECT email FROM tb_conta WHERE email= ?", "s", $email);

if ($result->count()) {
    header("location:../../?ses=cadastro&msg=O email informado já está cadastrado.");
    exit();
}

$id_encrip = md5(time());

$ativacao = substr(md5(time()), 8, 8);

$result = $connection->run(
    "INSERT INTO tb_conta (nome, id_encrip, email, senha, ativacao) 
            VALUES ( ?, ?, ?, ?, ?)",
    "sssss", array($nome, $id_encrip, $email, $senha, $ativacao)
);

$u_id = $result->last_id();

if (isset($_POST["padrinho"])) {
    $result = $connection->run("SELECT * FROM tb_conta WHERE id_encrip=?", "s", $padrinho);
    if ($result->count()) {
        $padrinho_info = $result->fetch_array();

        $connection->run("INSERT INTO tb_afilhados (id, afilhado) VALUES (?, ?)", "ss", array($padrinho_info["conta_id"], $u_id));
    }
}

$url = "http://www.sugoigame.com.br/Scripts/Geral/ativar_id.php?i=$u_id&cod=$ativacao";

require_once '../../Classes/PHPMailer.php';
$mail = new PHPMailer();
$mail->setFrom("cadastro@sugoigame.com.br", "Sugoi Game");
$mail->AddAddress($email, $inlognaam);
$mail->Subject = 'Sugoi Game - Criação de conta';
$mail->msgHTML('<div style="margin: 0 auto; background: #F5F5F5; border-radius: 5px; width: 520px; border: 1px dotted #D8D8D8; border-left: 4px solid #CE3233; border-right: 4px solid #CE3233;">
    <table width="100%" cellspacing="0" cellpadding="0" align="center">
        <tr><td><div style="padding: 1px 5px; font-size:12px; font-family: Arial, Helvetica, sans-serif;">
            Bem vindo ao Sugoi Game!<br /><br />
            Código de ativação: ' . $ativacao . '<br /><br />
            Ou se preferir acesse o linnk:<br /><br />
            <a href="' . $url . '" target="_blank">' . $url . '</a>
        </div></td></tr>
        <tr><td align="center"><div style="background: rgba(0, 0, 0, .5); margin-top: 10px; padding: 5px; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
            <b style="color: #FFF;">&copy; 2017 - ' . date("Y"). ' - Sugoi Game | Todos os direitos reservados.</b>
        </div></td></tr>
    </table>
</div>');
if($mail->Send()) {
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();

    $userDetails->set_authentication($u_id);
}
header("location:../../?ses=home");
exit;