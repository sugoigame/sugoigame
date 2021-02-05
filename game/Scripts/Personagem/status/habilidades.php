<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>

<?php $animacoes = $connection->run(
    "SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ?",
    "i", array($userDetails->tripulacao["id"])
)->fetch_all_array() ?>

<?php render_personagem_panel_top($pers, 0) ?>
<?php $skills = get_many_results_joined_mapped_by_type("tb_personagens_skil", "cod_skil", "tipo", array(
    array("nome" => "tb_skil_atk", "coluna" => "cod_skil", "tipo" => 1),
    array("nome" => "tb_skil_buff", "coluna" => "cod_skil", "tipo" => 2),
    array("nome" => "tb_skil_passiva", "coluna" => "cod_skil", "tipo" => 3),
    array("nome" => "tb_skil_atk", "coluna" => "cod_skil", "tipo" => 4),
    array("nome" => "tb_skil_buff", "coluna" => "cod_skil", "tipo" => 5),
    array("nome" => "tb_skil_passiva", "coluna" => "cod_skil", "tipo" => 6),
    array("nome" => "tb_akuma_skil_atk", "coluna" => "cod_skil", "tipo" => 7),
    array("nome" => "tb_akuma_skil_buff", "coluna" => "cod_skil", "tipo" => 8),
    array("nome" => "tb_akuma_skil_passiva", "coluna" => "cod_skil", "tipo" => 9)
), "WHERE cod = ? ORDER BY tipo", "i", $pers["cod"]) ?>
<?php $max_ataques_com_efeitos = 1; ?>
<?php $skills_com_efeitos = $connection->run("SELECT * FROM tb_personagens_skil WHERE special_effect IS NOT NULL AND cod = ?",
    "i", array($pers["cod"]))->count() ?>
<?php $ataques_com_efeitos = $connection->run("SELECT * FROM tb_personagens_skil WHERE special_effect IS NOT NULL AND cod = ? AND tipo = ?",
    "ii", array($pers["cod"], TIPO_SKILL_ATAQUE_AKUMA))->count() ?>
<h3>
    Efeitos especiais disponíveis: <?= $max_ataques_com_efeitos - $ataques_com_efeitos ?>
</h3>

<?php if (count($animacoes)): ?>
    <h3>Animações disponíveis:</h3>

    <div class="row">
        <?php foreach ($animacoes as $animacao): ?>
            <div class="col-md-3">
                <button data-effect="<?= $animacao["effect"] ?>" class="play-effect btn btn-primary">
                    <?= $animacao["effect"] ?> x<?= $animacao["quant"] ?> <i class="fa fa-play"></i>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $ultimo_tipo = null; ?>
<div class="row">
    <?php foreach ($skills as $skill): ?>
        <?php if ($skill["tipo"] != $ultimo_tipo): ?>
            <div class="col-xs-12">
                <h3>
                    <?= nome_tipo_skill($skill) ?> de <?= nome_origem_skill($skill) ?>
                    <img src="Imagens/Skils/Tipo/<?= nome_tipo_skill($skill) ?>.png"/>
                </h3>
                <?php $ultimo_tipo = $skill["tipo"]; ?>
            </div>
        <?php endif; ?>
        <div class="list-group-item col-sm-2">
            <a href="#" class="noHref" data-toggle="popover" data-html="true" data-placement="bottom"
               data-trigger="focus"
               data-content="<div style='min-width:250px;'><p><?= str_replace(array('"', "'"), array("&quot;", "&lsquo;"), htmlspecialchars($skill["descricao"])) ?></p><?php render_skill_efeitos($skill) ?></div>">
                <img src="Imagens/Skils/<?= $skill["icon"] ?>.jpg">
                <h4>
                    <?= $skill["nome"] ?>
                </h4>
            </a>
            <?php if (isset($skill["maestria"]) && $skill["maestria"]): ?>
                <small>Maestria</small>
            <?php endif; ?>
            <p>
                <?php render_skill_efeitos_resumidos($skill); ?>
            </p>
            <?php if (has_animacao($skill)): ?>
                <p>
                    <button data-effect="<?= $skill["effect"] ?>" class="play-effect btn btn-primary">
                        <?= $skill["effect"] ?> <i class="fa fa-play"></i>
                    </button>
                </p>
            <?php endif; ?>
            <?php if (has_special_effect($skill, $skills_com_efeitos, $ataques_com_efeitos, $max_ataques_com_efeitos)): ?>
                <?php if ($skill["special_effect"]): ?>
                    <h5>
                        <?= nome_special_effect_apply_type($skill["special_apply_type"]) ?>
                        <?= nome_special_effect($skill["special_effect"]) ?>
                        no <?= nome_special_effect_target($skill["special_target"]) ?> da
                        Habilidade
                    </h5>
                <?php else: ?>
                    <h5 class="text-warning">
                        Efeito especial disponível!
                        <?= $userDetails->alerts->get_alert() ?>
                    </h5>
                <?php endif; ?>
            <?php endif; ?>
            <?php $img_id = $skill["tipo"] . "_" . $skill["cod_skil"] . "_" . $pers["cod"]; ?>
            <script type="text/javascript">
                $(function () {
                    $('#customiza-<?= $img_id ?>').click(function () {
                        $('#modal-edit-skill-<?= $img_id ?>').modal();
                    });
                });
            </script>
            <p>
                <button id="customiza-<?= $img_id ?>" class="btn btn-success">
                    Customizar
                </button>
            </p>
            <div class="modal fade" id="modal-edit-skill-<?= $img_id ?>">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                Customizar Habilidade
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <?php if (is_editavel($skill)): ?>
                                    <div class="col-md-6">
                                        <form action="Vip/customiza_skill" class="ajax_form"
                                              onsubmit="$('#modal-edit-skill-<?= $img_id ?>').modal('hide');"
                                              data-question="Apenas a primeira customização de cada habilidade é gratuita. Deseja continuar?"
                                              id="form-custumza-skill" method="POST">
                                            <input value="<?= $pers["cod"] ?>" name="codpers" type="hidden">
                                            <input value="<?= $skill["cod_skil"] ?>" name="codskil" type="hidden">
                                            <input value="<?= $skill["tipo"] ?>" name="tiposkil" type="hidden">
                                            <input id="skill-input-<?= $img_id ?>" name="img" type="hidden"
                                                   value="<?= $skill["icon"] ?>"
                                                   required>

                                            <label>Selecione uma imagem:</label>
                                            <img width="40px"
                                                 id="skill-img-<?= $img_id ?>"
                                                 src="Imagens/Skils/<?= $skill["icon"] ?>.jpg"
                                                 onclick="geraImgsSkill('skill-list-<?= $img_id ?>','skill-input-<?= $img_id ?>','skill-img-<?= $img_id ?>', <?= SKILLS_ICONS_MAX ?>);"/>

                                            <div class="selecao_img" style="display: none"
                                                 id="skill-list-<?= $img_id ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Nome da habilidade</label>
                                                <input name="nome" size="10" maxlength="20" class="form-control"
                                                       value="<?= $skill["nome"] ?>"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label>Descrição da habilidade</label>
                                                <textarea cols="18" maxlength="300" name="descricao"
                                                          class="form-control"
                                                          required><?= $skill["descricao"] ?></textarea>
                                            </div>

                                            <?php if ($skill["editado"]): ?>
                                                <div class="form-group">
                                                    <label>
                                                        <input type="radio" name="tipo_pagamento" value="gold" required>
                                                        <?= PRECO_GOLD_CUSTOMIZAR_SKILL ?>
                                                        <img src="Imagens/Icones/Gold.png"/>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="tipo_pagamento" value="dobrao"
                                                               required>
                                                        <?= PRECO_DOBRAO_CUSTOMIZAR_SKILL ?>
                                                        <img src="Imagens/Icones/Dobrao.png"/>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                            <button class="btn btn-success" type="submit">
                                                Salvar nome, descrição e ícone
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                <?php if (has_animacao($skill)): ?>
                                    <div class="col-md-6">
                                        <h3>Animação ativa:</h3>
                                        <p>
                                            <button data-effect="<?= $skill["effect"] ?>"
                                                    class="play-effect btn btn-primary">
                                                <?= $skill["effect"] ?> <i class="fa fa-play"></i>
                                            </button>
                                        </p>
                                        <h4>Animações disponíveis:</h4>
                                        <?php if (count($animacoes)): ?>
                                            <form class="ajax_form" method="post"
                                                  action="Personagem/mudar_animacao_skill"
                                                  onsubmit="$('#modal-edit-skill-<?= $img_id ?>').modal('hide');"
                                                  data-question="Você consumirá uma unidade da animação para aplica-la a essa habilidade. Deseja continuar?">
                                                <input type="hidden" name="pers" value="<?= $pers["cod"] ?>"/>
                                                <input type="hidden" name="cod_skil"
                                                       value="<?= $skill["cod_skil"] ?>"/>
                                                <input type="hidden" name="tipo" value="<?= $skill["tipo"] ?>"/>
                                                <div class="form-group">
                                                    <select class="form-control" name="effect" required>
                                                        <?php foreach ($animacoes as $animacao): ?>
                                                            <option value="<?= $animacao["effect"] ?>">
                                                                <?= $animacao["effect"] ?> x<?= $animacao["quant"] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-info">
                                                    Mudar Animação
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (has_special_effect($skill, $skills_com_efeitos, $ataques_com_efeitos, $max_ataques_com_efeitos)): ?>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <h3>Efeito Especial:</h3>

                                            <?php if ($skill["special_effect"]): ?>
                                                <p>
                                                    <?= nome_special_effect_apply_type($skill["special_apply_type"]) ?>
                                                    <?= nome_special_effect($skill["special_effect"]) ?>
                                                    no <?= nome_special_effect_target($skill["special_target"]) ?> da
                                                    Habilidade
                                                </p>
                                                <p>
                                                    <?= nome_special_effect($skill["special_effect"]) ?>
                                                    : <?= descricao_special_effect($skill["special_effect"]) ?>
                                                </p>
                                                <button class="btn btn-danger link_confirm"
                                                        data-dismiss="modal"
                                                        data-question="Deseja remover o efeito especial dessa habilidade?"
                                                        href="Personagem/remover_efeito_especial.php?pers=<?= $pers["cod"] ?>&skill=<?= $skill["cod_skil"] ?>&tipo=<?= $skill["tipo"] ?>">
                                                    Remover
                                                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries(PRECO_BERRIES_REMOVER_SPECIAL_EFFECT) ?>
                                                </button>
                                            <?php else: ?>
                                                <form class="ajax_form" method="post"
                                                      onsubmit="$('#modal-edit-skill-<?= $img_id ?>').modal('hide');"
                                                      action="Personagem/salvar_efeito_especial">
                                                    <input type="hidden" name="pers" value="<?= $pers["cod"] ?>">
                                                    <input type="hidden" name="skill" value="<?= $skill["cod_skil"] ?>">
                                                    <input type="hidden" name="tipo" value="<?= $skill["tipo"] ?>">
                                                    <div class="form-group">
                                                        <label>Selecione a forma de uso do efeito:</label>
                                                        <select name="apply_type" class="form-control">
                                                            <option value="<?= SPECIAL_APPLY_TYPE_APPLY ?>">
                                                                <?= nome_special_effect_apply_type(SPECIAL_TARGET_SELF) ?>
                                                            </option>
                                                            <option value="<?= SPECIAL_APPLY_TYPE_REMOVE ?>">
                                                                <?= nome_special_effect_apply_type(SPECIAL_APPLY_TYPE_REMOVE) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Selecione um efeito:</label>
                                                        <select name="effect" class="form-control">
                                                            <option value="<?= SPECIAL_EFFECT_SANGRAMENTO ?>">
                                                                <?= nome_special_effect(SPECIAL_EFFECT_SANGRAMENTO) ?>
                                                                : <?= descricao_special_effect(SPECIAL_EFFECT_SANGRAMENTO) ?>
                                                            </option>
                                                            <option value="<?= SPECIAL_EFFECT_VENENO ?>">
                                                                <?= nome_special_effect(SPECIAL_EFFECT_VENENO) ?>
                                                                : <?= descricao_special_effect(SPECIAL_EFFECT_VENENO) ?>
                                                            </option>
                                                            <option value="<?= SPECIAL_EFFECT_MACHUCADO_JOELHO ?>">
                                                                <?= nome_special_effect(SPECIAL_EFFECT_MACHUCADO_JOELHO) ?>
                                                                : <?= descricao_special_effect(SPECIAL_EFFECT_MACHUCADO_JOELHO) ?>
                                                            </option>
                                                            <option value="<?= SPECIAL_EFFECT_PONTO_FRACO ?>">
                                                                <?= nome_special_effect(SPECIAL_EFFECT_PONTO_FRACO) ?>
                                                                : <?= descricao_special_effect(SPECIAL_EFFECT_PONTO_FRACO) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Selecione o alvo do efeito:</label>
                                                        <select name="target" class="form-control">
                                                            <option value="<?= SPECIAL_TARGET_TARGET ?>">
                                                                <?= nome_special_effect_target(SPECIAL_TARGET_TARGET) ?>
                                                                da
                                                                habilidade
                                                            </option>
                                                            <option value="<?= SPECIAL_TARGET_SELF ?>">
                                                                <?= nome_special_effect_target(SPECIAL_TARGET_SELF) ?>
                                                                da habilidade
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">
                                                        Salvar efeito especial
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (nome_origem_skill($skill) == "Akuma"): ?>
                                    <div class="col-md-6">
                                        <h3>Editar <?= nome_tipo_skill($skill) ?> de Akuma no Mi</h3>
                                        <form class="ajax_form" action="Akuma/editar_habilidade" method="post"
                                              onsubmit="$('#modal-edit-skill-<?= $img_id ?>').modal('hide');"
                                              data-question="Tem certeza que deseja gastar 1 Selo de Experiência para modificar essa habilidade?">
                                            <input type="hidden" name="cod" value="<?= $pers["cod"] ?>">
                                            <input type="hidden" name="codskill" value="<?= $skill["cod_skil"] ?>">
                                            <input type="hidden" name="tiposkill" value="<?= $skill["tipo"] ?>">
                                            <?php if ($skill["tipo"] == TIPO_SKILL_ATAQUE_AKUMA): ?>
                                                <div class="form-group">
                                                    <label>Alcance:</label>
                                                    <select name="alcance" class="form-control">
                                                        <?php for ($x = 1; $x <= 7; $x++): ?>
                                                            <option value="<?= $x ?>" <?= $skill["alcance"] == $x ? "selected" : "" ?>>
                                                                <?= $x ?> quadro(s)
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Área de efeito:</label>
                                                    <select name="area" class="form-control">
                                                        <?php for ($x = 1; $x <= 4; $x++): ?>
                                                            <?php if ($x == 3) continue; ?>
                                                            <option value="<?= $x ?>" <?= $skill["area"] == $x ? "selected" : "" ?>>
                                                                <?= $x ?> quadro(s)
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            <?php elseif ($skill["tipo"] == TIPO_SKILL_BUFF_AKUMA): ?>
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <select class="form-control" name="negativo">
                                                                <option value="0" <?= $skill["bonus_atr_qnt"] > 0 ? "selected" : "" ?>>
                                                                    +
                                                                </option>
                                                                <option value="1" <?= $skill["bonus_atr_qnt"] < 0 ? "selected" : "" ?>>
                                                                    -
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <div class="form-group">
                                                            <select class="form-control" name="atributo">
                                                                <?php for ($x = 1; $x <= 7; $x++): ?>
                                                                    <option value="<?= $x ?>" <?= $skill["bonus_atr"] == $x ? "selected" : "" ?>>
                                                                        <?= nome_atributo($x) ?>
                                                                    </option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alcance:</label>
                                                    <select name="alcance" class="form-control">
                                                        <?php for ($x = 1; $x <= 7; $x++): ?>
                                                            <option value="<?= $x ?>" <?= $skill["alcance"] == $x ? "selected" : "" ?>>
                                                                <?= $x ?> quadro(s)
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Área de efeito:</label>
                                                    <select name="area" class="form-control">
                                                        <?php for ($x = 1; $x <= 4; $x++): ?>
                                                            <?php if ($x == 3) continue; ?>
                                                            <option value="<?= $x ?>" <?= $skill["area"] == $x ? "selected" : "" ?>>
                                                                <?= $x ?> quadro(s)
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            <?php else: ?>
                                                <div class="form-group">
                                                    <select class="form-control" name="atributo">
                                                        <?php for ($x = 1; $x <= 7; $x++): ?>
                                                            <option value="<?= $x ?>" <?= $skill["bonus_atr"] == $x ? "selected" : "" ?>>
                                                                <?= nome_atributo($x) ?>
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                            <button class="btn btn-success" <?= $pers["selos_xp"] ? "" : "disabled"; ?>>
                                                Editar por 1 <img src="Imagens/Icones/seloexp.png"/>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            <button class="noHref btn btn-info"
                                    onclick="window.open('Scripts/habilidade_random.php','Sugoi Game - Sugestão de habilidade','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=200');">
                                Sugestão de habilidade
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php render_personagem_panel_bottom() ?>
