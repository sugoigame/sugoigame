<?php
require "Includes/conectdb.php";

if ($userDetails->conta) {
	header("location: ./");
	exit();
}

$facebook_url = "https://www.facebook.com/dialog/oauth?client_id=4195656893810914&redirect_uri=https://sugoigame.com.br/Scripts/Geral/login_facebook.php&scope=email";

$banners = array(
	"1" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Muitos desafios e recompensas estão esperando! E é claro MUITO PVP! Jogue agora!"
	),
	"2" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Muitos desafios e recompensas estão esperando! E é claro MUITO PVP! Jogue agora!"
	),
	"3" => array(
		"title" => "Batalhas PVP! Muitas Quests! Recompensas e Tesouros grandiosos!",
		"description" => "Jogue agora mesmo o melhor rpg de One Piece! Feito por fãs para fãs!"
	),
	"4" => array(
		"title" => "Batalhas PVP! Muitas Quests! Recompensas e Tesouros grandiosos!",
		"description" => "Jogue agora mesmo o melhor rpg de One Piece! Feito por fãs para fãs!"
	),
	"5" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Jogue agora mesmo o melhor rpg de One Piece! Feito por fãs para fãs!"
	),
	"6" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Jogue agora mesmo o melhor rpg de One Piece! Feito por fãs para fãs!"
	),
	"7" => array(
		"title" => "Batalhas PVP! Recompensas e Tesouros grandiosos!",
		"description" => "Muitos desafios e recompensas estão esperando! E é claro MUITO PVP! Jogue agora!"
	),
	"8" => array(
		"title" => "Batalhas PVP! Recompensas e Tesouros grandiosos!",
		"description" => "Muitos desafios e recompensas estão esperando! E é claro MUITO PVP! Jogue agora!"
	),
	"9" => array(
		"title" => "Batalhas PVP! Recompensas e Tesouros grandiosos!",
		"description" => "Muitos desafios e recompensas estão esperando! E é claro MUITO PVP! Jogue agora!"
	),
	"10" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Muitos desafios e recompensas estão esperando! E é claro MUITO PVP! Jogue agora!"
	),
	"11" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Jogue agora mesmo o melhor rpg de One Piece! Feito por fãs para fãs!"
	),
	"12" => array(
		"title" => "A maior aventura de One Piece que vocês já viram!",
		"description" => "Jogue agora mesmo o melhor rpg de One Piece! Feito por fãs para fãs!"
	)
);

$banner_id = isset($_GET["b"]) ? $_GET["b"] : null;
if (!isset($banners[$banner_id])) {
	$banner_id = null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Sugoi Game - One Piece MMORPG</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<meta name="description" content="Sugoi Game é um MMORPG estratégico gratuito cheio de PvP feito por fãs de One Piece. É um jogo de mundo aberto com batalhas em turnos e muito PvP. Jogue agora!">
	<meta name="keywords" content="one piece online,one piece games,one piece online game,one piece game online,one piece rpg,one piece pirate warriors,one piece mmorpg,tower defense games,free tower defense games,one piece browser game,new one piece game,one piece mundo aberto">

	<meta property="og:url" content="https://sugoigame.com.br/login.php<?= $banner_id ? "?b=" . $banner_id : "" ?>" />
	<meta property="og:site_name" content="One Piece MMORPG - Sugoi Game" />
	<meta property="og:type" content="website" />
	<?php if ($banner_id): ?>
		<meta property="og:image" content="https://sugoigame.com.br/Imagens/Banners/<?= $banner_id ?>.png" />
		<meta property="og:title" content="<?= $banners[$banner_id]["title"] ?>" />
		<meta property="og:description" content="<?= $banners[$banner_id]["description"] ?>" />
		<meta property="og:image:height" content="920" />
		<meta property="og:image:type" content="image/png" />
	<?php else: ?>
		<meta property="og:title" content="A maior aventura de One Piece que você já viu!" />
		<meta property="og:description" content="Batalhas PVP! Recompensas e Tesouros grandiosos! Jogue agora!" />
		<meta property="og:image" content="https://sugoigame.com.br/Imagens/Banners/banner.jpg" />
		<meta property="og:image:type" content="image/jpeg" />
	<?php endif; ?>
	<meta property="fb:app_id" content="444646756906612" />

	<link rel="manifest" href="manifest.json" />
	<link rel="shortcut icon" type="image/png" href="Imagens/favicon.png" />

	<!--[if lte IE 8]>
		<script src="Login/assets/js/ie/html5shiv.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="Login/assets/css/lightbox.min.css" />
	<link rel="stylesheet" href="CSS/bootstrap.min.css" />
	<link rel="stylesheet" href="Login/assets/css/main.css" />
	<!--[if lte IE 8]>
		<link rel="stylesheet" href="Login/assets/css/ie8.css" />
	<![endif]-->
	<!--[if lte IE 9]>
		<link rel="stylesheet" href="Login/assets/css/ie9.css" />
	<![endif]-->
	<script type="text/javascript">
		if (window.location.hostname == 'sugoigame.com.br') {
			if (!navigator.serviceWorker.controller) {
				navigator.serviceWorker.register("/sw.js").then(function(reg) {
					console.log("Service worker has been registered for scope: " + reg.scope);
				});
			}
		}
	</script>
	<?php if ($_SERVER['HTTP_HOST'] == 'sugoigame.com.br') { ?>
	<script data-ad-client="ca-pub-6665062829379662" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<?php } ?>
</head>
<body>
<div class="wrapper">
	<!-- Header -->
	<header id="header">
		<h1><img src="Imagens/logo.png"/></h1>
		<p class="text-justify">Um MMORPG estratégico gratuito cheio de PvP feito por fãs de One Piece.<br />
			É um jogo de mundo aberto com batalhas em turnos e muito PvP.</p>
	</header>

	<!-- Signup Form -->
	<div id="signup-form">
		<a class="button" id="jogar-button" data-toggle="modal" href="#modal-login">Comece a jogar!</a>
		<!--<a class="button bt-facebook" href="<?= $facebook_url ?>">
			<i class="fa fa-facebook-square fa-fw"></i>
			Entrar com Facebook
		</a>-->
	</div>
</div>

<div id="content" class="hidden-sm hidden-xs">
	<h2>A jornada mais incrível da sua vida!</h2>
	<p class="text-justify">
		Sugoi Game é um jogo de <strong>estratégia</strong> online, <strong>multiplayer</strong>, com uma
		<strong>profundidade</strong> instigante e ao mesmo tempo <strong>desafiadora</strong>.<br />
		Lute contra <strong>outros jogadores</strong> para <strong>provar suas habilidades</strong> em uma
		<strong>competição</strong> cheia de <strong>fortes emoções</strong>.<br />
		Os títulos de <strong>Rei dos Piratas</strong> e <strong>Almirante de Frota</strong> são os cargos mais altos
		reservados apenas para os <strong>melhores jogadores</strong>!
	</p>

	<h4>Apresentação</h4>
	<div class="embed-responsive embed-responsive-16by9">
		<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/DKpJXAFIYY4" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
	</div>
</div>

<!-- Footer -->
<footer id="footer">
	<ul class="copyright">
		<li>&copy; 2017 - <?=date('Y');?> <b>Sugoi Game</b></li>
		<li class="hidden-xs"><a href="./?ses=politica" class="link_content">Política de Privacidade</a></li>
		<li class="hidden-xs"><a href="./?ses=regras" class="link_content">Regras e Punições</a><br/></li>
	</ul>
</footer>
<div id="modal-login" class="modal fade">
	<div class="modal-dialog modal-login">
		<div class="modal-content">
			<div class="modal-header">
				<div class="avatar">
					<img src="https://www.tutorialrepublic.com/examples/images/avatar.png" alt="Avatar">
				</div>				
				<h4 class="modal-title">Acessar minha conta</h4>	
                <span href="javascript:void(0);" class="close" data-dismiss="modal" aria-hidden="true">&times;</span>
			</div>
			<div class="modal-body">
				<?php
				if (isset($_GET["erro"])) {
					switch ($_GET["erro"]) {
						case '1':
							$msg = 'Login e/ou senha inválidos.';
							break;
						case '2':
							$msg = 'Você não participa do beta.';
							break;
						default:
							$msg = 'Falha ao fazer login.';
					}
					echo "<div class='alert alert-danger text-center'>{$msg}</div>";
				}
				?>
				<form name="login" action="Scripts/Geral/logar.php" method="post" style="margin-bottom: 0;">
					<div class="form-group">
						<input type="email" class="form-control" name="login" placeholder="E-mail" required />
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="senha" placeholder="Senha" required />
						<div class="text-right">
							<a href="./?ses=recuperarSenha" class="recSenha">Esqueci minha senha?</a>
						</div>
					</div>        
					<div class="form-group">
						<button type="submit" class="btn btn-danger btn-lg btn-block login-btns">Acessar</button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<a href="./?ses=cadastro" class="irCadastro">Cadastre-se agora!</a>
			</div>
		</div>
	</div>
</div>

<!-- Scripts -->
<script src="JS/jquery-2.2.2.min.js"></script>
<script src="Login/assets/js/lightbox.min.js"></script>
<script src="JS/bootstrap.min.js"></script>
<!--[if lte IE 8]>
	<script src="Login/assets/js/ie/respond.min.js"></script>
<![endif]-->
<script src="Login/assets/js/main.js"></script>

<?php if (isset($_GET["erro"])) : ?>
	<script type="text/javascript">
		$('#modal-login').modal('show');
	</script>
<?php endif; ?>
</body>
</html>