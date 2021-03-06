<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$result = $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?", "ii", [
    $userDetails->tripulacao["id"],
    TIPO_ITEM_MAPA
]);

if (!$result->count()) {
    $protector->exit_error("Você precisa de um mapa.");
}

$mapa   = $result->fetch_array();
$mar    = get_mar($userDetails->tripulacao['x'], $userDetails->tripulacao['y']);
?>
<input type="hidden" id="meu_mapa" name="meu_mapa" class="tracar_rota_c" value="<?=$mapa["cod_item"];?>" />
<div class="modal-body">
    <div>
        <p>Selecione um oceano para ver o mapa</p>
        <ul class="nav nav-pills nav-justified menu-cartografo">
            <?php for ($i = 1; $i <= 6; $i++) { ?>
            <li><a href="#" class="select_oceano noHref" id="<?=$i;?>"><?=nome_mar($i);?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="mapa-mundi-view">
        <div id="mapa_cartografo_oceano"></div>
    </div>
</div>
<div class="modal-footer">
    <p class="text-center">O cartógrafo consegue exibir as coordenadas das ilhas, mapas de tesouro
        e de monstros marítimicos em lugares onde ele já desenhou</p>
</div>
<script type="text/javascript">
    loadMapaCartografo(<?=$mar;?>, <?=$mapa['cod_item'];?>)
</script>