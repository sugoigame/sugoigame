<?php
function has_chance($val) {
	$rnd = rand(0, 400) / 4;
	return $rnd <= $val ? true : false;
}

function get_chance() {
	return rand(0, 400) / 4;
}

function formulaExp($nivel = 1) {
	return (500 * $nivel);
}