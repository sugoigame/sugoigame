<?php
function categoria_akuma($atacante, $alvo) {
    if ($atacante == 1) {
        if ($alvo == 2) $mod = 1.2;
        else if ($alvo == 6) $mod = 0.8;
        else if ($alvo == 9) $mod = 1.1;
        else $mod = 1;
    } else if ($atacante == 2) {
        if ($alvo == 3) $mod = 1.2;
        else if ($alvo == 1) $mod = 0.8;
        else if ($alvo == 9) $mod = 1.1;
        else $mod = 1;
    } else if ($atacante == 3) {
        if ($alvo == 4) $mod = 1.2;
        else if ($alvo == 2) $mod = 0.8;
        else if ($alvo == 9) $mod = 1.1;
        else $mod = 1;
    } else if ($atacante == 4) {
        if ($alvo == 5) $mod = 1.2;
        else if ($alvo == 3) $mod = 0.8;
        else if ($alvo == 9) $mod = 1.1;
        else $mod = 1;
    } else if ($atacante == 5) {
        if ($alvo == 6) $mod = 1.2;
        else if ($alvo == 4) $mod = 0.8;
        else if ($alvo == 9) $mod = 1.1;
        else $mod = 1;
    } else if ($atacante == 6) {
        if ($alvo == 1) $mod = 1.2;
        else if ($alvo == 5) $mod = 0.8;
        else if ($alvo == 9) $mod = 1.1;
        else $mod = 1;
    } else if ($atacante == 7) {
        if ($alvo == 7) $mod = 1;
        if ($alvo == 8) $mod = 1;
        else $mod = 1.1;
    } else if ($atacante == 8) {
        $mod = 1;
    } else if ($atacante == 9) {
        $mod = 1;
    } else $mod = 1;

    return $mod;
}

function nome_categoria_akuma($categoria) {
    switch ($categoria) {
        case 1:
            return "A";
        case 2:
            return "B";
        case 3:
            return "C";
        case 4:
            return "D";
        case 5:
            return "E";
        case 6:
            return "F";
        case 7:
            return "Mística";
        case 8:
            return "Neutra";
        case 9:
            return "Ineficaz";
        default:
            return "Inexistente";
    }
}

function nome_tipo_akuma($tipo) {
    switch ($tipo) {
        case 8:
            return "Logia";
        case 9:
            return "Paramecia";
        case 10:
            return "Zoan";
        default:
            return "Inexistente";
    }
}

function get_desc_akuma_random($tipo) {
    $akumas = DataLoader::load("akuma_description");
    $akumas = $akumas[$tipo];

    return $akumas[array_rand($akumas)];
}

function render_status_akuma($pers, $img_akuma, $tipoakuma) {
    ?>
    <div class="row">
        <div class="col-md-6 hidden-sm hidden-xs">
            <?= big_pers_skin($pers["img"], $pers["skin_c"]) ?>
        </div>
        <div class="col-md-6 text-left">
            <img src="Imagens/Itens/<?= $img_akuma ?>.png">
            <?= nome_tipo_akuma($tipoakuma) ?>
            <ul>
                <li><span id="quantidade_ataques"><?= ($tipoakuma == 10) ? "2" : "3" ?></span> Ataques</li>
                <li><span id="quantidade_buffs"><?= ($tipoakuma == 10) ? "5" : ($tipoakuma == 9 ? "4" : "3") ?></span>
                    Buffs
                </li>
                <li><span id="quantidade_passivas"><?= ($tipoakuma == 8) ? "5" : "4" ?></span> Passivas</li>
            </ul>
        </div>
    </div>
<?php } ?>
<?php function render_criador_akuma($pers, $img_akuma, $tipoakuma, $nome_akuma = null, $descricao_akuma = null) { ?>
    <input type="hidden" name="cod_pers" value="<?= $pers["cod"] ?>">
    <input type="hidden" name="img_akuma" value="<?= $img_akuma ?>">
    <input type="hidden" name="tipoakuma" value="<?= $tipoakuma ?>">

    <?php $sugestao_nome = get_desc_akuma_random($tipoakuma); ?>
    <div class="form-group">
        <label>Nome:</label>
        <input <?= $nome_akuma ? "readonly" : "" ?>
                value="<?= $nome_akuma ? $nome_akuma : $sugestao_nome["nome"] ?>" class="form-control" name="nome"
                required maxlength="50"/>
    </div>
    <div class="form-group">
        <label>Nome:</label>
        <textarea <?= $nome_akuma ? "readonly" : "" ?> class="form-control" name="descricao" required
                                                       maxlength="255"><?= $descricao_akuma ? $descricao_akuma : $sugestao_nome["descricao"] ?></textarea>
    </div>

    <ul class="list-group text-center">
        <?php $i = 5; ?>
        <?php for ($x = 0; $x < 51; $x += 5) : ?>
            <li class="list-group-item">
                <!-- lvl -->
                <h4>
                    Habilidade de nível <?= $x == 0 ? 1 : $x ?>:
                </h4>

                <!-- tipo -->
                <div id="form_radios">
                    <label class="checkbox-inline radio_atk_<?php if ($x == 0) echo 1; else echo $x; ?>">
                        <input onclick="sel_atk('<?php if ($x == 0) echo 1; else echo $x; ?>');"
                               type="radio" name="tipo_skil_<?php if ($x == 0) echo 1; else echo $x; ?>" value="0"/>
                        <span>Ataque</span>
                    </label>
                    <label class="checkbox-inline radio_buf_<?php if ($x == 0) echo 1; else echo $x; ?>">
                        <input onclick="sel_buf('<?php if ($x == 0) echo 1; else echo $x; ?>');"
                               type="radio" name="tipo_skil_<?php if ($x == 0) echo 1; else echo $x; ?>" value="1">
                        <span>Buff</span>
                    </label>
                    <label class="checkbox-inline radio_pas_<?php if ($x == 0) echo 1; else echo $x; ?>">
                        <input onclick="sel_pas('<?php if ($x == 0) echo 1; else echo $x; ?>');"
                               type="radio" name="tipo_skil_<?php if ($x == 0) echo 1; else echo $x; ?>" value="2">
                        <span>Passiva</span>
                    </label>
                </div>
                <div id="skill-details-<?php if ($x == 0) echo 1; else echo $x; ?>" class="skill-details">
                    <div class="form-inline">
                        <!-- bonus -->
                        <div id="select_atributo_<?php if ($x == 0) echo 1; else echo $x; ?>" class="form-group">
                            <select class="form-control" name="atr_skil_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    size="1">
                                <option value="1">Ataque</option>
                                <option value="2">Defesa</option>
                                <option value="3">Agilidade</option>
                                <option value="4">Resistência</option>
                                <option value="5">Precisão</option>
                                <option value="6">Dextreza</option>
                                <option value="7">Percepção</option>
                            </select>
                        </div>

                        <!-- bonus value -->
                        <div id="add_sb_<?php if ($x == 0) echo 1; else echo $x; ?>" class="form-group">
                            <select name="add_ou_sub_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    id="add_ou_sub_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    class="form-control" style="display: none;">
                                <option value="1">+</option>
                                <option value="-1">-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control input-text"
                                   name="bonus_value_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   id="bonus_value_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   readonly="true" size="1">
                        </div>
                    </div>
                    <div class="form-inline">
                        <!-- energia gasta -->
                        <div class="form-group">
                            <label>Energia necessária:</label>
                            <input name="energia_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   id="energia_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   class="form-control input-text" readonly="true" size="1"/>
                        </div>
                    </div>
                    <div class="form-inline">
                        <!-- alcance -->
                        <div class="form-group">
                            <label>Alcance:</label>
                            <select id="alcance_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    name="alcance_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    class="form-control"
                                    onchange="atualizaBonus('1',this.value,'<?php if ($x == 0) echo 1; else echo $x; ?>')">
                                <option value="1">1 quadro</option>
                                <option value="2">2 quadros</option>
                                <option value="3">3 quadros</option>
                                <option value="4">4 quadros</option>
                                <option value="5">5 quadros</option>
                                <option value="6">6 quadros</option>
                                <option value="7">7 quadros</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-inline">
                        <!-- area -->
                        <div class="form-group">
                            <label>Área de efeito:</label>
                            <select id="area_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    name="area_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                    class="form-control"
                                    onchange="atualizaBonus('2',this.value,'<?php if ($x == 0) echo 1; else echo $x; ?>')">
                                <option value="1">1 quadro</option>
                                <option value="2">2 quadros</option>
                                <option value="4">4 quadros</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-inline">
                        <!-- duraçao do buff -->
                        <div class="form-group">
                            <label>Duração do buff:</label>
                            <input name="duracao_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   id="duracao_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   class="form-control input-text" readonly="true" size="1"/>
                        </div>
                    </div>
                    <div class="form-inline">
                        <!-- cooldown -->
                        <div class="form-group">
                            <label>Espera:</label>
                            <input name="cooldown_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   id="cooldown_<?php if ($x == 0) echo 1; else echo $x; ?>"
                                   class="form-control input-text" readonly="true" size="1"
                                   value="<?php if ($x < 11) echo "1";
                                   else if ($x > 11 AND $x < 21) echo "2";
                                   else if ($x > 21 AND $x < 31) echo "3";
                                   else if ($x > 31 AND $x < 41) echo "4";
                                   else echo "5"; ?>"/>
                        </div>
                    </div>
                </div>
            </li>
        <?php endfor; ?>
    </ul>
<?php } ?>