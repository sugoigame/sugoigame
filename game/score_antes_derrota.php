<?php
$x = $_GET["s"];

echo (2 * $x + pow(pow(2 * $x + 4, 2) - 4 * $x * $x, 1 / 2)) / 2;