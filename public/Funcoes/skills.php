<?php

function get_skill_table($tipo)
{
    switch ($tipo) {
        case TIPO_SKILL_ATAQUE_CLASSE:
        case TIPO_SKILL_ATAQUE_PROFISSAO:
            return "skil_atk";
        case TIPO_SKILL_BUFF_CLASSE:
        case TIPO_SKILL_BUFF_PROFISSAO:
            return "skil_buff";
        case TIPO_SKILL_ATAQUE_AKUMA:
        case TIPO_SKILL_BUFF_AKUMA:
            return "skil_akuma";
        default:
            return null;
    }
}

function get_random_skill_with_level($lvl)
{
    $habilidades_validas = MapLoader::filter("skil_atk", function ($habilidade) use ($lvl) {
        return $habilidade['requisito_lvl'] <= $lvl;
    });
    return $habilidades_validas[rand(0, (sizeof($habilidades_validas) - 1))];
}

function get_all_skills($pers)
{
    global $connection;
    $skills = $connection->run(
        "SELECT * FROM tb_personagens_skil WHERE cod = ? and tipo IN (1, 2, 4, 5, 7, 8)",
        "i", $pers["cod"]
    )->fetch_all_array();

    foreach ($skills as $key => $skill) {
        $tb = get_skill_table($skill["tipo"]);
        $details = MapLoader::find($tb, ["cod_skil" => $skill["cod_skil"]]) ?? [];
        $skills[$key] = array_merge($skill, $details);
    }

    usort($skills, function ($a, $b) {
        if ($a["tipo"] < $b["tipo"]) {
            return -1;
        } elseif ($a["tipo"] > $b["tipo"]) {
            return 1;
        }
        return $a["consumo"] - $b["consumo"];
    });

    return $skills;
}

function has_animacao($skill)
{
    return $skill["tipo"] != TIPO_SKILL_PASSIVA_AKUMA
        && $skill["tipo"] != TIPO_SKILL_PASSIVA_CLASSE
        && $skill["tipo"] != TIPO_SKILL_PASSIVA_PROFISSAO;
}

function is_editavel($skill)
{
    global $COD_HAOSHOKU_LVL;
    return ($skill["tipo"] != TIPO_SKILL_ATAQUE_CLASSE || $skill["cod_skil"] != 1)
        && ($skill["tipo"] != TIPO_SKILL_ATAQUE_CLASSE || ! in_array($skill["cod_skil"], $COD_HAOSHOKU_LVL));
}

function nome_tipo_skill($skill)
{
    switch ($skill["tipo"]) {
        case TIPO_SKILL_ATAQUE_CLASSE:
        case TIPO_SKILL_ATAQUE_PROFISSAO:
        case TIPO_SKILL_ATAQUE_AKUMA:
            return "Ataque";
        case TIPO_SKILL_BUFF_CLASSE:
        case TIPO_SKILL_BUFF_PROFISSAO:
        case TIPO_SKILL_BUFF_AKUMA:
            return "Buff";
        case TIPO_SKILL_PASSIVA_CLASSE:
        case TIPO_SKILL_PASSIVA_PROFISSAO:
        case TIPO_SKILL_PASSIVA_AKUMA:
            return "Passiva";
        default:
            return $skill["tipo"];
    }
}

function nome_origem_skill($skill)
{
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

function nome_special_effect($effect)
{
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

function duracao_special_effect($effect)
{
    switch ($effect) {
        case SPECIAL_EFFECT_SANGRAMENTO:
            return 3;
        case SPECIAL_EFFECT_VENENO:
            return 6;
        case SPECIAL_EFFECT_MACHUCADO_JOELHO:
            return 1;
        case SPECIAL_EFFECT_PONTO_FRACO:
            return 1;
        default:
            return 0;
    }
}

function descricao_special_effect($effect, $vontade)
{
    switch ($effect) {
        case SPECIAL_EFFECT_SANGRAMENTO:
            return "O personagem que sofre desse efeito perde " . dano_special_effect($effect, $vontade) . "% da vida máxima a cada turno durante " . duracao_special_effect($effect) . " turnos até ficar com 1 ponto de vida.";
        case SPECIAL_EFFECT_VENENO:
            return "O personagem que sofre desse efeito perde " . dano_special_effect($effect, $vontade) . "% da vida máxima a cada turno durante " . duracao_special_effect($effect) . " turnos até ficar com 1 ponto de vida.";
        case SPECIAL_EFFECT_MACHUCADO_JOELHO:
            return "O personagem que sofre desse efeito não pode se movimentar por " . duracao_special_effect($effect) . " turnos";
        case SPECIAL_EFFECT_PONTO_FRACO:
            return "O golpe que acerta o ponto fraco ignora 50% da defesa do adversário. (Esse efeito só funciona no Coliseu e em disputas amigáveis)";
        default:
            return "";
    }
}

function dano_special_effect($effect, $vontade)
{
    switch ($effect) {
        case SPECIAL_EFFECT_SANGRAMENTO:
            return round($vontade / 4.0 + 2.0, 2);
        case SPECIAL_EFFECT_VENENO:
            return round($vontade / 8.0 + 2.0, 2);
        case SPECIAL_EFFECT_MACHUCADO_JOELHO:
            return 0;
        case SPECIAL_EFFECT_PONTO_FRACO:
            return 0;
        default:
            return 0;
    }
}

function nome_special_effect_target($target)
{
    switch ($target) {
        case SPECIAL_TARGET_SELF:
            return "Emissor";
        case SPECIAL_TARGET_TARGET:
            return "Alvo";
        default:
            return "";
    }
}

function nome_special_effect_apply_type($target)
{
    switch ($target) {
        case SPECIAL_APPLY_TYPE_APPLY:
            return "Aplica o efeito";
        case SPECIAL_APPLY_TYPE_REMOVE:
            return "Remove uma aplicação e deixa imune por 1 turno ao efeito";
        default:
            return "";
    }
}

function get_basic_skills($filter_column, $filter_value, $tipo_base = 0, $maestria = 0)
{
    global $connection;
    $skills = [];

    $result = MapLoader::filter("skil_atk",
        function ($item) use ($filter_column, $filter_value, $maestria) {
            return $item[$filter_column] == $filter_value && $item["maestria"] == $maestria;
        });

    foreach ($result as $skill) {
        $skill["tipo"] = "Ataque";
        $skill["tiponum"] = $tipo_base + 1;
        $skills[] = $skill;
    }

    $result = MapLoader::filter("skil_buff",
        function ($item) use ($filter_column, $filter_value, $maestria) {
            return $item[$filter_column] == $filter_value && $item["maestria"] == $maestria;
        });

    foreach ($result as $skill) {
        $skill["tipo"] = "Buff";
        $skill["tiponum"] = $tipo_base + 2;
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

function aprende_habilidade_random($pers, $cod_skill, $tipo_skill)
{
    global $connection;

    $habilidade = habilidade_random();
    $icon = rand(1, SKILLS_ICONS_MAX);

    $connection->run("INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon) VALUE (?,?,?,?,?,?)",
        "iiissi", array($pers["cod"], $cod_skill, $tipo_skill, $habilidade["nome"], $habilidade["descricao"], $icon));
}
?>
<?php function render_habilidades_classe_tab($skills, $pers, $form_url, $pode_aprender_func, $lvls = array(1, 5, 10, 20, 30, 40, 50), $categorias = 3)
{ ?>
    <?php global $connection; ?>
    <?php $categorias_size = [1 => 12, 2 => 6, 3 => 4, 4 => 3] ?>
    <?php foreach ($lvls as $linha => $lvl) : ?>
        <div class="panel panel-default p0">
            <div class="panel-heading">
                Habilidades de Nível
                <?= $lvl ?>
            </div>
            <div class="row panel-body py0">
                <?php $aprendidas = array(
                    1 => $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
                        "iii", array($pers["cod"], $skills[1][$linha]["cod_skil"], $skills[1][$linha]["tiponum"]))->fetch_array(),
                    2 => $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
                        "iii", array($pers["cod"], $skills[2][$linha]["cod_skil"], $skills[2][$linha]["tiponum"]))->fetch_array(),
                    3 => $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
                        "iii", array($pers["cod"], $skills[3][$linha]["cod_skil"], $skills[3][$linha]["tiponum"]))->fetch_array()
                ); ?>
                <?php for ($categoria = 1; $categoria <= $categorias; $categoria++) : ?>
                    <div class="col-xs-<?= $categorias_size[$categorias] ?> p0">
                        <?php render_one_skill_info($skills[$categoria][$linha], $pers, $form_url, $pode_aprender_func, $aprendidas, false) ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php } ?>
<?php function render_one_skill_info($skill, $pers, $form_url, $pode_aprender_func, $aprendidas = [], $requisitos = true)
{ ?>
    <?php
    $aprendida_linha = false;
    foreach ($aprendidas as $aprendida) {
        if ($aprendida) {
            $aprendida_linha = $aprendida;
            break;
        }
    }

    $icone = $aprendida_linha ? $aprendida_linha["icon"] : $skill["icone_padrao"];
    ?>
    <div class="panel panel-default m0 h-100"
        style="<?= $aprendida_linha && ! $aprendidas[$skill["categoria"]] ? "opacity: 0.2" : "" ?>">
        <div class="panel-body pt1 pb0">
            <div class="d-flex justify-content-center align-items-center mb">
                <?php if ($icone) : ?>
                    <div class="mr">
                        <img src="Imagens/Skils/<?= $icone ?>.jpg" width="40vw">
                    </div>
                <?php endif; ?>
                <div>
                    <?= nome_tipo_skill($skill) ?> <img src="Imagens/Skils/Tipo/<?= nome_tipo_skill($skill) ?>.png"
                        width="15vw">
                </div>
            </div>
            <?php if ($requisitos) : ?>
                <p>
                    <?php render_skill_requisitos($skill, $pers) ?>
                </p>
            <?php endif; ?>
            <div class="text-left">
                <?php render_skill_efeitos($skill) ?>
            </div>
        </div>
        <div class="panel-footer">
            <?php if ($aprendidas[$skill["categoria"]]) : ?>
                <button class="btn btn-danger link_send"
                    href="Link_Vip/remover_habilidade.php?cod=<?= $pers["cod"] ?>&codskill=<?= $skill["cod_skil"] ?>&tiposkill=<?= $skill["tiponum"] ?>">
                    Remover
                </button>
            <?php else : ?>
                <?php render_new_skill_form($skill, $pers, $form_url, $pode_aprender_func, "Escolher") ?>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>
<?php function render_habilidades_tab($skills, $pers, $form_url, $pode_aprender_func)
{ ?>
    <?php global $connection; ?>
    <?php foreach ($skills as $skill) : ?>
        <?php
        $result = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
            "iii", array($pers["cod"], $skill["cod_skil"], $skill["tiponum"]));
        if (! $result->count()) : ?>
            <div class="panel panel-default col-xs-4 h-100 m-0">
                <div class="panel-body">
                    <?php if (isset($skill["icone_padrao"])) : ?>
                        <div class="mr">
                            <img src="Imagens/Skils/<?= $skill["icone_padrao"] ?>.jpg" width="40vw">
                        </div>
                    <?php endif; ?>
                    <div>
                        <?= $skill["tipo"] ?> <img src="Imagens/Skils/Tipo/<?= $skill["tipo"] ?>.png" width="15vw">
                    </div>
                    <div class="text-left">
                        <?php render_skill_efeitos($skill) ?>
                    </div>
                    <div>
                        <div>Requisitos:</div>
                        <?php render_skill_requisitos($skill, $pers) ?>
                    </div>
                </div>
                <div class="panel-footer">
                    <?php render_new_skill_form($skill, $pers, $form_url, $pode_aprender_func) ?>
                    <?php if (! $pode_aprender_func($pers, $skill)) : ?>
                        <button class="btn btn-default" disabled>
                            Indisponível
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php } ?>
<?php function render_skill_requisitos($skill, $pers)
{ ?>
    <?php global $userDetails; ?>
    <div>
        <?php if (isset($skill["requisito_prof"]) && $skill["requisito_prof"]) : ?>
            <?php if (isset($skill["requisito_lvl"])) : ?>
                <div
                    class="<?= $pers["profissao"] == $skill["requisito_prof"] && $pers["profissao_lvl"] >= $skill["requisito_lvl"] ? "text-success text-line-through" : "" ?>">
                    <?= nome_prof($skill["requisito_prof"]) ?> Nível: <strong>
                        <?= $skill["requisito_lvl"]; ?>
                    </strong>
                </div>
            <?php endif; ?>
            <?php if (isset($skill["requisito_berries"])) : ?>
                <div
                    class="<?= $userDetails->tripulacao["berries"] >= $skill["requisito_berries"] ? "text-success text-line-through" : "" ?>">
                    <img src="Imagens/Icones/Berries.png" width="15vw" />
                    <strong>
                        <?= mascara_berries($skill["requisito_berries"]); ?>
                    </strong>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <?php if (isset($skill["requisito_lvl"])) : ?>
                <div class="<?= $pers["lvl"] >= $skill["requisito_lvl"] ? "text-success text-line-through" : "" ?>">
                    Nível: <strong>
                        <?= $skill["requisito_lvl"]; ?>
                    </strong>
                </div>
            <?php endif; ?>
            <?php if (isset($skill["lvl"])) : ?>
                <div class="<?= $pers["lvl"] >= $skill["lvl"] ? "text-success text-line-through" : "" ?>">
                    Nível: <strong>
                        <?= $skill["lvl"]; ?>
                    </strong>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php } ?>
<?php function render_skill_efeitos($skill)
{ ?>
    <ul>
        <?php if (! empty($skill["dano"])) : ?>
            <li>
                Dano:
                <?= $skill["dano"] * 100 ?>%
                <?= ajuda_tooltip("Causa dano equivalente à " . ($skill["dano"] * 100) . "% dos pontos de Ataque do tripulante. Garante um dano mínimo de " . ($skill["dano"] * 400) . " pontos de dano.") ?>
            </li>
        <?php endif; ?>
        <?php if (! empty($skill["bonus_atr"])) : ?>
            <li>
                <img src="Imagens/Icones/<?= nome_atributo_img($skill["bonus_atr"]) ?>.png" height="12vw"
                    style="background: black" />
                <?= nome_atributo($skill["bonus_atr"]) ?>
                <?= $skill["bonus_atr_qnt"] > 0 ? "+" : "" ?>
                <?= $skill["bonus_atr_qnt"] ?>
            </li>
        <?php endif; ?>
        <?php if (! empty($skill["duracao"])) : ?>
            <li>Duração:
                <?= $skill["duracao"] ?> turno(s)
            </li>
        <?php endif; ?>
        <?php if (! empty($skill["consumo"])) : ?>
            <li>
                Vontade:
                <?= $skill["consumo"] ?>
                <img src="Imagens/Icones/vontade.png" height="12vw" />
            </li>
        <?php endif; ?>
        <?php if ($skill["alcance"] == 0) : ?>
            <li>
                Área de efeito:
                Em si mesmo
            </li>
        <?php else : ?>
            <?php if (! empty($skill["alcance"])) : ?>
                <li>
                    Alcance:
                    <?= $skill["alcance"] ?> quadro(s)
                </li>
            <?php endif; ?>
            <?php if (! empty($skill["area"])) : ?>
                <li>Área de efeito:
                    <?= $skill["area"] ?> quadro(s)
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (! empty($skill["espera"])) : ?>
            <li>Espera:
                <?= $skill["espera"] ?> turno(s)
            </li>
        <?php endif; ?>
        <?php if (isset($skill["special_effect"])) : ?>
            <li>
                <?= nome_special_effect_apply_type($skill["special_apply_type"]) ?>
                <?= nome_special_effect($skill["special_effect"]) ?>
                no
                <?= nome_special_effect_target($skill["special_target"]) ?> da Habilidade
                <?= ajuda_tooltip(
                    nome_special_effect($skill["special_effect"]) . ":" .
                    descricao_special_effect($skill["special_effect"], $skill["consumo"])
                ) ?>
            </li>
        <?php endif; ?>
    </ul>
<?php } ?>
<?php function render_skill_efeitos_resumidos($skill)
{ ?>
    <ul class="text-left">
        <?php if (! empty($skill["dano"])) : ?>
            <li>Dano
                <?= $skill["dano"] * 10 ?>
            </li>
        <?php endif; ?>
        <?php if (! empty($skill["bonus_atr"])) : ?>
            <li>
                <?= nome_atributo($skill["bonus_atr"]) ?>
                <?= $skill["bonus_atr_qnt"] > 0 ? "+" : "" ?>
                <?= $skill["bonus_atr_qnt"] ?>
            </li>
        <?php endif; ?>
        <?php if (! empty($skill["alcance"]) && $skill["alcance"] > 1) : ?>
            <li> Alcance
                <?= $skill["alcance"] ?>
            </li>
        <?php endif; ?>
        <?php if (! empty($skill["area"]) && $skill["area"] > 1) : ?>
            <li>Área
                <?= $skill["area"] ?>
            </li>
        <?php endif; ?>
    </ul>
<?php } ?>
<?php function render_new_skill_form($skill, $pers, $form_url, $pode_aprender_func, $submit_button_text = "Aprender", $confirm = false)
{ ?>
    <?php if ($pode_aprender_func($pers, $skill)) : ?>
        <button class="btn btn-success btn_aprender_skill link_<?= $confirm ? "confirm" : "send" ?>"
            data-question="Deseja aprender essa habilidade?"
            href="<?= $confirm ? "" : "link_" ?><?= $form_url ?>?cod=<?= $pers["cod"]; ?>&codskill=<?= $skill["cod_skil"]; ?>&tiposkill=<?= $skill["tiponum"]; ?>">
            <?= $submit_button_text ?>
        </button>
    <?php endif; ?>
<?php } ?>
<?php function render_new_skill_form_2($skill, $pers, $form_url, $pode_aprender_func)
{ ?>
    <?php if ($pode_aprender_func($pers, $skill)) : ?>
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
                <input name="nome" size="10" maxlength="20" class="form-control" value="<?= $habilidade["nome"] ?>" required>
            </div>

            <div class="form-group">
                <label>Descrição da habilidade</label>
                <textarea cols="18" name="descricao" class="form-control" required><?= $habilidade["descricao"] ?></textarea>
            </div>

            <button class="noHref btn btn-info"
                onclick="window.open('Scripts/habilidade_random.php','Sugoi Game - Sugestão de habilidade','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=200');">
                Sugestão de habilidade
            </button>
            <button class="btn btn-success" type="submit">Aprender</button>
        </form>
    <?php endif; ?>
<?php } ?>
<?php function render_skill_selecao_img($img_id)
{ ?>
    <input name="img" type="hidden" value="0" id="input_img_<?= $img_id ?>" required>

    <label>Selecione uma imagem:</label>
    <img width="40px" id="img_<?= $img_id ?>" src="Imagens/Skils/0.jpg" onclick="mostraimgs('img_skil_<?= $img_id ?>');" />

    <span class="selecao_img" style="display: none" id="img_skil_<?= $img_id ?>">
        <?php for ($z = 1; $z <= SKILLS_ICONS_MAX; $z++) : ?>
            <img width="50px" src="Imagens/Skils/<?= $z ?>.jpg"
                onclick="selectimg('<?= $z ?>', 'img_<?= $img_id ?>','input_img_<?= $img_id ?>', 'img_skil_<?= $img_id ?>')" />
        <?php endfor; ?>
    </span>
<?php } ?>

