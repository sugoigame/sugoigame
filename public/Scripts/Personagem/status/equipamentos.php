<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>
<?php function render_slot($slot, $equips, $equips_info, $treinos, $pers) { ?>
    <a href="#"
       class="noHref slot slot_<?= $slot ?> <?= isset($equips_info[$slot]) ? "equipamentos_casse_" . ($equips_info[$slot]["categoria"]) : "" ?>"
       data-content="-" data-toggle="popover" data-placement="bottom" data-html="true" data-trigger="focus"
       data-template='
         <div class="equip-info">
            <div class="panel panel-default">
                <div class="panel-heading"><?= nome_slot($slot) ?> </div>
                <div class="panel-body">
                    <?php if (isset($equips[$slot])) : ?>
                        <?= info_item($equips[$slot], $equips_info[$slot], FALSE, FALSE, FALSE, $treinos[$slot]["porcent"], $treinos[$slot]); ?>
                        <br/><br/>
                        <div>
                            <a href="link_Personagem/equipamento_desequipar.php?slot=<?= $slot ?>&pers=<?= $pers["cod"] ?>"
                                class="link_send btn btn-primary">Retirar</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>'>
        <?php if ($pers["lvl"] >= 50 && !isset($equips[$slot])) : ?>
            <?= get_alert("pull-right"); ?>
        <?php endif; ?>
        <?php $format = isset($equips_info[$slot]) && isset($equips_info[$slot]["img_format"]) ? $equips_info[$slot]["img_format"] : "png"; ?>
        <img src="Imagens/Itens/<?= isset($equips_info[$slot]) ? $equips_info[$slot]["img"] . "." . $format : "slot_$slot.jpg" ?>"
             id="slot_<?= $pers["cod"] ?>_<?= $slot ?>" class="img_slot"/>
    </a>
<?php } ?>

    <style type="text/css">
        .equip-info {
            position: absolute;
            z-index: 5;
            width: 300px;
        }

        .slot {
            display: block;
            cursor: pointer;
            padding: 10px;
            background: #777;
            border: 1px solid #444;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            margin: 5px;
        }

        .personagem-equipamentos {
            position: relative;
        }

        .personagem-atributos {
            position: absolute;
            bottom: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            padding: 1em 2em;
        }
    </style>

<?php
$eq = load_equipamentos($pers);
$equips = $eq["equips"];
$equips_info = $eq["equips_info"];
$treinos = $eq["treinos"];

$bonus_total = cal_bonus_equip_atributo($equips_info, $treinos);

$acessorio = $pers["cod_acessorio"]
    ? $connection->run("SELECT * FROM tb_item_acessorio WHERE cod_acessorio = ?", "i", $pers["cod_acessorio"])->fetch_array()
    : null;

if ($acessorio) {
    $bonus_total[nome_atributo_tabela($acessorio["bonus_atr"])] += $acessorio["bonus_atr_qnt"];
}
?>
<?php render_personagem_panel_top($pers, 0) ?>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-3">
                    <?php render_slot(1, $equips, $equips_info, $treinos, $pers) ?>
                    <?php render_slot(2, $equips, $equips_info, $treinos, $pers) ?>
                    <?php render_slot(3, $equips, $equips_info, $treinos, $pers) ?>
                </div>
                <div class="col-md-6 personagem-equipamentos">
                    <ul class="text-left personagem-atributos">
                        <?php for ($i = 1; $i < 8; $i++): ?>
                            <li>
                                <img src="Imagens/Icones/<?= nome_atributo_img($i) ?>.png"
                                     width="25px">
                                + <?= $bonus_total[nome_atributo_tabela($i)] ?>
                            </li>
                        <?php endfor; ?>
                    </ul>
                    <img src="Imagens/Personagens/Big/<?= getImg($pers, "c") ?>.jpg">
                </div>
                <div class="col-md-3">
                    <?php render_slot(4, $equips, $equips_info, $treinos, $pers) ?>
                    <?php render_slot(5, $equips, $equips_info, $treinos, $pers) ?>
                    <?php render_slot(6, $equips, $equips_info, $treinos, $pers) ?>

                    <a href="#" data-trigger="focus"
                       class="noHref slot slot_acessorio <?= isset($acessorio["categoria"]) ? "equipamentos_casse_" . $acessorio["categoria"] : "" ?>"
                       data-content="-" data-toggle="popover" data-placement="bottom" data-html="true"
                       data-template='
                                     <div class="equip-info">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Acessório</div>
                                            <div class="panel-body">
                                                <?php if ($acessorio) : ?>
                                                    <?= info_item($acessorio, $acessorio, FALSE, FALSE, FALSE); ?>
                                                    <br/><br/>
                                                    <div>
                                                        <a href="link_Personagem/desequipar.php?item=<?= $acessorio["cod_acessorio"] ?>&person=<?= $pers["cod"] ?>"
                                                            class="link_send btn btn-primary">Retirar</a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>'>
                        <?php if ($pers["lvl"] >= 50 && !$acessorio) : ?>
                            <?= get_alert("pull-right"); ?>
                        <?php endif; ?>
                        <?php $format = $acessorio && isset($acessorio["img_format"]) ? $acessorio["img_format"] : "png"; ?>
                        <img src="Imagens/Itens/<?= $acessorio ? $acessorio["img"] . "." . $format : "slot_9.jpg" ?>"
                             id="slot_<?= $pers["cod"] ?>_acessorio" class="img_slot"/>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php render_slot(7, $equips, $equips_info, $treinos, $pers) ?>
                </div>
                <div class="col-md-6">
                    <?php render_slot(8, $equips, $equips_info, $treinos, $pers) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Equipamentos -->
            <?php $iventario_equips = get_many_results_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", array(
                array("nome" => "tb_item_equipamentos", "coluna" => "cod_equipamento", "tipo" => TIPO_ITEM_EQUIPAMENTO),
                array("nome" => "tb_item_acessorio", "coluna" => "cod_acessorio", "tipo" => TIPO_ITEM_ACESSORIO)
            ), "WHERE id= ?", "i", $userDetails->tripulacao["id"]); ?>

            <div class="row">
                <?php foreach ($iventario_equips as $item): ?>
                    <?php $item["cod"] = $item["cod_item"]; ?>
                    <?php $item["tipo"] = $item["tipo_item"]; ?>
                    <div class="col-md-3 list-group-item">
                        <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                        <div>
                            <?php if ($item["tipo_item"] == TIPO_ITEM_ACESSORIO): ?>
                                <button href="link_Personagem/equipar.php?item=<?= $item["cod_acessorio"] ?>&person=<?= $pers["cod"] ?>"
                                        class="link_send btn btn-success">
                                    Equipar
                                </button>
                            <?php else: ?>
                                <?php if ($pers["lvl"] >= $item["lvl"] AND ($item["requisito"] == $pers["classe"] OR $item["requisito"] == 0)) : ?>
                                    <?php if ($item["slot"] <= 8) : ?>
                                        <button href="link_Personagem/equipamento_equipar.php?item=<?= $item["cod_equipamento"] ?>&pers=<?= $pers["cod"] ?>&slot=<?= $item["slot"] ?>"
                                                class="link_send btn btn-success">
                                            Equipar
                                        </button>
                                    <?php elseif ($item["slot"] == 9) : ?>
                                        <button href="link_Personagem/equipamento_equipar.php?item=<?= $item["cod_equipamento"] ?>&pers=<?= $pers["cod"] ?>&slot=7"
                                                class="link_send btn btn-success">
                                            Primeira mão
                                        </button><br/>
                                        <button href="link_Personagem/equipamento_equipar.php?item=<?= $item["cod_equipamento"] ?>&pers=<?= $pers["cod"] ?>&slot=8"
                                                class="link_send btn btn-success">
                                            Segunda mão
                                        </button><br/>
                                        <button href="link_Personagem/equipamento_equipar.php?item=<?= $item["cod_equipamento"] ?>&pers=<?= $pers["cod"] ?>&slot=1"
                                                class="link_send btn btn-success">
                                            Cabeça
                                        </button>
                                    <?php else : ?>
                                        <button href="link_Personagem/equipamento_equipar.php?item=<?= $item["cod_equipamento"] ?>&pers=<?= $pers["cod"] ?>&slot=7"
                                                class="link_send btn btn-success">
                                            Equipar
                                        </button>
                                    <?php endif; ?>
                                <? endif; ?>
                            <? endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


<?php render_personagem_panel_bottom() ?>