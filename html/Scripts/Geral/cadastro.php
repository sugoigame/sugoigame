<?php
include "../../Includes/conectdb.php";
include "../../sendgrid-php/sendgrid-php.php";

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

/*$url = "http://www.sugoigame.com.br/Scripts/Geral/ativar_id.php?i=$u_id&cod=$ativacao";

$sendgrid = new SendGrid('', '', array("turn_off_ssl_verification" => true));
$emailS = new SendGrid\Email();
$emailS
    ->addTo($email)
    ->setFrom('cadastro@sugoigame.com.br')
    ->setSubject('Criação de conta - Sugoi Game')
    ->setHtml('Bem vindo ao Sugoi Game!<br/><br/>Código de ativação: ' . $ativacao
        . '<br/><br/>Ou se preferir acesse o linnk:<br/><br/>'
        . "<a href=\"$url\" target=\"_blank\">$url</a>");

$sendgrid->send($emailS);*/


// $userDetails->set_authentication($u_id);

header("location:../../?ses=home");
// header("location:../../?ses=seltrip");
