<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_tripulante_or_exit("cod");
$cod_skill = $protector->get_number_or_exit("codskill");
$tipo_skill = $protector->get_enum_or_exit("tiposkill", array(TIPO_SKILL_ATAQUE_AKUMA, TIPO_SKILL_BUFF_AKUMA, TIPO_SKILL_PASSIVA_AKUMA));

$exists = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "iii", array($pers["cod"], $cod_skill, $tipo_skill));

if ($exists->count()) {
    $protector->exit_error("Você já possui essa habilidade");
}

$skill = MapLoader::find("skil_akuma", ["cod_akuma" => $pers["akuma"], "cod_skil" => $cod_skill]);

if (! $skill || $pers["lvl"] < $skill["requisito_lvl"]) {
    $protector->exit_error("Você não cumpre os requisitos para aprender essa habilidade");
}

$outras_skills = MapLoader::filter("skil_akuma", function ($s) use ($skill) {
    return $s["cod_akuma"] == $skill["cod_akuma"] && $s["requisito_lvl"] == $skill["requisito_lvl"];
});
$cods = [];
foreach ($outras_skills as $s) {
    $cods[] = $s["cod_skil"];
}
$connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND cod_skil IN (" . implode(",", $cods) . ") AND tipo IN (?,?,?)",
    "iiii", array($pers["cod"], TIPO_SKILL_ATAQUE_AKUMA, TIPO_SKILL_BUFF_AKUMA, TIPO_SKILL_PASSIVA_AKUMA));

$habilidade = habilidade_random();
$icon = rand(1, SKILLS_ICONS_MAX);

$connection->run("INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon) VALUE (?,?,?,?,?,?)",
    "iiissi", array($pers["cod"], $cod_skill, $tipo_skill, mb_strimwidth($habilidade["nome"], 0, 20), $habilidade["descricao"], $icon));

echo ":";
