<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

$env = 'dev';
if (in_array($_SERVER['HTTP_HOST'], ['sugoigame.com.br', 'map.sugoigame.com.br']))
    $env = 'prod';

require_once(__DIR__ . '/../../Constantes/configs.' . $env . '.php');
require_once('mywrap_result.php');
require_once('mywrap_connection.php');