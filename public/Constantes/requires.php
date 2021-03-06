<?php
$env = 'dev';
if (in_array($_SERVER['HTTP_HOST'], ['sugoigame.com.br', 'map.sugoigame.com.br']))
    $env = 'prod';

require_once(dirname(__FILE__) . '/configs.' . $env . '.php');
require_once(dirname(__FILE__) . "/classes.php");
require_once(dirname(__FILE__) . "/combate.php");
require_once(dirname(__FILE__) . "/haki.php");
require_once(dirname(__FILE__) . "/ilhas.php");
require_once(dirname(__FILE__) . "/itens.php");
require_once(dirname(__FILE__) . "/karma.php");
require_once(dirname(__FILE__) . "/missoes.php");
require_once(dirname(__FILE__) . "/navio.php");
require_once(dirname(__FILE__) . "/obstaculos.php");
require_once(dirname(__FILE__) . "/preco_chefao.php");
require_once(dirname(__FILE__) . "/profissoes.php");
require_once(dirname(__FILE__) . "/skills.php");
require_once(dirname(__FILE__) . "/variaveis_globais.php");
require_once(dirname(__FILE__) . "/vip.php");