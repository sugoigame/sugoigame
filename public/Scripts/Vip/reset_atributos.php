<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao", "free"));
if ($tipo == "free") {
    if (!$userDetails->tripulacao["free_reset_atributos"]) {
        $protector->exit_error("você não pode resetar seus atributos gratuitamente");
    }
} else {
    $protector->need_gold_or_dobrao($tipo, PRECO_GOLD_RESET_ATRIBUTOS, PRECO_DOBRAO_RESET_ATRIBUTOS);
}

$pers = $protector->get_tripulante_or_exit("cod");
$personagem = $pers["cod"];

$connection->run("INSERT INTO tb_resets (tipo, cod) VALUES ('1', '$personagem')");

$att    = (($pers["lvl"] - 1) * PONTOS_POR_NIVEL) + 69;
$hp     = (($pers["lvl"] - 1) * 100) + 2500;
$mp     = (($pers["lvl"] - 1) * 7) + 100;

$bonus = get_bonus_excelencia($pers["classe"], $pers["excelencia_lvl"]);

$hp += $bonus["hp_max"];
$mp += $bonus["mp_max"];

$connection->run("UPDATE tb_personagens 
	SET atk     = ?,
        def     = ?,
        agl     = ?,
        res     = ?,
        pre     = ?,
        dex     = ?,
        con     = ?,
        vit     = ?,
        pts     = ?,
        hp      = ?,
        hp_max  = ?,
        mp_max  = ?,
        mp      = ?
	WHERE cod = ?", "iiiiiiiiiiiiii", [
        1 + $bonus["atk"],
        1 + $bonus["def"],
        1 + $bonus["agl"],
        1 + $bonus["res"],
        1 + $bonus["pre"],
        1 + $bonus["dex"],
        1 + $bonus["con"],
        1 + $bonus["vit"],
        $att,
        $hp,
        $hp,
        $mp,
        $mp,
        $personagem
    ]
);

$userDetails->remove_skills_classe($pers);

if ($tipo == "free") {
    $connection->run("UPDATE tb_usuarios SET free_reset_atributos = free_reset_atributos - 1 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
} else {
    $userDetails->reduz_gold_or_dobrao($tipo, PRECO_GOLD_RESET_ATRIBUTOS, PRECO_DOBRAO_RESET_ATRIBUTOS, "resetar_atributos");
}

echo("-Atributos Resetados!");
