<?php
function preco_compra_acessorio($item) {
    return pow($item["bonus_atr_qnt"], 2) * 1000;
}

function preco_venda_acessorio($item) {
    return pow($item["bonus_atr_qnt"], 2) * 1000 * 0.5;
}

function preco_compra_equipamento($item) {
    $lvl = isset($item["lvl"]) ? $item["lvl"] : 50;
    return round(pow($lvl, 1 / 2) * 50000);
}

function preco_venda_equipamento($item) {
    $lvl = isset($item["lvl"]) ? $item["lvl"] : 50;
    return round((pow($lvl, 1 / 2) * 50000) / 2);
}

function get_many_results_joined_mapped_by_type($tabela_origem, $tabela_origem_column, $tipo_column, $tabelas_info, $where = "", $bind_type = NULL, $bind_value = NULL) {
    $many_items = [];

    foreach ($tabelas_info as $tabela_info) {
        $items = get_result_joined_mapped_by_type($tabela_origem, $tabela_origem_column, $tipo_column, $tabela_info["nome"], $tabela_info["coluna"], $tabela_info["tipo"], $where, $bind_type, $bind_value);

        $many_items = array_merge($many_items, $items);
    }

    return $many_items;
}

function get_result_joined_mapped_by_type($tabela_origem, $tabela_origem_column, $tipo_column, $tabela_info, $tabela_info_column, $tipo, $where = "", $bind_type = NULL, $bind_value = NULL) {
    global $connection;
    return $connection->run(
        "SELECT * FROM $tabela_origem origem 
        INNER JOIN $tabela_info info ON info.$tabela_info_column = origem.$tabela_origem_column AND origem.$tipo_column=$tipo 
        $where", $bind_type, $bind_value
    )->fetch_all_array();
}

function get_img_item_src($item) {
    $format = isset($item["img_format"]) && $item["img_format"] ? $item["img_format"] : "png";
    return "Imagens/Itens/" . $item["img"] . ".$format";
}

function get_img_item($item) {
    return "<img src=\"" . get_img_item_src($item) . "\"/>";
}

function info_item_with_img($item, $item_info, $extra, $acao, $incombate = FALSE, $porcent = 1, $treino = array()) {
    $categoria = isset($item_info["categoria"]) ? $item_info["categoria"] : 0;
    return "<a href='#' class='noHref' data-toggle='popover' data-html='true' data-placement='bottom' data-trigger='focus' data-content='" . info_item($item, $item_info, $extra, $acao, $incombate, $porcent, $treino) . "'><p class='equipamentos_casse_$categoria'>" . get_img_item($item) . "<br/>" . ucwords($item["nome"]) . "</p></a>";
}

function info_item($item, $item_info, $extra, $acao, $incombate = FALSE, $porcent = 1, $treino = array()) {
    global $userDetails;

    $return = '<div class="info-item">';
    $return .= '<div class="info-item-title ' . (isset($item_info["categoria"]) ? 'equipamentos_casse_' . $item_info["categoria"] : '') . '">';
    $return .= "<b>" . $item_info["nome"] . "</b>";

    if (isset($item_info["upgrade"]) && $item_info["upgrade"] > 0) {
        if ($item_info["upgrade"] > 0)
            $return .= ' <span style="color: #ff0000"><b>+' . $item_info["upgrade"] . "</b></span>";
    }

    $return .= '</div>';
    $return .= '<div class="row">';
    $return .= '<div class="col-xs-4">';
    $return .= '<div>';
    $return .= get_img_item($item_info);
    $return .= '</div>';
    if (isset($item_info["lvl"])) {
        $return .= '<p>';
        $return .= '<b>lvl</b> <span class="info-item-lvl">' . $item_info["lvl"] . '</span>';
        $return .= '</p>';
    }
    $return .= '</div>';
    $return .= '<div class="col-xs-8">';
    if (isset($item_info["descricao"])) {
        $return .= '<p class="info-item-descricao">' . $item_info["descricao"] . "</p>";
    }
    $return .= '</div>';
    $return .= '</div>';

    $return .= '<div class="text-left">';
    if (isset($item_info["slot"])) {
        $return .= '<div class="info-item-slot">' . nome_slot($item_info["slot"]) . '</div>';
    }

    if (isset($item_info["bonus_atr"])) {
        $atributo = nome_atributo($item_info["bonus_atr"]);
        $return .= '<div>';
        $return .= '<img src="Imagens/Icones/' . nome_atributo_img($item_info["bonus_atr"]) . '.png" width="25px"/> <b>' . $atributo . "</b> +" . $item_info["bonus_atr_qnt"];
        $return .= '</div>';
    }
    if (isset($item_info["b_1"]) && $item_info["b_1"]) {
        $return .= '<div>';
        $return .= '<img src="Imagens/Icones/' . nome_atributo_img($item_info["b_1"]) . '.png" width="25px"/> <b>' . nome_atributo($item_info["b_1"]) . "</b> +" . calc_bonus_equip_atr_principal($item_info);
        $return .= '</div>';
    }
    if (isset($item_info["b_2"]) && $item_info["b_2"]) {
        $return .= '<div>';
        $return .= '<img src="Imagens/Icones/' . nome_atributo_img($item_info["b_2"]) . '.png" width="25px"/> <b>' . nome_atributo($item_info["b_2"]) . "</b> +" . calc_bonus_equip_atr_secundario($item_info);
        $return .= '</div>';
    }
    if (isset($item_info["requisito"])) {
        $return .= '<div>';
        $return .= "<b>Classe:</b> ";
        switch ($item_info["requisito"]) {
            case 1:
                $return .= "Espadachim";
                break;
            case 2:
                $return .= "Lutador";
                break;
            case 3:
                $return .= "Atirador";
                break;
            default:
                $return .= "Todas";
                break;
        }
        $return .= '</div>';
    }
    if (isset($item_info["hp_recuperado"])) {
        $return .= '<div>';
        $return .= "HP + " . ($item_info["hp_recuperado"] * 10);
        $return .= '</div>';
    }
    if (isset($item_info["mp_recuperado"])) {
        $return .= '<div>';
        $return .= "Energia + " . $item_info["mp_recuperado"];
        $return .= '</div>';
    }
    if (isset($item["tipo_item"]) && $item_info["tipo_item"] === TIPO_ITEM_REMEDIO) {
        $return .= '<div>';
        $return .= "Energia necessária em combate: " . ($item_info["hp_recuperado"] + $item_info["mp_recuperado"]);
        $return .= '</div>';
    }
    if (isset($item_info["apontando"])) {
        $return .= '<div>';
        $return .= "<b>Direção:</b> " . $item_info["apontando"];
        $return .= '</div>';
    }
    if (isset($item_info["tipo"]) && $acao) {
        if ($item_info["tipo"] === "Logia" || $item_info["tipo"] === "Paramecia" || $item_info["tipo"] === "Zoan") {
            $return .= '<div>';
            $return .= "<b>Tipo:</b> " . $item_info["tipo"];
            $return .= '</div>';
            $return .= '<div class="text-center">';
            $return .= '<a href="link_akuma" data-dismiss="modal"  class="link_content2 btn btn-success">Comer</a>';
            $return .= '</div>';
        }
    }
    if (isset($item_info["bonus"])) {
        $return .= '<div>';
        $return .= "<b>Bonus:</b> ";
        if ($item["tipo_item"] == 3) {
            $return .= "HP + " . $item_info["bonus"];
        } else if ($item["tipo_item"] == 4) {
            $return .= "Influência de correntes: " . $item_info["bonus"] . "%";
        } else if ($item["tipo_item"] == 5) {
            $return .= "Influência de ventos: " . $item_info["bonus"] . "%";
        } else if ($item["tipo_item"] == 12) {
            $return .= "Chance de acerto: " . $item_info["bonus"] . "%";
        }
        $return .= '</div>';
    }

    if ($extra) {
        $return .= '<div class="info-item-quantidade">';
        if (($item["tipo"] == 16 OR $item["tipo"] == 17) AND !$incombate) {
            $return .= "<b>Limite:</b> {$userDetails->tripulacao['iscas_usadas']} / " . LIMITE_USOS_ISCA_DIA;
            if ($item["quant"] > 1) {
                $return .= '<br />';
            }
        }
        if ($item["quant"] > 1) {
            $return .= "<b>Quantidade:</b> " . $item["quant"];
        }
        $return .= '</div>';
    }
    $return .= '</div>';
    if ($acao) {
        if ((isset($item_info["hp_recuperado"]) OR isset($item_info["mp_recuperado"])) AND !$incombate) {
            $return .= '<p>';
            $return .= '<a title="Usar" data-dismiss="modal"  id="comida=' . $item["cod"] . '&tipo=' . $item["tipo"] . '" 
				class="link_dar_comida noHref btn btn-success" href="#">Usar</a>';
            $return .= '</p>';
        }
        if (isset($item_info["method"]) AND !$incombate) {
            $return .= '<p>';
            $return .= '<a title="Usar" data-dismiss="modal" class="link_confirm btn btn-success" data-question="Deseja usar este item?" href="Inventario/usar_item.php?cod=' . $item["cod"] . '&tipo=' . $item["tipo"] . '">Usar</a>';
            $return .= '</p>';
        }
        if (($item["tipo"] == 16 OR $item["tipo"] == 17) AND !$incombate) {
            $return .= '<p>';
            if ($userDetails->tripulacao['iscas_usadas'] < LIMITE_USOS_ISCA_DIA)
                $return .= '<a title="Usar" data-dismiss="modal" class="link_confirm btn btn-success" data-question="Deseja usar este item?" href="Vip/isca.php?tipo=' . $item["tipo"] . '">Usar</a>';
            else
                $return .= '<button type="button" class="btn btn-danger btn-disabled" disabled>Indisponível</button>';
            $return .= '</p>';
        }

        $return .= '<a title="Descartar" data-dismiss="modal" id="item=' . $item["cod"] . '&tipo=' . $item["tipo_item"] . '" 
					class="x_descart noHref btn btn-warning" href="#" >Descartar</a> ';
    }

    if ($extra) {
        if ($item["quant"] > 1) {
            $return .= '<a title="Descartar Tudo" id="item=' . $item["cod"] . '&tipo=' . $item["tipo"] . '&tudo=1" 
					class="x_descart_tudo btn btn-danger" href="#" class="noHref">Descartar Tudo</a>';
        }
    }

    return $return . "</div>";
}