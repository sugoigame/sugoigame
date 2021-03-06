<?php
include "../../Includes/conectdb.php";
if ($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_GET['code'])) {
    header("Location: ../../?ses=home&msg=" . urlencode('Requisão inválida!') . "&");
    exit;
}

$appId          = '444646756906612';
$appSecret      = '2b12e1f9b153386e6a0c7b18f09dca0d';

// URL informada no campo "Site URL"
$redirectUri    = urlencode('https://sugoigame.com.br/Scripts/Geral/login_facebook.php');

$code           = $_GET['code'];
$token_url      = "https://graph.facebook.com/oauth/access_token?client_id={$appId}&redirect_uri={$redirectUri}&client_secret={$appSecret}&code={$code}";

$response       = @file_get_contents($token_url);
if (!$response) {
    header("Location: ../../?ses=home&msg=" . urlencode('Falha ao conectar com o facebook!') . "&");
    exit;
}

$params         = json_decode($response, true);
if (!isset($params['access_token']) || !$params['access_token']) {
    header("Location: ../../?ses=home&msg=" . urlencode('Invalid access token!') . "&");
    exit;
}

$graph_url      = "https://graph.facebook.com/me?fields=name,email&access_token=" . $params['access_token'];
$user           = json_decode(file_get_contents($graph_url));

// Nesse IF verificamos se veio os dados corretamente
if (!isset($user->email) || !$user->email) {
    header("Location: ../../?ses=home&msg=" . urlencode('O Facebook não forneceu seu email!') . "&");
    exit;
}

$result = $connection->run("SELECT * FROM tb_conta WHERE email = ?", "s", [$user->email]);
if (!$result->count()) {
    $id_encrip = md5(time());
    $connection->run(
        "INSERT INTO tb_conta (nome, id_encrip, email, senha, ativacao, dobroes, fbid) VALUES (?, ?, ?, '', NULL, 600, ?)",
        "sssi", [$user->name, $id_encrip, $user->email, $user->id]
    );
    $result = $connection->run("SELECT * FROM tb_conta WHERE email = ?", "s", [$user->email]);
}
$conta = $result->fetch_array();

/*$allowed = [
    'contato@fmedeiros.com.br',
    'nelis@bct.ect.ufrn.br',
    'lucasalexandresampaioferreira@hotmail.com',
    'luizxxi@live.com',
    'adrieloliveira-pessoal@hotmail.com',
    'washingtonrodjf@gmail.com',
    'ivan.i.n@hotmail.com',

    'matheus1298@gmail.com',
    'raphaelribeirocaetano@gmail.com',
    'adrielfaria2020@hotmail.com',
    'leu_wp@hotmail.com',
    'murilo.original2012@hotmail.com',
    'marcosnoberto10000@hotmail.com',
    'oadailton69@gmail.com',
    'biludadores72@hotmail.com',
    'gabriekcarmo@gmail.com',
    'vctorkauedog@hotmail.com',
    'gui_lima.silva10@outlook.com',
    'matheusluffy16@gmail.com',
    'gustavo_ama-vc@hotmail.com',
    'kakachivssaske@hotmail.com',
    'iasmynbco@hotmail.com',
    'victorkaue1998@hotmail.com',
    'lucas199616@outlook.com',
    'wendell_yellow@hotmail.com',
    'bito-kun@hotmail.com',
    'kaelkatatal24@hotmail.com',
    'pele3885@hotmail.com',
    'igorbarrosdesouza1@gmail.com',
    'wwebrbr@outlook.com',
    'rolim.psy2@gmail.com',
    'guilherme1feel@gmail.com',
    'leonardoa.maseo@gmail.com',
    'arthurfinarde@gmail.com',
    'atavares@my.com',
    'gustavobh-27@hotmail.com',
    'andersonandradeander@gmail.com',
    'brendoeragon_2010@hotmail.com',
    'guilherme.combr@hotmail.com',
    'williansilva5015@gmail.com',
    'wsil7@hotmail.com',
    'jeanjoao8520@gmail.com',
    'matheusluffy16@gmail.com',
    'ivan.junior99@hotmail.com',
    'tonygomezrs@gmail.com'
];
if (!in_array($user->email, $allowed))
{
    header("Location: ../../?ses=home&msg=" . urlencode('Você não possui permissão!') . "&");
    exit;
}*/

$id     = $conta["conta_id"];
$cookie = md5(uniqid(time()));

$_SESSION["sg_c"] = $id;
$_SESSION["sg_k"] = $cookie;

setcookie("chat",   "0",        time() + 80000, '/', FALSE, TRUE);
setcookie("sg_c",   $id,        time() + 80000, '/', FALSE, TRUE);
setcookie("sg_k",   $cookie,    time() + 80000, '/', FALSE, TRUE);

// Atualiza o cookie do bd
$connection->run("UPDATE tb_conta SET cookie = ?, ativacao = NULL WHERE conta_id = ?", "si", [$cookie, $id]);

// Redireciona
if ($conta["tripulacao_id"])
    header("Location: ../../?ses=home");
else
    header("Location: ../../?ses=seltrip");