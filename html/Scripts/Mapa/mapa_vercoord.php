<?php
include "../../Includes/conectdb.php";

if (!validate_number($_GET["x"]) || !validate_number($_GET["y"])) {
    exit();
}
$x = $_GET["x"];
$y = $_GET["y"];

$npss = DataLoader::load("nps");
$rdms = DataLoader::load("rdm");

$ilha = $connection->run("SELECT * FROM tb_mapa mapa WHERE mapa.x = ? AND mapa.y = ?", "ii", array($x, $y))
    ->fetch_array();

$has_ilha_envolta_target = $connection->run(
    "SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND ilha <> 0 AND ilha <> 47",
    "iiii", array($x - 1, $x + 1, $y - 1, $y + 1)
)->count();

$result = get_player_in_coord($x, $y);

$contents = $result->fetch_all_array();
?>
<ul class="list-group">
    <?php if ($ilha["ilha"]): ?>
        <?php $dono = $ilha["ilha_dono"]
            ? $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", array($ilha["ilha_dono"]))->fetch_array()
            : array("tripulacao" => "Governo Mundial", "faccao" => FACCAO_MARINHA, "bandeira" => "030128044241030118456317010115204020", "karma_bom" => 1, "karma_mau" => 0) ?>
        <li class="list-group-item">
            <h4>
                <img src="Imagens/Mapa/Mapa_Oceano/Mapa_<?= sprintf("%02d", $x) ?>_<?= sprintf("%02d", $y) ?>.jpg">
                <?= nome_ilha($ilha["ilha"]) ?>
            </h4>
            <img src="Imagens/Bandeiras/img.php?cod=<?= $dono["bandeira"] ?>&f=<?= $dono["faccao"] ?>">
            <p>
                Essa ilha
                está <?= $dono["karma_bom"] ? "protegida pelo" : "sob o domínio de" ?> <?= $dono["tripulacao"] ?>
            </p>
        </li>
    <?php endif; ?>
    <?php foreach ($contents as $content) : ?>
        <li class="list-group-item">
            <?php if ($content["nps_id"]): ?>
                <?php $nps = $npss[$content["nps_id"]]; ?>
                <?php $rdm = $rdms[$nps["rdm_id"]]; ?>
                <div class="row">
                    <div class="col-md-4">
                        <img src="Imagens/Batalha/Npc/Navios/<?= $nps["icon"]; ?>.png"/>
                    </div>
                    <div class="col-md-8">
                        <h4> <?= $rdm["nome"] ?> </h4>
                        <p>
                            Nível: <?= $rdm["lvl"]; ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php if (can_attack_nps($content)): ?>
                            <button class="btn btn-primary link_send"
                                    href="link_Mapa/mapa_atacar_nps.php?id=<?= $content["increment_id"] ?>"
                                    data-toggle="tooltip"
                                    title="Atacar essa tripulação.">
                                <img src="Imagens/Icones/tipo_Ataque.png">
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($content["id"]): ?>
                <div class="row">
                    <div class="col-md-4">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $content["bandeira"] ?>&f=<?= $content["faccao"] ?>"><br/>
                        <img data-toggle="tooltip" data-placement="bottom" width="40px"
                             title="<?= get_patente_nome($content["faccao"], $content["reputacao"]) ?>"
                             src="Imagens/Ranking/Patentes/<?= $content["faccao"] . "_" . get_patente_id($content["reputacao"]) ?>.png"/>
                    </div>
                    <div class="col-md-8">
                        <h4 class="<?= in_guerra($content) ? 'text-danger' : '' ?>">
                            <?= $content["tripulacao"] ?> - <?= $content["capitao"] ?>
                        </h4>
                        <?php if ($content["titulo"]) : ?>
                            <h5><?= $content["titulo"] ?></h5>
                        <?php endif; ?>
                        <p>
                            Nível do mais forte: <?= $content["nivel_mais_forte"]; ?>
                        </p>
                        <?php if ($content["alianca"]) : ?>
                            <h5 class="<?= in_guerra($content) ? 'text-danger' : '' ?>">
                                Membro da <?= $content["alianca"] ?>
                            </h5>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($userDetails->tripulacao["id"] != $content["id"]): ?>
                            <button class="btn btn-primary link_send"
                                    href="link_Batalha/desafiar.php?id=<?= $content["id"] ?>"
                                    data-toggle="tooltip"
                                    title="Disputa Amigável - Batalha não vale nada, serve apenas para diversão.(Seu oponente precisa aceitar seu desafio)">
                                <i class="fa fa-handshake-o"></i>
                            </button>
                        <?php endif; ?>
                        <?php if (can_attack($content)): ?>
                            <button class="btn btn-primary link_send"
                                    href="link_Mapa/mapa_atacar.php?id=<?= $content["id"] ?>&tipo=2"
                                    data-toggle="tooltip"
                                    title="Saque - A batalha vale menos reputação e o vencedor rouba o perdedor.">
                                <img src="Imagens/Icones/VIP2.png">
                            </button>
                            <button class="btn btn-primary link_send"
                                    href="link_Mapa/mapa_atacar.php?id=<?= $content["id"] ?>&tipo=1"
                                    data-toggle="tooltip"
                                    title="Ataque - Batalha em busca de reputação.">
                                <img src="Imagens/Icones/tipo_Ataque.png">
                            </button>
                        <?php endif; ?>
                        <?php if (can_dispair_cannon($content)): ?>
                            <button class="btn btn-primary link_send"
                                    href="link_Mapa/mapa_disparar.php?id=<?= $content["id"] ?>&tipo=2"
                                    data-toggle="tooltip"
                                    title="Atirar bala de canhão - Quando você der o ultimo disparo e destruir o navio inimigo, você rouba 10% dos berries daquele jogador.">
                                <img src="Imagens/Icones/canhao.png">
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-4">
                        <img src="Imagens/Batalha/Npc/Navios/4.png"/>
                    </div>
                    <div class="col-md-8">
                        <h4> Navio Mercador </h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?php if (can_attack_mercador($content)): ?>
                            <button class="btn btn-primary link_send"
                                    href="link_Mapa/atacar_mercador.php?id=<?= $content["increment_id"] ?>"
                                    data-toggle="tooltip"
                                    title="Atacar essa tripulação.">
                                <img src="Imagens/Icones/tipo_Ataque.png">
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
