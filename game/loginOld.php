<?php
$valida = "EquipeSugoiGame2012";
require "Includes/conectdb.php";

if ($userDetails->conta) {
    header("location:index.php");
    exit();
}

$facebook_url = "https://www.facebook.com/dialog/oauth?client_id=296635354108298&redirect_uri=https://sugoigame.com.br/Scripts/Geral/login_facebook.php&scope=email";

?>
<!DOCTYPE HTML>
<!--
	Hyperspace by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>Sugoi Game</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Não é apenas um jogo de navegador, é a jornada mais incrível da sua vida!">
    <meta property="og:url" content="https://sugoigame.com.br/login.php">
    <meta property="og:title" content="Sugoi Game. Jogue agora! É grátis!">
    <meta property="og:site_name" content="Sugoi Game">
    <meta property="og:description" content="A jornada mais incrível da sua vida!">
    <meta property="og:image" content="https://sugoigame.com.br/Imagens/Banners/home.jpg">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="fb:app_id" content="296635354108298">

    <!--[if lte IE 8]>
    <script src="login_assets/assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="login_assets/assets/css/lightbox.min.css"/>
    <link rel="stylesheet" href="login_assets/assets/css/main.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="login_assets/assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="login_assets/assets/css/ie8.css"/><![endif]-->

    <link rel="shortcut icon" href="Imagens/favicon.png" type="image/png"/>
</head>
<body>

<!-- Sidebar -->
<section id="sidebar">
    <div class="inner">
        <nav>
            <ul>
                <li><a href="#intro">Bem vindo</a></li>
                <li><a href="#zero">Comece a jogar</a></li>
                <li><a href="#one">Sobre o jogo</a></li>
                <li><a href="#two">Conteúdo</a></li>
                <li><a href="#three">Taticus Entertainment</a></li>
            </ul>
        </nav>
    </div>
</section>

<!-- Wrapper -->
<div id="wrapper">

    <!-- Intro -->
    <section id="intro" class="wrapper fullscreen fade-up">
        <div class="inner">
            <h1><img src="Imagens/logo.png" style="max-width: 100%;"></h1>
            <p>A jornada mais incrível da sua vida!</p>
            <ul class="actions">
                <li>
                    <a href="#one" class="button scrolly">Quero conhecer</a>
                    <a class="button bt-facebook" href="<?= $facebook_url ?>">
                        <i class="fa fa-facebook-square"></i> Entrar com Facebook
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <section id="zero" class="wrapper style2-alt fade-up">
        <div class="inner">
            <h2>Comece a jogar</h2>
            <form name="login" action="Scripts/Geral/logar.php" method="POST">
                <div class="row uniform">
                    <div class="u12">
                        Ainda não tem uma conta?
                        <a href="index.php?ses=cadastro" class="button special">
                            Cadastre-se grátis
                        </a>
                    </div>
                    <?php if (isset($_GET["erro"])) : ?>
                        <div class="u12">
                            <p class="msg-error">
                                Login e/ou senha inválidos.
                            </p>
                        </div>
                    <?php endif; ?>
                    <div class="u12">
                        <input type="email" name="login" placeholder="Email" required/>
                    </div>
                    <div class="u12">
                        <input type="password" name="senha" placeholder="Senha" required/>
                    </div>
                    <div class="u12">
                        <button type="submit" class="button special">
                            Jogar!
                        </button>
                    </div>
                    <div class="u12">
                        <a href="index.php?ses=recuperarSenha">Esqueci minha senha</a>
                    </div>
                    <div class="u12">
                        <a class="button bt-facebook" href="<?= $facebook_url ?>">
                            <i class="fa fa-facebook-square"></i> Entrar com Facebook
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- One -->
    <section id="one" class="wrapper style1 fade-up">
        <div class="inner">
            <h2>Sobre o jogo</h2>
            <p>
                E se de repente você se tornasse um pirata e embarcasse numa jornada incrível em busca do
                <strong>seu</strong> tesouro?
            </p>
            <p>
                Sugoi Game é um jogo de <strong>estratégia</strong> online, <strong>multiplayer</strong>, com uma
                <strong>profundidade</strong> instigante e ao mesmo tempo <strong>desafiadora</strong>. Feita pra quem
                gosta de <strong>quebrar a cabeça</strong> e se envolver na <strong>filosofia</strong> do jogo, onde os
                desafios são o cerne da jornada.
            </p>
            <p>
                Mergulhe de cabeça nessa aventura incrível, desvende os enigmas, ganhe reputação, encontre tesouros e
                especiarias raras, enfrente os mostros dos mares e fique cada vez mais poderoso.
            </p>
            <p>
                Nesse jogo misterioso e temático, tudo pode acontecer.
            </p>
            <p>
                Aqui não existe as melhores <strong>builds</strong>.
            </p>
            <p>
                Aqui <strong>não</strong> existe pagar para vencer.
            </p>
            <p>
                Aqui só existe o Rei de <strong>todos</strong> os Piratas.
            </p>
            <p>
                Deixe a jornada mais <strong>incrível</strong> da sua vida começar: <strong>Sugoi Game!</strong>
            </p>

            <ul class="actions">
                <li>
                    <a href="index.php?ses=cadastro" class="button special">
                        Quero me cadastrar!
                    </a>
                    <a class="button bt-facebook" href="<?= $facebook_url ?>">
                        <i class="fa fa-facebook-square"></i> Entrar com Facebook
                    </a>
                </li>
            </ul>
        </div>
    </section>

    <!-- Two -->
    <section id="two" class="wrapper style2 spotlights">
        <section>
            <a href="login_assets/images/pic01.jpg" class="image" data-lightbox="demo">
                <img src="login_assets/images/pic01.jpg" alt="" data-position="center center"/>
            </a>
            <div class="content">
                <div class="inner">
                    <h2>Um vasto oceano para explorar</h2>
                    <p>
                        <strong>Entre na pele</strong> de um marinheiro ou de um pirata e <strong>experimente</strong> o
                        desafio de estar em mar <strong>aberto</strong> e ter que usar a sua
                        <strong>criatividade</strong> e <strong>intuição</strong> para navegar pelo oceano.<br/>
                        Em meio a essa <strong>aventura</strong>, você pode achar <strong>Akumas No Mi</strong>,
                        capturar <strong>tesouros</strong> pelas ilhas, saquear outros <strong>jogadores</strong>, ser
                        surpreendido por terríveis <strong>monstros</strong> dos mares e muito mais!
                    </p>
                    <ul class="actions">
                        <li>
                            <a href="index.php?ses=cadastro" class="button special">
                                Quero me cadastrar!
                            </a>
                            <a class="button bt-facebook" href="<?= $facebook_url ?>">
                                <i class="fa fa-facebook-square"></i> Entrar com Facebook
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section>
            <a href="login_assets/images/pic02.jpg" class="image" data-lightbox="demo">
                <img src="login_assets/images/pic02.jpg" alt="" data-position="top center"/>
            </a>
            <div class="content">
                <div class="inner">
                    <h2>Criaturas para derrotar</h2>
                    <p>
                        Para chegar ao final do caminho que escolheu percorrer, monstros marinhos
                        <strong>famintos</strong> podem te atacar a qualquer instante. Esteja preparado!
                    </p>
                    <ul class="actions">
                        <li>
                            <a href="index.php?ses=cadastro" class="button special">
                                Quero me cadastrar!
                            </a>
                            <a class="button bt-facebook" href="<?= $facebook_url ?>">
                                <i class="fa fa-facebook-square"></i> Entrar com Facebook
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section>
            <a href="login_assets/images/pic03.jpg" class="image" data-lightbox="demo">
                <img src="login_assets/images/pic03.jpg" alt="" data-position="25% 25%"/>
            </a>
            <div class="content">
                <div class="inner">
                    <h2>Batalhas a vencer</h2>
                    <p>
                        Sua cabeça <strong>vale muito</strong> dependendo da sua performance em jogo contra seus
                        <strong> adversários</strong>. É natural que muitos queiram te derrotar quanto mais <strong>poderoso</strong>
                        você ficar. Mostre que <strong>você</strong> é mais <strong>ardiloso</strong>, <strong>inteligente</strong>
                        e <strong>poderoso</strong> do que seu adversário. E lembre-se: embora seja possível viver em
                        aliança, é <strong>cada um por si</strong>. E a qualquer momento, pode aparecer alguém para te
                        <strong>ajudar</strong> ou para tirar mais <strong>vantagem</strong> em cima de você...
                    </p>
                    <ul class="actions">
                        <li>
                            <a href="index.php?ses=cadastro" class="button special">
                                Quero me cadastrar!
                            </a>
                            <a class="button bt-facebook" href="<?= $facebook_url ?>">
                                <i class="fa fa-facebook-square"></i> Entrar com Facebook
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </section>

    <!-- Three -->
    <section id="three" class="wrapper style3 fade-up">
        <div class="inner">
            <h3>Powered by:</h3>
            <br/>
            <p class="align-center">
                <img src="Imagens/taticus.png" style="max-width: 100%">
            </p>
            <br/>
        </div>
    </section>

</div>

<!-- Footer -->
<footer id="footer" class="wrapper style3-alt">
    <div class="inner">
        <ul class="menu">
            <li>&copy; Sugoi Game. All rights reserved.</li>
            <li>Design: <a href="http://html5up.net" target="_blank">HTML5 UP</a></li>
        </ul>
    </div>
</footer>

<!-- Scripts -->
<script src="login_assets/assets/js/jquery.min.js"></script>
<script src="login_assets/assets/js/jquery.scrollex.min.js"></script>
<script src="login_assets/assets/js/jquery.scrolly.min.js"></script>
<script src="login_assets/assets/js/lightbox.min.js"></script>
<script src="login_assets/assets/js/skel.min.js"></script>
<script src="login_assets/assets/js/util.js"></script>
<!--[if lte IE 8]>
<script src="login_assets/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="login_assets/assets/js/main.js"></script>
<?php if (isset($_GET["erro"])) : ?>
    <script>
        location.href = '#zero';
    </script>
<?php endif; ?>
<!-- Hotjar Tracking Code for www.sugoigame.com.br -->
<script>
    (function (h, o, t, j, a, r) {
        h.hj = h.hj || function () {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
        h._hjSettings = {hjid: 539774, hjsv: 5};
        a = o.getElementsByTagName('head')[0];
        r = o.createElement('script');
        r.async = 1;
        r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
        a.appendChild(r);
    })(window, document, '//static.hotjar.com/c/hotjar-', '.js?sv=');
</script>
</body>
</html>