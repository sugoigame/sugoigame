<?php
function has_animacao($skill) {
    return $skill["tipo"] != TIPO_SKILL_PASSIVA_AKUMA
        && $skill["tipo"] != TIPO_SKILL_PASSIVA_CLASSE
        && $skill["tipo"] != TIPO_SKILL_PASSIVA_PROFISSAO;
}

function is_editavel($skill) {
    global $COD_HAOSHOKU_LVL;
    return ($skill["tipo"] != TIPO_SKILL_ATAQUE_CLASSE || $skill["cod_skil"] != 1)
        && ($skill["tipo"] != TIPO_SKILL_ATAQUE_CLASSE || !in_array($skill["cod_skil"], $COD_HAOSHOKU_LVL));
}

function has_special_effect($skill, $skills_com_efeitos, $ataques_com_efeitos, $max_ataques_com_efeitos) {
    return ($skill["tipo"] == TIPO_SKILL_ATAQUE_AKUMA && $ataques_com_efeitos < $max_ataques_com_efeitos && $skills_com_efeitos < 1)
        || ($skill["tipo"] == TIPO_SKILL_ATAQUE_CLASSE && $skill["maestria"] && $ataques_com_efeitos < $max_ataques_com_efeitos && $skills_com_efeitos < 1)
        || $skill["special_effect"];
}

function nome_tipo_skill($skill) {
    switch ($skill["tipo"]) {
        case TIPO_SKILL_ATAQUE_CLASSE:
        case TIPO_SKILL_ATAQUE_PROFISSAO:
        case TIPO_SKILL_ATAQUE_AKUMA:
            return "Ataque";
        case TIPO_SKILL_BUFF_CLASSE:
        case TIPO_SKILL_BUFF_PROFISSAO:
        case TIPO_SKILL_BUFF_AKUMA:
            return "Buff";
        default:
            return "Passiva";
    }
}

function nome_origem_skill($skill) {
    switch ($skill["tipo"]) {
        case TIPO_SKILL_ATAQUE_CLASSE:
        case TIPO_SKILL_BUFF_CLASSE:
        case TIPO_SKILL_PASSIVA_CLASSE:
            return "Classe";
        case TIPO_SKILL_ATAQUE_PROFISSAO:
        case TIPO_SKILL_BUFF_PROFISSAO:
        case TIPO_SKILL_PASSIVA_PROFISSAO:
            return "Profissão";
        default:
            return "Akuma";
    }
}

function nome_special_effect($effect) {
    if ($effect < 0) {
        return "Imune a " . nome_special_effect(abs($effect));
    }

    switch ($effect) {
        case SPECIAL_EFFECT_SANGRAMENTO:
            return "Sangramento";
        case SPECIAL_EFFECT_VENENO:
            return "Veneno";
        case SPECIAL_EFFECT_MACHUCADO_JOELHO:
            return "Machucado no joelho";
        case SPECIAL_EFFECT_PONTO_FRACO:
            return "Acertar Ponto Fraco";
        default:
            return "";
    }
}

function duracao_special_effect($effect) {
    switch ($effect) {
        case SPECIAL_EFFECT_SANGRAMENTO:
            return 3;
        case SPECIAL_EFFECT_VENENO:
            return 6;
        case SPECIAL_EFFECT_MACHUCADO_JOELHO:
            return 2;
        case SPECIAL_EFFECT_PONTO_FRACO:
            return 1;
        default:
            return 0;
    }
}

function descricao_special_effect($effect) {
    switch ($effect) {
        case SPECIAL_EFFECT_SANGRAMENTO:
            return "O personagem que sofre desse efeito perde 6% da vida máxima a cada turno durante " . duracao_special_effect($effect) . " turnos até ficar com 1 ponto de vida.";
        case SPECIAL_EFFECT_VENENO:
            return "O personagem que sofre desse efeito perde 3% da vida máxima a cada turno durante " . duracao_special_effect($effect) . " turnos até ficar com 1 ponto de vida.";
        case SPECIAL_EFFECT_MACHUCADO_JOELHO:
            return "O personagem que sofre desse efeito não pode se movimentar por " . duracao_special_effect($effect) . " turnos";
        case SPECIAL_EFFECT_PONTO_FRACO:
            return "O golpe que acerta o ponto fraco ignora 50% da defesa do adversário. (Esse efeito só funciona no Coliseu e em disputas amigáveis)";
        default:
            return "";
    }
}

function nome_special_effect_target($target) {
    switch ($target) {
        case SPECIAL_TARGET_SELF:
            return "Emissor";
        case SPECIAL_TARGET_TARGET:
            return "Alvo";
        default:
            return "";
    }
}

function nome_special_effect_apply_type($target) {
    switch ($target) {
        case SPECIAL_APPLY_TYPE_APPLY:
            return "Aplica o efeito";
        case SPECIAL_APPLY_TYPE_REMOVE:
            return "Remove uma aplicação e deixa imune por 1 turno ao efeito";
        default:
            return "";
    }
}

function get_basic_skills($filter_column, $filter_value, $tipo_base = 0, $maestria = 0) {
    global $connection;
    $skills = [];

    $result = $connection->run("SELECT * FROM tb_skil_atk WHERE $filter_column = ? AND maestria = ? ORDER BY requisito_lvl, categoria",
        "ii", array($filter_value, $maestria));

    while ($skill = $result->fetch_array()) {
        $skill["tipo"] = "Ataque";
        $skill["tiponum"] = $tipo_base + 1;
        $skills[] = $skill;
    }

    $result = $connection->run("SELECT * FROM tb_skil_buff WHERE $filter_column = ? AND maestria = ? ORDER BY requisito_lvl, categoria",
        "ii", array($filter_value, $maestria));

    while ($skill = $result->fetch_array()) {
        $skill["tipo"] = "Buff";
        $skill["tiponum"] = $tipo_base + 2;
        $skills[] = $skill;
    }

    $result = $connection->run("SELECT * FROM tb_skil_passiva WHERE $filter_column = ? AND maestria = ? ORDER BY requisito_lvl, categoria",
        "ii", array($filter_value, $maestria));

    while ($skill = $result->fetch_array()) {
        $skill["tipo"] = "Passiva";
        $skill["tiponum"] = $tipo_base + 3;
        $skills[] = $skill;
    }

    $skills_ordered = $skills;
    $categorias = [];
    $lvl = [];
    foreach ($skills_ordered as $key => $row) {
        $categorias[$key] = $row['categoria'];
        $lvl[$key] = $row["requisito_lvl"];
    }
    array_multisort($categorias, SORT_ASC, $lvl, SORT_ASC, $skills_ordered);

    return $skills_ordered;
}

function aprende_habilidade_random($pers, $cod_skill, $tipo_skill) {
    global $connection;

    $habilidade = habilidade_random();
    $icon = rand(1, SKILLS_ICONS_MAX);

    $connection->run("INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon) VALUE (?,?,?,?,?,?)",
        "iiissi", array($pers["cod"], $cod_skill, $tipo_skill, $habilidade["nome"], $habilidade["descricao"], $icon));
}

function aprende_todas_habilidades_disponiveis_akuma($pers) {
    global $connection;

    $result = $connection->run(
        "SELECT * FROM tb_akuma_skil_atk a 
    LEFT JOIN tb_personagens_skil s ON a.cod_skil = s.cod_skil AND s.tipo = ? 
    WHERE a.cod_akuma= ? AND s.cod IS NULL ORDER BY a.lvl",
        "ii", array(TIPO_SKILL_ATAQUE_AKUMA, $pers["akuma"])
    );

    while ($skill = $result->fetch_array()) {
        if ($skill["lvl"] <= $pers["lvl"]) {
            aprende_habilidade_random($pers, $skill["cod_skil"], TIPO_SKILL_ATAQUE_AKUMA);
        }
    }

    $result = $connection->run(
        "SELECT * FROM tb_akuma_skil_buff a 
    LEFT JOIN tb_personagens_skil s ON a.cod_skil = s.cod_skil AND s.tipo = ? 
    WHERE a.cod_akuma= ? AND s.cod IS NULL ORDER BY a.lvl",
        "ii", array(TIPO_SKILL_BUFF_AKUMA, $pers["akuma"])
    );

    while ($skill = $result->fetch_array()) {
        if ($skill["lvl"] <= $pers["lvl"]) {
            aprende_habilidade_random($pers, $skill["cod_skil"], TIPO_SKILL_BUFF_AKUMA);
        }
    }

    $result = $connection->run(
        "SELECT * FROM tb_akuma_skil_passiva a 
    LEFT JOIN tb_personagens_skil s ON a.cod_skil = s.cod_skil AND s.tipo = ? 
    WHERE a.cod_akuma= ? AND s.cod IS NULL ORDER BY a.lvl",
        "ii", array(TIPO_SKILL_PASSIVA_AKUMA, $pers["akuma"])
    );

    while ($skill = $result->fetch_array()) {
        if ($skill["lvl"] <= $pers["lvl"]) {
            aprende_habilidade_random($pers, $skill["cod_skil"], TIPO_SKILL_PASSIVA_AKUMA);
        }
    }
}
?>
<?php function render_habilidades_classe_tab($skills, $pers, $form_url, $pode_aprender_func) { ?>
    <?php global $connection; ?>
    <?php $lvls = array(3, 5, 10, 15, 20, 30, 40, 50); ?>
    <?php foreach ($lvls as $linha => $lvl): ?>
        <div class="row">
            <?php $aprendidas = array(
                1 => $connection->run("SELECT cod FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
                    "iii", array($pers["cod"], $skills[1][$linha]["cod_skil"], $skills[1][$linha]["tiponum"]))->count(),
                2 => $connection->run("SELECT cod FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
                    "iii", array($pers["cod"], $skills[2][$linha]["cod_skil"], $skills[2][$linha]["tiponum"]))->count(),
                3 => $connection->run("SELECT cod FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
                    "iii", array($pers["cod"], $skills[3][$linha]["cod_skil"], $skills[3][$linha]["tiponum"]))->count()
            ); ?>
            <?php for ($categoria = 1; $categoria <= 3; $categoria++): ?>
                <div class="col-xs-4" style="padding: 0;">
                    <?php render_one_skill_info($skills[$categoria][$linha], $pers, $form_url, $pode_aprender_func, $aprendidas) ?>
                </div>
            <?php endfor; ?>
        </div>
    <?php endforeach; ?>
<?php } ?>
<?php function render_one_skill_info($skill, $pers, $form_url, $pode_aprender_func, $aprendidas) { ?>
    <?php
    $aprendida_linha = false;
    foreach ($aprendidas as $aprendida) {
        if ($aprendida) {
            $aprendida_linha = true;
            break;
        }
    }
    ?>
    <div class="panel panel-default"
         style="<?= $aprendida_linha && !$aprendidas[$skill["categoria"]] ? "opacity: 0.2" : "" ?>">
        <div class="panel-heading">
            <?= $skill["tipo"] ?> <img src="Imagens/Skils/Tipo/<?= $skill["tipo"] ?>.png">
            <?php if ($skill["requisito_lvl"] <= $pers["lvl"] && !$aprendidas[$skill["categoria"]]): ?>
                <?= get_alert() ?>
            <?php endif; ?>
        </div>
        <div class="panel-body">
            <div>
                <h5>Requisitos:</h5>
                <?php render_skill_requisitos($skill, $pers) ?>
            </div>
            <div class="visible-xs visible-sm">
                <button class="btn btn-info"
                        data-toggle="popover" data-html="true" data-placement="bottom" data-trigger="focus"
                        data-content='<div style="min-width: 250px"><?php render_skill_efeitos($skill) ?></div>'>
                    Efeitos
                </button>
            </div>
            <div class="hidden-xs hidden-sm text-left">
                <h5>Efeitos:</h5>
                <?php render_skill_efeitos($skill) ?>
            </div>
            <div>
                <?php if ($aprendidas[$skill["categoria"]]): ?>
                    <p class="text-success">Habilidade aprendida!</p>
                    <button class="btn btn-primary link_confirm" <?= $pers["selos_xp"] ? "" : "disabled"; ?>
                            href="Vip/remover_habilidade.php?cod=<?= $pers["cod"] ?>&codskill=<?= $skill["cod_skil"] ?>&tiposkill=<?= $skill["tiponum"] ?>"
                            data-question="Tem certeza que deseja gastar 1 Selo de Experiência para remover essa habilidade?">
                        Remover por 1 <img src="Imagens/Icones/seloexp.png"/>
                    </button>
                <?php elseif ($aprendida_linha): ?>
                    <p>Habilidade indisponível</p>
                <?php else: ?>
                    <?php render_new_skill_form($skill, $pers, $form_url, $pode_aprender_func, "Escolher", true) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php function render_habilidades_tab($skills, $pers, $form_url, $pode_aprender_func) { ?>
    <?php global $connection; ?>
    <?php foreach ($skills as $skill): ?>
        <?php
        $result = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
            "iii", array($pers["cod"], $skill["cod_skil"], $skill["tiponum"]));
        if (!$result->count()): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $skill["tipo"] ?>
                </div>
                <div class="panel-body">
                    <div class="col-md-6 text-left">
                        <h5>Requisitos:</h5>
                        <?php render_skill_requisitos($skill, $pers) ?>
                    </div>
                    <div class="col-md-6 text-left">
                        <h5>Efeitos:</h5>
                        <?php render_skill_efeitos($skill) ?>
                    </div>
                    <div class="text-left">
                        <?php render_new_skill_form($skill, $pers, $form_url, $pode_aprender_func) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php } ?>
<?php function render_skill_requisitos($skill, $pers) { ?>
    <?php global $userDetails; ?>
    <div>
        <?php if (isset($skill["requisito_prof"]) && $skill["requisito_prof"]): ?>
            <?php if (isset($skill["requisito_lvl"])): ?>
                <div class="<?= $pers["profissao"] == $skill["requisito_prof"] && $pers["profissao_lvl"] >= $skill["requisito_lvl"] ? "text-success text-line-through" : "" ?>">
                    <?= nome_prof($skill["requisito_prof"]) ?> Nível: <strong><?= $skill["requisito_lvl"]; ?></strong>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php if (isset($skill["requisito_lvl"])): ?>
                <div class="<?= $pers["lvl"] >= $skill["requisito_lvl"] ? "text-success text-line-through" : "" ?>">
                    Nível: <strong><?= $skill["requisito_lvl"]; ?></strong>
                </div>
            <?php endif; ?>
            <?php if (isset($skill["lvl"])): ?>
                <div class="<?= $pers["lvl"] >= $skill["lvl"] ? "text-success text-line-through" : "" ?>">
                    Nível: <strong><?= $skill["lvl"]; ?></strong>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (isset($skill["requisito_atr_1"]) && $skill["requisito_atr_1"]): ?>
            <div class="<?= $pers[nome_atributo_tabela($skill["requisito_atr_1"])] >= $skill["requisito_atr_1_qnt"] ? "text-success text-line-through" : "" ?>">
                <img src="Imagens/Icones/<?= nome_atributo_img($skill["requisito_atr_1"]); ?>.png"
                     width="15px"/>
                <?= nome_atributo($skill["requisito_atr_1"]) ?>:
                <strong><?= $skill["requisito_atr_1_qnt"]; ?></strong>
            </div>
        <?php endif; ?>
        <?php if (isset($skill["requisito_berries"])): ?>
            <div class="<?= $userDetails->tripulacao["berries"] >= $skill["requisito_berries"] ? "text-success text-line-through" : "" ?>">
                <img src="Imagens/Icones/Berries.png"/>
                <?= $skill["requisito_berries"]; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($skill["requisito_maestria"]) && $skill["requisito_maestria"]): ?>
            <div class="<?= $pers["maestria"] >= $skill["requisito_maestria"] ? "text-success text-line-through" : "" ?>">
                Maestria: <strong><?= $skill["requisito_maestria"]; ?></strong>
            </div>
        <?php endif; ?>
    </div>
<?php } ?>
<?php function render_skill_efeitos($skill) { ?>
    <ul>
        <?php if (!empty($skill["dano"])): ?>
            <li>Dano: <?= $skill["dano"] * 10 ?></li>
        <?php endif; ?>
        <?php if (!empty($skill["bonus_atr"])): ?>
            <li>
                Bonus: <?= nome_atributo($skill["bonus_atr"]) ?> <?= $skill["bonus_atr_qnt"] > 0 ? "+" : "" ?><?= $skill["bonus_atr_qnt"] ?></li>
        <?php endif; ?>
        <?php if (!empty($skill["duracao"])): ?>
            <li>Duração: <?= $skill["duracao"] ?> turno(s)</li>
        <?php endif; ?>
        <?php if (!empty($skill["consumo"])): ?>
            <li>Consumo: <?= $skill["consumo"] ?></li>
        <?php endif; ?>
        <?php if (!empty($skill["alcance"])): ?>
            <li>Alcance: <?= $skill["alcance"] ?> quadro(s)</li>
        <?php endif; ?>
        <?php if (!empty($skill["area"])): ?>
            <li>Área de efeito: <?= $skill["area"] ?> quadro(s)</li>
        <?php endif; ?>
        <?php if (!empty($skill["espera"])): ?>
            <li>Espera: <?= $skill["espera"] ?> turno(s)</li>
        <?php endif; ?>
    </ul>
<?php } ?>
<?php function render_skill_efeitos_resumidos($skill) { ?>
    <ul class="text-left">
        <?php if (!empty($skill["dano"])): ?>
            <li>Dano <?= $skill["dano"] * 10 ?></li>
        <?php endif; ?>
        <?php if (!empty($skill["bonus_atr"])): ?>
            <li><?= nome_atributo($skill["bonus_atr"]) ?> <?= $skill["bonus_atr_qnt"] > 0 ? "+" : "" ?><?= $skill["bonus_atr_qnt"] ?></li>
        <?php endif; ?>
        <?php if (!empty($skill["alcance"]) && $skill["alcance"] > 1): ?>
            <li> Alcance <?= $skill["alcance"] ?></li>
        <?php endif; ?>
        <?php if (!empty($skill["area"]) && $skill["area"] > 1): ?>
            <li>Área <?= $skill["area"] ?></li>
        <?php endif; ?>
    </ul>
<?php } ?>
<?php function render_new_skill_form($skill, $pers, $form_url, $pode_aprender_func, $submit_button_text = "Aprender", $confirm = false) { ?>
    <?php if ($pode_aprender_func($pers, $skill)): ?>
        <button class="btn btn-success link_<?= $confirm ? "confirm" : "send" ?>"
                data-question="Deseja aprender essa habilidade?"
                href="<?= $confirm ? "" : "link_" ?><?= $form_url ?>?cod=<?= $pers["cod"]; ?>&codskill=<?= $skill["cod_skil"]; ?>&tiposkill=<?= $skill["tiponum"]; ?>">
            <?= $submit_button_text ?>
        </button>
    <?php endif; ?>
<?php } ?>
<?php function render_new_skill_form_2($skill, $pers, $form_url, $pode_aprender_func) { ?>
    <?php if ($pode_aprender_func($pers, $skill)): ?>
        <?php $img_id = $skill["tiponum"] . "_" . $skill["cod_skil"] . "_" . $pers["cod"]; ?>
        <?php $form_id = $pers["cod"] . "-" . $skill["cod_skil"] . "-" . $skill["tiponum"]; ?>
        <script type="text/javascript">
            $(function () {
                $('#form-aprender-skill-<?= $form_id ?>').on('submit', function (e) {
                    var img = $('#input_img_<?= $img_id ?>').val();
                    if (!img.length || img == 0) {
                        e.preventDefault();
                        bootbox.alert('Selecione uma imagem para sua habilidade.');
                    }
                });
            });
        </script>
        <form id="form-aprender-skill-<?= $form_id ?>" method="POST" action="<?= $form_url ?>">
            <h3>Aprender Habilidade</h3>

            <input name="codpers" type="hidden" value="<?= $pers["cod"]; ?>">
            <input name="codskil" type="hidden" value="<?= $skill["cod_skil"]; ?>">
            <input name="tiposkil" type="hidden" value="<?= $skill["tiponum"] ?>">

            <?php render_skill_selecao_img($img_id) ?>

            <?php $habilidade = habilidade_random(); ?>
            <div class="form-group">
                <label>Nome da habilidade</label>
                <input name="nome" size="10" maxlength="20" class="form-control" value="<?= $habilidade["nome"] ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Descrição da habilidade</label>
                <textarea cols="18" name="descricao" class="form-control"
                          required><?= $habilidade["descricao"] ?></textarea>
            </div>

            <button class="noHref btn btn-info"
                    onclick="window.open('Scripts/habilidade_random.php','Sugoi Game - Sugestão de habilidade','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=200');">
                Sugestão de habilidade
            </button>
            <button class="btn btn-success" type="submit">Aprender</button>
        </form>
    <?php endif; ?>
<?php } ?>
<?php function render_skill_selecao_img($img_id) { ?>
    <input name="img" type="hidden" value="0" id="input_img_<?= $img_id ?>" required>

    <label>Selecione uma imagem:</label>
    <img width="40px" id="img_<?= $img_id ?>" src="Imagens/Skils/0.jpg"
         onclick="mostraimgs('img_skil_<?= $img_id ?>');"/>

    <span class="selecao_img" style="display: none" id="img_skil_<?= $img_id ?>">
    <?php for ($z = 1; $z <= SKILLS_ICONS_MAX; $z++) : ?>
        <img width="50px" src="Imagens/Skils/<?= $z ?>.jpg"
             onclick="selectimg('<?= $z ?>', 'img_<?= $img_id ?>','input_img_<?= $img_id ?>', 'img_skil_<?= $img_id ?>')"/>
    <?php endfor; ?>
</span>
<?php } ?>