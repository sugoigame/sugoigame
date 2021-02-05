<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$result = $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?",
    "ii", array($userDetails->tripulacao["id"], TIPO_ITEM_MAPA));

if (!$result->count()) {
    $protector->exit_error("Você precisa de um mapa.");
}

$cod_mapa = $result->fetch_array()["cod_item"];
?>
<div class="modal-body">
    <div>
        <p>Selecione um oceano para ver o mapa</p>
        <ul class="nav nav-pills nav-justified">
            <li><a href="#" class="select_oceano noHref" id="1"><?= nome_mar(1) ?></a></li>
            <li><a href="#" class="select_oceano noHref" id="2"><?= nome_mar(2) ?></a></li>
            <li><a href="#" class="select_oceano noHref" id="3"><?= nome_mar(3) ?></a></li>
            <li><a href="#" class="select_oceano noHref" id="4"><?= nome_mar(4) ?></a></li>
            <li><a href="#" class="select_oceano noHref" id="5"><?= nome_mar(5) ?></a></li>
            <li><a href="#" class="select_oceano noHref" id="6"><?= nome_mar(6) ?></a></li>
        </ul>
    </div>
    <div class="mapa-mundi-view">
        <div id="mapa_cartografo_oceano">
        </div>
    </div>
</div>
<div class="modal-footer text-left">
    <input type="hidden" id="meu_mapa" name="meu_mapa" class="tracar_rota_c" readonly="true"
           value="<?= $cod_mapa ?>"/>
    <ul>
        <li>
            O cartógrafo consegue exibir as coordenadas dos mapas de tesouro e de monstros marítimicos em lugares onde
            ele já desenhou
        </li>
    </ul>
</div>
