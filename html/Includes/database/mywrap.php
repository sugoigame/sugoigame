<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["HTTP_HOST"] == "localhost") {
    require_once('Constants.dev.php');
} else {
    require_once('Constants.prod.php');
}
require_once('mywrap_result.php');
require_once('mywrap_connection.php');