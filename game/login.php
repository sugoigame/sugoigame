<?php
require "Includes/conectdb.php";

if ($userDetails->conta) {
    header("location:index.php");
    exit();
}

$facebook_url = "https://www.facebook.com/dialog/oauth?client_id=296635354108298&redirect_uri=https://sugoigame.com.br/Scripts/Geral/login_facebook.php&scope=email";

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

<!DOCTYPE HTML>
<!--
	Eventually by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>Sugoi Game - One Piece RPG - Crie sua própria história e viva novas aventuras!</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <meta name="description"
          content="Sugoi Game é um MMORPG estratégico gratuito cheio de PvP feito por fãs de One Piece. É um jogo de mundo aberto com batalhas em turnos e muito PvP. Jogue agora!">
    <meta name="keywords"
          content="one piece online,one piece games,one piece online game,one piece game online,one piece rpg,one piece pirate warriors,one piece mmorpg,tower defense games,free tower defense games,one piece browser game,new one piece game,one piece mundo aberto">
    <meta property="og:url" content="https://sugoigame.com.br/login.php<?= $banner_id ? "?b=" . $banner_id : "" ?>">
    <meta property="og:site_name" content="One Piece MMORPG - Sugoi Game">
    <meta property="og:type" content="website"/>
    <?php if ($banner_id): ?>
        <meta property="og:image" content="https://sugoigame.com.br/Imagens/Banners/<?= $banner_id ?>.png">
        <meta property="og:title" content="<?= $banners[$banner_id]["title"] ?>">
        <meta property="og:description" content="<?= $banners[$banner_id]["description"] ?>">
        <meta property="og:image:height" content="920"/>
        <meta property="og:image:type" content="image/png"/>
    <?php else: ?>
        <meta property="og:title" content="A maior aventura de One Piece que você já viu!">
        <meta property="og:description"
              content="Batalhas PVP! Recompensas e Tesouros grandiosos! Jogue agora!">
        <meta property="og:image" content="https://sugoigame.com.br/Imagens/Banners/banner.jpg">
        <meta property="og:image:type" content="image/jpeg">
    <?php endif; ?>
    <meta property="fb:app_id" content="296635354108298">

    <!--[if lte IE 8]>
    <script src="Login/assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="Login/assets/css/lightbox.min.css"/>
    <link rel="stylesheet" href="CSS/bootstrap.min.css"/>
    <link rel="stylesheet" href="Login/assets/css/main.css"/>
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="Login/assets/css/ie8.css"/><![endif]-->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="Login/assets/css/ie9.css"/><![endif]-->

    <link rel="shortcut icon" href="Imagens/favicon.png" type="image/png"/>
</head>
<script type="text/javascript">
    if (location.host.startsWith('www.')) {
        location.href = 'https:' + window.location.href.substring(window.location.protocol.length).replace('www.', '');
    }
    if (location.host !== 'localhost' && location.protocol !== 'https:') {
        location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
    }
</script>
<body>
<div class="wrapper">
    <!-- Header -->
    <header id="header">
        <h1><img src="Imagens/logo.png"/></h1>
        <p>Um MMORPG estratégico cheio de PvP feito por fãs de One Piece.</p>
    </header>

    <!-- Signup Form -->
    <div id="signup-form">
        <a class="button" id="jogar-button" data-toggle="modal" href="#modal-login">Jogar!</a>
        <a class="button bt-facebook" href="<?= $facebook_url ?>">
            <i class="fa fa-facebook-square"></i> Entrar com Facebook
        </a>
    </div>
</div>

<div id="content">
    <h2>A jornada mais incrível da sua vida!</h2>
    <p>
        Sugoi Game é um jogo de <strong>estratégia</strong> online, <strong>multiplayer</strong>, com uma
        <strong>profundidade</strong> instigante e ao mesmo tempo <strong>desafiadora</strong>.
    </p>
    <p>
        Lute contra <strong>outros jogadores</strong> para <strong>provar suas habilidades</strong> em uma
        <strong>competição</strong> cheia de <strong>fortes emoções</strong>.
    </p>
    <p>
        Os títulos de <strong>Rei dos Piratas</strong> e <strong>Almirante de Frota</strong> são os cargos mais altos
        reservados apenas para os <strong>melhores jogadores</strong>!
    </p>

    <h4>Vídeos</h4>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/DKpJXAFIYY4" frameborder="0"
                gesture="media" allow="encrypted-media" allowfullscreen></iframe>
    </div>

    <h3>Screenshots</h3>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"
         title="Clique para ampliar">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php for ($x = 1; $x <= 12; $x++): ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?= $x - 1 ?>"
                    class="<?= $x == 1 ? "active" : "" ?>">
                </li>
            <?php endfor; ?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?php for ($x = 1; $x <= 12; $x++): ?>
                <div class="item <?= $x == 1 ? "active" : "" ?>">
                    <a href="Login/images/pic<?= str_pad($x, 2, "0", STR_PAD_LEFT) ?>.jpg" class="image"
                       data-lightbox="demo">
                        <img src="Login/images/pic<?= str_pad($x, 2, "0", STR_PAD_LEFT) ?>.jpg" alt="" height="80"
                             data-position="25% 25%"/>
                    </a>
                </div>
            <?php endfor; ?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<!-- Footer -->
<footer id="footer">
    <ul class="icons">
        <li>
            <a href="https://www.facebook.com/sugoigamebr/" class="icon fa-facebook" target="_blank">
                <span class="label">Facebook</span>
            </a>
        </li>
    </ul>
    <ul class="copyright">
        <li>&copy; Sugoi Game - Taticus Entertainment.</li>
    </ul>
</footer>

<div class="modal fade" id="modal-login">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></a>
            </div>
            <div class="modal-body text-center">
                <div>Ainda não tem uma conta?</div>
                <a id="button-cadastrar" href="index.php?ses=cadastro" class="btn btn-danger btn-lg">
                    Cadastre-se grátis!
                </a>
            </div>
            <form name="login" action="Scripts/Geral/logar.php" method="POST">
                <div class="modal-body">
                    <?php if (isset($_GET["erro"])) : ?>
                        <div class="row">
                            <div class="alert alert-danger" role="alert">
                                Login e/ou senha inválidos.
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input class="form-control" type="email" name="login" placeholder="Email" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input class="form-control" type="password" name="senha" placeholder="Senha" required/>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger btn-lg">
                            Jogar!
                        </button>
                    </div>
                    <div class="text-center">
                        <a href="index.php?ses=recuperarSenha">Esqueci minha senha</a>
                    </div>

                </div>
                <div class="modal-body text-center">
                    <a class="button bt-facebook" href="<?= $facebook_url ?>">
                        <i class="fa fa-facebook-square"></i> Entrar com Facebook
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="JS/jquery-2.2.2.min.js"></script>
<script src="Login/assets/js/lightbox.min.js"></script>
<script src="JS/bootstrap.min.js"></script>
<!--[if lte IE 8]>
<script src="Login/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="Login/assets/js/main.js"></script>

<?php if (isset($_GET["erro"])) : ?>
    <script type="text/javascript">
        $('#modal-login').modal('show');
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
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-64012297-1', 'auto');

    ga('set', 'page', '/login');
    ga('send', 'pageview');
</script>

<!-- Facebook Pixel Code -->
<script>
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '139472736666925');
    fbq('track', 'PageView');

    $('.bt-facebook').on('click', function () {
        fbq('track', 'FacebookLogin');
    });
</script>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=139472736666925&ev=PageView&noscript=1"/></noscript>
<!-- End Facebook Pixel Code -->

</body>
</html>