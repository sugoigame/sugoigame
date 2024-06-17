<?php
require_once "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_tripulante_or_exit("cod");
$cod_skill = $protector->get_number_or_exit("cod_skill");

$exists = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil =", "ii", [
    $pers["cod"],
    $cod_skill
]);

if ($exists->count()) {
    $protector->exit_error("Você já possui essa habilidade");
}

$tb = get_skill_table($tipo_skill);

$skill = MapLoader::find($tb, ["cod_skil" => $cod_skill]);
if (! $skill) {
    $protector->exit_error("Habilidade inválida");
}

$rec_1 = nome_atributo_tabela($skill["requisito_atr_1"]);
$rec_2 = nome_atributo_tabela($skill["requisito_atr_2"]);
if ($pers["lvl"] < $skill["requisito_lvl"]
    || $pers["classe"] != $skill["requisito_classe"]
) {
    $protector->exit_error("Você não cumpre os requisitos para aprender essa habilidade");
}


$habilidade = habilidade_random();
$icon = $skill["icone_padrao"];

$skills_personagem = $connection->run(
    "SELECT * FROM tb_personagens_skil WHERE cod = ? AND (tipo = 1 OR tipo = 2 OR tipo = 3)",
    "i", $pers["cod"]
)->fetch_all_array();
foreach ($skills_personagem as $x => $outra_skill) {
    $outra_tb = get_skill_table($outra_skill["tipo"]);
    $outra_skill_details = MapLoader::find($outra_tb, ["cod_skil" => $outra_skill["cod_skil"]]);

    if ($outra_skill_details["requisito_lvl"] == $skill["requisito_lvl"]
        && $outra_skill_details["requisito_classe"] != 0
        && $outra_skill_details["maestria"] == 0) {

        $icon = $outra_skill["icon"];
        $connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?", "iii", [
            $pers["cod"],
            $outra_skill["cod_skil"],
            $outra_skill["tipo"]
        ]);
    }
}

$connection->run(
    "INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon) VALUE (?,?,?,?,?,?)", "iiissi", [
        $pers["cod"],
        $cod_skill,
        $tipo_skill,
        mb_strimwidth($habilidade["nome"], 0, 20),
        $habilidade["descricao"],
        $icon
    ]);

echo ":";
