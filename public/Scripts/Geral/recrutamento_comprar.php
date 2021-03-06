<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$recompensa_id = $protector->get_number_or_exit("rec");

$recompensas = DataLoader::load("loja_recrutamento");

if (!isset($recompensas[$recompensa_id])) {
    $protector->exit_error("Recompensa inválida");
}

$recompensa = $recompensas[$recompensa_id];

if ($userDetails->conta["medalhas_recrutamento"] < $recompensa["preco"]) {
    $protector->exit_error("Você não possui medalhas de recrutamento suficientes");
}

if (isset($recompensa["akuma"])) {
    if (!$userDetails->add_item(rand(100, 110), rand(8, 10), 1, true)) {
        $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
    }
}
if (isset($recompensa["alcunha"])) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $recompensa["alcunha"]));
}

if (isset($recompensa["skin"])) {
    $existe = $connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ? AND img = ? AND skin = ?",
        "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
    if ($existe->count()) {
        $protector->exit_error("Você já possui essa aparência nessa tripulação");
    }

    $connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin) VALUE (?, ?, ?)",
        "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
}

if (isset($recompensa["skin_navio"])) {
    $existe = $connection->run("SELECT * FROM tb_tripulacao_skin_navio WHERE tripulacao_id = ? AND skin_id = ?",
        "ii", array($userDetails->tripulacao["id"], $recompensa["skin_navio"]));
    if ($existe->count()) {
        $protector->exit_error("Você já possui essa aparência nessa tripulação");
    }
    $connection->run("INSERT INTO tb_tripulacao_skin_navio (conta_id, tripulacao_id, skin_id) VALUE (?, ?, ?)",
        "iii", array($userDetails->conta["conta_id"], $userDetails->tripulacao["id"], $recompensa["skin_navio"]));
}

$connection->run("UPDATE tb_conta SET medalhas_recrutamento = medalhas_recrutamento - ? WHERE conta_id = ?",
    "ii", array($recompensa["preco"], $userDetails->conta["conta_id"]));

echo "Você recebeu sua recompensa!";