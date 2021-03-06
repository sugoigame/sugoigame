<?php
/**
 * Created by PhpStorm.
 * User: Luiz Eduardo
 * Date: 01/07/2017
 * Time: 12:32
 */

require('../../Includes/conectdb.php');

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_rota();
$protector->must_be_in_ilha();

$quant = $protector->post_number_or_exit('quant');
$quant = $quant < 1 ? 1 : $quant;

$treinosRealizados = $connection->run("SELECT * FROM tb_haki_treino WHERE tripulacao_id = ?", "i", [
    $userDetails->tripulacao["id"]
])->count();

$treinosLimite = $userDetails->tripulacao['treinos_haki_disponiveis'] - $treinosRealizados;

if ($treinosLimite < $quant || $quant > 6) {
    $protector->exit_error('Quantidade de treinos inválida');
}

$preco_unitario = PRECO_TREINO_HAKI;
$preco = $quant * $preco_unitario;

$protector->need_berries($preco);

$connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", array($userDetails->tripulacao["id"]));
$connection->run("DELETE FROM tb_mapa_contem WHERE id = ?", "i", array($userDetails->tripulacao["id"]));

$result = $connection->run(
    "INSERT INTO tb_combate_bot (tripulacao_id, tripulacao_inimiga, faccao_inimiga, bandeira_inimiga, battle_back, haki) VALUE(?, ?, ?, ?, ?, ?)",
    "isisii", array($userDetails->tripulacao["id"], "Tripulação Bot", FACCAO_PIRATA, "010113046758010128123542020122074820", $userDetails->ilha['ilha'] ? 21 : 35, $quant)
);

$id = $result->last_id();

$tabuleiro = [];
$enemy_count = 0;
foreach ($userDetails->personagens as $pers) {
    do {
        $x = rand(0, 4);
        $y = rand(0, 19);
    } while (isset($tabuleiro[$x]) && isset($tabuleiro[$x][$y]));
    $tabuleiro[$x][$y] = true;
    $skin = rand(0, 7);
    $connection->run(
        "INSERT INTO tb_combate_personagens_bot 
          (combate_bot_id, nome, lvl, img, skin_r, skin_c, hp, hp_max, mp, mp_max, atk, def, agl, res, pre, dex, con, vit, quadro_x, quadro_y, haki_esq, haki_cri, haki_blo, titulo, classe) VALUE 
          (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "isiiiiiiiiiiiiiiiiiiiiisi", array(
            $id, "Rayleigh", $pers["lvl"], 57, $skin, $skin, $pers["hp_max"], $pers["hp_max"],
            $pers["mp_max"], $pers["mp_max"], $pers["atk"], $pers["def"], $pers["agl"], $pers["res"], $pers["pre"], $pers["dex"], $pers["con"], $pers["vit"],
            $x, $y, $pers["haki_esq"], $pers["haki_cri"], $pers["haki_blo"], "A estratégia de " . $pers["nome"], $pers["classe"]
        )
    );
    $enemy_count++;
    if ($enemy_count >= 3) {
        break;
    }
}

insert_personagens_combate($userDetails->tripulacao["id"], $userDetails->personagens, $userDetails->vip, "tatic_a", 5, 9);

$userDetails->reduz_berries($preco);

echo "%combate";