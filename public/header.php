<?php
function get_berries()
{
    global $userDetails;

    return mascara_berries($userDetails->tripulacao["berries"]);
}
function get_current_mar()
{
    global $userDetails;

    return nome_mar($userDetails->ilha["mar"]);
}
function get_current_ilha()
{
    global $userDetails;

    return nome_ilha($userDetails->ilha["ilha"]);
}
function has_mapa()
{
    global $userDetails, $connection;

    return $connection->run("SELECT * FROM tb_usuario_itens WHERE tipo_item = 2 AND id= ?", "i", $userDetails->tripulacao["id"])->count();
}
?>
<nav class="header-navbar header-navbar-left">
    <ul class="nav navbar-nav">
        <li id="div_icon_coordenada" data-toggle="tooltip" title="Localização atual" data-placement="bottom">
            <a>
                <img src="Imagens/Icones/Pose.png" height="21px" />
                <span id="location">
                    <?= get_current_location(); ?>
                </span>,
                <span id="destino_mar">
                    <?= get_current_mar(); ?>
                </span> -
                <span id="destino_ilha">
                    <?= get_current_ilha(); ?>
                </span>
            </a>
        </li>
    </ul>
</nav>
<nav class="header-navbar header-navbar-right">

    <ul class="nav navbar-nav navbar-right">
        <?php include "Includes/Components/Header/icon_torneio.php"; ?>

        <?php include "Includes/Components/Header/localizador_pvp.php"; ?>

        <?php include "Includes/Components/Header/daily_gift.php"; ?>

        <?php include "Includes/Components/Header/buffs_ativos.php"; ?>

        <?php include "Includes/Components/Header/mapa_cartografo.php"; ?>

        <?php include "Includes/Components/Header/denden_mushi.php"; ?>

        <?php include "Includes/Components/Header/inventario.php"; ?>

        <?php include "Includes/Components/Header/berries.php"; ?>

        <?php include "Includes/Components/Header/moedas_ouro.php"; ?>

        <?php include "Includes/Components/Header/dobroes.php"; ?>

    </ul>
</nav>
