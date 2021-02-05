<?php
if ($_SERVER["HTTP_HOST"] == "localhost") {
    require_once('Constants.dev.php');
} else {
    require_once('Constants.prod.php');
}
require_once('mywrap_result.php');
require_once('mywrap_connection.php');